<?php 

// Define two important constant
if (!defined('SF_PARENT_THEME_DIR')) {
    define('SF_PARENT_THEME_DIR', get_template_directory());
}

if (!defined('SF_PARENT_THEME_URI')) {
    define('SF_PARENT_THEME_URI', get_template_directory_uri());
}

if (!defined('SF_CHILD_THEME_DIR')) {
    define('SF_CHILD_THEME_DIR', get_stylesheet_directory());
}

if (!defined('SF_CHILD_THEME_URI')) {
    define('SF_CHILD_THEME_URI', get_stylesheet_directory_uri());
}

include 'includes/member-options-schema.php';
include 'includes/admin-menu-member-options.php';
include 'includes/member-options-submission.php';
include 'includes/member-discount-submission.php';
include 'includes/member-queries.php';
include 'includes/update-user-meta.php';
include 'includes/woo-hook-tasks.php';
include 'includes/membership-product-submission.php';
include 'includes/member-handle-ajax.php';

function sf_enqueue_styles() {
    wp_enqueue_style('parent-style', SF_PARENT_THEME_URI . '/style.css');
    wp_enqueue_style('child-style', SF_CHILD_THEME_URI . '/style.css', false, time(), 'all');
    wp_enqueue_script('custom-global-script', SF_CHILD_THEME_URI . '/js/custom-global-script.js', ['jquery'], time(), true);
    wp_enqueue_script('member-frontend-script', SF_CHILD_THEME_URI . '/js/membership-frontend-script.js', ['jquery'], time(), true);
    $localize_var = [
        'ajax_url' => admin_url('admin-ajax.php'),
        'member_fee_nonce' => wp_create_nonce('member_fee_nonce')
    ];
    wp_localize_script('member-frontend-script', 'member_vars', $localize_var);
}

add_action('wp_enqueue_scripts', 'sf_enqueue_styles');

function sf_enqueue_admin_script($hook) {
    if ( 'toplevel_page_member_options' != $hook && 'toplevel_page_member_discount' != $hook && 'toplevel_page_member_renew_price' != $hook) {
        return;
    }
    wp_enqueue_style( 'admin-bootstrap-style', '//maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', [], time(), 'all');
    wp_enqueue_style( 'admin-style', SF_CHILD_THEME_URI . '/admin-style.css', [], time(), 'all');
}

add_action( 'admin_enqueue_scripts', 'sf_enqueue_admin_script' );