$(function () {
    'use strict';
    $('#datepicker').Zebra_DatePicker({
        direction: 1
    });

    $('.Zebra_DatePicker_Icon').css({'top':'20px', 'right' : '17px'});

    $('#license-form').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset]'
    });

      $('#submit').click(function (e) {
        e.preventDefault();
        $('.error').html('');
        $.ajax({
            type 	: "POST",
            url  	: base_url+"license-video",
            data    : $('#license-form').serialize(),
            success : function(data){
                data = JSON.parse(data);
                if(data.code == 201){
                    $.each(data.error, function (i, v) {
                        $('#' + i + '_err').html(v);
                    });
                }
                else if(data.code == 200){
                    toastr.success(data.message,{timeOut: 5000});
                    setTimeout(function(){
                        window.location = data.url;
                    },5000);
                }
            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });
    });

    $(document).on('click','#territory',function () {
        var val = $('#territory').val();
        if(val == 'National'){
            $('#country-div').show();
        }
        else{
            $('#country-div').hide();
            $('#country').val('');
        }

    });

    /*$(document).on('click','#time',function () {

        var val = $('#time').val();

        if(val == "othertime"){
            $('#calendar').show();
        }
        else {
            $('#calendar').hide();
        }

    });
    $(document).on('click','#license_type_id',function () {

       var val = $('#license_type_id').val();


       if(val == '2'){
           $('#internet').show();
       }
       else {
           $('#internet').hide();
           $('#page').hide();
           $('#channel').hide();
           $('#tweet').hide();
           $('#vemo').hide();
           $('#insta').hide();

           $('#social_media').prop('selectedIndex',0);
           $('input[name="youtube"]').val("");
           $('input[name="facebook"]').val("");
           $('input[name="twitter"]').val("");
           $('input[name="vimeo"]').val("");
           $('input[name="instagram"]').val("");
       }
    });
    $(document).on('click','#social_media',function () {

        $('input[name="youtube"]').val("");
        $('input[name="facebook"]').val("");
        $('input[name="twitter"]').val("");
        $('input[name="vimeo"]').val("");
        $('input[name="instagram"]').val("");

        var val1 = $('#social_media').val();
        if(val1 == 'youtube'){
            $('#channel').show();
            $('#page').hide();
            $('#tweet').hide();
            $('#vemo').hide();
            $('#insta').hide();
        }
        else if(val1 == 'facebook'){
            $('#page').show();
            $('#channel').hide();
            $('#tweet').hide();
            $('#vemo').hide();
            $('#insta').hide();
        }
        else if(val1 == 'vimeo'){
            $('#vemo').show();
            $('#page').hide();
            $('#channel').hide();
            $('#tweet').hide();
            $('#insta').hide();
        }
        else if(val1 == 'twitter'){
            $('#tweet').show();
            $('#vemo').hide();
            $('#page').hide();
            $('#channel').hide();
            $('#insta').hide();
        }
        else if(val1 == 'instagram'){
            $('#insta').show();
            $('#vemo').hide();
            $('#page').hide();
            $('#channel').hide();
            $('#tweet').hide();
        }
    });*/

})