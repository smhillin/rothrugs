$(".shape img").on('click', function() {
	var $option_value_id = $(this).attr('data-value');
	var $product_id_filter = $("#product_id_filter").val();
	var $model_filter = $("#model_filter").val();
	if ($product_id_filter != "" && $model_filter != "" && $option_value_id != "") {
		$.post('index.php?route=product/product/productoption', {
			model_filter: $model_filter,
			option_value_id_filter: $option_value_id,
			product_id_filter: $product_id_filter
		}, function(res) {
			var $result = JSON.parse(res);
			if ($result.result == true) {
				$(".product-page .product_details #casorel_color_id").html($result.html);
			} else {
				$(".product-page .product_details #casorel_color_id").html('<p>No item found</p>');
			}
			callCasurel();
		})

	}
});
function callCasurel() {
	$('#casorel_color').carouFredSel({
		auto: false,
		responsive: true,
		width: '100%',
		prev: '#prev_carousel',
		next: '#next_carousel',
		circular: false,
		infinite: false,
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

}