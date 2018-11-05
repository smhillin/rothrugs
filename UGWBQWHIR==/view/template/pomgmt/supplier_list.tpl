<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
						<label class="control-label" for="filter_id"><?php echo $entry_id; ?></label>
					   <input type="text"  id="filter_id" name="filter_id" value="<?php echo $filter_id; ?>" placeholder="<?php echo $entry_id; ?>" class="form-control" />
					  </div>
				 
					 <div class="form-group">
						<label class="control-label" for="filter_supplier"><?php echo $entry_name; ?></label>
					   <input type="text"  id="filter_supplier" name="filter_supplier" value="<?php echo $filter_supplier; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
					  </div>
				  </div>
				<div class="col-sm-4">
					 <div class="form-group">
						<label class="control-label" for="filter_currency_id"><?php echo $entry_currency; ?></label>
					  <select name="filter_currency_id" id="filter_currency_id" class="form-control">
						  <option value="*"></option>                  
						  <?php foreach ($currencies as $currency) { ?>
						  <?php if ($currency['currency_id'] == $filter_currency_id) { ?>
						  <option value="<?php echo $currency['currency_id']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
						  <?php } else { ?>
						  <option value="<?php echo $currency['currency_id']; ?>"><?php echo $currency['title']; ?></option>
						  <?php } ?>
						  <?php } ?>
						</select> 
					  </div>
				</div>
				<div class="col-sm-4">
					 <div class="form-group">
						<label class="control-label" for="filter_email"><?php echo $entry_email; ?></label>
					   <input type="text"  id="filter_email" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo $entry_email; ?>" class="form-control" />
					  </div>
					  
					  <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>   
				  </div>
	
		  </div>
	  </div>
		</div>
   <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
	   <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="text-left"><?php if ($sort == 'id') { ?>1111
                <a href="<?php echo $sort_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_id; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_id; ?>"><?php echo $column_id; ?></a>
                <?php } ?></td>
			  <td class="text-left"><?php if ($sort == 'name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
			<td class="text-left"><?php if ($sort == 'currency') { ?>
                <a href="<?php echo $sort_currency; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_currency; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_currency; ?>"><?php echo $column_currency; ?></a>
                <?php } ?></td>				
              <td class="text-left"><?php if ($sort == 'email') { ?>
                <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                <?php } ?></td>
              <td class="text-right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
				
			
            <?php if ($suppliers) { ?>
            <?php foreach ($suppliers as $supplier) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($supplier['selected']) { ?>
                <input type="checkbox" name="selected[]"id="selected[]"  value="<?php echo $supplier['supplier_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" id="selected[]" value="<?php echo $supplier['supplier_id']; ?>" />
                <?php } ?></td>
			  <td class="text-left"><?php echo $supplier['supplier_id']; ?></td>
              <td class="text-left"><?php echo $supplier['name']; ?></td>
			  <td class="text-left"><?php echo $supplier['currency']; ?></td>
              <td class="text-left"><?php echo $supplier['email']; ?></td>
              <td class="right">
			  <div class="pull-right">
				<a href="<?php echo $supplier['edit']; ?>" data-toggle="tooltip"  title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
			    <a href="<?php echo $supplier['viewpo']; ?>"  title="<?php echo $text_viewpo; ?>" class="btn btn-primary"><i class="fa fa-minus-square"></i></a>
			   </div>
			 </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
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
</div>
<script type="text/javascript"><!--
$("#button-filter").click(function(){
        
    
	url = 'index.php?route=pomgmt/supplier&token=<?php echo $token; ?>';
	
	var filter_id = $('#filter_id').val(); 
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
	
	var filter_supplier=$('#filter_supplier').val(); 
	if (filter_supplier) {
		url += '&filter_supplier=' + encodeURIComponent(filter_supplier);
	}
	var filter_currency_id=$('#filter_currency_id').val(); 
	if (filter_currency_id != '*') {
		url += '&filter_currency_id=' + encodeURIComponent(filter_currency_id);
	}	
	var filter_email =$('#filter_email').val(); 
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}	
	location = url;
});
//--></script>  
<?php echo $footer; ?>