<?php 
add_action('admin_post_membership_type_duration_renew_price_action', 'sf_membership_renew_options_form_submission');

function sf_membership_renew_options_form_submission() {
    $redirect_form_url = admin_url('admin.php?page=member_renew_price');
    $url = '';
    if (wp_verify_nonce($_POST['nonce'], 'membership_type_duration_renew_price') && is_user_logged_in()) {
        global $wpdb;
        
        $member_product_id       = trim($_POST['select_member_product']);
        $member_type             = trim($_POST['member_type']);
        $duration                = trim($_POST['duration']);
        $duration_day_month_year = trim($_POST['duration_day_month_year']);
        $price                   = trim($_POST['price']);
        
        $current_user_id = get_current_user_id();        
        
        if ($member_type == '') {
            $url = $redirect_form_url . '&status=1';
        } 
        elseif ($duration == '' || $duration_day_month_year == '') {
            $url = $redirect_form_url . '&status=2';
        }
        else {
            // Check duplicate member type
            if (check_member_renew_type_duplicacy($member_type, $member_product_id, $duration, $duration_day_month_year) === false) {
                $insert_data = [
                    'posted_by'      => $current_user_id,
                    'post_id'        => $member_product_id,
                    'type'           => $member_type,
                    'duration'       => $duration,
                    'day_month_year' => $duration_day_month_year,
                    'renew_price'    => $price,
                ];

                $wpdb->insert($wpdb->prefix . 'sf_member_renew_options', $insert_data);
                
                // Update post meta, update this product meta as type, duration, price
                update_post_meta($member_product_id, 'member_renew_type', $member_type);
                update_post_meta($member_product_id, 'member_renew_duration', $duration);
                update_post_meta($member_product_id, 'member_renew_day_month_year', $duration_day_month_year);
                update_post_meta($member_product_id, 'member_renew_price', $price);
                
                $url = $redirect_form_url . '&status=3';
            }
            else {
                $url = $redirect_form_url . '&status=4';
            }
        }
    }
    else {
        $url = $redirect_form_url . '&status=5';
    }

    wp_redirect($url);
    exit;  
}

add_action('admin_post_renew_membership_type_duration_price_edit_action', 'sf_membership_renew_options_edit_form_submission');

function sf_membership_renew_options_edit_form_submission() {
    $redirect_form_url = admin_url('admin.php?page=member_renew_price');
    $url = '';
    if (wp_verify_nonce($_POST['nonce'], 'renew_membership_type_duration_price_edit_nonce') && is_user_logged_in()) {
        global $wpdb;

        $member_product_id       = trim($_POST['select_member_product']);
        $member_type             = trim($_POST['member_type']);
        $duration                = trim($_POST['duration']);
        $duration_day_month_year = trim($_POST['duration_day_month_year']);
        $price                   = trim($_POST['price']);
        $primary_id              = trim($_POST['primary_id']);
        
        $current_user_id = get_current_user_id();

        if ($member_type == '') {
            $url = $redirect_form_url . '&primary_id=' . $primary_id . '&edit_pid=' . $member_product_id . '&member_type=' . $member_type . '&status=1&action=edit';
        } 
        elseif ($duration == '' || $duration_day_month_year == '') {
            $url = $redirect_form_url . '&primary_id=' . $primary_id . '&edit_pid=' . $member_product_id . '&member_type=' . $member_type . '&status=2&action=edit';
        }
        else {
            // Check duplicate member type
            if (edit_renew_membership_duplicacy_check($primary_id, $member_product_id, $member_type, $duration, $duration_day_month_year)) {
                $data = [
                    'posted_by'      => $current_user_id,
                    'post_id'        => $member_product_id,
                    'type'           => $member_type,
                    'duration'       => $duration,
                    'day_month_year' => $duration_day_month_year,
                    'renew_price'    => $price,
                ];
                $where = [ 'id' => $primary_id ]; // NULL value in WHERE clause.
                $wpdb->update( $wpdb->prefix . 'sf_member_renew_options', $data, $where ); // Also works in this case.

                // Update post meta, update this product meta as type, duration, price
                update_post_meta($member_product_id, 'member_renew_type', $member_type);
                update_post_meta($member_product_id, 'member_renew_duration', $duration);
                update_post_meta($member_product_id, 'member_renew_day_month_year', $duration_day_month_year);
                update_post_meta($member_product_id, 'member_renew_price', $price);

                $url = $redirect_form_url . '&primary_id='.$primary_id.'&edit_pid='.$member_product_id.'&member_type='.$member_type.'&status=3&action=edit';
            }
            else {
                $url = $redirect_form_url . '&primary_id='.$primary_id.'&edit_pid='.$member_product_id.'&member_type='.$member_type.'&status=4&action=edit';
            }
        }
    }
    else {
        $url = admin_url('admin.php?page=member_renew_price');
    }

    wp_redirect($url);
    exit; 
}