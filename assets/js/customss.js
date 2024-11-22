$(document).on('click','#viral_submit_button',function (e) {
    e.preventDefault();
    $('#appreance-submit-form').submit();
});
$('#appreance-submit-form').on('submit',function(e){
    var $sigdiv = $("#signature");

    var datapair = $sigdiv.jSignature("getData", "base30");
    var emptyimg='data:image/jsignature;base30,';
    var imgdata ='';
    var fileimg = '';
    if( datapair[1] == '' || datapair[0] == undefined || datapair[0] == emptyimg ) {
        fileimg = '';
    }else{
        var i = new Image();
        i.src = "data:" + datapair[0] + "," + datapair[1];
        imgdata = i;
        fileimg = imgdata.src
    }
    if($('.yes-button').hasClass("paypal-active")){
        yes_button_value = 1;
    }else{
        yes_button_value = 0;
    }
    if($('#video-link-button').hasClass("video-active")){
        video_link_value = 1;
    }else{
        video_link_value = 0;
    }
    var formdata = $('#viral-video-submit-form').serializeArray();

    if(yes_button_value > 0){
        formdata.push({name: "yespaypal", value: yes_button_value});
    }
    if(video_link_value > 0){
        formdata.push({name: "yeslink", value: video_link_value});
    }
    formdata.push({name: "img", value: fileimg});
    e.preventDefault();
    custom_preloader_show();
    $('.error').html('');
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: base_url + "submit_viral_video",
        data: formdata,
        success: function (data) {
            custom_preloader_hide();
            //data = JSON.parse(data);
            console.log(data);
            if(data.code == 204){
                toastr.error(data.message);
                setTimeout(function(){
                    window.location = data.url;
                },1000);
            }else if(data.code == 201){
                console.log(data.error);
                toastr.error(data.message);
                //$('#video-submit-contract-form').parsley().reset();
                $.each(data.error,function(i,v){
                    console.log(i);
                    $('#'+i+'_err').html(v);
                    if (i == 'fileuploader-list-file' && data.error[i] != '') {
                        $('html, body').stop(true,true).animate({
                            scrollTop: $('div#video-div').offset().top - 50
                        }, 1500);
                    }
                    if(i == 'video_single_url'){
                        $('#link_name_err').html(v);
                    }
                });
                toastr.error(data.error);
                $.each(data.error, function (i, v) {

                    $('#' + i + '_err').html(v);
                    if(i == 'video_single_url'){
                        $('#link_name_err').html(v);
                    }


                });

            }else if(data.code == 200){
                toastr.success(data.message);
                setTimeout(function(){
                    window.location = data.url;
                },1000);
            }
            else if (data.code == 403){
                altair_helpers.custom_preloader_hide();
                toastr.error(data.message);
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