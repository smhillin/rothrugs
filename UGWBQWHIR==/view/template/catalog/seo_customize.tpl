<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
   <div id="cssmenu">
    <ul>
    <?php foreach ($links as $link) { ?>
    <li><a class="top" href="<?php echo $link['href']; ?>"><?php echo $link['text']; ?></a></li>
    <?php } ?>
  </ul>
  </div>
  <?php if ($error_warning) { ?>
	<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
   <?php } ?>
   <?php if ($error_already_exists) { ?>
 	<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_already_exists; ?>
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
    <?php } ?>
	<?php if ($success) { ?>
	<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
	<?php } ?>
    <div class="page-header">
      <h1><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h1>
      <div class="container-fluid">
        <div class="pull-right">
              <a onclick="$('#myModal').modal('show');" data-toggle="tooltip" title="Help Guide" class="btn btn-primary"><i class="fa fa-life-ring"></i> How to use?</a> 
        </div>
      </div>
    </div>
  	<div class="container-fluid">
  	<div id="tabs" class="htabs"><a href="#tab_products"><?php echo $tab_products; ?></a><a href="#tab_categories"><?php echo $tab_categories; ?></a><a href="#tab_manufacturers"><?php echo $tab_manufacturers; ?></a><a href="#tab_information_pages"><?php echo $tab_information_pages; ?></a><a href="#tab_general"><?php echo $tab_general; ?></a></div>	
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form_general" class="form-horizontal">
      	<div id="tab_general">
      		<div class="form-group">

	              	<div class="container-fluid">
		      			<div class="pull-right">
		              	   <button onclick="$('#form_general').submit();" form="form_general" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		               </div>
	   			 	</div>
          	    </div>
	    	<div class="clear"></div>
	    	<div class="clear" style="margin-top: 25px;"></div>
	    	<table id="custom_url_store" class="pure-table pure-table-bordered" style="width:100%;">
				<thead>
					<tr>
						<td class="left"><?php echo $column_url; ?></td>
						<td class="left"><?php echo $column_keyword; ?></td>
						<td align="center"><?php echo 'Action'; ?></td>
					</tr>
				</thead>    
				<?php $custom_url_store_row = 0; ?>
				<?php foreach($custom_url_store_data as $url_alias_id => $value) { ?>	
		    		<tbody id="custom_url_store_row<?php echo $custom_url_store_row; ?>">
			    		<tr>
			    			<?php foreach($value as $row => $custom_url_store_top){ ?>
			    				<?php if(isset($row) && $row == 'keyword_query') { ?>
					    			<td><?php echo $domain; ?>index.php?route=<input class="blueprint" type="text" size="20" name="custom_url_store[<?php echo $custom_url_store_row; ?>][id][query]" value="<?php echo substr($custom_url_store_top['query'],6); ?>" /></td>
					    			<td><?php echo $domain; ?><input type="text" size="20" name="custom_url_store[<?php echo $custom_url_store_row; ?>][id][keyword]" class=" form-control" value="<?php echo $custom_url_store_top['keyword']; ?>" /></td>
			    				<?php } ?>
			    			<?php } ?>
			    			<td align="center"><a style="text-decoration: none;"  onclick="$('#custom_url_store_row<?php echo $custom_url_store_row; ?>').remove();" class="btn btn-primary"><i class="fa fa-times"></i> Remove</a></td>
			    		</tr>
		    		</tbody>
    				<?php $custom_url_store_row++; ?>
    			<?php } ?>
			 	<tfoot>
		            <tr>
		              	<td width="90%" colspan="2">&nbsp;</td>
		              	<td width="10%" align="center"><a style="text-decoration: none;" onclick="addcustom_url_store();" class="btn btn-primary"><span><i class="fa fa-plus"></i>  <?php echo $button_add_custom_url_store; ?></span></a></td>
		            </tr>
	          	</tfoot>    		
	    	</table>
	    	<!-- <div class="pagination"><?php //echo $pagination_general; ?></div> -->
	    	<textarea class="hidden" name="tab" value="tab_general">tab_general</textarea>
	    </div>
	</form>

	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
	    <div id="tab_products">
            <div class="row">
	            	<div class="col-sm-6">
	            		<div class="col-sm-4">
		            	<input type="text" name="filter_name" value="<?php echo $filter_keyword; ?>" placeholder="Enter Product Name" id="input-name" class="form-control" />
		           		</div>
		           		<div class="col-sm-4">
		            	<button type="button"  onclick="filter();" class="btn btn-primary"><i class="fa fa-search"></i> Filter</button>
		              	<button type="button"  onclick="reset_filter();" class="btn btn-primary"><i class="fa fa-search"></i> Reset</button>
		              	</div>
	              	</div>
	              	<div class="col-sm-4">
		              	<?php foreach ($languages as $language) { ?>
			    	  	<div class="buttons button_tabs">
			    	      	<a value="product_language<?php echo $language['language_id']; ?>" class="btn btn-primary product_language_button" onclick="showLang('product_language<?php echo $language['language_id']; ?>','product_language');"><span><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><?php echo $language['name']; ?></span></a>
			    	  	</div>
			    		<?php } ?>
		    		</div>
	              	<div class="col-sm-2">
		      			<div class="pull-right">
		              	   <button type="submit"  form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
		               </div>
	   			 	</div>
          	 </div>
             <div class="pagination pull-right"><?php echo $pagination_product; ?></div>
            
	    	<div class="clear" style="margin-top: 25px;"></div>
	    	<table class="pure-table pure-table-bordered" style="width:100%;">
				<thead>
					<tr>
						<td class="center"><?php echo $column_image; ?></td>
						<td class="left"><?php echo $column_name; ?></td>
						<td class="left"><?php echo $column_keyword; ?></td>
						<td class="center brt"><?php echo $column_title; ?><a class="help_icon" title="<?php echo $title_help ?>"></a></td>
						<td class="center brt"><?php echo $column_meta_keyword; ?><a class="help_icon" title="<?php echo $keywords_help ?>"></a></td>
						<td class="center"><?php echo $column_meta_description; ?><a class="help_icon" title="<?php echo $description_help ?>"></a></td>
						<td class="center"><?php echo $column_tags; ?><a class="help_icon" title="<?php echo $tags_help ?>"></a></td>
					</tr>
				</thead>    
					
	    		<tbody>
		    		<?php if ($products) { ?>
	          			<?php foreach ($products as $product) { ?>
				    		<tr>
				    			<td class="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
				    			<td class="left"><?php echo $product['name']; ?></td>
				    			<td class="left"><?php echo $domain; ?>
				    				<?php foreach ($languages as $language) { ?>
				    				<input size="30"  type="text" class="product_language<?php echo $language['language_id']; ?> form-control"  name="product[keyword][<?php echo $product['product_id'] ?>][<?php echo $language['language_id']; ?>]" value="<?php echo $product['keyword']['language_id'][$language['language_id']]; ?>" />
				    				<?php } ?>
				    			</td>
				    			<td class="brt center">
					    			<?php foreach ($languages as $language) { ?>
					    				<textarea class="product_language<?php echo $language['language_id']; ?> ct form-control" name="product[product_description][<?php echo $product['product_id']; ?>][<?php echo $language['language_id']; ?>][title]" cols="18" rows="6"><?php echo isset($product['product_description'][$language['language_id']]) ? $product['product_description'][$language['language_id']]['title'] : ''; ?></textarea>
					    				<span class="product_language<?php echo $language['language_id']; ?> countt">-</span>
					    				<?php if (isset(${'error_name_product_'.$product['product_id'].'_'.$language['language_id']})) { ?>
					    					<span class="product_language<?php echo $language['language_id']; ?> error"><?php echo ${'error_name_product_'.$product['product_id'].'_'.$language['language_id']}; ?></span>
					    				<?php } ?>
					    			<?php } ?>
				    			</td>
				    			<td class="brt center">
					    			<?php foreach ($languages as $language) { ?>
					    				<textarea class="product_language<?php echo $language['language_id']; ?> ck form-control" name="product[product_description][<?php echo $product['product_id']; ?>][<?php echo $language['language_id']; ?>][meta_keywords]" cols="18" rows="6"><?php echo isset($product['product_description'][$language['language_id']]) ? $product['product_description'][$language['language_id']]['meta_keywords'] : ''; ?></textarea>
					    				<span class="product_language<?php echo $language['language_id']; ?> countk">-</span>
					    			<?php } ?>
				    			</td>
				    			<td class="center">
					    			<?php foreach ($languages as $language) { ?>
					    				<textarea class="product_language<?php echo $language['language_id']; ?> cc form-control" name="product[product_description][<?php echo $product['product_id']; ?>][<?php echo $language['language_id']; ?>][meta_description]" cols="18" rows="6"><?php echo isset($product['product_description'][$language['language_id']]) ? $product['product_description'][$language['language_id']]['meta_description'] : ''; ?></textarea>
					    				 <span class="product_language<?php echo $language['language_id']; ?> count">-</span>
					    			<?php } ?>
				    			</td>
				    			<td class="center">
					    			<?php foreach ($languages as $language) { ?>
					    				<textarea class="product_language<?php echo $language['language_id']; ?> cta form-control" name="product[product_description][<?php echo $product['product_id']; ?>][<?php echo $language['language_id']; ?>][tags]" cols="18" rows="6"><?php echo isset($product['product_description'][$language['language_id']]) ? $product['product_description'][$language['language_id']]['tags'] : ''; ?></textarea>
					    				 <span class="product_language<?php echo $language['language_id']; ?> countta">-</span>
					    			<?php } ?>
				    			</td>
				    		</tr>
				    	<?php } ?>
					<?php } else { ?>
			          		<tr>
				            	<td class="center" colspan="7"><?php echo $text_no_results; ?></td>
				          	</tr>
				    <?php } ?>
	    		</tbody>	
	    	</table>
	    	<textarea class="hidden" name="tab" value="tab_products">tab_products</textarea>
	    </div>
	</form>
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form_categories">
	    <div id="tab_categories">
	    	 <div class="row">
	            	<div class="col-sm-6">
	            		<div class="col-sm-4">
		            		<input type="text" name="filter_category" value="<?php echo $filter_category; ?>" placeholder="Enter Category Name" id="input-name" class="form-control" />
		            	</div>
		            	<div class="col-sm-4">
		            		<button type="button"  onclick="filterc();" class="btn btn-primary"><i class="fa fa-search"></i> Filter</button>
		              		<button type="button"  onclick="reset_filterc();" class="btn btn-primary"><i class="fa fa-search"></i> Reset</button>
		              	</div>
	              	</div>
	              	<div class="col-sm-4">
		              	<?php foreach ($languages as $language) { ?>
			    	  	<div class="buttons button_tabs">
			    	      	<a value="category_language<?php echo $language['language_id']; ?>" class="btn btn-primary button category_language_button" onclick="showLang('category_language<?php echo $language['language_id']; ?>','category_language');"><span><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><?php echo $language['name']; ?></span></a>
			    	  	</div>
			    		<?php } ?>
		    		</div>
	              	<div class="col-sm-2">
		      			<div class="pull-right">
		              	   <button onclick="$('#form_categories').submit();" form="form_categories" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		               </div>
	   			 	</div>
              	
          	  </div>
            <div class="pagination pull-right"><?php echo $pagination_category; ?></div>
	    	<div class="clear" style="margin-top: 25px;"></div>
	    	<table class="pure-table pure-table-bordered" style="width:100%;">
				<thead>
					<tr>
						<td class="left"><?php echo $column_name; ?></td>
						<td class="left"><?php echo $column_keyword; ?></td>
						<td class="center brt"><?php echo $column_title; ?><a class="help_icon" title="<?php echo $title_help ?>"></a></td>
						<td class="center brt"><?php echo $column_meta_keyword; ?><a class="help_icon" title="<?php echo $keywords_help ?>"></a></td>
						<td class="center"><?php echo $column_meta_description; ?><a class="help_icon" title="<?php echo $description_help ?>"></a></td>
					</tr>
				</thead>    
					
	    		<tbody>
		    		<?php if ($categories) { ?>
	          			<?php foreach ($categories as $category) { ?>
				    		<tr>
				    			<td class="left"><?php echo $category['name']; ?></td>
				    			<td class="left"><?php echo $domain; ?>
				    				<?php foreach ($languages as $language) { ?>
				    				<input size="30"  type="text" class="category_language<?php echo $language['language_id']; ?> form-control form-control"  name="category[keyword][<?php echo $category['category_id'] ?>][<?php echo $language['language_id']; ?>]" value="<?php echo $category['keyword']['language_id'][$language['language_id']]; ?>" />
				    				<?php } ?>
				    			</td>
				    			<td class="brt center">
					    			<?php foreach ($languages as $language) { ?>
					    				<textarea class="category_language<?php echo $language['language_id']; ?> ct form-control form-control" name="category[category_description][<?php echo $category['category_id']; ?>][<?php echo $language['language_id']; ?>][title]" cols="20" rows="6"><?php echo isset($category['category_description'][$language['language_id']]) ? $category['category_description'][$language['language_id']]['title'] : ''; ?></textarea>
					    				<span class="category_language<?php echo $language['language_id']; ?> countt">-</span>
					    				<?php if (isset(${'error_name_category_'.$category['category_id'].'_'.$language['language_id']})) { ?>
					    					<span class="category_language<?php echo $language['language_id']; ?> error"><?php echo ${'error_name_category_'.$category['category_id'].'_'.$language['language_id']}; ?></span>
					    				<?php } ?>
					    			<?php } ?>
				    			</td>
				    			<td class="brt center">
					    			<?php foreach ($languages as $language) { ?>
					    				<textarea class="category_language<?php echo $language['language_id']; ?> ck form-control form-control" name="category[category_description][<?php echo $category['category_id']; ?>][<?php echo $language['language_id']; ?>][meta_keywords]" cols="25" rows="6"><?php echo isset($category['category_description'][$language['language_id']]) ? $category['category_description'][$language['language_id']]['meta_keywords'] : ''; ?></textarea>
					    				<span class="category_language<?php echo $language['language_id']; ?> countk">-</span>
					    			<?php } ?>
				    			</td>
				    			<td class="center">
					    			<?php foreach ($languages as $language) { ?>
					    				<textarea class="category_language<?php echo $language['language_id']; ?> cc form-control form-control" name="category[category_description][<?php echo $category['category_id']; ?>][<?php echo $language['language_id']; ?>][meta_description]" cols="25" rows="6"><?php echo isset($category['category_description'][$language['language_id']]) ? $category['category_description'][$language['language_id']]['meta_description'] : ''; ?></textarea>
					    				<span class="category_language<?php echo $language['language_id']; ?> count">-</span>
					    			<?php } ?>
				    			</td>
				    		</tr>
				    	<?php } ?>
					<?php } else { ?>
			          		<tr>
				            	<td class="center" colspan="5"><?php echo $text_no_results; ?></td>
				          	</tr>
				    <?php } ?>
	    		</tbody>
	    	</table>
	    	<!-- <div class="pagination"><?php //echo $pagination_category; ?></div> -->
	    	<textarea class="hidden" name="tab" value="tab_categories">tab_categories</textarea>
	    </div>
	</form>

	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form_manufacturers">	
	    <div id="tab_manufacturers">
	    	    <div class="form-group">
	              	<div class="col-sm-4">
		              	<?php foreach ($languages as $language) { ?>
			    	  	<div class="buttons button_tabs">
			    	      	<a value="manufacturer_language<?php echo $language['language_id']; ?>" class="btn btn-primary button manufacturer_language_button" onclick="showLang('manufacturer_language<?php echo $language['language_id']; ?>','manufacturer_language');"><span><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><?php echo $language['name']; ?></span></a>
			    	  	</div>
			    		<?php } ?>
		    		</div>
	              	<div class="container-fluid">
		      			<div class="pull-right">
		              	   <button onclick="$('#form_manufacturers').submit();" form="form_manufacturers" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		               </div>
	   			 	</div>
          	    </div>
	    	<div class="clear"></div>
	    	<table class="pure-table pure-table-bordered" style="width:100%;">
				<thead>
					<tr>
						<td class="left"><?php echo $column_name; ?></td>
						<td class="left"><?php echo $column_keyword; ?></td>
						<td class="center brt"><?php echo $column_title; ?><a class="help_icon" title="<?php echo $title_help ?>"></a></td>
						<td class="center brt"><?php echo $column_meta_keyword; ?><a class="help_icon" title="<?php echo $keywords_help ?>"></a></td>
						<td class="center"><?php echo $column_meta_description; ?><a class="help_icon" title="<?php echo $description_help ?>"></a></td>
					</tr>
				</thead>    
					
	    		<tbody>
		    		<?php if ($manufacturers) { ?>
	          			<?php foreach ($manufacturers as $manufacturer) { ?>
				    		<tr>
				    			<td class="left"><?php echo $manufacturer['name']; ?></td>
				    			<td class="left"><?php echo $domain; ?><input size="30"  type="text" name="manufacturer[manufacturer_id][<?php echo $manufacturer['manufacturer_id'] ?>]" value="<?php echo $manufacturer['keyword']; ?>" class="form-control" /></td>
				    			<td class="brt center">
					    			<?php foreach ($languages as $language) { ?>
					    				<textarea class="manufacturer_language<?php echo $language['language_id']; ?> ct form-control" name="manufacturer[manufacturer_description][<?php echo $manufacturer['manufacturer_id']; ?>][<?php echo $language['language_id']; ?>][title]" cols="20" rows="6"><?php echo isset($manufacturer['manufacturer_description'][$language['language_id']]) ? $manufacturer['manufacturer_description'][$language['language_id']]['title'] : ''; ?></textarea>
					    				<span class="manufacturer_language<?php echo $language['language_id']; ?> countt">-</span>
					    				<?php if (isset(${'error_name_manufacturer_'.$manufacturer['manufacturer_id'].'_'.$language['language_id']})) { ?>
					    					<span class="manufacturer_language<?php echo $language['language_id']; ?> error"><?php echo ${'error_name_manufacturer_'.$manufacturer['manufacturer_id'].'_'.$language['language_id']}; ?></span>
					    				<?php } ?>
					    			<?php } ?>
				    			</td>
				    			<td class="brt center">
					    			<?php foreach ($languages as $language) { ?>
					    				<textarea class="manufacturer_language<?php echo $language['language_id']; ?> ck form-control" name="manufacturer[manufacturer_description][<?php echo $manufacturer['manufacturer_id']; ?>][<?php echo $language['language_id']; ?>][meta_keywords]" cols="25" rows="6"><?php echo isset($manufacturer['manufacturer_description'][$language['language_id']]) ? $manufacturer['manufacturer_description'][$language['language_id']]['meta_keywords'] : ''; ?></textarea>
					    				<span class="manufacturer_language<?php echo $language['language_id']; ?> countk">-</span>
					    			<?php } ?>
				    			</td>
				    			<td class="center">
					    			<?php foreach ($languages as $language) { ?>
					    				<textarea class="manufacturer_language<?php echo $language['language_id']; ?> cc form-control" name="manufacturer[manufacturer_description][<?php echo $manufacturer['manufacturer_id']; ?>][<?php echo $language['language_id']; ?>][meta_description]" cols="25" rows="6"><?php echo isset($manufacturer['manufacturer_description'][$language['language_id']]) ? $manufacturer['manufacturer_description'][$language['language_id']]['meta_description'] : ''; ?></textarea>
					    				<span class="manufacturer_language<?php echo $language['language_id']; ?> count">-</span>
					    			<?php } ?>
				    			</td>
				    		</tr>
				    	<?php } ?>
					<?php } else { ?>
			          		<tr>
				            	<td class="center" colspan="5"><?php echo $text_no_results; ?></td>
				          	</tr>
				    <?php } ?>
	    		</tbody>
	    	</table>
	    	<!-- <div class="pagination"><?php //echo $pagination_manufacturer; ?></div> -->
	    	<textarea class="hidden" name="tab" value="tab_manufacturers">tab_manufacturers</textarea>
	    </div>
	</form>

	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form_information_pages">
	    <div id="tab_information_pages">
	    	<div class="form-group">
	              	<div class="col-sm-4">
		              	<?php foreach ($languages as $language) { ?>
			    	  	<div class="buttons button_tabs">
			    	      	<a value="information_language<?php echo $language['language_id']; ?>" class="btn btn-primary button information_language_button" onclick="showLang('information_language<?php echo $language['language_id']; ?>','information_language');"><span><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><?php echo $language['name']; ?></span></a>
			    	  	</div>
			    		<?php } ?>
		    		</div>
	              	<div class="container-fluid">
		      			<div class="pull-right">
		              	   <button onclick="$('#form_information_pages').submit();" form="form_information_pages" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		               </div>
	   			 	</div>
          	    </div>
	    	<div class="clear"></div>
	    	<table class="pure-table pure-table-bordered" style="width:100%;">
				<thead>
					<tr>
						<td class="left"><?php echo $column_name; ?></td>
						<td class="left"><?php echo $column_keyword; ?></td>
						<td class="center brt"><?php echo $column_title; ?><a class="help_icon" title="<?php echo $title_help ?>"></a></td>
						<td class="center brt"><?php echo $column_meta_keyword; ?><a class="help_icon" title="<?php echo $keywords_help ?>"></a></td>
						<td class="center"><?php echo $column_meta_description; ?><a class="help_icon" title="<?php echo $description_help ?>"></a></td>
					</tr>
				</thead>    
					
	    		<tbody>
		    		<?php if ($informations) { ?>
	          			<?php foreach ($informations as $information) { ?>
				    		<tr>
				    			<td class="left"><?php echo $information['name']; ?></td>
				    			<td class="left"><?php echo $domain; ?>
				    				<?php foreach ($languages as $language) { ?>
				    				<input size="30"  type="text" class="information_language<?php echo $language['language_id']; ?> form-control"  name="information[keyword][<?php echo $information['information_id'] ?>][<?php echo $language['language_id']; ?>]" value="<?php echo $information['keyword']['language_id'][$language['language_id']]; ?>" />
				    				<?php } ?>
				    			</td>
				    			<td class="brt center">
					    			<?php foreach ($languages as $language) { ?>
					    				<textarea class="information_language<?php echo $language['language_id']; ?> ct form-control" name="information[information_description][<?php echo $information['information_id']; ?>][<?php echo $language['language_id']; ?>][title]" cols="20" rows="6"><?php echo isset($information['information_description'][$language['language_id']]) ? $information['information_description'][$language['language_id']]['title'] : ''; ?></textarea>
					    				<span class="information_language<?php echo $language['language_id']; ?> countt">-</span>
					    				<?php if (isset(${'error_name_information_'.$information['information_id'].'_'.$language['language_id']})) { ?>
					    					<span class="information_language<?php echo $language['language_id']; ?> error"><?php echo ${'error_name_information_'.$information['information_id'].'_'.$language['language_id']}; ?></span>
					    				<?php } ?>
					    			<?php } ?>
				    			</td>
				    			<td class="brt center">
					    			<?php foreach ($languages as $language) { ?>
					    				<textarea class="information_language<?php echo $language['language_id']; ?> ck form-control" name="information[information_description][<?php echo $information['information_id']; ?>][<?php echo $language['language_id']; ?>][meta_keywords]" cols="25" rows="6"><?php echo isset($information['information_description'][$language['language_id']]) ? $information['information_description'][$language['language_id']]['meta_keywords'] : ''; ?></textarea>
					    				<span class="information_language<?php echo $language['language_id']; ?> countk">-</span>
					    			<?php } ?>
				    			</td>
				    			<td class="center">
					    			<?php foreach ($languages as $language) { ?>
					    				<textarea name="information[information_description][<?php echo $information['information_id']; ?>][<?php echo $language['language_id']; ?>][meta_description]" cols="25" rows="6" class="information_language<?php echo $language['language_id']; ?> cc form-control"><?php echo isset($information['information_description'][$language['language_id']]) ? $information['information_description'][$language['language_id']]['meta_description'] : ''; ?></textarea>
					    				<span class="information_language<?php echo $language['language_id']; ?> count">-</span>		
					    			<?php } ?>
				    			</td>
				    		</tr>
				    	<?php } ?>
					<?php } else { ?>
			          		<tr>
				            	<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
				          	</tr>
				    <?php } ?>
	    		</tbody>
	    	</table>
	    	<!-- <div class="pagination"><?php //echo $pagination_information; ?></div> -->
	    	<textarea class="hidden" name="tab" value="tab_information_pages">tab_information_pages</textarea>
	    </div>
    </form>
  </div>
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">How to customize seo data more?</h1>
            </div>
            <div class="modal-body">
              <?php echo $helpedit; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
