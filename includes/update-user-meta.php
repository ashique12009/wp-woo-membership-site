<?php 
// HOOK to get order status change from on-hold to completed
// If completed then check the order is membership or not
// If membership then update user meta with membership value
function sf_action_woocommerce_order_status_changed($order_id, $old_status, $new_status) { 
    if ($new_status === 'completed') {
        $order = wc_get_order($order_id);
        $user_id = $order->get_user_id();
        $items = $order->get_items();
        $membership_type = '';
        $membership_categorty = '';
        $membership_duration = '';

        foreach ($items as $item_id => $item) {
            $product_id = $item->get_product_id();
            $terms = get_the_terms($product_id, 'product_cat');

            if (isset($terms[0]) && $terms[0]->slug == 'membership') {
                $membership_type = wc_get_order_item_meta($item_id, 'Membership Type', true);
                $membership_categorty = wc_get_order_item_meta($item_id, 'Membership Category', true);
                $membership_duration = wc_get_order_item_meta($item_id, 'Membership Duration', true);
            }
        }

        if ($membership_type != '' && $membership_categorty != '' && $membership_duration != '') {
            update_user_meta($user_id, '_membership_type', $membership_type);
            update_user_meta($user_id, '_membership_category', $membership_categorty);
            update_user_meta($user_id, '_membership_duration', $membership_duration);
            update_user_meta($user_id, '_membership_start_date', trim(explode(' - ', $membership_duration)[0]));
            update_user_meta($user_id, '_membership_end_date', trim(explode(' - ', $membership_duration)[1]));
        }
    }
}; 

add_action('woocommerce_order_status_changed', 'sf_action_woocommerce_order_status_changed', 10, 3); 