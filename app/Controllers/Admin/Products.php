<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GameModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductModel;
use App\Models\SettingModel;

class Products extends BaseController
{
    protected $productModel;
    protected $gameModel;
    protected $categoryModel;
    protected $settingModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->gameModel     = new GameModel();
        $this->categoryModel = new ProductCategoryModel();
        $this->settingModel  = new SettingModel();
    }

    public function index()
    {
        $gameId = $this->request->getGet('game_id');
        $builder = $this->productModel
            ->select('products.*, games.name as game_name, product_categories.name as category_name')
            ->join('games', 'games.id = products.game_id', 'left')
            ->join('product_categories', 'product_categories.id = products.category_id', 'left');

        if ($gameId) {
            $builder->where('products.game_id', $gameId);
        }

        $products = $builder->orderBy('products.game_id', 'ASC')->orderBy('products.sort_order', 'ASC')->findAll();
        $games    = $this->gameModel->orderBy('name', 'ASC')->findAll();
        $settings = $this->settingModel->getAllSettings();

        return view('admin/products/index', [
            'title'    => 'Kelola Produk & Denominasi - ' . ($settings['site_name'] ?? 'Norvago'),
            'products' => $products,
            'games'    => $games,
            'gameId'   => $gameId,
            'settings' => $settings,
        ]);
    }

    public function create()
    {
        $games      = $this->gameModel->orderBy('name', 'ASC')->findAll();
        $categories = $this->categoryModel->findAll();
        $settings   = $this->settingModel->getAllSettings();

        return view('admin/products/form', [
            'title'      => 'Tambah Produk / Denominasi',
            'product'    => null,
            'games'      => $games,
            'categories' => $categories,
            'settings'   => $settings,
        ]);
    }

    public function edit(int $id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan');
        }

        $games      = $this->gameModel->orderBy('name', 'ASC')->findAll();
        $categories = $this->categoryModel->where('game_id', $product['game_id'])->findAll();
        $settings   = $this->settingModel->getAllSettings();

        return view('admin/products/form', [
            'title'      => 'Edit Produk: ' . $product['name'],
            'product'    => $product,
            'games'      => $games,
            'categories' => $categories,
            'settings'   => $settings,
        ]);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $name = trim((string) $this->request->getPost('name'));
        $sku = trim((string) $this->request->getPost('sku')) ?: strtoupper(url_title($name, '-', true));

        $data = [
            'game_id'          => (int) $this->request->getPost('game_id'),
            'category_id'      => $this->request->getPost('category_id') ? (int) $this->request->getPost('category_id') : null,
            'name'             => $name,
            'sku'              => $sku,
            'provider_code'    => $this->request->getPost('provider_code') ?: 'manual',
            'provider_sku'     => $this->request->getPost('provider_sku'),
            'price_cost'       => (float) $this->request->getPost('price_cost'),
            'price_normal'     => (float) $this->request->getPost('price_normal'),
            'price_gold'       => (float) $this->request->getPost('price_gold'),
            'price_reseller'   => (float) $this->request->getPost('price_reseller'),
            'is_flash_sale'    => $this->request->getPost('is_flash_sale') ? 1 : 0,
            'flash_sale_price' => (float) $this->request->getPost('flash_sale_price'),
            'status'           => $this->request->getPost('status') ?: 'available',
            'sort_order'       => (int) $this->request->getPost('sort_order'),
        ];

        if ($id) {
            $this->productModel->update($id, $data);
            $msg = 'Produk berhasil diperbarui!';
        } else {
            $this->productModel->insert($data);
            $msg = 'Produk baru berhasil ditambahkan!';
        }

        return redirect()->to('/admin/products')->with('success', $msg);
    }

    public function delete(int $id)
    {
        $this->productModel->delete($id);
        return redirect()->to('/admin/products')->with('success', 'Produk berhasil dihapus!');
    }
}
