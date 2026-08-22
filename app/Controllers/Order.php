<?php

namespace App\Controllers;

use App\Models\GameModel;
use App\Models\OrderModel;
use App\Models\PaymentMethodModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductModel;
use App\Models\SettingModel;
use App\Models\UserModel;
use App\Models\VoucherModel;
use App\Services\Payment\QrisService;
use App\Services\Provider\ProviderService;
use App\Services\Validator\NicknameCheckerService;

class Order extends BaseController
{
    protected $gameModel;
    protected $productCategoryModel;
    protected $productModel;
    protected $paymentModel;
    protected $orderModel;
    protected $voucherModel;
    protected $settingModel;
    protected $userModel;
    protected $qrisService;
    protected $nicknameChecker;
    protected $providerService;

    public function __construct()
    {
        $this->gameModel            = new GameModel();
        $this->productCategoryModel = new ProductCategoryModel();
        $this->productModel         = new ProductModel();
        $this->paymentModel         = new PaymentMethodModel();
        $this->orderModel           = new OrderModel();
        $this->voucherModel         = new VoucherModel();
        $this->settingModel         = new SettingModel();
        $this->userModel            = new UserModel();
        $this->qrisService          = new QrisService();
        $this->nicknameChecker      = new NicknameCheckerService();
        $this->providerService      = new ProviderService();
    }

    public function detail(string $slug)
    {
        $game = $this->gameModel->where('slug', $slug)->where('is_active', 1)->first();
        if (!$game) {
            return redirect()->to('/')->with('error', 'Game tidak ditemukan atau sedang tidak aktif');
        }

        $productCategories = $this->productCategoryModel->where('game_id', $game['id'])->orderBy('sort_order', 'ASC')->findAll();
        $products          = $this->productModel->getProductsByGame($game['id']);
        $paymentMethods    = $this->paymentModel->getActiveMethods();
        $settings          = $this->settingModel->getAllSettings();

        // Check if user is logged in
        $userId   = session()->get('user_id');
        $userTier = 'basic';
        $user     = null;
        if ($userId) {
            $user = $this->userModel->find($userId);
            if ($user) {
                $userTier = $user['tier'] ?? 'basic';
            }
        }

        // Group payment methods
        $groupedPayments = [];
        foreach ($paymentMethods as $pm) {
            $grp = $pm['group_name'] ?: 'Lainnya';
            $groupedPayments[$grp][] = $pm;
        }

        return view('order/detail', [
            'title'             => 'Top Up ' . $game['name'] . ' Termurah & Instan - ' . ($settings['site_name'] ?? 'Norvago'),
            'game'              => $game,
            'productCategories' => $productCategories,
            'products'          => $products,
            'groupedPayments'   => $groupedPayments,
            'settings'          => $settings,
            'user'              => $user,
            'userTier'          => $userTier,
        ]);
    }

    public function checkId()
    {
        $gameId = $this->request->getPost('game_id');
        $userId = $this->request->getPost('user_id');
        $zoneId = $this->request->getPost('zone_id');

        $game = $this->gameModel->find($gameId);
        if (!$game) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Game tidak valid']);
        }

        $code = $game['check_id_endpoint'] ?: $game['slug'];
        $result = $this->nicknameChecker->checkNickname($code, (string) $userId, (string) $zoneId);

        if ($result['success']) {
            return $this->response->setJSON([
                'status'   => 'success',
                'nickname' => $result['nickname'],
                'message'  => $result['message'],
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => $result['message'],
        ]);
    }

    public function applyVoucher()
    {
        $code   = (string) $this->request->getPost('code');
        $amount = (float) $this->request->getPost('amount');

        $result = $this->voucherModel->validateVoucher($code, $amount);
        if ($result['valid']) {
            return $this->response->setJSON([
                'status'          => 'success',
                'discount_amount' => $result['discount'],
                'message'         => $result['message'],
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => $result['message'],
        ]);
    }

    public function checkout()
    {
        $gameId          = (int) $this->request->getPost('game_id');
        $productId       = (int) $this->request->getPost('product_id');
        $paymentMethodId = (int) $this->request->getPost('payment_method_id');
        $targetUserId    = trim((string) $this->request->getPost('target_user_id'));
        $targetZoneId    = trim((string) $this->request->getPost('target_zone_id'));
        $targetServer    = trim((string) $this->request->getPost('target_server'));
        $targetNickname  = trim((string) $this->request->getPost('target_nickname'));
        $customerPhone   = trim((string) $this->request->getPost('customer_phone'));
        $voucherCode     = trim((string) $this->request->getPost('voucher_code'));

        if (empty($targetUserId) || empty($customerPhone) || empty($productId) || empty($paymentMethodId)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Harap lengkapi semua data akun, pilihan item, metode pembayaran, dan no. WhatsApp!',
            ]);
        }

        $game = $this->gameModel->find($gameId);
        $product = $this->productModel->find($productId);
        $payment = $this->paymentModel->find($paymentMethodId);

