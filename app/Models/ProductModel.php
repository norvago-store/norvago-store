<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'game_id', 'category_id', 'name', 'sku', 'provider_code', 'provider_sku',
        'price_cost', 'price_normal', 'price_gold', 'price_reseller',
        'is_flash_sale', 'flash_sale_price', 'flash_sale_end',
        'status', 'icon_url', 'sort_order'
    ];
    protected $useTimestamps = true;

    public function getProductsByGame(int $gameId)
    {
        return $this->select('products.*, product_categories.name as category_name')
            ->join('product_categories', 'product_categories.id = products.category_id', 'left')
            ->where('products.game_id', $gameId)
            ->where('products.status', 'available')
            ->orderBy('products.sort_order', 'ASC')
            ->orderBy('products.price_normal', 'ASC')
            ->findAll();
    }
}
