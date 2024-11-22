var table_export;
var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);
var rate;
$(function() {
    // datatables

    rate = $('#rating').selectize();
    if(rate.length > 0){
        rate = rate[0].selectize;
    }


    $('#rating').change(function(){
        var rating = $(this).val();
        if(rating >= 5){
            $('#rating_detail_div').show();
            $("#closing").prop('required',true).parsley();
            $("#revenue").prop('required',true).parsley();
            $("#revenue").val('50');
            $("#closing").focusin();
            $("#closing").focusout();
        }else {
            $('#rating_detail_div').hide();
            $("#closing").removeAttr('required').parsley().destroy();
            $("#revenue").removeAttr('required').parsley().destroy();
            $("#revenue").val('10');

        }
    });




   //alert($('.single').length);
    $(".single").eq(3).css('z-index',9999);
	$('textarea[name="rating_point"]').css('overflow-y','hidden')

    
    $('#form_validation2').on('submit',function(e){
        $('.preloadr-div').removeAttr('style');
        $('.preloadr-div').attr('display','block');
		e.preventDefault();
		var form = $('#form_validation2')[0];

		var data = new FormData(form);
		$.ajax({
			type 	: "POST",
			enctype: 'multipart/form-data',
			url  	: base_url+"submit_lead",
			cache: false,
			contentType: false,
			processData: false,
            async:false,
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
                    //rate.setValue('');
                    $('#comments').val('');
                    var modal = UIkit.modal("#detial");
                    modal.hide();
                    setTimeout(function(){
                        window.location = window.location;
                    },1000);


				}else if(data.code == 205){
                    $('#err-msg').attr('data-message',data.message);
                    $('#err-msg').click();
                    $('#form_validation3').parsley().reset();
                    var modal = UIkit.modal("#detial");
                    modal.hide();


                }else{
					$('#err-msg').attr('data-message',' if else Something is going wrong!');
					$('#err-msg').click();
				}
				
			},
			error 	: function(){

				$('#err-msg').attr('data-message','error Something is going wrong!');
				$('#err-msg').click();
			}
		});
		
	});

	



	$(document).on('click','.play-video',function(){

		var title = $(this).data('title');
		var url = $(this).data('url');
        var html = '<iframe  src = "'+url+'"  frameborder="0" allowfullscreen style="height: 60vh;width:100%;"></iframe>'
        $('#vt').text(title);
        $('#play').html(html);
        var modal = UIkit.modal("#play_model");
        modal.show();

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
            }
        }

    });

});

