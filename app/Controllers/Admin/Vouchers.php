<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\VoucherModel;

class Vouchers extends BaseController
{
    protected $voucherModel;
    protected $settingModel;

    public function __construct()
    {
        $this->voucherModel = new VoucherModel();
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        $vouchers = $this->voucherModel->orderBy('id', 'DESC')->findAll();
        $settings = $this->settingModel->getAllSettings();

        return view('admin/vouchers/index', [
            'title'    => 'Kelola Kode Promo & Voucher - ' . ($settings['site_name'] ?? 'Norvago'),
            'vouchers' => $vouchers,
            'settings' => $settings,
        ]);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $code = strtoupper(trim((string) $this->request->getPost('code')));

        $data = [
            'code'         => $code,
            'name'         => trim((string) $this->request->getPost('name')),
            'type'         => $this->request->getPost('type') ?: 'percent',
            'amount'       => (float) $this->request->getPost('amount'),
            'min_purchase' => (float) $this->request->getPost('min_purchase'),
            'max_discount' => (float) $this->request->getPost('max_discount'),
            'quota'        => (int) $this->request->getPost('quota'),
            'valid_until'  => $this->request->getPost('valid_until') ?: null,
            'status'       => $this->request->getPost('status') ?: 'active',
        ];

        if ($id) {
            $this->voucherModel->update($id, $data);
            $msg = 'Voucher berhasil diperbarui!';
        } else {
            $this->voucherModel->insert($data);
            $msg = 'Voucher baru berhasil ditambahkan!';
        }

        return redirect()->to('/admin/vouchers')->with('success', $msg);
    }

    public function delete(int $id)
    {
        $this->voucherModel->delete($id);
        return redirect()->to('/admin/vouchers')->with('success', 'Voucher berhasil dihapus!');
    }
}
