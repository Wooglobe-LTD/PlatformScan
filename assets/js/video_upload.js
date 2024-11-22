$(function () {
    'use strict';

    $('.datepicker').Zebra_DatePicker();
    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a video thumbnail here or click',
            'replace': 'Drag and drop or click to replace',
            'remove':  'Remove',
            'error':   'Ooops, something wrong happended.'
        },
        error: {
            'fileSize': 'The file size is too big ({{ value }}) max).',
            'minWidth': 'The image width is too small ({{ value }})px min).',
            'maxWidth': 'The image width is too big ({{ value }}}px max).',
            'minHeight': 'The image height is too small ({{ value }}) px min).',
            'maxHeight': 'The image height is too big ({{ value }}) px max).',
        }
    });
    Dropzone.autoDiscover = false;
    $("#dZUpload").dropzone({
        url: base_url+"file-upload",
        addRemoveLinks: true,
        acceptedFiles:'.mp4,.flv,.3gp,.mkv,.avi,.mov',
        filesizeBase:1024,
        createImageThumbnails: false,
        dictInvalidFileType:'Please enter the valid video file.',
        init: function(){
            this.on("error", function(file){if (!file.accepted) this.removeFile(file);});
        },
        success: function (file, response) {
            var imgName = response;
            file.previewElement.classList.add("dz-success");

            response = JSON.parse(response);
            console.log(response);
            if(response.video.code == 200){
                toastr.success(response.message);
            }else if(response.video.code == 201){
                toastr.error(response.error);
            }
        },
        error: function (file, response) {
            file.previewElement.classList.add("dz-error");
        }
    });

    $('#video_type_id').change(function () {
        var type_id = $(this).val();
        if(type_id.length > 0){

            if(type_id == 1){
                $('#video-div').show();
                $('#url').prop('disabled',true);
            }else{
                $('#video-div').hide();
                $('#url').prop('disabled',false);
            }

        }else{
            $('#video-div').hide();
            $('#url').prop('disabled',true);
        }
    });


    $('#category_id').change(function () {
        var category_id = $(this).val();
        var html = '<option value="">Select A Sub Category</option>';
        if(category_id.length > 0){
            $.ajax({
                type 	: "POST",
                url  	: base_url+"get_child_categories",
                data    : {category_id:category_id},
                success : function(data){
                    data = JSON.parse(data);
                    if(data.code == 204){

                    }else if(data.code == 201){

                        toastr.error(data.message);

                        $.each(data.error,function(i,v){
                            $('#'+i+'_err').html(v);
                        });

                    }else if(data.code == 200){

                        if(data.categories.length > 0){
                            $.each(data.categories,function (i,v) {
                                html += '<option value="'+v.id+'">'+v.title+'</option>';
                            });
                        }
                        $('#parent_id').html(html);

                    }else{
                        toastr.error(data.message);
                    }

                },
                error 	: function(){
                    toastr.error('Something is going wrong!');
                }
            });
        }else{
            $('#parent_id').html(html);
        }

    })






    $('#upload').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]'
    });

    $('#upload').on('submit',function (e) {

        e.preventDefault();
        var postData = new FormData($("#upload")[0]);
        $.ajax({
            type 	: "POST",
            url  	: base_url+"update-profile",
            processData: false,
            contentType: false,
            data : postData,
            success : function(data){
                data = JSON.parse(data);
                if(data.code == 204){
                    toastr.error(data.message);
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else if(data.code == 201){

                    toastr.error(data.message);
                    $('#upload').parsley().reset();
                    $.each(data.error,function(i,v){
                        $('#'+i+'_err').html(v);
                    });

                }else if(data.code == 200){
                    toastr.success(data.message);
                    setTimeout(function(){
                        window.location = data.url;
                    },2000);
                }else{
                    toastr.error(data.message);
                }

            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });

    });


});