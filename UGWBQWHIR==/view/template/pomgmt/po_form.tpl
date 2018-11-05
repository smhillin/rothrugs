<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	  
	  <?php if (isset($email)) { ?>
		<button data-original-title="<?php echo $button_email; ?>" onclick="location.href='<?php echo $email; ?>'"  data-toggle="tooltip" title="" class="btn btn-info"><i class="fa fa-envelope"></i></button>
 	  <?php } ?>
	  <a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form class="form-horizontal" id="form-order">
          <ul id="order" class="nav nav-tabs nav-justified">
            <li class="disabled active"><a href="#tab-general" data-toggle="tab">1. <?php echo $tab_general; ?></a></li>
            <li class="disabled"><a href="#tab-cart" data-toggle="tab">2. <?php echo $tab_product; ?></a></li>            
            <li class="disabled"><a href="#tab-total" data-toggle="tab">3. <?php echo $tab_total; ?></a></li>
          </ul>
          <div class="tab-content">
           <div class="tab-pane active" id="tab-general">
				<div class="form-group">
					<label class="col-sm-2 control-label" ><?php echo $entry_orderid; ?></label>
					<div class="col-sm-10">
					<?php if  (empty($order_id)) { ?>
					<p class="form-control-static">-NA-</p>
					<?php } else { ?>
					<p class="form-control-static"><?php echo '#'.$order_id;?></p>
					<?php } ?>
					<input type="hidden" name="order_id" id="order_id" value="<?php echo($order_id); ?>">
					</div>
				</div>						
				<div class="form-group">
					<label class="col-sm-2 control-label" ><?php echo $entry_orderdate; ?></label>
					<div class="col-sm-10"> <p class="form-control-static"><?php echo $order_date; ?></p>
					</div>
				</div>						
				<div class="form-group">
					<label class="col-sm-2 control-label" ><?php echo $entry_salesorderid; ?></label>
							
					<div class="col-sm-10">
					<?php if  (empty($sales_order_id)) { ?>
					<p class="form-control-static">-NA-</p>
					<?php } else { ?>
					 <a href='index.php?route=sale/order/info&token=
									<?php echo $token; ?>
									&order_id=
									<?php echo $sales_order_id; ?>
									'>#
									<?php echo $sales_order_id; ?>
									</a>
							 <?php } ?>

					</div>						
				</div>		
				<div class="form-group">
					<label class="col-sm-2 control-label" for="poinvoice_ref"><?php echo $entry_poinvoice_ref; ?></label>
						<div class="col-sm-10">
							<input type="text" name="poinvoice_ref" value="<?php echo $poinvoice_ref; ?>" placeholder="<?php echo $poinvoice_ref; ?>" id="poinvoice_ref" class="form-control" />
						</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="inv_value"><?php echo $entry_poinvoice_value; ?></label>
					<div class="col-sm-10">
					<input type="text" name="inv_value" value="<?php echo $inv_value; ?>" placeholder="<?php echo $inv_value; ?>" id="inv_value" class="form-control" />
					</div>
				</div>				  
				<div class="form-group">
					<label class="col-sm-2 control-label" for="store_id"><?php echo $entry_store; ?></label>
					<div class="col-sm-10">
					<select name="store_id" id="store_id" <?php  if ($issupplier) { echo 'disabled'; } ?> class="form-control">
					  <option value="0"><?php echo $text_default; ?></option>
					  <?php foreach ($stores as $store) { ?>
					  <?php if ($store['store_id'] == $store_id) { ?>
					  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
					  <?php } else { ?>
					  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
					  <?php } ?>
					  <?php } ?>
					</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-supplier"><?php echo $entry_supplier;   if ($error_warning) $supplier_id='0';  ?>*</label>
					<div class="col-sm-10">
					<select name="supplier_id" id="input-supplier" <?php  if ($order_id!='' || $issupplier) { echo 'disabled'; } ?> class="form-control" onchange="updatecurrency(this.value);">
					  <option value="0" selected="selected"><?php echo $text_none; ?></option>
					  <?php foreach ($suppliers as $supplier) { ?>
					  <?php if ($supplier['supplier_id'] == $supplier_id) { ?>
					  <option value="<?php echo $supplier['supplier_id']; ?>" selected="selected"><?php echo $supplier['name']; ?></option>
					  <?php } else { ?>
					  <option value="<?php echo $supplier['supplier_id']; ?>"><?php echo $supplier['name']; ?></option>
					  <?php } ?>
					  <?php } ?>
					</select>					
					<?php if ($error_supplier) { ?>
						  <div class="text-danger"><?php echo $error_supplier; ?></div>
						  <?php } ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" ><?php echo $entry_currency; ?></label>
					<div class="col-sm-10">
						<input type="hidden" name="currency_id" id="currency_id" value="0">
						<p class="form-control-static" id="currencyname"> <?php echo $currency; ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" ><?php echo $entry_exrate; ?></label>
					<div class="col-sm-10">
						 <p class="form-control-static" id="currencyrate"><?php echo $currency_value; ?></p>
					
					</div>
				</div>
				<div class="form-group">
				<label class="col-sm-2 control-label" for="order_status_id"><?php echo $entry_orderstatus; ?></label>
					<div class="col-sm-10">
					<select name="order_status_id" id="order_status_id" class="form-control">
						<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $order_status_id) { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="tracking_id_s2s"><?php echo $entry_tracking_id_s2s; ?></label>
					<div class="col-sm-10">
						  <input type="text" name="tracking_id_s2s" value="<?php echo $tracking_id_s2s; ?>" placeholder="<?php echo $tracking_id_s2s; ?>" id="tracking_id_s2s" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="tracking_id_s2c"><?php echo $entry_tracking_id_s2c; ?></label>
					<div class="col-sm-10">
						  <input type="text" name="tracking_id_s2c" value="<?php echo $tracking_id_s2c; ?>" placeholder="<?php echo $tracking_id_s2c; ?>" id="tracking_id_s2c" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="delivery_time_s2s"><?php echo $entry_delivery_time_s2s; ?></label>
						<div class="col-sm-10">
						  <input type="text" name="delivery_time_s2s" value="<?php echo $delivery_time_s2s; ?>" placeholder="<?php echo $delivery_time_s2s; ?>" id="delivery_time_s2s" class="form-control" />
					  </div>
				</div>
				<div class="form-group">
				<label class="col-sm-2 control-label" for="delivery_time_s2c"><?php echo $entry_delivery_time_s2c; ?></label>
					<div class="col-sm-10">
					  <input type="text" name="delivery_time_s2c" value="<?php echo $delivery_time_s2c; ?>" placeholder="<?php echo $delivery_time_s2c; ?>" id="delivery_time_s2c" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="delivery_charge"><?php echo $text_delivery_charge; ?></label>
					<div class="col-sm-10">
					<input type="text" name="delivery_charge" value="<?php echo $delivery_charge; ?>" placeholder="<?php echo $delivery_charge; ?>" id="delivery_charge" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="order_status_id"><?php echo $entry_sendto; ?></label>
						<div class="col-sm-10">
						<select name="order_sendto_id"  id="order_sendto_id" <?php  if ($issupplier) { echo 'disabled'; } ?> <?php  if (empty($sales_order_id)) { echo 'disabled'; } ?> class="form-control">
							<?php if ($send_to == 0) { ?>
							<option value="0" selected="selected"><?php echo $text_customer; ?></option>
							<?php } else { ?>
							<option value="0"><?php echo $text_customer; ?></option>
							<?php } ?>   
							<?php if ($send_to == 1 || empty($sales_order_id)) { ?>
							<option value="1" selected="selected"><?php echo $text_company; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_company; ?></option>
							<?php } ?>   					
					</select>
						</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="comments"><?php echo $entry_comments; ?></label>
						<div class="col-sm-10">
						  <textarea name="comments"  id="comments" rows="7"  class="form-control" placeholder="<?php echo $comments; ?>" <?php  if ($issupplier) { echo 'readonly'; } ?> ><?php echo $comments; ?></textarea>
						 </div>
				</div>
				<div class="text-right">
					<button type="button" id="button-general" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-arrow-right"></i> <?php echo $button_continue; ?></button>
				</div>
			</div>
            
			<div class="tab-pane" id="tab-cart">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $column_product; ?></td>
                      <td class="text-left"><?php echo $column_model; ?></td>
                      <td class="text-right"><?php echo $column_quantity; ?></td>
                      <td class="text-right"><?php echo $column_price; ?></td>
                      <td class="text-right"><?php echo $column_total; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody id="cart">
                    <?php if ($order_products) { ?>
                    <?php $product_row = 0; ?>
                    <?php foreach ($order_products as $order_product) { ?>
                    <tr>
                      <td class="text-left"><?php echo $order_product['name']; ?><br />
                        <input type="hidden" name="product[<?php echo $product_row; ?>][product_id]" value="<?php echo $order_product['product_id']; ?>" />
						   <input type="hidden" name="product[<?php echo $product_row; ?>][sale_order_product_id]" value="<?php echo $order_product['sale_order_product_id']; ?>" />
                        <?php foreach ($order_product['option'] as $option) { ?>
                        - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                        <?php if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'image') { ?>
                        <input type="hidden" name="product[<?php echo $product_row; ?>][option][<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['product_option_value_id']; ?>" />
                        <?php } ?>
                        <?php if ($option['type'] == 'checkbox') { ?>
                        <input type="hidden" name="product[<?php echo $product_row; ?>][option][<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option['product_option_value_id']; ?>" />
                        <?php } ?>
                        <?php if ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') { ?>
                        <input type="hidden" name="product[<?php echo $product_row; ?>][option][<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" />
                        <?php } ?>
                        <?php } ?></td>
                      <td class="text-left"><?php echo $order_product['model']; ?></td>
                      <td class="text-right"><?php echo $order_product['quantity']; ?>
                        <input type="hidden" name="product[<?php echo $product_row; ?>][quantity]" value="<?php echo $order_product['quantity']; ?>" /></td>
                      <td class="text-right"><?php echo $order_product['price']; ?>
                        <input type="hidden" name="product[<?php echo $product_row; ?>][price]" value="<?php echo $order_product['price']; ?>" /></td>
                      <td class="text-right"></td>
                      <td class="text-center"></td>
                    </tr>
                    <?php $product_row++; ?>
                    <?php } ?>
                    <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                    </tr>
                  </tbody>
                  <?php } ?>
                </table>
              </div>
              <ul class="nav nav-tabs nav-justified">
                <li class="active"><a href="#tab-product" data-toggle="tab"><?php echo $tab_product; ?></a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="tab-product">
                  <fieldset>
                    <legend><?php echo $text_product; ?></legend>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-product"><?php echo $entry_product; ?></label>
                      <div class="col-sm-10">
                        <input type="text" name="product" value="" id="input-product" class="form-control" />
                        <input type="hidden" name="product_id" value="" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                      <div class="col-sm-10">
                        <input type="text" name="quantity" value="1" id="input-quantity" class="form-control" />
                      </div>
                    </div>
					  <div class="form-group">
                      <label class="col-sm-2 control-label" for="cost"><span data-toggle="tooltip" title="<?php echo $help_unitcost; ?>"><?php echo $entry_cost; ?></span></label>
                      <div class="col-sm-10">
                        <input type="text" name="cost" value="" id="input-cost" class="form-control" />
                      </div>
                    </div>
                    <div id="option"></div>
                  </fieldset>
                  <div class="text-right">
                    <button type="button" id="button-product-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_product_add; ?></button>
                  </div>
                </div>                
              </div>
              <br />
              <div class="row">
                <div class="col-sm-6 text-left">
                  <button type="button" onclick="$('a[href=\'#tab-general\']').tab('show');" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?php echo $button_back; ?></button>
                </div>
                <div class="col-sm-6 text-right">
                  <button type="button" id="button-cart" class="btn btn-primary"><i class="fa fa-arrow-right"></i> <?php echo $button_continue; ?></button>
                </div>
              </div>
            </div>
            
            <div class="tab-pane" id="tab-total">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $column_product; ?></td>
                      <td class="text-left"><?php echo $column_model; ?></td>
                      <td class="text-right"><?php echo $column_quantity; ?></td>
                      <td class="text-right"><?php echo $column_price; ?></td>
                      <td class="text-right"><?php echo $column_total; ?></td>
                    </tr>
                  </thead>
                  <tbody id="total">
                    <tr>
                      <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <div class="row">
                <div class="col-sm-6 text-left">
                  <button type="button" onclick="$('a[href=\'#tab-cart\']').tab('show');" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?php echo $button_back; ?></button>
                </div>
                <div class="col-sm-6 text-right">
                  <button type="button" id="button-refresh" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-warning"><i class="fa fa-refresh"></i></button>
                  <button type="button" id="button-save" class="btn btn-primary"><i class="fa fa-check-circle"></i> <?php echo $button_save; ?></button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  
