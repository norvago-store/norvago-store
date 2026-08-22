<?php

namespace App\Controllers\Member;

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
        if (session()->get('user_logged_in')) {
            return redirect()->to('/member/dashboard');
        }

        $settings = $this->settingModel->getAllSettings();
        return view('member/auth/login', [
            'title'    => 'Masuk Akun Member - ' . ($settings['site_name'] ?? 'Norvago'),
            'settings' => $settings,
        ]);
    }

    public function loginProcess()
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $user = $this->userModel->where('username', $username)->orWhere('email', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                return redirect()->back()->with('error', 'Akun Anda sedang dinonaktifkan.');
            }

            session()->set([
                'user_logged_in' => true,
                'user_id'        => $user['id'],
                'user_name'      => $user['name'],
                'user_username'  => $user['username'],
                'user_email'     => $user['email'],
                'user_role'      => $user['role'],
                'user_tier'      => $user['tier'],
            ]);

            return redirect()->to('/member/dashboard')->with('success', 'Selamat datang, ' . $user['name'] . '!');
        }

        return redirect()->back()->withInput()->with('error', 'Username/Email atau Password salah!');
    }

    public function register()
    {
        if (session()->get('user_logged_in')) {
            return redirect()->to('/member/dashboard');
        }

        $settings = $this->settingModel->getAllSettings();
        return view('member/auth/register', [
            'title'    => 'Daftar Akun Member Baru - ' . ($settings['site_name'] ?? 'Norvago'),
            'settings' => $settings,
        ]);
    }

    public function registerProcess()
    {
        $name     = trim((string) $this->request->getPost('name'));
        $username = trim((string) $this->request->getPost('username'));
        $email    = trim((string) $this->request->getPost('email'));
        $phone    = trim((string) $this->request->getPost('phone'));
        $password = (string) $this->request->getPost('password');

        if (empty($name) || empty($username) || empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Semua kolom wajib diisi!');
        }

        // Check duplicate
        if ($this->userModel->where('username', $username)->first()) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan, silakan pilih username lain.');
        }

        if ($this->userModel->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('error', 'Email sudah terdaftar.');
        }

        $userId = $this->userModel->insert([
            'name'       => $name,
            'username'   => $username,
            'email'      => $email,
            'phone'      => $phone,
            'password'   => password_hash($password, PASSWORD_BCRYPT),
            'role'       => 'member',
            'balance'    => 0.00,
            'tier'       => 'basic',
            'status'     => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        session()->set([
            'user_logged_in' => true,
            'user_id'        => $userId,
            'user_name'      => $name,
            'user_username'  => $username,
            'user_email'     => $email,
            'user_role'      => 'member',
            'user_tier'      => 'basic',
        ]);

        return redirect()->to('/member/dashboard')->with('success', 'Registrasi berhasil! Selamat bergabung di Norvago.');
    }

    public function logout()
    {
        session()->remove(['user_logged_in', 'user_id', 'user_name', 'user_username', 'user_email', 'user_role', 'user_tier']);
        return redirect()->to('/')->with('success', 'Anda telah berhasil keluar.');
    }
}
