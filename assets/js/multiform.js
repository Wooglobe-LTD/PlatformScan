/**
 * Created by Abdul Rehman Aziz on 3/15/2018.
 */
var gname = '';
(function($){

    Dropzone.autoDiscover = false;
    $("#dZUpload").dropzone({
        url: base_url+"file-upload",
        addRemoveLinks: true,
        acceptedFiles:'.mp4,.flv,.3gp,.mkv,.avi',
        filesizeBase:1024,
        createImageThumbnails: false,
        dictInvalidFileType:'Please enter the valid video file.',
        init: function(){
            this.on("error", function(file){
                if (!file.accepted)
                    this.removeFile(file);
                $('#fm-1').prop('disabled',false);
            });
            this.on("complete", function(file,res) {
                $(".dz-remove").attr('href','javascript:void(0);');
                $(".dz-remove").attr('data-name',gname);
                $('#fm-1').prop('disabled',false);
            });
            this.on("removedfile", function(file){
                var name = $(file._removeLink).data('name');
                console.log(name);
                delete_file(name);
            });
            this.on("sending", function(file,xhr, formData){
                $('#fm-1').prop('disabled',true);
                formData.append('uid', uid);
            });


        },
        /*$( "" ).append( "<div>Test</div>" );*/
        success: function (file, response) {
            var imgName = response;
            file.previewElement.classList.add("dz-success");



            response = JSON.parse(response);

           // console.log(file);

            if(response.video.code == 200){
                toastr.success(response.message);
                //file.removeLink.data('id',response.video.url);

                 //var att = file._removeLink.createAttribute("data-id");
                //att.value = response.video.url;

                $('#video_urls').append('<input type="hidden" name="url[]"  value="'+response.video.url+'" />');
                gname = response.video.url;
                $('#fm-1').prop('disabled',false);
                //$('#url').val(response.video.url);

            }else if(response.video.code == 201){
                toastr.error(response.error);
                $('#fm-1').prop('disabled',false);

            }
        },
        error: function (file, response) {
            file.previewElement.classList.add("dz-error");
        }
    });


    $('#same').on('click',function () {
       var val =  $('#same').is(':checked');
       var email =  $('#same').data('email');
       if(val == true)
       {
           $('#email').prop('disabled', true);
           $('#email').parsley().destroy();
           $('#email').removeAttr('required');
           $('#email').val(email);

       }
       else{
           $('#email').prop('disabled', false)
           $('#email').val('');
       }
    });

    var slug= $('#slug').val();

    //alert(slug);


    $('#video-submit-form').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset]'
    });

    $('#video-submit-form').on('submit',function (e) {

        e.preventDefault();
        $('.error').html('');
        $('.preloadr-div').show();
        $.ajax({
            type: "POST",
            url : base_url+'video-submission',
            data: $('#video-submit-form').serialize(),
            success: function (data) {
                data = JSON.parse(data);
                if(data.code == 204){
                    toastr.error(data.message);
                }else if(data.code == 201){
                    toastr.error(data.message);
                    $.each(data.error,function(i,v){
                        $('#'+i+'_err').html(v);
                    });

                }else if(data.code == 200){
                    //toastr.success(data.message);
                    $('#fm-block1').hide();
                    $('#fm-block2').show();
                    $('#li-block2').addClass('active');
                    location.reload();
                    /*setTimeout(function(){
                        if(slug.length > 0) {
                            window.location = data.url+"/"+slug;

                        }
                        else{
                            window.location = data.url;
                        }

                    },2000);*/
                }
                else{
                    toastr.error(data.message);
                }

            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });

    });


    $('#video-submit-form2').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset]'
    });

    $('#video-submit-form2').on('submit',function (e) {
        e.preventDefault();
        $('.error').html('');
        $('.preloadr-div').show();
        $.ajax({
            type: "POST",
            url : base_url+'video-submission',
            data: $('#video-submit-form2').serialize(),
            success: function (data) {
                data = JSON.parse(data);
                if(data.code == 204){
                    toastr.error(data.message);
                }else if(data.code == 201){
                    toastr.error(data.message);
                    $.each(data.error,function(i,v){
                        $('#'+i+'_err').html(v);
                    });

                }else if(data.code == 200){
                    //toastr.success(data.message);
                    $('#fm-block2').hide();
                    $('#fm-block3').show();
                    $('#li-block3').addClass('active');
                    location.reload();
                    /*setTimeout(function(){
                        if(slug.length > 0) {
                            window.location = data.url+"/"+slug;

                        }
                        else{
                            window.location = data.url;
                        }

                    },2000);*/
                }
                else{
                    toastr.error(data.message);
                }

            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });

    });

    $('#video-submit-form3').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset]'
    });

    $('#video-submit-form3').on('submit',function (e) {
        e.preventDefault();
        $('.error').html('');
        $('.preloadr-div').show();
        $.ajax({
            type: "POST",
            url : base_url+'video-submission',
            data: $('#video-submit-form3').serialize(),
            success: function (data) {
                data = JSON.parse(data);
                if(data.code == 204){
                    toastr.error(data.message);
                }else if(data.code == 201){
                    toastr.error(data.message);
                    $.each(data.error,function(i,v){
                        $('#'+i+'_err').html(v);
                    });

                }else if(data.code == 200){
                    //toastr.success(data.message);
                    $('#fm-block3').hide();
                    $('#fm-block4').show();
                    $('#li-block4').addClass('active');
                    location.reload();
                    /*setTimeout(function(){
                        if(slug.length > 0) {
                            window.location = data.url+"/"+slug;

                        }
                        else{
                            window.location = data.url;
                        }

                    },2000);*/
                }
                else{
                    toastr.error(data.message);
                }

            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });

    });

    /*$(document).on('change','#country_id',function () {
        var country = $(this).val();
        $('.preloadr-div').show();
        $.ajax({
            type: "POST",
            url : base_url+'get_states',
            data: {country_id:country},
            success: function (data) {
                data = JSON.parse(data);
                if(data.code == 200){
                    var html = '<option value="">Select State</option>'
                    $.each(data.states,function(i,v){
                        html += '<option value="'+v.id+'"';
                        if(state_id == v.id){
                            html += 'selected';
                        }
                        html += '>'+v.name+'</option>'
                    });
                    $('#state_id').html(html)
                }
                else{
                    toastr.error(data.message);
                }

            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });

    });*/
    /*$(document).on('change','#state_id',function () {
        var state = $(this).val();
        $('.preloadr-div').show();
        $.ajax({
            type: "POST",
            url : base_url+'get_cities',
            data: {state_id:state},
            success: function (data) {
                data = JSON.parse(data);
                if(data.code == 200){
                    var html = '<option value="">Select City</option>'
                    $.each(data.cities,function(i,v){
                        html += '<option value="'+v.id+'"';
                        if(city_id == v.id){
                            html += 'selected';
                        }
                        html += '>'+v.name+'</option>'
                    });
                    $('#city_id').html(html)
                }
                else{
                    toastr.error(data.message);
                }

            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });

    });*/
    /*if(country_id > 0){
        $('.preloadr-div').show();
        $.ajax({
            type: "POST",
            url : base_url+'get_states',
            data: {country_id:country_id},
            success: function (data) {
                data = JSON.parse(data);
                if(data.code == 200){
                    var html = '<option value="">Select State</option>'
                    $.each(data.states,function(i,v){
                        html += '<option value="'+v.id+'"';
                        if(state_id == v.id){
                            html += 'selected';
                        }
                        html += '>'+v.name+'</option>'
                    });
                    $('#state_id').html(html)
                }
                else{
                    toastr.error(data.message);
                }

            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });
    }
    if(state_id > 0){
        $('.preloadr-div').show();
        $.ajax({
            type: "POST",
            url : base_url+'get_cities',
            data: {state_id:state_id},
            success: function (data) {
                data = JSON.parse(data);
                if(data.code == 200){
                    var html = '<option value="">Select City</option>'
                    $.each(data.cities,function(i,v){
                        html += '<option value="'+v.id+'"';
                        if(city_id == v.id){
                            html += 'selected';
                        }
                        html += '>'+v.name+'</option>'
                    });
                    $('#city_id').html(html)
                }
                else{
                    toastr.error(data.message);
                }

            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });
    }*/

})(jQuery);

function delete_file(name) {
    $('.preloadr-div').show();
    $.ajax({
        type: "POST",
        url : base_url+'remove_file',
        dataType:'json',
        data: {file:name},
        success: function (data) {
            toastr.success('File remove successfully!');
        },
        error 	: function(){
            toastr.error('Something is going wrong!');
        }
    });
}