<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\QrisMutationModel;
use App\Models\SettingModel;
use App\Services\Provider\ProviderService;

class Webhook extends BaseController
{
    protected $orderModel;
    protected $productModel;
    protected $mutationModel;
    protected $settingModel;
    protected $providerService;

    public function __construct()
    {
        $this->orderModel      = new OrderModel();
        $this->productModel    = new ProductModel();
        $this->mutationModel   = new QrisMutationModel();
        $this->settingModel    = new SettingModel();
        $this->providerService = new ProviderService();
    }

    /**
     * Webhook endpoint to receive bank/QRIS/e-wallet mutation notifications
     * POST /api/webhook/qris-mutation
     */
    public function qrisMutation()
    {
        $rawBody = $this->request->getBody();
        $json = json_decode($rawBody, true) ?: [];

        // 1. Verify Secret Key (if configured)
        $secretKey = $this->settingModel->getSetting('webhook_secret_key', '');
        $incomingSecret = $this->request->getHeaderLine('X-Webhook-Secret') 
            ?: ($json['secret'] ?? $this->request->getGet('secret') ?? $this->request->getPost('secret'));

        if (!empty($secretKey) && $incomingSecret !== $secretKey) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized: Invalid secret key',
            ]);
        }

        // 2. Extract Amount and Description
        $amount = (float) ($json['amount'] ?? $json['nominal'] ?? $json['credit'] ?? $this->request->getPost('amount') ?? $this->request->getGet('amount') ?? $this->request->getGet('nominal') ?? 0);
        $description = (string) ($json['description'] ?? $json['keterangan'] ?? $json['pesan'] ?? $this->request->getPost('description') ?? $this->request->getGet('description') ?? '');
        $source = (string) ($json['source'] ?? $json['bank'] ?? $this->request->getGet('source') ?? 'webhook');

        // Regex fallback if amount is in raw text (e.g. from notification text "Dana masuk Rp 20.148")
        if ($amount <= 0 && !empty($rawBody)) {
            if (preg_match('/(?:Rp|IDR|\s)\s*([0-9\.,]+)/i', $rawBody, $matches)) {
                $clean = str_replace(['.', ','], '', $matches[1]);
                $amount = (float) $clean;
            }
        }

        if ($amount <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Nominal mutasi tidak valid',
            ]);
        }

        // 3. Log Mutation to Database
        $mutationId = $this->mutationModel->insert([
            'source'           => $source,
            'raw_content'      => $rawBody ?: json_encode($this->request->getPost()),
            'amount'           => $amount,
            'description'      => $description,
            'matched_order_id' => null,
            'status'           => 'unmatched',
            'created_at'       => date('Y-m-d H:i:s'),
        ]);

        // 4. Find matching unpaid order within the last 2 hours
        $twoHoursAgo = date('Y-m-d H:i:s', strtotime('-2 hours'));
        $order = $this->orderModel
            ->where('payment_status', 'unpaid')
            ->where('total_amount', $amount)
            ->where('created_at >=', $twoHoursAgo)
            ->orderBy('id', 'DESC')
            ->first();

        if (!$order) {
            return $this->response->setJSON([
                'status'      => 'success',
                'matched'     => false,
                'mutation_id' => $mutationId,
                'amount'      => $amount,
                'message'     => 'Mutasi berhasil dicatat, namun tidak ada order pending dengan nominal ini.',
            ]);
        }

        // 5. Match Found: Mark Order as PAID!
        $paidTime = date('Y-m-d H:i:s');
        $this->orderModel->update($order['id'], [
            'payment_status' => 'paid',
            'paid_at'        => $paidTime,
            'updated_at'     => $paidTime,
        ]);

        // Update mutation status
        $this->mutationModel->update($mutationId, [
            'matched_order_id' => $order['id'],
            'status'           => 'matched',
        ]);

        // 6. Auto-Deliver Game Item via Provider Service!
        $product = $this->productModel->find($order['product_id']);
        $fulfillResult = $this->providerService->fulfillOrder($order, $product ?: []);

        $deliveryStatus = $fulfillResult['success'] ? 'success' : ($fulfillResult['delivery_status'] ?? 'processing');
        $this->orderModel->update($order['id'], [
            'delivery_status'   => $deliveryStatus,
            'provider_trx_id'   => $fulfillResult['provider_trx_id'] ?? null,
            'provider_sn'       => $fulfillResult['sn'] ?? null,
            'provider_response' => $fulfillResult['message'] ?? null,
            'completed_at'      => $fulfillResult['success'] ? date('Y-m-d H:i:s') : null,
        ]);

        return $this->response->setJSON([
            'status'          => 'success',
            'matched'         => true,
            'mutation_id'     => $mutationId,
            'invoice_no'      => $order['invoice_no'],
            'order_id'        => $order['id'],
            'delivery_status' => $deliveryStatus,
            'provider_sn'     => $fulfillResult['sn'] ?? null,
            'message'         => 'Order berhasil dilunasi & top up diproses otomatis!',
        ]);
    }
}
