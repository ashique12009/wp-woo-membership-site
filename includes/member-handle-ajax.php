<?php 
add_action('wp_ajax_nopriv_member_fee_price', 'sf_membership_fee_seeker');
add_action('wp_ajax_member_fee_price', 'sf_membership_fee_seeker');

function sf_membership_fee_seeker() {
    if (wp_verify_nonce($_GET['nonce'], 'member_fee_nonce') && is_user_logged_in()) {
        $table_primary_id = trim($_GET['id']);
        $price = get_membership_price($table_primary_id);
        $json_response = isset($price[0]->price) ? $price[0]->price : 0;

        wp_send_json_success($json_response);
    }
    else {
        wp_send_json_error(array('error' => 'Could not retrieve data! Or you are not logged in!'));
    }
}

add_action('wp_ajax_nopriv_member_renew_fee_price', 'sf_membership_renew_fee_seeker');
add_action('wp_ajax_member_renew_fee_price', 'sf_membership_renew_fee_seeker');

function sf_membership_renew_fee_seeker() {
    if (wp_verify_nonce($_GET['nonce'], 'member_fee_nonce') && is_user_logged_in()) {
        $table_primary_id = trim($_GET['id']);
        $price = get_membership_rewnew_price($table_primary_id);
        $json_response = isset($price[0]->renew_price) ? $price[0]->renew_price : 0;

        wp_send_json_success($json_response);
    }
    else {
        wp_send_json_error(array('error' => 'Could not retrieve data! Or you are not logged in!'));
    }
}