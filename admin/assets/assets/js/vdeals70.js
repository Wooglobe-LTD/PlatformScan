
var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);
var statusAdd;
$(function() {

    partner_story();
    $('#add_to_white').click(function(e){
        e.preventDefault();

        var name = $(this).data('name');
        var link = $(this).data('link');

        $.ajax({
            type: "POST",
            url: base_url + 'add_to_white',
            data: {name:name,link:link},
            success: function (data) {
                altair_helpers.custom_preloader_hide();
                data = JSON.parse(data);
                Swal.fire({
                    icon: 'success',
                    title: 'Whitelist',
                    text: 'Channel Added To Whitelis.',
                });


            }
        });
    });

    $('.download-raw-files').click(function(e){
        e.preventDefault();

        var vid = $(this).attr('vid');
        var direct_url = $(this).attr('href');

        $.ajax({
            type: "POST",
            url: base_url + 'check-download-filesize',
            data: {vid:vid},
            success: function (data) {
                altair_helpers.custom_preloader_hide();
                data = JSON.parse(data);
                console.log(data.type);
                if (data.type == 'direct') {
                    window.location = direct_url;
                }
                else if (data.type == 'email') {

                    Swal.fire({
                        icon: 'success',
                        title: 'Check Email Shortly',
                        text: 'Your download link will be sent through email when your file is ready!',
                    });

                    if (data.cmd != '') {
                        $.ajax({
                            type: "POST",
                            url: base_url + 'Video_Rights/ajax_create_zip_request',
                            data: {cmd: data.cmd},
                            success: function (data) {
                                console.log(data);
                                //data = JSON.parse(data);
                            },
                            error: function () {
                                altair_helpers.custom_preloader_hide();
                            },
                            timeout: function () {
                                altair_helpers.custom_preloader_hide();
                            }
                        });
                    }
                }
            }
        });
    });
    /* MRSS categories : S */

    var clear_partners_specfiic_feeds = function () {

        var video_id = $('#lead-video-id').val();
        $.ajax({
            type: 'GET',
            url: base_url + 'Video_Rights/clear_all_partner_feeds/'+video_id,
            async: false,
            success: function (data) {
                //data = JSON.parse(data);
            }
        });


    }

    var show_hide_mrss = function () {
        var val = $('#allow-mrss').val();
        var mpc = $('#mrss-partner-container');
        if (val == 'yes') {
            mpc.show();
        }
        else {
            mpc.hide();
        }
    };


    $('#is_mrss').on('ifChecked', function () {
        $('#mrss_id').show();
    });

    $('#is_mrss').on('ifUnchecked', function () {
        $('#mrss_id').hide();
    });

    var change_partner_categories = function () {

        $('#mrss-video-categories').find('option').not('option:first').remove().trigger('chosen:updated');

        var partner_id           = $('#exclusive-partners-list').val();
        var is_partner_exclusive = $('#is-partner-exclusive').val();

        if (partner_id != '' && is_partner_exclusive != '') {

            var dataarray = [];
            var select_options = '';
            $.ajax({
                type: 'POST',
                url: base_url + 'mrss_partner',
                data: {id: partner_id},
                async: false,
                success: function (data) {
                    data = JSON.parse(data);

                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();

                    } else if (data.code == 200) {
                        dataarray = data.data;
                        console.log(dataarray);
                        var option_ids = [];
                        for (i = 0; i < dataarray.length; i++) {
                            if (option_ids.indexOf(dataarray[i].id) == -1) {
                                select_options += '<option value="'+dataarray[i].id+'" pid="'+dataarray[i].partner_id+'">'+dataarray[i].url+'</option>';
                            }
                        }

                        $('#mrss-video-categories').append(select_options);

                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }
                }
            });
        }
        else {

            if (is_partner_exclusive == '') {
                $('#exclusive-partners-list').val('').trigger('chosen:updated');
            }
        }

    }

    // ununsed func
    var change_general_categories = function () {

        $('#mrss-video-categories').find('option').not('option:first').remove();

        var dataarray = [];
        var select_options = '';
        $.ajax({
            type: 'GET',
            url: base_url + 'ajax-list-gen-mrss-categories',
            //async: false,
            success: function (data) {
                data = JSON.parse(data);

                if (data.code == 200) {
                    dataarray = data.list;console.log(dataarray);

                    //return 0;
                    for (i = 0; i < dataarray.length; i++) {
                        select_options += '<option value="'+dataarray[i].id+'" pid="'+dataarray[i].partner_id+'">'+dataarray[i].url+'</option>';
                    }

                    $('#mrss-video-categories').append(select_options);
                }
            }
        });

    };

    /* fetch partner categories */

    var selected_partners_list = [];
    var selected_categories_list = [];
    var selected_categories_pid_list = []; // the list which contains partners ids of selected categories

    var switch_partners_single_multiple = function () {
        var is_exclusive = $('#is-partner-exclusive').val();

        // yes
        if (is_exclusive == '1') {
            // show single dropdown
            $('#exclusive-partners-list').removeAttr('multiple');

            $('#exclusive-partners-list').show();
            //$('#exclusive_partners_list_chosen').show();
            $('#mrss-video-categories').show();
            //$('#mrss_video_categories_chosen').show();

            change_partner_categories();
        } // show multiple
        else if (is_exclusive == '2') {
            $('#exclusive-partners-list').prop('multiple', true);

            $('#exclusive-partners-list').show(); // don't show when chosen plugin is applied
            $('#mrss-video-categories').show();   // don't show when chosen plugin is applied

            //change_partner_categories();
        }
        else {
            $('#exclusive-partners-list').hide();
            //$('#exclusive_partners_list_chosen').hide();    // chosen plugin container hide
            $('#mrss-video-categories').hide();
            //$('#mrss_video_categories_chosen').hide();      // chosen plugin container hide

            //change_general_categories();
        }
    }

    function initiliaze_mrss () {
        show_hide_mrss();

        $('#allow-mrss').val($('#allow-mrss-def-val').attr('value')).trigger('change');
        $('#is-partner-exclusive').val($('#partnership-type-def-val').attr('value')).trigger('change');

        var default_partner_selected_categories_values = [];
        var default_partner_selected_categories_names = [];
        var default_general_selected_categories_values = [];
        var default_general_selected_categories_names = [];
        var partners_name = [];
        var categories_to_show;

        var partners_info = $('#partners-def-val').attr('value');

        if (partners_info != '') {
            partners_info = JSON.parse(partners_info);
            for (i = 0; i < Object.keys(partners_info).length; i++) {
                $('#exclusive-partners-list option[value="'+partners_info[i].id+'"]').prop('selected', true);
                default_partner_selected_categories_values.push(partners_info[i].feed_id);
                partners_name.push(partners_info[i].full_name);
                default_partner_selected_categories_names.push(partners_info[i].url);
            }

            $('#exclusive-partners-list option[value=""]').prop('selected', false);

            $('#partners-name-preview').text(partners_name.join());
            $('#partner-categories-preview').text(default_partner_selected_categories_names.join());
        }


        var general_categories = $('#selected-general-categories-def-val').attr('value');

        if (general_categories != '') {
            general_categories = JSON.parse(general_categories);
            for (i = 0; i < Object.keys(general_categories).length; i++) {
                default_general_selected_categories_values.push(general_categories[i].id);
                default_general_selected_categories_names.push(general_categories[i].title);
            }
            $('#general-categories-preview').text(default_general_selected_categories_names.join());
        }

        change_partner_categories();

        // set delay to allow options to load from AJAX
        setTimeout(function(){
            for (i = 0; i < default_partner_selected_categories_values.length; i++) {
                $('#mrss-video-categories option[value="'+default_partner_selected_categories_values[i]+'"]').prop('selected', true);
            }

            //$('#mrss-video-categories').trigger('chosen:updated');

            for (i = 0; i < default_general_selected_categories_values.length; i++) {
                $('#general-categories option[value="'+default_general_selected_categories_values[i]+'"]').prop('selected', true);
            }

            //$('#general-categories').trigger('chosen:updated');

        },1500);

    }

    function reset_categories_list () {
        selected_partners_list = [];
        selected_categories_list = [];
        selected_categories_pid_list = [];
    }

    $('#allow-mrss').change(function(){
        show_hide_mrss();
    });


    $('#is-partner-exclusive').change(function(){
        console.log('change');
        change_partner_categories();
        switch_partners_single_multiple();
        reset_categories_list();
    });

    $('#update-exclusive-status').click(function(){

        var is_exclusive            = $('#is-partner-exclusive').val();
        var exclusive_partners_list = $('#exclusive-partners-list').val();
        var mrss_video_categories   = $('#mrss-video-categories').val();
        var general_categories      = $('#general-categories').val();
        var video_id                = $('#lead-video-id').val();
        var allow_mrss              = $('#allow-mrss').val();
        var s3_video                = $('#portal_video').val();
        var s3_thumb                = $('#portal_thumb').val();
        var video_cats              = $('#video_cats').val();
        var video_tags              = $('#video_tags').val();

        console.log('is exclusive '+is_exclusive);
        console.log(is_exclusive);
        console.log('exclusive partners list');
        console.log(exclusive_partners_list);
        console.log('mrss video categories ');
        console.log(mrss_video_categories);
        console.log('video tags ');
        console.log(video_tags);
        console.log('video cats ');
        console.log(video_cats);


        var ajax_info = {};
        if(s3_video != null  && s3_thumb != null && video_cats != null && video_tags != null ) {
            if (allow_mrss == 'yes') {

                if (is_exclusive == '1' || is_exclusive == '2') {

                    if (exclusive_partners_list == null || exclusive_partners_list == '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'No Parnter specified...',
                            text: 'Select exclusive partners from the partner drop downlist!',
                        });

                        return 0;
                    }

                    if (mrss_video_categories == null) {
                        Swal.fire({
                            icon: 'error',
                            title: 'No Category specified...',
                            text: 'Select categories from the category drop downlist!',
                        });

                        return 0;
                    }

                    if (is_exclusive == '2') {

                        console.log('selected partners list ');
                        console.log(selected_partners_list);
                        console.log('selected categories partner list ');
                        console.log(selected_categories_pid_list);

                        for (i = 0; i < selected_partners_list.length; i++) {

                            if (!selected_categories_pid_list.includes(selected_partners_list[i])) {
                                console.log('Executing validation ');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Select category for each partner',
                                    text: 'You have to select at least one category for each partner',
                                });

                                return 0;
                            }
                        }
                    }

                }

                ajax_info['url'] = 'update-vi-mrss-info';
                ajax_info['data'] = {
                    'is_exclusive': is_exclusive,
                    'exclusive_partners_list': exclusive_partners_list,
                    'mrss_video_categories': mrss_video_categories,
                    'video_id': video_id,
                    'general_categories': general_categories
                };

                // Send AJAX request based on selection
                $.ajax({
                    url: base_url + ajax_info.url,
                    type: 'POST',
                    data: ajax_info.data,
                    success: function (response) {
                        response = JSON.parse(response);

                        if (response.code == '200') {
                            Swal.fire({
                                icon: 'success',
                                title: 'MRSS information updated',
                                confirmButtonText: 'OK',
                            }).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            });
                        }
                        console.log();
                        //location.reload();
                    }
                });

            }
            else {

                Swal.fire({
                    icon: 'warning',
                    title: 'Delete MRSS information?',
                    confirmButtonText: 'Yes',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    text: 'Selection of No option will remove all partners and categories information from the system against this video (if already exists). Are you sure you want to proceed?',
                }).then((result) => {
                    if (result.value) {
                        ajax_info['url'] = 'reset-vi-mrss/' + video_id;
                        ajax_info['data'] = {};

                        $.ajax({
                            url: base_url + ajax_info.url,
                            type: 'POST',
                            data: ajax_info.data,
                            success: function () {
                                location.reload();
                            }
                        });
                    }
                });
            }
        }else{
            Swal.fire({
                icon: 'warning',
                title: 'Please complete details of Your s3 video or thumb details and also check if you added tags and category of deals',
                showCancelButton: false,
            });
        }

    });

    $('#exclusive-partners-list').change(function(){
        selected_partners_list = $(this).val();
        change_partner_categories();
        //reset_categories_list();
    });

    $('#mrss-video-categories').change(function(){

        var cp_list = [];
        $('#mrss-video-categories option:selected').each(function() {
            var pid = $(this).attr('pid');
            cp_list.push(pid);
            $('#exclusive-partners-list option[value="'+pid+'"]').prop('selected', true);
        });

        selected_categories_pid_list = cp_list;
    });

    if ($('#allow-mrss').length) {
        initiliaze_mrss(); // initialize MRSS
    }

    // $('#allow-mrss, #general-categories, #is-partner-exclusive, #exclusive-partners-list, #mrss-video-categories').chosen({
    //     disable_search: true,
    //     display_selected_options:true,
    //     width: "25%"
    // });



    //$('#allow-mrss').selectize();

    /* MRSS categories : E */


    $('.scrum_column').on('click', '.material-icons.drop-down', function() {
        $(this).toggleClass('noborder');
        $(this).parent().siblings(".drop-down-menu").toggle();
        event.stopPropagation();
    });

    $(document).click(function(){
        if($('.material-icons.drop-down').hasClass('noborder')) {
            $('.material-icons.drop-down').removeClass('noborder');
            $(".drop-down-menu").css("display","none");
        }
    });


    $(".single").css('z-index', 9999);
    $(document).on('click','#edit-watermark',function(){


        var modal = UIkit.modal("#watermark-video");
        modal.show();

    });
    $(document).on('click','#delete-soical',function(){

        var conf = confirm('Are you sure to want delete this video?');
        if(conf){
            $.ajax({
                type: "POST",
                url: base_url + "soical-video-delete",
                data: {video_id:video_id},
                success: function (data) {
                    //data = JSON.parse(data);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        $('#form_validation21').parsley().reset();
                        $.each(data.error, function (i, v) {
                            $('#' + i).parent().parent().find('.error').html(v);
                        });

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();


                        location.reload();
                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });
        }

    });
    $(document).on('click','.sign_appearance_release',function(){
        $('#form_validation21').parsley().reset();
        $('#form_validation21')[0].reset();
        var uid = $(this).data('uid');
        var id = $(this).data('id');
        $('#wga_uid').val(uid);
        $('#second_signer_id').val(id);
        $('#appearance_detail').val('');
        var modal = UIkit.modal("#appreance-release");
        modal.show();

    });

    $(document).on('click','#appreance-relase-munual',function(){
        $('#form_validation26').parsley().reset();
        $('#form_validation26')[0].reset();
        var uid = $(this).data('uid');
        $('#wga_uid_mar').val(uid);
        $('#appearance_detail_map').val('');
        var modal = UIkit.modal("#munual_ar_modal");
        modal.show();

    });
    $(document).on('click','#manual_appearance_submit',function(){
        var uid = $('#wga_uid_mar').val();
        var detail = $('#appearance_detail_map').val();
        if(detail.length == 0){
            alert('Please fill the identity field');
            return false;
        }
        $.ajax({
            type: "POST",
            url: base_url + "manual-ar",
            data: $('#form_validation26').serialize(),
            success: function (data) {
                //data = JSON.parse(data);
                if (data.code == 204) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                } else if (data.code == 201) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();
                    $('#form_validation26').parsley().reset();
                    $.each(data.error, function (i, v) {
                        $('#' + i).parent().parent().find('.error').html(v);
                    });

                } else if (data.code == 200) {
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();


                    location.reload();
                } else {
                    $('#suc-msg').attr('data-message', ' Feed Updated successfully!');
                    $('#suc-msg').click();
                }

            },  
            error: function () {
                $('#err-msg').attr('data-message', 'Something is going wrong!');
                $('#err-msg').click();
            }
    });
    $(document).on('click','.video_info',function(){
        $('#form_validation24').parsley().reset();
        $('#form_validation24')[0].reset();
        var modal = UIkit.modal("#xyzmodal");
        modal.show();
    });
    $(document).on('click','#appearance_submit',function(e){

        e.preventDefault();

        $('#form_validation21').submit();

    });
    $(document).on('click','#story_feed_save',function(){
        var categories = $('#exclusive-partners-list-story').find('option:selected');
        var feeds = $('#story_feed_id').find('option:selected');
        if(categories.length == 0){
            alert('Please select at least one Partner');
            return false;
        }
        if(feeds.length == 0){
            alert('Please select at least one MRSS Feed');
            return false;
        }

        $.ajax({
            type: "POST",
            url: base_url + "publish_story_content",
            data: $('#story_content_form').serialize(),
            success: function (data) {
                //data = JSON.parse(data);
                if (data.code == 204) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                } else if (data.code == 201) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();
                    $('#form_validation21').parsley().reset();
                    $.each(data.error, function (i, v) {
                        $('#' + i).parent().parent().find('.error').html(v);
                    });

                } else if (data.code == 200) {
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();


                    location.reload();
                } else {
                    $('#suc-msg').attr('data-message', ' Feed Updated successfully!');
                    $('#suc-msg').click();
                }

            },
            error: function () {
                $('#err-msg').attr('data-message', 'Something is going wrong!');
                $('#err-msg').click();
            }
        });


    });
    $('#form_validation30').parsley();
    $('#deal-reporting').on('click',function (){



        var modal = UIkit.modal("#deal_reporting_model");
        modal.show();


    });
    $('#deal-resolve-issue').on('click',function (){
        var id = $(this).data('id');
        var conf = confirm('Are you sure to mark as resolved?')
        if(conf){
            $.ajax({
                type: "POST",
                url: base_url + "report_bug_resolved",
                data: {id:id},
                success: function (data) {
                    //data = JSON.parse(data);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        $('#form_validation30').parsley().reset();
                        $.each(data.error, function (i, v) {
                            console.log()
                            $('#' + i+'_err').html(v);
                        });

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();

                        location.reload();
                    } else {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        location.reload();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });
        }
    });
    $('#raw-video-upload-refresh').on('click',function (){
        var lead_id = $(this).data('id');
        var video_id = $(this).data('vid');
        var conf = confirm('Are you sure to refresh URL?')
        if(conf){
            $.ajax({
                type: "POST",
                url: base_url + "reject-videolead2",
                data: {lead_id:lead_id,video_id:video_id},
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        $.each(data.error, function (i, v) {
                            console.log()
                            $('#' + i+'_err').html(v);
                        });

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();

                        location.reload();
                    } else {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        //location.reload();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });
        }
    });
    $(document).on('submit', '#form_validation21', function (e) {

        e.preventDefault();

        $.ajax({
            type: "POST",
            url: base_url + "second_signer_appearance_release",
            data: $('#form_validation21').serialize(),
            success: function (data) {
                //data = JSON.parse(data);
                if (data.code == 204) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                } else if (data.code == 201) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();
                    $('#form_validation21').parsley().reset();
                    $.each(data.error, function (i, v) {
                        $('#' + i).parent().parent().find('.error').html(v);
                    });

                } else if (data.code == 200) {
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();
                    var modal = UIkit.modal("#appreance-release");
                    modal.hide();
                    $('#form_validation21').parsley().reset();
                    $('#form_validation21')[0].reset();

                    location.reload();
                } else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }

            },
            error: function () {
                $('#err-msg').attr('data-message', 'Something is going wrong!');
                $('#err-msg').click();
            }
        });

    });
    $('#report-bug-ajax').on('click',function (){
        $('#form_validation30').submit();

    });
    $(document).on('submit', '#form_validation30', function (e) {

        e.preventDefault();

        $.ajax({
            type: "POST",
            url: base_url + "report_bug",
            data: $('#form_validation30').serialize(),
            success: function (data) {
                //data = JSON.parse(data);
                if (data.code == 204) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1000);
                } else if (data.code == 201) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();
                    $('#form_validation30').parsley().reset();
                    $.each(data.error, function (i, v) {
                        console.log()
                        $('#' + i+'_err').html(v);
                    });

                } else if (data.code == 200) {
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();
                    var modal = UIkit.modal("#deal_reporting_model");
                    modal.hide();
                    $('#form_validation30').parsley().reset();
                    $('#form_validation30')[0].reset();

                    location.reload();
                } else {
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();
                    var modal = UIkit.modal("#deal_reporting_model");
                    modal.hide();
                    $('#form_validation30').parsley().reset();
                    $('#form_validation30')[0].reset();
                    location.reload();
                }

            },
            error: function () {
                $('#err-msg').attr('data-message', 'Something is going wrong!');
                $('#err-msg').click();
            }
        });

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
            url: base_url+"watermar_upload_video?key="+uid+"&type="+watertype,
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
                var modal = UIkit.modal("#watermark-video");
                modal.hide();
                $('#w_s3_url').html(data.s3_url);
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
    $('input[name="story_content"]').fileuploader({
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
            url: base_url+"story_upload_video?key="+uid,
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
                ///$('#story-file-div').hide();
                $('#story-feed-div').show();
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

                item.html.find('.fileuploader-action-remove').remove();
                var modal = UIkit.modal("#watermark-video");
                modal.hide();
                $('#w_s3_url').html(data.s3_url);
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
                item.html.find('.fileuploader-action-remove').remove();
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

           /* $.post(base_url+'remove_file', {
                file: item.name
            })  .done(function() {
                on_reomve(item.title_new);
            })*/

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
    $('input[name="story_content_thumb"]').fileuploader({
        changeInput: '<div class="fileuploader-input">' +
            '<div class="fileuploader-input-inner">' +
            '<div class="fileuploader-main-icon"></div>' +
            '<h3 class="fileuploader-input-caption"><span>${captions.feedback}</span></h3>' +
            '<p>${captions.or}</p>' +
            '<div class="fileuploader-input-button"><span>${captions.button}</span></div>' +
            '</div>' +
            '</div>',
        theme: 'dragdrop',
        extensions:['jpeg', 'jpg', 'png'],
        upload: {
            url: base_url+"story_upload_thumb?key="+uid,
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
                $('#file_upload-drop_mrss_thumb .fileuploader-input').hide();
                $('#file_upload-drop_mrss_thumb .fileuploader-item-icon').hide();
                $('#file_upload-drop_mrss_thumb .progress-bar2').hide();
                $('#file_upload-drop_mrss_thumb .cm-mrss').show();

                $('#portal_video').val(data.url);

                var val = $('#portal_video_thumb').val();
                //$('#story-file-div').hide();
                //$('#story-feed-div').show();
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

                item.html.find('.fileuploader-action-remove').remove();
                var modal = UIkit.modal("#watermark-video");
                modal.hide();
                $('#w_s3_url').html(data.s3_url);
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
                $('#file_upload-drop_mrss_thumb .fileuploader-input').hide();
                $('#file_upload-drop_mrss_thumb .fileuploader-item-icon').hide();
                item.html.find('.fileuploader-action-remove').remove();
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

            /* $.post(base_url+'remove_file', {
                 file: item.name
             })  .done(function() {
                 on_reomve(item.title_new);
             })*/

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
    if(access.list) {

        $(document).on('click','.refresh-sort',function(){
            var sort = $(this).data('sort');
            var url = $(this).data('url');
            var id = $(this).data('id');
            var count = $(this).data('count');
            if(sort == 'ASC'){
                $(this).data('sort','DESC');
            }else if(sort == 'DESC'){
                $(this).data('sort','ASC');
            }else{

                $(this).data('sort','ASC');
            }
            $(this).parent().parent().find('.sort_value').val(sort);
            var column = $(this).parent().parent().find('.column_value').val();
            $.ajax({
                type: "POST",
                url: base_url + url,
                data: {sort:sort,column:column},
                success: function (data) {
                    data = JSON.parse(data);
                    $('#'+id).html(data.data);
                    $('#'+count).text(data.total);
                }
            });
        });

		$('.toogle-order').on('click, change', function () {
			var order;
			var num = $(this).data('num');
			var order = $(this).data('sort');

			var sort_val = $(this).parent().parent().find('input.sort_value').val(order);
		});

        $(document).on('click','.refresh-column',function(){
            var column = $(this).data('column');
            var url = $(this).data('url');
            var id = $(this).data('id');
            var count = $(this).data('count');

            $(this).parent().parent().find('.column_value').val(column);
            var sort = $(this).parent().parent().find('.sort_value').val();
            $(this).parent().parent().find('li').removeClass('active');
            $(this).parent().addClass('active');
            $.ajax({
                type: "POST",
                url: base_url + url,
                data: {sort:sort,column:column},
                success: function (data) {
                    data = JSON.parse(data);
                    $('#'+id).html(data.data);
                    $('#'+count).text(data.total);
                }
            });
        });

///////////////////////////////////Update Closing///////////////////////////////////////
        $('#edit-closing').click(function(){
            $('#edit-closing').hide();
            $('span.closing-area').each(function(){
                var content = $(this).html();
                $(this).html('<input type="date" id="closing-text" value="' + content + '">');
            });
            $('#cancel-closing').show();
            $('#save-closing').show();
            $('.info').fadeIn('fast');
        });
        $('#cancel-closing').click(function(){
            var cancel=$('input#closing-text').val();console.log(cancel);
            var closing_area = $('#edit-closing-area');
            var date_val = closing_area.data('val');
            closing_area.html(date_val);
            $('#edit-closing').show();
            $('#cancel-closing').hide();
            $('#save-closing').hide();
        });
        $('#save-closing').click(function(){
            $('#save-closing').hide();
            $('input#closing-text').each(function(){
                var weekday = ["Sun","Mon","Tue","Wed","Thurs","Fri","Sat"];
                var date = $(this).val();//.replace(/\n/g,"<br>");
                var newdate = new Date(date);
                var month = newdate.getMonth()+1;
                var updatedate=weekday[newdate.getDay()]+' '+month+'/'+newdate.getDate()+'/'+newdate.getFullYear();
                $(this).html(updatedate);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_closing',
                    data: {id:id,content:date},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
            });
            $('#cancel-closing').hide();
            $('#edit-closing').show();

        });
///////////////////////////////////Update Revenue///////////////////////////////////////
        $('#edit-revenue').click(function(){
            $('#edit-revenue').hide();
            $('span.revenue-area').each(function(){
                var content = $(this).html();
                $(this).html('<input type="text" id="revenue-text" value="' + content + '">');
            });
            $('#cancel-revenue').show();
            $('#save-revenue').show();
        });
        $('#cancel-revenue').click(function(){
            var cancel=$('input#revenue-text').val();console.log(cancel);
            var revenue_area = $('#edit-revenue-area');
            var date_val = revenue_area.data('val');
            date_val=date_val+'%';
            revenue_area.html(date_val);
            $('#edit-revenue').show();
            $('#cancel-revenue').hide();
            $('#save-revenue').hide();
        });
        $('#save-revenue').click(function(){
            $('#save-revenue').hide();
            $('input#revenue-text').each(function(){
                var revenue = $(this).val();//.replace(/\n/g,"<br>");
                var updaterevenue= $(this).html(revenue);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                $.ajax({
                    type:"POST",
                    url: base_url + 'revenue_update',
                    data: {lead_id:id,revenue_share:revenue,sent:1},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
            });
            $('#cancel-revenue').hide();
            $('#edit-revenue').show();

        });
///////////////////////////////////Update Video Title///////////////////////////////////////
        $('#edit-title').click(function(){
            $('#edit-title').hide();
            $('span.title-area').each(function(){
                var content = $(this).html();
                $(this).html('<textarea id="title-text">' + content + '</textarea>');
            });
            $('#cancel-title').show();
            $('#save-title').show();
        });
        $('#cancel-title').click(function(){
            var cancel=$('textarea#title-text').val();console.log(cancel);
            var title_area = $('#edit-title-area');
            var date_val = title_area.data('val');
            title_area.html(date_val);
            $('#edit-title').show();
            $('#cancel-title').hide();
            $('#save-title').hide();
        });
        $('#save-title').click(function(){
            $('#save-title').hide();
            $('textarea#title-text').each(function(){
                var title = $(this).val();//.replace(/\n/g,"<br>");
                var updatetitle= $(this).html(title);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_title',
                    data: {id:id,content:title},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
            });
            $('#cancel-title').hide();
            $('#edit-title').show();

        });
        ///////////////////////////////////Update Video URL///////////////////////////////////////
        $('#edit-video-url').click(function(){
            $('#edit-video-url').hide();
            $('span.edit-video-url-area').each(function(){
                var content = $(this).data('val');
                $(this).html('<textarea id="video-url-text">' + content + '</textarea>');
            });
            $('#cancel-video-url').show();
            $('#save-video-url').show();
        });
        $('#cancel-video-url').click(function(){
            var cancel=$('textarea#video-url-text').val();console.log(cancel);
            var title_area = $('#edit-video-url-area');
            var date_val = title_area.data('val');
            title_area.html(date_val);
            $('#edit-video-url').show();
            $('#cancel-video-url').hide();
            $('#save-video-url').hide();
        });
        $('#save-video-url').click(function(){
            $('#save-video-url').hide();
            $('textarea#video-url-text').each(function(){
                var title = $(this).val();//.replace(/\n/g,"<br>");
                var updatetitle= $(this).html(title);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_video_url',
                    data: {id:id,content:title},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
            });
            $('#cancel-video-url').hide();
            $('#edit-video-url').show();

        });

        ///////////////////////////////////Update Video Email///////////////////////////////////////
        $('#edit-video-email').click(function(){
            $('#edit-video-email').hide();
            $('span.edit-video-email-area').each(function(){
                var content = $(this).data('val');
                $(this).html('<textarea id="video-email-text">' + content + '</textarea>');
            });
            $('#cancel-video-email').show();
            $('#save-video-email').show();
        });
        $('#cancel-video-email').click(function(){
            var cancel=$('textarea#video-email-text').val();console.log(cancel);
            var title_area = $('#edit-video-email-area');
            var date_val = title_area.data('val');
            title_area.html(date_val);
            $('#edit-video-email').show();
            $('#cancel-video-email').hide();
            $('#save-video-email').hide();
        });
        $('#save-video-email').click(function(){
            $('#save-video-email').hide();
            $('textarea#video-email-text').each(function(){
                var title = $(this).val();//.replace(/\n/g,"<br>");
                var updatetitle= $(this).html(title);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_video_email',
                    data: {id:id,content:title},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
            });
            $('#cancel-video-email').hide();
            $('#edit-video-email').show();

        });
///////////////////////////////////Update Video Description///////////////////////////////////////
        $('#edit-des').click(function(){
            $('#edit-des').hide();
            $('span.des-area').each(function(){
                var content = $(this).html();
                $(this).html('<textarea id="des-text">' + content + '</textarea>');
            });
            $('#cancel-des').show();
            $('#save-des').show();
        });
        $('#cancel-des').click(function(){
            var cancel=$('textarea#des-text').val();console.log(cancel);
            var title_area = $('#edit-des-area');
            var date_val = title_area.data('val');

            title_area.html(date_val);
            $('#edit-des').show();
            $('#cancel-des').hide();
            $('#save-des').hide();
        });
        $('#save-des').click(function(){
            $('#save-des').hide();
            $('textarea#des-text').each(function(){
                var title = $(this).val();//.replace(/\n/g,"<br>");
                var updatetitle= $(this).html(title);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_des',
                    data: {id:id,content:title},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
            });
            $('#cancel-des').hide();
            $('#edit-des').show();

        });
///////////////////////////////////Update Video Tags///////////////////////////////////////
        $('#edit-tags').click(function(){
            $('#edit-tags').hide();
            $('span.tags-area').each(function(){
                var content = $(this).html();
                $(this).html('<textarea id="tags-text">' + content + '</textarea>');
            });
            $('#cancel-tags').show();
            $('#save-tags').show();
        });
        $('#cancel-tags').click(function(){
            var cancel=$('textarea#tags-text').val();console.log(cancel);
            var title_area = $('#edit-tags-area');
            var date_val = title_area.data('val');

            title_area.html(date_val);
            $('#edit-tags').show();
            $('#cancel-tags').hide();
            $('#save-tags').hide();
        });
        $('#save-tags').click(function(){
            $('#save-tags').hide();
            $('textarea#tags-text').each(function(){
                var tags = $(this).val();//.replace(/\n/g,"<br>");
                var updatetags= $(this).html(tags);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_tags',
                    data: {id:id,content:tags},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
            });
            $('#cancel-tags').hide();
            $('#edit-tags').show();

        });
///////////////////////////////////Update Video Message///////////////////////////////////////
        $('#edit-message').click(function(){
            $('#edit-message').hide();
            $('span.message-area').each(function(){
                var content = $(this).html();
                $(this).html('<textarea id="message-text">' + content + '</textarea>');
            });
            $('#cancel-message').show();
            $('#save-message').show();
        });
        $('#cancel-message').click(function(){
            var cancel=$('textarea#message-text').val();console.log(cancel);
            var message_area = $('#edit-message-area');
            var date_val = message_area.data('val');
            message_area.html(date_val);
            $('#edit-message').show();
            $('#cancel-message').hide();
            $('#save-message').hide();
        });
        $('#save-message').click(function(){
            $('#save-message').hide();
            $('textarea#message-text').each(function(){
                var message = $(this).val();//.replace(/\n/g,"<br>");
                var updatemessage= $(this).html(message);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_message',
                    data: {id:id,content:message},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
            });
            $('#cancel-message').hide();
            $('#edit-message').show();

        });
///////////////////////////////////Update Video Rating///////////////////////////////////////
        $('#edit-ratings').click(function(){
            $('#edit-ratings').hide();
            $('span.ratings-area').each(function(){
                var content = $(this).html();
                $(this).html('<textarea id="ratings-text">' + content + '</textarea>');
            });
            $('#cancel-ratings').show();
            $('#save-ratings').show();
        });
        $('#cancel-ratings').click(function(){
            var cancel=$('textarea#ratings-text').val();console.log(cancel);
            var ratings_area = $('#edit-ratings-area');
            var date_val = ratings_area.data('val');
            ratings_area.html(date_val);
            $('#edit-ratings').show();
            $('#cancel-ratings').hide();
            $('#save-ratings').hide();
        });
        $('#save-ratings').click(function(){
            $('#save-ratings').hide();
            $('textarea#ratings-text').each(function(){
                var ratings = $(this).val();//.replace(/\n/g,"<br>");
                var trimStr = $.trim(ratings);
                var updateratings= $(this).html(ratings);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                if(trimStr){
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_ratings',
                    data: {id:id,content:ratings},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
                }
            });
            $('#cancel-ratings').hide();
            $('#edit-ratings').show();

        });
///////////////////////////////////Update Video Rating Comments///////////////////////////////////////
        $('#edit-rating-comments').click(function(){
            $('#edit-rating-comments').hide();
            $('span.rating-comments-area').each(function(){
                var comcontent = $(this).html();
                $(this).html('<textarea id="rating-comments-text">' + comcontent + '</textarea>');
            });
            $('#cancel-rating-comments').show();
            $('#save-rating-comments').show();
        });
        $('#cancel-rating-comments').click(function(){
            var comcancel=$('textarea#rating-comments-text').val();
            var rating_comments_area = $('#edit-rating-comments-area');
            var date_val = rating_comments_area.data('val');
            rating_comments_area.html(date_val);console.log(date_val);
            $('#edit-rating-comments').show();
            $('#cancel-rating-comments').hide();
            $('#save-rating-comments').hide();
        });
        $('#save-rating-comments').click(function(){
            $('#save-rating-comments').hide();
            $('textarea#rating-comments-text').each(function(){
                var rating_comments = $(this).val();//.replace(/\n/g,"<br>");
                var trimStr = $.trim(rating_comments);
                var updateratingcomment= $(this).html(rating_comments);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                if(trimStr){
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_ratings_comment',
                    data: {id:id,content:rating_comments},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
                }else{
                    $("textarea#rating-comments-text").remove();
                }
            });
            $('#cancel-rating-comments').hide();
            $('#edit-rating-comments').show();

        });
        ///////////////////////////////////Update Facebook Url///////////////////////////////////////
        $('#edit-facebook').click(function(){
            $('#edit-facebook').hide();
            $('span.facebook_edit_area').each(function(){
                var comcontent = $(this).html();
                $(this).html('<textarea id="facebook-text">' + comcontent + '</textarea>');
            });
            $('#cancel-facebook').show();
            $('#save-facebook').show();
        });
        $('#cancel-facebook').click(function(){
            var comcancel=$('textarea#facebook-text').val();
            var facebook_area = $('#facebook_edit');
            var date_val = facebook_area.data('val');
            facebook_area.html(date_val);console.log(date_val);
            $('#edit-facebook').show();
            $('#cancel-facebook').hide();
            $('#save-facebook').hide();
        });
        $('#save-facebook').click(function(){
            $('#save-facebook').hide();
            $('textarea#facebook-text').each(function(){
                var facebook_comments = $(this).val();//.replace(/\n/g,"<br>");
                var trimStr = $.trim(facebook_comments);
                var updatefacebook= $(this).html(facebook_comments);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                if(trimStr){
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_facebook',
                    data: {id:id,content:facebook_comments},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
                }
            });
            $('#cancel-facebook').hide();
            $('#edit-facebook').show();

        });
        ///////////////////////////////////Update Youtube Url///////////////////////////////////////
        $('#edit-youtube').click(function(){
            $('#edit-youtube').hide();
            $('span.youtube_edit_area').each(function(){
                var comcontent = $(this).html();
                $(this).html('<textarea id="youtube-text">' + comcontent + '</textarea>');
            });
            $('#cancel-youtube').show();
            $('#save-youtube').show();
        });
        $('#cancel-youtube').click(function(){
            var comcancel=$('textarea#youtube-text').val();
            var youtube_area = $('#youtube_edit');
            var date_val = youtube_area.data('val');
            youtube_area.html(date_val);console.log(date_val);
            $('#edit-youtube').show();
            $('#cancel-youtube').hide();
            $('#save-youtube').hide();
        });
        $('#save-youtube').click(function(){
            $('#save-youtube').hide();
            $('textarea#youtube-text').each(function(){
                var youtube_comments = $(this).val();//.replace(/\n/g,"<br>");
                var trimStr = $.trim(youtube_comments);
                var updateyoutube= $(this).html(youtube_comments);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                if(trimStr){
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_youtube',
                    data: {id:id,content:youtube_comments},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
                }
            });
            $('#cancel-youtube').hide();
            $('#edit-youtube').show();

        });
        ///////////////////////////////////Update Confidence Level///////////////////////////////////////
        $('#edit-con').click(function(){
            $('#edit-con').hide();
            $('span.con_edit_area').each(function(){
                var comcontent = $(this).html();
                $(this).html('<select id="con-select" class="uk-width-medium-1-5 select-bs"><option  value="High" selected>High</option><option value="Medium">Medium</option><option value="Low">Low</option></select>');
            });
            $('#cancel-con').show();
            $('#save-con').show();
        });
        $('#cancel-con').click(function(){
            var comcancel=$('#con-select').find(":selected").text();
            var con_area = $('#con_edit');
            var date_val = con_area.data('val');
            con_area.html(date_val);console.log(date_val);
            $('#edit-con').show();
            $('#cancel-con').hide();
            $('#save-con').hide();
        });
        $('#save-con').click(function(){
        $('#save-con').hide();
            var con = $('#con-select').find(":selected").text();
            if(con == 'Medium' || con == 'Low'){
               var video_comment_data = $('#video_comment_id').data('value');
                console.log(video_comment_data);
               if(video_comment_data){
                   var url=(window.location.href).split('/');
                   var id = url[url.length-1];

                   $.ajax({
                       type:"POST",
                       url: base_url + 'update_confidence',
                       data: {id:id,content:con},
                       success: function (data) {
                           data = JSON.parse(data);
                           if (data.code == 200) {
                               $('#suc-msg').attr('data-message', data.message);
                               $('#suc-msg').click();
                               window.location = window.location.href;
                           }
                       }
                   })
                   $('#cancel-con').hide();
                   $('#edit-con').show();
               }else {
                   Swal.fire({
                       icon: 'error',
                       title: 'No Video Comment added',
                       text: 'Please add video comment to save this option',
                   });
                   $("select#con-select").remove();
                   $('#cancel-con').hide();
                   $('#edit-con').show();
               }
            }else{
                var url=(window.location.href).split('/');
                var id = url[url.length-1];

                $.ajax({
                    type:"POST",
                    url: base_url + 'update_confidence',
                    data: {id:id,content:con},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                });
                $('#cancel-con').hide();
                $('#edit-con').show();
            }
        });
        ///////////////////////////////////Update Video Comments///////////////////////////////////////
        $('#edit-video-comment').click(function(){
            $('#edit-video-comment').hide();
            $('span.video_comment_edit_area').each(function(){
                var comcontent = $(this).html();
                $(this).html('<textarea cols="30" rows="10" id="video-comment-text">' + comcontent + '</textarea>');
            });
            $('#cancel-video-comment').show();
            $('#save-video-comment').show();
        });
        $('#cancel-video-comment').click(function(){
            var comcancel=$('textarea#video-comment-text').val();
            var rating_comments_area = $('#video_comment_edit');
            var date_val = rating_comments_area.data('val');
            rating_comments_area.html(date_val);console.log(date_val);
            $('#edit-video-comment').show();
            $('#cancel-video-comment').hide();
            $('#save-video-comment').hide();
        });
        $('#save-video-comment').click(function(){
            var words = $.trim($("textarea#video-comment-text").val()).split(" ").length;
             if(words > 100){
                    Swal.fire({
                       icon: 'error',
                       title: 'Word Limit',
                       text: 'You have added '+words+' words please add 100 words',
                   });
                }else {
            $('#save-video-comment').hide();
            $('textarea#video-comment-text').each(function(){
                var video_comments = $(this).val();//.replace(/\n/g,"<br>");
                var trimStr = $.trim(video_comments);
                var updateratingcomment= $(this).html(video_comments);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                console.log(video_comments);
               if(trimStr){
                    $.ajax({
                        type:"POST",
                        url: base_url + 'update_video_comment',
                        data: {id:id,content:video_comments},
                        success: function (data) {
                            data = JSON.parse(data);
                            if (data.code == 200) {
                                $('#suc-msg').attr('data-message', data.message);
                                $('#suc-msg').click();
                                window.location = window.location.href;
                            }
                        }
                    })
                }else{
                   $("textarea#video-comment-text").remove();
               }
            });
            $('#cancel-video-comment').hide();
            $('#edit-video-comment').show();
            }
        });
        ///////////////////////////////////Update S3 Raw Url///////////////////////////////////////
        $('#edit-raw').click(function(){
            $('#edit-raw').hide();
            $('span.raw_edit_area').each(function(){
                var comcontent = $(this).html();
                $(this).html('<textarea id="raw-text">' + comcontent + '</textarea>');
            });
            $('#cancel-raw').show();
            $('#save-raw').show();
        });
        $('#cancel-raw').click(function(){
            var comcancel=$('textarea#raw-text').val();
            var raw_area = $('#raw_edit');
            var date_val = raw_area.data('val');
            raw_area.html(date_val);console.log(date_val);
            $('#edit-raw').show();
            $('#cancel-raw').hide();
            $('#save-raw').hide();
        });
        $('#save-raw').click(function(){
            $('#save-raw').hide();
            $('textarea#raw-text').each(function(){
                var raw_comments = $(this).val();//.replace(/\n/g,"<br>");
                var trimStr = $.trim(raw_comments);
                var updateraw= $(this).html(raw_comments);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                if(trimStr){
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_raws3',
                    data: {id:id,content:raw_comments},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            var pathArray = window.location.pathname.split( '/' );
                            console.log(pathArray[1])
                           window.location = window.location.protocol + "//" + window.location.host + "/"+ pathArray[1]+"/admin/edit_video/"+data.video_id;
                        }
                    }
                })
                }
            });
            $('#cancel-raw').hide();
            $('#edit-raw').show();

        });
        ///////////////////////////////////Update S3 Doc Url///////////////////////////////////////
        $('#edit-s3-doc').click(function(){
            $('#edit-s3-doc').hide();
            $('span.s3_doc_edit_area').each(function(){
                var comcontent = $(this).html();
                $(this).html('<textarea id="s3-doc-text">' + comcontent + '</textarea>');
            });
            $('#cancel-s3-doc').show();
            $('#save-s3-doc').show();
        });
        $('#cancel-s3-doc').click(function(){
            var comcancel=$('textarea#s3-doc-text').val();
            var s3_doc_area = $('#s3_doc_edit');
            var date_val = s3_doc_area.data('val');
            s3_doc_area.html(date_val);console.log(date_val);
            $('#edit-s3-doc').show();
            $('#cancel-s3-doc').hide();
            $('#save-s3-doc').hide();
        });
        $('#save-s3-doc').click(function(){
            $('#save-s3-doc').hide();
            $('textarea#s3-doc-text').each(function(){
                var s3_doc_comments = $(this).val();//.replace(/\n/g,"<br>");
                var trimStr = $.trim(s3_doc_comments);
                var updates3doc= $(this).html(s3_doc_comments);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                if(trimStr){
                    $.ajax({
                        type:"POST",
                        url: base_url + 'update_docs3',
                        data: {id:id,content:s3_doc_comments},
                        success: function (data) {
                            data = JSON.parse(data);
                            if (data.code == 200) {
                                $('#suc-msg').attr('data-message', data.message);
                                $('#suc-msg').click();
                                window.location = window.location.href;
                            }
                        }
                    })
                }
            });
            $('#cancel-s3-doc').hide();
            $('#edit-s3-doc').show();

        });
        ///////////////////////////////////Update S3 Edited Video Url///////////////////////////////////////
        $('#edit-editeds3').click(function(){
            $('#edit-editeds3').hide();
            $('span.editeds3_edit_area').each(function(){
                var comcontent = $(this).html();
                $(this).html('<textarea id="editeds3-text">' + comcontent + '</textarea>');
            });
            $('#cancel-editeds3').show();
            $('#save-editeds3').show();
        });
        $('#cancel-editeds3').click(function(){
            var comcancel=$('textarea#editeds3-text').val();
            var editeds3_area = $('#editeds3_edit');
            var date_val = editeds3_area.data('val');
            editeds3_area.html(date_val);console.log(date_val);
            $('#edit-editeds3').show();
            $('#cancel-editeds3').hide();
            $('#save-editeds3').hide();
        });
        $('#save-editeds3').click(function(){
            $('#save-editeds3').hide();
            $('textarea#editeds3-text').each(function(){
                var editeds3_comments = $(this).val();//.replace(/\n/g,"<br>");
                var trimStr = $.trim(editeds3_comments);
                var updateediteds3= $(this).html(editeds3_comments);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                if(trimStr){
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_editeds3',
                    data: {id:id,content:editeds3_comments},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
                }
            });
            $('#cancel-editeds3').hide();
            $('#edit-editeds3').show();

        });
        ///////////////////////////////////Update S3 Edited Thumb Url///////////////////////////////////////
        $('#edit-thumbs3').click(function(){
            $('#edit-thumbs3').hide();
            $('span.thumbs3_edit_area').each(function(){
                var comcontent = $(this).html();
                $(this).html('<textarea id="thumbs3-text">' + comcontent + '</textarea>');
            });
            $('#cancel-thumbs3').show();
            $('#save-thumbs3').show();
        });
        $('#cancel-thumbs3').click(function(){
            var comcancel=$('textarea#thumbs3-text').val();
            var thumbs3_area = $('#thumbs3_edit');
            var date_val = thumbs3_area.data('val');
            thumbs3_area.html(date_val);console.log(date_val);
            $('#edit-thumbs3').show();
            $('#cancel-thumbs3').hide();
            $('#save-thumbs3').hide();
        });
        $('#save-thumbs3').click(function(){
            $('#save-thumbs3').hide();
            $('textarea#thumbs3-text').each(function(){
                var thumbs3_comments = $(this).val();//.replace(/\n/g,"<br>");
                var trimStr = $.trim(thumbs3_comments);
                var updatethumbs3= $(this).html(thumbs3_comments);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                if(trimStr){
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_thumbs3',
                    data: {id:id,content:thumbs3_comments},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
                }
            });
            $('#cancel-thumbs3').hide();
            $('#edit-thumbs3').show();

        });
        ///////////////////////////////////Update Q1///////////////////////////////////////
        $('#edit-q1').click(function(){
            $('#edit-q1').hide();
            $('span.q1-area').each(function(){
                var content = $(this).html();
                $(this).html('<input type="date" id="q1-text" value="' + content + '">');
            });
            $('#cancel-q1').show();
            $('#save-q1').show();
        });
        $('#cancel-q1').click(function(){
            var cancel=$('input#q1-text').val();console.log(cancel);
            var q1_area = $('#edit-q1-area');
            var date_val = q1_area.data('val');
            q1_area.html(date_val);
            $('#edit-q1').show();
            $('#cancel-q1').hide();
            $('#save-q1').hide();
        });
        $('#save-q1').click(function(){
            $('#save-q1').hide();
            $('input#q1-text').each(function(){
                var weekday = ["Sun","Mon","Tue","Wed","Thurs","Fri","Sat"];
                var date = $(this).val();//.replace(/\n/g,"<br>");
                var newdate = new Date(date);
                var month = newdate.getMonth()+1;
                var updatedate=weekday[newdate.getDay()]+' '+month+'/'+newdate.getDate()+'/'+newdate.getFullYear();
                $(this).html(updatedate);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                $.ajax({
                    type:"POST",
                    url: base_url + 'update_q1',
                    data: {id:id,content:date},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            window.location = window.location.href;
                        }
                    }
                })
            });
            $('#cancel-q1').hide();
            $('#edit-q1').show();

        });

        ///////////////////////////////////Update Q2///////////////////////////////////////
        $('#edit-q2').click(function(){
            $('#edit-q2').hide();
            $('span.q2_edit_area').each(function(){
                var comcontent = $(this).html();
                $(this).html('<textarea id="q2-text">' + comcontent + '</textarea>');
            });
            $('#cancel-q2').show();
            $('#save-q2').show();
        });
        $('#cancel-q2').click(function(){
            var comcancel=$('textarea#q2-text').val();
            var q2_area = $('#q2_edit');
            var date_val = q2_area.data('val');
            q2_area.html(date_val);console.log(date_val);
            $('#edit-q2').show();
            $('#cancel-q2').hide();
            $('#save-q2').hide();
        });
        $('#save-q2').click(function(){
            $('#save-q2').hide();
            $('textarea#q2-text').each(function(){
                var q2_comments = $(this).val();//.replace(/\n/g,"<br>");
                var trimStr = $.trim(q2_comments);
                var updateq2= $(this).html(q2_comments);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                if(trimStr){
                    $.ajax({
                        type:"POST",
                        url: base_url + 'update_q2',
                        data: {id:id,content:q2_comments},
                        success: function (data) {
                            data = JSON.parse(data);
                            if (data.code == 200) {
                                $('#suc-msg').attr('data-message', data.message);
                                $('#suc-msg').click();
                                window.location = window.location.href;
                            }
                        }
                    })
                }
            });
            $('#cancel-q2').hide();
            $('#edit-q2').show();

        });

        ///////////////////////////////////Update Q3///////////////////////////////////////
        $('#edit-q3').click(function(){
            $('#edit-q3').hide();
            $('span.q3_edit_area').each(function(){
                var comcontent = $(this).html();
                $(this).html('<textarea id="q3-text">' + comcontent + '</textarea>');
            });
            $('#cancel-q3').show();
            $('#save-q3').show();
        });
        $('#cancel-q3').click(function(){
            var comcancel=$('textarea#q3-text').val();
            var q3_area = $('#q3_edit');
            var date_val = q3_area.data('val');
            q3_area.html(date_val);console.log(date_val);
            $('#edit-q3').show();
            $('#cancel-q3').hide();
            $('#save-q3').hide();
        });
        $('#save-q3').click(function(){
            $('#save-q3').hide();
            $('textarea#q3-text').each(function(){
                var q3_comments = $(this).val();//.replace(/\n/g,"<br>");
                var trimStr = $.trim(q3_comments);
                var updateq3= $(this).html(q3_comments);
                $(this).contents().unwrap();
                var url=(window.location.href).split('/');
                var id = url[url.length-1];
                if(trimStr){
                    $.ajax({
                        type:"POST",
                        url: base_url + 'update_q3',
                        data: {id:id,content:q3_comments},
                        success: function (data) {
                            data = JSON.parse(data);
                            if (data.code == 200) {
                                $('#suc-msg').attr('data-message', data.message);
                                $('#suc-msg').click();
                                window.location = window.location.href;
                            }
                        }
                    })
                }
            });
            $('#cancel-q3').hide();
            $('#edit-q3').show();

        });

        ///////////////////////////////////Update Mrss///////////////////////////////////////
        $('#edit-mrss').click(function(){
            $('#edit-mrss').hide();
            $('#update_mrss_id').show();
            $('#cancel-mrss').show();
            $('#save-mrss').show();
        });
        $('#cancel-mrss').click(function(){
            $('#update_mrss_id').hide();
            $('#edit-mrss').show();
            $('#cancel-mrss').hide();
            $('#save-mrss').hide();
        });
        $('#save-mrss').click(function(){
            $('#save-mrss').hide();
            var mrss = [];
            var mrssarray=[];
            var id='';
            $('#update_mrss_id div.selectize-input div.item').each(function(){
                var mrss_split = $(this).html().split('<a href="javascript:void(0)" class="remove" tabindex="-1" title="Remove">X</a>');
                mrss.push(mrss_split[0]);
                var mrss_value = $(this).data('value');
                mrssarray.push(mrss_value);
                $('#update_mrss_id').hide();
                var url=(window.location.href).split('/');
                id = url[url.length-1];

            });
            var mrss_data=mrss.join(',');
            var mrss_data_value = mrssarray.join(',');
            $('#edit-mrss-area').html(mrss_data);
            $.ajax({
                type:"POST",
                url: base_url +'update_deal_mrss',
                data: {id:id,content:mrss_data_value},
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        window.location = window.location.href;
                    }
                }
            });
            $('.mrss_publish_button').hide();
            $('#cancel-mrss').hide();
            $('#edit-mrss').show();

        });
        mrss_cc = $('#mrss_categories').selectize({
            plugins: {
                'remove_button': {
                    label: 'X'
                }
            },
        });
        if(mrss_cc.length > 0){
            mrss_cc = mrss_cc[0].selectize;
        }
        $('#is_mrss').on('ifChecked', function () {

            $('#update_mrss_id').show();

        });

        $('#is_mrss').on('ifUnchecked', function () {

            $('#update_mrss_id').hide();

        });




        $(document).on('click','.refresh-column-data',function(){
            var url = $(this).data('url');
            var id = $(this).data('id');
            var count = $(this).data('count');

            var sort = $(this).parent().find('.sort_value').val();
            var column = $(this).parent().find('.column_value').val();
            $(this).parent().parent().find('li').removeClass('active');
            $(this).parent().addClass('active');
            $.ajax({
                type: "POST",
                url: base_url + url,
                data: {sort:sort,column:column},
                beforeSend: function(){
                    altair_helpers.custom_preloader_hide();
                },
                success: function (data) {
                    data = JSON.parse(data);
                    $('#'+id).html(data.data);
                    //$('#'+count).text(data.total);
                }
            });
        });




        /*$(document).on('click','#signed-refresh',function(){
            $.ajax({
                type: "POST",
                url: base_url + "signed-refresh",
                data: {},
                success: function (data) {
                    data = JSON.parse(data);
                    $('#scrum_column_contract_signed').html(data.signed);
                    $('#signed-count').text(data.total);
                }
            });
        });
*/
        $(document).on('click','.deal_detail',function() {

            var id = $(this).data('id');
            window.location = base_url+'deal-detail/'+id;

            /* $.ajax({
                  type: 'POST',
                  url: base_url + 'get_video_deal',
                  data: {id: id},
                  success: function (data) {
                      data = JSON.parse(data);

                      if (data.code == 204) {
                          $('#err-msg').attr('data-message', data.message);
                          $('#err-msg').click();
                          setTimeout(function () {
                              window.location = data.url;
                          }, 1000);
                      } else if (data.code == 201) {
                          $('#err-msg').attr('data-message', data.message);
                          $('#err-msg').click();

                      } else if (data.code == 200) {
                          $('#suc-msg').attr('data-message', data.message);
                          $('#suc-msg').click();
                          $('#rating_body').hide();
                          if (data.data.rating_point > 0 || data.data.rating_comments.length > 0) {
                              $('#rating_body').show();
                          }
                          $.each(data.data, function (i, v) {
                              $('#' + i).html(v);
                              if (i == 'video_url') {
                                  $('#' + i).text(v);
                                  $('#' + i).attr('data-url',data.data.url);
                                  $('#' + i).attr('data-title',data.data.video_title);
                              }
                              if (i == 'id') {
                                  $('#' + i).val(v);
                              }

                              if (i == 'email') {
                                  $('#' + i+'-c').text(v);
                              }


                          });

                          var modal = UIkit.modal("#detial");
                          modal.show();

                      } else {
                          $('#err-msg').attr('data-message', 'Something is going wrong!');
                          $('#err-msg').click();
                      }


                  }
              });*/
        });

        $(document).on('click','.play-video',function(){

            var title = $(this).data('title');
            var url = $(this).data('url');
            var html = '<iframe  src = "'+url+'"  frameborder="0" allowfullscreen style="height: 60vh;width:100%;"></iframe>'
            $('#vt').text(title);
            $('#play').html(html);
            var modal = UIkit.modal("#play_model");
            modal.show();

        });



    }
    if(access.can_client_add) {
        statusAdd = $('#status').selectize();

        if (statusAdd.length > 0) {
            statusAdd = statusAdd[0].selectize;
        }


        $(document).on('click','.add',function(){
            $('#form_validation2').parsley().reset();
            $('#form_validation2')[0].reset();
            var lead_id = $(this).data('id');
            var name = $(this).data('name');
            var email = $(this).data('email');
            $('#lead_id').val(lead_id);
            var modal = UIkit.modal("#add_model");
            modal.show();
            $('#email').val(email).focus();
            $('#full_name').val(name).focus();

            statusAdd.setValue(1);


        });
        $(".single").eq(4).css('z-index', 9999);
        $(".single").eq(5).css('z-index', 9999);
        $(document).on('click','#add_from',function(e){

            e.preventDefault();

            $('#form_validation2').submit();

        });
        $(document).on('submit', '#form_validation2', function (e) {

            e.preventDefault();

            $.ajax({
                type: "POST",
                url: base_url + "add_client2",
                data: $('#form_validation2').serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        $('#form_validation2').parsley().reset();
                        $.each(data.error, function (i, v) {
                            $('#' + i).parent().parent().find('.error').html(v);
                        });

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        var lead_id = $('#lead_id').val();
                        var modal = UIkit.modal("#add_model");
                        modal.hide();
                        $('#form_validation2').parsley().reset();
                        $('#form_validation2')[0].reset();
                        $('#lead_id').val('');
                        $.ajax({
                            type: "POST",
                            url: base_url + "assign-deal-client",
                            data: {lead_id:lead_id,client_id:data.id},
                            success: function (data) {
                                data = JSON.parse(data);
                                $('#scrum_column_contract_signed').html(data.signed);/*
                                $('#scrum_column_account_created').html(data.created);*/
                                setTimeout(function () {
                                   location.reload();
                                }, 1000);
                            }
                        });
                        location.reload();
                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });

        });
    }
    if(access.reminder) {
        /*$(document).on('click','#created-refresh',function(){
            $.ajax({
                type: "POST",
                url: base_url + "created-refresh",
                data: {},
                success: function (data) {
                    data = JSON.parse(data);
                    $('#created-from').html(data.accountCreated);
                    $('#created-count').text(data.total);
                }
            });
        });*/
        $(document).on('ifChanged','.created-reminder',function(){
            var leads_length = $('.created-reminder');
            var checked_length = $('.created-reminder:checked');
            if(leads_length.length == checked_length.length){
                $('#selected-created').text('Unmarked All');
            }else{
                $('#selected-created').text('Marked All');
            }
        });
        $(document).on('click','#selected-created',function(){
            var state = $(this).attr('data-state');
            if(state == 0){
                $(this).attr('data-state',1);
                $(this).text('Unmarked All');
                $('.created-reminder').iCheck('check');
            }else if(state == 1){
                $(this).attr('data-state',0);
                $(this).text('Marked All');
                $('.created-reminder').iCheck('uncheck');
            }
        });

        $(document).on('click','#selected-reminder',function(){
            var checked_length = $('.created-reminder:checked');
            if(checked_length.length > 0){
                $.ajax({
                    type: "POST",
                    url: base_url + "welcome-reminder",
                    data: $('#created-from').serialize(),
                    success: function (data) {
                        data = JSON.parse(data);
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                    }
                });
            }else{
                $('#err-msg').attr('data-message', 'Please select the at least one deal.');
                $('#err-msg').click();
            }
        });

        $(document).on('click','.send',function(){
            var id  = $(this).data('id');
            var lead_id  = $(this).data('lead');


            $('#single-reminder').val(id);
            $('#single-reminder-lead-id').val(lead_id);
            $.ajax({
                type: "POST",
                url: base_url + "welcome-reminder",
                data: $('#created-from-single').serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    $('#suc-msg').attr('data-message', 'Reminder email send successfully!');
                    $('#suc-msg').click();

                }
            });
        });

    }

    if(access.information_reminder) {
        /*$(document).on('click', '#information-refresh', function () {
            $.ajax({
                type: "POST",
                url: base_url + "information-refresh",
                data: {},
                success: function (data) {
                    data = JSON.parse(data);
                    $('#information-from').html(data.dealInformation);
                    $('#information-count').text(data.total);
                }
            });
        });*/
        $(document).on('ifChanged','.information-reminder',function(){
            var leads_length = $('.information-reminder');
            var checked_length = $('.information-reminder:checked');
            if(leads_length.length == checked_length.length){
                $('#selected-information').text('Unmarked All');
            }else{
                $('#selected-information').text('Marked All');
            }
        });
        $(document).on('click','#selected-information',function(){
            var state = $(this).attr('data-state');
            if(state == 0){
                $(this).attr('data-state',1);
                $(this).text('Unmarked All');
                $('.information-reminder').iCheck('check');
            }else if(state == 1){
                $(this).attr('data-state',0);
                $(this).text('Marked All');
                $('.information-reminder').iCheck('uncheck');
            }
        });

        $(document).on('click','#information-selected-reminder',function(){
            var checked_length = $('.information-reminder:checked');
            if(checked_length.length > 0){
                $.ajax({
                    type: "POST",
                    url: base_url + "information-notification",
                    data: $('#information-from').serialize(),
                    success: function (data) {
                        data = JSON.parse(data);
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                    }
                });
            }else{
                $('#err-msg').attr('data-message', 'Please select the at least one deal.');
                $('#err-msg').click();
            }
        });

        $(document).on('click','.send-notification',function(){
            var id  = $(this).data('id');
            $('#information-single-reminder').val(id);
            $.ajax({
                type: "POST",
                url: base_url + "information-notification",
                data: $('#information-from-single').serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    $('#suc-msg').attr('data-message', 'Reminder email send successfully!');
                    $('#suc-msg').click();

                }
            });
        });
    }

    var channel = $('#youtube_channel').selectize();

    $(document).on('click','.distribute',function(e){
        e.preventDefault();
        var id  = $(this).data('id');
        var url  = $(this).data('url');
		var wgid  = $(this).data('wgid');

        if(url == 'publish-youtube'){

            $.ajax({
                url: base_url + 'Video_Rights/publishing_form_fields',
                data : {'p-type':'youtube', 'v-id': id},
                success: function (response) {
                    response = JSON.parse(response);

                    if (response.code == '200' && (Object.keys(response.fields)).length > 0) {
                        var vals = response.fields;
                        var date_time;

                        channel = $('#youtube_channel').selectize();

                        if (channel.length > 0) {
                            channel = channel[0].selectize;
                        }

                        channel.setValue(vals.youtube_channel);

                        //$('#youtube_channel').setValue(vals.youtube_channel);

                        //$('#youtube_channel').val(vals.youtube_channel);
                        //$('#youtube_channel option[value="'+WooG+'"]').prop('selected', true);

                        if (vals.publish_now == '1') {
                            $('#publish_now_youtube').prop('checked', true);
                            $('#dvPinNo2').hide();
                            $("#distribute_yt").parsley().destroy();
                            $('#youtube_publish_date').prop('required',false);
                            $('#youtube_publish_date').prop('disabled',true);
                            $('#youtube_publish_time').prop('required',false);
                            $('#youtube_publish_time').prop('disabled',true);
                            $("#distribute_yt").parsley();
                        }
                        else {
                            $('#youtube_publish_date_radio').prop('checked', true);
                            $('#dvPinNo2').show();
                            $("#distribute_yt").parsley().destroy();
                            $('#youtube_publish_date').prop('required',true);
                            $('#youtube_publish_date').prop('disabled',false);
                            $('#youtube_publish_time').prop('required',true);
                            $('#youtube_publish_time').prop('disabled',false);
                            $("#distribute_yt").parsley();

                            //date_time = vals.youtube_channel;
                        }


                        $('#youtube_publish_title').val(vals.video_title);
                        $('#youtube_publish_tags').val(vals.video_tags);
                        $('#youtube_publish_status').val(vals.youtube_publish_status);

                    }

                }
            });


            $('#ed_video_id').val(id);
			$('#ed_wgid').val(wgid);
            var modal = UIkit.modal("#youtube");
            modal.show();
            $.ajax({
                type: "POST",
                url: base_url + 'get-publishing-data',
                data : {video_id:id,type:'YouTube'},
                success: function (data) {
                    data = JSON.parse(data);
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();

                    //var channel = "";
                   // var category = $('#youtube_category').selectize();
                    var pstatus = $('#youtube_publish_status').selectize();

                    if (channel.length > 0) {
                        channel = channel[0].selectize;
                    }

                    /*if (category.length > 0) {
                        category = category[0].selectize;
                    }*/
                    if (pstatus.length > 0) {
                        pstatus = pstatus[0].selectize;
                    }


                    $.each(data.data,function(i,v){

                        if (i == 'publish_now')  {
                            if (v == '1') {
                                $('#publish_now_youtube').iCheck('check');

                            }
                            else if (v == '0') {
                                $('#youtube_publish_date_radio').iCheck('check');
                                $('#dvPinNo2').show();
                            }
                        }
                        else if(i == 'channel'){
                            channel.setValue(v);
                        }else if(i == 'categories'){
							var option = '';
							for(var x=0; x < v.length; x++){
								option += '<option value="'+v[x].id+'">'+v[x].title+'</option>';
							}
							$('#youtube_category').append(option);
						/*}else if(i == 'category'){
                            category.setValue(v);*/
                        }else if(i == 'youtube_publish_status'){
                            pstatus.setValue(v);
                        }else if(i == 'unique_key'){
							$('#video_id').html(v);
						}
                        else{
                            $('#youtube_'+i).val(v);
                        }


                        $('#youtube_'+i).focus();
                    });

                    var charc = $("#youtube_publish_tags").val().length;
                    charc = parseInt(500-parseInt(charc));
                    $('.counter').text(charc);
					$('#yt_desc_footer').val($('#yt_footer').html());
					$("#youtube_category").selectize({
						persist			  : false,
						hideSelected	: true,
						create			  : function(input) {
							return {
								value	: input,
								text	: input
							}
						}
					});
					$.each(data.data,function(i,v){
						if(i == 'category'){
							$("#youtube_category").data('selectize').setValue(v);
						}
					});

                }
            });
        }else if(url == 'publish-facebook'){

            $.ajax({
                url: base_url + 'Video_Rights/publishing_form_fields',
                data : {'p-type':'facebook', 'v-id': id},
                success: function (response) {
                    response = JSON.parse(response);

                    if (response.code == '200' && (Object.keys(response.fields)).length > 0) {
                        var vals      = response.fields;

                        if(vals.publish_now == '1'){
                            $('#publish_now_facebook').prop('checked', true);
                            $('#dvPinNo').hide();
                            $("#distribute_fb").parsley().destroy();
                            $('#facebook_publish_time').prop('required',false);
                            $('#facebook_publish_time').prop('disabled',true);
                            $('#facebook_publish_date').prop('required',false);
                            $('#facebook_publish_date').prop('disabled',true);
                            $("#distribute_fb").parsley();
                        }
                        else{
                            $('#facebook_publish_date_radio').prop('checked', true);
                            $('#dvPinNo').show();
                            $("#distribute_fb").parsley().destroy();
                            $('#facebook_publish_time').prop('required',true);
                            $('#facebook_publish_time').prop('disabled',false);
                            $('#facebook_publish_date').prop('required',true);
                            $('#facebook_publish_date').prop('disabled',false);
                            $("#distribute_fb").parsley();

                            var date_time = response.publish_datetime;
                        }

                        $('#facebook_publish_title').val(vals.video_title);

                        $('#facebook_publish_description').val(vals.video_description);
                        $('#facebook_publish_tags').val(vals.video_tags);
                    }

                }
            });



            $('#ed_video_id_fb').val(id);
            var modal = UIkit.modal("#facebook");
            modal.show();
            $.ajax({
                type: "POST",
                url: base_url + 'get-publishing-data',
                data : {video_id:id,type:'FaceBook'},
                success: function (data) {
                    data = JSON.parse(data);
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();



                    $.each(data.data,function(i,v){

                        if (i == 'publish_now')  {
                            if (v == '1') {
                                $('#publish_now_facebook').iCheck('check');
                            }
                            else if (v == '0') {
                                $('#facebook_publish_date_radio').iCheck('check');
                                $('#dvPinNo').show();
                            }
                        }

                        $('#facebook_'+i).val(v);
                        $('#facebook_'+i).focus();
                    });

                    var charc = $("#facebook_publish_tags").val().length;
                    charc = parseInt(500-parseInt(charc));
                    $('.counter').text(charc);

                }
            });
        }else {
            $.ajax({
                type: "POST",
                url: base_url + url + "/" + id,
                success: function (data) {
                    data = JSON.parse(data);
                    $('#suc-msg').attr('data-message', '');
                    $('#suc-msg').click();

                }
            });
        }
    });


    $(document).on('click','.send_reminder_email',function () {

        var email = $(this).attr('data-email');
        var id = $(this).attr('data-id');

        $.ajax({
            type: "POST",
            url: base_url + "send-reminder-email",
            data:{email:email,id:id},
            success: function (data) {
                data = JSON.parse(data);
                if(data.code == 200){
                    $('#suc-msg').attr('data-message', 'Reminder email send successfully!');
                    $('#suc-msg').click();
                }
                else if(data.code == 201){
                    $('#err-msg').attr('data-message', 'Having trouble while sending Email.');
                    $('#err-msg').click();
                }

            }
        });

    });
