<?php

namespace App\Models;

use CodeIgniter\Model;

class QrisMutationModel extends Model
{
    protected $table = 'qris_mutations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'source', 'raw_content', 'amount', 'description', 'matched_order_id', 'status', 'created_at'
    ];
    protected $useTimestamps = false;
}
