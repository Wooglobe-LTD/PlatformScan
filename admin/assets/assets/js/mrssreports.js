var table_export;
var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);

$(function() {
    // datatables
    altair_datatables.dt_tableExport();
    //altair_datatables.except_report();

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
    mrss_allcc = $('#all_mrss_cat').selectize({

        plugins: {
            'remove_button': {
                label: 'X'
            }
        },
    });

    // publish_status = $('#publish_status').selectize({
    //     plugins: {
    //         'remove_button': {
    //             label: 'X'
    //         }
    //     },
    // });
    if(publish_status.length > 0){
        publish_status = publish_status[0].selectize;
    }
    $("select#publish_status").change(function(){
        var selected = $(this).children("option:selected").val();
        if(selected == 1){
            publish_status.clear();
            $("#publish_status").hide();
        }else if(selected == 2){
            publish_status.clear();
            $("#publish_status").hide();
        }
    });
    
    if(mrss_allcc.length > 0){
        mrss_allcc = mrss_allcc[0].selectize;
    }
    $("select#mrss_partner_status").change(function(){
        var selectedCountry = $(this).children("option:selected").val();
        if(selectedCountry == 1){
            mrss_allcc.clear();
            $("#all_mrss_partner_cat").hide();
        }else if(selectedCountry == 3){
            mrss_allcc.clear();
            $("#all_mrss_partner_cat").hide();
        } else{
            $("#all_mrss_partner_cat").show();
        }
    });

    $("select#mrss_category_status").change(function(){
        var selectedCountry = $(this).children("option:selected").val();
        if(selectedCountry == 1){
            mrss_cc.clear();
            $("#cartegory_status").hide();
        }else if(selectedCountry == 3){
            mrss_cc.clear();
            $("#cartegory_status").hide();
        } else{
            $("#cartegory_status").show();
        }
    });
    mrss_allstage = $('#all_stage').selectize({

        plugins: {
            'remove_button': {
                label: 'X'
            }
        },
    });

    if(mrss_allstage.length > 0){
        mrss_allstage = mrss_allstage[0].selectize;
    }
    mrss_allrate = $('#all_rating').selectize({

        plugins: {
            'remove_button': {
                label: 'X'
            }
        },
    });

    if(mrss_allrate.length > 0){
        mrss_allrate = mrss_allrate[0].selectize;
    }
    $('#search').on('click',function () {
        table_export.draw();
    });


    $('#search_reset').on('click',function () {
        var mrss_cat = $('#mrss_categories').selectize();
        mrss_cat[0].selectize.clear();
        var stage = $('#all_stage').selectize();
        stage[0].selectize.clear();
        var rating = $('#all_rating').selectize();
        rating[0].selectize.clear();
        var all_partner = $('#all_mrss_cat').selectize();
        all_partner[0].selectize.clear();
        var exmrss = $('#exmrss').selectize();
        exmrss[0].selectize.clear();
        var conmrss = $('#conmrss').selectize();
        conmrss[0].selectize.clear();
        var vqmrss = $('#vqmrss').selectize();
        vqmrss[0].selectize.clear();
        var thmrss = $('#thmrss').selectize();
        thmrss[0].selectize.clear();
        $('#date_from').val('');
        $('#date_to').val('');
        table_export.draw();
    });

});

altair_datatables = {
    dt_tableExport: function() {
        var $dt_tableExport = $('#dt_tableExport');
        //$dt_buttons = $dt_tableExport.prev('.dt_colVis_buttons');

        if($dt_tableExport.length) {
            table_export = $dt_tableExport.removeAttr('width').DataTable({
                //"dom": 'fBltir',
                // order: [0, 'asc'],
                dom: 'Bfrltip',
                fixedHeader: {
                    header: false,
                },
                "buttons": [{
                    extend:    'csvHtml5',
                    text: 	   'Export Reports in CSV',
                    className: 'dt_csv'
                }],
                "scrollX": true,
                "bPaginate": true, //hide pagination
                "bSort" : true,
                "ordering": true,
                "bAutoWidth":false,
                "processing": true,
                "serverSide": true,
                "searching": true,
                "ajax": {
                    url : base_url+'get_mrss_reports',
                    type: "GET",
                    //data : $('#form_search').serializeObject()
                    data : function (data) {
                        /* console.log('data');
                         console.log(data); */
                        data.cat = $('#mrss_categories').val();
                        data.partner = $('#all_mrss_cat').val();
                        data.date_from = $('#date_from').val();
                        data.date_to = $('#date_to').val();
                        data.publish_status = $('#publish_status').val();
                        // data.stage = $('#all_stage').val();
                        // data.rating = $('#all_rating').val();
                        // data.mrss_partner_status = $('#mrss_partner_status').val();
                        // data.mrss_category_status = $('#mrss_category_status').val();
                        // data.mrss = $('#mrss').val();
                        // data.exmrss = $('#exmrss').val();
                        // data.conmrss = $('#conmrss').val();
                        // data.vqmrss = $('#vqmrss').val();
                        // data.thmrss = $('#thmrss').val();
                    }
                },

            });
        }
    },
};
