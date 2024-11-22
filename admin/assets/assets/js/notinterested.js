var table_export;
var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);
var gender;
var genderAdd;
$(function() {
    // datatables
    altair_datatables.dt_tableExport();


    gender = $('#gender_e').selectize();

    if(gender.length > 0){
        gender = gender[0].selectize;
    }

    genderAdd = $('#gender').selectize();

    if(genderAdd.length > 0){
        genderAdd = genderAdd[0].selectize;
    }
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
                        setTimeout(function(){
                            window.location = window.location;
                        },1000);
                        //$this.parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().find('.refresh-column-data').click();
                      /*  $('#not-interested-col').click();
                        var url = window.location.href.toString().split(window.location.host)[1];
                        if(url.split('/')[3] == 'deal-detail'){
                            window.location = window.location.href;
                        }*/

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
});

altair_datatables = {

    dt_tableExport: function() {
        var $dt_tableExport = $('#dt_tableExport'),
            $dt_buttons = $dt_tableExport.prev('.dt_colVis_buttons');
        if($dt_tableExport.length) {
            table_export = $dt_tableExport.DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": base_url+'notinterested_leads',
                "columnDefs": [ { orderable: false, targets: [soryFlast, 0] }]
            });


        }
    }
};
