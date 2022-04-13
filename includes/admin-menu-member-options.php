<?php 
/**
 * Adding member options page menu
 */
function sf_member_options_admin_menu() {
	add_menu_page(
		'Membership Variations',
		'Membership Variations',
		'manage_options',
		'member_options',
		'sf_member_registration_form_view',
		'dashicons-admin-page',
		58
	);

}

add_action('admin_menu', 'sf_member_options_admin_menu');

/**
 * Include member options page view
 */
function sf_member_registration_form_view() {
	$edit_pid 	 	= isset($_REQUEST['edit_pid']) ? $_REQUEST['edit_pid'] : 0;
	$remove_member 	= isset($_REQUEST['remove_membership']) ? $_REQUEST['remove_membership'] : 0;
	$member_type 	= isset($_REQUEST['member_type']) ? $_REQUEST['member_type'] : '';
	if ($edit_pid > 0 && $member_type != '') {
		include SF_CHILD_THEME_DIR . '/member-options-views/member-options-edit-view.php';
	}
	elseif ($remove_member > 0) {
		include SF_CHILD_THEME_DIR . '/member-options-views/member-options-remove.php';
	}
	else {
		include SF_CHILD_THEME_DIR . '/member-options-views/member-options-view.php';
	}	
}

/**
 * Adding member options page menu for discount
 */
function sf_member_discount_options_admin_menu() {
	add_menu_page(
		'Membership Discount',
		'Membership Discount',
		'manage_options',
		'member_discount',
		'sf_member_discount_form_view',
		'dashicons-admin-page',
		59
	);

}

add_action('admin_menu', 'sf_member_discount_options_admin_menu');

/**
 * Include member discount options page view
 */
function sf_member_discount_form_view() {
	$action 	 	= isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
	if ($action == 'edit') {
		include SF_CHILD_THEME_DIR . '/member-discount-options-views/member-discount-edit-view.php';
	}
	elseif ($action == 'delete') {
		include SF_CHILD_THEME_DIR . '/member-discount-options-views/member-discount-delete-view.php';
	}
	else {
		include SF_CHILD_THEME_DIR . '/member-discount-options-views/member-discount-list-view.php';
	}
}

/**
 * Adding member options page menu for discount
 */
function sf_member_renew_product_price_menu() {
	add_menu_page(
		'Membership Renew Price',
		'Membership Renew Price',
		'manage_options',
		'member_renew_price',
		'sf_member_renew_price_form_view',
		'dashicons-admin-page',
		60
	);

}

add_action('admin_menu', 'sf_member_renew_product_price_menu');

/**
 * Include member renew price page view
 */
function sf_member_renew_price_form_view() {
	$action 	 	= isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
	if ($action == 'edit') {
		include SF_CHILD_THEME_DIR . '/member-options-views/member-discount-edit-view.php';
	}
	elseif ($action == 'delete') {
		include SF_CHILD_THEME_DIR . '/member-options-views/member-discount-delete-view.php';
	}
	else {
		include SF_CHILD_THEME_DIR . '/member-options-views/member-renew-price-form-view.php';
	}
}