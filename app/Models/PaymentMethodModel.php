<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethodModel extends Model
{
    protected $table = 'payment_methods';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'name', 'code', 'group_name', 'type', 'fee_flat', 'fee_percent',
        'min_amount', 'max_amount', 'icon_url', 'account_number', 'account_name',
        'instructions', 'status', 'sort_order'
    ];
    protected $useTimestamps = true;

    public function getActiveMethods()
    {
        return $this->where('status', 'active')
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }
}
