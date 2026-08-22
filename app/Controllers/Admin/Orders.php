<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\SettingModel;
use App\Services\Provider\ProviderService;

class Orders extends BaseController
{
    protected $orderModel;
    protected $productModel;
    protected $settingModel;
    protected $providerService;

    public function __construct()
    {
        $this->orderModel      = new OrderModel();
        $this->productModel    = new ProductModel();
        $this->settingModel    = new SettingModel();
        $this->providerService = new ProviderService();
    }

    public function index()
    {
        $status         = $this->request->getGet('status');
        $deliveryStatus = $this->request->getGet('delivery_status');
        $search         = $this->request->getGet('search');
        $dateStart      = $this->request->getGet('date_start');
        $dateEnd        = $this->request->getGet('date_end');

        $builder = $this->orderModel
            ->select('orders.*, games.name as game_name, products.name as product_name, payment_methods.name as payment_name')
            ->join('games', 'games.id = orders.game_id', 'left')
            ->join('products', 'products.id = orders.product_id', 'left')
            ->join('payment_methods', 'payment_methods.id = orders.payment_method_id', 'left');

        if (!empty($status)) {
            $builder->where('orders.payment_status', $status);
        }

        if (!empty($deliveryStatus)) {
            $builder->where('orders.delivery_status', $deliveryStatus);
        }

        if (!empty($dateStart)) {
            $builder->where('orders.created_at >=', $dateStart . ' 00:00:00');
        }

        if (!empty($dateEnd)) {
            $builder->where('orders.created_at <=', $dateEnd . ' 23:59:59');
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('orders.invoice_no', $search)
                ->orLike('orders.target_user_id', $search)
                ->orLike('orders.customer_phone', $search)
                ->orLike('orders.target_nickname', $search)
                ->groupEnd();
        }

        $orders   = $builder->orderBy('orders.id', 'DESC')->findAll(200);
        $settings = $this->settingModel->getAllSettings();

        // Summary stats for overview
        $totalRevenue = 0;
        $totalPaid = 0;
        $totalPending = 0;
        foreach ($orders as $o) {
            if (in_array($o['payment_status'], ['paid', 'completed'])) {
                $totalRevenue += (float) $o['total_amount'];
                $totalPaid++;
            } elseif ($o['payment_status'] === 'unpaid') {
                $totalPending++;
            }
        }

        return view('admin/orders/index', [
            'title'          => 'Kelola Transaksi - ' . ($settings['site_name'] ?? 'Norvago'),
            'orders'         => $orders,
            'status'         => $status,
            'deliveryStatus' => $deliveryStatus,
            'search'         => $search,
            'dateStart'      => $dateStart,
            'dateEnd'        => $dateEnd,
            'totalRevenue'   => $totalRevenue,
            'totalPaid'      => $totalPaid,
            'totalPending'   => $totalPending,
            'settings'       => $settings,
        ]);
    }

    public function exportPdf()
    {
        $status         = $this->request->getGet('status');
        $deliveryStatus = $this->request->getGet('delivery_status');
        $search         = $this->request->getGet('search');
        $dateStart      = $this->request->getGet('date_start');
        $dateEnd        = $this->request->getGet('date_end');

        $builder = $this->orderModel
            ->select('orders.*, games.name as game_name, products.name as product_name, payment_methods.name as payment_name')
            ->join('games', 'games.id = orders.game_id', 'left')
            ->join('products', 'products.id = orders.product_id', 'left')
            ->join('payment_methods', 'payment_methods.id = orders.payment_method_id', 'left');

        if (!empty($status)) {
            $builder->where('orders.payment_status', $status);
        }

        if (!empty($deliveryStatus)) {
            $builder->where('orders.delivery_status', $deliveryStatus);
        }

        if (!empty($dateStart)) {
            $builder->where('orders.created_at >=', $dateStart . ' 00:00:00');
        }

        if (!empty($dateEnd)) {
            $builder->where('orders.created_at <=', $dateEnd . ' 23:59:59');
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('orders.invoice_no', $search)
                ->orLike('orders.target_user_id', $search)
                ->orLike('orders.customer_phone', $search)
                ->orLike('orders.target_nickname', $search)
                ->groupEnd();
        }

        $orders   = $builder->orderBy('orders.id', 'DESC')->findAll(500);
        $settings = $this->settingModel->getAllSettings();

        // Calculate summary
        $totalRevenue = 0;
        $totalPaidCount = 0;
        $totalPendingCount = 0;
        $totalDeliveredCount = 0;

        foreach ($orders as $o) {
            if (in_array($o['payment_status'], ['paid', 'completed'])) {
                $totalRevenue += (float) $o['total_amount'];
                $totalPaidCount++;
            } elseif ($o['payment_status'] === 'unpaid') {
                $totalPendingCount++;
            }

            if ($o['delivery_status'] === 'success') {
                $totalDeliveredCount++;
            }
        }

        return view('admin/orders/pdf', [
            'title'               => 'Rekap Transaksi - ' . ($settings['site_name'] ?? 'Norvago'),
            'orders'              => $orders,
            'status'              => $status,
            'dateStart'           => $dateStart,
            'dateEnd'             => $dateEnd,
            'totalRevenue'        => $totalRevenue,
            'totalPaidCount'      => $totalPaidCount,
            'totalPendingCount'   => $totalPendingCount,
            'totalDeliveredCount' => $totalDeliveredCount,
            'totalCount'          => count($orders),
            'settings'            => $settings,
            'printDate'           => date('d F Y, H:i'),
        ]);
    }

