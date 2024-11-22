/**
 * Created by Abdul Rehman Aziz on 4/26/2018.
 */
$(function () {



    $('#email_template').on('change',function () {

        var val = $(this).find(':selected').data('id');
        if(val == 2){

            $('#checkbox').show();
        }
        else{
            $('#checkbox').hide();
        }

    });



    $('#form_validation2').on('submit',function(e){

        e.preventDefault();
        var form = $('#form_validation2')[0];

        var data = new FormData(form);
        $.ajax({
            type 	: "POST",
            enctype: 'multipart/form-data',
            url  	: base_url+"send_email",
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
            url  	: base_url+"send_custom_email",
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



});