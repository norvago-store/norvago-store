<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GameModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\QrisMutationModel;
use App\Models\SettingModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    protected $orderModel;
    protected $gameModel;
    protected $productModel;
    protected $mutationModel;
    protected $userModel;
    protected $settingModel;

    public function __construct()
    {
        $this->orderModel    = new OrderModel();
        $this->gameModel     = new GameModel();
        $this->productModel  = new ProductModel();
        $this->mutationModel = new QrisMutationModel();
        $this->userModel     = new UserModel();
        $this->settingModel  = new SettingModel();
    }

    public function index()
    {
        $today = date('Y-m-d 00:00:00');
        $thisMonth = date('Y-m-01 00:00:00');

        // Revenue today
        $revToday = $this->orderModel
            ->selectSum('total_amount')
            ->where('payment_status', 'paid')
            ->where('created_at >=', $today)
            ->first();

        // Revenue this month
        $revMonth = $this->orderModel
            ->selectSum('total_amount')
            ->where('payment_status', 'paid')
            ->where('created_at >=', $thisMonth)
            ->first();

        // Stats counts
        $totalOrders   = $this->orderModel->countAllResults();
        $paidOrders    = $this->orderModel->where('payment_status', 'paid')->countAllResults();
        $pendingOrders = $this->orderModel->where('payment_status', 'unpaid')->countAllResults();
        $totalGames    = $this->gameModel->countAllResults();
        $totalUsers    = $this->userModel->countAllResults();

        // Recent Orders
        $recentOrders = $this->orderModel
            ->select('orders.*, games.name as game_name, products.name as product_name, payment_methods.name as payment_name')
            ->join('games', 'games.id = orders.game_id', 'left')
            ->join('products', 'products.id = orders.product_id', 'left')
            ->join('payment_methods', 'payment_methods.id = orders.payment_method_id', 'left')
            ->orderBy('orders.id', 'DESC')
            ->limit(10)
            ->findAll();

        // Recent Mutations
        $recentMutations = $this->mutationModel->orderBy('id', 'DESC')->limit(8)->findAll();

        $settings = $this->settingModel->getAllSettings();

        return view('admin/dashboard/index', [
            'title'           => 'Dashboard Admin - ' . ($settings['site_name'] ?? 'Norvago'),
            'revToday'        => $revToday['total_amount'] ?? 0,
            'revMonth'        => $revMonth['total_amount'] ?? 0,
            'totalOrders'     => $totalOrders,
            'paidOrders'      => $paidOrders,
            'pendingOrders'   => $pendingOrders,
            'totalGames'      => $totalGames,
            'totalUsers'      => $totalUsers,
            'recentOrders'    => $recentOrders,
            'recentMutations' => $recentMutations,
            'settings'        => $settings,
        ]);
    }
}
