 <?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	  <div class="page-header">
			<div class="container-fluid">
				  <div class="pull-right">
					<button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
	<div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
	         <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
				
							<div class="form-group">
								<label class="col-sm-2 control-label" ><?php echo $entry_id; ?></label>
									<div class="col-sm-10">
										 <p class="form-control-static"><?php echo $supplier_id; ?>
										
									</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-2 control-label" for="name"><?php echo $entry_name; ?></label>
									<div class="col-sm-10">
									  <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $name; ?>" id="name" class="form-control" />
									  <?php if ($error_name) { ?>
										  <div class="text-danger"><?php echo $error_name; ?></div>
										  <?php } ?>
										</div>
								  </div>
							  </div>
							  <div class="form-group">
							  <label class="col-sm-2 control-label" for="supplierurl"><?php echo $entry_supplierurl; ?></label>
									<div class="col-sm-10">
									  <input type="text" name="supplierurl" value="<?php echo $supplierurl; ?>" placeholder="<?php echo $supplierurl; ?>" id="supplierurl" class="form-control" />
									 </div>
							  </div>
						  
						  
							  <div class="form-group">
							  <label class="col-sm-2 control-label" for="orderurl"><?php echo $entry_orderurl; ?></label>
									<div class="col-sm-10">
									  <input type="text" name="orderurl" value="<?php echo $orderurl; ?>" placeholder="<?php echo $orderurl; ?>" id="orderurl" class="form-control" />
									  
								  </div>
							  </div>
							  <div class="form-group">
							  <label class="col-sm-2 control-label" for="email"><?php echo $entry_email; ?></label>
									<div class="col-sm-10">
									  <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $email; ?>" id="email" class="form-control" />
									  
								  </div>
							  </div>
							  <div class="form-group">
							   <label class="col-sm-2 control-label left" for="im"><?php echo $entry_im; ?></label>
									<div class="col-sm-10">
									  <input type="text" name="im" value="<?php echo $im; ?>" placeholder="<?php echo $im; ?>" id="im" class="form-control" />
									 </div>
							  </div>
							   <div class="form-group">
							   <label class="col-sm-2 control-label" for="gp_percent"><span data-toggle="tooltip" title="<?php echo $help_grossprofit; ?>"><?php echo $entry_gp_percent; ?></span></label>
									<div class="col-sm-10">
									  <input type="text" name="gp_percent" value="<?php echo $gp_percent; ?>" placeholder="<?php echo $gp_percent; ?>" id="gp_percent" class="form-control" />
									 </div>
							  </div>
							  <div class="form-group">
							  <label class="col-sm-2 control-label" for="exportpath"><span data-toggle="tooltip" title="<?php echo $help_exportpath; ?>"><?php echo $entry_exportpath; ?></span></label>
									<div class="col-sm-10">
									  <input type="text" name="exportpath" value="<?php echo $exportpath; ?>" placeholder="<?php echo $exportpath; ?>" id="exportpath" class="form-control" />
									  
								  </div>
							  </div>
							  <div class="form-group">
							  <label class="col-sm-2 control-label" for="fileattach"><span data-toggle="tooltip" title="<?php echo $help_email; ?>"><?php echo $entry_fileattach; ?></span></label>
									<div class="col-sm-10">
									  <input type="text" name="fileattach" value="<?php echo $fileattach; ?>" placeholder="<?php echo $fileattach; ?>" id="fileattach" class="form-control" />
									  
								  </div>
							  </div>
						  
						 <div class="form-group">
							  <label class="col-sm-2 control-label" for="tax"><?php echo $entry_tax; ?></label>
									<div class="col-sm-10">
									 <select name="tax" id="tax" class="form-control">
										<?php if ($tax) { ?>
										<option value="1" ><?php echo $text_yes; ?></option>
										<?php echo $text_yes; ?>
										 <option value="0" selected="selected" ><?php echo $text_yes; ?></option>
										<?php } else { ?>
										<option value="1" ><?php echo $text_yes; ?></option>
										<option value="0"  selected="selected"><?php echo $text_no; ?></option>
										<?php } ?>
									  </select>
									  
								  </div>
							  </div>
						  
						  
						   <div class="form-group">
								<label class="col-sm-2 control-label" for="tax_rate_id"><?php echo $entry_taxrate; ?></label>
							<div class="col-sm-10">
							<select id="tax_rate_id" name="tax_rate_id" <?php  if (!$tax) { echo 'disabled="disabled"'; } ?>  class="form-control">
							  <option value="0"><?php echo $text_select; ?></option>
							  <?php foreach ($tax_rates as $tax_rate) { ?>
							  <?php if ($tax_rate['tax_rate_id'] == $tax_rate_id) { ?>
							  <option value="<?php echo $tax_rate['tax_rate_id']; ?>" selected="selected"> <?php echo $tax_rate['name']; ?> </option>
							  <?php } else { ?>
							  <option value="<?php echo $tax_rate['tax_rate_id']; ?>"><?php echo $tax_rate['name']; ?></option>
							  <?php } ?>
							  <?php } ?>
							</select>
										<?php if ($error_taxrate) { ?>
										  <div class="text-danger"><?php echo $error_taxrate; ?></div>
										  <?php } ?>
										</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-2 control-label" for="currency_id"><?php echo $entry_currency; ?></label>
								<div class="col-sm-10">
										<select name="currency_id" id="currency_id" class="form-control">
										  <option value="false"><?php echo $text_select; ?></option>
										  <?php foreach ($currencies as $currency) { ?>
										  <?php if ($currency['currency_id'] == $currency_id) { ?>
										  <option value="<?php echo $currency['currency_id']; ?>" selected="selected"> <?php echo $currency['title']; ?> </option>
										  <?php } else { ?>
										  <option value="<?php echo $currency['currency_id']; ?>"><?php echo $currency['title']; ?></option>
										  <?php } ?>
										  <?php } ?>
										</select>
										<?php if ($error_currency) { ?>
										  <div class="text-danger"><?php echo $error_currency; ?></div>
										  <?php } ?>
								</div>
						  </div>
						
							 <div class="form-group">
							   <label class="col-sm-2 control-label" for="maxshipdays"><?php echo $entry_maxshipdays; ?></label>
									<div class="col-sm-10">
									  <input type="text" name="maxshipdays" value="<?php echo $maxshipdays; ?>" placeholder="<?php echo $maxshipdays; ?>" id="maxshipdays" class="form-control" />
									 </div>
							  </div>
							   <div class="form-group">
							   <label class="col-sm-2 control-label" for="dropshipfee"><span data-toggle="tooltip" title="<?php echo $help_dropshipfee; ?>"><?php echo $entry_dropshipfee; ?></span></label>
									<div class="col-sm-10">
									  <input type="text" name="dropshipfee" value="<?php echo $dropshipfee; ?>" placeholder="<?php echo $dropshipfee; ?>" id="dropshipfee" class="form-control" />
									 </div>
							  </div>
							   <div class="form-group">
							   <label class="col-sm-2 control-label left" for="itemdel_fee"><span data-toggle="tooltip" title="<?php echo $help_itemdel_fee; ?>"><?php echo $entry_itemdel_fee; ?></span></label>
									<div class="col-sm-10">
									  <input type="text" name="itemdel_fee" value="<?php echo $itemdel_fee; ?>" placeholder="<?php echo $itemdel_fee; ?>" id="itemdel_fee" class="form-control" />
									 </div>
							  </div>
							   <div class="form-group">
							   <label class="col-sm-2 control-label" for="comments"><span data-toggle="tooltip" title="<?php echo $help_comments; ?>"><?php echo $entry_comments; ?></span></label>
									<div class="col-sm-10">
									  <textarea name="comments"  class="form-control"  placeholder="<?php echo $comments; ?>" id="comments"><?php echo $comments; ?></textarea>
									 </div>
							  </div>
							   <div class="form-group">
							   <label class="col-sm-2 control-label" for="notes"><?php echo $entry_notes; ?></label>
									<div class="col-sm-10">
									  <textarea name="notes"  class="form-control" placeholder="<?php echo $notes; ?>" id="notes"><?php echo $notes; ?></textarea>
									 </div>
							  </div>

								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
									<div class="col-sm-10">
									  <div class="well well-sm" style="height: 150px; overflow: auto;">
										<div class="checkbox">
										  <label>
											<?php if (in_array(0, $supplier_store)) { ?>
											<input type="checkbox" name="product_store[]" value="0" checked="checked" />
											<?php echo $text_default; ?>
											<?php } else { ?>
											<input type="checkbox" name="product_store[]" value="0" />
											<?php echo $text_default; ?>
											<?php } ?>
										  </label>
										</div>
										<?php foreach ($stores as $store) { ?>
										<div class="checkbox">
										  <label>
											<?php if (in_array($store['store_id'], $supplier_store)) { ?>
											<input type="checkbox" name="supplier_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
											<?php echo $store['name']; ?>
											<?php } else { ?>
											<input type="checkbox" name="supplier_store[]" value="<?php echo $store['store_id']; ?>" />
											<?php echo $store['name']; ?>
											<?php } ?>
										  </label>
										</div>
										<?php } ?>
									  </div>
									</div>
							  </div>							  
									   
							<div class="form-group">
							   <label class="col-sm-2 control-label" for="firstname"><?php echo $entry_firstname; ?></label>
									<div class="col-sm-10">
									  <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $firstname; ?>" id="firstname" class="form-control" />
									
									 <?php if ($error_firstname) { ?>
										  <div class="text-danger"><?php echo $error_firstname; ?></div>
										  <?php } ?>
									 </div>
							  </div>	
							
								<div class="form-group">
								   <label class="col-sm-2 control-label" for="lastname"><?php echo $entry_lastname; ?></label>
										<div class="col-sm-10">
										  <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $lastname; ?>" id="lastname" class="form-control" />
										 <?php if ($error_lastname) { ?>
											  <div class="text-danger"><?php echo $error_lastname; ?></div>
											  <?php } ?>
										</div>
								  </div>	
							  <div class="form-group">
							   <label class="col-sm-2 control-label" for="address1"><?php echo $entry_address1; ?></label>
									<div class="col-sm-10">
									  <input type="text" name="address1" value="<?php echo $address1; ?>" placeholder="<?php echo $address1; ?>" id="address1" class="form-control" />
									 
									 <?php if ($error_address1) { ?>
										  <div class="text-danger"><?php echo $error_address1; ?></div>
										  <?php } ?>
									</div>
							  </div>	
							  <div class="form-group">
							   <label class="col-sm-2 control-label" for="address2"><?php echo $entry_address2; ?></label>
									<div class="col-sm-10">
									  <input type="text" name="address2" value="<?php echo $address2; ?>" placeholder="<?php echo $address2; ?>" id="address2" class="form-control" />
									 </div>
							  </div>	
							  <div class="form-group">
							   <label class="col-sm-2 control-label" for="city"><?php echo $entry_city; ?></label>
									<div class="col-sm-10">
									  <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $city; ?>" id="city" class="form-control" />
									
									 <?php if ($error_city) { ?>
										  <div class="text-danger"><?php echo $error_city; ?></div>
										  <?php } ?>
									 </div>
							  </div>	
	 
						  <div class="form-group">
						   <label class="col-sm-2 control-label" for="postcode"><?php echo $entry_postcode; ?></label>
								<div class="col-sm-10">
								  <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $postcode; ?>" id="postcode" class="form-control" />
								
								 <?php if ($error_postcode) { ?>
									  <div class="text-danger"><?php echo $error_postcode; ?></div>
									  <?php } ?>
								 </div>
						  </div>	
						   <div class="form-group">
						   <label class="col-sm-2 control-label" for="country_id"><?php echo $entry_country; ?></label>
								<div class="col-sm-10">
								<select name="country_id" id="country_id" onchange="$('select[name=\'zone_id\']').load('index.php?route=pomgmt/supplier/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');" class="form-control">
									  <option value="0"><?php echo $text_select; ?></option>
									  <?php foreach ($countries as $country) { ?>
									  <?php if ($country['country_id'] == $country_id) { ?>
									  <option value="<?php echo $country['country_id']; ?>" selected="selected"> <?php echo $country['name']; ?> </option>
									  <?php } else { ?>
									  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
									  <?php } ?>
									  <?php } ?>
									</select>
								
										<?php if ($error_country) { ?>
									  <div class="text-danger"><?php echo $error_country; ?></div>
									  <?php } ?>
									</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="zone_id"><?php echo $entry_zone; ?></label>
								<div class="col-sm-10">
								<select name="zone_id" id="zone_id" class="form-control">								 
									</select>
								
										<?php if ($error_zone) { ?>
									  <div class="text-danger"><?php echo $error_zone; ?></div>
									  <?php } ?>
								</div>
							</div>
							
							<div class="form-group">
						     <label class="col-sm-2 control-label" for="telephone"><?php echo $entry_telephone; ?></label>
								<div class="col-sm-10">
								  <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $telephone; ?>" id="telephone" class="form-control" />
								 
								 <?php if ($error_telephone) { ?>
									  <div class="text-danger"><?php echo $error_telephone; ?></div>
									  <?php } ?>
								</div>
							</div>
								  <div class="form-group">
									<label class="col-sm-2 control-label" for="username"><span data-toggle="tooltip" title="<?php echo $help_username; ?>"><?php echo $entry_username; ?></span></label>
									<div class="col-sm-10">
									  <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $username; ?>" id="username" class="form-control" />
									 </div>
								</div>
						    <div class="form-group">
							   <label class="col-sm-2 control-label" for="status"><?php echo $entry_status; ?></label>
									<div class="col-sm-10">
									<select name="status" id="status" class="form-control">
									  <?php if ($status) { ?>
									  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									  <option value="0"><?php echo $text_disabled; ?></option>
									  <?php } else { ?>
									  <option value="1"><?php echo $text_enabled; ?></option>
									  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									  <?php } ?>
									</select>
									</div>
							</div>
		</div>
		</div>
		</form>	
	  
	  
	  </div>
</div>
</div>
<script>
			$(document).ready(function () {
            //alert("Hai");
            $('#ddl').change(function () {
                $.get('WebForm1.aspx?Id=' + $('#ddl').val(), function (data) {
                    document.getElementById("tb").value = $(data).find("#txt1").val();
                });
                
				});
			});
			</script>
<script type="text/javascript"><!--
function image_upload(field, preview) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
 <script type="text/javascript"><!--
$('select[name=\'zone_id\']').load('index.php?route=pomgmt/supplier/zone&token=<?php echo $token; ?>&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
//--></script>      
<script>
 $("#tax").change( function() { 
       val=$("#tax").val();
		if (val==0)
		{
		
		$('#tax_rate_id').attr('disabled','disabled');
		$('#tax_rate_id').get(0).selectedIndex = 0;
		}
		else
		{
		
		$('#tax_rate_id').removeAttr('disabled');
		}
    });
	

</script>

</div>
<?php echo $footer; ?>