<script>
function updatecurrency(supplier_id)
{
	$.get('index.php?route=pomgmt/supplier/suppliercurrency&token=<?php echo $token; ?>&supplier_id=' + supplier_id  , 
	function (data) {
					$('#currencyname').html(data.split('|')[0]);
					$('#currencyrate').html(data.split('|')[1]);
					$('#currency_id').val(data.split('|')[3]);
                });
	$.get('index.php?route=pomgmt/supplier/getsuppliercomments&token=<?php echo $token; ?>&supplier_id=' + supplier_id  , 
	function (data) {
					$('#comments').val(data);
                });				
	
	$.get('index.php?route=pomgmt/supplier/getsupplierdeliverycharges&token=<?php echo $token; ?>&supplier_id=' + supplier_id  , 
	function (data) {
		$('#delivery_charge').val(data);
		updateDeliveryCharge(data);
	});					
}

</script>

  <script type="text/javascript"><!--
// Disable the tabs
$('#order a[data-toggle=\'tab\']').on('click', function(e) {
	return false;
});
			
// Add all products to the cart using the api
$('#button-refresh').on('click', function() {
	$.ajax({
		url: 'index.php?route=pomgmt/po/api&token=<?php echo $token; ?>&api=api/po/products&delcharge=' + $('#delivery_charge').val() +'&supplier_id=' + $('#input-supplier').val() + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		dataType: 'json',
		success: function(json) {
			$('.alert-danger, .text-danger').remove();
			
			// Check for errors
			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
									
				if (json['error']['stock']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['stock'] + '</div>');
				}
								
				if (json['error']['minimum']) {
					for (i in json['error']['minimum']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['minimum'][i] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			}				
			
			
			html = '';
			
			if (json['products']) {
				for (i = 0; i < json['products'].length; i++) {
					product = json['products'][i];
					
					html += '<tr>';
					html += '  <td class="text-left">' + product['name'] + ' ' + (!product['stock'] ? '<span class="text-danger">***</span>' : '') + '<br />';
					html += '  <input type="hidden" name="product[' + i + '][product_id]" value="' + product['product_id'] + '" />';
					html += '  <input type="hidden" name="product[' + i + '][sale_order_product_id]" value="' + product['sale_order_product_id'] + '" />';
				
					if (product['option']) {
						for (j = 0; j < product['option'].length; j++) {
							option = product['option'][j];
							
							html += '  - <small>' + option['name'] + ': ' + option['value'] + '</small><br />';
							
							if (option['type'] == 'select' || option['type'] == 'radio' || option['type'] == 'image') {
								html += '<input type="hidden" name="product[' + i + '][option][' + option['product_option_id'] + ']" value="' + option['product_option_value_id'] + '" />';
							}
							
							if (option['type'] == 'checkbox') {
								html += '<input type="hidden" name="product[' + i + '][option][' + option['product_option_id'] + '][]" value="' + option['product_option_value_id'] + '" />';
							}
							
							if (option['type'] == 'text' || option['type'] == 'textarea' || option['type'] == 'file' || option['type'] == 'date' || option['type'] == 'datetime' || option['type'] == 'time') {
								html += '<input type="hidden" name="product[' + i + '][option][' + option['product_option_id'] + ']" value="' + option['value'] + '" />';
							}
						}
					}
					
					html += '</td>';
					html += '  <td class="text-left">' + product['model'] + '</td>';
					html += '  <td class="text-right">' + product['quantity'] + '<input type="hidden" name="product[' + i + '][quantity]" value="' + product['quantity'] + '" /></td>';
					html += '  <td class="text-right">' + product['price'] + '</td>';
					html += '  <td class="text-right">' + product['total'] + '</td>';
					html += '  <td class="text-center" style="width: 3px;"><button type="button" value="' + product['key'] + '" data-toggle="tooltip" title="<?php echo $button_remove; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
					html += '</tr>';
					
				}
			} 
								
			
			if (json['products'] == '') {				
				html += '<tr>';
				html += '  <td colspan="6" class="text-center"><?php echo $text_no_results; ?></td>';
				html += '</tr>';	
			}

			$('#cart').html(html);

			// Totals
			html = '';
			
			if (json['products']) {
				for (i = 0; i < json['products'].length; i++) {
					product = json['products'][i];
					
					html += '<tr>';
					html += '  <td class="text-left">' + product['name'] + ' ' + (!product['stock'] ? '<span class="text-danger">***</span>' : '') + '<br />';
					
					if (product['option']) {
						for (j = 0; j < product['option'].length; j++) {
							option = product['option'][j];
							
							html += '  - <small>' + option['name'] + ': ' + option['value'] + '</small><br />';
						}
					}
					
					html += '  </td>';
					html += '  <td class="text-left">' + product['model'] + '</td>';
					html += '  <td class="text-right">' + product['quantity'] + '</td>';
					html += '  <td class="text-right">' + product['price'] + '</td>';
					html += '  <td class="text-right">' + product['total'] + '</td>';
					html += '</tr>';
				}				
			}
			
			
			if (json['totals']) {
				for (i in json['totals']) {
					total = json['totals'][i];
					
					html += '<tr>';
					html += '  <td class="text-right" colspan="4">' + total['title'] + ':</td>';
					html += '  <td class="text-right">';
					if (total['title']=='VAT')
						html += '<input type="hidden" id="VAT" name="VAT" value="' + total['value'] + '">' ;
					if (total['title']=='Sub-Total')
						html += '<input type="hidden" id="SubTotal" name="SubTotal" value="' + total['value'] + '">' ;
					html += total['text'] + '</td>';
					html += '</tr>';
				}
			}
			
			if (!json['totals'] && !json['products'] ) {				
				html += '<tr>';
				html += '  <td colspan="5" class="text-center"><?php echo $text_no_results; ?></td>';
				html += '</tr>';	
			}
						
			$('#total').html(html);
		},	
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
		
$('#button-general').on('click', function() {
	$.ajax({
		url: 'index.php?route=pomgmt/po/api&token=<?php echo $token; ?>&api=api/po&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: $('#tab-general input[type=\'text\'], #tab-general input[type=\'hidden\'], #tab-general input[type=\'radio\']:checked, #tab-general input[type=\'checkbox\']:checked, #tab-general select, #tab-general textarea'),
		dataType: 'json',
		beforeSend: function() {			
			$('#button-general').button('loading');
		},
		complete: function() {
			 $('#button-general').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');			
			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}				
				
				for (i in json['error']) {
					var element = $('#input-' + i.replace('_', '-'));
					
					if (element.parent().hasClass('input-group')) {
                   		$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}				
				
				// Highlight any found errors
				$('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');
			} else {
				$.ajax({
					url: 'index.php?route=pomgmt/po/api&token=<?php echo $token; ?>&api=api/cart/add&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
					type: 'post',
					data: $('#cart input[name^=\'product\'][type=\'text\'], #cart input[name^=\'product\'][type=\'hidden\'], #cart input[name^=\'product\'][type=\'radio\']:checked, #cart input[name^=\'product\'][type=\'checkbox\']:checked, #cart select[name^=\'product\'], #cart textarea[name^=\'product\']'),
					dataType: 'json',
					beforeSend: function() {
						$('#button-product-add').button('loading');
					},
					complete: function() {
						$('#button-product-add').button('reset');
					},
					success: function(json) {
						$('.alert, .text-danger').remove();
						$('.form-group').removeClass('has-error');
					
						if (json['error'] && json['error']['warning']) {
							$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});		

				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
								
				$('a[href=\'#tab-cart\']').tab('show');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert("ERROR : " + thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});
				
$('#tab-product input[name=\'product\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id'],
						model: item['model'],
						option: item['option'],
						price: item['price']						
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('#tab-product input[name=\'product\']').val(item['label']);
		$('#tab-product input[name=\'product_id\']').val(item['value']);
		
		if (item['option'] != '') {
 			html  = '<fieldset>';
            html += '  <legend><?php echo $entry_option; ?></legend>';
			  
			for (i = 0; i < item['option'].length; i++) {
				option = item['option'][i];
				
				if (option['type'] == 'select') {
					html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="col-sm-10">';
					html += '    <select name="option[' + option['product_option_id'] + ']" id="input-option' + option['product_option_id'] + '" class="form-control">';
					html += '      <option value=""><?php echo $text_select; ?></option>';
				
					for (j = 0; j < option['product_option_value'].length; j++) {
						option_value = option['product_option_value'][j];
						
						html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
						
						if (option_value['price']) {
							html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
						}
						
						html += '</option>';
					}
						
					html += '    </select>';
					html += '  </div>';
					html += '</div>';
				}
				
				if (option['type'] == 'radio') {
					html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="col-sm-10">';
					html += '    <select name="option[' + option['product_option_id'] + ']" id="input-option' + option['product_option_id'] + '" class="form-control">';
					html += '      <option value=""><?php echo $text_select; ?></option>';
				
					for (j = 0; j < option['product_option_value'].length; j++) {
						option_value = option['product_option_value'][j];
						
						html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
						
						if (option_value['price']) {
							html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
						}
						
						html += '</option>';
					}
						
					html += '    </select>';
					html += '  </div>';
					html += '</div>';
				}
					
				if (option['type'] == 'checkbox') {
					html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="col-sm-2 control-label">' + option['name'] + '</label>';
					html += '  <div class="col-sm-10">';
					html += '    <div id="input-option' + option['product_option_id'] + '">';
					
					for (j = 0; j < option['product_option_value'].length; j++) {
						option_value = option['product_option_value'][j];
						
						html += '<div class="checkbox">';
						
						html += '  <label><input type="checkbox" name="option[' + option['product_option_id'] + '][]" value="' + option_value['product_option_value_id'] + '" /> ' + option_value['name'];
						
						if (option_value['price']) {
							html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
						}
						
						html += '  </label>';
						html += '</div>';
					}
										
					html += '    </div>';
					html += '  </div>';
					html += '</div>';
				}
			
				if (option['type'] == 'image') {
					html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="col-sm-10">';
					html += '    <select name="option[' + option['product_option_id'] + ']" id="input-option' + option['product_option_id'] + '" class="form-control">';
					html += '      <option value=""><?php echo $text_select; ?></option>';
				
					for (j = 0; j < option['product_option_value'].length; j++) {
						option_value = option['product_option_value'][j];
						
						html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
						
						if (option_value['price']) {
							html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
						}
						
						html += '</option>';
					}
						
					html += '    </select>';					
					html += '  </div>';
					html += '</div>';
				}
						
				if (option['type'] == 'text') {
					html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="col-sm-10"><input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" id="input-option' + option['product_option_id'] + '" class="form-control" /></div>';
					html += '</div>';					
				}
				
				if (option['type'] == 'textarea') {
					html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="col-sm-10"><textarea name="option[' + option['product_option_id'] + ']" rows="5" id="input-option' + option['product_option_id'] + '" class="form-control">' + option['value'] + '</textarea></div>';
					html += '</div>';
				}
				
				if (option['type'] == 'file') {
					html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="col-sm-2 control-label">' + option['name'] + '</label>';
					html += '  <div class="col-sm-10">';
					html += '    <button type="button" id="button-upload' + option['product_option_id'] + '" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>';
					html += '    <input type="hidden" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" id="input-option' + option['product_option_id'] + '" />';
					html += '  </div>';
					html += '</div>';
				}
				
				if (option['type'] == 'date') {
					html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="col-sm-3"><div class="input-group date"><input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" placeholder="' + option['name'] + '" data-date-format="YYYY-MM-DD" id="input-option' + option['product_option_id'] + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
					html += '</div>';
				}
				
				if (option['type'] == 'datetime') {
					html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="col-sm-3"><div class="input-group datetime"><input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" placeholder="' + option['name'] + '" data-date-format="YYYY-MM-DD HH:mm" id="input-option' + option['product_option_id'] + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
					html += '</div>';					
				}
				
				if (option['type'] == 'time') {
					html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="col-sm-3"><div class="input-group time"><input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" placeholder="' + option['name'] + '" data-date-format="HH:mm" id="input-option' + option['product_option_id'] + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
					html += '</div>';					
				}
			}
			
			html += '</fieldset>';
			
			$('#option').html(html);
			
			$('.date').datetimepicker({
				pickTime: false
			});
			
			$('.datetime').datetimepicker({
				pickDate: true,
				pickTime: true
			});
			
			$('.time').datetimepicker({
				pickDate: false
			});
		} else {
			$('#option').html('');
		}		
	}	
});

$('#button-product-add').on('click', function() {
	$.ajax({
		url: 'index.php?route=pomgmt/po/api&token=<?php echo $token; ?>&api=api/cart/add&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: $('#tab-product input[name=\'product_id\'], #tab-product input[name=\'quantity\'], #tab-product input[name=\'cost\'], #tab-product input[name^=\'option\'][type=\'text\'], #tab-product input[name^=\'option\'][type=\'hidden\'], #tab-product input[name^=\'option\'][type=\'radio\']:checked, #tab-product input[name^=\'option\'][type=\'checkbox\']:checked, #tab-product select[name^=\'option\'], #tab-product textarea[name^=\'option\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-product-add').button('loading');
		},
		complete: function() {
			$('#button-product-add').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');
		
			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
				
				if (json['error']['option']) {	
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));
						
						if (element.parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}
				
				if (json['error']['store']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['store'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');				
			} else {
				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});				
});

$('#tab-cart').delegate('.btn-danger', 'click', function() {
	var node = this;
	
	$.ajax({
		url: 'index.php?route=pomgmt/po/api&token=<?php echo $token; ?>&api=api/cart/remove&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: 'key=' + encodeURIComponent(this.value),
		dataType: 'json',
		beforeSend: function() {
			$(node).button('loading');
		},
		complete: function() {
			$(node).button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
		
			// Check for errors
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			} else {
				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});				
});

$('#button-cart').on('click', function() {
	$('a[href=\'#tab-total\']').tab('show');
});
				

// Checkout
$('#button-save').on('click', function() {
	var order_id = $('input[name=\'order_id\']').val();
	
	if (order_id == 0) {
		var url = 'index.php?route=pomgmt/po/api&token=<?php echo $token; ?>&api=api/po/add&store_id=' + $('select[name=\'store_id\'] option:selected').val()+ '&vat=' + $('#VAT').val() + '&subtotal=' + $('#SubTotal').val();
	} else {
		var url = 'index.php?route=pomgmt/po/api&token=<?php echo $token; ?>&api=api/po/edit&store_id=' + $('select[name=\'store_id\'] option:selected').val() + '&order_id=' + order_id + '&vat=' + $('#VAT').val() + '&subtotal=' + $('#SubTotal').val();
	}
	
	$.ajax({
		url: url,
		type: 'post',
		data: $('#tab-general input, #tab-general select, #tab-general textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-save').button('loading');	
		},	
		complete: function() {
			$('#button-save').button('reset');
		},		
		success: function(json) {
			$('.alert, .text-danger').remove();
			
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '  <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
			
			if (json['order_id']) {
				$('input[name=\'order_id\']').val(json['order_id']);
			}			
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});		
});


$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});	
</script> 
  <script type="text/javascript">
// Sort the custom fields
$('#tab-general .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-general .form-group').length) {
		$('#tab-general .form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#tab-general .form-group').length) {
		$('#tab-general .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#tab-general .form-group').length) {
		$('#tab-general .form-group:first').before(this);
	}
});

</script></div>
<?php echo $footer; ?>