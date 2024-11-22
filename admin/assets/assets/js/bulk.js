
$(function() {
    // file upload
    altair_form_file_upload.init();

    $('.dropify_my').dropify({
        messages: {
            'default': 'Drag and drop a CSV file here or click',
            'replace': 'Drag and drop or click to replace',
            'remove':  'Remove',
            'error':   'Ooops, something wrong happended.'
        }
    });
    $('#edited_video').on('submit',function(e){console.log('submitted bulk upload');

        e.preventDefault();
        var form = $('#edited_video')[0];
        console.log('form bulk upload');
        var data = new FormData(form);console.log('data bulk upload:'+data);

        $.ajax({
            type 	: "POST",
            enctype: 'multipart/form-data',
            url  	: base_url+"csv_upload",
            cache: false,
            contentType: false,
            processData: false,
            data    : data,
            success : function(data){console.log('Success bulk upload:'+data);
                data = JSON.parse(data);
                $('.error').html('');
                altair_helpers.custom_preloader_hide();
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

                        $('#'+i).parent().parent().parent().find('.error').html(v);
                    });

                }else if(data.code == 200){
                	if(data.problems){console.log(data.admin_email);
						//sendBulkUploadDetails(data.problems, "farooq.akbar@powersoft19.com");
					}
                    $('#suc-msg').attr('data-message',data.message);
                    $('#suc-msg').click();
                    setTimeout(function(){
                        //window.location = data.url;
                    },1000);
                }else{
                    $('#err-msg').attr('data-message','Something is going wrong!');
                    $('#err-msg').click();
                }

            },
            error 	: function(){console.log('Error bulk upload:');
                $('#err-msg').attr('data-message','Something is going wrong!');
                $('#err-msg').click();
            }
        });

    });

    /*for mobile application*/
    $('#import_videos').on('submit',function(e){

        e.preventDefault();
        var form = $('#import_videos')[0];


        var data = new FormData(form);
        console.log("form data "+data);
        $.ajax({
            type 	: "POST",
            enctype: 'multipart/form-data',
            url  	: base_url+"import-videos",
            cache: false,
            contentType: false,
            processData: false,
            data    : data,
            success : function(data){
                data = JSON.parse(data);
                $('.error').html('');
                altair_helpers.custom_preloader_hide();
                if(data.code == 204){
                    $('#err-msg').attr('data-message',data.message);
                    $('#err-msg').click();
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else if(data.code == 201){
                    $('#err-msg').attr('data-message',data.message);
                    $('#err-msg').click();
                    $('#import_videos').parsley().reset();
                    $.each(data.error,function(i,v){

                        $('#'+i).parent().parent().parent().find('.error').html(v);
                    });

                }else if(data.code == 200){
                    if(data.problems){
                        sendBulkUploadDetails(data.problems);
                    }
                    $('#suc-msg').attr('data-message',data.message);

                    sendBulkUploadDetails(data.import_status_message);
                    $('#suc-msg').click();
                    setTimeout(function(){
                        //window.location = data.url;
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
});

function sendBulkUploadDetails(message, adminEmail){
	$.ajax({
		type: "POST",
		url: base_url + "send_mail",
		data: {to:adminEmail, cc:'', bcc:'', subject:"Bulk Upload Complete", message:message},
		success: function (data) {
			data = JSON.parse(data);
			if (data.code == 204) {
				$('#err-msg').attr('data-message', data.message);
				$('#err-msg').click();

			} else if (data.code == 201) {
				alert(data.error);

			} else if (data.code == 200) {
				window.close();
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
}


