var video_single_url = '';
var video_url = '';
(function($){

	"use strict";

	var VideoStories = {

      // Bootstrap Carousels

      carousel: function() {

      	$('.carousel.slide').carousel({
      		cycle: true
      	}); 
      }, 

      matchHeight: function() {
      	$('article.post.type-post, .widget_instagram_feed img').matchHeight({ 
      		property: 'min-height' 
      	});

      },

      magnific: function() {
      	$('.iframe').magnificPopup({
      		type: 'iframe',
      		// gallery: {
      		// 	enabled: true
      		// },
      	});

      	$('.image-popup').magnificPopup({
      		type: 'image',
      		gallery: {
      			enabled: true
      		},
      	});

      },

		// Owl Carousels 

		owlcarousel: function() {
			try { 
				(function($) {

					$(".video-slider").owlCarousel({
						items:3,
						loop:true,
						margin:30,
						nav: false,
						autoplay: true,
						responsive:{
							320:{
								items:1,
								margin: 0,
							},
							480:{
								items:2,
								margin: 0,
							},
							640:{
								items:2,
								margin: 15,
							},
							768:{
								items:3,
								margin: 15,
							}
						}
					});

					$(".trending-slider, .category-slider-01, .related-videos-slider").owlCarousel({
						items:3,
						loop:true,
						margin:25,
						nav: false,
						autoplay: true,
						responsive:{
							320:{
								items:1,
								margin: 0,
							},
							480:{
								items:1,
								margin: 0,
							},
							640:{
								items:2,
								margin: 0,
							},
							768:{
								items:3,
								margin: 15,
							}
						}
					});

					$(".music-video-slider").owlCarousel({
						items:2,
						loop:true,
						margin:25,
						nav: false,
						autoplay: true,
						responsive:{
							320:{
								items:1,
								margin: 0,
							},
							480:{
								items:1,
								margin: 0,
							},
							640:{
								items:2,
								margin: 15,
							}
						}
					});

					$(".weekly-top, .play-list-3 .list-slider").owlCarousel({
						items:1,
						loop:true,
						margin:0,
						nav: false,
						autoplay: true
					});

					$(".tweet-slider").owlCarousel({
						items:1,
						loop:true,
						margin:0,
						nav: false,
						autoplay: true,
						startPosition: 0,
						animateOut: 'slideOutUp',
						animateIn: 'slideInUp'
					});

					$(".title-slider").owlCarousel({
						items:2,
						loop:true,
						margin:0,
						nav: false,
						autoplay: true,
						responsive:{
							320:{
								items:1,
								margin: 0,
							},
							640:{
								items:1,
								margin: 0,
							},
							768:{
								items:2,
								margin: 15,
							}
						}
					});

					$(".list-slider").owlCarousel({
						items:4,
						loop:true,
						margin:30,
						nav: false,
						autoplay: true,
						responsive:{
							320:{
								items:1,
								margin: 0,
							},
							480:{
								items:2,
								margin: 15,
							},
							636:{
								items:2,
								margin: 15,
							},
							768:{
								items:3,
								margin: 15,
							},
							1024:{
								items:4,
								margin: 20,
							}
						}
					});

					$(".latest-videos-slider, .viral-videos-slider").owlCarousel({
						items:2,
						loop:true,
						margin:30,
						nav: false,
						responsive:{
							320:{
								items:1,
								margin: 0,
							},
							480:{
								items:2,
								margin: 15,
							},
							640:{
								items:2,
								margin: 15,
							},
							768:{
								items:2,
								margin: 30,
							}
						}
					});

					$(".latest-videos-slider-2, .viral-videos-slider-2, .exclusive-videos-slider, .upload-videos-slider").owlCarousel({
						items:3,
						loop:true,
						margin:25,
						nav: false,
						responsive:{
							320:{
								items:1,
								margin: 0,
							},
							480:{
								items:2,
								margin: 15,
							},
							636:{
								items:2,
								margin: 15,
							},
							768:{
								items:3,
								margin: 20,
							}
						}
					});

					$(".most-liked").owlCarousel({
						items:3,
						loop:true,
						margin:25,
						nav: false,
						responsive:{
							320:{
								items:1,
								margin: 0,
							},
							480:{
								items:2,
								margin: 15,
							},
							768:{
								items:2,
								margin: 20,
							},
							1024:{
								items:3,
								margin: 20,
							}
						}
					});

					$('.banner-slider-01').owlCarousel({
						center: true,
						items:2,
						autoplay: true,
						loop:true,
						margin:90,
						responsive:{
							320:{
								items:1,
								margin: 0,
							},
							480:{
								items:1,
								margin: 0,
							},
							636:{
								items:1,
								margin: 0,
							},
							768:{
								items:2,
								margin: 15,
							},
							1024:{
								items:2,
								margin: 30,
							},
							1200:{
								items:2,
								margin: 90,
							}
						}
					});

					$('.bottom-slider').owlCarousel({
						items:3,
						autoplay: true,
						loop:true,
						margin:40,
						responsive:{
							767:{
								items:1,
								margin: 0,
							},
							768:{
								items:2,
								margin: 20,
							},
							1024:{
								items:3,
								margin: 30,
							},
							1200:{
								items:3,
								margin: 40,
							}
						}
					});

					$('.banner-slider-02 .banner-slider').owlCarousel({
						items:1,
						autoplay: true,
						loop:true
					});

					$('.most-viewed').owlCarousel({
						items:2,
						autoplay: true,
						loop:true,
						margin:30,
						responsive:{
							320:{
								items:1,
								margin: 0,
							},
							480:{
								items:2,
								margin: 10,
							},
							640:{
								items:2,
								margin: 15,
							},
							768:{
								items:2,
								margin: 20,
							},
							1024:{
								items:2,
								margin: 30,
							}
						}
					});

					$('.recent-movie-slider').owlCarousel({
						items:5,
						autoplay: true,
						loop:true,
						margin:10,
						responsive:{
							320:{
								items:2
							},
							480:{
								items:2
							},
							640:{
								items:3
							},
							768:{
								items:4
							},
							1024:{
								items:5
							}
						}
					});

				})(jQuery);
			} catch(e) { 
				
			} 
		},

		// Facebook Profile Badge Script

		facebook_feed: function() {
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));

		},


	};
    var url= $(location).attr('href');
    var slug = url.split('=');
    if( $( ".movie-contents-area").length > 0){
    $( ".movie-contents-area" ).load(
        $.ajax({
            type: "POST",
            url: base_url + "partner/sidebar",
            data: {id: slug[1]},
            success: function(result){
                $(".loader").delay(1000).fadeOut("slow");
                $("#overlayer").delay(1000).fadeOut("slow");
                var theArray = JSON.parse(result);
                theArray.forEach(function (obj) {
                    var title= obj.title;
                    title = title.replace(new RegExp("\\\\", "g"), "");
                    title = title.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });
                    if(title.length <= 20){
                        var subtitle=  title
                    }else{
                        subtitle = title.substring(0, 20);
                    }
                    // var thumb = obj.thumbnail;
                    var thumb = 'https://img.youtube.com/vi/'+obj.youtube_id+'/hqdefault.jpg';
                    var relatedhtml= '<div class="row">' +
                        '<div class="col-md-12">'+
                        '<a href="'+base_url +'?video='+obj.slug+'" title="'+title+'">'+
                        '<div class="entry-thumbnail" style="margin-bottom: 0px !important;">'+
                        '<img src="'+ thumb +'" alt="Video Thumbnail">'+
                        '</div>'+
                        '<span class="rel-title">'+subtitle+'</span>'+
                        '</div></div>'
                    $(".relate-vid").append(relatedhtml);
                });
            }
        })
    )
    }
    $( function() {
        $( "#accordion" ).accordion({
		});
    } );
    $( function() {
        $( ".accordion" ).accordion({
        });
    } );

	$(document).ready(function() {
        function custom_preloader_show  (){
            $('.preloadr-div').show();
        }
        function custom_preloader_hide(){
            $('.preloadr-div').hide();
        }
        $( document ).ajaxStart(function(){
           custom_preloader_show();
        });

        $( document ).ajaxComplete(function() {
            custom_preloader_hide();
        });


        // Background Img
        $('#video-upload-form').parsley({
            'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]'
        });

        $(document).on('click','.upload' ,function () {
            $('#video-upload-form').parsley().reset();
            $('.error').html('');
            $('#video-upload-form')[0].reset();
            $( "#theImg" ).remove();
            $('#myModal').modal('show');


        });
        $(document).on('click','.block_request' ,function () {
            $('#myModal').modal('hide');
            $('#blockModal').modal('show');

        });

        $('#video_single_url').on('focusout',function () {
          //  $('#img')[0].reset();
            var url = $('#video_single_url').val();

            if(video_single_url != url){
                video_single_url = url;
                if(url.length == 0){
                    $('#img').hide();
                    $('#img').html('');

                }
                var urlValue = url.split(".");
                if(urlValue[1] == "youtube" || urlValue[0] == "youtube" || urlValue[1] == "youtu" || urlValue[0] == "youtu" || urlValue[0] == "https://youtube" || urlValue[1] == "youtu" || urlValue[0] == "https://youtu"  || urlValue[0] == "http://youtube" || urlValue[1] == "youtu" || urlValue[0] == "http://youtu") {
                    var reg = new RegExp('^(?:http|https):\/\/|(?:www\.)?(?:youtube.com|youtu.be)\/(?:watch\?(?=.*v=([\w\-]+))(?:\S+)?|([\w\-]+))$');

                    if (reg.test(url)){
                        $.ajax({
                            url: "https://api.linkpreview.net?key=5aa77e0423fdda5eb02f2e24db13fe5e4826cfd515b84&q="+url,
                            type: "GET",
                            contentType: "application/json",
                            success: function(result){
                                var src = result.image;
                                $('#video_title').val(result.title);
                                //$('#message').val(result.description);
                                $('#img').show();
                                $("#img").html('');
                                $("#img").html("<img id='theImg' src="+src+"></>");
                                $("#img").append('<input class="iframe-social" type="hidden" name="img_url" value='+src+'>');
                            }
                        });
                    }
                }else if(urlValue[1] == "instagram" || urlValue[0] == "https://twitter" ){
                        $.ajax({
                            type: "POST",
                            url: base_url + "admin/instagram_url",
                            data: {id: url},
                            success: function (data) {
                            	//data=JSON.parse(data);
                                custom_preloader_hide();
                                if (data.code == 201) {
                                    toastr.error(data.error);
                                    $.each(data.error, function (i, v) {
                                        $('#' + i + '_err').html(v);
                                    });
                                }
                                if (data.code == 200) {
                                   var result=data.data;
                                    var src = result.image[0].url;
                                    var title = result.title.split('Instagram:');
                                    var titlerem = title[1].replace("“","").replace("”","");
                                    var shorttitle=titlerem.substr(0, 70)+ "...";

                                    $('#video_title').val(shorttitle);
                                    $('#img').show();
                                    $("#img").html('');
                                    $("#img").html("<img id='theImg' src="+src+"></>");
                                }
                                if (data.code == 204) {
                                    toastr.error(data.message);
                                }
                                if (data.code == 205) {
                                    $("#myModal").modal('hide');
                                    toastr.success(data.message);

                                }
                            }
                        });
				}
			}

        });
        $('#video_url').on('focusout',function () {
            //  $('#img')[0].reset();
            var url = $('#video_url').val();

            if(video_url != url){
                video_url = url;
                if(url.length == 0){
                    $('#img').hide();
                    $('#img').html('');

                }
                var urlValue = url.split(".");
                if(urlValue[1] == "youtube" || urlValue[0] == "youtube" || urlValue[1] == "youtu" || urlValue[0] == "youtu" || urlValue[0] == "https://youtube" || urlValue[1] == "youtu" || urlValue[0] == "https://youtu"  || urlValue[0] == "http://youtube" || urlValue[1] == "youtu" || urlValue[0] == "http://youtu") {
                    var reg = new RegExp('^(?:http|https):\/\/|(?:www\.)?(?:youtube.com|youtu.be)\/(?:watch\?(?=.*v=([\w\-]+))(?:\S+)?|([\w\-]+))$');

                    if (reg.test(url)){
                        $.ajax({
                            url: "https://api.linkpreview.net?key=5aa77e0423fdda5eb02f2e24db13fe5e4826cfd515b84&q="+url,
                            type: "GET",
                            contentType: "application/json",
                            success: function(result){
                                var src = result.image;
                                $('#video_title').val(result.title);
                                //$('#message').val(result.description);
                                $('#img').show();
                                $("#img").html('');
                                $("#img").html("<img id='theImg' src="+src+"></>");
                            }
                        });
                    }
                }else if(urlValue[1] == "instagram" || urlValue[0] == "https://twitter" ){
                    $.ajax({
                        type: "POST",
                        url: base_url + "admin/instagram_url",
                        data: {id: url},
                        success: function (data) {
                            //data=JSON.parse(data);
                            custom_preloader_hide();
                            if (data.code == 201) {
                                toastr.error(data.error);
                                $.each(data.error, function (i, v) {
                                    $('#' + i + '_err').html(v);
                                });
                            }
                            if (data.code == 200) {
                                var result=data.data;
                                var src = result.image[0].url;
                                var title = result.title.split('Instagram:');
                                var titlerem = title[1].replace("“","").replace("”","");
                                var shorttitle=titlerem.substr(0, 70)+ "...";

                                $('#video_title').val(shorttitle);
                                $('#img').show();
                                $("#img").html('');
                                $("#img").html("<img id='theImg' src="+src+"></>");
                            }
                            if (data.code == 204) {
                                toastr.error(data.message);
                            }
                            if (data.code == 205) {
                                $("#myModal").modal('hide');
                                toastr.success(data.message);

                            }
                        }
                    });
                }
            }

        });
        $('#video-upload-form').on('submit',function (e) {
            e.preventDefault();
            custom_preloader_show();
            $('.error').html('');
            setTimeout(function(){
                $.ajax({
                    type: "POST",
                    url: base_url + "video-upload",
                    data: $('#video-upload-form').serialize(),
                    beforeSend: function(){
                        custom_preloader_show();
                    },
                    success: function (data) {
                        custom_preloader_hide();
                        data = JSON.parse(data);
                        console.log(data)
                        if (data.code == 201) {
                            toastr.error(data.error);
                            $.each(data.error, function (i, v) {
                                $('#' + i + '_err').html(v);
                            });
                        }
                        if (data.code == 200) {
                            $("#myModal").modal('hide');
                            toastr.success(data.message);
                        }
                        if (data.code == 204) {
                            toastr.error(data.message);
                        }
                        if (data.code == 205) {
                            $("#myModal").modal('hide');
                            toastr.success(data.message);

                        }
                    }
                });
			},200);


        });
        $('#user-block-request-form').on('submit',function (e) {
            e.preventDefault();
            custom_preloader_show();
            setTimeout(function(){
                $.ajax({
                    type: "POST",
                    url: base_url + "user_block_request",
                    data: $('#user-block-request-form').serialize(),
                    beforeSend: function(){
                        custom_preloader_show();
                    },
                    success: function (data) {
                        custom_preloader_hide();
                        //data = JSON.parse(data);
                        if (data.code == 201) {
                            toastr.error(data.error);
                            $.each(data.error, function (i, v) {
                                $('#' + i + '_err').html(v);
                            });
                        }
                        if (data.code == 200) {
                            $("#blockModal").modal('hide');
                            toastr.success(data.message);
                        }
                        if (data.code == 204) {
                            toastr.error(data.message);
                        }
                        if (data.code == 205) {
                            $("#blockModal").modal('hide');
                            toastr.success(data.message);

                        }
                    }
                });
            },20);


        });



		$(".background-bg").css('background-image', function () {
			var bg = ('url(' + $(this).data("image-src") + ')');
			return bg;
		});


		$('.section-title, aside .widget-title').each(function() {
			var word = $(this).html();
			var index = word.indexOf(' ');
			if(index == -1) {
				index = word.length;
			}
			$(this).html('<span class="first-word">' + word.substring(0, index) + '</span>' + word.substring(index, word.length));
		});

		$('.style-grid').on("click", function() {
			$(".style-grid").addClass("active");
			$(".style-list").removeClass("active");
			$(".play-list-4").addClass("grid-layout");
			$(".play-list-4 article").addClass("col-sm-6");
			$(".author-videos article").addClass("col-sm-4");
			$(".author-videos").removeClass("list-style");
		});

		$('.style-list').on("click", function() {
			$(".style-list").addClass("active");
			$(".style-grid").removeClass("active");
			$(".play-list-4").removeClass("grid-layout");
			$(".play-list-4 article").removeClass("col-sm-6");
			$(".author-videos article").removeClass("col-sm-4");
			$(".author-videos").addClass("list-style");
		});




		VideoStories.carousel();
		VideoStories.matchHeight();
		VideoStories.magnific();
		VideoStories.owlcarousel();
		VideoStories.facebook_feed();
	});

	if ($(window).width() < 767) {
		"use strict";


		$('.bottom-slider').owlCarousel({
			items:1,
			autoplay: true,
			loop:true,
			margin:0
		});

	};
    if ($(window).width() > 767) {
        "use strict";
        $('div.content div.banner').prepend('<video poster="https://www.wooglobe.com/images/back-poster.png" class="vid_set masthead-video" playsinline="" autoplay="" muted="" loop="">\n' +
            '<source src="https://www.wooglobe.com/images/bg-mgm-web.webm" type="video/webm">\n' +
            '<source src="https://www.wooglobe.com/images/bg-mgm.mp4" type="video/mp4">\n' +
            '<img src="https://www.wooglobe.com/images/woog_gif2.gif" alt="">\n' +
            '</video>');
        $('div.content div.banner img#header-image').remove();



    };

	// Responsive Menu Open and Close in Mobile

	if ($(window).width() < 767) {
		"use strict";
		$('.menu-item-has-children>a').on('click', function(event) {
			event.preventDefault(); 
			event.stopPropagation(); 
			$(this).parent().siblings().removeClass('open');
			$(this).parent().toggleClass('open');
		});
		
	};



	jQuery(window).on('scroll', function () {
		
		'use strict';

		if (jQuery(this).scrollTop() > 100) {
			jQuery('#scroll-to-top').fadeIn('slow');
		} else {
			jQuery('#scroll-to-top').fadeOut('slow');
		}

	});


	jQuery('#scroll-to-top').on("click", function() {
		
		'use strict';

		jQuery("html,body").animate({ scrollTop: 0 }, 1500);
		return false;
	});


    /*$( "#search_input" ).autocomplete({
        source: tags
    });*/
    $('#datatable').DataTable();
    $('.tooltip').tooltipster();

    $(document).on('click','#button_newsletter',function () {
		var email = $('#mail_newsletter').val();
		$('#err_newsletter').html('');
		if(email.length > 0){
           var check =  isEmail(email);
           if(check){
               $.ajax({
                   url: base_url+'news_letter',
                   type: "POST",
                   data: {email:email},
                   success: function(data){
						data = JSON.parse(data);
                       if(data.result.hasOwnProperty('id')){
                           $('#mail_newsletter').val('');
                           toastr.success('You have subcribed successfully for our newsletters.');
                       }else if(data.result.hasOwnProperty('title')){
                           $('#mail_newsletter').val('');
                           toastr.success('You have already subcribed for our newsletters.');
					   }else{
                           toastr.error('Something goes wrong.');
					   }


                   }
               });
		   }else {
               $('#err_newsletter').html('Invalid email address.');
		   }
		}else{
            $('#err_newsletter').html('Email is required.');
		}
    });
    
    $('#buy-video-submit').on('click',function () {
		
		//$("#license-form").parsley().destroy();
		$('#license-form').submit();
    	var media_type = $('.media-type:checked').length;
    	$('.error').html('');
    	if(media_type > 0){
            
		}else {
			$('#media_type_err').html('At least one media type reqired.');
		}
		//

    });
    $('#license-form').on('submit',function (e) {
        e.preventDefault();
		
        //custom_preloader_show();
		var validate = $('#license-form').parsley().isValid();
        $('.error').html('');
		if(validate === true){
			setTimeout(function(){
            $.ajax({
                type: "POST",
                url: base_url + "license-video",
                data: $('#license-form').serialize(),
                beforeSend: function(){
                    //custom_preloader_show();
					
                },
                success: function (data) {
                    //custom_preloader_hide();
                    data = JSON.parse(data);
                    if (data.code == 201) {
						 //$("#myModal-buy").modal('hide');
                        $.each(data.error, function (i, v) {
                            $('#' + i + '_err').html(v);
                        });
                    }
                    if (data.code == 200) {
                        $("#myModal-buy").modal('hide');
                        toastr.success(data.message);
                    }
                    if (data.code == 204) {
                        toastr.error(data.message);
                    }
                    if (data.code == 205) {
                        $("#myModal-buy").modal('hide');
                        toastr.success(data.message);

                    }
                }
            });
        },200);
		}
        


    });
	$('#license-form').parsley({
		'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]'
	});

	$(document).on('change','#earning-search',function () {
		var slug = $(this).val();
		var url = base_url+'dashboard?page=license&video='+slug;

		window.location = url;
	})
	//const player = new Plyr('#player');
	if (typeof cslug !== "undefined") {
		var catStart = 9;
		var ajaxSend = 0;
		$(window).scroll(function() {
			var footer = $('footer').height();

			if($(window).scrollTop() >= ($(document).height() - ($(window).height()+footer+100))) {
				if(catStart < ctotal && ajaxSend == 0){
					$.ajax({
						type: "POST",
						url : base_url+'load-more-cat-videos/'+cslug,
						data : {start:catStart},
						beforeSend:function(){
							ajaxSend = 1;
						},
						success:function (data) {
							$('#load-more').append(data);
							catStart += 9;
							ajaxSend = 0;
						}
					})
				}

			}
		});
	}

	if (typeof search !== "undefined") {
		var serStart = 15;
		var ajaxSendSer = 0;
		$(window).scroll(function() {
			var footer = $('footer').height();

			if($(window).scrollTop() >= ($(document).height() - ($(window).height()+footer+100))) {
				if(serStart < searchTotal && ajaxSendSer == 0){
					$.ajax({
						type: "GET",
						url : base_url+'load-more-search/',
						data : {start:serStart,search:search},
						beforeSend:function(){
							ajaxSendSer = 1;
						},
						success:function (data) {
							$('#load-more').append(data);
							serStart += 15;
							ajaxSendSer = 0;
						}
					})
				}

			}
		});
	}
})(jQuery);

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}










