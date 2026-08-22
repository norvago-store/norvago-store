<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\SettingModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $orderModel;
    protected $settingModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->orderModel   = new OrderModel();
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->to('/logout');
        }

        $orders = $this->orderModel
            ->select('orders.*, games.name as game_name, games.image_url as game_image, products.name as product_name')
            ->join('games', 'games.id = orders.game_id', 'left')
            ->join('products', 'products.id = orders.product_id', 'left')
            ->where('orders.user_id', $userId)
            ->orderBy('orders.id', 'DESC')
            ->findAll(20);

        $settings = $this->settingModel->getAllSettings();

        return view('member/dashboard/index', [
            'title'    => 'Dashboard Member - ' . ($settings['site_name'] ?? 'Norvago'),
            'user'     => $user,
            'orders'   => $orders,
            'settings' => $settings,
        ]);
    }
}
