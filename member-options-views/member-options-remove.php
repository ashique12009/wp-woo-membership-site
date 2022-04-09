<?php $primary_id = isset($_REQUEST['primary_id']) ? $_REQUEST['primary_id'] : 0;?>

<?php 
if ($primary_id > 0) {
    $redirect_form_url = admin_url('admin.php?page=member_options');
    $url = '';
    global $wpdb;

    if ($wpdb->delete($wpdb->prefix . 'sf_member_options', ['id' => $primary_id]))
        $url = $redirect_form_url . '&status=6';
    else 
        $url = $redirect_form_url . '&status=5';
}
else {
    $url = $redirect_form_url . '&status=5';
}

wp_redirect($url);
exit; 