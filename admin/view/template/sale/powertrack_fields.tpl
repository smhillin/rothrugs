<div class="form-group">
  <label class="col-sm-2 control-label" for="powertrack_carrier"><?php echo $powertrack_text_ship_company; ?></label>
  <div class="col-sm-10">
        <select id="powertrack_carrier" name="powertrack_carrier" class="form-control">
          <option value="" selected="selected">- - - &nbsp; select &nbsp; - - -</option>
          <?php foreach($powertrack_companies as $powertrack_company) { ?>
              <option value="<?php echo $powertrack_company['company_id'] ?>"><?php echo $powertrack_company['company_name'] ?></option>
          <?php } ?>
        </select>
  </div>
</div>

<div class="form-group">
  <label class="col-sm-2 control-label" for="powertrack_trackcode"><?php echo $powertrack_text_tracking_code; ?></label>
  <div class="col-sm-10">
    <input id="powertrack_trackcode" 
           type="text" 
           name="powertrack_trackcode" 
           class="form-control" 
           placeholder="tracking number" 
           onkeydown = "if (event.keyCode == 13) {document.getElementById('button-history').click(); return false;}" />
  </div>
</div>

<?php echo $other_fields; ?>