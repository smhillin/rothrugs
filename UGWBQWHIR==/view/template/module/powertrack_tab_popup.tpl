<h4>Popup settings</h4>
<p hook-d4071>
  Advanced feature (EXTRA) available only after purchase. 
  Get it here <a href="https://www.prowebtec.com/powertrack-shipment-popup" target="_blank">www.prowebtec.com/powertrack-shipment-popup</a>
</p>
<hr />

<div class="form-group">
    <label class="col-sm-3 control-label">
      Show popup when order status is:
      <br>
      <small style="font-weight: normal;">Check your "Shipped" order statuses</small>
    </label>
    <div class="col-sm-9">
      <div class="well well-sm" style="height: 290px; overflow: auto; resize: vertical;">
        <?php foreach ($order_statuses as $order_status) { ?>
        <div class="checkbox">
          <label>
            <input type="checkbox" name="powertrack_cfg_show_popup_for_these_statuses[]" value="<?php echo $order_status['order_status_id']; ?>" <?php echo in_array($order_status['order_status_id'], $powertrack_cfg_show_popup_for_these_statuses) ? 'checked' : ''; ?> />
            <?php echo $order_status['name']; ?>
          </label>
        </div>
        <?php } ?>
      </div>
      <small class="help-block">Show popup to logged in customers when you update their order status to one of the checked statuses</small>
    </div>
</div>