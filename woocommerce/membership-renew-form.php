<?php 
  $current_user = wp_get_current_user(); 
  $current_page_url = get_permalink();
  $error_code = isset($_REQUEST['renew-error']) ? $_REQUEST['renew-error'] : '';
?>

<?php if ($error_code == 1) : ?>
  <div class="alert alert-danger mt-25"><?php esc_html_e('First name of last name is missing!', 'ctp_2020');?></div>
<?php elseif ($error_code == 2) : ?>
  <div class="alert alert-danger mt-25"><?php esc_html_e('Email is empty!', 'ctp_2020');?></div>
<?php elseif ($error_code == 3) : ?>
  <div class="alert alert-danger mt-25"><?php esc_html_e('Please select membership type!', 'ctp_2020');?></div>
<?php elseif ($error_code == 4) : ?>
  <div class="alert alert-danger mt-25"><?php esc_html_e('Please select membership duration!', 'ctp_2020');?></div>
<?php elseif ($error_code == 5) : ?>
  <div class="alert alert-danger mt-25"><?php esc_html_e('Something went wrong!', 'ctp_2020');?></div>
<?php elseif ($error_code == 6) : ?>
  <div class="alert alert-danger mt-25"><?php esc_html_e('You were not in selected membership, so you cannot re-new it!', 'ctp_2020');?></div>
<?php endif; ?>

<div class="accordion mt-25" id="accordionExample">
  <div class="card accordion-card">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0 mt-0 text-center">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        <h3>Membership renew form</h3>
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
        <form action="<?php echo esc_url(admin_url('admin-post.php'));?>" method="post">
          <input type="hidden" name="post_id" value="<?php echo $pid;?>">
          <input type="hidden" name="action" value="membership_renew_action">
          <input type="hidden" name="current_page_url" value="<?php echo $current_page_url;?>">
          <?php $nonce = wp_create_nonce('membership_renew_nonce'); ?>
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
              <label for="email">Email:</label>
              <input type="email" class="form-control" id="email" name="email" value="<?php echo $current_user->billing_email;?>">
            </div>

            <div class="form-group col-md-6 col-xs-12">
              <label for="mtype">Membership type:</label>
              <select name="mtype" id="mtype-renew" class="form-control">
                <option value="0">-- Select membership type --</option>
                <option value="Individual">Individual</option>
                <option value="Family">Family (up-to 4 persons)</option>
              </select>
            </div>

            <div class="form-group col-md-6 col-xs-12">
              <label for="mduration">Membership duration:</label>
              <select name="mduration" id="mduration-renew" class="form-control">
                <option value="0">-- Select membership duration --</option>
                <option value="1 Year">1 Year</option>
                <option value="2 Year">2 Year</option>
                <option value="3 Year">3 Year</option>
              </select>
            </div>

            <?php 
              // $one_year_price_individual = ((get_field('fee_for_1_year_individual', $pid) == "" || get_field('fee_for_1_year_individual', $pid) == '0')) ? 0 : get_field('fee_for_1_year_individual', $pid);
              // $two_year_price_individual = ((get_field('fee_for_2_year_individual', $pid) == "" || get_field('fee_for_2_year_individual', $pid) == '0')) ? 0 : get_field('fee_for_2_year_individual', $pid);
              // $three_year_price_individual = ((get_field('fee_for_3_year_individual', $pid) == "" || get_field('fee_for_3_year_individual', $pid) == '0')) ? 0 : get_field('fee_for_3_year_individual', $pid);

              // $one_year_price_family = ((get_field('fee_for_1_year_family', $pid) == "" || get_field('fee_for_1_year_family', $pid) == '0')) ? 0 : get_field('fee_for_1_year_family', $pid);
              // $two_year_price_family = ((get_field('fee_for_2_year_family', $pid) == "" || get_field('fee_for_2_year_family', $pid) == '0')) ? 0 : get_field('fee_for_2_year_family', $pid);
              // $three_year_price_family = ((get_field('fee_for_3_year_family', $pid) == "" || get_field('fee_for_3_year_family', $pid) == '0')) ? 0 : get_field('fee_for_3_year_family', $pid);
            ?>
            <input type="hidden" id="fee_for_1_year_individual" value="<?php echo $one_year_price_individual;?>">
            <input type="hidden" id="fee_for_2_year_individual" value="<?php echo $two_year_price_individual;?>">
            <input type="hidden" id="fee_for_3_year_individual" value="<?php echo $three_year_price_individual;?>">

            <input type="hidden" id="fee_for_1_year_family" value="<?php echo $one_year_price_family;?>">
            <input type="hidden" id="fee_for_2_year_family" value="<?php echo $two_year_price_family;?>">
            <input type="hidden" id="fee_for_3_year_family" value="<?php echo $three_year_price_family;?>">

            <div class="form-group col-md-6 col-xs-12">
              <label for="mcharge">Membership charge:</label>
              <input type="text" class="form-control" id="mcharge-renew" name="mcharge" readonly>
            </div>

          </div>         
          <input type="submit" class="btn btn-primary" value="Renew">
        </form>
      </div>
    </div>
  </div>
</div>