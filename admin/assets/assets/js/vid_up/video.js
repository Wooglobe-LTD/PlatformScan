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
    $('input[name="file-yt"]').fileuploader({
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
            url: base_url+"upload_video?key="+ukey+"&type=youtube",
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

                try {
                    data = JSON.parse(result);
                } catch (e) {
                    data.hasWarnings = true;
                }

                console.log(data);
                $('#suc-msg').attr('data-message',data.message);
                $('#suc-msg').click();
                $('#file_upload-drop_yt .fileuploader-input').hide();
                $('#file_upload-drop_yt .fileuploader-item-icon').hide();
                $('#file_upload-drop_yt .progress-bar2').hide();
                $('#file_upload-drop_yt .cm-yt').show();
                $('#yt_video').val(data.url);

                var val = $('#yt_video').val();

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
                // setTimeout(function() {
                //     item.html.find('.progress-bar2').fadeOut(400);
                // }, 400);
            },
            onError: function(item) {
                var progressBar = item.html.find('.progress-bar2');
                $('.preloadr-div').hide();
                if(progressBar.length) {
                    progressBar.find('span').html(0 + "%");
                    progressBar.find('.fileuploader-progressbar .bar').width(0 + "%");
                    item.html.find('.progress-bar2').fadeOut(400);
                }
                $('#err-msg').attr('data-message','Something is going wrong!');
                $('#err-msg').click();
                item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
                    '<a class="fileuploader-action fileuploader-action-retry" title="Retry"><i></i></a>'
                ) : null;
            },
            onProgress: function(data, item) {
                $('.preloadr-div').hide();
                $('#file_upload-drop_yt .fileuploader-input').hide();
                $('#file_upload-drop_yt .fileuploader-item-icon').hide();
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

            $('.cm-yt').hide();
            $('#file_upload-drop_yt .fileuploader-input').show();


        },

        onEmpty: function(listEl, parentEl, newInputEl, inputEl) {
            $('#fm-1').attr('disabled', true);
        },

        captions: {

            feedback: 'Drag and drop files here',
            feedback2: 'Drag and drop files here',
            drop: 'Drag and drop files here',
            or: 'or',
            button: 'Browse Files',

        },
        thumbnails: {
            item: '<li class="fileuploader-item">' +
            '<div class="progress-bar2">${progressBar}<span></span></div>' +
            '<div class="columns">' +
            '<div class="column-thumbnail"><span class="fileuploader-action-popup"></span></div>' +
            '<div class="column-title">' +
            '<div title="${name}">${name}</div>' +
            '<span>${size2}</span>' +
            '</div>' +
            '<div class="column-actions">' +
                '<button class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove">Remove</i></button>' +
            '</div>' +
            '</div>' +
            '</li>',
        }
    });

    $('input[name="file-fb"]').fileuploader({
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
            url: base_url+"upload_video?key="+ukey+"&type=facebook",
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

                try {
                    data = JSON.parse(result);
                } catch (e) {
                    data.hasWarnings = true;
                }

                console.log(data);
                $('#suc-msg').attr('data-message',data.message);
                $('#suc-msg').click();
                $('#file_upload-drop_fb .fileuploader-input').hide();
                $('#file_upload-drop_fb .fileuploader-item-icon').hide();
                $('#file_upload-drop_fb .progress-bar2').hide();
                $('#file_upload-drop_fb .cm-fb').show();

                $('#fb_video').val(data.url);

                var val = $('#fb_video').val();

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
                // setTimeout(function() {
                //     item.html.find('.progress-bar2').fadeOut(400);
                // }, 400);
            },
            onError: function(item) {
                var progressBar = item.html.find('.progress-bar2');
                $('.preloadr-div').hide();
                if(progressBar.length) {
                    progressBar.find('span').html(0 + "%");
                    progressBar.find('.fileuploader-progressbar .bar').width(0 + "%");
                    item.html.find('.progress-bar2').fadeOut(400);
                }
                $('#err-msg').attr('data-message','Something is going wrong!');
                $('#err-msg').click();

                item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
                    '<a class="fileuploader-action fileuploader-action-retry" title="Retry"><i></i></a>'
                ) : null;
            },
            onProgress: function(data, item) {
                $('.preloadr-div').hide();
                $('#file_upload-drop_fb .fileuploader-input').hide();
                $('#file_upload-drop_fb .fileuploader-item-icon').hide();
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

            $('.cm-fb').hide();
            $('#file_upload-drop_fb .fileuploader-input').show();

        },

        onEmpty: function(listEl, parentEl, newInputEl, inputEl) {
            $('#fm-1').attr('disabled', true);
        },

        captions: {

            feedback: 'Drag and drop files here',
            feedback2: 'Drag and drop files here',
            drop: 'Drag and drop files here',
            or: 'or',
            button: 'Browse files test',

        },
        thumbnails: {
            item: '<li class="fileuploader-item">' +
            '<div class="progress-bar2">${progressBar}<span></span></div>' +
            '<div class="columns">' +
            '<div class="column-thumbnail"><span class="fileuploader-action-popup"></span></div>' +
            '<div class="column-title">' +
            '<div title="${name}">${name}</div>' +
            '<span>${size2}</span>' +
            '</div>' +
            '<div class="column-actions">' +
            '<button class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove">Remove</i></button>' +
            '</div>' +
            '</div>' +
            '</li>',
        }
    });

    $('input[name="file-mrss"]').fileuploader({
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
            url: base_url+"upload_video?key="+ukey+"&type=mrss",
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

                try {
                    data = JSON.parse(result);
                } catch (e) {
                    data.hasWarnings = true;
                }

                console.log(data);
                $('#suc-msg').attr('data-message',data.message);
                $('#suc-msg').click();
                $('#file_upload-drop_mrss .fileuploader-input').hide();
                $('#file_upload-drop_mrss .fileuploader-item-icon').hide();
                $('#file_upload-drop_mrss .progress-bar2').hide();
                $('#file_upload-drop_mrss .cm-mrss').show();

                $('#portal_video').val(data.url);

                var val = $('#portal_video').val();

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
                // setTimeout(function() {
                //     item.html.find('.progress-bar2').fadeOut(400);
                // }, 400);
            },
            onError: function(item) {
                var progressBar = item.html.find('.progress-bar2');
                $('.preloadr-div').hide();
                if(progressBar.length) {
                    progressBar.find('span').html(0 + "%");
                    progressBar.find('.fileuploader-progressbar .bar').width(0 + "%");
                    item.html.find('.progress-bar2').fadeOut(400);
                }
                $('#err-msg').attr('data-message','Something is going wrong!');
                $('#err-msg').click();

                item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
                    '<a class="fileuploader-action fileuploader-action-retry" title="Retry"><i></i></a>'
                ) : null;
            },
            onProgress: function(data, item) {
                $('.preloadr-div').hide();
                $('#file_upload-drop_mrss .fileuploader-input').hide();
                $('#file_upload-drop_mrss .fileuploader-item-icon').hide();
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

            $('.cm-yt').hide();
            $('#file_upload-drop_yt .fileuploader-input').show();
        },

        onEmpty: function(listEl, parentEl, newInputEl, inputEl) {
            $('#fm-1').attr('disabled', true);
        },

        captions: {

            feedback: 'Drag and drop files here',
            feedback2: 'Drag and drop files here',
            drop: 'Drag and drop files here',
            or: 'or',
            button: 'Browse files test',

        },
        thumbnails: {
            item: '<li class="fileuploader-item">' +
            '<div class="progress-bar2">${progressBar}<span></span></div>' +
            '<div class="columns">' +
            '<div class="column-thumbnail"><span class="fileuploader-action-popup"></span></div>' +
            '<div class="column-title">' +
            '<div title="${name}">${name}</div>' +
            '<span>${size2}</span>' +
            '</div>' +
            '<div class="column-actions">' +
            '<button class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove">Remove</i></button>' +
            '</div>' +
            '</div>' +
            '</li>',
        }
    });
});

