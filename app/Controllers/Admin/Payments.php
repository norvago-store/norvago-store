<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaymentMethodModel;
use App\Models\SettingModel;

class Payments extends BaseController
{
    protected $paymentModel;
    protected $settingModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentMethodModel();
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        $payments = $this->paymentModel->orderBy('sort_order', 'ASC')->findAll();
        $settings = $this->settingModel->getAllSettings();

        return view('admin/payments/index', [
            'title'    => 'Kelola Metode Pembayaran & Fee - ' . ($settings['site_name'] ?? 'Norvago'),
            'payments' => $payments,
            'settings' => $settings,
        ]);
    }

    public function edit(int $id)
    {
        $payment  = $this->paymentModel->find($id);
        $settings = $this->settingModel->getAllSettings();

        return view('admin/payments/form', [
            'title'    => 'Edit Pembayaran: ' . $payment['name'],
            'payment'  => $payment,
            'settings' => $settings,
        ]);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $data = [
            'name'           => trim((string) $this->request->getPost('name')),
            'code'           => trim((string) $this->request->getPost('code')),
            'group_name'     => $this->request->getPost('group_name') ?: 'QRIS',
            'type'           => $this->request->getPost('type') ?: 'qris',
            'fee_flat'       => (float) $this->request->getPost('fee_flat'),
            'fee_percent'    => (float) $this->request->getPost('fee_percent'),
            'min_amount'     => (float) $this->request->getPost('min_amount'),
            'max_amount'     => (float) $this->request->getPost('max_amount'),
            'account_number' => $this->request->getPost('account_number'),
            'account_name'   => $this->request->getPost('account_name'),
            'icon_url'       => $this->request->getPost('icon_url'),
            'instructions'   => $this->request->getPost('instructions'),
            'status'         => $this->request->getPost('status') ?: 'active',
            'sort_order'     => (int) $this->request->getPost('sort_order'),
        ];

        if ($id) {
            $this->paymentModel->update($id, $data);
            $msg = 'Metode pembayaran berhasil diperbarui!';
        } else {
            $this->paymentModel->insert($data);
            $msg = 'Metode pembayaran baru berhasil ditambahkan!';
        }

        return redirect()->to('/admin/payments')->with('success', $msg);
    }
}
