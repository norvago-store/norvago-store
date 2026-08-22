<?php

namespace App\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['title', 'subtitle', 'image_url', 'link_url', 'sort_order', 'status'];
    protected $useTimestamps = true;

    public function getActiveBanners()
    {
        return $this->where('status', 'active')
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }
}