var custom_url_store_row = <?php echo $custom_url_store_row; ?>;
var currency = "<?php echo $currency; ?>";
var edited = false;
function addcustom_url_store() {
	html  = '<tbody id="custom_url_store_row' + custom_url_store_row + '">';
	html += '<tr>'; 
    html += '<td class="left"><?php echo $domain; ?>index.php?route=<input type="text" size="18" name="custom_url_store['+custom_url_store_row+'][id][query]" value="" /></td>';
	html += '<td class="left"><?php echo $domain; ?><input type="text" size="30" name="custom_url_store['+custom_url_store_row+'][id][keyword]" value="" /></td>';
	html += '<td align="center"><a style="text-decoration: none;" onclick="$(\'#custom_url_store_row' + custom_url_store_row + '\').remove();" class="btn btn-primary"><span><i class="fa fa-plus"></i> <?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
    html += '</tbody>';
	
	$('#custom_url_store tfoot').before(html);
	
	custom_url_store_row++;
	showLang('custom_url_store_language'+currency,'custom_url_store_language');
}

function showLang(selected,button){
	$('.'+selected).siblings().hide();
	$('.'+selected).show();
	$('.button_tabs .'+button+'_button').css('opacity','1');
	$('.button_tabs a[value="'+selected+'"]').css('opacity','0.5');
}