    public function detail(int $id)
    {
        $order = $this->orderModel
            ->select('orders.*, games.name as game_name, games.image_url as game_image, games.target_input_label_1, games.target_input_label_2, products.name as product_name, products.sku, payment_methods.name as payment_name, payment_methods.type as payment_type, payment_methods.account_number, payment_methods.account_name')
            ->join('games', 'games.id = orders.game_id', 'left')
            ->join('products', 'products.id = orders.product_id', 'left')
            ->join('payment_methods', 'payment_methods.id = orders.payment_method_id', 'left')
            ->where('orders.id', $id)
            ->first();

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $settings = $this->settingModel->getAllSettings();

        return view('admin/orders/detail', [
            'title'    => 'Detail Order #' . $order['invoice_no'],
            'order'    => $order,
            'settings' => $settings,
        ]);
    }

    public function markPaid(int $id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $now = date('Y-m-d H:i:s');
        $this->orderModel->update($id, [
            'payment_status' => 'paid',
            'paid_at'        => $now,
            'updated_at'     => $now,
        ]);

        // Auto fulfill provider
        $product = $this->productModel->find($order['product_id']);
        $fulfillResult = $this->providerService->fulfillOrder($order, $product ?: []);

        $deliveryStatus = $fulfillResult['success'] ? 'success' : ($fulfillResult['delivery_status'] ?? 'processing');
        $this->orderModel->update($id, [
            'delivery_status'   => $deliveryStatus,
            'provider_trx_id'   => $fulfillResult['provider_trx_id'] ?? null,
            'provider_sn'       => $fulfillResult['sn'] ?? null,
            'provider_response' => $fulfillResult['message'] ?? null,
            'completed_at'      => $fulfillResult['success'] ? $now : null,
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi & status diperbarui!');
    }

    public function retryProvider(int $id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $product = $this->productModel->find($order['product_id']);
        $fulfillResult = $this->providerService->fulfillOrder($order, $product ?: []);

        $this->orderModel->update($id, [
            'delivery_status'   => $fulfillResult['success'] ? 'success' : 'failed',
            'provider_trx_id'   => $fulfillResult['provider_trx_id'] ?? null,
            'provider_sn'       => $fulfillResult['sn'] ?? null,
            'provider_response' => $fulfillResult['message'] ?? null,
            'completed_at'      => $fulfillResult['success'] ? date('Y-m-d H:i:s') : null,
        ]);

        return redirect()->back()->with('success', 'Percobaan pengiriman provider berhasil dijalankan: ' . ($fulfillResult['message'] ?? ''));
    }

    public function updateStatus(int $id)
    {
        $paymentStatus  = $this->request->getPost('payment_status');
        $deliveryStatus = $this->request->getPost('delivery_status');
        $providerSn     = trim((string) $this->request->getPost('provider_sn'));

        $data = [];
        if ($paymentStatus) {
            $data['payment_status'] = $paymentStatus;
            if ($paymentStatus === 'paid') {
                $data['paid_at'] = date('Y-m-d H:i:s');
            }
        }
        if ($deliveryStatus) {
            $data['delivery_status'] = $deliveryStatus;
            if ($deliveryStatus === 'success') {
                $data['completed_at'] = date('Y-m-d H:i:s');
            }
        }
        if (!empty($providerSn)) {
            $data['provider_sn'] = $providerSn;
        }

        $this->orderModel->update($id, $data);
        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui!');
    }

    public function delete(int $id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $invoiceNo = $order['invoice_no'];

        // Detach mutation if any
        $db = \Config\Database::connect();
        $db->table('qris_mutations')->where('matched_order_id', $id)->update(['matched_order_id' => null, 'status' => 'unmatched']);

        // Delete order
        $this->orderModel->delete($id);

        return redirect()->to('/admin/orders')->with('success', 'Transaksi #' . $invoiceNo . ' berhasil dihapus!');
    }
}
