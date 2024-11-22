function on_reomve(id){

    $('[id="'+id+'"]').remove();
    if($('.remove_input').length == 0){
        setTimeout(function(){
            $('#fm-1').prop('disabled',true);
        },100);

    }
}
$(document).ready(function() {
    $( document ).ajaxStart(function() {
        //$('.preloadr-div').hide();
        $('#fm-1').attr('disabled',true);
    });
    $( document ).ajaxStop(function() {
        $('#fm-1').attr('disabled',false);
    });
	// enable fileuploader plugin
	$('input[name="file"]').fileuploader({
        changeInput: '<div class="fileuploader-input">' +
					      '<div class="fileuploader-input-inner">' +
						      '<div class="fileuploader-main-icon"></div>' +
							  '<h3 class="fileuploader-input-caption"><span>${captions.feedback}</span></h3>' +
							  '<p>${captions.or}</p>' +
							  '<div class="fileuploader-input-button"><span>${captions.button}</span></div>' +
						  '</div>' +
					  '</div>',
        theme: 'dragdrop',
        extensions:['mp4', 'flv', '3gp', 'mkv', 'avi', 'mov','mts'],
		upload: {
            url: base_url+"file-upload/"+uid,
            data: null,
            type: 'POST',
            chunk: 50,
            global: false,
            enctype: 'multipart/form-data',
            start: function(){
                $('.preloadr-div').hide();
            },
            ajaxSend:function(){
                $('.preloadr-div').hide();
            },
            synchron: true,
            beforeSend: function() {
                // setting a timeout

            },
            onSuccess: function(result, item) {
                //$('#fm-1').attr('disabled',false);
                var data = {};
                $('.progress-stat').hide();
                $('#video-contract-form-submit').show();
                $('#viral_submit_button').show();
				$('#acquired-client-video-form-submit').show();
				try {
					data = JSON.parse(result);
				} catch (e) {
					data.hasWarnings = true;
				}

                console.log(data);
				
                // if success
                
                if (data.isSuccess && data.files[0]) {
                    item.name = data.files[0].name;
                    item.title_new = data.files[0].title_new;

					item.html.find('.column-title > div:first-child').text(data.files[0].name).attr('title', data.files[0].name);
                    $('#video_urls').append('<input class="remove_input" type="hidden" id="'+item.title_new+'" name="url[]" value="'+data.files[0].video+'" />');

                }
              
				// if warnings
				if (data.hasWarnings) {
					for (var warning in data.warnings) {
						alert(data.warnings);
					}
					
					item.html.removeClass('upload-successful').addClass('upload-failed');
					// go out from success function by calling onError function
					// in this case we have a animation there
					// you can also response in PHP with 404
					return this.onError ? this.onError(item) : null;
				}
                
                item.html.find('.fileuploader-action-remove').addClass('fileuploader-action-success');
                setTimeout(function() {
                    item.html.find('.progress-bar2').fadeOut(400);
                }, 400);
            },
            onError: function(item) {
				var progressBar = item.html.find('.progress-bar2');
				$('.preloadr-div').hide();
                $('.progress-stat').hide();
				if(progressBar.length) {
					progressBar.find('span').html(0 + "%");
                    progressBar.find('.fileuploader-progressbar .bar').width(0 + "%");
					item.html.find('.progress-bar2').fadeOut(400);
				}
                
                item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
                    '<a class="fileuploader-action fileuploader-action-retry" title="Retry"><i></i></a>'
                ) : null;
            },
            onProgress: function(data, item) {
                $('.preloadr-div').hide();
                $('.progress-stat').show();
                $('#viral_submit_button').hide();
                $('#video-contract-form-submit').hide();
                $('#acquired-client-video-form-submit').hide();
                var progressBar = item.html.find('.progress-bar2');
                if(progressBar.length > 0) {
                    progressBar.show();
                    progressBar.find('span').html(data.percentage + "%");
                    progressBar.find('.fileuploader-progressbar .bar').width(data.percentage + "%");
                }
            },
            onComplete: function(){

            },

        },
        
		onRemove: function(item) {
           // $('#fm-1').attr('disabled', false);

            $.post(base_url+'remove_file', {
				file: item.name
			})  .done(function() {
                on_reomve(item.title_new);
            })

            

		},

        onEmpty: function(listEl, parentEl, newInputEl, inputEl) {
            $('#fm-1').attr('disabled', true);
        },

		captions: {

            feedback: 'Drag and drop all raw video files related to this story here',
            feedback2: 'Drag and drop files here',
            drop: 'Drag and drop files here',
            or: 'or',
            button: 'Browse files',
        
        },
	});
	
});