        if (!$game || !$product || !$payment) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Produk atau metode pembayaran tidak ditemukan!',
            ]);
        }

        // Determine price by tier
        $userId   = session()->get('user_id');
        $userTier = 'basic';
        $user     = null;
        if ($userId) {
            $user = $this->userModel->find($userId);
            if ($user) {
                $userTier = $user['tier'] ?? 'basic';
            }
        }

        $basePrice = $product['price_normal'];
        if ($product['is_flash_sale'] && $product['flash_sale_price'] > 0) {
            $basePrice = $product['flash_sale_price'];
        } elseif ($userTier === 'vip' && $product['price_reseller'] > 0) {
            $basePrice = $product['price_reseller'];
        } elseif ($userTier === 'gold' && $product['price_gold'] > 0) {
            $basePrice = $product['price_gold'];
        }

        // Voucher discount
        $discountAmount = 0.00;
        if (!empty($voucherCode)) {
            $vCheck = $this->voucherModel->validateVoucher($voucherCode, $basePrice);
            if ($vCheck['valid']) {
                $discountAmount = (float) $vCheck['discount'];
            }
        }

        // Payment fee
        $feeAmount = (float) $payment['fee_flat'] + (($basePrice * (float) $payment['fee_percent']) / 100);

        // Generate unique code for auto-matching if QRIS / Bank Transfer / E-Wallet
        $uniqueCode = 0;
        if (in_array($payment['type'], ['qris', 'bank_transfer', 'ewallet'])) {
            if ($game['slug'] === 'mobile-legends' || $game['id'] == 1 || stripos($game['name'], 'mobile legends') !== false) {
                // For MLBB: Start with 001 (Rp 1). Check if another active unpaid order exists with exact amount to prevent collision:
                $candidateCode = 1;
                $tentativeTotal = ($basePrice + $feeAmount + $candidateCode) - $discountAmount;
                while ($this->orderModel->where('payment_status', 'unpaid')->where('total_amount', $tentativeTotal)->where('created_at >=', date('Y-m-d H:i:s', strtotime('-15 minutes')))->countAllResults() > 0 && $candidateCode < 99) {
                    $candidateCode++;
                    $tentativeTotal = ($basePrice + $feeAmount + $candidateCode) - $discountAmount;
                }
                $uniqueCode = $candidateCode;
            } else {
                $uniqueCode = random_int(101, 999);
            }
        }

        $totalAmount = ($basePrice + $feeAmount + $uniqueCode) - $discountAmount;
        if ($totalAmount < 0) {
            $totalAmount = 0;
        }

        // Expiry time (15 mins default)
        $expiryMinutes = (int) ($this->settingModel->getSetting('order_expiry_minutes', 15));
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$expiryMinutes} minutes"));

        $invoiceNo = $this->orderModel->generateInvoiceNumber();

        // Generate Dynamic QRIS if QRIS
        $qrisPayload = null;
        if ($payment['type'] === 'qris') {
            $staticQris = $this->settingModel->getSetting('qris_static_payload', '');
            if (!empty($staticQris)) {
                $qrisPayload = $this->qrisService->generateDynamicQris($staticQris, $totalAmount);
            }
        }

        // If paying with internal balance
        $isPaidImmediately = false;
        if ($payment['type'] === 'balance') {
            if (!$user) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Anda harus login untuk membayar menggunakan Saldo Akun',
                ]);
            }
            if ($user['balance'] < $totalAmount) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Saldo akun tidak mencukupi. Saldo Anda: Rp ' . number_format($user['balance'], 0, ',', '.'),
                ]);
            }

            // Deduct balance
            $newBalance = $user['balance'] - $totalAmount;
            $this->userModel->update($user['id'], ['balance' => $newBalance]);
            $isPaidImmediately = true;
        }

        $orderData = [
            'invoice_no'        => $invoiceNo,
            'user_id'           => $userId ?: null,
            'game_id'           => $gameId,
            'product_id'        => $productId,
            'payment_method_id' => $paymentMethodId,
            'target_user_id'    => $targetUserId,
            'target_zone_id'    => $targetZoneId ?: null,
            'target_server'     => $targetServer ?: null,
            'target_nickname'   => $targetNickname ?: null,
            'customer_phone'    => $customerPhone,
            'price_product'     => $basePrice,
            'price_fee'         => $feeAmount,
            'unique_code'       => $uniqueCode,
            'discount_amount'   => $discountAmount,
            'total_amount'      => $totalAmount,
            'payment_status'    => $isPaidImmediately ? 'paid' : 'unpaid',
            'delivery_status'   => 'pending',
            'provider_name'     => $product['provider_code'] ?: 'manual',
            'qris_payload'      => $qrisPayload,
            'expires_at'        => $expiresAt,
            'paid_at'           => $isPaidImmediately ? date('Y-m-d H:i:s') : null,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ];

        $orderId = $this->orderModel->insert($orderData);

        // If paid immediately, fulfill instantly!
        if ($isPaidImmediately) {
            $fulfillResult = $this->providerService->fulfillOrder($orderData, $product);
            $this->orderModel->update($orderId, [
                'delivery_status'   => $fulfillResult['success'] ? 'success' : 'failed',
                'provider_trx_id'   => $fulfillResult['provider_trx_id'] ?? null,
                'provider_sn'       => $fulfillResult['sn'] ?? null,
                'provider_response' => $fulfillResult['message'] ?? null,
                'completed_at'      => $fulfillResult['success'] ? date('Y-m-d H:i:s') : null,
            ]);
        }

        return $this->response->setJSON([
            'status'       => 'success',
            'redirect_url' => base_url('invoice/' . $invoiceNo),
            'invoice_no'   => $invoiceNo,
        ]);
    }
}
