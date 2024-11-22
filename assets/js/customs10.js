$(document).ready(function() {

    //Header sticky
    $(window).scroll(function() {
        scrollFunction();
        var sticky = $('.header'),
            scroll = $(window).scrollTop();
        if (scroll > 100)
        {
            sticky.addClass('fixed');
            $('.hd-back').show();
        }

        else {
            sticky.removeClass('fixed');
            $('.hd-back').hide();
        }
    });
    if($('#email').length > 0){
        /*jQuery('#email').parsley().addAsyncValidator(
            'remotevalidator', function (xhr) {
                var email = $('#email').parsley();
                var error_name = 'multiple_inn_kpp';
                window.ParsleyUI.removeError(email,'error_name');
                var message_to_display = "";

                if(xhr.status == '200'){
                    response = $.parseJSON(xhr.responseText);
                    if(response == "error"){
                        message_to_display = "This User Email is block.if you want to and ublock. Please <a href=\"javascript:void(0)\" class=\"block_request\">click here</a>";
                    }else if(response === "success"){
                        return 200;
                    }
                }
                if(xhr.status == '404'){
                    message_to_display = "Account already created on this email please <a href=\"/login\">login</a> first.";
                }

                //response = $.parseJSON(xhr.responseText);
                window.ParsleyUI.addError(email,'error_name',message_to_display);

            }, ''
        );*/
    }

    //toggle the component with class accordion_body
    if ($(".accordion_container").length){
    $(".accordion_head").click(function() {
        if ($(".accordion_body").not($(this).next(".accordion_body")).is(':visible')) {
            $(".accordion_head").removeClass("active");
            $(".accordion_body").hide("swing");
            $(".plusminus").text('+');
        }

        //if ($('.accordion_body').is(':visible')) {
        $(this).toggleClass("active");
        $(this).next(".accordion_body").slideToggle(300);
        if ($(this).hasClass('active')){$(this).children(".plusminus").text('-');}else{$(".plusminus").text('+');}
        //$(".plusminus").text('+');
        // }
        /*if ($(this).next(".accordion_body").is(':visible')) {

          $(this).next(".accordion_body").slideUp(300);
          $(this).children(".plusminus").text('+');
        } else {

          $(this).next(".accordion_body").slideDown(300);
          $(this).children(".plusminus").text('-');
          $(this).addClass("active");
        }*/
    });

    }

    //Smart Search
    if($(".page-search-form").length){

    $('#search_input').autocompleter({
        // marker for autocomplete matches
        highlightMatches: true,
        changeWhenSelect: true,
        // object to local or url to remote search
        source: tags,

        // show hint
        hint: false,

        // abort source if empty field
        empty: false,
        // max results
        limit: 10,

        callback: function (value, index, selected) {
            if (selected) {
                $('.icon').css('background-color', selected.hex);
            }
        }
    });
    $(document).on('change','#search_input',function(){
        $('.search-form').submit();
    });
    }

    //dashboard navigation active inactive system

    if($(".sidebar_main").length){
        $( '#nav_status li' ).on( 'click', function() {
            $( this ).parent().find( 'li.active' ).removeClass( 'active' );
            $( this ).addClass( 'active' );
        });

        $( '.sts_blk' ).on( 'click', function() {
            $('#info_status').show();
            $('#resale_status').hide();
        });

        //$('#resale_status').hide();

        $( '.license_blk' ).on( 'click', function() {
            $('#resale_status').show();
            $('#info_status').hide();

        });
    }

    if($(".form-control-chosen").length){
    $('.form-control-chosen').chosen({
    });
    }

    if($(".buy-video").length){
        /*(function($) {
            'use strict';

            if(typeof jQuery === "undefined") {
                /!*console.log('jquery.checkboxall plugin needs the jquery plugin');*!/
                return false;
            }

            $.fn.checkboxall = function(allSelector) {

                if (allSelector === undefined) {
                    allSelector =   'all';
                }

                var parent  =   this;

                if ($('.' + allSelector, parent).length) {
                    var all             =   $('.' + allSelector, parent),
                        checkbox        =   parent.find('input[type="checkbox"]'),
                        childCheckbox   =   checkbox.not('.' + allSelector, parent);

                    return checkbox
                        .unbind('click')
                        .click(function(event) {
                            event.stopPropagation();

                            var th  =   $(this);

                            if (th.hasClass(allSelector)) {
                                checkbox.prop('checked', th.prop('checked'));
                            }
                            else {
                                if (childCheckbox.length !== childCheckbox.filter(':checked').length) {
                                    all.prop('checked', false);
                                }
                                else {
                                    all.prop('checked', true);
                                }
                            }
                        });
                }
                else {
                    console.log('jquery.checkboxall error: main selector is not exists.');
                    console.log('Please add \'all\' class to first checkbox or give the first checkbox a class name and enter the checkboxall() functions for the class name!');
                    console.log('Example: $(selector).checkboxall(\'your-checkbox-class-name\');');
                }
            };
        }(jQuery));*/
    }

    if($(".territory.form-control").length || $(".time.form-control").length)
    {
        var open = false;
        $("select.form-control").on("click", function () {
            open = !open;
            $(this).toggleClass("open");

        });
        $("select.form-control").on("blur", function () {
            if (open) {
                open = !open;
                $(this).toggleClass("open");
            }
        });
        $(document).keyup(function (e) {
            if (e.keyCode == 27) {
                if (open) {
                    open = !open;

                    $("select.form-control").removeClass("open");
                }
            }
        });
    }

    if($("#gotoTopBtn")){
        function scrollFunction() {
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                document.getElementById("gotoTopBtn").style.display = "block";
            } else {
                document.getElementById("gotoTopBtn").style.display = "none";
            }
        }

    }

});
$(document).ready(function() {
    function custom_preloader_show  (){
        $('.preloadr-div').show();
    }
    function custom_preloader_hide(){
        $('.preloadr-div').hide();
    }
    $( document ).ajaxStart(function(){
        custom_preloader_show();
    });

    $( document ).ajaxComplete(function() {
        custom_preloader_hide();
    });
    $('#video-submit-contract-form').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]'
    });
    $("#signature").jSignature({ lineWidth: 1, width: 390, height: 148 });
    $('.clear-button').on('click', function(e) {
        e.preventDefault();
        $('#signature').jSignature("reset");
    });
    $('#video-upload-button').on('click', function(){
         $('#link_add').hide();
         $('#video-div').show();
        $('#video-upload-button').addClass('video-active');
        $('#video-link-button').removeClass('video-active');
    });
    $('#video-link-button').on('click', function(){
        $('#link_add').show();
        $('#video-div').hide();
         $('#video-upload-button').removeClass('video-active');
        $('#video-link-button').addClass('video-active');
    });
    $('.no-button').on('click', function(){
        $('.payapal_info').hide();
        $('.no-button').addClass('paypal-active');
        $('.yes-button').removeClass('paypal-active');
    });
    $('.yes-button').on('click', function(){
        $('.payapal_info').show();
         $('.no-button').removeClass('paypal-active');
        $('.yes-button').addClass('paypal-active');
    });
     $('#myModalage').modal({
            backdrop: 'static'
        });
        $('#myModalage').modal('show');
   $('.launch-modal').click(function(){
        $('#myModalunder').modal({
            backdrop: 'static'
        });
         $('#myModalage').modal('hide');
    });

