
<li><a class="a-search" href="javacript:void();"><i class="fa fa-search"></i></a>
	<div  class="search">
		<div id="search">
			<input name="search" id="search-all"  type="text" value="<?php echo $search; ?>" class="form-control" placeholder="What are you looking for?">
			<a class="mobile-only closebtn" href="javascript:;" style="display:none;" onclick="closeSearch(this)"><i class="fa fa-times"></i>
</a>
		</div>
		
	</div>

</li>
<script type="text/javascript" >
// $('#search input[name=\'search\']').on('keydown', function(e) {
// 		if (e.keyCode == 13) {
// 			url = $('base').attr('href') + 'index.php?route=product/search';

// 			var value = $('#search input[name=\'search\']').val();

// 			if (value) {
// 				url += '&search=' + encodeURIComponent(value);
// 			}

// 			location = url;
// 		}
// 	});

function closeSearch($this){
	$('.search div').fadeOut();
}

$(document).on('keydown', '#search-all', function(event) {
	if (event.keyCode == 13) {
		url = $('base').attr('href') + 'index.php?route=product/search';
		var inputlimit = $('#input-limit option:selected').attr('data-limit');
		var value = $(this).val();
		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		if(inputlimit){
			url += '&limit=' + inputlimit;
		}

		console.log(url);
		window.location = url;
	}
	// return false;
});

	</script>