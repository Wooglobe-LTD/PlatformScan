$(function () {
    'use strict';

    $('.datepicker').Zebra_DatePicker();
    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a profile picture here or click',
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
    var html = '<option value="">Select A State</option>';
   /* if(country_id.length > 0){
        $.ajax({
            type 	: "POST",
            url  	: base_url+"get_states",
            data    : {country_id:country_id},
            success : function(data){

                data = JSON.parse(data);
                if(data.code == 204){

                }else if(data.code == 201){

                    toastr.error(data.message);
                    $('#otp-form').parsley().reset();
                    $.each(data.error,function(i,v){
                        $('#'+i+'_err').html(v);
                    });

                }else if(data.code == 200){

                    if(data.states.length > 0){
                        $.each(data.states,function (i,v) {
                            if(v.id == state_id){
                                html += '<option selected="selected" value="'+v.id+'">'+v.name+'</option>';
                            }else {
                                html += '<option value="'+v.id+'">'+v.name+'</option>';
                            }

                        });
                    }
                    $('#state_id').html(html);

                }else{
                    toastr.error(data.message);
                }

            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });
    }else{
        $('#state_id').html(html);
    }*/
    var html1 = '<option value="">Select A City</option>';
   /* if(state_id.length > 0){
        $.ajax({
            type 	: "POST",
            url  	: base_url+"get_cities",
            data    : {state_id:state_id},
            success : function(data){
                data = JSON.parse(data);
                if(data.code == 204){

                }else if(data.code == 201){



                }else if(data.code == 200){

                    if(data.cities.length > 0){
                        $.each(data.cities,function (i,v) {
                            if(v.id == city_id){
                                html1 += '<option selected="selected" value="'+v.id+'">'+v.name+'</option>';
                            }else {
                                html1 += '<option value="'+v.id+'">'+v.name+'</option>';
                            }
                        });
                    }
                    $('#city_id').html(html1);

                }else{
                    toastr.error(data.message);
                }

            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });
    }else{
        $('#city_id').html(html1);
    }*/


    $('#country_id').change(function () {
        var country_id = $(this).val();
        var html = '<option value="">Select A State</option>';
        if(country_id.length > 0){
            $.ajax({
                type 	: "POST",
                url  	: base_url+"get_states",
                data    : {country_id:country_id},
                success : function(data){
                    data = JSON.parse(data);
                    if(data.code == 204){

                    }else if(data.code == 201){

                        toastr.error(data.message);
                        $('#otp-form').parsley().reset();
                        $.each(data.error,function(i,v){
                            $('#'+i+'_err').html(v);
                        });

                    }else if(data.code == 200){

                        if(data.states.length > 0){
                            $.each(data.states,function (i,v) {
                                html += '<option value="'+v.id+'">'+v.name+'</option>';
                            });
                        }
                        $('#state_id').html(html);
                        $('#country_code').val('+'+data.country.phonecode);

                    }else{
                        toastr.error(data.message);
                    }

                },
                error 	: function(){
                    toastr.error('Something is going wrong!');
                }
            });
        }else{
            $('#state_id').html(html);
            $('#country_code').val('');
        }

    })

    $('#state_id').change(function () {
        var state_id = $(this).val();
        var html = '<option value="">Select A City</option>';
        if(state_id.length > 0){
            $.ajax({
                type 	: "POST",
                url  	: base_url+"get_cities",
                data    : {state_id:state_id},
                success : function(data){
                    data = JSON.parse(data);
                    if(data.code == 204){

                    }else if(data.code == 201){



                    }else if(data.code == 200){

                        if(data.cities.length > 0){
                            $.each(data.cities,function (i,v) {
                                html += '<option value="'+v.id+'">'+v.name+'</option>';
                            });
                        }
                        $('#city_id').html(html);

                    }else{
                        toastr.error(data.message);
                    }

                },
                error 	: function(){
                    toastr.error('Something is going wrong!');
                }
            });
        }else{
            $('#city_id').html(html);
        }

    })


    $('#profile').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]'
    });

    $('#profile').on('submit',function (e) {

        e.preventDefault();
        var postData = new FormData($("#profile")[0]);
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
                    $('#profile').parsley().reset();
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