<?php

namespace App\Models;

use CodeIgniter\Model;

class GameModel extends Model
{
    protected $table = 'games';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'category_id', 'name', 'slug', 'subtitle', 'developer', 'image_url', 'banner_url',
        'instructions', 'target_input_type', 'target_input_label_1', 'target_input_label_2',
        'target_input_placeholder_1', 'target_input_placeholder_2', 'server_list',
        'check_id_endpoint', 'is_popular', 'is_active', 'sort_order'
    ];
    protected $useTimestamps = true;

    public function getActiveGames(?int $categoryId = null)
    {
        $builder = $this->where('is_active', 1);
        if ($categoryId) {
            $builder->where('category_id', $categoryId);
        }
        return $builder->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll();
    }

    public function getPopularGames()
    {
        return $this->where('is_active', 1)
            ->where('is_popular', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }
}
