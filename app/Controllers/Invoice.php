<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\SettingModel;
use App\Services\Payment\QrisService;

class Invoice extends BaseController
{
    protected $orderModel;
    protected $settingModel;
    protected $qrisService;

    public function __construct()
    {
        $this->orderModel   = new OrderModel();
        $this->settingModel = new SettingModel();
        $this->qrisService  = new QrisService();
    }

    public function detail(string $invoiceNo)
    {
        $order = $this->orderModel->getOrderWithDetails($invoiceNo);
        if (!$order) {
            return redirect()->to('/')->with('error', 'Invoice tidak ditemukan');
        }

        $settings = $this->settingModel->getAllSettings();

        // Check if expired
        if ($order['payment_status'] === 'unpaid' && $order['expires_at'] && strtotime($order['expires_at']) < time()) {
            $this->orderModel->update($order['id'], ['payment_status' => 'expired']);
            $order['payment_status'] = 'expired';
        }

        // Render QR Code SVG/Image if QRIS
        $qrCodeDataUri = null;
        if (!empty($order['qris_payload'])) {
            $qrCodeDataUri = $this->qrisService->renderQrCodeDataUri($order['qris_payload']);
        }

        // Remaining seconds for countdown
        $remainingSeconds = 0;
        if ($order['payment_status'] === 'unpaid' && $order['expires_at']) {
            $remainingSeconds = max(0, strtotime($order['expires_at']) - time());
        }

        return view('invoice/detail', [
            'title'            => 'Invoice #' . $order['invoice_no'] . ' - ' . ($settings['site_name'] ?? 'Norvago'),
            'order'            => $order,
            'qrCodeDataUri'    => $qrCodeDataUri,
            'remainingSeconds' => $remainingSeconds,
            'settings'         => $settings,
        ]);
    }

    public function checkStatus(string $invoiceNo)
    {
        $order = $this->orderModel->where('invoice_no', $invoiceNo)->first();
        if (!$order) {
            return $this->response->setJSON(['status' => 'not_found']);
        }

        // Check expiry
        if ($order['payment_status'] === 'unpaid' && $order['expires_at'] && strtotime($order['expires_at']) < time()) {
            $this->orderModel->update($order['id'], ['payment_status' => 'expired']);
            $order['payment_status'] = 'expired';
        }

        return $this->response->setJSON([
            'status'          => 'success',
            'payment_status'  => $order['payment_status'],
            'delivery_status' => $order['delivery_status'],
            'provider_sn'     => $order['provider_sn'] ?? '',
            'is_paid'         => in_array($order['payment_status'], ['paid', 'completed']),
            'is_success'      => $order['delivery_status'] === 'success',
        ]);
    }

    public function printReceipt(string $invoiceNo)
    {
        $order = $this->orderModel->getOrderWithDetails($invoiceNo);
        if (!$order) {
            return redirect()->to('/')->with('error', 'Invoice tidak ditemukan');
        }

        $settings = $this->settingModel->getAllSettings();

        return view('invoice/print', [
            'title'    => 'Struk Transaksi #' . $order['invoice_no'],
            'order'    => $order,
            'settings' => $settings,
        ]);
    }
}
