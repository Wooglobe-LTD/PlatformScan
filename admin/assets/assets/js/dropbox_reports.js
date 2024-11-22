var table_export;
var dt_tableExport;
var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);
var charts = 0;
var chart3 = null;
var chart = null;
var chart2 = null;
$(function() {

    $.ajax({
        type: "GET",
        url: base_url + "update_dropbox_status",
        data: {},
        success: function (data) {
            data = JSON.parse(data);
            if (data.code == 200) {
                console.log(data.message);
                console.log();
                msg = "Updated Job Status";
                $('#suc-msg').attr('data-message', msg);
                $('#suc-msg').click();
            }
            else if(data.code == 301){
                window.open(data.url, '_blank');
            }
        },
        error: function (xhr, status, error) {
            $('#err-msg').attr('data-message', 'Something is going wrong!');
            $('#err-msg').click();
            // var err = eval("(" + xhr.responseText + ")");
            // alert(err.Message);
        }
    });

    // datatables
    altair_datatables.dt_tableExport();

    $('#search').on('click',function () {

        $('#search_field').val(2)
        $('#lead_type').val(-1)
        table_export.draw();
        // dt_tableExport.draw();
        $.ajax({
            type : "POST",
            url  : base_url+'dropbox_pie_report',
            data : {
                type : $('#type').val(),
                rating : $('#rating').val(),
                published : $('#published').val(),
                date_period : $('#date_period').val(),
                if_verified: $("#if_verified").val(),
                date_from : $('#date_from').val(),
                date_to : $('#date_to').val(),
                date_aqution : $('#date_aqution').val(),
                date_from_aqution : $('#date_from_aqution').val(),
                date_to_aqution : $('#date_to_aqution').val(),
                closing_date_from : $('#closing_date_from').val(),
                closing_date_to : $('#closing_date_to').val(),
                stage : $('#stage').val(),
                mrss : $('#mrss').val(),
                search2 : $('#search_field').val()
            },
            success : function (res) {
                // if(charts > 0){
                //     // chart3.destroy();
                //     // chart.destroy();
                //     // chart2.destroy();
                // }
                res = JSON.parse(res);
                // chart = new CanvasJS.Chart("chartContainer",
                // {
                //     title: {
                //         text: "Staff Week wise Trend"
                //     },
                //     axisY: {
                //         title: "Number of Leads"
                //     },

                //     data: [
                //         {
                //             type: "line",
                //             showInLegend: true,
                //             name: "series1",
                //             legendText: "Leads",
                //             dataPoints: res.week.deal
                //         },
                //         {
                //             type: "line",
                //             legendText: "Signed",
                //             name: "series2",
                //             showInLegend: true,
                //             dataPoints: res.week.signed
                //         },
                //         {
                //             type: "line",
                //             legendText: "Not Rated",
                //             name: "series3",
                //             showInLegend: true,
                //             dataPoints: res.week.not_rated
                //         },
                //         {
                //             type: "line",
                //             legendText: "Action Required",
                //             name: "series4",
                //             showInLegend: true,
                //             dataPoints: res.week.action_required
                //         },
                //         {
                //             type: "line",
                //             legendText: "Signed (Rejected, Awaiting Resolution)",
                //             name: "series5",
                //             showInLegend: true,
                //             dataPoints: res.week.signed_reject
                //         },
                //         {
                //             type: "line",
                //             legendText: "Acquired",
                //             name: "series6",
                //             showInLegend: true,
                //             dataPoints: res.week.acquired
                //         },
                //         {
                //             type: "line",
                //             legendText: "Rejected",
                //             name: "series7",
                //             showInLegend: true,
                //             dataPoints: res.week.rejected
                //         },
                //         {
                //             type: "line",
                //             legendText: "Canceled",
                //             name: "series8",
                //             showInLegend: true,
                //             dataPoints: res.week.cancel
                //         },
                //         {
                //             type: "line",
                //             legendText: "Poor",
                //             name: "series9",
                //             showInLegend: true,
                //             dataPoints: res.week.poor
                //         },
                //         {
                //             type: "line",
                //             legendText: "Not Interest",
                //             name: "series10",
                //             showInLegend: true,
                //             dataPoints: res.week.poor
                //         }
                //     ]
                // });
                // chart2 = new CanvasJS.Chart("monthchartContainer",
                //     {
                //         title: {
                //             text: "Staff Month wise Trend"
                //         },
                //         axisY: {
                //             title: "Number of Leads"
                //         },

                //         data: [
                //             {
                //                 type: "line",
                //                 showInLegend: true,
                //                 name: "series1",
                //                 legendText: "Leads",
                //                 dataPoints: res.month.deal
                //             },
                //             {
                //                 type: "line",
                //                 legendText: "Signed",
                //                 name: "series2",
                //                 showInLegend: true,
                //                 dataPoints: res.month.signed
                //             },
                //             {
                //                 type: "line",
                //                 legendText: "Not Rated",
                //                 name: "series3",
                //                 showInLegend: true,
                //                 dataPoints: res.month.not_rated
                //             },
                //             {
                //                 type: "line",
                //                 legendText: "Action Required",
                //                 name: "series4",
                //                 showInLegend: true,
                //                 dataPoints: res.month.action_required
                //             },
                //             {
                //                 type: "line",
                //                 legendText: "Signed (Rejected, Awaiting Resolution)",
                //                 name: "series5",
                //                 showInLegend: true,
                //                 dataPoints: res.month.signed_reject
                //             },
                //             {
                //                 type: "line",
                //                 legendText: "Acquired",
                //                 name: "series6",
                //                 showInLegend: true,
                //                 dataPoints: res.month.acquired
                //             },
                //             {
                //                 type: "line",
                //                 legendText: "Rejected",
                //                 name: "series7",
                //                 showInLegend: true,
                //                 dataPoints: res.month.rejected
                //             },
                //             {
                //                 type: "line",
                //                 legendText: "Canceled",
                //                 name: "series8",
                //                 showInLegend: true,
                //                 dataPoints: res.month.cancel
                //             },
                //             {
                //                 type: "line",
                //                 legendText: "Poor",
                //                 name: "series9",
                //                 showInLegend: true,
                //                 dataPoints: res.month.poor
                //             },
                //             {
                //                 type: "line",
                //                 legendText: "Not Interest",
                //                 name: "series10",
                //                 showInLegend: true,
                //                 dataPoints: res.month.poor
                //             }
                //         ]
                //     });
                // chart3 = new CanvasJS.Chart("chartContainerPie", {
                //     animationEnabled: true,
                //     title: {
                //         text: "Deals By Rating Points"
                //     },
                //     data: [{
                //         type: "pie",
                //         startAngle: 240,
                //         yValueFormatString: "##0.00\"%\"",
                //         indexLabel: "{label} {y}",
                //         dataPoints: res.data
                //     }]
                // });
                // chart3.render();
                // chart.render();
                // chart2.render();
                charts = charts+1;

            },
            error: function(msg){
                console.log(msg);
            }
        });

    });
    $(document).on('click','.individual-report',function () {
        var type = $(this).data('type');
        $('#lead_type').val(type);
        table_export.draw();
    })
    $('#search_reset').on('click',function () {
        var type = $('#type').selectize();
        type[0].selectize.clear();
        var stage = $('#stage').selectize();
        stage[0].selectize.clear();
        var rating = $('#rating').selectize();
        rating[0].selectize.clear();
        var published = $('#published').selectize();
        published[0].selectize.clear();
        var mrss = $('#mrss').selectize();
        mrss[0].selectize.clear();
        $('#date_from').val('');
        $('#date_to').val('');
        $('#closing_date_from').val('');
        $('#closing_date_to').val('');
        dt_tableExport.draw();
    });

    $('#staff_search_reset').on('click',function () {
        var date_period = $('#date_period').selectize();
        date_period[0].selectize.clear();
        var date_aqution = $('#date_aqution').selectize();
        date_aqution[0].selectize.clear();
        var rating = $('#rating').selectize();
        rating[0].selectize.clear();
        var published = $('#published').selectize();
        published[0].selectize.clear();
        $('#date_from').val('');
        $('#date_to').val('');
        $(".date_from_to").show();
        $(".date_from_aqution").show();
        dt_tableExport.draw();
    });
    $("select#date_period").change(function(){
        $(".date_from_to").hide();
    });
    $("select#date_aqution").change(function(){
        $(".date_from_aqution").hide();
    });

    $(document).on('click', '#bulk-upload-btn',function(e){
        // console.log(data_table);
        var rows_selected = table_export.column(0).checkboxes.selected().toArray();
        var upload_already = confirm("Press 'Ok' to overwrite already uploaded videos, otherwise 'Cancel'")
        
        // var rows_selected = [];
        e.preventDefault();
        
        $.ajax({
            url: base_url+"bulk_upload_to_dropbox",
            type: "POST",
            data: {"dropbox_rows" : rows_selected, "overwrite": upload_already},
            success: function(data){
                data = JSON.parse(data);
                console.log(data);
                msg = data.message;
                if(data.code == 200){
                    $('#suc-msg').attr('data-message', msg);
                    $('#suc-msg').click();
                }
            },
            error: function(msg){
                console.log(msg);
                $('#err-msg').attr('data-message', "Something went wrong!");
                $('#err-msg').click();

            },
            complete: function(){
                // console.log("Completed");
            }
        });

    });

    $(document).on('click', '#update-duration', function(e){
        $.ajax({
            url: base_url+"update_durations",
            type: "POST",
            success: function(data){
                data = JSON.parse(data);
                console.log(data);
                msg = data.message;
                if(data.code == 200){
                    $('#suc-msg').attr('data-message', msg);
                    $('#suc-msg').click();
                }
            },
            error: function(msg){
                console.log(msg);
                $('#err-msg').attr('data-message', "Something went wrong!");
                $('#err-msg').click();
            }
        });
    });
});

   