showLang('custom_url_store_language'+currency,'custom_url_store_language');
showLang('product_language'+currency,'product_language');
showLang('category_language'+currency,'category_language');
showLang('manufacturer_language'+currency,'manufacturer_language');
showLang('information_language'+currency,'information_language');

$.fn.tabs = function() {
	var selector = this;
	
	this.each(function() {
		var obj = $(this); 
		
		$(obj.attr('href')).hide();
		
		$(obj).click(function() {
			$(selector).removeClass('selected');
			
			$(selector).each(function(i, element) {
				$($(element).attr('href')).hide();
			});
			
			$(this).addClass('selected');
			
			$($(this).attr('href')).show();
			
			return false;
		});
	});

	$(this).show();
	
	$(this).first().click();
};


$(document).ready(function() {
	var click_tab = 'a[href=#'+"<?php echo $tab; ?>"+']';
	$(click_tab).click();
	$("#tabs a").unbind('click');
	$("#tabs a").each(function() {
		tab = $(this).attr('href').substr(1);
		$(this).attr('href','<?php echo html_entity_decode($action) ?>&tab=' + tab);
        
	});
	$("input, textarea").keypress(function() {
		edited = true;
	});
	$("form").submit(function(e) {
	    edited = false;
	});

	$(".count").each(function() {
        var l = $(this).prev().val().length;
        if(l == 0){
            $(this).html('✘');
        } else {
            $(this).html('✔');
        }
    });
    $(".countk").each(function() {
        var l = $(this).prev().val().length;
        if(l == 0){
            $(this).html('✘');
        } else {
            $(this).html('✔');
        }
    });

    $(".countt").each(function() {
        var l = $(this).prev().val().length;
        if(l == 0){
            $(this).html('✘');
        } else {
            $(this).html('✔');
        }
    });

    $(".countta").each(function() {
        var l = $(this).prev().val().length;
        if(l == 0){
            $(this).html('✘');
        } else {
            $(this).html('✔');
        }
    });

	
});