$(document).on('click','.move-closewon',function () {

        var id = $(this).attr('data-id');

        $.ajax({
            type: "POST",
            url: base_url + "move-closewon",
            data:{id:id},
            success: function (data) {
                data = JSON.parse(data);
                if(data.code == 200){
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();
                }
                else if(data.code == 201){
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();
                }

            }
        });

    });
    if(access.can_revenue_update) {
        $(document).on('click','.revenue-update',function () {

            var revenue = $(this).data('revenue');
            var id = $(this).data('id');
            var title = $(this).data('title');
            var sent = $(this).data('sent');

            $('#ru_lead_id').val(id);
            $('#ru_sent').val(sent);
            $('#revenue_modal').find('.dt').text(title);
            $('#revenue_modal').find('#revenue_share').val(revenue);

            var modal = UIkit.modal("#revenue_modal");
            modal.show();
            $('#revenue_modal').find('#revenue_share').focus();

        });

        $(document).on('click','#revenue_submit',function () {
            $('#form_validation4').submit();
        });
        $(document).on('submit', '#form_validation4', function (e) {

            e.preventDefault();

            $.ajax({
                type: "POST",
                url: base_url + "revenue_update",
                data: $('#form_validation4').serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        $('#revenue_form').parsley().reset();
                        $.each(data.error, function (i, v) {
                            $('#' + i).parent().parent().find('.error').html(v);
                        });

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        var modal = UIkit.modal("#revenue_modal");
                        modal.hide();
                        $('#form_validation4').parsley().reset();
                        $('#form_validation4')[0].reset();
                        $('#ru_lead_id').val('');
                        $('#contract-sent-refresh').click();
                        var url = window.location.href.toString().split(window.location.host)[1];
                        if(url.split('/')[3] == 'deal-detail'){
                            window.location = window.location.href;
                        }

                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });

        });
    }
    if(access.can_contract_cancel) {
        $(document).on('click','.contract-cancel',function () {

            var id = $(this).data('id');
            var title = $(this).data('title');
            $('#contract_cancel_modal #ru_lead_id').val(id);
            $('#contract_cancel_modal').find('.dt').text(title);

            var modal = UIkit.modal("#contract_cancel_modal");
            modal.show();

        });

        $(document).on('click','#contract_cancel_submit',function () {
            $('#form_validation5').submit();
        });
        $(document).on('submit', '#form_validation5', function (e) {

            e.preventDefault();

            $.ajax({
                type: "POST",
                url: base_url + "contract_cancel",
                data: $('#form_validation5').serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();


                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        var modal = UIkit.modal("#contract_cancel_modal");
                        modal.hide();
                        $('#form_validation5').parsley().reset();
                        $('#form_validation5')[0].reset();
                        $('#ru_lead_id').val('');
                        $('#contract-sent-refresh').click();
                        var url = window.location.href.toString().split(window.location.host)[1];
                        if(url.split('/')[3] == 'deal-detail'){
                            window.location = window.location.href;
                        }

                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });

        });
    }
    if(access.information_received) {
        /*$(document).on('click', '#information-received-refresh', function () {
            $.ajax({
                type: "POST",
                url: base_url + "information-received-refresh",
                data: {},
                success: function (data) {
                    data = JSON.parse(data);
                    $('#scrum_column_account_deal_information_received').html(data.dealReceived);
                    $('#information-received-count').text(data.total);
                }
            });
        });*/
    }

    if(access.won) {
        /*$(document).on('click', '#won-refresh', function () {
            $.ajax({
                type: "POST",
                url: base_url + "won-refresh",
                data: {},
                success: function (data) {
                    data = JSON.parse(data);
                    $('#scrum_column_closed_won').html(data.dealWon);
                    $('#won-count').text(data.total);
                }
            });
        });*/
    }

    if(access.lost) {
        /*$(document).on('click', '#lost-refresh', function () {
            $.ajax({
                type: "POST",
                url: base_url + "lost-refresh",
                data: {},
                success: function (data) {
                    data = JSON.parse(data);
                    $('#scrum_column_closed_lost').html(data.dealLost);
                    $('#lost-count').text(data.total);
                }
            });
        });*/
    }
    if(access.upload_video) {
        /*$(document).on('click', '#upload-refresh', function () {
            $.ajax({
                type: "POST",
                url: base_url + "upload-refresh",
                data: {},
                success: function (data) {
                    data = JSON.parse(data);
                    $('#scrum_column_closed_lost').html(data.dealLost);
                    $('#lost-count').text(data.total);
                }
            });
        });*/
    }

    if(access.rejected) {
        /*$(document).on('click', '#dealsRejected-refresh', function () {
            $.ajax({
                type: "POST",
                url: base_url + "rejected-refresh",
                data: {},
                success: function (data) {
                    data = JSON.parse(data);
                    $('#scrum_column_closed_lost').html(data.dealLost);
                    $('#lost-count').text(data.total);
                }
            });
        });*/
    }

    if(access.can_distribute) {

        $('input[name="publish_now_youtube"]').on('change',function(){
            var scheduling = $('input[name="publish_now_youtube"]:checked').val();
            if(scheduling == 1){
                $('#dvPinNo2').show();
                $("#distribute_yt").parsley().destroy();
                $('#youtube_publish_date').prop('required',true);
                $('#youtube_publish_date').prop('disabled',false);
                $('#youtube_publish_time').prop('required',true);
                $('#youtube_publish_time').prop('disabled',false);
                $("#distribute_yt").parsley();
                $("input[name='publish_now_youtube']").val('0');
            }else{
                $('#dvPinNo2').hide();
                $("#distribute_yt").parsley().destroy();
                $('#youtube_publish_date').prop('required',false);
                $('#youtube_publish_date').prop('disabled',true);
                $('#youtube_publish_time').prop('required',false);
                $('#youtube_publish_time').prop('disabled',true);
                $("#distribute_yt").parsley();
                $("input[name='publish_now_youtube']").val('1');
            }
        });
        $('input[name="publish_now_facebook"]').on('change',function(){
            var scheduling = $('input[name="publish_now_facebook"]:checked').val();
            if(scheduling == 1){
                $('#dvPinNo').show();
                $("#distribute_fb").parsley().destroy();
                $('#facebook_publish_time').prop('required',true);
                $('#facebook_publish_time').prop('disabled',false);
                $('#facebook_publish_date').prop('required',true);
                $('#facebook_publish_date').prop('disabled',false);
                $("#distribute_fb").parsley();
                $("input[name='publish_now_facebook']").val('0');
            }else{
                $('#dvPinNo').hide();
                $("#distribute_fb").parsley().destroy();
                $('#facebook_publish_time').prop('required',false);
                $('#facebook_publish_time').prop('disabled',true);
                $('#facebook_publish_date').prop('required',false);
                $('#facebook_publish_date').prop('disabled',true);
                $("#distribute_fb").parsley();
                $("input[name='publish_now_facebook']").val('1');
            }
        });
        /*$('#publish_now').on('ifChecked', function () {



        });

        $('#publish_now').on('ifUnchecked', function () {



        });*/

        /* $('#publish_now_facebook').on('ifChecked', function () {



         });

         $('#publish_now_facebook').on('ifUnchecked', function () {



         });*/

        $(document).on('click','#distribute_from_fb',function () {
            $('#distribute_fb').submit();
        });

        $(document).on('click','#distribute_from',function () {
            $('#distribute_yt').submit();
        });

        $(document).on('submit', '#distribute_yt', function (e) {

            e.preventDefault();
            $('#distribute_from').prop('disabled',true);
            $.ajax({
                type: "POST",
                url: base_url + "publish-youtube",
                data: $('#distribute_yt').serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    $('#distribute_from').prop('disabled',false);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        $('#form_validation2').parsley().reset();
                        $.each(data.error, function (i, v) {
                            $('#' + i).parent().parent().find('.error').html(v);
                        });

                    } else if (data.code == 206) {
                        alert(data.message.error.message);
                        $('#yt-err').html('');
                        $('#yt-err').html('<strong>YouTube Error : <strong>'+data.message.error.message+' ');

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        var lead_id = $('#lead_id').val();
                        var modal = UIkit.modal("#youtube");
                        modal.hide();
                        $('#form_validation2').parsley().reset();
                        $('#form_validation2')[0].reset();
                        $('#lead_id').val('');
                      /*  $.ajax({
                            type: "POST",
                            url: base_url + "assign-deal-client",
                            data: {lead_id:lead_id,client_id:data.id},
                            success: function (data) {
                                data = JSON.parse(data);
                                $('#scrum_column_contract_signed').html(data.signed);
                                $('#scrum_column_account_created').html(data.created);
                            }
                        });*/
                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });

        });
        $(document).on('submit', '#distribute_fb', function (e) {

            e.preventDefault();
            $('#distribute_from_fb').prop('disabled',true);

            $.ajax({
                type: "POST",
                url: base_url + "publish-facebook",
                data: $('#distribute_fb').serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    $('#distribute_from_fb').prop('disabled',false);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        $('#form_validation2').parsley().reset();
                        $.each(data.error, function (i, v) {
                            $('#' + i).parent().parent().find('.error').html(v);
                        });

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        var lead_id = $('#lead_id').val();
                        var client_id = $('#client_id').val();
                        var modal = UIkit.modal("#facebook");
                        modal.hide();
                        $('#form_validation2').parsley().reset();
                        $('#form_validation2')[0].reset();
                        $('#lead_id').val('');
                       /* $.ajax({
                            type: "POST",
                            url: base_url + "assign-deal-client",
                            data: {lead_id:lead_id,client_id:client_id},
                            success: function (data) {
                                data = JSON.parse(data);
                                $('#scrum_column_contract_signed').html(data.signed);
                                $('#scrum_column_account_created').html(data.created);
                            }
                        });*/
                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });

        });
    }

    if(access.not_interested) {

        $(document).on('click','.not-interested',function(){
            $this = $(this);
            UIkit.modal.confirm('Are you sure to want to move in not interested?', function() {
                var id = $this.data('id');
                var title = $this.data('title');
                $.ajax({
                    type: "POST",
                    url: base_url + "not-interested",
                    data: {id: id, title: title},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 204) {
                            $('#err-msg').attr('data-message', data.message);
                            $('#err-msg').click();

                        } else if (data.code == 201) {
                            $('#err-msg').attr('data-message', data.message);
                            $('#err-msg').click();


                        } else if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            $this.parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().find('.refresh-column-data').click();
                            $('#not-interested-col').click();
                            var url = window.location.href.toString().split(window.location.host)[1];
                            if(url.split('/')[3] == 'deal-detail'){
                                window.location = window.location.href;
                            }else{
                                window.location = window.location.href;
                            }

                        } else {
                            $('#err-msg').attr('data-message', 'Something is going wrong!');
                            $('#err-msg').click();
                        }

                    },
                    error: function () {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }
                });
            });

        });
        $(document).on('click','.undo-not-interested',function(){
            $this = $(this);
            UIkit.modal.confirm('Are you sure to want to restore the video?', function() {
                var id = $this.data('id');
                var title = $this.data('title');
                $.ajax({
                    type: "POST",
                    url: base_url + "not-interested",
                    data: {id: id, title: title, restore: 1},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 204) {
                            $('#err-msg').attr('data-message', data.message);
                            $('#err-msg').click();

                        } else if (data.code == 201) {
                            $('#err-msg').attr('data-message', data.message);
                            $('#err-msg').click();


                        } else if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            $this.parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().find('.refresh-column-data').click();
                            $('#not-interested-col').click();
                            var url = window.location.href.toString().split(window.location.host)[1];
                            if(url.split('/')[3] == 'deal-detail'){
                                window.location = window.location.href;
                            }

                        } else {
                            $('#err-msg').attr('data-message', 'Something is going wrong!');
                            $('#err-msg').click();
                        }

                    },
                    error: function () {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }
                });
            });

        });
    }

    if(access.can_upload_edited_videos) {

        $(document).on('click','.download-raw',function(){
            $this = $(this);

            var id = $this.data('id');
            $.ajax({
                type: "POST",
                url: base_url + "download-raw-files",
                data: {id: id},
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();

                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();


                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        $this.parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().find('.refresh-column-data').click();
                        $('#not-interested-col').click();
                        var url = window.location.href.toString().split(window.location.host)[1];
                        if(url.split('/')[3] == 'deal-detail'){
                            window.location = window.location.href;
                        }

                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });


        });
    }

    $(document).on('click','.email-detail',function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: base_url + "email_information",
            data: {id: id},
            success: function (data) {
                data = JSON.parse(data);
                if (data.code == 204) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();

                } else if (data.code == 201) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();


                } else if (data.code == 200) {
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();
                    var modal_upper_html=$('#email_modal .email_html_data');
                    var sending_html='';
                    modal_upper_html.empty();
                    //Empty the Email Data
                    $.each(data.data,function (i,v) {
                        if(i == 'reply_link'){
                            $('.'+i).attr('data-id',v.message_id);
                        }else {
                            //ADD POPUP HTML WITH MESSAGE DATA
                            var d=new Date(v.date_email);
                            var format_date=dateFormat(d);
                            var sending_html='<div class="cntrct-pop-content">' +
                                '<div class="pop-content-lft">'+
                                '<div class="pop-content-section"> From : <label class="from_email">'+v.from_email+'</label></div>'+
                                '<div class="pop-content-section"> To : <label class="to_email">'+v.to_email+'</label>'+
                                '<div class="rev-cover"><span class="rev-detail"><i class="material-icons">arrow_drop_down</i></span>'+
                                '<div class="rev-dtl-con" style="min-width: 245px;">'+
                                '<p><span>From:</span><label class="from_email">'+v.from_email+'</label></p>'+
                                '<p><span>to:</span><label class="to_email">'+v.to_email+'</label></p>'+
                                '<p><span>Date:</span><label class="date_email">'+v.date_email+'</label></p>'+
                                '<p><span>Subject:</span><label class="subject_email">'+v.subject_email+'</label></p>'+
                                '</div></div></div>' +
                                '</div>' +
                                '<div class="pop-content-rgt">'+
                                '<span>'+format_date +'</span>  '+
                                '<div class="rev-cover"><a href="javascript:void(0);" class="reply_link" data-id="" data-url="email_reply" data-lead="'+v.message_id+'"> Reply </a><span class="rev-detail" data-id="" data-url="email_reply" data-lead="'+v.message_id+'" style="color:#1e88e5;">'+
                                '<i class="material-icons" style="color: #1e88e5;">arrow_drop_down</i></span>'+
                                '<div class="rev-dtl-con" style="min-width: 80px; text-align: center">'+
                                '<a href="javascript:void(0);" class="reply_link" data-id="" data-url="email_reply" data-lead="'+v.message_id+'">Reply All</a>'+
                                '<a href="javascript:void(0);" class="reply_link" data-id="" data-url="email_forword" data-lead="'+v.message_id+'">Forward</a>'+
                                '</div></div>'+
                                '</div>'+
                                '<div class="pop-email-con uk-modal-footer content_email" style="display: none">'+v.content_email+'</div>'+
                                '</div>';

                            modal_upper_html.append( sending_html );
                            //ADD the Email Data
                        }

                    });
                    //Toggle the last email And by default the last one will be open
                    $('.cntrct-pop-content:last-child .content_email').toggle();
                    var modal = UIkit.modal("#email_modal");
                    modal.show();

                } else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }

            },
            error: function () {
                $('#err-msg').attr('data-message', 'Something is going wrong!');
                $('#err-msg').click();
            }
        });
    });
    function dateFormat(d) {
        var monthShortNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];
        var dayNames= ["Sun","Mon","Tues","Wed","Thurs","Fri","Sat"];
        var t = new Date(d);
        var hours = t.getHours();
        var minutes = t.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;
        return dayNames[t.getDay()] + ", " +t.getDate() + ' ' + monthShortNames[t.getMonth()] + ', ' + t.getFullYear()+ '  ' + strTime;
    }

    $(document).on('click','.cntrct-pop-content',function (e) {
        //Toggle ALL email
        $(this).find('.content_email').toggle();
    });
    $(document).on('click',".rev-detail",function() {
        $(this).next().slideToggle();
    });
    /*$(document).click(function(e) {
        var target = e.target;
        if (!$(target).is('.rev-detail')  ) {
            $(".rev-dtl-con").slideUp();
        }
    });*/
    $(document).mouseup(function(e)
    {
        var container = $(".rev-dtl-con");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            container.hide();
        }
    });

    $(document).on('click','.reply_link',function () {
        var id = $(this).data('id');
        var url = $(this).data('url');
        var lead = $(this).data('lead');
        window.open(base_url+url+'/'+lead,'_blank');
    });
    var charc = 0
    if($("#youtube_publish_tags").length > 0){
        var charc = $("#youtube_publish_tags").val().length;
    }
    $('.counter').text(charc);
    $('#youtube_publish_tags').on('keydown',function (e) {

        charc = $(this).val().length;
        if(charc > 500 && e.keyCode != 8){
            e.preventDefault();
        }else{
            charc = parseInt(500-parseInt(charc));
            $('.counter').text(charc);
        }
    });
    var charc1 = 0
    if($("#facebook_publish_tags").length > 0){
        var charc1 = $("#facebook_publish_tags").val().length;
    }
    $('.counter1').text(charc1);
    $('#facebook_publish_tags').on('keydown',function (e) {

        charc1 = $(this).val().length;
        if(charc1 > 500 && e.keyCode != 8){
            e.preventDefault();
        }else{
            charc1 = parseInt(500-parseInt(charc1));
            $('.counter1').text(charc1);
        }
    });
    $('#facebook_publish_description').on('keyup',function () {
        var footer = $('#add_footer1').data('footer');
        var desc = $(this).val();
        var check = desc.includes(footer);
        if(check === true){
            $('#add_footer1').hide();
        }else{
            $('#add_footer1').show();
        }
    });
    $('#add_footer1').on('click',function () {
        var desc = $('#facebook_publish_description').val();
        var footer = $(this).data('footer');
        var check = desc.includes(footer);
        if(check === true){
            $(this).hide();
        }else{
            desc = desc+"\n"+footer;
            $('#facebook_publish_description').val(desc);
            $(this).hide();
        }

    });

    if($( "ol.p-st" ).length > 0){
        var spans = $( "ol.p-st" ).find( "li" );
        var active = $( "ol.p-st" ).find( "li" );

        if(active.length > 0){
            var active_index = active.index($('.active'));

            $.each(spans,function(i,v){
                if(i < active_index){
                    $(this).addClass('active');
                }
            });
        }
    }
    $(document).on('click','.view_contract',function () {
        var id = $(this).data('contract');
        // window.location = base_url+'view_contract/'+id
    });

    if(access.can_delete_lead) {
        $(document).on('click', '.delete-videolead', function () {

                var id = $(this).data('id');
                $('#cf_lead_id').val(id);
                var modal = UIkit.modal("#cancelform");
                modal.show();
            });
            $(document).on('click','#cancel_submit',function () {
                $('#form_validation6').submit();
            });

            $(document).on('submit', '#form_validation6', function (e) {

                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: base_url + "delete_deal",
                    data: $('#form_validation6').serialize(),
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 204) {
                            $('#err-msg').attr('data-message', data.message);
                            $('#err-msg').click();

                        } else if (data.code == 201) {
                            $('#err-msg').attr('data-message', data.message);
                            $('#err-msg').click();


                        } else if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            var modal = UIkit.modal("#cancelform");
                            modal.hide();
                            window.location = base_url +"video_rights";
                        } else {
                            $('#err-msg').attr('data-message', 'Something is going wrong!');
                            $('#err-msg').click();
                        }

                    },
                    error: function () {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }
                });
            });
        $(document).on('click','.delete-videolead-per',function(){

            var id = $(this).data('id');
            UIkit.modal.confirm('Are you sure you want to delete this video permanently?', function(){
                $.ajax({
                    type 	: 'POST',
                    url  	: base_url+'delete_deal_per',
                    data 	: {lead_id:id},
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


                    }
                });

            });


        });
        $(document).on('click','.appearance_release_delete',function(){

            var id = $(this).data('id');
            UIkit.modal.confirm('Are you sure you want to delete this Appearance Release?', function(){
                $.ajax({
                    type 	: 'POST',
                    url  	: base_url+'appearance_delete',
                    data 	: {lead_id:id},
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


                    }
                });

            });


        });
        $(document).on('click','.second_signer_delete',function(){

            var id = $(this).data('id');
            UIkit.modal.confirm('Are you sure you want to delete this Second Signer?', function(){
                $.ajax({
                    type 	: 'POST',
                    url  	: base_url+'second_signer_delete',
                    data 	: {lead_id:id},
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


                    }
                });

            });


        });
    }
    $(document).on('click', '.reject-videolead', function () {

        var id = $(this).data('id');
        $('#rj_lead_id').val(id);
        var videoid = $(this).data('videoid');
        $('#rj_video_id').val(videoid);
        var modal = UIkit.modal("#rejectform");
        modal.show();
    });
    $(document).on('click','#reject_submit',function () {
        $('#form_validation8').submit();
    });

    $(document).on('submit', '#form_validation8', function (e) {
        e.preventDefault();
        $.ajax({
            type 	: 'POST',
            url  	: base_url+'reject-videolead',
            data: $('#form_validation8').serialize(),
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

                }else if(data.code == 200){
                    $('#suc-msg').attr('data-message',data.message);
                    $('#suc-msg').click();
                    window.location = base_url +"video_rights";

                }else{
                    $('#err-msg').attr('data-message','Something is going wrong!');
                    $('#err-msg').click();
                }


            }
        });

    });
    $(document).on('click', '.upload_user_video', function () {

        var id = $(this).data('id');
        $('#up_lead_id').val(id);
        var modal = UIkit.modal("#uploadform");
        modal.show();
    });
    $(document).on('click','#upload_user_submit',function () {
        $('#form_validation7').submit();
    });

    $(document).on('submit', '#form_validation7', function (e) {

        e.preventDefault();
        $.ajax({
            type: "POST",
            url: base_url + "upload_user_submit",
            data: $('#form_validation7').serialize(),
            success: function (data) {
                data = JSON.parse(data);
                if (data.code == 204) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();

                } else if (data.code == 201) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();


                } else if (data.code == 200) {
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();
                    var modal = UIkit.modal("#uploadform");
                    modal.hide();
                    window.location = window.location.href;
                } else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }

            },
            error: function () {
                $('#err-msg').attr('data-message', 'Something is going wrong!');
                $('#err-msg').click();
            }
        });
    });
    /*setInterval(function () {
        $('.refresh-column-data').click();

    },5000);*/


    $(document).on('click', '#story_information_modal_trigger', function () {

        var id = $(this).data('id');
        $('#up_lead_id').val(id);
        var modal = UIkit.modal("#story_information_modal");
        modal.show();
    });

    $(document).on('click','#story_information_submit',function () {
        $('#story_information_form').submit();
    });

    $(document).on('submit', '#story_information_form', function (e) {

        e.preventDefault();
        $.ajax({
            type: "POST",
            url: base_url + "story_information_submission",
            data: $('#story_information_form').serialize(),
            success: function (data) {
                data = JSON.parse(data);
                if (data.code == 204) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();

                } else if (data.code == 201) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();


                } else if (data.code == 200) {
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();
                    var modal = UIkit.modal("#uploadform");
                    modal.hide();
                    window.location = window.location.href;
                } else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }

            },
            error: function () {
                $('#err-msg').attr('data-message', 'Something is going wrong!');
                $('#err-msg').click();
            }
        });
    });

    $(document).on('click', '#personal_information_modal_trigger', function () {

        var id = $(this).data('id');
        $('#up_lead_id').val(id);
        var modal = UIkit.modal("#personal_information_modal");
        modal.show();
    });

    $(document).on('click','#personal_information_submit',function () {
        $('#personal_information_form').submit();
    });

    $(document).on('submit', '#personal_information_form', function (e) {

        e.preventDefault();
        $.ajax({
            type: "POST",
            url: base_url + "personal_information_submission",
            data: $('#personal_information_form').serialize(),
            success: function (data) {
                data = JSON.parse(data);
                if (data.code == 204) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();

                } else if (data.code == 201) {
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();


                } else if (data.code == 200) {
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();
                    var modal = UIkit.modal("#uploadform");
                    modal.hide();
                    window.location = window.location.href;
                } else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }

            },
            error: function () {
                $('#err-msg').attr('data-message', 'Something is going wrong!');
                $('#err-msg').click();
            }
        });
    });

    var lastScrollTop = 0;

    var stage_list = [];

    $('.scrum_column').scroll(function(event){

        var st         = $(this).scrollTop();
        var context    = $(this);
        var curr_scrum = $(this).children().first();
        var scrum_id   = $(this).children().first().attr('id');

        var obj_index;
        var limit      = 10;
        var offset     = 10;

        if (st > lastScrollTop) {

            obj_index = stage_list.findIndex(srcum => srcum.id == scrum_id);

            if (obj_index == -1) {
                stage_list.push({'id':scrum_id, 'limit':limit, 'offset':0, 'max_offset_reached':0});
            }

			var diff = Math.ceil($(this).prop('scrollHeight') - $(this).scrollTop());

            if ((diff == $(this).outerHeight()) || (diff + 1 == $(this).outerHeight()) || (diff - 1 == $(this).outerHeight())) { // if scrolled to bottom
                if (stage_list[obj_index].max_offset_reached != 1) {
                    stage_list[obj_index].limit   = limit;
                    stage_list[obj_index].offset += offset;

                    $.ajax({
                        type: 'POST',
                        url:  base_url + 'Video_Deals',
                        data: {'deal_stage':scrum_id, 'limit':stage_list[obj_index].limit, 'offset':stage_list[obj_index].offset},
                        success: function (response) {
                            response = JSON.parse(response);
                            stage_list[obj_index].max_offset_reached = response.max_offset_reached;

                            if (response.view != '') {
                                var videos_list = response.view;
                                $('#'+scrum_id).append(videos_list);
                            }
                        }
                    });
                }

            }
            else {
				// console.log('else ');

				// console.log('diff  '         + ($(this).prop('scrollHeight') - $(this).scrollTop()));
				// console.log('ceil diff  '    + diff);
				// console.log('scroll height ' + $(this).prop('scrollHeight'));
				// console.log('scroll top '    + $(this).scrollTop());
				// console.log('outer height '  + $(this).outerHeight());

				// console.log((diff == $(this).outerHeight()) || (diff + 1 == $(this).outerHeight()) || (diff - 1 == $(this).outerHeight()));
            }




            //alert('scroll down');
        } else {
            //alert('scroll up');
        }
        lastScrollTop = st;
    });

	$('.last-activity, .curr-stage-updated').click(function(e) {
		var link_class = $(this).attr('class');
		var stage_filter = false;
		if (link_class == 'curr-stage-updated') {
			stage_filter = true;
		}
		var scrum_id = $(this).data('id');
		var parent_li = $(this).parent();
		var sort = parent_li.siblings('.sort_value').val();
		var order_by = $(this).data('column');
		parent_li.siblings('.column_value').val(order_by);
		var limit = 10;
		var offset = 0;

		if(sort == 'ASC'){
			parent_li.siblings('.sort_value').data('sort','DESC');
		}else if(sort == 'DESC'){
			parent_li.siblings('.sort_value').data('sort','ASC');
		}else{
			parent_li.siblings('.sort_value').data('sort','ASC');
		}
		load_videos_list (scrum_id, limit, offset, order_by, sort, stage_filter);
	});

	function load_videos_list (scrum_id, limit, offset, order_by, sort, stage_filter) {

		$.ajax({
			type: 'POST',
			url:  base_url + 'Video_Rights',
			data: {'deal_stage':scrum_id, 'order_by':order_by, 'sort':sort, 'limit':limit, 'offset':offset, 'filter_by_curr_stage':stage_filter},
			success: function (response) {
				$('.preloadr-div').hide();
				response = JSON.parse(response);

				if (response.view != '') {
					var videos_list = response.view;
					$('#'+scrum_id).html('');
					$('#'+scrum_id).append(videos_list);
				}
			}
		});
	}

    $('#save_staff').click(function(e){
        e.preventDefault();

        var staff_id = $('#staff_id').val();
        var direct_url = $(this).attr('href');
        if(staff_id.length > 0){
            $.ajax({
                type: "POST",
                url: base_url + 'update_staff',
                data: {staff_id:staff_id,uid:uid},
                success: function (data) {
                    altair_helpers.custom_preloader_hide();
                    data = JSON.parse(data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Staff Assigned',
                        text: 'Staff Assigned Successfully!',
                    });

                }
            });
        }else {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Staff Member field required',
            });
        }

    });
    $('#save_type').click(function(e){
        e.preventDefault();

        var video_type = $('#video_type').val();
        var direct_url = $(this).attr('href');
        if(video_type.length > 0){
            $.ajax({
                type: "POST",
                url: base_url + 'video_type',
                data: {video_type:video_type,uid:uid},
                success: function (data) {
                    altair_helpers.custom_preloader_hide();
                    data = JSON.parse(data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Video Type',
                        text: 'Video Type Assigned Successfully!',
                    });

                }
            });
        }else {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Video Type field required',
            });
        }

    });

    $('.generate-link').click(function(e){
        e.preventDefault();
        var days_expire = $('#days-expire').val();
        var type = $(this).data('type');
        if(days_expire.length > 0){
            $.ajax({
                type: "POST",
                url: base_url + 'signer_link',
                data: {days_expire:days_expire,type:type,uid:uid},
                success: function (data) {
                    altair_helpers.custom_preloader_hide();
                    data = JSON.parse(data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Create Link',
                        text: 'Link Created Successfully!',
                    });
                  location.reload();
                }
            });
        }else {
            Swal.fire({
                icon: 'Error',
                title: 'Validation Error',
                text: 'Days Field Required',
            });
        }

    });
    $('.update_link_date').click(function(e) {
        var id = $(this).data('id');
        UIkit.modal.confirm('Are you sure you want to update this link date?', function(){
            $.ajax({
                type: "POST",
                url: base_url + 'renew_date_signerlink',
                data: {id: id},
                success: function (data) {
                    altair_helpers.custom_preloader_hide();
                    data = JSON.parse(data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Update Expire Date',
                        text: 'Update Expire Date Successfully!',
                    });

                }
            });

    });
    });
    $('.raw-s3-remove').on('click',function (){
        var id = $(this).data('id');
        var conf = confirm('Are you sure to want delete this raw video?');
        if(conf){
            $.ajax({
                type: "POST",
                url: base_url + "raw-video-delete",
                data: {id:id},
                success: function (data) {
                    //data = JSON.parse(data);
                    console.log(data);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        $('#form_validation21').parsley().reset();
                        $.each(data.error, function (i, v) {
                            $('#' + i).parent().parent().find('.error').html(v);
                        });

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();


                        //location.reload();
                    } else {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();


                        //location.reload();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });
        }
    });
    $('#raw-video-upload').on('click',function (){


        var conf = confirm('Are you sure to want add new raw video?');
        if(conf) {
            var modal = UIkit.modal("#raw-upload-modal");
            modal.show();
        }

    });
    $('input[name="raw_new"]').fileuploader({
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
            url: base_url+"new_raw_upload_video?key="+uid,
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
                ///$('#story-file-div').hide();
                $('#story-feed-div').show();
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

                item.html.find('.fileuploader-action-remove').remove();
                var modal = UIkit.modal("#watermark-video");
                modal.hide();
                $('#w_s3_url').html(data.s3_url);
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
                item.html.find('.fileuploader-action-remove').remove();
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

            /* $.post(base_url+'remove_file', {
                 file: item.name
             })  .done(function() {
                 on_reomve(item.title_new);
             })*/

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


    $('#exclusive-partners-list-story').on('change',function (){
        console.log(1);
        partner_story();
    });

    $('#video_rights_claimed').click(function(e) {
            $.ajax({
                type: "POST",
                url: base_url + 'rights-claimed',
                data: $('#video-rights-form').serialize(),
                success: function (data) {
                    altair_helpers.custom_preloader_hide();
                    data = JSON.parse(data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Video Right Claimed',
                        text: 'Video Right Claimed Successfully!',
                    });
                    setTimeout(function (){
                        window.location.href = base_url+'video_rights';
                    },5000)

                }
            });


    });
    $('#video_compilation_claimed').click(function(e) {
        $.ajax({
            type: "POST",
            url: base_url + 'compilation-claimed',
            data: $('#video-compilation-form').serialize(),
            success: function (data) {
                altair_helpers.custom_preloader_hide();
                data = JSON.parse(data);
                Swal.fire({
                    icon: 'success',
                    title: 'Video Claimed',
                    text: 'Video Claimed Successfully!',
                });
                setTimeout(function (){
                    window.location.href = base_url+'video_rights';
                },5000)

            }
        });


    });


});

function partner_story(){
    var id = $('#exclusive-partners-list-story').val();
    console.log(2);
    var dataarray = [];
    var select_options = '';
    $('#story_feed_id').html();
    if(id){
        $.ajax({
            type: 'POST',
            url: base_url + 'mrss_partner2',
            data: {id: id},
            async: false,
            success: function (data) {
                data = JSON.parse(data);

                if (data.code == 200) {
                    dataarray = data.data;

                    var option_ids = [];
                    for (i = 0; i < dataarray.length; i++) {
                        if (option_ids.indexOf(dataarray[i].id) == -1) {
                            select_options += '<option value="'+dataarray[i].id+'" pid="'+dataarray[i].partner_id+'">'+dataarray[i].url+'</option>';
                        }
                    }

                    $('#story_feed_id').html(select_options);

                }
            }
        });
    }

}






