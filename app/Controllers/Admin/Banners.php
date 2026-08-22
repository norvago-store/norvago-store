<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BannerModel;
use App\Models\SettingModel;

class Banners extends BaseController
{
    protected $bannerModel;
    protected $settingModel;

    public function __construct()
    {
        $this->bannerModel  = new BannerModel();
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        $banners  = $this->bannerModel->orderBy('sort_order', 'ASC')->findAll();
        $settings = $this->settingModel->getAllSettings();

        return view('admin/banners/index', [
            'title'    => 'Kelola Banner Slider - ' . ($settings['site_name'] ?? 'Norvago'),
            'banners'  => $banners,
            'settings' => $settings,
        ]);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $data = [
            'title'      => trim((string) $this->request->getPost('title')),
            'subtitle'   => $this->request->getPost('subtitle'),
            'image_url'  => $this->request->getPost('image_url'),
            'link_url'   => $this->request->getPost('link_url'),
            'sort_order' => (int) $this->request->getPost('sort_order'),
            'status'     => $this->request->getPost('status') ?: 'active',
        ];

        if ($id) {
            $this->bannerModel->update($id, $data);
            $msg = 'Banner berhasil diperbarui!';
        } else {
            $this->bannerModel->insert($data);
            $msg = 'Banner baru berhasil ditambahkan!';
        }

        return redirect()->to('/admin/banners')->with('success', $msg);
    }

    public function delete(int $id)
    {
        $this->bannerModel->delete($id);
        return redirect()->to('/admin/banners')->with('success', 'Banner berhasil dihapus!');
    }
}
