<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <table class="form">
        <tr>
          <td><?php echo $entry_date_start; ?>
            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" /></td>
          <td><?php echo $entry_date_end; ?>
            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" /></td>
          <td><?php echo $entry_store; ?>
            <select name="filter_store_id">
              <option value="" selected="selected"><?php echo $text_all_stores; ?></option>
              <?php foreach ($stores as $store) { ?>
              <?php if ($store['store_id'] == $filter_store_id) { ?>
              <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>			
          <td><?php echo $entry_orderwise; ?>
            <input type="checkbox" name="filter_orderwise" <?php echo $filter_orderwise=='1'?'checked':''; ?> id="filter_orderwise"  /></td>			
		  
          <td style="text-align: right;"><a onclick="filter(1);" class="button"><?php echo $button_print; ?></a>&nbsp;<a onclick="filter(0);" class="button"><?php echo $button_filter; ?></a></td>
        </tr>		
      </table>
      <table class="list">
        <thead>
          <tr>
			<?php if ($filter_orderwise) { ?>
				<td class="right"><?php echo $column_ponumber; ?></td>
				<td class="right"><?php echo $column_povalue; ?></td>
				<td class="right"><?php echo $column_purinvno; ?></td>
				<td class="right"><?php echo $column_purinvvalue; ?></td>
				<td class="right"><?php echo $column_salesorder; ?></td>
			<?php } else {?>
				<td class="left"><?php echo $column_store; ?></td>
				<td class="right"><?php echo $column_ordercount; ?></td>
				<td class="right"><?php echo $column_povalue; ?></td>
				<td class="right"><?php echo $column_purinvvalue; ?></td>
			<?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php if ($suppreporting) { ?>
          <?php foreach ($suppreporting as $suppreport) { ?>
          <tr>
			<?php if ($filter_orderwise) { ?>
				<td class="right"><a href='index.php?route=pomgmt/po/update&token=<?php echo $token; ?>&order_id=<?php echo $suppreport['order_id']; ?>'><?php echo $suppreport['order_id']; ?></a></td>
				<td class="right"><?php echo $suppreport['pur_value']; ?></td>
				<td class="right"><?php echo $suppreport['poinvoice_ref']; ?></td>
				<td class="right"><?php echo $suppreport['inv_value']; ?></td>
				<td class="right">
				<?php if ($suppreport['sales_order_id']!=0) { ?>
				<a href='index.php?route=sale/order/info&token=<?php echo $token; ?>&order_id=<?php echo $suppreport['sales_order_id']; ?>'><?php echo $suppreport['sales_order_id']; ?></a>
				<?php } else {?>
				<?php echo $suppreport['sales_order_id']; ?>
				<?php } ?>
			<?php } else {?>
				<td class="left"><?php echo $suppreport['store_name']; ?></td>
				<td class="right"><?php echo $suppreport['ordercount']; ?></td>
				<td class="right"><?php echo $suppreport['pur_value']; ?></td>
				<td class="right"><?php echo $suppreport['inv_value']; ?></td>
			<?php } ?>
          </tr>
          <?php } ?>
		  <tr>
			<?php if ($filter_orderwise) { ?>
				<td class="right"><strong>Total</strong></td>
				<td class="right"><strong><?php echo $poTotal; ?></strong></td>
				<td class="right"></td>
				<td class="right"><strong><?php echo $poValueTotal; ?></strong></td>
				<td class="right"></td>
			<?php } else {?>
				<td class="right"><strong>Total</strong></td>
				<td class="right"></td>
				<td class="right"><strong><?php echo $poTotal; ?></strong></td>
				<td class="right"><strong><?php echo $poValueTotal; ?></strong></td>
			<?php } ?>
          </tr>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="<?php if ($filter_orderwise) echo '5'; else echo '4'; ?>"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter(isPrint) {

	url = 'index.php?route=pomgmt/suppreporting&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_store_id = $('select[name=\'filter_store_id\']').attr('value');
	
	if (filter_store_id != "") {
		url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
	}	
	
	var filter_orderwise = $('input[name=\'filter_orderwise\']').attr('checked');
	
	if (filter_orderwise == 'checked') {
		url += '&filter_orderwise=1' ;
	}	
	
	if (isPrint==1) {
		url += '&print=1';
		window.open(url);
	}
	else
		location = url; 
}
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<?php echo $footer; ?>