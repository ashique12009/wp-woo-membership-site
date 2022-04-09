<?php $status_code = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';?>

<div class="container member-form-container">

    <?php if ($status_code == 1) : ?>
    <div class="alert alert-danger"><?php echo 'Membership type is missing!';?></div>
    <?php elseif ($status_code == 2) : ?>
    <div class="alert alert-danger"><?php echo 'Start date or end date is missing!';?></div>
    <?php elseif ($status_code == 3) : ?>
    <div class="alert alert-success"><?php echo 'Successfully submitted!';?></div>
    <?php elseif ($status_code == 4) : ?>
    <div class="alert alert-danger"><?php echo 'Duplicate data found! Please choose another name or date!';?></div>
    <?php elseif ($status_code == 5) : ?>
    <div class="alert alert-danger"><?php echo 'Something went wrong!';?></div>
    <?php elseif ($status_code == 6) : ?>
    <div class="alert alert-success"><?php echo 'Removed successfully!';?></div>
    <?php endif; ?>

    <h3>Enter price for each type of membership:</h3>
    <form action="<?php echo esc_url(admin_url('admin-post.php'));?>" method="post" class="sf-form">
    <input type="hidden" name="action" value="membership_type_duration_price_action">
    <?php $nonce = wp_create_nonce('membership_type_duration_price'); ?>
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
                <option value="<?php echo $value->ID;?>"><?php echo $value->post_title;?></option>
            <?php endforeach;?>
        </select>
        <small id="select_member_product" class="form-text text-muted">Products which belong to membership category</small>
        </div>
        
        <div class="form-group col-md-6 col-xs-12">
        <label for="member_type">Membership Type:</label>
        <input type="text" class="form-control" id="member_type" name="member_type" value="">
        <small id="member_type" class="form-text text-muted">It can be E.g: Individual, Family etc.</small>
        </div>

        <div class="form-group col-md-6 col-xs-12">
        <label for="state_date">Start Date:</label>
        <input type="date" class="form-control" id="start_date" name="start_date" value="">
        </div>

        <div class="form-group col-md-6 col-xs-12">
        <label for="end_date">End Date:</label>
        <input type="date" class="form-control" id="end_date" name="end_date" value="">
        </div>

        <div class="form-group col-md-6 col-xs-12">
        <label for="price">Fee Price:</label>
        <input type="number" class="form-control" id="price" name="price" value="" step="0.01">
        </div>
    </div>         
    <input type="submit" class="btn btn-primary" value="Submit">
    </form>
</div>

<?php if (get_count_membership_types() > 0) :?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h3>Membership type list:</h3>
                <?php $result = get_membership_type_list();?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Member product name</th>
                            <th>Membership type</th>
                            <th>Start date</th>
                            <th>End date</th>
                            <th>Total days</th>
                            <th>Fee price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $key => $value) : ?>
                            <tr>
                                <td><?php echo get_the_title($value->post_id);?></td>
                                <td><?php echo $value->type;?></td>
                                <td><?php echo $value->start_date;?></td>
                                <td><?php echo $value->end_date;?></td>
                                <td>
                                <?php 
                                    $start_date = strtotime($value->start_date);
                                    $end_date = strtotime($value->end_date);
                                    $diff = $end_date - $start_date;
                                    echo $total_days = round($diff / (60 * 60 * 24));
                                ?>
                                </td>
                                <td><?php echo $value->price;?></td>
                                <td>
                                    <a class="btn btn-success btn-sm" href="<?php echo admin_url('admin.php?page=member_options&primary_id='.$value->id.'&edit_pid=' . $value->post_id . '&member_type=' . $value->type);?>">Edit</a>
                                    <a class="btn btn-danger btn-sm" href="<?php echo admin_url('admin.php?page=member_options&primary_id='.$value->id.'&remove_membership=1');?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif ;?>