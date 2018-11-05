<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" id="button-submit" form="form-order-status" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
      <div class="panel-body">
	   <form  method="post"  action="index.php?route=user/changepwd/changepass&token=<?php echo $token; ?>" enctype="multipart/form-data" id="formpwd" class="form-horizontal" name="formpwd">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="oldpassword"><?php echo $entry_old_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="oldpassword" value="" placeholder="<?php echo $entry_old_password; ?>" id="oldpassword" class="form-control" />
		    </div>
          </div>
		  <div class="form-group required">
				<label class="col-sm-2 control-label" for="newpwd"><?php echo $entry_new_password; ?></label>
					<div class="col-sm-10">
					  <input type="password" name="newpwd" value="" placeholder="<?php echo $entry_new_password; ?>" id="newpwd" class="form-control" />
				    </div>
			</div>
			 <div class="form-group">
				<label class="col-sm-2 control-label required" for="retypepwd"><?php echo $entry_retype_password; ?></label>
					<div class="col-sm-10">
					  <input type="password" name="retypepwd" value="" placeholder="<?php echo $entry_retype_password; ?>" id="retypepwd" class="form-control" />            
				     </div>
			 </div>
		</div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
 <script type="text/javascript"><!--
 	$('#button-submit').on('click', function() {
	
	 var oldpwd = $('input[name=\'oldpassword\']').val()
	var newpwd1 = $('input[name=\'newpwd\']').val()
	var newpwd2 = $('input[name=\'retypepwd\']').val()
  	if (oldpwd == "")
	{
		alert('<?php echo $error_oldpwdempty; ?>');
		return false;
	}	
    else if (newpwd1 == "")
	{
		alert('<?php echo $error_newpwdempty; ?>');
		return false;
	}	
    else if (newpwd2 == "")
	{
		alert('<?php echo $error_retypepwdempty; ?>');
		return false;
	}	
   
    else if (newpwd1 != newpwd2)
	{
		alert('<?php echo $error_donotmatch; ?>');
		return false;
	}
	else
	 $("#formpwd").submit();
	 
});




//--></script> 
	
	
	
  