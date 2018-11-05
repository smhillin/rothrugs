<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
	 <div class="pull-right">
	    <a onclick="$('#form').submit();" data-toggle="tooltip" title="<?php echo $button_create; ?>" class="btn btn-primary"><i class="fa fa-circle-o-notch"></i></a>
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
			<h3 class="panel-title"><i class="fa fa-list"></i><?php echo $text_pobatch; ?></h3>
		  </div>
	  <div class="panel-body">
	  <form action="index.php?route=pomgmt/po_batch_rules/createpo&token=<?php echo $_GET['token'];?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
	         <div class="tab-content">
		
						 <div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_supplier; ?></label>
								<div class="col-sm-10">
									<div class="well well-sm" style="height: 150px; overflow: auto;">
									<?php foreach($suppliers as $supplier){ ?>
									<div class="checkbox" >
									  <label>
										<input type="checkbox" name="supplier[]" id="supplier[]" value="<?php echo $supplier['supplier_id']; ?>"  /><?php echo $supplier['name']; ?>
											
									  </label>
									</div>
									<?php } ?>
								
									</div>
								</div>
						</div>
										
					 <div class="form-group">
						<label class="col-sm-2 control-label" for="store"><?php echo $entry_store; ?></label>
						<div class="col-sm-10">
					    <select name="store" id="store" class="form-control">
						  <?php foreach($stores as $store){ ?>	              					
							<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
	              	     <?php } ?>
						</select> 
					  </div>
				</div>
						
									
		</div>
		</form>
		</d
		</div>
	</div>
  </div>
 </div>
  <?php echo $footer; ?>