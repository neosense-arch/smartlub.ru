$(window).load(function () {
    $('.flexslider').flexslider({
        animation: "slide",
        touch: false,
        slideshow: true
    });

    $('.add-to-cart').click(function(){
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
});

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
