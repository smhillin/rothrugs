<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	    <a href="<?php echo $create; ?>" data-toggle="tooltip" title="<?php echo $button_create; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
	    <button type="submit" id="button-invoice" form="form" formtarget="_blank" formaction="<?php echo $invoice; ?>" data-toggle="tooltip" title="<?php echo $button_printpo; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>
       <button type="submit" id="button-shipping" form="form" formaction="<?php echo $email; ?>" data-toggle="tooltip" title="<?php echo $button_email; ?>" class="btn btn-info"><i class="fa fa-envelope-o"></i></button> 
	   <button type="submit" id="button-shipping" form="form" formaction="<?php echo $remind; ?>" data-toggle="tooltip" title="<?php echo $button_remind; ?>" class="btn btn-info"><i class="fa fa-bell"></i></button> 
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-list"></i><?php echo $text_list; ?></h3>
			</div>
			<div class="panel-body">
				<div class="well">
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label" for="filter_porder_id"><?php echo $entry_orderid; ?></label>
						   <input type="text"  id="filter_porder_id" name="filter_porder_id" value="<?php echo $filter_porder_id; ?>" placeholder="<?php echo $entry_orderid; ?>" class="form-control" />
						  </div>
					</div>
					  <div class="col-sm-4">
						 <div class="form-group">
							<label class="control-label" for="filter_order_id"><?php echo $entry_salesorderid; ?></label>
						   <input type="text"  id="filter_order_id" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_salesorderid; ?>" class="form-control" />
						  </div>
					  </div>
					   <div class="col-sm-4">
						 <div class="form-group">
							<label class="control-label" for="filter_refno"><?php echo $entry_poinvoice_ref; ?></label>
						   <input type="text"  id="filter_refno" name="filter_refno" value="<?php echo $filter_refno; ?>" placeholder="<?php echo $entry_poinvoice_ref; ?>" class="form-control" />
						  </div>
					  </div>
					  <div class="col-sm-4">
						 <div class="form-group">
							<label class="control-label" for="filter_supplier_id"><?php echo $entry_supplier; ?></label>
						   <select name="filter_supplier_id" id="filter_supplier_id" class="form-control">
							  <option value="*"></option>                  
							  <?php if (!empty($suppliers)) {
							  foreach ($suppliers as $supplier) { ?>
							  <?php if ($supplier['supplier_id'] == $filter_supplier_id) { ?>
							  <option value="<?php echo $supplier['supplier_id']; ?>" selected="selected"><?php echo $supplier['name']; ?></option>
							  <?php } else { ?>
							  <option value="<?php echo $supplier['supplier_id']; ?>"><?php echo $supplier['name']; ?></option>
							  <?php } ?>
							  <?php } } ?>
						</select>
						  </div>
					  </div>
					   <div class="col-sm-4">
						 <div class="form-group">
							<label class="control-label" for="filter_total"><?php echo $entry_total; ?></label>
							   <input type="text"  id="filter_total" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" class="form-control" />
						  </div>
					  </div>
					  <div class="col-sm-4">
						 <div class="form-group">
							<label class="control-label" for="filter_total"><?php echo $entry_status; ?></label>
							  <select name="filter_order_status_id" id="filter_order_status_id" class="form-control">
								  <option value="*"></option>
								  <?php foreach ($order_statuses as $order_status) { ?>
								  <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
								  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
								  <?php } else { ?>
								  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								  <?php } ?>
								  <?php } ?>
							</select>
						  </div>
					  </div>
					   <div class="col-sm-4">
						 <div class="form-group">
							<label class="control-label" for="filter_delay"><?php echo $entry_delay; ?></label>
							 <select name="filter_delay" id="filter_delay" class="form-control">
							  <option value="*" selected="selected">All</option>
							  <option value="3" <?php if ($filter_delay=="3") echo "selected"; ?>>>3</option>
							  <option value="5" <?php if ($filter_delay=="5") echo "selected"; ?>>>5</option>
							  <option value="7" <?php if ($filter_delay=="7") echo "selected"; ?>>>7</option>
							  <option value="10" <?php if ($filter_delay=="10") echo "selected"; ?>>>10</option>
							  <option value="15" <?php if ($filter_delay=="15") echo "selected"; ?>>>15</option>
							</select>	
						  </div>
					  </div>
					  <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
					</div>
				</div>

			


				<form method="post" enctype="multipart/form-data"  id="form">
				<div class="table-responsive">
				<table class="table table-bordered table-hover">
				 <thead>
				   <tr>
				  <td width="1" style="text-ali: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
				  <td class="text-right"><?php if ($sort == 'po.order_id') { ?>
					<a href="<?php echo $sort_orderid; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_orderid; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_orderid; ?>"><?php echo $column_orderid; ?></a>
					<?php } ?></td>
				  <td class="text-right"><?php if ($sort == 'po.sales_order_id') { ?>
					<a href="<?php echo $sort_salesorderid; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_salesorderid; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_salesorderid; ?>"><?php echo $column_salesorderid; ?></a>
					<?php } ?></td>
				  <td class="text-left"><?php if ($sort == 'po.poinvoice_ref') { ?>
					<a href="<?php echo $sort_poinvoice_ref; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_poinvoice_ref; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_poinvoice_ref; ?>"><?php echo $column_poinvoice_ref; ?></a>
					<?php } ?></td>				
				  <td class="text-left"><?php if ($sort == 'supplier') { ?>
					<a href="<?php echo $sort_supplier; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_supplier; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_supplier; ?>"><?php echo $column_supplier; ?></a>
					<?php } ?></td>				
				  <td class="text-right"><?php if ($sort == 'total') { ?>
					<a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
					<?php } ?></td>
					<td class="text-left"><?php if ($sort == 'status') { ?>
					<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
					<?php } ?></td>	
					<td class="text-right"><?php if ($sort == 'delay') { ?>
					<a href="<?php echo $sort_delay; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_delay; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_delay; ?>"><?php echo $column_delay; ?></a>
					<?php } ?></td>					
				  <td class="text-right"><?php echo $column_action; ?></td>
				</tr>
				 </thead>
					<tbody>
					 <?php if ($orders) { ?>
				<?php foreach ($orders as $order) { ?>
				<tr>
				  <td style="text-align: center;"><?php if ($order['selected']) { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
					<?php } ?></td>
				 <td class="text-right"><a href='index.php?route=pomgmt/po/edit&token=<?php echo $token; ?>&order_id=<?php echo $order['order_id']; ?>'><?php echo $order['order_id']; ?></a></td>
				  <td class="text-right">
				  
				  <?php 
					if ($order['sales_order_id'] =='-NA-')
						echo '-NA-';
					else {?>
						<a href='index.php?route=sale/order/info&token=<?php echo $token; ?>&order_id=<?php echo $order['sales_order_id']; ?>'>
						<?php echo $order['sales_order_id']; ?>
						</a>
					<?php } ?>
					
				  
				  
				  </td>
				  <td class="text-left"><?php echo $order['poinvoice_ref']; ?></td>
				  <td class="text-left"><?php echo $order['supplier']; ?></td>
				  <td class="text-right"><?php echo $order['total']; ?></td>
				  <td class="text-left"><?php echo $order['status']; ?></td>			  
				  <td class="text-right"><?php echo $order['delay']; ?></td>
				  <td class="text-right"><a data-original-title="<?php echo $text_edit; ?>" href="<?php echo $order['update']; ?>" data-toggle="tooltip" title="" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
				  <a href="<?php echo $order['delete']; ?>" id="button-delete<?php echo $order['order_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
				  <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
				</tr>
				<?php } ?>
					</tbody>
				</table>
				</div>
				</form>


				<div class="row">
				<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
				<div class="col-sm-6 text-right"><?php echo $results; ?></div>
				</div>
			</div>
		</div>
	</div>	
<script type="text/javascript"><!--
$("#button-filter").click(function(){
	url = 'index.php?route=pomgmt/po&token=<?php echo $token; ?>';
	
	var filter_order_id = $('#filter_order_id').val();
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	var filter_porder_id = $('#filter_porder_id').val();
	if (filter_porder_id) {
		url += '&filter_porder_id=' + encodeURIComponent(filter_porder_id);
	}
	var filter_refno = $('#filter_refno').val();
	if (filter_refno) {
		url += '&filter_refno=' + encodeURIComponent(filter_refno);
	}
	var filter_order_status_id = $('#filter_order_status_id').val();
	if (filter_order_status_id != '*') {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	
	var filter_supplier_id = $('#filter_supplier_id').val();
	if (filter_supplier_id != '*') {
		url += '&filter_supplier_id=' + encodeURIComponent(filter_supplier_id);
	}	
	var filter_total = $('#filter_total').val();
	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}	
	var filter_delay = $('#filter_delay').val();
	if (filter_delay!="*") {
		url += '&filter_delay=' + encodeURIComponent(filter_delay);
	}	

				
	location = url;
});
$('a[id^=\'button-delete\']').on('click', function(e) {
	e.preventDefault();
	
	if (confirm('<?php echo $text_confirmdelete; ?>')) {
		location = $(this).attr('href');
	}
});
//--></script> 
</div>
<?php echo $footer; ?>