altair_datatables = {
    dt_tableExport: function() {

        dt_tableExport = $('#dt_tableExport');

        $dt_buttons = dt_tableExport.prev('.dt_colVis_buttons');

        if(dt_tableExport.length) {

            dt_tableExport = dt_tableExport.removeAttr('width').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    url : base_url+'get_reports',
                    type: "GET",
                    //data : $('#form_search').serializeObject()
                    data : function (data) {

                        data.type = $('#type').val();
                        data.rating = $('#rating').val();
                        data.published = $('#published').val();
                        data.date_period = $('#date_period').val();
                        data.date_from = $('#date_from').val();
                        data.date_to = $('#date_to').val();
                        data.date_aqution = $('#date_aqution').val();
                        data.date_from_aqution = $('#date_from_aqution').val();
                        data.date_to_aqution = $('#date_to_aqution').val();

                        data.closing_date_from = $('#closing_date_from').val();
                        data.closing_date_to = $('#closing_date_to').val();
                        data.stage = $('#stage').val();


                        data.mrss = $('#mrss').val();
                        data.search2 = $('#search_field').val();
                    }
                },
                "ordering": true,
                "bAutoWidth":false,
                "bPaginate": false,
                "aoColumns ": [
                    { sWidth: "10%"},
                    { sWidth: "20%"},
                    { sWidth: "20%"},
                    { sWidth: "20%"},
                    { sWidth: "20%"},
                    { sWidth: "10%"},
                ]
            });


        }

        var $dt_tableExport_details = $('#dt_tableExport_details');

        var $dt_buttons = $dt_tableExport_details.prev('.dt_colVis_buttons');

        if($dt_tableExport_details.length) {

            table_export = $dt_tableExport_details.removeAttr('width').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": true,
                "ajax": {
                    url : base_url+'get_dropbox_reports_details',
                    type: "GET",
                    data : function (data) {
                        if(data === undefined)
                            data = {};
                        data.type = $('#type').val();
                        data.date_period = $('#date_period').val();
                        data.if_verified = $("#if_verified").val(),
                        data.duration_from = $('#duration_from').val();
                        data.duration_to = $('#duration_to').val();
                        data.date_from = $('#date_from').val();
                        data.date_to = $('#date_to').val();
                        data.closing_date_from = $('#closing_date_from').val();
                        data.closing_date_to = $('#closing_date_to').val();
                        data.date_aqution = $('#date_aqution').val();
                        data.date_from_aqution = $('#date_from_aqution').val();
                        data.date_to_aqution = $('#date_to_aqution').val();
                        data.stage = $('#stage').val();
                        data.rating = $('#rating').val();
                        data.published = $('#published').val();
                        data.mrss = $('#mrss').val();
                        data.search2 = $('#search_field').val();
                        data.lead_type = $('#lead_type').val();
                    },
                    complete: function (data) {
                        console.log(data);
                    },
                    error: function(data){
                        console.log(data);
                    }
                },
                "columnDefs": [
                    {
                        'targets': 0,
                        'checkboxes': {
                            'selectRow': true
                        }
                    }
                ],
                'select': {
                    'style': 'multi'
                },
                "order": [[3, 'desc']],
                "ordering": true,
                "bAutoWidth":false,
                "aoColumns ": [
                ]
            });

            
        }


    }
};