function uploder_plugin(uid) {
    $('input[name="files"]').fileuploader({
        changeInput: '<div class="fileuploader-input">' +
        '<div class="fileuploader-input-inner">' +
        '<div class="fileuploader-main-icon"></div>' +
        '<h3 class="fileuploader-input-caption"><span>${captions.feedback}</span></h3>' +
        '<p>${captions.or}</p>' +
        '<div class="fileuploader-input-button"><span>${captions.button}</span></div>' +
        '</div>' +
        '</div>',
        theme: 'dragdrop',
        extensions:['mp4', 'flv', '3gp', 'mkv', 'avi', 'mov','mts'],
        upload: {
            url: base_url+"file-upload-2/"+uid,
            data: null,
            type: 'POST',
            chunk: 50,
            global: false,
            enctype: 'multipart/form-data',
            start: function(){
                $('.preloadr-div').hide();
            },
            ajaxSend:function(){
                $('.preloadr-div').hide();
            },
            synchron: true,
            beforeSend: function(item, listEl, parentEl, newInputEl, inputEl) {

            },
            onSuccess: function(result, item, listEl, parentEl) {
                //$('#fm-1').attr('disabled',false);
                var data = {};
                $('.progress-stat').hide();
                $('#video-contract-form-submit').show();
                $('#viral_submit_button').show();
                $('#acquired-client-video-form-submit').show();
                try {
                    data = JSON.parse(result);
                } catch (e) {
                    data.hasWarnings = true;
                }

                console.log(parentEl);

                // if success

                if (data.isSuccess && data.files[0]) {
                    item.name = data.files[0].name;
                    item.title_new = data.files[0].title_new;

                    item.html.find('.column-title > div:first-child').text(data.files[0].name).attr('title', data.files[0].name);
                    parentEl.append('<input class="remove_input" type="hidden" id="'+item.title_new+'" name="url_multi[videos]['+uid+'][]" value="'+data.files[0].video+'" />');

                }

                // if warnings
                if (data.hasWarnings) {
                    for (var warning in data.warnings) {
                        alert(data.warnings);
                    }

                    item.html.removeClass('upload-successful').addClass('upload-failed');
                    // go out from success function by calling onError function
                    // in this case we have a animation there
                    // you can also response in PHP with 404
                    return this.onError ? this.onError(item) : null;
                }

                item.html.find('.fileuploader-action-remove').addClass('fileuploader-action-success');
                setTimeout(function() {
                    item.html.find('.progress-bar2').fadeOut(400);
                }, 400);
            },
            onError: function(item) {
                var progressBar = item.html.find('.progress-bar2');
                $('.preloadr-div').hide();
                $('.progress-stat').hide();
                if(progressBar.length) {
                    progressBar.find('span').html(0 + "%");
                    progressBar.find('.fileuploader-progressbar .bar').width(0 + "%");
                    item.html.find('.progress-bar2').fadeOut(400);
                }

                item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
                    '<a class="fileuploader-action fileuploader-action-retry" title="Retry"><i></i></a>'
                ) : null;
            },
            onProgress: function(data, item) {
                $('.preloadr-div').hide();
                $('.progress-stat').show();
                $('#viral_submit_button').hide();
                $('#video-contract-form-submit').hide();
                $('#acquired-client-video-form-submit').hide();
                var progressBar = item.html.find('.progress-bar2');
                if(progressBar.length > 0) {
                    progressBar.show();
                    progressBar.find('span').html(data.percentage + "%");
                    progressBar.find('.fileuploader-progressbar .bar').width(data.percentage + "%");
                }
            },
            onComplete: function(){

            },

        },

        onRemove: function(item) {
            // $('#fm-1').attr('disabled', false);

            $.post(base_url+'remove_file', {
                file: item.name
            })  .done(function() {
                on_reomve(item.title_new);
            })



        },

        onEmpty: function(listEl, parentEl, newInputEl, inputEl) {
            $('#fm-1').attr('disabled', true);
        },

        captions: {

            feedback: 'Drag and drop all raw video files related to this story here',
            feedback2: 'Drag and drop files here',
            drop: 'Drag and drop files here',
            or: 'or',
            button: 'Browse files',

        },
    });


}
 $(window).on('load',function () {
     uploder_plugin(uid);
 })
