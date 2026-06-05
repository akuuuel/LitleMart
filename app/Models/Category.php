<?php

namespace App\Models;

use App\Core\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function getAllActive(): array
    {
        return $this->db->query("
            SELECT c.*, 
                   (SELECT pi.image_path 
                    FROM product_images pi 
                    JOIN products p ON pi.product_id = p.id 
                    WHERE p.category_id = c.id AND p.stock > 0
                    LIMIT 1) as image
            FROM {$this->table} c 
            ORDER BY name ASC
        ")->fetchAll();
    }
}
