	<div class="begin-carousel">
			<ul class="colors" id="casorel_color" >
				<?php if(!empty($product_same)): ?>
				<?php foreach($product_same as $item):?>
				<li id="color_<?php echo $item['product_id'] ?>" class="color " data-value="<?php echo $item['product_id'] ?>" 
					data-replace-to="shape" 
					data-product-option-value-id="<?php echo isset($item['product_option_value_id'])?$item['product_option_value_id']:''?>"
					data-product-option-id="<?php echo isset($item['product_option_id'])?$item['product_option_id']:''?>"
					data-option-value-id="<?php echo isset($item['option_value_id'])?$item['option_value_id']:'' ?>"
					data-option-id="<?php echo isset($item['options']['Color'])?$item['options']['Color']['option_id']:'' ?>"
					data-optoin-rule="<?php echo isset($item['options']['Shape'])?$item['options']['Shape']['option_id']:''?>">
					<a  class="colorbox"  ><img src="<?php echo $item['images'] ?>"></a>
				</li>
				<?php 
				endforeach;?>
				<?php else: ?>
				<p>No item found</p>
				<?php endif;?>
			</ul>
	
		</div>
<a id="prev_carousel" class="prev  glyphicon glyphicon-chevron-left" href="#" title="prev"></a>
			<a id="next_carousel" class="next  glyphicon glyphicon-chevron-right" href="#" title="next"></a>
					<script type="text/javascript" language="javascript">
			jQuery(document).ready(function($) {
				$('#casorel_color').carouFredSel({
					auto: false,
					responsive: true,
					width: '100%',
					circular: false,
					infinite: false,
					prev: '#prev_carousel',
					next: '#next_carousel',
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
			<input type="hidden" id="product_was_choose" value="">
			<input type="hidden" id="color_option_choose" value="<?php echo $active_option_value_id ?>">
				
