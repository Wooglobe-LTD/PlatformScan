var pq_table_export;
var last_index = parseInt(parseInt($('#publication_queue_table thead').find('th').length));

$(function () {
    $('#date-type-fltr').selectize()[0].selectize.clear();
    
    // datatables
    altair_datatables.publication_queue_table();
    $('#sheet-filter-apply').on('click', function () {
        pq_table_export.draw();
    });
    $('#sheet-filter-reset').on('click', function () {
        var date_type = $('#date-type-fltr').selectize();
        date_type[0].selectize.clear();
        $('#date-from').val('');
        $('#date-to').val('');
        pq_table_export.draw();
    });
    $('#sort-by').on('change', function () {
        pq_table_export.draw();
    });
    $('#sort-dir').on('change', function () {
        pq_table_export.draw();
    });
    
    $(document).on('click', '.sheet-dropbox-check', function (e) {
        var id = $(this).data('id');
        $('#dropbox-error').html('');
        $('#dropbox-search-status').html('');
        $('#dropbox-path-list').html('');
        $.ajax({
            type: 'POST',
            url: base_url + 'dropbox_search_file',
            data: { unique_key: id },
            success: function (data) {
                data = JSON.parse(data);
                if (data.code == 404) {
                    $('#dropbox-search-status').html('Video does not exist in any of the Dropbox folders');
                }
                else if (data.code == 301) {
                    ret = window.open(data.url, '_blank');
                    ret.addEventListener('load', () => {
                        console.log(Popup.document.body.innerText);
                    });
                    $('#err-msg').attr('data-message', 'Token refreshed. Please re-open the modal.');
                    $('#err-msg').click();
                }
                else if (data.code == 200) {
                    var paths = data.data;
                    if (paths.length > 0) {
                        $('#dropbox-search-status').html('Video exists in the following folders');
                    }
                    else {
                        $('#dropbox-search-status').html('Video does not exist in any of the Dropbox folders');
                    }
                    for (var i = 0; i < paths.length; i++) {
                        $('#dropbox-path-list').append('<li>' + paths[i] + '</li>');
                    }
                }
                else if (data.code == 201) {
                    $('#dropbox-error').html(data.message);
                }
                else {
                    $('#dropbox-error').html('Something is going wrong!');
                }
                var modal = UIkit.modal("#dropbox-modal");
                modal.show();
            }
        });
    });
    
    $(document).on('change', '.content_writer_dpdn', function (e) {
        var lead_id = $(this).data('id');
        var staff_id = $(this).val();
        $.ajax({
            type: 'POST',
            url: base_url + 'assign_content_writer',
            data: { lead_id: lead_id, staff_id: staff_id },
            success: function (res) {
                res = JSON.parse(res);
                if (res.code == 200) {
                    $('#suc-msg').attr('data-message', res.message);
                    $('#suc-msg').click();
                }
                else {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            }
        });
    });

});

altair_datatables = {
    publication_queue_table: function () {
        var $publication_queue_table = $('#publication_queue_table'),
        $dt_buttons = $publication_queue_table.prev('.dt_colVis_buttons');
        
        if ($publication_queue_table.length) {
            pq_table_export = $publication_queue_table.DataTable({
                // dom: 'Bfrltip',
                "processing": true,
                "serverSide": true,
                "columnDefs": [
                    { targets: [9, 10], orderable: false },
                    { targets: [9, 10], searchable: false }
                ],
                "pageLength": 50,
                "scrollX": true,
                "bPaginate": true,
                "bSort": true,
                "ordering": true,
                "bAutoWidth": false,
                "searching": true,
                "createdRow": function (row, data) {
                    if (data[last_index]['is_deleted'] == 1) {
                        $(row).css('background-color', '#ffbbaa');
                    }
                    else if (data[last_index]['has_issue'] == true) {
                        $(row).css('background-color', '#ffdd88');
                    }
                    else {
                        if (data[1] === "High") {
                            $(row).find('td').eq(1).css('background-color', '#aaffaa')
                        }
                    }
                },
                "ajax": {
                    url: base_url + 'get_publication_queue',
                    type: "GET",
                    data: function (data) {
                        data.date_type = $('#date-type-fltr').val();
                        data.date_from = $('#date-from').val();
                        data.date_to = $('#date-to').val();
                        data.sort_by = $('#sort-by').val();
                        data.sort_dir = $('#sort-dir').val();
                    }
                },
            });
        }
    }
};