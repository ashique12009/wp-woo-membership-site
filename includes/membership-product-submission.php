<?php 
add_action('admin_post_nopriv_membership_action', 'sf_membership_form_submission');
add_action('admin_post_membership_action', 'sf_membership_form_submission');

function sf_membership_form_submission() {
    $current_page_url = trim($_POST['current_page_url']);

    if (wp_verify_nonce($_POST['nonce'], 'membership_nonce') && is_user_logged_in()) {
        $first_name                     = trim($_POST['first_name']);
        $last_name                      = trim($_POST['last_name']);
        $street                         = trim($_POST['street']);
        $country                        = trim($_POST['country']);
        $dob                            = trim($_POST['dob']);
        $femail                         = trim($_POST['email']);
        $cemail                         = trim($_POST['cemail']);
        $cno                            = trim($_POST['cno']);
        $mduration_table_primary_id     = trim($_POST['mtype_duration']);
        $mcharge                        = trim($_POST['mcharge']);
        $post_id                        = trim($_POST['post_id']);

        $current_user       = wp_get_current_user();
        $current_user_id    = get_current_user_id();
        $post               = get_post($post_id); 
        $post_slug          = $post->post_name;

        // Get membership price from server side cross verification to user input fee price
        $fee_price_object = get_membership_price($mduration_table_primary_id);
        $fee_price = isset($fee_price_object[0]->price) ? $fee_price_object[0]->price : 0;
        $member_data = get_member_data($mduration_table_primary_id);

        if ($first_name == '' || $last_name == '') {
            $url = $current_page_url . '?error=1';
        } 
        elseif ($femail != $cemail) {
            $url = $current_page_url . '?error=2';
        }
        elseif ($mduration_table_primary_id == '0' || $mduration_table_primary_id == '') {
            $url = $current_page_url . '?error=3';
        }
        elseif ($mcharge != $fee_price) {
            $url = $current_page_url . '?error=4';
        }
        else {
            $billing_address = [
                'first_name' => $current_user->billing_first_name,
                'last_name'  => $current_user->billing_last_name,
                'company'    => $current_user->billing_company,
                'email'      => $current_user->billing_email,
                'phone'      => $current_user->billing_phone,
                'address_1'  => $current_user->billing_address_1,
                'address_2'  => $current_user->billing_address_2,
                'city'       => $current_user->billing_city,
                'state'      => $current_user->billing_state,
                'postcode'   => $current_user->billing_postcode,
                'country'    => $current_user->billing_country
            ];

            $shipping_address = [
                'first_name' => $current_user->shipping_first_name,
                'last_name'  => $current_user->shipping_last_name,
                'company'    => $current_user->shipping_company,
                'email'      => $current_user->shipping_email,
                'phone'      => $current_user->shipping_phone,
                'address_1'  => $current_user->shipping_address_1,
                'address_2'  => $current_user->shipping_address_2,
                'city'       => $current_user->shipping_city,
                'state'      => $current_user->shipping_state,
                'postcode'   => $current_user->shipping_postcode,
                'country'    => $current_user->shipping_country
            ];

            // Now we create the order
            $order = wc_create_order(['customer_id' => $current_user_id]);

            // // Set addresses
            $order->set_address($billing_address, 'billing');
            $order->set_address($shipping_address, 'shipping');

            $product_item_id = $order->add_product(wc_get_product($post_id), 1, [
                'subtotal' => $mcharge,
                'total'    => $mcharge
            ]);

            wc_add_order_item_meta($product_item_id, 'Membership Type', isset($member_data[0]->type) ? $member_data[0]->type : '');
            wc_add_order_item_meta($product_item_id, 'Membership Category', $post_slug);
            wc_add_order_item_meta($product_item_id, 'Membership Duration', isset($member_data[0]->start_date) ? $member_data[0]->start_date . ' - ' . $member_data[0]->end_date : '');
            wc_add_order_item_meta($product_item_id, 'First name', $first_name);
            wc_add_order_item_meta($product_item_id, 'Last name', $last_name);
            wc_add_order_item_meta($product_item_id, 'Street', $street);
            wc_add_order_item_meta($product_item_id, 'Country', $country);
            wc_add_order_item_meta($product_item_id, 'Date of birth', $dob);
            wc_add_order_item_meta($product_item_id, 'Email', $femail);
            wc_add_order_item_meta($product_item_id, 'Contact no', $cno);

            // Calculate totals
            $order->calculate_totals();
            $order->set_total($mcharge);
            $order->update_status('pending', 'Order created dynamically - ', TRUE);
            $order->save();
            
            $url = site_url() . '/checkout/order-pay/' . $order->get_id() . '/?pay_for_order=true&key=' . $order->get_order_key();
            wp_redirect(esc_url_raw($url));
            exit;
        }
    }
    else {
        $url = $current_page_url . '?error=5';
    }

    wp_redirect(esc_url($url));
    exit;
}

// Renew submission
add_action('admin_post_nopriv_membership_renew_action', 'ctp_renew_membership_form_submission');
add_action('admin_post_membership_renew_action', 'ctp_renew_membership_form_submission');

