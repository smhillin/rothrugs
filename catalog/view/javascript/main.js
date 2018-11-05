function getURLVar(t) {
    var e = [],
        o = String(document.location).split("?");
    if (o[1]) {
        var c = o[1].split("&");
        for (i = 0; i < c.length; i++) {
            var a = c[i].split("=");
            a[0] && a[1] && (e[a[0]] = a[1])
        }
        return e[t] ? e[t] : ""
    }
}
$(document).ready(function() {
    window.matchMedia("(max-width: 767px)").matches && ($("#footer .first").before($("#footer .social")), $("#footer").after($("#footer .copy")), $("#intro").after($("#intro #why"))), $(".navi").click(function() {
        $(".mobile_menu").toggle()
    }), $('section[data-type="background"]').each(function() {
        var t = $(this);
        $(window).scroll(function() {
            var e = -($(window).scrollTop() / t.data("speed")),
                o = "50% " + e + "px";
            t.css({
                backgroundPosition: o
            })
        })
    }), $(".product-page").length && $("#thumbs img").click(function() {
        $full_img_src = $(this).attr("data-full"), $("#mainPic img").attr("src", $full_img_src)
    }), $(".rug-finder-multiselect").each(function() {
        var t = $(this);
        t.multiselect({
            buttonText: function() {
                return t.attr("title") + ' <b class="caret"></b>'
            }
        })
    });

    $(window).scroll(function() {    
        var scroll = $(window).scrollTop();    
        if (scroll >= 50) {
            $(".header_bg").addClass('sticky');
        } else {
            $(".header_bg").removeClass('sticky');
        }
    });
    $.ajax({
	url: 'https://api.instagram.com/v1/users/' + '559365259' + '/media/recent', // or /users/self/media/recent for Sandbox
	dataType: 'jsonp',
	type: 'GET',
	data: {access_token: '559365259.2ad4424.be257474b7064ecfb3a98660461e911b', count: 10},
	success: function(data){
 		console.log(data);
		for( x in data.data ){
			$('.insta_field').append('<div class="item"><img src="'+data.data[x].images.low_resolution.url+'"></div>');
                }
                $('.insta_field').owlCarousel({
                    navigation: true,
                    pagination:false,
                    rewindNav:false,
                    items : 4,
                    itemsDesktop : [1199,4],
                    itemsDesktopSmall : [980,3],
                    itemsTablet: [768,2],
                    itemsMobile : [479,1]
                });
	},
	error: function(data){
		console.log(data); // send the error notifications to console
	}
}); 
    if ($('.brands').length > 0) {
        $('.brands').owlCarousel({
            autoPlay: 3000,
            pagination:false,
            items : 6,
            itemsDesktop : [1199,4],
            itemsDesktopSmall : [980,3],
            itemsTablet: [768,2],
            itemsMobile : [479,1]
        });
    }

});
var cart = {
    add: function(t, e) {
        $.ajax({
            url: "index.php?route=checkout/cart/add",
            type: "post",
            data: "product_id=" + t + "&quantity=" + ("undefined" != typeof e ? e : 1),
            dataType: "json",
            beforeSend: function() {
                $("#cart > button").button("loading")
            },
            success: function(t) {
                $(".alert, .text-danger").remove(), $("#cart > button").button("reset"), t.redirect && (location = t.redirect), t.success && ($("#content").parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + t.success + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>'), $("#cart-total").html(t.total), $("html, body").animate({
                    scrollTop: 0
                }, "slow"), $("#cart > ul").load("index.php?route=module/cart/info ul li"))
            }
        })
    },
    update: function(t, e) {
        $.ajax({
            url: "index.php?route=checkout/cart/update",
            type: "post",
            data: "key=" + t + "&quantity=" + ("undefined" != typeof e ? e : 1),
            dataType: "json",
            beforeSend: function() {
                $("#cart > button").button("loading")
            },
            success: function(t) {
                $("#cart > button").button("reset"), $("#cart-total").html(t.total), "checkout/cart" == getURLVar("route") || "checkout/checkout" == getURLVar("route") ? location = "index.php?route=checkout/cart" : $("#cart > ul").load("index.php?route=module/cart/info ul li")
            }
        })
    },
    remove: function(t) {
        $.ajax({
            url: "index.php?route=checkout/cart/remove",
            type: "post",
            data: "key=" + t,
            dataType: "json",
            beforeSend: function() {
                $("#cart > button").button("loading")
            },
            success: function(t) {
                $("#cart > button").button("reset"), $("#cart-total").html(t.total), "checkout/cart" == getURLVar("route") || "checkout/checkout" == getURLVar("route") ? location = "index.php?route=checkout/cart" : $("#cart > ul").load("index.php?route=module/cart/info ul li")
            }
        })
    }
};