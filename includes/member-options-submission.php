<?php 
add_action('admin_post_membership_type_duration_price_action', 'sf_membership_options_form_submission');

function sf_membership_options_form_submission() {
    $redirect_form_url = admin_url('admin.php?page=member_options');
    $url = '';
    if (wp_verify_nonce($_POST['nonce'], 'membership_type_duration_price') && is_user_logged_in()) {
        global $wpdb;
        
        $member_product_id  = trim($_POST['select_member_product']);
        $member_type        = trim($_POST['member_type']);
        $start_date         = trim($_POST['start_date']);
        $end_date           = trim($_POST['end_date']);
        $price              = trim($_POST['price']);
        
        $current_user_id = get_current_user_id();        
        
        if ($member_type == '') {
            $url = $redirect_form_url . '&status=1';
        } 
        elseif ($start_date == '' || $end_date == '') {
            $url = $redirect_form_url . '&status=2';
        }
        else {
            // Check duplicate member type
            if (check_member_type_duplicacy($member_type, $member_product_id, $start_date, $end_date) === false) {
                $insert_data = [
                    'user_id'       => $current_user_id,
                    'post_id'       => $member_product_id,
                    'type'          => $member_type,
                    'start_date'    => $start_date,
                    'end_date'      => $end_date,
                    'price'         => $price,
                ];

                $wpdb->insert($wpdb->prefix . 'sf_member_options', $insert_data);
                
                // Update post meta, update this product meta as type, duration, price
                update_post_meta($member_product_id, 'member_type', $member_type);
                update_post_meta($member_product_id, 'member_start_date', $start_date);
                update_post_meta($member_product_id, 'member_end_date', $end_date);
                update_post_meta($member_product_id, 'member_type_price', $price);
                
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

add_action('admin_post_membership_type_duration_price_edit_action', 'sf_membership_options_edit_form_submission');

function sf_membership_options_edit_form_submission() {
    $redirect_form_url = admin_url('admin.php?page=member_options');
    $url = '';
    if (wp_verify_nonce($_POST['nonce'], 'membership_type_duration_price_edit') && is_user_logged_in()) {
        global $wpdb;

        $member_product_id  = trim($_POST['select_member_product']);
        $member_type        = trim($_POST['member_type']);
        $primary_id         = trim($_POST['primary_id']);
        $start_date         = trim($_POST['start_date']);
        $end_date           = trim($_POST['end_date']);
        $price              = trim($_POST['price']);
        
        $current_user_id = get_current_user_id();

        if ($member_type == '') {
            $url = $redirect_form_url . '&primary_id=' . $primary_id . '&edit_pid=' . $member_product_id . '&member_type=' . $member_type . '&status=1';
        } 
        elseif ($start_date == '' || $end_date == '') {
            $url = $redirect_form_url . '&primary_id=' . $primary_id . '&edit_pid=' . $member_product_id . '&member_type=' . $member_type . '&status=2';
        }
        else {
            // Check duplicate member type
            if (edit_membership_duplicacy_check($primary_id, $member_product_id, $member_type, $start_date, $end_date)) {
                $data = [
                    'user_id'       => $current_user_id,
                    'post_id'       => $member_product_id,
                    'type'          => $member_type,
                    'start_date'    => $start_date,
                    'end_date'      => $end_date,
                    'price'         => $price,
                ];
                $where = [ 'id' => $primary_id ]; // NULL value in WHERE clause.
                $wpdb->update( $wpdb->prefix . 'sf_member_options', $data, $where ); // Also works in this case.

                // Update post meta, update this product meta as type, duration, price
                update_post_meta($member_product_id, 'member_type', $member_type);
                update_post_meta($member_product_id, 'member_start_date', $start_date);
                update_post_meta($member_product_id, 'member_end_date', $end_date);
                update_post_meta($member_product_id, 'member_type_price', $price);

                $url = $redirect_form_url . '&primary_id='.$primary_id.'&edit_pid='.$member_product_id.'&member_type='.$member_type.'&status=3';
            }
            else {
                $url = $redirect_form_url . '&primary_id='.$primary_id.'&edit_pid='.$member_product_id.'&member_type='.$member_type.'&status=4';
            }
        }
    }
    else {
        $url = admin_url('admin.php?page=member_options');
    }

    wp_redirect($url);
    exit; 
}