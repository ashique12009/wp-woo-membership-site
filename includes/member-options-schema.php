<?php 
// Table setup
function sf_create_member_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $sql = "CREATE TABLE `{$wpdb->prefix}sf_member_options` (
        id bigint(128) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL DEFAULT 0,
        post_id int(11) NOT NULL DEFAULT 0,
        type varchar(128) NOT NULL,
        start_date date NOT NULL,
        end_date date NOT NULL,
        price decimal(10,2) NOT NULL DEFAULT 0,
        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
        ) $charset_collate;
        CREATE TABLE `{$wpdb->prefix}sf_member_product_discount` (
        id bigint(128) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL DEFAULT 0,
        target_product_id int(11) NOT NULL DEFAULT 0,
        membership_product_id int(11) NOT NULL DEFAULT 0,
        discount_price decimal(10,2) NOT NULL DEFAULT 0,
        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
        ) $charset_collate;
        ";
    
    dbDelta($sql);
}

add_action('init', 'sf_create_member_tables');