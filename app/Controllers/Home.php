<?php

namespace App\Controllers;

use App\Models\BannerModel;
use App\Models\GameCategoryModel;
use App\Models\GameModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\SettingModel;

class Home extends BaseController
{
    protected $bannerModel;
    protected $categoryModel;
    protected $gameModel;
    protected $productModel;
    protected $settingModel;
    protected $orderModel;

    public function __construct()
    {
        $this->bannerModel   = new BannerModel();
        $this->categoryModel = new GameCategoryModel();
        $this->gameModel     = new GameModel();
        $this->productModel  = new ProductModel();
        $this->settingModel  = new SettingModel();
        $this->orderModel    = new OrderModel();
    }

    public function index(): string
    {
        $banners    = $this->bannerModel->getActiveBanners();
        $categories = $this->categoryModel->where('status', 'active')->orderBy('sort_order', 'ASC')->findAll();
        $popular    = $this->gameModel->getPopularGames();
        $allGames   = $this->gameModel->getActiveGames();
        $settings   = $this->settingModel->getAllSettings();

        // Flash sale products
        $flashSales = $this->productModel
            ->select('products.*, games.name as game_name, games.slug as game_slug, games.image_url as game_image')
            ->join('games', 'games.id = products.game_id')
            ->where('products.is_flash_sale', 1)
            ->where('products.status', 'available')
            ->where('games.is_active', 1)
            ->findAll();

        return view('home/index', [
            'title'      => ($settings['site_name'] ?? 'Norvago') . ' - ' . ($settings['site_tagline'] ?? 'Top Up Game Terpercaya'),
            'banners'    => $banners,
            'categories' => $categories,
            'popular'    => $popular,
            'allGames'   => $allGames,
            'flashSales' => $flashSales,
            'settings'   => $settings,
        ]);
    }

    public function calculatorWinrate(): string
    {
        $settings = $this->settingModel->getAllSettings();
        return view('tools/calculator_winrate', [
            'title'    => 'Kalkulator Winrate MLBB - ' . ($settings['site_name'] ?? 'Norvago'),
            'settings' => $settings,
        ]);
    }

    public function calculatorMagicWheel(): string
    {
        $settings = $this->settingModel->getAllSettings();
        return view('tools/calculator_magic_wheel', [
            'title'    => 'Kalkulator Magic Wheel MLBB - ' . ($settings['site_name'] ?? 'Norvago'),
            'settings' => $settings,
        ]);
    }

    public function tracking(): string
    {
        $settings  = $this->settingModel->getAllSettings();
        $invoiceNo = trim((string)$this->request->getGet('invoice'));
        $phone     = trim((string)$this->request->getGet('phone'));
        $orders    = [];
        $order     = null;

        if ($invoiceNo !== '') {
            $order = $this->orderModel->getOrderWithDetails($invoiceNo);
            if ($order) {
                $orders = [$order];
            }
        } elseif ($phone !== '') {
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            if (!empty($cleanPhone)) {
                $orders = $this->orderModel
                    ->select('orders.*, games.name as game_name, games.image_url as game_image, products.name as product_name, payment_methods.name as payment_name')
                    ->join('games', 'games.id = orders.game_id', 'left')
                    ->join('products', 'products.id = orders.product_id', 'left')
                    ->join('payment_methods', 'payment_methods.id = orders.payment_method_id', 'left')
                    ->like('orders.customer_phone', $cleanPhone)
                    ->orderBy('orders.id', 'DESC')
                    ->findAll(15);
            }
        } else {
            // If logged-in member, auto show their orders
            $memberId = session()->get('user_id');
            if ($memberId) {
                $orders = $this->orderModel
                    ->select('orders.*, games.name as game_name, games.image_url as game_image, products.name as product_name, payment_methods.name as payment_name')
                    ->join('games', 'games.id = orders.game_id', 'left')
                    ->join('products', 'products.id = orders.product_id', 'left')
                    ->join('payment_methods', 'payment_methods.id = orders.payment_method_id', 'left')
                    ->where('orders.user_id', $memberId)
                    ->orderBy('orders.id', 'DESC')
                    ->findAll(10);
            }
        }

        return view('home/tracking', [
            'title'     => 'Pesanan Saya - Status Pengiriman & Pembayaran - ' . ($settings['site_name'] ?? 'Norvago'),
            'settings'  => $settings,
            'invoiceNo' => $invoiceNo,
            'phone'     => $phone,
            'order'     => $order,
            'orders'    => $orders,
        ]);
    }
}
