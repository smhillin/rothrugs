<h4>Other settings</h4>
<hr />

<div class="form-group">
  <label class="col-sm-3 control-label" for="powertrack_cfg_show_tracking_column_in_order_list">Show powertrack tracking column in order list</label>
  <div class="col-sm-9">
      <div class="form-control" style="border: none; box-shadow: none;">
      
      <?php if ($powertrack_cfg_show_tracking_column_in_order_list) { ?>
      <input type="radio" name="powertrack_cfg_show_tracking_column_in_order_list" value="1" checked="checked" id="powertrack_cfg_show_tracking_column_in_order_list" />
      <?php echo $text_yes; ?>
      <input type="radio" name="powertrack_cfg_show_tracking_column_in_order_list" value="0" />
      <?php echo $text_no; ?>
      <?php } else { ?>
      <input type="radio" name="powertrack_cfg_show_tracking_column_in_order_list" value="1" />
      <?php echo $text_yes; ?>
      <input type="radio" name="powertrack_cfg_show_tracking_column_in_order_list" value="0" checked="checked" />
      <?php echo $text_no; ?>
      <?php } ?>
      
      </div>
  </div>
</div>                


<div class="form-group">
  <label class="col-sm-3 control-label" for="powertrack_cfg_default_company_id">Default company</label>
  <div class="col-sm-5" style="margin-left: 14px;">
    <select class="form-control" name="powertrack_cfg_default_company_id">
        <option value="">Shipping company</option>
        <?php foreach ($powertrack_companies as $company) { ?>
        <?php if(empty($company['company_name'])) continue; ?>
        <option value="<?php echo $company['company_id']; ?>" <?php if($company['company_id'] == $powertrack_cfg_default_company_id) echo 'selected'; ?>><?php echo $company['company_name']; ?></option>
        <?php } ?>
    </select>
    <small>
        Set a company as default to use it automatically when you enter a tracking code but you don't select the company. 
    </small>
  </div>
</div> 

<div class="form-group">
  <label class="col-sm-3 control-label" for="powertrack_cfg_log"><span data-toggle="tooltip" title="Verbose logs will be printed to the opencart log file. Useful when you need to figure out what is going on.">Enable verbose logs:</span></label>
  <div class="col-sm-9">
      <div class="form-control" style="border: none; box-shadow: none;">
      
          <?php if ($powertrack_cfg_log) { ?>
          <input type="radio" name="powertrack_cfg_log" value="1" checked="checked" id="powertrack_cfg_log" />
          <?php echo $text_yes; ?>
          <input type="radio" name="powertrack_cfg_log" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="powertrack_cfg_log" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="powertrack_cfg_log" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?>
          
      </div>
  </div>
</div>