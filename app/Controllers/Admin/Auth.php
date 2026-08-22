<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $settingModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->settingModel = new SettingModel();
    }

    public function login()
    {
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin/dashboard');
        }

        $settings = $this->settingModel->getAllSettings();
        return view('admin/auth/login', [
            'title'    => 'Login Admin - ' . ($settings['site_name'] ?? 'Norvago'),
            'settings' => $settings,
        ]);
    }

    public function loginProcess()
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $user = $this->userModel->where('username', $username)->orWhere('email', $username)->first();

        if ($user && $user['role'] === 'admin' && password_verify($password, $user['password'])) {
            session()->set([
                'admin_logged_in' => true,
                'admin_id'        => $user['id'],
                'admin_name'      => $user['name'],
                'admin_username'  => $user['username'],
                'admin_email'     => $user['email'],
                'admin_role'      => $user['role'],
            ]);
            return redirect()->to('/admin/dashboard')->with('success', 'Selamat datang kembali, ' . $user['name']);
        }

        return redirect()->back()->withInput()->with('error', 'Username atau password admin salah!');
    }

    public function logout()
    {
        session()->remove(['admin_logged_in', 'admin_id', 'admin_name', 'admin_username', 'admin_email', 'admin_role']);
        return redirect()->to('/admin/login')->with('success', 'Anda telah berhasil logout.');
    }
}
