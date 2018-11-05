	
		  <ul class="colors" id="casorel_color">
			  <?php if(!empty($product_same_color)): ?>
		
			
				<?php foreach($product_same_color as $item):?>
				<li class="color">
				<a  class="colorbox" href="<?php echo $item['url']?>"><img src="<?php echo $item['images'] ?>"></a></li>
				<?php 
				endforeach;?>
				<?php else: ?>
				<p>No item found</p>
				<?php endif;?>
		  </ul>
		<a id="prev_carousel" class="prev  glyphicon glyphicon-chevron-left" href="#" title="prev"></a>
		<a id="next_carousel" class="next  glyphicon glyphicon-chevron-right" href="#" title="next"></a>

