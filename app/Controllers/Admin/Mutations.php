<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\QrisMutationModel;
use App\Models\SettingModel;
use App\Services\Provider\ProviderService;

class Mutations extends BaseController
{
    protected $mutationModel;
    protected $orderModel;
    protected $productModel;
    protected $settingModel;
    protected $providerService;

    public function __construct()
    {
        $this->mutationModel   = new QrisMutationModel();
        $this->orderModel      = new OrderModel();
        $this->productModel    = new ProductModel();
        $this->settingModel    = new SettingModel();
        $this->providerService = new ProviderService();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $builder = $this->mutationModel
            ->select('qris_mutations.*, orders.invoice_no, orders.customer_phone, orders.target_nickname')
            ->join('orders', 'orders.id = qris_mutations.matched_order_id', 'left');

        if ($status) {
            $builder->where('qris_mutations.status', $status);
        }

        $mutations = $builder->orderBy('qris_mutations.id', 'DESC')->findAll(100);
        $settings  = $this->settingModel->getAllSettings();

        // Get recent unpaid orders for quick manual match dropdown
        $unpaidOrders = $this->orderModel->where('payment_status', 'unpaid')->orderBy('id', 'DESC')->limit(30)->findAll();

        return view('admin/mutations/index', [
            'title'        => 'Log Mutasi QRIS / Bank - ' . ($settings['site_name'] ?? 'Norvago'),
            'mutations'    => $mutations,
            'unpaidOrders' => $unpaidOrders,
            'status'       => $status,
            'settings'     => $settings,
        ]);
    }

    public function matchManual()
    {
        $mutationId = (int) $this->request->getPost('mutation_id');
        $orderId    = (int) $this->request->getPost('order_id');

        $mutation = $this->mutationModel->find($mutationId);
        $order    = $this->orderModel->find($orderId);

        if (!$mutation || !$order) {
            return redirect()->back()->with('error', 'Mutasi atau Order tidak valid');
        }

        $now = date('Y-m-d H:i:s');

        // Update mutation
        $this->mutationModel->update($mutationId, [
            'matched_order_id' => $orderId,
            'status'           => 'matched',
        ]);

        // Update order to paid
        $this->orderModel->update($orderId, [
            'payment_status' => 'paid',
            'paid_at'        => $now,
            'updated_at'     => $now,
        ]);

        // Auto fulfill provider
        $product = $this->productModel->find($order['product_id']);
        $fulfillResult = $this->providerService->fulfillOrder($order, $product ?: []);

        $this->orderModel->update($orderId, [
            'delivery_status'   => $fulfillResult['success'] ? 'success' : 'failed',
            'provider_trx_id'   => $fulfillResult['provider_trx_id'] ?? null,
            'provider_sn'       => $fulfillResult['sn'] ?? null,
            'provider_response' => $fulfillResult['message'] ?? null,
            'completed_at'      => $fulfillResult['success'] ? $now : null,
        ]);

        return redirect()->back()->with('success', 'Mutasi berhasil dipasangkan ke Order #' . $order['invoice_no'] . ' dan diproses otomatis!');
    }
}
