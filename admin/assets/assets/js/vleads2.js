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


    $('#rating').change(function(){
        var rating = $(this).val();
        if(rating >= 5){
            $('#rating_detail_div').show();
            $("#closing").prop('required',true).parsley();
            $("#revenue").prop('required',true).parsley();
            $("#revenue").val('60');
            $("#closing").focusin();
            $("#closing").focusout();
        }else {
            $('#rating_detail_div').hide();
            $("#closing").removeAttr('required').parsley().destroy();
            $("#revenue").removeAttr('required').parsley().destroy();
            $("#revenue").val('10');

        }
    });


    $('#add').on('click',function(){

        $('#form_validation2').parsley().reset();
		$('#form_validation2')[0].reset();
		var modal = UIkit.modal("#add_model");
    	modal.show();

    });

   //alert($('.single').length);
    $(".single").eq(1).css('z-index',9999);
    $(".single").eq(2).css('z-index',9999);
    $(".single").eq(3).css('z-index',9999);
    $(".single").eq(4).css('z-index',9999);
    $(".single").eq(5).css('z-index',9999);
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
				//data = JSON.parse(data);
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

///////////////////////////////////Update LEAD Url///////////////////////////////////////
    $('#edit-url').click(function(){
        $('#edit-url').hide();
        $('span.url_edit_area').each(function(){
            var comcontent = $(this).html();
            $(this).html('<textarea id="url-text">' + comcontent + '</textarea>');
        });
        $('#cancel-url').show();
        $('#save-url').show();
    });
    $('#cancel-url').click(function(){
        var comcancel=$('textarea#url-text').val();
        var url_area = $('#url_edit');
        var date_val = url_area.data('val');
        url_area.html(date_val);console.log(date_val);
        $('#edit-url').show();
        $('#cancel-url').hide();
        $('#save-url').hide();
    });
    $('#save-url').click(function(){
        $('#save-url').hide();
        $('textarea#url-text').each(function(){
            var url_comments = $(this).val();//.replace(/\n/g,"<br>");
            var trimStr = $.trim(url_comments);
            var updateurl= $(this).html(url_comments);
            $(this).contents().unwrap();
            var url=(window.location.href).split('/');
            var id = $('#id').val();
            if(trimStr){
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_details',
                    data: {id:id,content:url_comments,check:"url"},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
            }
        });
        $('#cancel-url').hide();
        $('#edit-url').show();

    });
