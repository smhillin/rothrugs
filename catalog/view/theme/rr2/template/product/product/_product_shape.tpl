<div class="begin-carousel">
	<ul class="shapes" id="casorel_shapes">
		<?php if(!empty($product_same)): ?>
		<?php foreach($product_same as $item):?>

		<li class="shape" id="shape_<?php echo $item['option_shape'] ?>" data-value="<?php echo $item['product_id'] ?>"
			data-replace-to="color" 
			data-option-value-id="<?php  echo $item['option_shape'] ?>"
			data-option-id="<?php echo isset($item['options']['Color'])?$item['options']['Color']['option_id']:'' ?>">
			<a  class="colorbox"  ><img src="<?php echo $item['images'] ?>"></a></li>
		<?php 
		endforeach;?>
		<?php else: ?>
		<p>No item found</p>
		<?php endif;?>
	</ul>

	<input type="hidden" id="product_choose" value="<?php echo $product_id ?>">
	<input type="hidden" id="shape_option_choose" value="<?php echo $active_option_value_id ?>">
</div>
<a id="prev_carousel_shape" class="prev  glyphicon glyphicon-chevron-left" href="#" title="prev"></a>
<a id="next_carousel_shape" class="next  glyphicon glyphicon-chevron-right" href="#" title="next"></a>
<script type="text/javascript" language="javascript">
	jQuery(document).ready(function($) {
		$('#casorel_shapes').carouFredSel({
			auto: false,
			responsive: true,
			width: '100%',
			circular: false,
			infinite: false,
			prev: '#prev_carousel_shape',
			next: '#next_carousel_shape',
			swipe: {
				onTouch: true
			},
			items: {
				width: 71,
				height: 71,
				visible: {
					min: 1,
					max: 4
				}
			},
			scroll: {
				direction: 'left', //  The direction of the transition.
				duration: 500   //  The duration of the transition.
			}
		});
	});
</script>