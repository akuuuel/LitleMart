-- =============================================
-- PERFORMANCE INDEXES — Apply to existing DB
-- Run this script ONCE in phpMyAdmin or MySQL CLI
-- =============================================

-- orders
ALTER TABLE orders ADD INDEX IF NOT EXISTS idx_orders_user_id    (user_id);
ALTER TABLE orders ADD INDEX IF NOT EXISTS idx_orders_status     (status);
ALTER TABLE orders ADD INDEX IF NOT EXISTS idx_orders_updated_at (updated_at);
ALTER TABLE orders ADD INDEX IF NOT EXISTS idx_orders_created_at (created_at);
ALTER TABLE orders ADD INDEX IF NOT EXISTS idx_orders_status_updated (status, updated_at);

-- order_items
ALTER TABLE order_items ADD INDEX IF NOT EXISTS idx_order_items_order_id  (order_id);
ALTER TABLE order_items ADD INDEX IF NOT EXISTS idx_order_items_vendor_id (vendor_id);

-- notifications (dipanggil tiap halaman untuk badge count)
ALTER TABLE notifications ADD INDEX IF NOT EXISTS idx_notifications_user_id      (user_id);
ALTER TABLE notifications ADD INDEX IF NOT EXISTS idx_notifications_user_is_read (user_id, is_read);

-- products
ALTER TABLE products ADD INDEX IF NOT EXISTS idx_products_status    (status);
ALTER TABLE products ADD INDEX IF NOT EXISTS idx_products_vendor_id (vendor_id);
ALTER TABLE products ADD INDEX IF NOT EXISTS idx_products_category  (category_id);
ALTER TABLE products ADD INDEX IF NOT EXISTS idx_products_created   (created_at);

-- product_images (subquery primary image di hampir semua listing produk)
ALTER TABLE product_images ADD INDEX IF NOT EXISTS idx_product_images_product_primary (product_id, is_primary);

-- payments
ALTER TABLE payments ADD INDEX IF NOT EXISTS idx_payments_order_id (order_id);

-- shipments
ALTER TABLE shipments ADD INDEX IF NOT EXISTS idx_shipments_order_id (order_id);

-- wallets
ALTER TABLE wallets ADD INDEX IF NOT EXISTS idx_wallets_user_id (user_id);

-- vendors
ALTER TABLE vendors ADD INDEX IF NOT EXISTS idx_vendors_user_id (user_id);

SELECT 'All performance indexes applied successfully!' AS result;