$(function(){
	$(".help_icon").tipTip({
		maxWidth: "275px", 
		defaultPosition: "top",
		delay: 100
	});
});

<?php if(isset($existing_keyword) && $existing_keyword){ ?>
	var existing_keyword = "<?php echo $existing_keyword; ?>";
	var selector = $('input[value*="'+existing_keyword+'"]');
	var trimmed;
	$(selector).each(function() {
	  	trimmed = $.trim($(this).val());
	  	if(trimmed == existing_keyword){
	  		$(this).addClass('existing_keyword');
	  	}
	});
	$(selector).keyup(function() {
		$(this).removeClass('existing_keyword');
	});
<?php } ?>

//--></script>

<script type="text/javascript"><!--
$('#tabs a').tabs();

window.onbeforeunload = confirmExit;

function confirmExit()  {
    if(edited) {
        var click_tab = 'a[href=#'+"<?php echo $tab; ?>"+']';
        $(click_tab).click();
        return "Save the changes, page refresh will result to loose unsaved data";
    } 
}

<!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});

$('input[name=\'filter_model\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}
});
//-->

function filter(){
    url = '<?php echo html_entity_decode($filter); ?>';
	
    var filter_keyword = $('input[name=\'filter_name\']').val();
	
	if (filter_keyword) {
		url += '&filter_keyword=' + encodeURIComponent(filter_keyword);
	}

    location = url;
}

