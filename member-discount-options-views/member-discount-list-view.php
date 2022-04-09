<div class="container member-form-container">

    <h2>Membership set discount on product</h2>
    <hr />

    <?php 
        $args = [
            'posts_per_page' => -1,
            'post_type' => 'product',
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => 'membership',
                    'operator' => 'NOT IN'
                ],
            ],
        ];

        $loop = new WP_Query($args);
    ?>
    <?php if ($loop->have_posts()) : ?>
        <ul class="list-group">
            <?php 
                $discount_price = 0;
                while ( $loop->have_posts() ) : $loop->the_post();
                    global $product;
                    $pid = get_the_ID();
                    $build_discount_url = admin_url('admin.php?page=member_discount&action=edit&pid=' . $pid);

                    echo '<li class="list-group-item">
                    <span class="second-block span-block"><a href="' . $build_discount_url . '">' .  get_the_title() . '</a></span>
                    </li>';
                endwhile;
                wp_reset_query();
            ?>
        </ul>
    <?php endif; ?>
</div>

<?php if (get_count_membership_discounts() > 0) :?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h3>Applied discount price products:</h3>
                <?php $result = get_membership_discount_list();?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Discount product image</th>
                            <th>Discount product name</th>
                            <th>Membership product name</th>
                            <th>Discount amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $key => $value) : ?>
                            <tr>
                                <?php $build_discount_url = admin_url('admin.php?page=member_discount&action=edit&pid=' . $value->target_product_id); ?>
                                <td><a href="<?php echo $build_discount_url;?>"><?php echo get_the_post_thumbnail($value->target_product_id, [32, 32]);?></a></td>
                                <td><?php echo get_the_title($value->target_product_id);?></td>
                                <td><?php echo get_the_title($value->membership_product_id);?></td>
                                <td><?php echo $value->discount_price;?></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif ;?>