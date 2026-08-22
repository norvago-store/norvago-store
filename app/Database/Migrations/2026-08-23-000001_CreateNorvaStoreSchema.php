<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNorvaStoreSchema extends Migration
{
    public function up()
    {
        // 1. Table Users
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'username' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role' => ['type' => 'ENUM', 'constraint' => ['admin', 'member', 'reseller'], 'default' => 'member'],
            'balance' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'tier' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'basic'],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'suspended'], 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true);

        // 2. Table Game Categories
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'icon' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'sort_order' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('game_categories', true);

        // 3. Table Games
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'category_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'subtitle' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'developer' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'image_url' => ['type' => 'VARCHAR', 'constraint' => 255],
            'banner_url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'instructions' => ['type' => 'TEXT', 'null' => true],
            'target_input_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'single'],
            'target_input_label_1' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => 'User ID'],
            'target_input_label_2' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'target_input_placeholder_1' => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => 'Masukkan User ID'],
            'target_input_placeholder_2' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'server_list' => ['type' => 'TEXT', 'null' => true],
            'check_id_endpoint' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'is_popular' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'sort_order' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('category_id');
        $this->forge->createTable('games', true);

        // 4. Table Product Categories
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'game_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'sort_order' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('game_id');
        $this->forge->createTable('product_categories', true);

        // 5. Table Products (Denominations)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'game_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'category_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'sku' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'provider_code' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'manual'],
            'provider_sku' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'price_cost' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'price_normal' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'price_gold' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'price_reseller' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'is_flash_sale' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'flash_sale_price' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'flash_sale_end' => ['type' => 'DATETIME', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['available', 'empty'], 'default' => 'available'],
            'icon_url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sort_order' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('game_id');
        $this->forge->addKey('category_id');
        $this->forge->createTable('products', true);

        // 6. Table Payment Methods
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'code' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'group_name' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'QRIS'],
            'type' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'qris'],
            'fee_flat' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'fee_percent' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'min_amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 1000.00],
            'max_amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 10000000.00],
            'icon_url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'account_number' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'account_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'instructions' => ['type' => 'TEXT', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'sort_order' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('payment_methods', true);

        // 7. Table Orders
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'invoice_no' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'game_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'payment_method_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'target_user_id' => ['type' => 'VARCHAR', 'constraint' => 100],
            'target_zone_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'target_server' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'target_nickname' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'customer_phone' => ['type' => 'VARCHAR', 'constraint' => 30],
            'price_product' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'price_fee' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'unique_code' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'discount_amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'total_amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'payment_status' => ['type' => 'ENUM', 'constraint' => ['unpaid', 'paid', 'expired', 'cancelled', 'refunded'], 'default' => 'unpaid'],
            'delivery_status' => ['type' => 'ENUM', 'constraint' => ['pending', 'processing', 'success', 'failed'], 'default' => 'pending'],
            'provider_name' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'manual'],
            'provider_trx_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'provider_response' => ['type' => 'TEXT', 'null' => true],
            'provider_sn' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'qris_payload' => ['type' => 'TEXT', 'null' => true],
            'expires_at' => ['type' => 'DATETIME', 'null' => true],
            'paid_at' => ['type' => 'DATETIME', 'null' => true],
            'completed_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('payment_status');
        $this->forge->addKey('delivery_status');
        $this->forge->createTable('orders', true);

        // 8. Table Vouchers
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'type' => ['type' => 'ENUM', 'constraint' => ['fixed', 'percent'], 'default' => 'percent'],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'min_purchase' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'max_discount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'quota' => ['type' => 'INT', 'constraint' => 11, 'default' => 100],
            'used_count' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'valid_until' => ['type' => 'DATETIME', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('vouchers', true);

        // 9. Table Banners
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 150],
            'subtitle' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'image_url' => ['type' => 'VARCHAR', 'constraint' => 255],
            'link_url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sort_order' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('banners', true);

        // 10. Table QRIS Mutations
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'source' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'webhook'],
            'raw_content' => ['type' => 'TEXT', 'null' => true],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'description' => ['type' => 'TEXT', 'null' => true],
            'matched_order_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['matched', 'unmatched', 'ignored'], 'default' => 'unmatched'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('amount');
        $this->forge->addKey('status');
        $this->forge->createTable('qris_mutations', true);

        // 11. Table Settings
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'setting_key' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'setting_value' => ['type' => 'TEXT', 'null' => true],
            'setting_group' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'general'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('settings', true);
    }

    public function down()
    {
        $this->forge->dropTable('settings', true);
        $this->forge->dropTable('qris_mutations', true);
        $this->forge->dropTable('banners', true);
        $this->forge->dropTable('vouchers', true);
        $this->forge->dropTable('orders', true);
        $this->forge->dropTable('payment_methods', true);
        $this->forge->dropTable('products', true);
        $this->forge->dropTable('product_categories', true);
        $this->forge->dropTable('games', true);
        $this->forge->dropTable('game_categories', true);
        $this->forge->dropTable('users', true);
    }
}
