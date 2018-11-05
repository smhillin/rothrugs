var Product = {};
Product.qtyTotal = 0,
        Product.priceTotal = 0,
        Product.ID = $("#product-id").data("productid"),
        Product.colorOptionID = $(".colors .active").data("product-option-id"),
        Product.shapeOptionID = $(".shapes").attr("id"),
        Product.colorID = $(".colors .active").data("product-option-value-id"),
        Product.sizeOptionId = $(".sizes").attr("id"),
        Product.sizeIDs = {},
        Product.cartData = {},
        Product.product_ids = {},
        Product.OptionDate = {},
        Product.addData = function (t, o) {
            Product.sizeIDs[t] = o
        },
        Product.updatePrice = function () {
            Product.priceTotal = 0, Product.qtyTotal = 0,
                    // $(".quantity select").each(function() {
                    //     var t = parseInt($(this)
                    //             .val()),
                    //         o = parseFloat($(this)
                    //             .data("price")
                    //             .replace(/[^0-9|\.]/g, ""));
                    //     Product.qtyTotal += t, Product.priceTotal += t * o
                    // }),

                    $(".quantity input").each(function () {
                var t = parseInt($(this)
                        .attr('data-val')),
                        o = parseFloat($(this)
                                .data("price")
                                .replace(/[^0-9|\.]/g, ""));
                Product.qtyTotal += t, Product.priceTotal += t * o
            });
                    //Product.qtyTotal > 0 ? $("#button-cart").removeAttr("disabled").text("ADD TO CART") : $("#button-cart").attr("disabled", "disabled").text("Please choose quantity"),
                   // $("#total span").text("$" + Product.priceTotal.toFixed(2));
        },
        Product.prepCartData = function (o) {
            var t = {};
            t.product_id = $("#product-id").data("productid"),
                    t[Product.colorOptionID] = Product.colorID,
                    Product.sizeOptionId = $(".sizes").attr("id")
            //$.each(Product.sizeIDs, function (o, c) {
                t[Product.sizeOptionId] = o, t.quantity = 1, Product.updateCart(t)
            //});

        },
        Product.updateCart = function (t) {
            $.ajax({
                url: "index.php?route=checkout/cart/add",
                type: "post",
                data: t,
                dataType: "json",
                beforeSend: function () {
                    $("#button-cart")
                            .button("loading")
                            .prop("disabled", !0)
                },
                complete: function () {
                    $("#button-cart")
                            .button("reset")
                            .prop("disabled", !1);
                    $("#button-cart").text("ADDED TO CART");

                },
                success: function (json) {
                    $(".error").remove();
                    if (json['error']) {
                        if (json['error']['option']) {
                            var str = '';
                            for (i in json['error']['option']) {

                                str = str + json['error']['option'][i] + '\n';
                            }
                            alert(str);
                        }
                    }
                    if (json['success']) {
                        //location.href = "index.php?route=checkout/cart"

                        var partsOfStr = json.total.split(' ');
                        $(".a-cart span").text(partsOfStr[0].trim());
                        $.ajax({
                            url: "index.php?route=common/cart/minicart",
                            type: "post",
                            success: function (html) {
                                $(".minicart").css('top','52px').html(html);
                                setTimeout(function(){$('.minicart.rem_keep').animate({top: '-1500px'}) } ,2000);
                            }
                        });
                        

                    }
                }
            })
        },
        Product.getDetails = function (product_id) {
            //$(".bot_content").html("<p>Loading...</p>");
            $.ajax({
                url: "index.php?route=product/ajax/getDetails",
                data: {
                    'product_id': product_id,
                    'loadProduct': product_id
                },
                dataType: 'json',
                success: function (json) {

                    if (json.status != true) {
                        $(".product-page").html(json);
                    } else {
                        $(".product_descr").html(json.description);
                        $(".bot_content").html(json.shape_size);
                        $(".product-name").html(json.name);
                        $(".sku-name").html(json.heading_sku);
                        $(".left-product").html(json.review);
                        $('#show-review').load('index.php?route=product/product/getreview&product_id=' + product_id);
                        $('.over-review').load('index.php?route=product/product/gettotalreview&product_id=' + product_id);
                        $('#show-review_mobile').load('index.php?route=product/product/getreview&product_id=' + product_id);
                        $('.over-review_mobile').load('index.php?route=product/product/gettotalreview&product_id=' + product_id);
                    }
                    Product.ChangeQuantity();
                }
            });

        },
        Product.ChangeQuantity = function () {

            //      $(".quantity select").each(function() {
            //          $(this).multiselect({
            //              maxHeight: 200
            //          })
            //      }).on("change", function() {
            //          Product.updatePrice(), Product.addData($(this)
            // .attr("name"), $(this)
            // .val())
            //      })
            $(".quantity input").each(function () {
                // $(this).multiselect({
                //     maxHeight: 200
                // })
                //$(this).val('0')
                $(this).attr('data-val',0);
            }).on("click", function () {
                var qun = parseInt($(this).attr('data-val'))+1;
                $(this).attr('data-val',qun);
                Product.updatePrice();
                if (typeof Product.colorID != 'undefined') {
                    Product.prepCartData($(this).attr("name"))
                }
                /*Product.addData($(this)
                        .attr("name"), $(this)
                        .attr('data-val'))*/
            
            })

        },
        $(document).on("touchstart", ".colors .color", function () {
    Product.getDetails($(this).data("value")),
            Product.colorID = $(this).data("product-option-value-id"),
            Product.colorOptionID = $(this).data("product-option-id");
    $(this).siblings().removeClass("active");
    $(this).addClass("active");

}),
        $(document).on("click", ".colors .color", function () {
    Product.getDetails($(this).data("value")),
            Product.colorID = $(this).data("product-option-value-id"),
            Product.colorOptionID = $(this).data("product-option-id");
    $(this).siblings().removeClass("active");
    $(this).addClass("active");

}),
$(document).on("click", "#button-cart", function () {

    if (typeof Product.colorID != 'undefined') {
        Product.prepCartData()
    } else {
        alert('Please choose color to order');
    }

});
Product.ChangeQuantity();