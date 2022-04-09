<?php 
function sf_remove_product_content() {
    // If a product in the 'Clothing' category is being viewed…
    if ( is_product() && has_term( 'membership', 'product_cat' ) ) {
        //… Remove the images
        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
        remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_images', 20 );
        // For a full list of what can be removed please see woocommerce-hooks.php
    }
}

add_action('wp', 'sf_remove_product_content');

function sf_cart_discount_programmatically() {
    global $woocommerce;
    $product_ids = [];

    foreach (WC()->cart->get_cart() as $cart_item) {
        array_push($product_ids, $cart_item['data']->get_id());
    }

    $user_id = get_current_user_id();

    $membership_type = get_user_meta($user_id, '_membership_type', true);
    $membership_category = get_user_meta($user_id, '_membership_category', true);
    $membership_duration = get_user_meta($user_id, '_membership_duration', true);
    $membership_start_date = get_user_meta($user_id, '_membership_start_date', true);
    $membership_end_date = get_user_meta($user_id, '_membership_end_date', true);

    if ($membership_type != '' 
        && $membership_category != '' 
        && $membership_duration != '' 
        && $membership_start_date != '' 
        && $membership_end_date != '') {
        // Check membeship is expired or valid
        // If valid then discount will be added

        if (strtotime(date('Y-m-d', strtotime($membership_end_date))) > strtotime(date('Y-m-d'))) {
            // Get membership product id from slug
            $product_obj = get_page_by_path($membership_category, OBJECT, 'product');
            $membership_product_id = 0;
            if ($product_obj->post_name == $membership_category) {
                $membership_product_id = $product_obj->ID;
                if ($membership_product_id > 0) {
                    $membership_price = 0;
                    foreach ($product_ids as $product_id) {
                        // Get discount price for each product here by product_id, type, start and end date
                        $dicount_price = get_discount_price($product_id, $membership_product_id);
                        $membership_price += $dicount_price;
                    }

                    if ($membership_price > 0) {
                        $surcharge = - $membership_price;
                        $woocommerce->cart->add_fee('Membership Discount', $surcharge, true, '');
                    }
                }
            }
        }
    }
}

add_action('woocommerce_cart_calculate_fees', 'sf_cart_discount_programmatically');