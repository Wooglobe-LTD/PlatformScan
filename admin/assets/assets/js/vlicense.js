var table_export;
var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);
var rate;
$(function() {
    // datatables
	altair_datatables.dt_tableExport();

    rate = $('#rating').selectize();
    if(rate.length > 0){
        rate = rate[0].selectize;
    }





    $('#add').on('click',function(){

        $('#form_validation2').parsley().reset();
		$('#form_validation2')[0].reset();
		var modal = UIkit.modal("#add_model");
    	modal.show();

    });

   //alert($('.single').length);
    $(".single").eq(1).css('z-index',9999);
	$('textarea[name="rating_point"]').css('overflow-y','hidden')

    
    $('#form_validation3').on('submit',function(e){
		
		e.preventDefault();
		var form = $('#form_validation3')[0];

		var data = new FormData(form);
		$.ajax({
			type 	: "POST",
			enctype: 'multipart/form-data',
			url  	: base_url+"rate_lead",
			cache: false,
			contentType: false,
			processData: false,
			data    : data,
			success : function(data){
				data = JSON.parse(data);
				if(data.code == 204){
					$('#err-msg').attr('data-message',data.message);
					$('#err-msg').click();
					setTimeout(function(){
						window.location = data.url;
					},1000);
				}else if(data.code == 201){
					$('#err-msg').attr('data-message',data.message);
					$('#err-msg').click();
					$('#form_validation3').parsley().reset();
					$.each(data.error,function(i,v){
                        $('[name="'+i+'"]').parent().parent().find('.error').html(v);
                    });
					
				}else if(data.code == 200){
					$('#suc-msg').attr('data-message',data.message);
					$('#suc-msg').click();
                    rate.setValue('');
                    $('#comments').val('');
                    var modal = UIkit.modal("#detial");
                    modal.hide();

				}else{
					$('#err-msg').attr('data-message','Something is going wrong!');
					$('#err-msg').click();
				}
				
			},
			error 	: function(){
				$('#err-msg').attr('data-message','Something is going wrong!');
				$('#err-msg').click();
			}
		});
		
	});





    $(document).on('click','.play-video',function(){

        var id = $(this).data('id');

        $.ajax({
            type 	: 'POST',
            url  	: base_url+'get_video',
            data 	: {id:id},
            success : function(data){
                data = JSON.parse(data);

                if(data.code == 204){
                    $('#err-msg').attr('data-message',data.message);
                    $('#err-msg').click();
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else if(data.code == 201){
                    $('#err-msg').attr('data-message',data.message);
                    $('#err-msg').click();

                }else if(data.code == 200){
                    $('#suc-msg').attr('data-message',data.message);
                    $('#suc-msg').click();
                    html = '';
                    if(data.data.embed == 0){
                        yurl= data.data.url;
                        nwyurl = yurl.indexOf("https://www.youtube.com");
                        if(nwyurl == 0){
                            youtubefull=yurl.split('v=');
                            youtubesecondsplit=youtubefull[1].split('&');
                            if(youtubesecondsplit[1]){
                                youtubefull[1]=youtubesecondsplit[0];
                            }
                            youtubeid=youtubefull[1];

                            html = '<iframe width="400" id="myIframe" src="https://www.youtube.com/embed/'+youtubeid+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                        }
                        nwyvimeo = yurl.indexOf("https://vimeo.com");
                        if(nwyvimeo == 0){
                            vimeofull=yurl.split('vimeo.com/');
                            vimeoid=vimeofull[1];
                            html = '<iframe width="400" id="myIframe" src="https://player.vimeo.com/video/'+vimeoid+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
                        }
                        nwyinsta = yurl.indexOf("https://www.instagram.com");
                        if(nwyinsta == 0){
                            vimeofull="https://api.instagram.com/oembed/?url="+yurl;
                            $.ajax({
                                type: "GET",
                                url: vimeofull,
                                data: {},
                                success: function (data) {
                                    //data = JSON.parse(data);
                                    html = data.html;
                                    $('#play').html(html);
                                }
                            });
                        }
                        $(".uk-modal-close").click(function(){
                            $("iframe#myIframe").remove();
                            $("iframe.instagram-media").remove();

                        });
                        $("body").click(function(){
                            $("iframe#myIframe").remove();
                            $("iframe.instagram-media").remove();
                        });
                    }else{
                        html = data.data.url;
                    }
                    $('#vt').text(data.data.title);
                    $('#play').html(html);
                    var modal = UIkit.modal("#play_model");
                    modal.show();

                }else{
                    $('#err-msg').attr('data-message','Something is going wrong!');
                    $('#err-msg').click();
                }


            }
        });




    });


    $(document).on('click','.license_detail',function(){

        var id = $(this).data('id');

        $.ajax({
            type 	: 'POST',
            url  	: base_url+'get_video_license',
            data 	: {id:id},
            success : function(data){
                data = JSON.parse(data);

                if(data.code == 204){
                    $('#err-msg').attr('data-message',data.message);
                    $('#err-msg').click();
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else if(data.code == 201){
                    $('#err-msg').attr('data-message',data.message);
                    $('#err-msg').click();

                }else if(data.code == 200){
                    $('#suc-msg').attr('data-message',data.message);
                    $('#suc-msg').click();
                   /* $('#rating_body').hide();
                    if(data.data.rating_point > 0 || data.data.rating_comments.length > 0 ){
                        $('#rating_body').show();
                    }*/

                    $.each(data.data,function(i,v){

                    	if(i == 'video_id'){
                            $('#'+i).attr('data-id',v);
                            //$('#'+i).text(v);
                        }else if(i == 'id'){
                            $('#'+i).val(v);
                        }else {
                            $('#'+i).html(v);
                        }
                        if(i == 'mobile'){
                            $('#'+i).html('+'+data.data.country_code+v);
                        }



					});

                    $('#country-div1').hide();
                    if(data.data.territory == 'National'){
                        $('#country-div1').show();
                    }
                    var modal = UIkit.modal("#detial");
                    modal.show();

                }else{
                    $('#err-msg').attr('data-message','Something is going wrong!');
                    $('#err-msg').click();
                }


            }
        });




    });


});

altair_datatables = {
    
    dt_tableExport: function() {
        var $dt_tableExport = $('#dt_tableExport'),
            $dt_buttons = $dt_tableExport.prev('.dt_colVis_buttons');

        if($dt_tableExport.length) {
            table_export = $dt_tableExport.DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": base_url+'get_video_licenses',
                "ordering": false
            });


        }
    }
};