///////////////////////////////////Update LEAD TITLE///////////////////////////////////////
    $('#edit-title').click(function(){
        $('#edit-title').hide();
        $('span.title_edit_area').each(function(){
            var comcontent = $(this).html();
            $(this).html('<textarea id="title-text">' + comcontent + '</textarea>');
        });
        $('#cancel-title').show();
        $('#save-title').show();
    });
    $('#cancel-title').click(function(){
        var comcancel=$('textarea#title-text').val();
        var title_area = $('#title_edit');
        var date_val = title_area.data('val');
        title_area.html(date_val);console.log(date_val);
        $('#edit-title').show();
        $('#cancel-title').hide();
        $('#save-title').hide();
    });
    $('#save-title').click(function(){
        $('#save-title').hide();
        $('textarea#title-text').each(function(){
            var title_comments = $(this).val();//.replace(/\n/g,"<br>");
            var trimStr = $.trim(title_comments);
            var updatetitle= $(this).html(title_comments);
            $(this).contents().unwrap();
            var url=(window.location.href).split('/');
            var id = $('#id').val();
            if(trimStr){
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_details',
                    data: {id:id,content:title_comments,check:"title"},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
            }
        });
        $('#cancel-title').hide();
        $('#edit-title').show();

    });

    ///////////////////////////////////Update LEAD MESSAGE///////////////////////////////////////
    $('#edit-message').click(function(){
        $('#edit-message').hide();
        $('span.message_edit_area').each(function(){
            var comcontent = $(this).html();
            $(this).html('<textarea id="message-text">' + comcontent + '</textarea>');
        });
        $('#cancel-message').show();
        $('#save-message').show();
    });
    $('#cancel-message').click(function(){
        var comcancel=$('textarea#title-text').val();
        var message_area = $('#message_edit');
        var date_val = message_area.data('val');
        message_area.html(date_val);console.log(date_val);
        $('#edit-message').show();
        $('#cancel-message').hide();
        $('#save-message').hide();
    });
    $('#save-message').click(function(){
        $('#save-message').hide();
        $('textarea#message-text').each(function(){
            var message_comments = $(this).val();//.replace(/\n/g,"<br>");
            var trimStr = $.trim(message_comments);
            var updatemessage= $(this).html(message_comments);
            $(this).contents().unwrap();
            var url=(window.location.href).split('/');
            var id = $('#id').val();
            if(trimStr){
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_details',
                    data: {id:id,content:message_comments,check:"message"},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
            }
        });
        $('#cancel-title').hide();
        $('#edit-title').show();

    });




	$(document).on('click','.play-video',function(){

		var title = $(this).data('title');
		var url = $(this).data('url');
		if(url.indexOf('instagram.com/') > -1){
			$.ajax({
				type: "GET",
				url: url,
				data: {},
				success: function (data) {
					//data = JSON.parse(data);
					var html = data.html;
					$('#play').html(html);

				}
			});
		}else if(url.indexOf('twitter.com/') > -1){
		    var twurl=' https://twitframe.com/show?url='+url;
            var html = '<iframe id="myIframe" src = "'+twurl+'"  frameborder="0" allowfullscreen style="height: 60vh;width:100%;"></iframe>';
            $('#play').html(html);
        }else{
			var html = '<iframe id="myIframe" src = "'+url+'"  frameborder="0" allowfullscreen style="height: 60vh;width:100%;"></iframe>';
			$('#play').html(html);
		}
        $('#vt').text(title);
        var modal = UIkit.modal("#play_model");
        modal.show();
        $("body").click(function(){
            $("iframe#myIframe").remove();
            $("iframe.instagram-media").remove();
        });
	});
    $(document).on('click','.delete-lead',function(){

        var id = $(this).data('id');
        UIkit.modal.confirm('Are you sure you want to delete this video?', function(){
            $.ajax({
                type 	: 'POST',
                url  	: base_url+'delete_lead',
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
                        setTimeout(function(){
                            window.location = data.url;
                        },1000);

                    }else{
                        $('#err-msg').attr('data-message','Something is going wrong!');
                        $('#err-msg').click();
                    }


                }
            });

        });


    });
    $(document).on('click','.delete-lead-per',function(){

        var id = $(this).data('id');
        UIkit.modal.confirm('Are you sure you want to delete this video permanently?', function(){
            $.ajax({
                type 	: 'POST',
                url  	: base_url+'delete_lead_per',
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
                        setTimeout(function(){
                            window.location = data.url;
                        },1000);

                    }else{
                        $('#err-msg').attr('data-message','Something is going wrong!');
                        $('#err-msg').click();
                    }


                }
            });

        });


    });
    https://twitframe.com/show?url=

    $(document).on('click','.lead_detail',function(){

        var id = $(this).data('id');

        $.ajax({
            type 	: 'POST',
            url  	: base_url+'get_video_lead',
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
                    $('#rating_body').hide();
                    if(data.data.rating_point > 0 || data.data.rating_comments.length > 0 ){
                        $('#rating_body').show();
                    }
                    $.each(data.data,function(i,v){
                    	$('#'+i).html(v);
                    	if(i == 'video_url'){
                            $('#'+i).attr('href',v);
                            $('#'+i).text(v);
                        }
                        else if (i == "url") {
                            console.log(i, v);
                            $("#raw_video_url").css("display", "block");
                            var html = '';
                            var raw_urls = v.split(", ");
                            $.each(raw_urls, function(index, url) {
                                html += '<a target="_blank" style="color:black" href="'+data.root_url+url+'">'+url+'</a><br>';
                            });
                            $("#" + i).html(html);
                        }
                        else if (v != "emptyurl") {
                            $("#raw_video_url").css("display", "block");
                            $("#" + i).attr("href", (data.root_url + v));
                            $("#" + i).text(v);
                        } else {
                            $("#raw_video_url").css("display", "none");
                        }
                        if(i == 'id'){
                            $('#'+i).val(v);
                        }


					});

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
            table_export = $dt_tableExport.removeAttr('width').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": base_url+'get_video_leads',
                "ordering": true,
                "bAutoWidth":false,
                "aoColumns ": [
                    { sWidth: "10%"},
                    { sWidth: "20%"},
                    { sWidth: "20%"},
                    { sWidth: "20%"},
                    { sWidth: "20%"},
                    { sWidth: "10%"},
                ],
                columnDefs: [
                    { orderable: false, targets: 0 }
                ]
            });


        }
    }
};
