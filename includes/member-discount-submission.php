<?php 
add_action('admin_post_set_membership_discount_action', 'sf_set_membership_discount');

function sf_set_membership_discount() {
    $redirect_form_url = admin_url('admin.php?page=member_discount');
    $url = '';
    if (wp_verify_nonce($_POST['nonce'], 'set_membership_discount_nonce') && is_user_logged_in()) {
        global $wpdb;
        
        $target_product_id  = trim($_POST['target_product_id']);
        $member_product_id  = trim($_POST['member_product_id']);
        $discount_price     = trim($_POST['discount_price']);
        
        $current_user_id = get_current_user_id();     
        
        if ($discount_price < 0) {
            $url = $redirect_form_url . '&status=1';
        }
        else {
            // Check discount already exist or not 
            if (check_discount_exist($target_product_id, $member_product_id) === false) { // not exist so insert
                $insert_data = [
                    'user_id'               => $current_user_id,
                    'target_product_id'     => $target_product_id,
                    'membership_product_id' => $member_product_id,
                    'discount_price'        => $discount_price,
                ];

                $wpdb->insert($wpdb->prefix . 'sf_member_product_discount', $insert_data);
                $url = $redirect_form_url . '&status=3';
            }
            else { // exists so update
                $update_data = [
                    'user_id'               => $current_user_id,
                    'target_product_id'     => $target_product_id,
                    'membership_product_id' => $member_product_id,
                    'discount_price'        => $discount_price,
                ];
                $where = ['target_product_id' => $target_product_id, 'membership_product_id' => $member_product_id];
                $wpdb->update($wpdb->prefix . 'sf_member_product_discount', $update_data, $where);
                $url = $redirect_form_url . '&status=3';
            }
        }
    }
    else {
        $url = $redirect_form_url . '&status=5';
    }

    wp_redirect($url);
    exit;  
}