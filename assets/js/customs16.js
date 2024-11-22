$(document).ready(function () {

    //Header sticky
    $(window).scroll(function (e) {
        scrollFunction();
        var sticky = $('.header'),
            scroll = $(window).scrollTop();
        if (scroll > 100) {
            sticky.addClass('fixed');
            $('.hd-back').show();
        }

        else {
            sticky.removeClass('fixed');
            $('.hd-back').hide();
        }
    });
    if ($('#email').length > 0) {
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
    if ($(".accordion_container").length) {
        $(".accordion_head").click(function () {
            if ($(".accordion_body").not($(this).next(".accordion_body")).is(':visible')) {
                $(".accordion_head").removeClass("active");
                $(".accordion_body").hide("swing");
                $(".plusminus").text('+');
            }

            //if ($('.accordion_body').is(':visible')) {
            $(this).toggleClass("active");
            $(this).next(".accordion_body").slideToggle(300);
            if ($(this).hasClass('active')) { $(this).children(".plusminus").text('-'); } else { $(".plusminus").text('+'); }
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
    if ($(".page-search-form").length) {

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
        $(document).on('change', '#search_input', function () {
            $('.search-form').submit();
        });
    }

    //dashboard navigation active inactive system

    if ($(".sidebar_main").length) {
        $('#nav_status li').on('click', function () {
            $(this).parent().find('li.active').removeClass('active');
            $(this).addClass('active');
        });

        $('.sts_blk').on('click', function () {
            $('#info_status').show();
            $('#resale_status').hide();
        });

        //$('#resale_status').hide();

        $('.license_blk').on('click', function () {
            $('#resale_status').show();
            $('#info_status').hide();

        });
    }

    if ($(".form-control-chosen").length) {
        $('.form-control-chosen').chosen({
        });
    }

    if ($(".buy-video").length) {
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

    if ($(".territory.form-control").length || $(".time.form-control").length) {
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

    if ($("#gotoTopBtn")) {
        function scrollFunction() {
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                document.getElementById("gotoTopBtn").style.display = "block";
            } else {
                document.getElementById("gotoTopBtn").style.display = "none";
            }
        }

    }

});
$(document).ready(function () {
    fetch('https://api.ipregistry.co/?key=nstkoliyatmw1i5v')
        .then(function (response) {
            return response.json();
        })
        .then(function (payload) {
            $('#country_code').val('+' + payload.location.country.calling_code).trigger('chosen:updated');
            $('#city').val(payload.location.city);
            $('#state').val(payload.location.region.name);
            $("#country option[data-code='" + payload.location.country.code + "']").prop("selected", true).trigger('chosen:updated');
            $("#country").val(payload.location.country.name);
            $('#zip').val(payload.location.postal);
            //console.log(payload, payload.location.country.calling_code + ', ' + payload.location.city);
        });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            toastr.error("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        console.log("Latitude: " + position.coords.latitude +
            "<br>Longitude: " + position.coords.longitude);
        fetch('http://api.geonames.org/countrySubdivisionJSON?lat=' + position.coords.latitude + '&lng=' + position.coords.longitude + '&username=yasirdev89')
            .then(function (response) {
                return response.json();
            })
            .then(function (payload) {
                $("#country option[data-code='" + payload.countryCode + "']").prop("selected", true).trigger('chosen:updated');
                //console.log(payload, payload.location.country.calling_code + ', ' + payload.location.city);
            });

    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                console.log("User denied the request for Geolocation.")
                break;
            case error.POSITION_UNAVAILABLE:
                console.log("Location information is unavailable.")
                break;
            case error.TIMEOUT:
                console.log("The request to get user location timed out.")
                break;
            case error.UNKNOWN_ERROR:
                console.log("An unknown error occurred.")
                break;
        }
    }
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        // some code..
        //getLocation();
    }


    function custom_preloader_show() {
        $('.preloadr-div').show();
    }
    function custom_preloader_hide() {
        $('.preloadr-div').hide();
    }
    $(document).ajaxStart(function () {
        custom_preloader_show();
    });

    $(document).ajaxComplete(function () {
        custom_preloader_hide();
    });
    $('#video-submit-contract-form').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]'
    });
    $("#signature").jSignature({ lineWidth: 1, width: 390, height: 148 });
    $('.clear-button').on('click', function (e) {
        e.preventDefault();
        $('#signature').jSignature("reset");
    });
    $('#video-upload-button').on('click', function () {
        $('#link_add').hide();
        $('#video-div').show();
        $('#video-upload-button').addClass('video-active');
        $('#video-link-button').removeClass('video-active');
        $('#Yes_link_two').val(0);
        $('#link_name').prop('required', false);
    });
    $(document).on('click', '.video-upload-button', function () {
        $(this).parent().parent().find('.link_add').hide();
        $(this).parent().parent().find('.video-div').show();
        $(this).addClass('video-active');
        $(this).parent().find('.video-link-button').removeClass('video-active');
        $(this).parent().find('.yeslink').val(0);
    });
    $('#video-link-button').on('click', function () {
        $('#link_add').show();
        $('#video-div').hide();
        $('#video-upload-button').removeClass('video-active');
        $('#video-link-button').addClass('video-active');
        $('#Yes_link_two').val(1);
        $('#link_name').prop('required', true);
    });
    $(document).on('click', '.video-link-button', function () {

        $(this).parent().parent().find('.link_add').show();
        $(this).parent().parent().find('.video-div').hide();
        $(this).parent().find('.video-upload-button').removeClass('video-active');
        $(this).addClass('video-active');
        $(this).parent().find('.yeslink').val(1);
    });
    $('.no-button').on('click', function () {
        $('.payapal_info').hide();
        $('.no-button').addClass('paypal-active');
        $('.yes-button').removeClass('paypal-active');
    });
    $('.yes-button').on('click', function () {
        $('.payapal_info').show();
        $('.no-button').removeClass('paypal-active');
        $('.yes-button').addClass('paypal-active');
    });
    $('#myModalage').modal({
        backdrop: 'static'
    });
    $('#myModalage').modal('show');
    $('.launch-modal').click(function () {
        $('#myModalunder').modal({
            backdrop: 'static'
        });
        $('#myModalage').modal('hide');
    });

    //Old form function
    $(document).on('click', '#video-contract-form-submit', function (e) {
        e.preventDefault();
        $('#video-submit-contract-form').submit();
    });
    $('#video-submit-contract-form').on('submit', function (e) {
        var $sigdiv = $("#signature");

        var datapair = $sigdiv.jSignature("getData", "base30");
        var emptyimg = 'data:image/jsignature;base30,';
        var imgdata = '';
        var fileimg = '';
        if (datapair[1] == '' || datapair[0] == undefined || datapair[0] == emptyimg) {
            fileimg = '';
        } else {
            var i = new Image();
            i.src = "data:" + datapair[0] + "," + datapair[1];
            imgdata = i;
            fileimg = imgdata.src
        }
        if ($('.yes-button').hasClass("paypal-active")) {
            yes_button_value = 1;
        } else {
            yes_button_value = 0;
        }
        if ($('#video-link-button').hasClass("video-active")) {
            video_link_value = 1;
        } else {
            video_link_value = 0;
        }
        var formdata = $('#video-submit-contract-form').serializeArray();

        if (yes_button_value > 0) {
            formdata.push({ name: "yespaypal", value: yes_button_value });
        }
        /*if(video_link_value > 0){
            formdata.push({name: "yeslink", value: video_link_value});
        }*/
        formdata.push({ name: "img", value: fileimg });
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
                if (data.code == 204) {
                    toastr.error(data.message);
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                } else if (data.code == 201) {

                    toastr.error(data.message);
                    $('#video-submit-contract-form').parsley().reset();
                    $.each(data.error, function (i, v) {
                        $('#' + i + '_err').html(v);
                        if (i == 'fileuploader-list-file' && data.error[i] != '') {
                            $('html, body').stop(true, true).animate({
                                scrollTop: $('div#video-div').offset().top - 50
                            }, 1500);
                        }
                    });

                } else if (data.code == 200) {
                    toastr.success(data.message);
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                }
                else if (data.code == 403) {
                    altair_helpers.custom_preloader_hide();
                    toastr.error(data.message);
                }
                else {
                    toastr.error(data.message);
                }

            },
            error: function () {
                toastr.error('Something is going wrong!');
            }
        });

    });
    //New form function
    $(document).on('click', '#simple_submit_button', function (e) {
        e.preventDefault();
        $('#simple-video-submit-form').submit();
    });
    $('#simple-video-submit-form').on('submit', function (e) {
        var $sigdiv = $("#signature");

        var datapair = $sigdiv.jSignature("getData", "base30");
        var emptyimg = 'data:image/jsignature;base30,';
        var imgdata = '';
        var fileimg = '';
        if (datapair[1] == '' || datapair[0] == undefined || datapair[0] == emptyimg) {
            fileimg = '';
        } else {
            var i = new Image();
            i.src = "data:" + datapair[0] + "," + datapair[1];
            imgdata = i;
            fileimg = imgdata.src
        }

        if ($('#video-link-button').hasClass("video-active")) {
            video_link_value = 1;
        } else {
            video_link_value = 0;
        }
        var formdata = $('#simple-video-submit-form').serializeArray();

        /*if(video_link_value > 0){
            formdata.push({name: "yeslink", value: video_link_value});
        }*/
        formdata.push({ name: "img", value: fileimg });
        e.preventDefault();
        custom_preloader_show();
        $('.error').html('');
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: base_url + "simple_viral_video",
            data: formdata,
            success: function (data) {
                custom_preloader_hide();
                //data = JSON.parse(data);
                console.log(data);
                if (data.code == 204) {
                    toastr.error(data.message);
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                } else if (data.code == 201) {
                    //console.log(data.error);
                    toastr.error(data.message);
                    $('html, body').stop(true, true).animate({
                        scrollTop: $('body').offset().top - 50
                    }, 1500);
                    //$('#video-submit-contract-form').parsley().reset();
                    $.each(data.error, function (i, v) {
                        console.log(i);
                        $('#' + i + '_err').html(v);
                        /*if (i == 'fileuploader-list-file' && data.error[i] != '') {
                            $('html, body').stop(true,true).animate({
                                scrollTop: $('div#video-div').offset().top - 50
                            }, 1500);
                        }*/
                        if (i == 'video_single_url') {
                            $('#link_name_err').html(v);
                        }
                    });
                    // toastr.error(data.error);
                    $.each(data.error, function (i, v) {

                        $('#' + i + '_err').html(v);
                        if (i == 'video_single_url') {
                            $('#link_name_err').html(v);
                        }


                    });

                } else if (data.code == 200) {
                    toastr.success(data.message);
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                }
                else if (data.code == 403) {
                    altair_helpers.custom_preloader_hide();
                    toastr.error(data.message);
                }
                else {
                    toastr.error(data.message);
                }

            },
            error: function () {
                toastr.error('Something is going wrong!');
            }
        });

    });
    // $("#country").chosen({ no_results_text: "Oops, nothing found!" });
    $('#viral-video-submit-form').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]',
        // 'classHandler' : function(field){
        //     console.log(field);

        // },
        // errorsContainer: function (Field) {

        //     console.log('err')
        // },
    });
    // $(document).on('click','#viral_submit_button',function (e) {
    //     e.preventDefault();
    //     $('#viral-video-submit-form').parsley().reset();
    //     alert("Here");
    //     //return;
    //     //$('#viral-video-submit-form').submit();
    // });
    $('#viral-video-submit-form').on('submit', function (e) {
        var $sigdiv = $("#signature");
        var datapair = $sigdiv.jSignature("getData", "base30");
        var emptyimg = 'data:image/jsignature;base30,';
        var imgdata = '';
        var fileimg = '';
        if (datapair[1] == '' || datapair[0] == undefined || datapair[0] == emptyimg) {
            fileimg = '';
        } else {
            var i = new Image();
            i.src = "data:" + datapair[0] + "," + datapair[1];
            imgdata = i;
            fileimg = imgdata.src
        }
        if ($('.yes-button').hasClass("paypal-active")) {
            yes_button_value = 1;
        } else {
            yes_button_value = 0;
        }
        if ($('#video-link-button').hasClass("video-active")) {
            video_link_value = 1;
        } else {
            video_link_value = 0;
        }

        var formdata = $('#viral-video-submit-form').serializeArray();

        if (yes_button_value > 0) {
            formdata.push({ name: "yespaypal", value: yes_button_value });
        }
        /*if(video_link_value > 0){
                formdata.push({name: "yeslink", value: video_link_value});
        }*/
        formdata.push({ name: "img", value: fileimg });
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
                try {
                    data = JSON.parse(data);
                } catch (error) {

                }
                console.log(data.code);
                if (data.code == "204") {
                    toastr.error(data.message);
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                } else if (data.code == 201) {
                    //alert(data.code);
                    console.log(data);
                    //console.log(data.error);
                    //if(data.message.length)toastr.error(data.message);
                    // $('html, body').stop(true,true).animate({
                    //     scrollTop: $('body').offset().top - 50
                    // }, 1500);
                    //$('#video-submit-contract-form').parsley().reset();

                    for (let i in data.error) {
                        if (data.error[i] == '') continue;
                        let v = data.error[i];
                        if (i == 'img') {
                            toastr.error('Signature Field is required');
                        } if (i == '1_file') {
                            toastr.error('Please upload the original video');
                        }
                        $('#' + i + '_err').html(v).focus();
                        if (i == 'video_single_url') {
                            $('#link_name_err').html(v).focus();
                            $('html, body').stop(true, true).animate({
                                scrollTop: $('#link_name_err').offset().top - 50
                            }, 1500);
                        }

                        $('html, body').stop(true, true).animate({
                            scrollTop: $('#' + i + '_err').offset().top - 120
                        }, 1500);
                    }




                    // $.each(data.error,function(i,v){
                    //     if(v == '')return;
                    //     console.log(i);
                    //     if(i == 'img'){
                    //         toastr.error('Signature Field is required');
                    //     }if(i == 'i_file'){
                    //         toastr.error('Original Video is required');
                    //     }
                    //     $('#'+i+'_err').html(v).focus();
                    //     // if (i == 'fileuploader-list-file' && data.error[i] != '') {
                    //     //     $('html, body').stop(true,true).animate({
                    //     //         scrollTop: $('div#video-div').offset().top - 50
                    //     //     }, 1500);
                    //     // }
                    //     if(i == 'video_single_url'){
                    //         $('#link_name_err').html(v).focus();
                    //         $('html, body').stop(true,true).animate({
                    //             scrollTop: $('#link_name_err').offset().top - 50
                    //         }, 1500);
                    //     }
                    //     // $('html, body').stop(true,true).animate({
                    //     //     scrollTop: $('#'+i+'_err').offset().top - 120
                    //     // }, 1500);

                    // });
                    // toastr.error(data.error);
                    // $.each(data.error, function (i, v) {

                    //     $('#' + i + '_err').html(v);
                    //     if(i == 'video_single_url'){
                    //         $('#link_name_err').html(v);
                    //     }

                    // });

                } else if (data.code == 200) {
                    toastr.success(data.message);
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                } else if (data.code == 403) {
                    altair_helpers.custom_preloader_hide();
                    toastr.error(data.message);
                }
                else {
                    alert("Here");
                    toastr.error(data.message);
                }

            },
            error: function () {
                toastr.error('Something is going wrong!');
            }
        });

    });

    $(document).on('click', '#acquired-client-video-form-submit', function (e) {
        e.preventDefault();
        $('#acquired-client-video-form').submit();
    });
    $('#acquired-client-video-form').on('submit', function (e) {

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
                if (data.code == 204) {
                    toastr.error(data.message);
                    setTimeout(function () {
                        window.location = data.url;
                    }, 3000);
                } else if (data.code == 201) {

                    toastr.error(data.message);
                    $('#sign-in-form').parsley().reset();
                    $.each(data.error, function (i, v) {
                        $('#' + i + '_err').html(v);
                    });

                } else if (data.code == 200) {
                    toastr.success(data.message);
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                }
                else if (data.code == 403) {
                    altair_helpers.custom_preloader_hide();
                    toastr.error(data.message);
                }
                else {
                    toastr.error(data.message);
                }

            },
            error: function () {
                toastr.error('Something is going wrong!');
            }
        });

    });

    window.Parsley.on('field:success', function (fieldInstance) {
        console.log('success');
        var element = fieldInstance.$element;
        $(element).removeClass('error-fi check-err');
    });

    window.Parsley.on('field:error', function (fieldInstance) {
        console.log("error", fieldInstance);
        var element = fieldInstance.$element;
        var element_id = element.attr('id');
        if (element_id == 'country_code') {

            if ($('#country_code_chose').length) {
                $('#country_code_chosen')[0].scrollIntoView({
                    behavior: "instant", // or "auto" or "instant"
                    block: "center" // or "end"
                });
                $('#country_code_chosen')[0].focus({ preventScroll: true });
                $(document).scrollTop(0);
            } else {
                $('#country_code_err')[0].scrollIntoView({
                    behavior: "smooth", // or "auto" or "instant"
                    block: "end"
                });
                $('#country_code_err')[0].focus({ preventScroll: true });
                $(document).scrollTop(0);
            }
        }
        if (element_id == 'question1' || element_id == 'question3' || element_id == 'question4') {
            $('#collapseTwo').collapse('show');
        }
        fieldInstance.__class__ == 'FieldMultiple' ? $(element).addClass('check-err') : $(element).addClass('error-fi');
    });
    $(document).on('click', '#appreance-submit-button', function (e) {
        e.preventDefault();
        $('#vviral-video-submit-form').submit();
    });
    $('#vviral-video-submit-form').on('submit', function (e) {
        var $sigdiv = $("#signature");

        var datapair = $sigdiv.jSignature("getData", "base30");
        var emptyimg = 'data:image/jsignature;base30,';
        var imgdata = '';
        var fileimg = '';
        if (datapair[1] == '' || datapair[0] == undefined || datapair[0] == emptyimg) {
            fileimg = '';
        } else {
            var i = new Image();
            i.src = "data:" + datapair[0] + "," + datapair[1];
            imgdata = i;
            fileimg = imgdata.src
        }
        if ($('.yes-button').hasClass("paypal-active")) {
            yes_button_value = 1;
        } else {
            yes_button_value = 0;
        }
        if ($('#video-link-button').hasClass("video-active")) {
            video_link_value = 1;
        } else {
            video_link_value = 0;
        }
        var formdata = $('#vviral-video-submit-form').serializeArray();

        if (yes_button_value > 0) {
            formdata.push({ name: "yespaypal", value: yes_button_value });
        }
        /*if(video_link_value > 0){
            formdata.push({name: "yeslink", value: video_link_value});
        }*/
        formdata.push({ name: "img", value: fileimg });
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
                if (data.code == 204) {
                    toastr.error(data.message);
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                } else if (data.code == 201) {
                    //console.log(data.error);
                    toastr.error(data.message);
                    $('html, body').stop(true, true).animate({
                        scrollTop: $('body').offset().top - 50
                    }, 1500);
                    //$('#video-submit-contract-form').parsley().reset();
                    $.each(data.error, function (i, v) {
                        if (i == 'img') {
                            toastr.error('Signature Field is required');
                        } if (i == 'i_file') {

                            toastr.error('Please upload the original video');

                        }
                        //console.log(i);
                        $('#' + i + '_err').html(v);
                        /*if (i == 'fileuploader-list-file' && data.error[i] != '') {
                            $('html, body').stop(true,true).animate({
                                scrollTop: $('div#video-div').offset().top - 50
                            }, 1500);
                        }*/
                        if (i == 'video_single_url') {
                            $('#link_name_err').html(v);
                        }
                    });
                    // toastr.error(data.error);
                    $.each(data.error, function (i, v) {

                        $('#' + i + '_err').html(v);
                        if (i == 'video_single_url') {
                            $('#link_name_err').html(v);
                        }
                    });

                } else if (data.code == 200) {
                    toastr.success(data.message);
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                }
                else if (data.code == 403) {
                    altair_helpers.custom_preloader_hide();
                    toastr.error(data.message);
                }
                else {
                    toastr.error(data.message);
                }

            },
            error: function () {
                toastr.error('Something is going wrong!');
            }
        });

    });
    function makeid(length) {
        var result = [];
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result.push(characters.charAt(Math.floor(Math.random() *
                charactersLength)));
        }
        return result.join('');
    }
    $(document).on('click', '#add-new-video-html', function (e) {
        var counter = $('.videos-data-div').length;
        counter = parseInt(parseInt(counter) + 1);
        counter2 = parseInt(parseInt(counter) + 1);
        var uid = makeid(10);
        $('#video-html').find('.video-title').attr('data-target', '#collapseTwo-' + counter);
        $('#video-html').find('.video-title').attr('aria-controls', 'collapseTwo-' + counter);
        $('#video-html').find('.video-body').attr('id', 'collapseTwo-' + counter);
        $('#video-html').find('.link_name_cls').attr('id', counter + '_link_name_err');
        $('#video-html').find('.video_title_cls').attr('id', counter + '_video_title_err');
        $('#video-html').find('.question4_cls').attr('id', counter + '_question4_err');
        $('#video-html').find('.question3_cls').attr('id', counter + '_question3_err');
        $('#video-html').find('.question1_cls').attr('id', counter + '_question1_err');
        $('#video-html').find('.video_single_url_cls').attr('id', counter + '_video_single_url_err');
        $('#video-html').find('.video-div').html('<input type="file" name="files" style="display: none;">\n' +
            '                        <div class="error" id="' + counter + '_file_err"></div><input type="hidden" name="uid_mul[videos][]" value="' + uid + '">')
        $('#new-video-html').append($('#video-html').html());
        uploder_plugin(uid);
        $('.question3').dcalendarpicker({ format: 'mm/dd/yyyy', maxdate: 'today' });
    });
    $(document).on('click', '.cross-outer', function (e) {
        $(this).parent().remove();
    });

    $(document).on('click', '#guardian_submit_button', function (e) {
        e.preventDefault();
        $('#gviral-video-submit-form').submit();
    });
    $('#gviral-video-submit-form').on('submit', function (e) {
        var $sigdiv = $("#signature");

        var datapair = $sigdiv.jSignature("getData", "base30");
        var emptyimg = 'data:image/jsignature;base30,';
        var imgdata = '';
        var fileimg = '';
        if (datapair[1] == '' || datapair[0] == undefined || datapair[0] == emptyimg) {
            fileimg = '';
        } else {
            var i = new Image();
            i.src = "data:" + datapair[0] + "," + datapair[1];
            imgdata = i;
            fileimg = imgdata.src
        }
        if ($('.yes-button').hasClass("paypal-active")) {
            yes_button_value = 1;
        } else {
            yes_button_value = 0;
        }
        if ($('#video-link-button').hasClass("video-active")) {
            video_link_value = 1;
        } else {
            video_link_value = 0;
        }
        var formdata = $('#gviral-video-submit-form').serializeArray();

        if (yes_button_value > 0) {
            formdata.push({ name: "yespaypal", value: yes_button_value });
        }
        /*if(video_link_value > 0){
            formdata.push({name: "yeslink", value: video_link_value});
        }*/
        formdata.push({ name: "img", value: fileimg });
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
                if (data.code == 204) {
                    toastr.error(data.message);
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                } else if (data.code == 201) {
                    //console.log(data.error);
                    toastr.error(data.message);
                    $('html, body').stop(true, true).animate({
                        scrollTop: $('body').offset().top - 50
                    }, 1500);
                    //$('#video-submit-contract-form').parsley().reset();
                    $.each(data.error, function (i, v) {
                        console.log(i);
                        $('#' + i + '_err').html(v);
                        /*if (i == 'fileuploader-list-file' && data.error[i] != '') {
                            $('html, body').stop(true,true).animate({
                                scrollTop: $('div#video-div').offset().top - 50
                            }, 1500);
                        }*/
                        if (i == 'video_single_url') {
                            $('#link_name_err').html(v);
                        }
                    });
                    // toastr.error(data.error);
                    $.each(data.error, function (i, v) {

                        $('#' + i + '_err').html(v);
                        if (i == 'video_single_url') {
                            $('#link_name_err').html(v);
                        }


                    });

                } else if (data.code == 200) {
                    toastr.success(data.message);
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                }
                else if (data.code == 403) {
                    altair_helpers.custom_preloader_hide();
                    toastr.error(data.message);
                }
                else {
                    toastr.error(data.message);
                }

            },
            error: function () {
                toastr.error('Something is going wrong!');
            }
        });

    });

});
function goUp() {
    $('html,body').animate({ scrollTop: 0 }, 'slow');
    return false;
}