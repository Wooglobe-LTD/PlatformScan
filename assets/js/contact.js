jQuery(document).ready(function($) {

    "use strict";
    $('#contact-form').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]'
    });

    $('#contact-form').on('submit',function (e) {

        e.preventDefault();
        $.ajax({
            type 	: "POST",
            url  	: base_url+"contact_lead",
            data    : $('#contact-form').serialize(),
            success : function(data){
                data = JSON.parse(data);
                if(data.code == 204){
                    toastr.error(data.message);
                    setTimeout(function(){
                        window.location = data.url;
                    },2000);
                }else if(data.code == 201){

                    toastr.error(data.message);
                    $('#contact-form').parsley().reset();
                    $.each(data.error,function(i,v){
                        $('#'+i+'_err').html(v);
                    });

                }else if(data.code == 200){
                    toastr.success(data.message);
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else{
                    toastr.error(data.message);
                }

            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });

    });
    /*----------- Google Map - with support of gmaps.js ----------------*/
    function isMobile() {
        return ('ontouchstart' in document.documentElement);
    }

    function init_gmap() {
        if ( typeof google == 'undefined' ) return;
        var options = {
            center: {lat: 51.515991, lng: -0.123438},
            zoom: 15,
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            navigationControl: true,
            scrollwheel: false,
            streetViewControl: true,
            styles: [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#cdcdcd"},{"visibility":"on"}]}]
        }

        if (isMobile()) {
            options.draggable = false;
        }

        $('#googleMaps').gmap3({
            map: {
                options: options
            },
            marker: {
                latLng: [51.515991, -0.123438],
                // options: { icon: 'images/map-icon.png' }
            }
        });
    }

    init_gmap();



});