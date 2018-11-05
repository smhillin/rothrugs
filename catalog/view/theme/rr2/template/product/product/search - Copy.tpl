<?php echo $header; ?>

<div id="content" class="container products-page">
	<ul id="filter" class="nav nav-justified">
		<li class="filter_item filter_title">RUG FINDER</li>
		<li class="filter_item">
			<select class="rug-finder-multiselect" multiple="multiple" title="style" name="option_id">
				<?php foreach ($finder_options['Style'] as $key => $value) {
					if(in_array($value['option_value_id'], $option_id))
						echo '<option value="'.$value['option_value_id'].'" selected>'.$value['name'].'</option>';
					else
						echo '<option value="'.$value['option_value_id'].'">'.$value['name'].'</option>';
				}?>
			</select>
		</li>
			<li class="filter_item">
			<select class="rug-finder-multiselect" multiple="multiple" title="category" name="category_id">
			
            <option value="0"><?php echo $text_category; ?></option>
            <?php foreach ($categories as $category_1) { ?>
            <?php if (in_array($category_1['category_id'],$category_id)) { ?>
            <option value="<?php echo $category_1['category_id']; ?>" data_name="<?php echo $category_1['name']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_1['category_id']; ?>"  data_name="<?php echo $category_1['name']; ?>"><?php echo $category_1['name']; ?></option>
            <?php } ?>
            <?php foreach ($category_1['children'] as $category_2) { ?>
            <?php if (in_array($category_2['category_id'],$category_id)) { ?>
            <option value="<?php echo $category_2['category_id']; ?>"  data_name="<?php echo $category_2['name']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_2['category_id']; ?>" data_name="<?php echo $category_2['name']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
            <?php } ?>
            <?php foreach ($category_2['children'] as $category_3) { ?>
            <?php if (in_array($category_3['category_id'], $category_id)) { ?>
            <option value="<?php echo $category_3['category_id']; ?>" data_name="<?php echo $category_3['name']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_3['category_id']; ?>" data_name="<?php echo $category_3['name']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </select>
		</li>
		<li class="filter_item">
			<select class="rug-finder-multiselect" id='option_color' multiple="multiple" title="color" name="option_id">
				<?php foreach ($finder_options['Color'] as $key => $value) {
					if(in_array($value['option_value_id'], $option_id))
						echo '<option data_name="'.$value['name'].'" value="'.$value['option_value_id'].'" selected>'.$value['name'].'</option>';
					else
						echo '<option data_name="'.$value['name'].'" value="'.$value['option_value_id'].'">'.$value['name'].'</option>';
				}?>
			</select>
		</li>
		<li class="filter_item">
			<select class="rug-finder-multiselect" multiple="multiple" title="price" name="price">
				<?php foreach ($finder_prices as $key => $value) {
					if(in_array($key, $price_id))
						echo '<option value="'.$key.'" selected>'.$value.'</option>';
					else
						echo '<option value="'.$key.'">'.$value.'</option>';
				}?>
			</select>
		</li>
		<li class="filter_item">
			<select class="rug-finder-multiselect" multiple="multiple" title="size" name="option_id">
				<?php foreach ($finder_options['Size'] as $key => $value) {
					if(in_array($value['option_value_id'], $option_id))
						echo '<option value="'.$value['option_value_id'].'" selected>'.$value['name'].'</option>';
					else
						echo '<option value="'.$value['option_value_id'].'">'.$value['name'].'</option>';
				}?>
			</select>
		</li>
		<li class="filter_item filter_search"  id="button-search"><div>FILTER SEARCH</div></li>
	</ul>
	<div class="clearfix"></div>

	<div class="rugs_grid">
		<!--promo banner-->
		<div class="col-sm-4 rug promo-banner right">
			<?php if(empty($banner['link'])): ?>
			<img src="<?php echo $banner['image'] ?>" />
			<?php else: ?>
			<a href="<?php echo $banner['link']; ?>">
				<img src="<?php echo $banner['image'] ?>" />
			</a>
			<?php endif; ?>
		</div>
		<!--promo banner-->
		<?php foreach ($products as $product) { ?>  
		<div class="col-sm-4 rug">
			<div class="rug_img">
				<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
			</div>
			<div class="rug_details">
				<a href="<?php echo $product['href']; ?>">
					<p><?php echo $product['name']; ?></p>
					<p class="price">FROM <span><?php echo $product['lowest-price']; ?> USD</span></p>
				</a>
				<div class="view-btn text-right"><a href="<?php echo $product['href']; ?>">View Details</a></div>
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="row clear">
		<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
		<div class="col-sm-6 text-right"><?php echo $results; ?></div>
	</div>
</div>

<?php echo $footer; ?>
<script type="text/javascript"><!--
$('#button-search').on('click', function(e) {
e.preventDefault();

	url = 'index.php?route=product/search';
	
	var option_id = [];
	var option_name = [];
	$('#content select[name=\'option_id\']').each(function(){
		if($(this).val() !== null){
			option_id.push($(this).val());
		}
	
		if($(this).attr('id') == 'option_color' && $('option:selected', this).attr('data_name') !== null){
			$(this).children("option:selected").each(function(){
				var str = $(this).attr('data_name');
				str = str.split('&');
				str.forEach(function(name) {
			   option_name.push( name);
			   });

		   });
				
		}
		
	});

	if (option_id != '') {
		url += '&option_id=' + option_id;
	}
	if(option_name != ''){
		url += '&filter_filter_name='+ option_name;
	}

	var price_id = [];
	$('#content select[name=\'price\']').each(function(){
		if($(this).val() !== null){
			price_id.push($(this).val());
		}
	});

	if (price_id != '') {
		url += '&price_id=' + price_id;
	}
	var category_id = [];
	var category_name =[];
	$('#content select[name=\'category_id\']').each(function(){
		if($(this).val() !== null){
			category_id.push($(this).val());
		}
		
		if($('option:selected', this).attr('data_name') !== null){
			category_name.push($('option:selected', this).attr('data_name'));
		}
	});
	if(category_id !=''){
		url += '&category_id=' + category_id;
	}
	location = url;

});

--></script>