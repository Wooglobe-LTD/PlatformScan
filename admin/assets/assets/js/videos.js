var table_export;
var table_export_1;
var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);
var add_catgory;
var sub_catgory;
var user_id;
var statuss;
var video_type_id;
var video_type = 1;
var media_id;
var mrss_cc;
$(function() {
    // datatables
	altair_datatables.dt_tableExport();
	altair_datatables_1.dt_tableExport_1();
    //$(".single").eq(0).css('z-index',9999);
    //$(".single").eq(1).css('z-index',9999);
    //$(".single").eq(2).css('z-index',9999);
    //$(".single").eq(3).css('z-index',9999);
    //$(".single").eq(4).css('z-index',9999);
    $(".single:gt(4)").css('z-index',9999);
    /*$(".single").eq(6).css('z-index',9999);
    $(".single").eq(7).css('z-index',9999);
    $(".single").eq(8).css('z-index',9999);
    $(".single").eq(9).css('z-index',9999);
    $(".single").eq(10).css('z-index',9999);
    $(".single").eq(8).css('z-index',9999);
    $(".single").eq(8).css('z-index',9999);
    $(".single").eq(8).css('z-index',9999);
    $(".single").eq(8).css('z-index',9999);
    $(".single").eq(8).css('z-index',9999);
    $(".single").eq(8).css('z-index',9999);
    $(".single").eq(8).css('z-index',9999);*/
	$('#video_type').on('change',function(){
		var val = $(this).val();
		window.location = base_url+'videos?video_type='+val;
	});
    media_id = $('#earning_type_id').selectize();
    if(media_id.length > 0){
        media_id = media_id[0].selectize;
    }
    mrss_cc = $('#mrss_categories').selectize({
		plugins: {
			'remove_button': {
				label: 'X'
			}
		},
	});

    if(mrss_cc.length > 0){
        mrss_cc = mrss_cc[0].selectize;
    }
    media_id.on('change', function() {
        var val = media_id.getValue();
        $('.ms').hide();
        if(val == 1){
			$('#ss').show();

		}else if(val == 2) {
            $('#p').show();

		}
        $('#form_validation4').parsley();

    });
	add_catgory = $('#parent').selectize();
	if(add_catgory.length > 0){
		add_catgory = add_catgory[0].selectize;
	}

	sub_catgory = $('#category_id').selectize({
        plugins: {
            'remove_button': {
                label: 'X'
            }
        },
    });

	if(sub_catgory.length > 0){
		sub_catgory = sub_catgory[0].selectize;
	}



	user_id = $('#user_id').selectize();

	if(user_id.length > 0){
		user_id = user_id[0].selectize;
	}


	statuss = $('#status_u').selectize();

	if(statuss.length > 0){
		statuss = statuss[0].selectize;
	}


/*	video_type_id = $('#video_type_id').selectize();

	if(video_type_id.length > 0){
		video_type_id = video_type_id[0].selectize;
	}
	if(typeof edit_data !== 'undefined'){
		edit_data = JSON.parse(edit_data);
		console.log(edit_data);
		add_catgory.addOption({text: caterory, value: edit_data.parent_id});
		add_catgory.setValue(edit_data.parent_id);
		user_id.addOption({text: usr, value: edit_data.user_id});
		user_id.setValue(edit_data.user_id);
		statuss.addOption({text: sts, value: edit_data.status});
		statuss.setValue(edit_data.status);
		video_type_id.addOption({text: typ, value: edit_data.video_type_id});
		video_type_id.setValue(edit_data.video_type_id);
		if(edit_data.video_type_id == 1){

			$('#upload').show();
			//$('#url').val('');
			$('#url').prop('readonly',true);
		}else{

			$('#upload').hide();
			//$('#url').val('');
			$('#url').prop('readonly',false);

		}
		$.ajax({
			type 	: 'POST',
			url	 	: base_url+'get_video_sub_category',
			data 	: {id:edit_data.parent_id},
			success : function(data){

				data = JSON.parse(data);

				sub_catgory.clearCache('option');
				sub_catgory.clearOptions();
				sub_catgory.addOption(data.data);
				sub_catgory.setValue(edit_data.category_id);

			}
		});

	} */

	$('.selectize-input > input').val('');
	add_catgory.on('change', function() {
			var id = add_catgory.getValue();
			$.ajax({
				type 	: 'POST',
				url	 	: base_url+'get_video_sub_category',
				data 	: {id:id},
				success : function(data){

					data = JSON.parse(data);

					sub_catgory.clearCache('option');
					sub_catgory.clearOptions();
					sub_catgory.addOption(data.data);
					//sub_catgory.refreshOptions(true);

				}
			});
	});

/*	video_type_id.on('change', function() {
		var id = video_type_id.getValue();
		if(id == 1){

			$('#upload').show();
			$('#url').val('');
			$('#url').prop('readonly',true);
		}else{

			$('#upload').hide();
			$('#url').val('');
			$('#url').prop('readonly',false);

		}

}); */
    $(document).ready(function() {

        $('input.delete_facebook_video').change(function() {
            if(this.checked) {
                $('.facebook_main_edited').css('pointer-events', 'auto');
            }else{
                $('.facebook_main_edited').css('pointer-events', 'none');
            }
        });
        $('input.delete_youtube_video').change(function() {
            if(this.checked) {
                $('.youtube_main_edited').css('pointer-events', 'auto');
            }else{
                $('.youtube_main_edited').css('pointer-events', 'none');
            }
        });
    });
  /*  if ($('input.delete_youtube_video').is(':checked')) {
        console.log('clicked')
        $('.youtube_main_edited').css('pointer-events', 'auto');
    }*/

    $('#add').on('click',function(){

        $('#form_validation2').parsley().reset();
		$('#form_validation2')[0].reset();
		var modal = UIkit.modal("#add_model");
    	modal.show();

    });



	$('#edit_from').on('click',function(e){

		e.preventDefault();

		$('#form_validation3').submit();

    });
   /* $(document).on('click','.check',function(e){
        e.preventDefault();
        var modal = UIkit.modal("#add_model");
        modal.show();

    });

    $(document).on('click','#verified_form',function(e){

        e.preventDefault();

        var category = $('#category_verified').is(":checked");
        var tags = $('#tags_verified').is(":checked");
        var title = $('#title_verified').is(":checked");
        var description = $('#description_verified').is(":checked");
        var orignal = $('#orignal_video_verified').is(":checked");

        if(category == true){
            $('#is_category_verified').val(1);
		}
		else{
            $('#is_category_verified').val(0);
		}
		if(tags == true){
            $('#is_tags_verified').val(1);
		}
		else{
            $('#is_tags_verified').val(0);
		}
		if(title == true){
            $('#is_title_verified').val(1);
		}else{
            $('#is_title_verified').val(0);
		}
		if(description == true){
            $('#is_description_verified').val(1);
		}
		else {
            $('#is_description_verified').val(0);
		}
        if(orignal == true){
            $('#is_orignal_video_verified').val(1);
        }
        else {
            $('#is_orignal_video_verified').val(0);
        }
		if($('#is_category_verified').val() == 0 || $('#is_tags_verified').val() == 0 || $('#is_title_verified').val() == 0 || $('#is_description_verified').val() == 0 || $('#is_orignal_video_verified').val() == 0){

			$('#video_verified').val(0);
		}
		else {
            $('#video_verified').val(1);
		}
        var modal = UIkit.modal("#add_model");
        modal.hide();



        $('#form_validation3').submit();






      //  $('#form_validation2').submit();

    });*/

    $('#form_validation2').on('submit',function(e){

		e.preventDefault();
		var form = $('#form_validation2')[0];

		var data = new FormData(form);
		$.ajax({
			type 	: "POST",
			enctype: 'multipart/form-data',
			url  	: base_url+"add_video",
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
					$('#form_validation2').parsley().reset();
					$.each(data.error,function(i,v){
                        $('#'+i).parent().parent().find('.error').html(v);
                    });

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

			},
			error 	: function(){
				$('#err-msg').attr('data-message','Something is going wrong!');
				$('#err-msg').click();
			}
		});

	});



	$('#form_validation3').on('submit',function(e){

		e.preventDefault();



		var form = $('#form_validation3')[0];

		var data = new FormData(form);
		$.ajax({
			type 	: "POST",
			enctype: 'multipart/form-data',
			url  	: base_url+"update_video",
			cache: false,
			contentType: false,
			processData: false,
			data    : data,
			success : function(data){
				data = JSON.parse(data);
				console.log(data);
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
                        $('#'+i+'_e').parent().parent().find('.error').html(v);
                    });

				}else if(data.code == 200){
					$('#suc-msg').attr('data-message',data.message);
					$('#suc-msg').click();


                    if ('s3_ajax_files' in data) {

                        var s3_uploads = data.s3_ajax_files;

                        var file_source;
                        var file_ext;
                        var file_key;

                        for (i = 0; i < s3_uploads.length; i++) {
                            file_source = s3_uploads[i].file_source;
                            file_ext    = s3_uploads[i].file_ext;
                            file_key    = s3_uploads[i].file_key;

                            $.ajax({
                                type: "POST",
								url:   base_url + "Videos/put_oject_s3_cmd",
                                data: {'file-source':file_source, 'file-key':file_key, 'file-ext':file_ext},
                                success: function (response) {
                                	console.log(response);

                                }
                            });
						}

                    }

                    setTimeout(function(){
                        window.location = data.url;
                    },1500);
                }else if(data.code == 205){
                    $('#err-msg').attr('data-message',data.message);
                    $('#err-msg').click();
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);

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

	$(document).on('click','.delete-video',function(){

		var id = $(this).data('id');
		UIkit.modal.confirm('Are you sure you want to delete this video?', function(){
			$.ajax({
				type 	: 'POST',
				url  	: base_url+'delete_video',
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
    $(document).on('click','.delete_btn',function(){

        var id = $(this).data('id');
        var url = $(this).data('url');
        UIkit.modal.confirm('Are you sure you want to delete this video?', function(){
            $.ajax({
                type 	: 'POST',
                url  	: base_url+'delete_video_raw_file',
                data 	: {id:id,url:url},
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
                            youtubeid=youtubefull[1];
                            youtubesecondsplit=youtubefull[1].split('&');
                            if(youtubesecondsplit[1]){
                                youtubefull[1]=youtubesecondsplit[0];
                            }
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

    $(document).on('click','.add-earning',function(){

        $('#form_validation4').parsley().reset();
        $('#form_validation4')[0].reset();
		var id = $(this).data('id');
		var title = $(this).data('title');
		$('#video-title').text(title);
		$('#video_id').val(id);
        var modal = UIkit.modal("#earning_model");
        modal.show();

    });

    $(document).on('click','.add-expense',function(){

        $('#form_validation5').parsley().reset();
        $('#form_validation5')[0].reset();
        var id = $(this).data('id');
        var title = $(this).data('title');
        $('#video-title-expense').text(title);
        $('#video_id_expense').val(id);
        var modal = UIkit.modal("#expense_model");
        modal.show();

    });
    $(document).on('click','.mrss-feed',function(){

        $('#form_validation6').parsley().reset();
        $('#form_validation6')[0].reset();
        var id = $(this).data('id');
        var title = $(this).data('title');
        var mrss = $(this).data('mrss');
        var mrss_c = $(this).data('mrss-c');
        if(mrss == 1){
            $('#mrss').iCheck('uncheck');
            $('#mrss_id').show();
            if(mrss_c != 0){
                mrss_cc.setValue(mrss_c);
            }

        }else{
            $('#mrss').iCheck('check');
            $('#mrss_id').hide();
        }
        $('#video1-title').text(title);
        $('#video1_id').val(id);
        var modal = UIkit.modal("#mrss_model");
        modal.show();

    });
    $(document).on('click','#add_earning_from',function (e) {
        e.preventDefault();
        $('#form_validation4').submit();
    });
    $(document).on('click','#mrss_feed_form',function (e) {
        e.preventDefault();
        $('#form_validation6').submit();
    });
    $('#form_validation6').on('submit',function(e){

        e.preventDefault();

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: base_url + "update_mrss",
                data: $('#form_validation6').serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        $('#form_validation6').parsley().reset();


                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        var modal = UIkit.modal("#mrss_model");
                        modal.hide();
                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });


    });
    $(document).on('click','#add_expense_from',function (e) {
        e.preventDefault();
        $('#form_validation5').submit();
    });
    $(document).on('click','#description_reminder',function () {

        var id = $(this).attr('data-id');

        $.ajax({
            type: "POST",
            url: base_url + "description-send-reminder-email",
            data:{id:id},
            success: function (data) {
                data = JSON.parse(data);
                if(data.code == 200){
                    $('#suc-msg').attr('data-message', 'Reminder email send successfully!');
                    $('#suc-msg').click();
                }
                else if(data.code == 201){
                    $('#err-msg').attr('data-message', 'Having trouble while sending Email.');
                    $('#err-msg').click();
                }

            }
        });

    });
    $('#form_validation4').on('submit',function(e){

        e.preventDefault();
        UIkit.modal.confirm('Are you sure you want to add this earning?', function() {
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: base_url + "add_earning",
                data: $('#form_validation4').serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        $('#form_validation4').parsley().reset();
                        $.each(data.error, function (i, v) {
                            $('#' + i).parent().parent().find('.error').html(v);
                        });

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        var modal = UIkit.modal("#earning_model");
                        modal.hide();
                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });
        });

    });

    $('#form_validation5').on('submit',function(e){

        e.preventDefault();
        UIkit.modal.confirm('Are you sure you want to add this expense?', function() {
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: base_url + "add_video_expense",
                data: $('#form_validation5').serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        $('#form_validation5').parsley().reset();
                        $.each(data.error, function (i, v) {
                            $('#' + i).parent().parent().find('.error').html(v);
                        });

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        var modal = UIkit.modal("#expense_model");
                        modal.hide();
                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });
        });

    });
	
	$('#chkbox-upload-single-video').on('ifChecked ifUnchecked', function(event){

		var callback     = event.type;
		var target_div   = $('#portal_video_div');
		var target_field = $('input#portal_video');
		switch (callback) {
			case 'ifChecked':
				target_div.show();
				target_field.attr('name', 'portal_video');
				break;
			case 'ifUnchecked':
				target_div.hide();
				target_field.removeAttr('name');
				break;
		}
	});

    $('#edited_video').on('submit',function(e) {

        e.preventDefault();

        var form = $('#edited_video')[0];

        var data = new FormData(form);
        console.log(data)
        $.ajax({
            type 	: "POST",
            enctype: 'multipart/form-data',
            url  	: base_url+"edited_video_upload",
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
                    $('#edited_video').parsley().reset();
					
					
					
					
                    $.each(data.error,function(i,v){
						console.log('i '+i);
						console.log('v '+v);
						console.log('---------------');
                        $('#'+i).parent().parent().find('.error').html(v);
                    });

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

            },
            error 	: function(){
                $('#err-msg').attr('data-message','Something is going wrong!');
                $('#err-msg').click();
            }
        });
    });

    $(document).on('click','.play-files',function(){

        var id = $(this).data('id');

        $.ajax({
            type 	: 'POST',
            url  	: base_url+'get_file',
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
                    html = ''

					html = '<video width="400" controls><source src="'+root+data.data.url+'">Your browser does not support HTML5 video.</video>'

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
    $('#is_mrss').on('ifChecked', function () {

        $('#mrss_id').show();

    });

    $('#is_mrss').on('ifUnchecked', function () {

        $('#mrss_id').hide();

    });
	$('#is_mrss_partner').on('ifChecked', function () {

		$('#mrss_partner_id').show();
        $('.in_ex_top_area').hide();
        $('#is_mrss_partner').val(1);
	});
    $('#not_mrss_partner').on('ifChecked', function () {

        $('#all_mrss_partner_id').show();
        $('#all_mrss_partner_cat').show();
        $('.ex_top_area').hide();
        $('#not_mrss_partner').val(1);

    });
    $('#is_mrss_partner').on('ifUnchecked', function () {
        $('#mrss_partner_cat').hide();
        $('#mrss_partner_id').hide();
        $('.in_ex_top_area').show();
        $("#mrss_partner_id option:selected").removeAttr("selected");
        $("#mrss_partner_cat option:selected").removeAttr("selected");
        $('#is_mrss_partner').val(0);
    });
    $('#not_mrss_partner').on('ifUnchecked', function () {

        $('#all_mrss_partner_id').hide();
        $('#all_mrss_partner_cat').hide();
        $('.ex_top_area').show();
        $("#all_mrss_partner_id option:selected").removeAttr("selected");
        $("#all_mrss_partner_cat option:selected").removeAttr("selected");
        $('#not_mrss_partner').val(0);
    });
    $('#mrss_partner').on('change',function () {
        var mrss_value=this.value;
        $('#mrss_partner_cat').show();
        var dataarray = [];
        $.ajax({
            type 	: 'POST',
            url  	: base_url+'mrss_partner',
            data 	: {id:mrss_value},
            async: false,
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
                    dataarray =data;
                    return dataarray;
                    modal.show();

                }else{
                    $('#err-msg').attr('data-message','Something is going wrong!');
                    $('#err-msg').click();
                }


            }
        });
        mrss_cp = $('#mrss_partner_cat_opt').selectize({

            plugins: {
                'remove_button': {
                    label: 'X'
                }
            },
        });

        if(mrss_cp.length > 0){
            mrss_cp = mrss_cp[0].selectize;
            mrss_cp.clearOptions();
            for(var i=0;i<=dataarray.data.length-1;i++){console.log('i:::'+i);
                mrss_cp.addOption({value:dataarray.data[i].id,text:dataarray.data[i].url});

            }

        }


    });
    if(exfedid){
        //$('#mrss_partner').selectize.setValue(expartnerid);
        var mrss_value=expartnerid;
        $('#mrss_partner_cat').show();
        var dataarray = [];
        $.ajax({
            type 	: 'POST',
            url  	: base_url+'mrss_partner',
            data 	: {id:mrss_value},
            async: false,
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
                    dataarray =data;
                    return dataarray;
                    modal.show();

                }else{
                    $('#err-msg').attr('data-message','Something is going wrong!');
                    $('#err-msg').click();
                }


            }
        });
        mrss_cp = $('#mrss_partner_cat_opt').selectize({

            plugins: {
                'remove_button': {
                    label: 'X'
                }
            },
        });

        if(mrss_cp.length > 0){
            mrss_cp = mrss_cp[0].selectize;
            mrss_cp.clearOptions();

            for(var i=0;i<=dataarray.data.length-1;i++){console.log('i:::'+i);
                mrss_cp.addOption({value:dataarray.data[i].id,text:dataarray.data[i].url});
                mrss_cp.setValue(exfedid);

            }

        }
    }

    mrss_allcp = $('#all_mrss_partners').selectize({

        plugins: {
            'remove_button': {
                label: 'X'
            }
        },
    });

    if(mrss_allcp.length > 0){
        mrss_allcp = mrss_allcp[0].selectize;
    }


    mrss_allcc = $('#all_mrss_cat').selectize({

        plugins: {
            'remove_button': {
                label: 'X'
            }
        },
    });

    if(mrss_allcc.length > 0){
        mrss_allcc = mrss_allcc[0].selectize;
    }

});

altair_datatables = {

    dt_tableExport: function() {
        var $dt_tableExport = $('#dt_tableExport'),
            $dt_buttons = $dt_tableExport.prev('.dt_colVis_buttons');

        if($dt_tableExport.length) {
            table_export = $dt_tableExport.DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": base_url+'get_videos?video_type='+video_type,
                "columnDefs": [ { orderable: false, targets: [soryFlast, 0] }],
                /*'initComplete': function(){
                    var api = this.api();



                    dataSrc.sort();
					console.log(dataSrc);
                    // Initialize Typeahead plug-in
                    $('.dataTables_filter input[type="search"]', api.table().container())
                        .typeahead({
                                source: dataSrc,
                                afterSelect: function(value){
                                    api.search(value).draw();
                                }
                            }
                        );
                }*/
            });


        }
    }
};
altair_datatables_1 = {

    dt_tableExport_1: function() {
        var $dt_tableExport_1 = $('#dt_tableExport_1'),
            $dt_buttons = $dt_tableExport_1.prev('.dt_colVis_buttons');

        if($dt_tableExport_1.length) {
            table_export_1 = $dt_tableExport_1.DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": base_url+'get_original_files/'+id,
                "columnDefs": [ { orderable: false, targets: [soryFlast] }],

            });


        }
    }
};
