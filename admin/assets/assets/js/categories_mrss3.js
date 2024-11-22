var dt_table_export;
var ev_table_export;
var soryFlast = parseInt(parseInt($('#dt_tableExport thead').find('th').length) - 1);
var statuss;
var statusAdd;
var pubdate;
var pubdateAdd;
var pubdelay;
var pubdelayAdd;
var selected_feed_id;
var typep;
var typepAdd;
var is_ev_table_initalized = false;

$(function () {


    var feeds_search_table = $('#feeds-list-table').DataTable({
        "columnDefs": [{ "visible": false, "targets": [0, 1] }, { "searchable": false, "targets": 2 }],
    });

    // datatables
    altair_datatables.dt_tableExport();
    $(".view_enqueued_videos").parent().css({
        "display": "flex",
        "justify-content": "space-between"
    });
    dt_table_export.on('draw.dt', function () {
        selected_feed_id = dt_table_export.ajax.json().first_row_id;
        if (!is_ev_table_initalized) {
            enqueued_videos.dt_enqueued_videos();
            is_ev_table_initalized = true;
        }
    });

    pubdate = $('#pub_date_e').selectize();
    if (pubdate.length > 0) {
        pubdate = pubdate[0].selectize;
    }
    pubdateAdd = $('#pub_date').selectize();

    if (pubdateAdd.length > 0) {
        pubdateAdd = pubdateAdd[0].selectize;
    }



    typep = $('#partner_type_e').selectize();
    if (typep.length > 0) {
        typep = typep[0].selectize;
    }
    typepAdd = $('#partner_type').selectize();

    if (typepAdd.length > 0) {
        typepAdd = typepAdd[0].selectize;
    }


    statuss = $('#status_e').selectize();

    if (statuss.length > 0) {
        statuss = statuss[0].selectize;
    }

    statusAdd = $('#status').selectize();

    if (statusAdd.length > 0) {
        statusAdd = statusAdd[0].selectize;
    }

    type = $('#type_e, #type').selectize();

    if (type.length > 0) {
        type = type[0].selectize;
    }

    partner = $('#partner_e, #partner').selectize();

    if (partner.length > 0) {
        partner = partner[0].selectize;
    }

    feed_videos = $('#feed_videos').selectize({
        plugins: {
            'remove_button': {
                label: ''
            }
        },
    });

    if (feed_videos.length > 0) {
        feed_videos = feed_videos[0].selectize;
    }

    $('#type').on('change', function () {
        if ($('#type').val() == 0) {
            $("#partner").prop('required', true);
            $('#partner').closest('.uk-grid').show();
            $('#type_partner').show();
        } else {
            $("#partner").prop('required', false);
            $('#partner').closest('.uk-grid').hide();
            $('#type_partner').hide();
        }
    });
    $('#type_e').on('change', function () {
        if ($('#type_e').val() == 0) {
            $("#partner_e").prop('required', true);
            $('#partner_e').closest('.uk-grid').show();
            $('#type_partner_e').show();
        } else {
            $("select#partner_e option").attr('selected', false);
            $("select#partner_e option").attr('value', '');
            $("#partner_e").attr('data-parsley-id', '');
            $("#partner_e").prop('required', false);
            $('#partner_e').closest('.uk-grid').hide();
            $('#type_partner_e').hide();
        }
    });

    $('#add').on('click', function () {

        $('#form_validation2').parsley().reset();
        $('#form_validation2')[0].reset();
        statusAdd.setValue('');
        type.setValue('');
        var modal = UIkit.modal("#add_model");
        modal.show();

    });
    $(".single").eq(4).css('z-index', 9999);
    $(".single").eq(5).css('z-index', 9999);
    $(".single").eq(6).css('z-index', 9999);
    $(".single").eq(7).css('z-index', 9999);
    $('#add_from').on('click', function (e) {

        e.preventDefault();

        $('#form_validation2').submit();

    });

    $('#edit_from').on('click', function (e) {

        e.preventDefault();

        $('#form_validation3').submit();

    });

    $('#form_validation2').on('submit', function (e) {

        console.log($('#form_validation2').serialize());
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: base_url + "add_category_mrss",
            data: $('#form_validation2').serialize(),
            success: function (data) {
                data = JSON.parse(data);
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

                    console.log(data.error);

                    $('#form_validation2').parsley().reset();
                    $.each(data.error, function (i, v) {
                        $('#' + i).parent().parent().find('.error').html(v);
                    });

                } else if (data.code == 200) {
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();

                    var modal = UIkit.modal("#add_model");
                    modal.hide();
                    $('#form_validation2').parsley().reset();
                    $('#form_validation2')[0].reset();
                    dt_table_export.ajax.reload();
                } else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }

            },
            error: function (data) {
                console.log(data);
                $('#err-msg').attr('data-message', 'Something is going wrong!');
                $('#err-msg').click();
            }
        });

    });

    $(document).on('click', '.edit-category', function () {

        var id = $(this).data('id');

        $('#form_validation3').parsley().reset();
        $('#form_validation3')[0].reset();
        $.ajax({
            type: 'POST',
            url: base_url + 'get_category_mrss',
            data: { id: id },
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
                    $('#form_validation3').parsley().reset();

                } else if (data.code == 200) {
                    var modal = UIkit.modal("#edit_model");
                    modal.show();
                    var pval = ''
                    $.each(data.data, function (i, v) {
                        $('#' + i + '_e').val(v);
                        if (i == 'url' && v.indexOf('/') > -1) {
                            var url = v.split('/');
                            url = url[url.length - 1];
                            $('#' + i + '_e').val(url);
                            $('#' + i + '_e').focus();
                        }
                        if ($('#' + i + '_e').hasAttr('select-s')) {
                            statuss.setValue(v);
                        }
                        if ($('#' + i + '_e').hasAttr('select-m')) {
                            pubdate.setValue(v);
                        }

                        if ($('#' + i + '_e').hasAttr('select-t')) {
                            if (v == 0) {
                                $('#type_partner_e').show();
                            } else {
                                $('#type_partner_e').hide();
                            }
                            $('#type_e').data('selectize').setValue(v);
                        }
                        if ($('#' + i + '_e').hasAttr('select-tt')) {
                            $('#partner_type_e').data('selectize').setValue(v);
                        }
                        if (i == 'partner') {
                            pval = v;
                        }
                        if (i == 'type' && v == '0') {
                            $('#partner_e').data('selectize').setValue(pval);
                            $("#partner_e").prop('required', true);
                            $('#partner_e').closest('.uk-grid').show();
                        }
                        if (i == 'name' && v == '1') {
                            $("#cb_" + i).prop('checked', true);
                        }
                        if (i == 'location' && v == '1') {
                            $("#cb_" + i).prop('checked', true);
                        }
                        if (i == 'filmed_on' && v == '1') {
                            $("#cb_" + i).prop('checked', true);
                        }
                        if (i == 'wgid' && v == '1') {
                            $("#cb_" + i).prop('checked', true);
                        }
                        if (i == 'license_signature' && v == '1') {
                            $("#cb_" + i).prop('checked', true);
                        }
                        if (i == 'wooglobe_signature' && v == '1') {
                            $("#cb_" + i).prop('checked', true);
                        }
                        if (i == 'is_title_2' && v == '1') {
                            $("#cb_" + i).prop('checked', true);
                        }

                        if ((i == 'mon' || i == 'tue' || i == 'wed' || i == 'thu' || i == 'fri' || i == 'sat' || i == 'sun' || i == 'sun') && v == 1) {
                            $("#cb_" + i).prop('checked', true);
                        }
                        $('#' + i + '_e').focus();
                    });


                } else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }


            },
            error: function (msg) {
                console.log("ERROR");
                console.log(msg);

            }

        });

    });

    $('#form_validation3').on('submit', function (e) {

        e.preventDefault();

        $.ajax({
            type: "POST",
            url: base_url + "update_category_mrss",
            data: $('#form_validation3').serialize(),
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
                    $('#form_validation3').parsley().reset();
                    $.each(data.error, function (i, v) {
                        $('#' + i + '_e').parent().parent().find('.error').html(v);
                    });

                } else if (data.code == 200) {
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();

                    var modal = UIkit.modal("#edit_model");
                    modal.hide();
                    $('#form_validation3').parsley().reset();
                    $('#form_validation3')[0].reset();
                    dt_table_export.ajax.reload();
                } else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }

            },
            error: function (msg) {
                console.log(msg);
                $('#err-msg').attr('data-message', 'Something is going wrong!');
                $('#err-msg').click();
            }
        });

    });

    $(document).on('click', '.delete-category', function () {

        var id = $(this).data('id');
        UIkit.modal.confirm('Are you sure to want delete this?', function () {
            $.ajax({
                type: 'POST',
                url: base_url + 'delete_category_mrss',
                data: { id: id },
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
                        dt_table_export.ajax.reload();

                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }


                }
            });

        });


    });

    $(document).on('click', '.preview-feed', function () {

        var id = $(this).data('id');
        var feed_url = $(this).data('url');

        $.ajax({
            type: 'POST',
            url: base_url + 'get-feed-data',
            data: { id: id, feed_url: feed_url },
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
                    $('#form_validation3').parsley().reset();

                } else if (data.code == 200) {
                    var modal = UIkit.modal("#edit_model");
                    modal.show();
                    var pval = ''
                    $.each(data.data, function (i, v) {
                        $('#' + i + '_e').val(v);
                        if (i == 'url' && v.indexOf('/') > -1) {
                            var url = v.split('/');
                            url = url[url.length - 1];
                            $('#' + i + '_e').val(url);
                        }
                        if ($('#' + i + '_e').hasAttr('select-s')) {
                            statuss.setValue(v);
                        }
                        if ($('#' + i + '_e').hasAttr('select-t')) {
                            $('#type_e').data('selectize').setValue(v);
                        }
                        if (i == 'partner') {
                            pval = v;
                        }
                        if (i == 'type' && v == '0') {
                            $('#partner_e').data('selectize').setValue(pval);
                            $("#partner_e").prop('required', true);
                            $('#partner_e').closest('.uk-grid').show();
                        }

                        $('#' + i + '_e').focus();
                    });

                } else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }


            }
        });

    });
    $(document).on('click', '.remove-from-feed', function () {

        var vid = $(this).data('vid');
        var fid = $(this).data('fid');
        var this_elem = $(this).closest('div.video-feed-card');
        UIkit.modal.confirm('Are you sure to want delete this video from this feed?', function () {
            $.ajax({
                type: 'POST',
                url: base_url + 'remove_from_feed',
                data: { vid: vid, fid: fid },
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
                        $/*('#suc-msg').attr('data-message',data.message);
                        $('#suc-msg').click();*/
                        //dt_table_export.ajax.reload();
                        //this_elem.remove();
                        window.location.reload();

                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }


                }
            });

        });
    });
    $(document).on('click', '.add-to-feed', function () {

        var fid = $(this).data('fid');
        var vid = $('#feed_videos').val(); console.log(vid);
        var this_elem = $(this).closest('div.video-feed-card');
        //UIkit.modal.confirm('Are you sure to want delete this video from this feed?', function(){
        $.ajax({
            type: 'POST',
            url: base_url + 'add_to_feed',
            data: { vid: vid, fid: fid },
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

                    window.location.reload();

                } else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }


            }
        });

    });

    $(document).on('click', '.publish-feed', function () {

        var fid = $(this).data('fid');
        var status = $(this).data('status');
        var message = 'Want publish this feed?';
        var pub_unpub = 1;
        if (status == 1) {
            pub_unpub = 0;
            message = 'Are you sure you want to unpublish this feed?';
        }

        UIkit.modal.confirm(message, function () {
            $.ajax({
                type: 'POST',
                url: base_url + 'publish_feed',
                data: { status: pub_unpub, fid: fid },
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

                        window.location.reload();

                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }


                }
            });

        });
    });
    $(document).on('click', '.secure-feed', function () {

        var id = $(this).data('id');
        var feed_url = $(this).data('url');
        var feed_id = $(this).attr('id');

        $.ajax({
            type: 'POST',
            url: base_url + 'secure-feed-data',
            data: { id: id, feed_url: feed_url },
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
                    $('#form_validation3').parsley().reset();

                } else if (data.code == 200) {
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();
                    window.location.reload();
                } else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }


            }
        });

    });

    $(document).on('click', '.view_enqueued_videos', function () {
        selected_feed_id = $(this).data('id');
        var modal = UIkit.modal("#enqueued-videos-modal");
        modal.show();
        ev_table_export.ajax.url(base_url + 'get_enqueued_videos/' + selected_feed_id).load();
    });
    $(document).on('click', '.queue_to_mrss_direct_btn', function () {
        var qid = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: base_url + 'queue_to_mrss_direct',
            data: { queue_id: qid },
            success: function (data) {
                data = JSON.parse(data);
                if (data.code == 200) {
                    $('#suc-msg').attr('data-message', data.message);
                    $('#suc-msg').click();
                    ev_table_export.ajax.url(base_url + 'get_enqueued_videos/' + selected_feed_id).load();
                    dt_table_export.ajax.reload();
                }
                else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            },
            error: function (msg) {
                console.log("ERROR---!", msg);
            }
        });
    });
    $(document).on('click', '.remove_enqueued_video_btn', function () {
        var qid = $(this).data('id');
        UIkit.modal.confirm('Are you sure to want remove this video from this queue?', function () {
            $.ajax({
                type: 'POST',
                url: base_url + 'remove_from_queue',
                data: { queue_id: qid },
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.code == 200) {
                        $('#suc-msg').attr('data-message',data.message);
                        $('#suc-msg').click();
                        ev_table_export.ajax.url(base_url + 'get_enqueued_videos/' + selected_feed_id).load();
                        dt_table_export.ajax.reload();
                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }
                }
            });

        });
    });
});

altair_datatables = {
    dt_tableExport: function () {
        var $dt_tableExport = $('#dt_tableExport'),
            $dt_buttons = $dt_tableExport.prev('.dt_colVis_buttons');
        if ($dt_tableExport.length) {
            dt_table_export = $dt_tableExport.DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": base_url + 'get_categories_mrss',
                "columnDefs": [{ orderable: false, targets: [soryFlast, 0, 4] }]
            });
        }
    }
};
enqueued_videos = {
    dt_enqueued_videos: function () {
        var $dt_enqueued_videos = $('#dt_enqueued_videos'),
            $ev_buttons = $dt_enqueued_videos.prev('.dt_colVis_buttons');
        if ($dt_enqueued_videos.length) {
            ev_table_export = $dt_enqueued_videos.DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": base_url + 'get_enqueued_videos/' + selected_feed_id,
                "columnDefs": [{ orderable: false, targets: [0] }]
            });
        }
    }
};