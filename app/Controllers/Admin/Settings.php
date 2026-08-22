<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class Settings extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        $settings = $this->settingModel->getAllSettings();

        return view('admin/settings/index', [
            'title'    => 'Pengaturan Website & QRIS Automation - ' . ($settings['site_name'] ?? 'Norvago'),
            'settings' => $settings,
        ]);
    }

    public function save()
    {
        $posts = $this->request->getPost();

        foreach ($posts as $key => $value) {
            if ($key === 'csrf_test_name') {
                continue;
            }
            $this->settingModel->setSetting($key, is_string($value) ? trim($value) : $value);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan!');
    }

    public function generateSecret()
    {
        $newSecret = bin2hex(random_bytes(16));
        $this->settingModel->setSetting('webhook_secret_key', $newSecret, 'qris');

        return redirect()->back()->with('success', 'Secret Key Webhook baru berhasil di-generate: ' . $newSecret);
    }
}