function reset_filter() {
    url = '<?php echo html_entity_decode($filter); ?>';
    location = url;
}

function filterc(){
    url = '<?php echo html_entity_decode($filter); ?>';
	
    var filter_category = $('input[name=\'filter_category\']').val();
	
	if (filter_category) {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}
	
    location = url;
}

function reset_filterc() {
    url = '<?php echo html_entity_decode($filter); ?>';
    location = url;
}

$('.cc').keyup(function(){
    var l = $(this).val().length;
    if (l == 0) {
         $(this).next().html('✘');
    } else {
         $(this).next().html('✔');
    }
});

$('.ck').keyup(function(){
    var l = $(this).val().length;
    if (l == 0) {
         $(this).next().html('✘');
    } else {
         $(this).next().html('✔');
    }
});

$('.ct').keyup(function(){
    var l = $(this).val().length;
    if (l == 0) {
         $(this).next().html('✘');
    } else {
         $(this).next().html('✔');
    }
});


$('.cta').keyup(function(){
    var l = $(this).val().length;
    if (l == 0) {
         $(this).next().html('✘');
    } else {
         $(this).next().html('✔');
    }
});


/*
$('input[name=filter_keyword]').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
*/
    
//--></script>
<script type="text/javascript">
$('#content #cssmenu ul li:nth-child(2)').addClass('active'); 
</script>
<?php echo $footer; ?>