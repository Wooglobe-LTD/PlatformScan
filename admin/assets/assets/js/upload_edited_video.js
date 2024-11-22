/**
 * Created by Abdul Rehman Aziz on 5/4/2018.
 */

$(function() {
    // file upload
    altair_form_file_upload.init();



});


altair_form_file_upload = {
    init: function() {


        var progressbar = $("#file_upload-progressbar_yt"),
            bar         = progressbar.find('.uk-progress-bar'),
            settings    = {
                action:  base_url+'upload_video?key='+ukey+'&type=youtube',
                allow : '*.(avi|mp4|wmv|flv|mkv|AVI|MP4|FLV|MKV)', // File filter
                single: true,
                param : 'files',
                chunks: 50,
                loadstart: function() {
                    bar.css("width", "0%").text("0%");
                    progressbar.removeClass("uk-hidden");
                },
                progress: function(percent) {
                    percent = Math.ceil(percent);
                    bar.css("width", percent+"%").text(percent+"%");
                },
                allcomplete: function(response,xhr) {
                    data = JSON.parse(response);
                    //data = response;
                    if(data.code == 204){
                        $('#err-msg').attr('data-message',data.message);
                        $('#err-msg').click();
                        setTimeout(function(){
                            window.location = data.url;
                        },1000);
                    }else if(data.code == 201){
                        $('#err-msg').attr('data-message',data.message);
                        $('#err-msg').click();
                        //$('#form_validation2').parsley().reset();
                        $.each(data.error,function(i,v){
                            $('#'+i).parent().parent().find('.error').html(v);
                        });

                    }else if(data.code == 200){
                        $('#suc-msg').attr('data-message',data.message);
                        $('#suc-msg').click();

                       // alert(data.url);

                       // console.log(data);

                       $('#yt_video').val(data.url);

                       var val = $('#yt_video').val();

                      // alert(val);

                        //$('#url').focus();

                    }else{
                        $('#err-msg').attr('data-message','Something is going wrong!');
                        $('#err-msg').click();
                    }
                    bar.css("width", "100%").text("100%");
                    setTimeout(function(){
                        progressbar.addClass("uk-hidden");
                    }, 250);
                    setTimeout(function() {
                        UIkit.notify({
                            message: "Upload Completed",
                            pos: 'top-right'
                        });
                    },280);
                }
            };

        var select = UIkit.uploadSelect($("#file_upload-select_yt"), settings),
            drop   = UIkit.uploadDrop($("#file_upload-drop_yt"), settings);

        var progressbar1 = $("#file_upload-progressbar_fb"),
            bar1         = progressbar1.find('.uk-progress-bar'),
            setting    = {
                action:  base_url+'upload_video?key='+ukey+'&type=facebook',
                allow : '*.(avi|mp4|wmv|flv|mkv|AVI|MP4|FLV|MKV)', // File filter
                single: true,
                param : 'files',
                chunk: 50,
                loadstart: function() {
                    bar1.css("width", "0%").text("0%");
                    progressbar1.removeClass("uk-hidden");
                },
                progress: function(percent) {
                    percent = Math.ceil(percent);
                    bar1.css("width", percent+"%").text(percent+"%");
                },
                allcomplete: function(response,xhr) {
                    data = JSON.parse(response);
                    if(data.code == 204){
                        $('#err-msg').attr('data-message',data.message);
                        $('#err-msg').click();
                        setTimeout(function(){
                            window.location = data.url;
                        },1000);
                    }else if(data.code == 201){
                        $('#err-msg').attr('data-message',data.message);
                        $('#err-msg').click();
                        //$('#form_validation2').parsley().reset();
                        $.each(data.error,function(i,v){
                            $('#'+i).parent().parent().find('.error').html(v);
                        });

                    }else if(data.code == 200){
                        $('#suc-msg').attr('data-message',data.message);
                        $('#suc-msg').click();

                       // alert(data.url);

                        $('#fb_video').val(data.url);
                        //$('#url').focus();

                        var val1 = $('#fb_video').val();

                       // alert(val1);

                    }else{
                        $('#err-msg').attr('data-message','Something is going wrong!');
                        $('#err-msg').click();
                    }
                    bar1.css("width", "100%").text("100%");
                    setTimeout(function(){
                        progressbar1.addClass("uk-hidden");
                    }, 250);
                    setTimeout(function() {
                        UIkit.notify({
                            message: "Upload Completed",
                            pos: 'top-right'
                        });
                    },280);
                }
            };

        var select1 = UIkit.uploadSelect($("#file_upload-select_fb"), setting),
            drop1   = UIkit.uploadDrop($("#file_upload-drop_fb"), setting);

        var progressbar2 = $("#file_upload-progressbar_portal"),
            bar2         = progressbar2.find('.uk-progress-bar'),
            setting    = {
                action:  base_url+'upload_video?key='+ukey+'&type=mrss',
                allow : '*.(avi|mp4|wmv|flv|mkv|AVI|MP4|FLV|MKV)', // File filter
                single: true,
                param : 'files',
                chunk: 50,
                loadstart: function() {
                    bar2.css("width", "0%").text("0%");
                    progressbar2.removeClass("uk-hidden");
                },
                progress: function(percent) {
                    percent = Math.ceil(percent);
                    bar2.css("width", percent+"%").text(percent+"%");
                },
                allcomplete: function(response,xhr) {
                    data = JSON.parse(response);
                    if(data.code == 204){
                        $('#err-msg').attr('data-message',data.message);
                        $('#err-msg').click();
                        setTimeout(function(){
                            window.location = data.url;
                        },1000);
                    }else if(data.code == 201){
                        $('#err-msg').attr('data-message',data.message);
                        $('#err-msg').click();
                       // $('#form_validation2').parsley().reset();
                        $.each(data.error,function(i,v){
                            $('#'+i).parent().parent().find('.error').html(v);
                        });

                    }else if(data.code == 200){
                        $('#suc-msg').attr('data-message',data.message);
                        $('#suc-msg').click();

                        //alert(data.url);

                        $('#portal_video').val(data.url);
                        var val2 = $('#portal_video').val();

                        //alert(val2);
                        //$('#url').focus();

                    }else{
                        $('#err-msg').attr('data-message','Something is going wrong!');
                        $('#err-msg').click();
                    }
                    bar2.css("width", "100%").text("100%");
                    setTimeout(function(){
                        progressbar2.addClass("uk-hidden");
                    }, 250);
                    setTimeout(function() {
                        UIkit.notify({
                            message: "Upload Completed",
                            pos: 'top-right'
                        });
                    },280);
                }
            };

        var select2 = UIkit.uploadSelect($("#file_upload-select_portal"), setting),
            drop2   = UIkit.uploadDrop($("#file_upload-drop_portal"), setting);

		var progressbar3 = $("#file_upload_banner_mp4_progressbar"),
			bar3         = progressbar3.find('.uk-progress-bar'),
			setting    = {
				action:  base_url+'upload_video_banner_mp4',
				allow : '*.(mp4|MP4)', // File filter
				single: true,
				param : 'files',
                chunk: 50,
				loadstart: function() {
					bar3.css("width", "0%").text("0%");
					progressbar3.removeClass("uk-hidden");
				},
				progress: function(percent) {
					percent = Math.ceil(percent);
					bar3.css("width", percent+"%").text(percent+"%");
					$('#banner_btn').prop('disabled',true);
				},
				allcomplete: function(response,xhr) {
					data = JSON.parse(response);
					if(data.code == 204){
						$('#err-msg').attr('data-message',data.message);
						$('#err-msg').click();

					}else if(data.code == 201){
						$('#err-msg').attr('data-message',data.message);
						$('#err-msg').click();
						// $('#form_validation2').parsley().reset();
						$.each(data.error,function(i,v){
							$('#'+i).parent().parent().find('.error').html(v);
						});

					}else if(data.code == 200){
						$('#suc-msg').attr('data-message',data.message);
						$('#suc-msg').click();
						$('#banner_btn').prop('disabled',false);
						//alert(data.url);

						$('#banner_video_mp4').val(data.url);
						var val3 = $('#banner_video_mp4').val();

						//alert(val2);
						//$('#url').focus();

					}else{
						$('#err-msg').attr('data-message','Something is going wrong!');
						$('#err-msg').click();
					}
					bar3.css("width", "100%").text("100%");
					setTimeout(function(){
						progressbar3.addClass("uk-hidden");
					}, 250);
					setTimeout(function() {
						UIkit.notify({
							message: "Upload Completed",
							pos: 'top-right'
						});
					},280);
				}
			};

		var select2 = UIkit.uploadSelect($("#file_upload_banner_mp4_select"), setting),
			drop2   = UIkit.uploadDrop($("#file_upload_banner_mp4_drop"), setting);

		var progressbar4 = $("#file_upload_banner_webm_progressbar"),
			bar4         = progressbar4.find('.uk-progress-bar'),
			setting    = {
				action:  base_url+'upload_video_banner_webm',
				allow : '*.(webm|WEBM)', // File filter
				single: true,
				param : 'files',
                chunk: 50,
				loadstart: function() {
					bar4.css("width", "0%").text("0%");
					progressbar4.removeClass("uk-hidden");
					$('#banner_btn').prop('disabled',true);
				},
				progress: function(percent) {
					percent = Math.ceil(percent);
					bar4.css("width", percent+"%").text(percent+"%");
				},
				allcomplete: function(response,xhr) {
					data = JSON.parse(response);
					if(data.code == 204){
						$('#err-msg').attr('data-message',data.message);
						$('#err-msg').click();

					}else if(data.code == 201){
						$('#err-msg').attr('data-message',data.message);
						$('#err-msg').click();
						// $('#form_validation2').parsley().reset();
						$.each(data.error,function(i,v){
							$('#'+i).parent().parent().find('.error').html(v);
						});

					}else if(data.code == 200){
						$('#banner_btn').prop('disabled',false);
						$('#suc-msg').attr('data-message',data.message);
						$('#suc-msg').click();

						//alert(data.url);

						$('#banner_video_webm').val(data.url);
						var val4 = $('#banner_video_webm').val();

						//alert(val2);
						//$('#url').focus();

					}else{
						$('#err-msg').attr('data-message','Something is going wrong!');
						$('#err-msg').click();
					}
					bar4.css("width", "100%").text("100%");
					setTimeout(function(){
						progressbar4.addClass("uk-hidden");
					}, 250);
					setTimeout(function() {
						UIkit.notify({
							message: "Upload Completed",
							pos: 'top-right'
						});
					},280);
				}
			};

		var select2 = UIkit.uploadSelect($("#file_upload_banner_webm_select"), setting),
			drop2   = UIkit.uploadDrop($("#file_upload_banner_webm_drop"), setting);
    }
};
