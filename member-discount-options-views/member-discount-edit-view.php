<?php $pid = $_REQUEST['pid']; ?>

<?php 
$args = [
  'posts_per_page' => -1,
  'tax_query' => array(
    'relation' => 'OR',
    array(
      'taxonomy' => 'product_cat',
      'field' => 'slug',
      'terms' => 'membership'
    )
  ),
  'post_type' => 'product',
  'orderby' => 'id',
];

$loop = new WP_Query($args);
?>

<div class="container">
    <h2>Set membership discount for this product</h2>
    <hr />

    <form action="<?php echo esc_url(admin_url('admin-post.php'));?>" method="post">
        <div class="row">
            <input type="hidden" name="target_product_id" value="<?php echo $pid;?>">
            <input type="hidden" name="action" value="set_membership_discount_action">
            <?php $nonce = wp_create_nonce('set_membership_discount_nonce'); ?>
            <input type="hidden" name="nonce" value="<?php echo $nonce;?>">
            
            <div class="form-group col-md-12 col-xs-12">
                <label for="discount_price">Product name:</label>
                <label><?php echo get_the_title($pid);?></label>
            </div>

            <div class="form-group col-md-12 col-xs-12">
                <div class="row">
                    <div class="col-md-6">
                        <label for="discount_price">Select membership product:</label>
                        <select name="member_product_id" class="form-control">
                        <?php
                            if ($loop->have_posts()) {
                                while ($loop->have_posts()) {
                                    $loop->the_post();
                                    echo '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
                                }
                            }
                        ?>
                        </select>
                    </div>
                    <div class="col-md-6"></div>
                </div>                
            </div>

            <div class="form-group col-md-12 col-xs-12">
                <div class="row">
                    <div class="col-md-6">
                        <label for="discount_price">Set discount price:</label>
                        <input type="number" class="form-control" id="discount_price" name="discount_price" value="">
                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>
        </div>
        
        <input type="submit" class="btn btn-primary" value="Set discount">
    </form>
</div>