function ctp_renew_membership_form_submission() {
  if (wp_verify_nonce($_POST['nonce'], 'membership_renew_nonce') && is_user_logged_in()) {
    $first_name                 = trim($_POST['first_name']);
    $last_name                  = trim($_POST['last_name']);
    $femail                     = trim($_POST['email']);
    // Get type, duration and day_month_year
    $mcharge                    = trim($_POST['mcharge']);
    $mduration_table_primary_id = trim($_POST['mtype_duration']);
    $post_id                    = trim($_POST['post_id']);
    $current_page_url           = trim($_POST['current_page_url']);
    
    $current_user = wp_get_current_user();
    $current_user_id = get_current_user_id();
    $post = get_post($post_id);
    $post_slug = $post->post_name;

    // Check this user has this selected membership
    // If not then redirect him to error page
    $fee_price_object = get_membership_rewnew_price($mduration_table_primary_id);
    $fee_price = isset($fee_price_object[0]->renew_price) ? $fee_price_object[0]->renew_price : 0;
    $member_data = get_membership_rewnew_info($mduration_table_primary_id);

    $membership_type = get_user_meta($current_user_id, '_membership_type', true);
    $membership_category = get_user_meta($current_user_id, '_membership_category', true);

    if ($first_name == '' || $last_name == '') {
      $url = $current_page_url . '?renew-error=1';
    } 
    elseif ($femail == '') {
      $url = $current_page_url . '?renew-error=2';
    }
    elseif ($mduration_table_primary_id == '0' || $mduration_table_primary_id == '') {
      $url = $current_page_url . '?renew-error=3';
    }
    elseif ($mcharge != $fee_price) {
      $url = $current_page_url . '?renew-error=5';
    }
    elseif ($membership_type != $member_data[0]->type && $membership_category != $post_slug) {
      $url = $current_page_url . '?renew-error=6';
    }
    else {
      $billing_address = [
        'first_name' => $current_user->billing_first_name,
        'last_name'  => $current_user->billing_last_name,
        'company'    => $current_user->billing_company,
        'email'      => $current_user->billing_email,
        'phone'      => $current_user->billing_phone,
        'address_1'  => $current_user->billing_address_1,
        'address_2'  => $current_user->billing_address_2,
        'city'       => $current_user->billing_city,
        'state'      => $current_user->billing_state,
        'postcode'   => $current_user->billing_postcode,
        'country'    => $current_user->billing_country
      ];

      $shipping_address = [
        'first_name' => $current_user->shipping_first_name,
        'last_name'  => $current_user->shipping_last_name,
        'company'    => $current_user->shipping_company,
        'email'      => $current_user->shipping_email,
        'phone'      => $current_user->shipping_phone,
        'address_1'  => $current_user->shipping_address_1,
        'address_2'  => $current_user->shipping_address_2,
        'city'       => $current_user->shipping_city,
        'state'      => $current_user->shipping_state,
        'postcode'   => $current_user->shipping_postcode,
        'country'    => $current_user->shipping_country
      ];

      // Now we create the order
      $order = wc_create_order(['customer_id' => $current_user_id]);

      // // Set addresses
      $order->set_address($billing_address, 'billing');
      $order->set_address($shipping_address, 'shipping');

      $product_item_id = $order->add_product(wc_get_product($post_id), 1, [
        'subtotal' => $mcharge,
        'total'    => $mcharge
      ]);

      wc_add_order_item_meta($product_item_id, 'Membership Type', isset($member_data[0]->type) ? $member_data[0]->type : '');
      wc_add_order_item_meta($product_item_id, 'Membership Category', $post_slug);
      // Add date from today mean current date of this duration
      // Then put it into order meta
      if ($member_data[0]->day_month_year == 'day') {
        $date_added = strtotime(date("Y-m-d") . " +" . $member_data[0]->duration . " day");
      }
      elseif ($member_data[0]->day_month_year == 'month') {
        $date_added = strtotime(date("Y-m-d") . " +" . $member_data[0]->duration . " month");
      }
      elseif ($member_data[0]->day_month_year == 'year') {
        $date_added = strtotime(date("Y-m-d") . " +" . $member_data[0]->duration . " year");
      }
      else {
        $date_added = date("Y-m-d");
      }

      wc_add_order_item_meta($product_item_id, 'Membership Duration', isset($member_data[0]->duration) ? date("Y-m-d") . ' - ' . date("Y-m-d", $date_added) : '');
      wc_add_order_item_meta($product_item_id, 'First name', $first_name);
      wc_add_order_item_meta($product_item_id, 'Last name', $last_name);
      wc_add_order_item_meta($product_item_id, 'Email', $femail);

      // Calculate totals
      $order->calculate_totals();
      $order->set_total($mcharge);
      $order->update_status('pending', 'Order created dynamically - ', TRUE);
      $order->save();
      
      $url = site_url() . '/checkout/order-pay/' . $order->get_id() . '/?pay_for_order=true&key=' . $order->get_order_key();
      wp_redirect(esc_url_raw($url));
      exit;
    }
  }
  else {
    $url = $current_page_url . '?error=5';  
  }

  wp_redirect(esc_url($url));
  exit; 
}