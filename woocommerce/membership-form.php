<?php 
    $current_user = wp_get_current_user();
    $current_page_url = get_permalink();
    $error_code = isset($_REQUEST['error']) ? $_REQUEST['error'] : '';
    global $product;
    $pid = $product->get_id();
?>

<?php if ($error_code == 1) : ?>
    <div class="alert alert-danger"><?php echo 'First name of last name is missing!';?></div>
<?php elseif ($error_code == 2) : ?>
    <div class="alert alert-danger"><?php echo 'Email and confirm email is not matched!';?></div>
<?php elseif ($error_code == 3) : ?>
    <div class="alert alert-danger"><?php echo 'Please select membership type and duration!';?></div>
<?php elseif ($error_code == 4) : ?>
    <div class="alert alert-danger"><?php echo 'Something went wrong!';?></div>
<?php elseif ($error_code == 5) : ?>
    <div class="alert alert-danger"><?php echo 'You are not logged in!';?></div>
<?php endif; ?>

<form id="membership-purchase-form" action="<?php echo esc_url(admin_url('admin-post.php'));?>" method="post">
    <input type="hidden" name="post_id" value="<?php echo $pid;?>">
    <input type="hidden" name="action" value="membership_action">
    <input type="hidden" name="current_page_url" value="<?php echo $current_page_url;?>">
    <?php $nonce = wp_create_nonce('membership_nonce'); ?>
    <input type="hidden" name="nonce" value="<?php echo $nonce;?>">
    <div class="row">
        <div class="form-group col-md-6 col-xs-12">
            <label for="first_name">First name:</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $current_user->billing_first_name;?>">
        </div>

        <div class="form-group col-md-6 col-xs-12">
            <label for="last_name">Last name:</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $current_user->billing_last_name;?>">
        </div>

        <div class="form-group col-md-6 col-xs-12">
            <label for="street">Contact address (street):</label>
            <input type="text" class="form-control" id="street" name="street">
        </div>

        <div class="form-group col-md-6 col-xs-12">
            <label for="country">Country:</label>
            <input type="text" class="form-control" id="country" name="country">
        </div>

        <div class="form-group col-md-6 col-xs-12">
            <label for="dob">Date of birth:</label>
            <input type="date" class="form-control datepicker" id="dob" name="dob">
        </div>

        <div class="form-group col-md-6 col-xs-12">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $current_user->billing_email;?>">
        </div>
        
        <div class="form-group col-md-6 col-xs-12">
            <label for="cemail">Confirm email:</label>
            <input type="email" class="form-control" id="cemail" name="cemail" value="<?php echo $current_user->billing_email;?>">
        </div>

        <div class="form-group col-md-6 col-xs-12">
            <label for="cno">Contact number(with country code):</label>
            <input type="text" class="form-control" id="cno" name="cno">
        </div>

        <div class="form-group col-md-6 col-xs-12">
            <label for="mtype">Membership type and duration:</label>
            <?php $member_product_types = get_member_products($pid);?>
            <select name="mtype_duration" id="mtype_duration" class="form-control">
                <option value="0">-- Select membership type --</option>
                    <?php foreach ($member_product_types as $value) : ?>
                    <?php 
                        $start_date = strtotime($value->start_date);
                        $end_date = strtotime($value->end_date);
                        $diff = $end_date - $start_date;
                        $total_days = round($diff / (60 * 60 * 24));
                    ?>
                    <option value="<?php echo $value->id;?>"><?php echo $value->type . ' - ' . $value->start_date . ' TO ' . $value->end_date . ' - ' . '(' . $total_days . ' days' . ')';?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group col-md-6 col-xs-12">
            <label for="mcharge">Membership charge:</label>
            <input type="text" class="form-control" id="mcharge" name="mcharge" value="" readonly>
        </div>

    </div>         
    <input type="submit" class="btn btn-primary" value="Submit">
</form>