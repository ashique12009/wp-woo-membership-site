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
        <button id="slideToggleBtn" class="btn btn-link collapsed" type="button">
        <h3>Membership renew form</h3>
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
        <form id="membership-renew-purchase-form" action="<?php echo esc_url(admin_url('admin-post.php'));?>" method="post">
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
              <label for="mtype">Membership type and duration:</label>
              <?php $member_product_types = get_member_products($pid);?>
              <select name="mtype_duration" id="renew_mtype_duration" class="form-control">
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
              <input type="text" class="form-control" id="mcharge-renew" name="mcharge" readonly>
            </div>

          </div>         
          <input type="submit" class="btn btn-primary" value="Renew">
        </form>
      </div>
    </div>
  </div>
</div>