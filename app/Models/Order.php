<?php

namespace App\Models;

use App\Core\Database;

class Order {
    public static function create($data) {
        $db = Database::getInstance();
        $id = $data['id'];
        
        $db->query(
            "INSERT INTO orders (id, user_id, total_amount, shipping_cost, grand_total, status, shipping_address) 
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $id,
                $data['user_id'],
                $data['total_amount'],
                $data['shipping_cost'],
                $data['grand_total'],
                $data['status'] ?? 'pending',
                $data['shipping_address']
            ]
        );
        
        return $id;
    }

    public static function addItem($data) {
        $db = Database::getInstance();
        $db->query(
            "INSERT INTO order_items (order_id, product_id, vendor_id, variant_id, quantity, price, total) 
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $data['order_id'],
                $data['product_id'],
                $data['vendor_id'],
                $data['variant_id'] ?? null,
                $data['quantity'],
                $data['price'],
                $data['total']
            ]
        );
    }
}
