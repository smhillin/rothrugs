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
			<select class="rug-finder-multiselect" id="option_color" multiple="multiple" title="color" name="filter_color">
				<?php foreach ($finder_filter['Color'] as $key => $value) {
					if(in_array($value['filter_id'], $filter_color_id))
						echo '<option data_name="'.$value['name'].'" value="'.$value['filter_id'].'" selected>'.$value['name'].'</option>';
					else
						echo '<option data_name="'.$value['name'].'" value="'.$value['filter_id'].'">'.$value['name'].'</option>';
				}?>
			</select>
		</li>
		<li class="filter_item">
			<select class="rug-finder-multiselect" multiple="multiple" title="price" name="price">
				<?php foreach ($finder_filter['Price'] as  $finder_filter) {
				$from = !empty($finder_filter['from_value'])? (int) $finder_filter['from_value']:0;
				$to = !empty($finder_filter['to_value'])? (int) $finder_filter['to_value']:0;
					if(in_array($finder_filter['filter_id'], $filter_price_id))
						echo '<option data-price="'.$from .'-'.$to.'" value="'.$finder_filter['filter_id'].'" selected>'.$finder_filter['name'].'</option>';
					else
						echo '<option data-price="'.$from .'-'.$to.'"  value="'.$finder_filter['filter_id'].'">'.$finder_filter['name'].'</option>';
				}?>
			</select>
		</li>
		<li class="filter_item">
			<select class="rug-finder-multiselect" multiple="multiple" title="size" parrentOption ="true" name="option_size_id">
				<?php foreach ($finder_options['Parent Option'] as $key => $value) {
					if(in_array($value['option_value_id'], $option_parent))
						echo '<option  value="'.$value['option_value_id'].'" selected>'.$value['name'].'</option>';
					else
						echo '<option value="'.$value['option_value_id'].'">'.$value['name'].'</option>';
				}?>
			</select>
		</li>
		<li class="filter_item filter_search" id="button-search"><div>GO!</div></li>
	</ul>
	<div class="clearfix"></div>

	<div class="rugs_grid">
		<?php if(!empty($quiz)):?>
			<div class="col-sm-12">
			<label class="btn_red">Check out your quiz results!</label>
		</div>
		<?php endif;?>
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
				<div  class="rug_title"><a href="<?php echo $product['href']; ?>">
					<p><?php echo $product['name']; ?></p>
					
				</a>
					</div>
				<p class="price">FROM <span><?php echo $product['lowest-price']; ?> USD</span></p>
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
$('#button-search').on('click', function() {
	url = 'index.php?route=product/search';
	
	var option_id = [];
	var filter_color_name = [];
	var option_parent = [];
	var filter_color_id = [];
	var filter_price_id = [];
	$('#content select[name=\'option_id\']').each(function(){
		if($(this).val() !== null){
			option_id.push($(this).val());
		}
		
	});
	$('#content select[name=\'option_size_id\']').each(function(){
	if($(this).val() !== null){
			option_parent.push($(this).val());
		}
	});
	$('#content select[name=\'filter_color\']').each(function(){
			if($('option:selected', this).attr('data_name') !== null){
			$(this).children("option:selected").each(function(){
				var str = $(this).attr('data_name');
				filter_color_name.push(str.trim());
				filter_color_id.push($(this).val());
	
			   
		   });	
		}
	
	});
	$('#content select[name=\'price\']').each(function(){
		if($(this).val() !== null){
			filter_price_id.push($(this).val());
		}
		
	});
	
	if (option_id != '') {
		url += '&option_id=' + option_id;
	}
	if(filter_color_name != ''){
		url += '&filter_color_name='+ filter_color_name;
	}
	if(filter_color_name != ''){
		url += '&filter_color_id='+ filter_color_id;
	}
	if(option_parent !=''){
		url += '&option_parent=' + option_parent;
	}
	if (filter_price_id != '') {
		url += '&filter_price_id=' + filter_price_id;
	}
	if(url !=''){
		url +='&find=true';
	}
		
	location = url;
});

--></script>