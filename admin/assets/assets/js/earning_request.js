var table_export;
var soryFlast = parseInt(parseInt($('thead').find('th').length) - 1);
var earning_type_id;
var social_source_id;
var partner_id;
var media_id;
$(function () {
    // datatables
    altair_datatables.dt_tableExport();


    $(document).on('click', '.status-earning', function () {

        var id = $(this).data('id');
        var status = $(this).data('status');
        UIkit.modal.confirm('Are you sure?', function () {
            $.ajax({
                type: 'POST',
                url: base_url + 'update_earning_request',
                data: { id: id, status: status },
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
                        table_export.ajax.reload();

                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }


                }
            });

        });


    });

    $(document).on('click', '.play-video', function () {

        var id = $(this).data('id');

        $.ajax({
            type: 'POST',
            url: base_url + 'get_video',
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
                    console.log(data.data);
                    html = '';
                    if (data.data.embed == 0) {
                        yurl = data.data.url;
                        nwyurl = yurl.indexOf("https://www.youtube.com");
                        if (nwyurl == 0) {
                            youtubefull = yurl.split('v=');
                            youtubeid = youtubefull[1];
                            youtubesecondsplit = youtubefull[1].split('&');
                            if (youtubesecondsplit[1]) {
                                youtubefull[1] = youtubesecondsplit[0];
                            }
                            html = '<iframe width="400" id="myIframe" src="https://www.youtube.com/embed/' + youtubeid + '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                        }
                        nwyvimeo = yurl.indexOf("https://vimeo.com");
                        if (nwyvimeo == 0) {
                            vimeofull = yurl.split('vimeo.com/');
                            vimeoid = vimeofull[1];
                            html = '<iframe width="400" id="myIframe" src="https://player.vimeo.com/video/' + vimeoid + '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
                        }
                        nwyinsta = yurl.indexOf("https://www.instagram.com");
                        if (nwyinsta == 0) {
                            vimeofull = "https://api.instagram.com/oembed/?url=" + yurl;
                            $.ajax({
                                type: "GET",
                                url: vimeofull,
                                data: {},
                                success: function (data) {
                                    //data = JSON.parse(data);
                                    html = data.html;
                                    $('#play').html(html);

                                }
                            });
                        }
                        $(".uk-modal-close").click(function () {
                            $("iframe#myIframe").remove();
                            $("iframe.instagram-media").remove();

                        });
                        $("body").click(function () {
                            $("iframe#myIframe").remove();
                            $("iframe.instagram-media").remove();
                        });
                    } else {
                        html = data.data.url;
                    }
                    $('#vt').text(data.data.title);
                    $('#play').html(html);
                    var modal = UIkit.modal("#play_model");
                    modal.show();

                } else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }


            }
        });




    });

    $('#earning_req_sel_all').on('change', function () {
        var isChecked = $(this).prop('checked');
        $(".select_earning_row").prop('checked', isChecked);
    });
    $(document).on('change', '.select_earning_row', function () {
        if (!$(this).prop('checked')) {
            $('#earning_req_sel_all').prop('checked', false);
        }
        else if ($('.select_earning_row:checked').length === $('.select_earning_row').length) {
            $('#earning_req_sel_all').prop('checked', true);
        }
    });

    $(document).on('click', '.bulk_earning_req', function () {

        var ids = [];
        $('.select_earning_row:checked').each(function () {
            ids.push($(this).data('id'));
        });
        var status = $(this).data('status');
        if (ids.length == 0) {
            $('#err-msg').attr('data-message', 'No Rows Selected!');
            $('#err-msg').click();
        }
        else {
            UIkit.modal.confirm('Are you sure?', function () {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'update_earning_request_bulk',
                    data: { ids: ids, status: status },
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 201) {
                            $('#err-msg').attr('data-message', data.message);
                            $('#err-msg').click();
                        } else if (data.code == 200) {
                            $('#suc-msg').attr('data-message', data.message);
                            $('#suc-msg').click();
                            table_export.ajax.reload();
                        } else {
                            $('#err-msg').attr('data-message', 'Something is going wrong!');
                            $('#err-msg').click();
                        }
                    }
                });
            });
        }
    });

});

altair_datatables = {
    dt_tableExport: function () {
        var $dt_tableExport = $('#dt_tableExport'),
            $dt_buttons = $dt_tableExport.prev('.dt_colVis_buttons');
        if ($dt_tableExport.length) {
            table_export = $dt_tableExport.DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, 500, -1],
                    [10, 25, 50, 100, 500, 'All']
                ],
                "processing": true,
                "serverSide": true,
                "ajax": base_url + 'get_earning_requests',
                "columnDefs": [{ orderable: false, targets: [soryFlast, 0] }]
            });
        }
    }
};