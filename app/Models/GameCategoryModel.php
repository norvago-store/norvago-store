<?php

namespace App\Models;

use CodeIgniter\Model;

class GameCategoryModel extends Model
{
    protected $table = 'game_categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'slug', 'icon', 'sort_order', 'status'];
    protected $useTimestamps = true;
}
