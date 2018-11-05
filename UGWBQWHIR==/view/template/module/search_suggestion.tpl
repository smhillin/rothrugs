<?php echo $header; ?>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="view/javascript/jquery/ui/jquery-ui.theme.min.css" />
<style>
.disable {
  background-color: #EFEFEF !important;
}
.info {
  color: #FF0000;
  font-weight: bold;
}
</style>

<?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product" data-toggle="tooltip_" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip_" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-search-suggestion" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-where" data-toggle="tab"><?php echo $tab_where; ?></a></li>
            <li><a href="#tab-fields" data-toggle="tab"><?php echo $tab_fields; ?></a></li>
            <li><a href="#tab-support" data-toggle="tab"><?php echo $tab_support; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
								<div class="col-sm-10">
									<select name="search_suggestion_status" id="input-status" class="form-control">
										<?php if ($search_suggestion_status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>          
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-search-order"><span data-toggle="tooltip_" title="<?php echo $help_search_order; ?>"><?php echo $search_order; ?></span></label>
								<div class="col-sm-10">
									<div class="row">
                    <div class="col-sm-6">
											<select name="search_suggestion_options[search_order]" id="input-search-order" class="form-control">
												<option value="name" <?php echo (isset($options['search_order']) && $options['search_order'] == 'name') ? 'selected="selected"' : "" ;?>><?php echo $search_order_name; ?></option>
												<option value="rating" <?php echo (isset($options['search_order']) && $options['search_order'] == 'rating') ? 'selected="selected"' : "" ;?>><?php echo $search_order_rating; ?></option>
												<option value="relevance" <?php echo (isset($options['search_order']) && $options['search_order'] == 'relevance') ? 'selected="selected"' : "" ;?>><?php echo $search_order_relevance; ?></option>
											</select>
                    </div>
                    <div class="col-sm-6">
											<select name="search_suggestion_options[search_order_dir]" id="input-search-order-dir" class="form-control">
												<option value="asc" <?php echo (isset($options['search_order_dir']) && $options['search_order_dir'] == 'asc') ? 'selected="selected"' : "" ;?>><?php echo $search_order_dir_asc; ?></option>
												<option value="desc" <?php echo (isset($options['search_order_dir']) && $options['search_order_dir'] == 'desc') ? 'selected="selected"' : "" ;?>><?php echo $search_order_dir_desc; ?></option>
											</select>              
                    </div>
                  </div>
								</div>
							</div>          

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-search-limit"><?php echo $search_limit; ?></label>
								<div class="col-sm-10">
									<input type="text" name="search_suggestion_options[search_limit]" value="<?php echo isset($options['search_limit']) ? $options['search_limit'] : 0;?>"  id="input-search-limit" class="form-control">
								</div>
							</div>          

							<div class="form-group disable">
								<label class="col-sm-2 control-label" for="input-search-logic"><span data-toggle="tooltip_" title="<?php echo $help_search_logic; ?>"><?php echo $search_logic; ?></span></label>
								<div class="col-sm-10">
									<select class="form-control"  disabled="disabled">
										<option><?php echo $search_logic_or; ?></option>
										<option><?php echo $search_logic_and; ?></option>
									</select>              
								</div>
							</div>          
							
							<div class="form-group disable">
								<label class="col-sm-2 control-label" for="input-search-cache"><?php echo $search_cache; ?></label>
								<div class="col-sm-10">
									<input type="checkbox" class="form-control" disabled="disabled"/>
								</div>
							</div>
              
							<div class="form-group">
								<label class="col-sm-2 control-label" for="search-css"><?php echo $search_css; ?></label>
								<div class="col-sm-10">
                  <textarea name="search_suggestion_options[search_css]" rows="8" cols="60" id="search-css" class="form-control"><?php echo isset($options['search_css']) ? $options['search_css'] : '';?></textarea>                  
								</div>
							</div>                        
                                  
							<input type="hidden" name="search_suggestion_module[0][search_suggestion]" value="1"/>

							<div class="form-group">
								<div class="col-sm-12"><?php echo $text_commercial; ?></div>
							</div>                        
            </div>
						<div class="tab-pane" id="tab-where">
              
							<table id="where" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<td class="text-center">
											<input type="checkbox" onclick="$('input[type=checkbox][name*=\'search_suggestion_options[search_where]\']').prop('checked', this.checked);" />
										</td>
										<td class="text-left"><?php echo $search_where; ?></td>  
										<td class="text-left"><?php echo $relevance_weight; ?></td>
									</tr>
								</thead>
								<tbody >
									<tr>
										<td class="text-center">
											<input type="checkbox" name="search_suggestion_options[search_where][name]" value="1" <?php echo (isset($options['search_where']['name']) && $options['search_where']['name']) ? "checked=checked" : "" ;?> />
										</td>
										<td class="text-left"><?php echo $search_where_name; ?></td>  
										<td class="text-left"><?php echo $relevance_weight_mr; ?></td>
									</tr>
									<tr class="disable">
										<td class="text-center">
											<input type="checkbox" disabled="disabled" />
										</td>
										<td class="text-left"><?php echo $search_where_tags; ?></td>
										<td class="text-left"><?php echo $relevance_weight_mr; ?></td>
									</tr>
									<tr class="disable">
										<td class="text-center">
											<input type="checkbox" disabled="disabled" />
										</td>
										<td class="text-left"><?php echo $search_where_description; ?></td>  
										<td class="text-left"><?php echo $relevance_weight_mr; ?></td>
									</tr>
									<tr class="disable">
										<td class="text-center">
											<input type="checkbox" disabled="disabled" />
										</td>
										<td class="text-left"><?php echo $search_where_model; ?></td> 
										<td class="text-left"><?php echo $relevance_weight_mr; ?></td>              
									</tr>
									<tr class="disable">
										<td class="text-center">
											<input type="checkbox" disabled="disabled" />
										</td>
										<td class="text-left"><?php echo $search_where_sku; ?></td>  
										<td class="text-left"><?php echo $relevance_weight_mr; ?></td>              
									</tr>

								</tbody>
							</table>
							
							<?php echo $tab_where_help; ?>  
							
							<div class="form-group">
								<div class="col-sm-12"><?php echo $text_commercial; ?></div>
							</div>          

            </div>
						<div class="tab-pane" id="tab-fields">

              <table id="fields" class="table table-striped table-bordered table-hover sorted_table">
                <thead>
                  <tr>
                    <td class="left"><?php echo $search_fields_show; ?></td>
                    <td class="left"><?php echo $search_fields; ?></td>              
                    <td class="left"><?php echo $search_fields_show_field_name; ?></td>  
                    <td class="left"><?php echo $search_fields_settings; ?></td>  
                    <td class="left"><?php echo $search_fields_location; ?></td>  
                    <td class="left"><?php echo $search_fields_sort; ?></td>  
                    <td class="left"><?php echo $search_fields_css; ?></td>  
                  </tr>
                </thead>
                <tbody>

                  <?php foreach ($options['search_field'] as $field_name => $field_options) { ?>
                    <?php if ($field_name == 'name' || $field_name == 'price') { ?>
                    <tr>
                      <td width="1" style="text-align: center;">
                        <input type="checkbox" name="search_suggestion_options[search_field][<?php echo $field_name ?>][show]" value="1" <?php echo (isset($field_options['show']) && $field_options['show']) ? "checked=checked" : "" ;?> class="form-control"/>
                      </td>
                      <td class="left"><?php echo ${'search_field_' . $field_name}; ?></td>              
                      <td class="left">
                        <input type="checkbox" name="search_suggestion_options[search_field][<?php echo $field_name ?>][show_field_name]" value="1" <?php echo (isset($field_options['show_field_name']) && $field_options['show_field_name']) ? "checked=checked" : "" ;?> class="form-control"/>
                      </td>
                      <td class="left">
                        <?php if ($field_name == 'description') { ?>
                          <?php echo $search_fields_cut; ?>: <input type="text" name="search_suggestion_options[search_field][description][cut]" value="<?php echo isset($field_options['cut']) ? $field_options['cut'] : 0;?>" size="4" class="form-control">
                        <?php } ?>
                      </td>  
                      <td class="left">
                        <select name="search_suggestion_options[search_field][<?php echo $field_name ?>][location]" class="form-control">
                          <option value="newline" <?php echo (isset($field_options['location']) && $field_options['location'] == 'newline') ? "selected='selected'" : "" ;?>>
                            <?php echo $search_field_location_newline; ?>
                          </option>            
                          <option value="inline" <?php echo (isset($field_options['location']) && $field_options['location'] == 'inline') ? "selected='selected'" : "" ;?>>
                            <?php echo $search_field_location_inline; ?>
                          </option>
                        </select>  
                      </td>  
                      <td class="left">
                        <input type="text" name="search_suggestion_options[search_field][<?php echo $field_name ?>][sort]" value="<?php echo isset($field_options['sort']) ? (int)$field_options['sort'] : 0; ?>" size="3" class="form-control"/>
                      </td>  
                      <td class="left">
                        .<?php echo $field_name ?> {<br />
                        <textarea name="search_suggestion_options[search_field][<?php echo $field_name ?>][css]" rows="4" cols="40" class="form-control"><?php echo isset($field_options['css']) ? $field_options['css'] : '';?></textarea>}
                      </td>
                    </tr>
                    
                    <?php } else { ?>
                    
                    <tr class="disable">
                      <td width="1" style="text-align: center;">
                        <input type="checkbox" class="form-control" disabled="disabled" />
                      </td>
                      <td class="left"><?php echo ${'search_field_' . $field_name}; ?></td>              
                      <td class="left">
                        <input type="checkbox" class="form-control" disabled="disabled" />
                      </td>
                      <td class="left">
                        <?php if ($field_name == 'attributes') { ?>
                          <?php echo $search_fields_cut; ?>: <input type="text" size="4" class="form-control" disabled="disabled" >
                          <?php echo $search_fields_separator; ?>: <input type="text" size="2" class="form-control" disabled="disabled" >
                        <?php } elseif ($field_name == 'image') { ?>
                          <?php echo $search_field_image_width; ?>: <input type="text" size="3" class="form-control" disabled="disabled" >  
                          <?php echo $search_field_image_height; ?>: <input type="text" size="3" class="form-control" disabled="disabled" >            
                        <?php } ?>
                      </td>  
                      <td class="left">
                        <select class="form-control" disabled="disabled" >
                          <option>
                            <?php echo $search_field_location_newline; ?>
                          </option>            
                          <option>
                            <?php echo $search_field_location_inline; ?>
                          </option>
                        </select>  
                      </td>  
                      <td class="left">
                        <input type="text" name="search_suggestion_options[search_field][<?php echo $field_name ?>][sort]" value="<?php echo isset($field_options['sort']) ? (int)$field_options['sort'] : 0; ?>" size="3" class="form-control" />
                      </td>  
                      <td class="left">
                        .<?php echo $field_name ?> {<br />
                        <textarea rows="4" cols="40" class="form-control" disabled="disabled" ></textarea>}
                      </td>
                    </tr>
                    <?php } ?>            
                  <?php } ?>

                </tbody>
              </table>              
              
							<table id="fields_attributes" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<td class="text-left"><?php echo $search_field_attributes; ?></td>              
										<td class="text-left"><?php echo $search_fields_attributes_show; ?></td>
										<td class="text-left"><?php echo $search_fields_attributes_replace_text; ?></td>  
									</tr>
								</thead>
								<tbody>

									<?php foreach($attributes as $attribute) {?>
									<tr class="disable">
										<td class="text-left"><?php echo $attribute['name']; ?></td>              
										<td>

											<select class="form-control" disabled="disabled" >

												<option><?php echo $search_fields_attributes_hide; ?></option>
												<option><?php echo $search_fields_attributes_show; ?></option>
												<option><?php echo $search_fields_attributes_replace; ?></option>

											</select>

										</td>

										<td class="text-left">
											<input type="text" class="form-control" disabled="disabled" >
										</td>  

									</tr>
									<?php } ?>
								</tbody>
							</table>
							
							<div class="form-group">
								<div class="col-sm-12"><?php echo $text_commercial; ?></div>
							</div>          
							
            </div>
						<div class="tab-pane" id="tab-support">
              <?php echo $support_text; ?>
            </div>
					</div>
					
        </form>
      </div>
    </div>
  </div>

  
  <style type="text/css">
    .sorted_table tbody tr {
      cursor: move;
    }
  </style>
  
	<script type="text/javascript"><!--
	
		$(document).ready(function() {
    
      // Sortable rows
      $('.sorted_table tbody').sortable({
        cursor: 'move',
        update: function( event, ui ) {
          ui.item.parent().find('tr').each(function (i, row) {
            $(row).find('input[name*="[sort]"]').val(i);
          });
        }
      });
			
      // Hide attributes
			if ($('input[name=\'search_suggestion_options[search_field][attributes][show]\']').is(':not(:checked)')) {
				$('#fields_attributes').hide();
			}
		});
    
		$('input[name=\'search_suggestion_options[search_field][attributes][show]\']').on('change', function() {
			if ($(this).is(':checked')) {
				$('#fields_attributes').show();
			}
			else {
				$('#fields_attributes').hide();
			}
		});

	//--></script> 
</div>
<?php echo $footer; ?>