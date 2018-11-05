$(document).ready(function($) {
	console.log(location.search.split('to'));
	 var div_id = location.search.split('to=')[1] ? location.search.split('to=')[1] : 'none';
	 console.log(div_id);
	if(div_id !='none'){
  goToByScroll(div_id);
	}
 });
 function goToByScroll(id){
      // Remove "link" from the ID
    id = id.replace("link", "");
      // Scroll
    $('html,body').animate({
        scrollTop: ($("#"+id).offset().top -100) },
        'slow');
}