//Old form function
    $(document).on('click','#video-contract-form-submit',function (e) {
        e.preventDefault();
        $('#video-submit-contract-form').submit();
    });
    $('#video-submit-contract-form').on('submit',function(e){
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
        var formdata = $('#video-submit-contract-form').serializeArray();

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
            url: base_url + "video_signed_contract",
            data: formdata,
            success: function (data) {
                custom_preloader_hide();
                data = JSON.parse(data);
                if(data.code == 204){
                    toastr.error(data.message);
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else if(data.code == 201){

                    toastr.error(data.message);
                    $('#video-submit-contract-form').parsley().reset();
                    $.each(data.error,function(i,v){
                        $('#'+i+'_err').html(v);
                        if (i == 'fileuploader-list-file' && data.error[i] != '') {
                            $('html, body').stop(true,true).animate({
                                scrollTop: $('div#video-div').offset().top - 50
                            }, 1500);
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
//New form function
    $(document).on('click','#viral_submit_button',function (e) {
        e.preventDefault();
        $('#viral-video-submit-form').submit();
    });
    $('#viral-video-submit-form').on('submit',function(e){
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
                    //console.log(data.error);
                    toastr.error(data.message);
                    $('html, body').stop(true,true).animate({
                        scrollTop: $('body').offset().top - 50
                    }, 1500);
                    //$('#video-submit-contract-form').parsley().reset();
                    $.each(data.error,function(i,v){
                        console.log(i);
                        $('#'+i+'_err').html(v);
                        /*if (i == 'fileuploader-list-file' && data.error[i] != '') {
                            $('html, body').stop(true,true).animate({
                                scrollTop: $('div#video-div').offset().top - 50
                            }, 1500);
                        }*/
                        if(i == 'video_single_url'){
                            $('#link_name_err').html(v);
                        }
                    });
                   // toastr.error(data.error);
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

$(document).on('click','#acquired-client-video-form-submit',function (e) {
    e.preventDefault();
    $('#acquired-client-video-form').submit();
});
$('#acquired-client-video-form').on('submit',function(e){

    var formdata = $('#acquired-client-video-form').serializeArray();
    console.log(formdata);
    e.preventDefault();
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: base_url + "acquired_client_video_submit",
        data: formdata,
        success: function (data) {
            data = JSON.parse(data);
            if(data.code == 204){
                toastr.error(data.message);
                setTimeout(function(){
                    window.location = data.url;
                },3000);
            }else if(data.code == 201){

                toastr.error(data.message);
                $('#sign-in-form').parsley().reset();
                $.each(data.error,function(i,v){
                    $('#'+i+'_err').html(v);	
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

	window.Parsley.on('field:error', function (fieldInstance) {
		var element = fieldInstance.$element;
		var element_id = element.attr('id');
		
		if (element_id == 'country_code') {
			element = $('#country_code_chosen');
		}		

		$('html, body').stop(true,true).animate({
			scrollTop: element.offset().top - 150
		}, 1500);	
		
		if (element_id == 'question1' || element_id == 'question3' || element_id == 'question4') {
			$('#collapseTwo').collapse('show');				
		}	
	});
    $(document).on('click','#appreance-submit-button',function (e) {
        e.preventDefault();
        $('#vviral-video-submit-form').submit();
    });
    $('#vviral-video-submit-form').on('submit',function(e){
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
        var formdata = $('#vviral-video-submit-form').serializeArray();

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
            url: base_url + "appearance_release_ajax",
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
                    //console.log(data.error);
                    toastr.error(data.message);
                    $('html, body').stop(true,true).animate({
                        scrollTop: $('body').offset().top - 50
                    }, 1500);
                    //$('#video-submit-contract-form').parsley().reset();
                    $.each(data.error,function(i,v){
                        console.log(i);
                        $('#'+i+'_err').html(v);
                        /*if (i == 'fileuploader-list-file' && data.error[i] != '') {
                            $('html, body').stop(true,true).animate({
                                scrollTop: $('div#video-div').offset().top - 50
                            }, 1500);
                        }*/
                        if(i == 'video_single_url'){
                            $('#link_name_err').html(v);
                        }
                    });
                    // toastr.error(data.error);
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
    $(document).on('click','#guardian_submit_button',function (e) {
        e.preventDefault();
        $('#gviral-video-submit-form').submit();
    });
    $('#gviral-video-submit-form').on('submit',function(e){
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
        var formdata = $('#gviral-video-submit-form').serializeArray();

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
            url: base_url + "second_signer_ajax",
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
                    //console.log(data.error);
                    toastr.error(data.message);
                    $('html, body').stop(true,true).animate({
                        scrollTop: $('body').offset().top - 50
                    }, 1500);
                    //$('#video-submit-contract-form').parsley().reset();
                    $.each(data.error,function(i,v){
                        console.log(i);
                        $('#'+i+'_err').html(v);
                        /*if (i == 'fileuploader-list-file' && data.error[i] != '') {
                            $('html, body').stop(true,true).animate({
                                scrollTop: $('div#video-div').offset().top - 50
                            }, 1500);
                        }*/
                        if(i == 'video_single_url'){
                            $('#link_name_err').html(v);
                        }
                    });
                    // toastr.error(data.error);
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
	
});
function goUp() {
    $('html,body').animate({ scrollTop: 0 }, 'slow');
    return false;
}