(function ($) {
    // Price slider initialization
    var elSlider = $("#slider-price");
    elSlider.find(".slider").slider({
        range: true,
        min: +elSlider.attr('data-from'),
        max: +elSlider.attr('data-to'),
        step: +elSlider.attr('data-step'),
        values: [$("input.ns-price-from").val(), $("input.ns-price-to").val()],
        slide: function (event, ui) {
            elSlider.find(".left input").val(ui.values[ 0 ]);
            elSlider.find(".right input").val(ui.values[ 1 ]);
        }
    });
    $('.ui-slider-handle').last().addClass('last');
    elSlider.find(".left input").val(elSlider.find(".slider").slider("values", 0));
    elSlider.find(".right input").val(elSlider.find(".slider").slider("values", 1));

    // Init flexslider
    $('.flexslider').flexslider({
        animation: "slide",
        touch: false,
        slideshow: true
    });

    // Init add to cart
    $('.add-to-cart').live('click', function(){
        $.ajax($(this).attr('data-url'), {
            type: 'POST',
            data: {
                itemId: $(this).attr('data-id'),
                quantity: 1
            },
            success: function(res){
                if (res.error) {
                    throw res.error;
                }
                $.ajax($('.basketTop').attr('data-url'), {
                    type: 'GET',
                    success: function(res){
                        $('.basketTop').replaceWith(res);
                    }
                });
                $('#cartAdded').modal('show');
            }
        });
        return false;
    });

    // Init more items button
    $('.ns-items-more').click(function(){
        var elContainer = $('.ns-item-more-container');
        var elLoader = $('.ns-item-more-loader');
        elContainer.css({display:'none'});
        elLoader.css({display:'block'});

        $.ajax(elContainer.attr('data-url'), {
            type: 'GET',
            data: {
                skip: $('.blockProducts li').length,
                limit: elContainer.attr('data-limit')
            },
            success: function(res) {
                $('.blockProducts ul').append(res);
                elLoader.css({display:'none'});
                if (elContainer.attr('data-total') > $('.blockProducts li').length) {
                    elContainer.css({display:'block'});
                }
            }
        });

        return false;
    });

    // Cart item count
    var fnHandler = function(e){
        if (e.which < 48 || e.which > 57) {
            return;
        }

        var itemPrice = $(this).attr('data-price') * $(this).val();
        $(this).parents('.itemBasket').find('.price-last').text(itemPrice.toFixed(2).replace('.', ',') + ' руб.');

        $.ajax($(this).parents('.ns-cart-form').attr('data-url'), {
            'type': 'POST',
            'data': $('.ns-cart-form').serialize(),
            'success': function(res){
                $('.boxSumm p').html('<span>Итого:</span> ' + res.totalPrice.toFixed(2).replace('.', ',') + ' руб.');
            }
        });
    };

    $('.ns-item-count').each(function(){
        $(this).number(true, '0', '.', '');
        $(this).keyup(fnHandler);
    });

    // Cart item delete
    $('.ns-cart-delete').click(function(){
        if (!confirm('Вы уверены?')) {
            return false;
        }

        $.ajax($(this).parents('.ns-cart-form').attr('data-delete-url'), {
            type: 'POST',
            data: {
                itemId: +$(this).attr('data-id')
            },
            success: function(res) {
                $('.boxSumm p').html('<span>Итого:</span> ' + res.totalPrice.toFixed(2).replace('.', ',') + ' руб.');
            }
        });

        $(this).parents('.itemBasket').remove();

        return false;
    });

})(jQuery);

$(function(){

	$('.iconTime a').click(function(){$('.timeOpen').fadeToggle(); return false;});
	$(document).click(function(e) {
		if ($(e.target).parents().filter('.timeOpen:visible').length != 1)
		$('.timeOpen').hide();
	});
	
	$('.styled').styler();
	
	var s=1000;
	    $("select.styled").each(function(){
	        s--;
				$(this).css({'z-index': s}); 	
				
	    });	
	var f=1000;
	    $(".itemBasket").each(function(){
	        f--;
				$(this).css({'z-index': f}); 	
				
	    });	
		
	$('.listImgSmall1 li a').click(function(){
            srcBig=$(this).attr('data-big');
            $(this).parents('.imgCard').find('.imgBig img').attr('src',srcBig);			
			$(this).parents('.imgCard').find('.listImgSmall1 li.active').removeClass('active');
			$(this).parent().addClass('active');			
            return false;
        });	
	$('.listImgSmall2 li a').click(function(){
            srcBig=$(this).attr('data-big');
            $(this).parents('.imgCard').find('.imgBig img').attr('src',srcBig);			
			$(this).parents('.imgCard').find('.listImgSmall2 li.active').removeClass('active');
			$(this).parent().addClass('active');			
            return false;
        });	
	
	
		jQuery("#list_img_1").jcarousel({
			wrap: 'circular',
			scroll: 1
		});

		jQuery("#list_img_2").jcarousel({
			vertical: true,
			wrap: 'circular',
			scroll: 1
		});
		
		$('.nav-left-toggle').click(function(){$('.leftNav').slideToggle(); return false;});
		$('.navbar-toggle').click(function(){$('.navbar-open').slideToggle(); return false;});
	    

});


function initialize() {
    var myLatlng = new google.maps.LatLng(55.747304, 37.96019);
    var myOptions = {
        zoom: 14,
        scrollwheel: false,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControlOptions: {
            position: google.maps.ControlPosition.BOTTOM_LEFT
        }
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    var contentString = '<div id="contentMap">' +
        '<div class="telMap">+7 (495) <span>744 6450</span></div>' +
        '<div class="email">email@domin.com</div>' +
        '<p>Московская область,<br> г. Железнодорожный, ул. Речная, влад. 2.</p>' +

        '</div>';
    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });


    var markerImage = new google.maps.MarkerImage(
        'img/descriptionMap.png',
        new google.maps.Size(37, 19)
    );

    var marker = new google.maps.Marker({
        icon: markerImage,
        position: myLatlng,
        map: map,
        title: "Ювелирная дизайн-студия The Just Diamond"
    });
    google.maps.event.addListener(marker, 'click', function () {
        infowindow.open(map, marker);
    });
    google.maps.event.trigger(marker, "click");
}
