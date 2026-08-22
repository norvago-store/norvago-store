<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'invoice_no', 'user_id', 'game_id', 'product_id', 'payment_method_id',
        'target_user_id', 'target_zone_id', 'target_server', 'target_nickname',
        'customer_phone', 'price_product', 'price_fee', 'unique_code',
        'discount_amount', 'total_amount', 'payment_status', 'delivery_status',
        'provider_name', 'provider_trx_id', 'provider_response', 'provider_sn',
        'qris_payload', 'expires_at', 'paid_at', 'completed_at'
    ];
    protected $useTimestamps = true;

    public function generateInvoiceNumber(): string
    {
        return 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function getOrderWithDetails(string $invoiceNo)
    {
        return $this->select('orders.*, games.name as game_name, games.image_url as game_image, games.slug as game_slug, games.target_input_label_1, games.target_input_label_2, products.name as product_name, products.sku, payment_methods.name as payment_name, payment_methods.type as payment_type, payment_methods.icon_url as payment_icon, payment_methods.account_number, payment_methods.account_name, payment_methods.instructions as payment_instructions')
            ->join('games', 'games.id = orders.game_id', 'left')
            ->join('products', 'products.id = orders.product_id', 'left')
            ->join('payment_methods', 'payment_methods.id = orders.payment_method_id', 'left')
            ->where('orders.invoice_no', $invoiceNo)
            ->first();
    }
}
