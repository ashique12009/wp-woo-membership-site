<?php 
    $status_code = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
    $primary_id = isset($_REQUEST['primary_id']) ? $_REQUEST['primary_id'] : 0;
    $edit_pid = isset($_REQUEST['edit_pid']) ? $_REQUEST['edit_pid'] : 0;
    $member_type = isset($_REQUEST['member_type']) ? $_REQUEST['member_type'] : '';
    $result = get_membership_renew_info($edit_pid, $member_type);

    if (count($result) == 0)
        $status_code = 6;
?>

<div class="container member-form-container">

    <?php if ($status_code == 1) : ?>
    <div class="alert alert-danger"><?php echo 'Membership type is missing!';?></div>
    <?php elseif ($status_code == 2) : ?>
    <div class="alert alert-danger"><?php echo 'Start date or end date is missing!';?></div>
    <?php elseif ($status_code == 3) : ?>
    <div class="alert alert-success"><?php echo 'Successfully updated!';?></div>
    <?php elseif ($status_code == 4) : ?>
    <div class="alert alert-danger"><?php echo 'Durplcate membership renew package is found!';?></div>
    <?php elseif ($status_code == 5) : ?>
    <div class="alert alert-danger"><?php echo 'Something went wrong!';?></div>
    <?php elseif ($status_code == 6) : ?>
    <div class="alert alert-danger"><?php echo 'Wrong product and member type selected!';?></div>
    <?php endif; ?>

    <?php if ($status_code != 6) : ?>
        <h3>Edit renew membership information:</h3>
        <form action="<?php echo esc_url(admin_url('admin-post.php'));?>" method="post" class="sf-form">
        <input type="hidden" name="action" value="renew_membership_type_duration_price_edit_action">
        <input type="hidden" name="primary_id" value="<?php echo $primary_id;?>">
        <?php $nonce = wp_create_nonce('renew_membership_type_duration_price_edit_nonce'); ?>
        <input type="hidden" name="nonce" value="<?php echo $nonce;?>">
        <div class="row">
            <div class="form-group col-md-6 col-xs-12">
            <label for="select_member_product">Select Membership Product:</label>
            <?php 
                $category = get_term_by('slug', 'membership', 'product_cat');
                $cat_id = $category->term_id;
                
                $prod_categories = [$cat_id];
                $product_args = [
                    'post_type' => ['product'],
                    'orderby' => 'ID',
                    'order' => 'ASC',
                ];

                if (!empty($prod_categories)) {
                    $product_args['tax_query'] = [
                        [
                            'taxonomy' => 'product_cat',
                            'field' => 'id',
                            'terms' => $prod_categories,
                            'operator' => 'IN',
                        ]
                    ];
                }

                $products = get_posts($product_args);
            ?>
            <select name="select_member_product" id="select_member_product" class="form-control">
                <?php foreach ($products as $key => $value) : ?>
                    <option value="<?php echo $value->ID;?>" <?php echo ($value->ID == $edit_pid) ? 'selected' : '';?>><?php echo $value->post_title;?></option>
                <?php endforeach;?>
            </select>
            <small id="select_member_product" class="form-text text-muted">Products which belong to membership category</small>
            </div>
            
            <div class="form-group col-md-6 col-xs-12">
            <label for="member_type">Membership Type:</label>
            <input type="text" class="form-control" id="member_type" name="member_type" value="<?php echo $member_type;?>">
            <small id="member_type" class="form-text text-muted">It can be E.g: Individual, Family etc.</small>
            </div>

            <div class="form-group col-md-6 col-xs-12">
            <label for="price">Set Time Duration:</label>
            <input type="number" class="form-control" id="duration" name="duration" value="<?php echo $result[0]->duration;?>" step="1">
            </div>

            <div class="form-group col-md-6 col-xs-12">
            <label for="price">Select Day, Month or Year:</label>
            <select name="duration_day_month_year" id="duration_day_month_year" class="form-control">
                <option value="day" <?php echo ($result[0]->day_month_year == 'day') ? 'selected' : '';?>>Day</option>
                <option value="month" <?php echo ($result[0]->day_month_year == 'month') ? 'selected' : '';?>>Month</option>
                <option value="year" <?php echo ($result[0]->day_month_year == 'year') ? 'selected' : '';?>>Year</option>
            </select>
            </div>

            <div class="form-group col-md-6 col-xs-12">
            <label for="price">Fee Price:</label>
            <input type="number" class="form-control" id="price" name="price" value="<?php echo $result[0]->renew_price;?>" step="0.01">
            </div>
        </div>         
        <input type="submit" class="btn btn-primary" value="Update">
        </form>
    <?php endif;?>
</div>