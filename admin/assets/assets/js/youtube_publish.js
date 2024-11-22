$(function () {

    var seo_optimized = false;
    var title_limit = 0;
    var title = seo_optimized ? $('#youtube_publish_title_seo') : $('#youtube_publish_title');
    var tags_limit = 0;
    var tags = seo_optimized ? $('#youtube_publish_tags_seo') : $('#youtube_publish_tags');

    $(document).on('click', '#btn-publish-youtube', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var wgid = $(this).data('wgid');
        $('#ed_video_id').val(id);
        $('#ed_wgid').val(wgid);

        $('#seo-optimized-check').prop('checked', seo_optimized);
        handle_seo_check();

        var publish_footer = '';
        $('#yt_footer p').each(function () {
            publish_footer += $(this).text();
        });

        var modal = UIkit.modal("#youtube-modal");
        modal.show();

        $.ajax({
            type: "POST",
            url: base_url + 'get-publishing-data',
            data: { video_id: id, type: 'YouTube' },
            success: function (data) {
                data = JSON.parse(data);
                $('#suc-msg').attr('data-message', data.message);
                $('#suc-msg').click();
                var select_channel = data.data.channel;
                var select_category = data.data.category;
                var select_youtube_publish_status = data.data.youtube_publish_status;

                $.each(data.data, function (i, v) {
                    if (i == 'publish_now') {
                        if (v == '1') {
                            $('#publish_now_youtube').iCheck('check');
                        }
                        else if (v == '0') {
                            $('#publish_schedule_youtube').iCheck('check');
                            $('#dvPinNo2').show();
                        }
                    }
                    else if (i == 'channels') {
                        var option = '';
                        option += '<option value="UCbbtHuBeqqlRB9yNr_Hpc7w">WooGlobe Rights Management</option>';
                        $('#youtube_channel').append(option);
                        for (var x = 0; x < v.length; x++) {
                            option += '<option value="' + v[x].id + '>' + v[x].title + '</option>';
                        }
                        $('#youtube_channel').append(option);
                        var channel = $('#youtube_channel').selectize();
                        if (channel.length > 0) {
                            channel = channel[0].selectize;
                            channel.setValue(channel.search('WooGlobe Rights Management').items[0].id);
                        }
                    }
                    else if (i == 'categories') {
                        var option = '';
                        for (var x = 0; x < v.length; x++) {
                            option += '<option value="' + v[x].id + '">' + v[x].title + '</option>';
                        }
                        $('#youtube_category').append(option);

                        var category = $('#youtube_category').selectize();
                        if (category.length > 0) {
                            category = category[0].selectize;
                            category.setValue(category.search('Entertainment').items[0].id);
                        }
                    }
                    else if (i == 'category') {
                        var category = $('#youtube_category').selectize();;
                        if (category.length > 0 && select_category) {
                            category = category[0].selectize;
                            category.setValue(select_category);
                        }
                    }
                    else if (i == 'youtube_publish_status') {
                        var pstatus = $('#youtube_publish_status').selectize();
                        if (pstatus.length > 0) {
                            pstatus = pstatus[0].selectize;
                            pstatus.setValue(select_youtube_publish_status);
                        }
                    }
                    else if (i == 'channel') {
                        var channel = $('#youtube_channel').selectize();
                        if (channel.length > 0) {
                            channel = channel[0].selectize;
                            channel.setValue(select_channel);
                        }
                    }
                    else if (i == 'unique_key') {
                        $('#video_id').html(v);
                    }
                    else if (i == 'publish_title') {
                        v = v.length > 100 ? v.substring(0, 100) : v;
                        v = v.trim();
                        v += " || WooGlobe";
                        $('#youtube_publish_title').val(v);
                    }
                    else {
                        $('#youtube_' + i).val(v);
                    }
                    $('#youtube_' + i).focus();
                });

                title_limit = title.val().length;
                title_limit = parseInt(100 - parseInt(title_limit));
                $('.counter-title').text(title_limit);

                tags_limit = tags.val().length;
                tags_limit = parseInt(500 - parseInt(tags_limit));
                $('.counter-tags').text(tags_limit);

                $('#yt_desc_footer').val(publish_footer);
                $("#youtube_category").selectize({
                    persist: false,
                    hideSelected: true,
                    create: function (input) {
                        return {
                            value: input,
                            text: input
                        }
                    }
                });
                $("#youtube_channel").selectize({
                    persist: false,
                    hideSelected: true,
                    create: function (input) {
                        return {
                            value: input,
                            text: input
                        }
                    }
                });
                $("#youtube_video_type").selectize({
                    persist: false,
                    hideSelected: true,
                    create: function (input) {
                        return {
                            value: input,
                            text: input
                        }
                    }
                });
            },
            error: function (err, data) {
                console.log(err.responseText);
                console.log(data);
                $('#err-msg').attr('data-message', err.message);
                $('#err-msg').click();
            },
            complete: function () {
                setTimeout(function () {
                    get_seo_optimized_data();
                }, 500);
            }
        });
    });

    if (access.can_distribute) {

        $('input[name="publish_now_youtube"]').on('change', function () {
            var scheduling = $('input[name="publish_now_youtube"]:checked').val();
            if (scheduling == 0) {
                $('#dvPinNo2').show();
                $("#publish-yt-form").parsley().destroy();
                $('#youtube_publish_date').prop('required', true);
                $('#youtube_publish_date').prop('disabled', false);
                $('#youtube_publish_time').prop('required', true);
                $('#youtube_publish_time').prop('disabled', false);
                $("#publish-yt-form").parsley();
            } else {
                $('#dvPinNo2').hide();
                $("#publish-yt-form").parsley().destroy();
                $('#youtube_publish_date').prop('required', false);
                $('#youtube_publish_date').prop('disabled', true);
                $('#youtube_publish_time').prop('required', false);
                $('#youtube_publish_time').prop('disabled', true);
                $("#publish-yt-form").parsley();
            }
        });

        $(document).on('click', '#publish-yt-btn', function (e) {
            e.preventDefault();
            publish_youtube();
        });
        function publish_youtube() {
            var formData = {
                'video_id': $('#ed_video_id').val(),
                'wgid': $('#ed_wgid').val(),
                'title': title.val(),
                'description': seo_optimized ? $('#youtube_publish_description_seo').val() : $('#youtube_publish_description').val(),
                'desc_footer': $('#yt_desc_footer').val(),
                'tags': tags.val(),
                'channel': $('#youtube_channel').val(),
                'category': $('#youtube_category').val(),
                'publish_status': $('#youtube_publish_status').val(),
                'publish_now': $('#publish_now_youtube').val(),
                'publish_date': $('#youtube_publish_date').val(),
                'publish_time': $('#youtube_publish_time').val(),
                'video_type': $('#youtube_video_type').val()
            }
            
            $.ajax({
                type: "POST",
                url: base_url + "publish-youtube",
                data: formData,
                success: function (data) {

                    data = JSON.parse(data);
                    $('#publish-yt-btn').prop('disabled', false);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        setTimeout(function () {
                            window.location = data.url;
                        }, 1000);
                    } else if (data.code == 201) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                        $('#publish-yt-form').parsley().reset();
                        $.each(data.error, function (i, v) {
                            $('#' + i).parent().parent().find('.error').html(v);
                        });

                    } else if (data.code == 206) {
                        $('#yt-err').html('');
                        $('#yt-err').html('<strong>YouTube Error : <strong>' + data.message + ' ');

                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        var lead_id = $('#lead_id').val();
                        var video_id = data.data.id;
                        if (video_id != null) {
                            $("#published-youtube-url").html('<a href="https://www.youtube.com/watch?v=' + video_id + '" target="_blank">https://www.youtube.com/watch?v=' + video_id + '</a>')
                        }
                        var modal = UIkit.modal("#youtube");
                        modal.hide();
                        $('#form_validation2').parsley().reset();
                        $('#form_validation2')[0].reset();
                        $('#lead_id').val('');
                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }

                },
                error: function () {
                    custom_preloader_hide();
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });
        };
    }

    $(document).on('click', '#btn-delete-youtube', function (e) {
        var ans = confirm("This video will be permanently deleted from Youtube. Press 'Ok' to confirm.");
        if (ans) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id);

            $.ajax({
                url: base_url + 'delete-youtube-video',
                data: { 'type': 'youtube', 'video_id': id },
                type: "POST",
                success: function (data) {
                    data = JSON.parse(data);
                    console.log(data.code);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();
                    } else if (data.code == 206) {
                        $('#err-msg').attr('data-message', data.message.msg);
                        $('#err-msg').click();

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();

                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    });

    function get_seo_optimized_data() {
        var title = $('#youtube_publish_title').val();
        var description = $('#youtube_publish_description').val();
        var targets = $('#seo-targets-area').data('val');
        $(".error").empty();
        $.ajax({
            type: "POST",
            url: base_url + "get-youtube-seo",
            data: { title: title, description: description, target_words: targets },
            cache: false,
            success: function (res) {
                res = JSON.parse(res);
                if (res.code == 200) {
                    if (res.data !== null || typeof data === 'object') {
                        $('#seo-optimized-check').prop('checked', true);
                        handle_seo_check();
                        $.each(res.data, function (i, v) {
                            var element = null;
                            if (i == "title") {
                                element = $('#youtube_publish_title_seo');
                                v += " || WooGlobe";
                            }
                            else {
                                element = $('#youtube_publish_' + i + '_seo').val(v);
                            }
                            element.val(v);
                            element.focus();
                        });
                        handle_seo_check();
                    }
                    else {
                        $("#err-msg").attr("data-message", res.message);
                        $("#err-msg").click();
                    }
                }
                else if (res.code == 201) {
                    $("#err-msg").attr("data-message", res.message);
                    $("#err-msg").click();
                    $.each(res.error, function (i, v) {
                        $('#' + i).parent().parent().find('.error').html(v);
                    });
                }
                else {
                    $("#err-msg").attr("data-message", res.message);
                    $("#err-msg").click();
                }
            },
            error: function () {
                $("#err-msg").attr("data-message", "Something is going wrong!");
                $("#err-msg").click();
            },
        });
    };

    $(document).on('change', '#seo-optimized-check', function () {
        handle_seo_check();
    });
    function handle_seo_check() {
        seo_optimized = $('#seo-optimized-check').prop('checked');
        $("#publish-yt-form").parsley().destroy();
        $('.default_input input, .default_input textarea').each(function () {
            $(this).prop('required', !seo_optimized);
            $(this).prop('disabled', seo_optimized);
        });
        $('.seo_optimized_input input, .seo_optimized_input textarea').each(function () {
            $(this).prop('required', seo_optimized);
            $(this).prop('disabled', !seo_optimized);
        });
        $("#publish-yt-form").parsley();
        if (seo_optimized) {
            $('.default_input').hide();
            $('.seo_optimized_input').show();
        }
        else {
            $('.default_input').show();
            $('.seo_optimized_input').hide();
        }
        title = seo_optimized ? $('#youtube_publish_title_seo') : $('#youtube_publish_title');
        title_limit = title.val().length;
        tags = seo_optimized ? $('#youtube_publish_tags_seo') : $('#youtube_publish_tags');
        tags_limit = tags.val().length;
        title_limit = parseInt(100 - parseInt(title_limit));
        $('.counter-title').text(title_limit);
        tags_limit = parseInt(500 - parseInt(tags_limit));
        $('.counter-tags').text(tags_limit);
    }

    $('.counter-title').text(title_limit);
    $(document).on('keydown', title, function (e) {
        title_limit = title.val().length;
        if (title_limit > 100 && e.keyCode != 8) {
            e.preventDefault();
        } else {
            title_limit = parseInt(100 - parseInt(title_limit));
            $('.counter-title').text(title_limit);
        }
    });
    $('.counter-tags').text(tags_limit);
    $(document).on('keydown', tags, function (e) {
        tags_limit = tags.val().length;
        if (tags_limit > 500 && e.keyCode != 8) {
            e.preventDefault();
        } else {
            tags_limit = parseInt(500 - parseInt(tags_limit));
            $('.counter-tags').text(tags_limit);
        }
    });

});