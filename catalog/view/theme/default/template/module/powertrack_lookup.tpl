<?php echo $header; ?>

<!-- make journal2 happy with id=container -->
<div class="container" id="container">

  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <!--  <h3><?php echo "Track your orders"; ?></h3>   -->
          
          <?php if ($error_no_match) { ?>
              <div class="text-danger"><p class="text-danger"><?php echo $error_no_match; ?></p></div>
          <?php } ?>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
              <?php if ($error_email) { ?>
              <div class="text-danger"><p class="text-danger"><?php echo $error_email; ?></p></div>
              <?php } ?>
            </div>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-order-id"><?php echo "Order ID"; ?></label>
            <div class="col-sm-10">
              <input type="text" name="order_id" value="<?php echo $order_id; ?>" id="input-order-id" class="form-control" />
              <?php if ($error_order_id) { ?>
              <div class="text-danger"><p class="text-danger"><?php echo $error_order_id; ?></p></div>
              <?php } ?>
            </div>
          </div>
          
        </fieldset>
        <div class="buttons" style="overflow: visible; /*fix for shopme theme which shows scroll on the button*/">
          <div class="pull-right">
            <input class="btn btn-primary" type="submit" value="<?php echo "VIEW ORDER STATUS"; ?>" />
          </div>
        </div>
      </form>
      
      
      <div style="margin-top: 77px; margin-bottom: 77px" class="table-responsive">
          <table class="table table-bordered table-hover table-striped">
              <thead>
                <tr>
                  <th>Tracking No.</th>
                  <th>Company</th>
                  <th>Tracking link</th>
                </tr>
              </thead>
              <tbody>
              <?php if(! empty($powertrack_grid)) { ?>
                <?php foreach ($powertrack_grid as $powertrack_row) { ?>
                <tr>
                  <th scope="row"><?php echo $powertrack_row['powertrack_tracking_code']?></th>
                  <td><?php echo $powertrack_row['powertrack_carrier_name']?></td>
                  <td><a href="<?php echo $powertrack_row['powertrack_tracking_url']?>" target="_blank"><?php echo $powertrack_row['powertrack_tracking_url']?></a></td>
                </tr>
                <?php } ?>
              <?php } else {?>
                <tr>
                  <td colspan="3" style="text-align: center;"><h3>There is no tracking for this invoice.</h3></td>
                </tr>
              <?php }?>
              </tbody>
            </table>
      </div>
    
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
