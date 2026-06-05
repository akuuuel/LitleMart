<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Product extends Model {
    protected $table = 'products';

    public function getWithDetails($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, v.store_name, v.city_id as vendor_city_id, c.name as category_name 
            FROM products p
            JOIN vendors v ON p.vendor_id = v.id
            JOIN categories c ON p.category_id = c.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getImages($id) {
        $stmt = $this->db->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC");
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function getByVendor($vendorId) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name,
            (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.vendor_id = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$vendorId]);
        return $stmt->fetchAll();
    }

    public function createWithImages(array $data, array $images) {
        try {
            $this->db->beginTransaction();
            
            // Create product
            $this->create($data);
            
            // Create images
            foreach ($images as $index => $path) {
                $stmt = $this->db->prepare("
                    INSERT INTO product_images (product_id, image_path, is_primary) 
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([
                    $data['id'],
                    $path,
                    $index === 0 ? 1 : 0
                ]);
            }
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function updateWithImages($id, array $data, array $images) {
        try {
            $this->db->beginTransaction();
            
            // Update basic info
            $this->update($id, $data);
            
            if (!empty($images)) {
                // Remove old images
                $stmt = $this->db->prepare("DELETE FROM product_images WHERE product_id = ?");
                $stmt->execute([$id]);
                
                // Add new images
                foreach ($images as $index => $path) {
                    $stmt = $this->db->prepare("
                        INSERT INTO product_images (product_id, image_path, is_primary) 
                        VALUES (?, ?, ?)
                    ");
                    $stmt->execute([
                        $id,
                        $path,
                        $index === 0 ? 1 : 0
                    ]);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    public function getLatest($limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT p.*, v.store_name, c.name as category_name, (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
            FROM products p
            JOIN vendors v ON p.vendor_id = v.id
            JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'published'
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function search($filters = [], $limit = 20) {
        $query = "SELECT p.*, v.store_name, c.name as category_name, (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
                  FROM products p
                  JOIN vendors v ON p.vendor_id = v.id
                  JOIN categories c ON p.category_id = c.id
                  WHERE p.status = 'published'";
        
        $params = [];
        
        if (!empty($filters['category'])) {
            $query .= " AND p.category_id = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $params[] = "%" . $filters['search'] . "%";
            $params[] = "%" . $filters['search'] . "%";
        }

        if (!empty($filters['min_price'])) {
            $query .= " AND p.price >= ?";
            $params[] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $query .= " AND p.price <= ?";
            $params[] = $filters['max_price'];
        }
        // Rating filter is reserved for when a rating system is implemented
        // if (!empty($filters['rating'])) { ... }
        
        $query .= " ORDER BY p.created_at DESC LIMIT " . (int)$limit;
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function reduceStock($id, $quantity) {
        error_log("Attempting to reduce stock for Product ID: $id by $quantity units");
        $stmt = $this->db->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
        $stmt->execute([$quantity, $id, $quantity]);
        $updated = $stmt->rowCount() > 0;
        error_log("Stock reduction for $id: " . ($updated ? "SUCCESS" : "FAILED (Insufficient stock or invalid ID)"));
        return $updated;
    }
}
