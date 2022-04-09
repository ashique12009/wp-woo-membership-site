<?php 
function get_membership_type_list() {
    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->prefix}sf_member_options";
    $result = $wpdb->get_results($sql);

    return $result;
}

function get_count_membership_types() {
    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->prefix}sf_member_options";
    $result = $wpdb->get_results($sql);

    return count($result);
}

function get_membership_info($product_id, $member_type) {
    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->prefix}sf_member_options WHERE type='$member_type' AND post_id=" . $product_id;
    $result = $wpdb->get_results($sql);

    return $result;
}

function edit_membership_duplicacy_check($primary_id, $product_id, $member_type) {
    global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}sf_member_options WHERE id!=$primary_id AND type='$member_type' AND post_id=" . $product_id;
    $result = $wpdb->get_results($sql);

    if (count($result) > 0)
        return false;
    else 
        return true;
}

function remove_membership_by_id($primary_id) {
    global $wpdb;
    $sql = "DELETE FROM {$wpdb->prefix}sf_member_options WHERE id=$primary_id";
    $result = $wpdb->get_results($sql);
    return true;
}

function check_discount_exist($target_product_id, $member_product_id) {
    global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}sf_member_product_discount WHERE target_product_id=$target_product_id AND membership_product_id='$member_product_id'";
    $result = $wpdb->get_results($sql);

    if (count($result) > 0)
        return true;
    else 
        return false;
}

function get_count_membership_discounts() {
    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->prefix}sf_member_product_discount";
    $result = $wpdb->get_results($sql);

    return count($result);
}

function get_membership_discount_list() {
    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->prefix}sf_member_product_discount";
    $result = $wpdb->get_results($sql);

    return $result;
}

function get_member_products($product_id) {
    global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}sf_member_options WHERE post_id=" . $product_id;
    $result = $wpdb->get_results($sql);

    if (count($result) > 0)
        return $result;
    else 
        return [];
}

function get_membership_price($mduration_table_primary_id) {
    global $wpdb;

    $sql = "SELECT price FROM {$wpdb->prefix}sf_member_options WHERE id=" . $mduration_table_primary_id;
    $result = $wpdb->get_results($sql);

    if (count($result) > 0)
        return $result;
    else 
        return [];
}

function get_member_data($mduration_table_primary_id) {
    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->prefix}sf_member_options WHERE id=" . $mduration_table_primary_id;
    $result = $wpdb->get_results($sql);

    if (count($result) > 0)
        return $result;
    else 
        return [];
}

function check_member_type_duplicacy($member_type, $post_id, $start_date, $end_date) {
    global $wpdb;
    
    $sql = "SELECT * FROM {$wpdb->prefix}sf_member_options WHERE type='$member_type' AND start_date='$start_date' AND end_date='$end_date' AND post_id =" . $post_id;
    $result = $wpdb->get_results($sql);
    
    if (count($result) > 0)
        return true;
    else 
        return false;
}

function get_discount_price($product_id, $membership_product_id) {
    global $wpdb;

    $sql = "SELECT discount_price FROM {$wpdb->prefix}sf_member_product_discount WHERE target_product_id=" . $product_id . ' AND membership_product_id=' . $membership_product_id;
    $result = $wpdb->get_results($sql);

    if (count($result) > 0)
        return $result[0]->discount_price;
    else 
        return 0;
}