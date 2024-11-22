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
    $(document).on('click','.unblock-user',function(){

        var id = $(this).data('id');
        $.ajax({
            type 	: 'POST',
            url  	: base_url+'unblock_user',
            data 	: {id:id},
            success : function(data){
                data = JSON.parse(data);
             	 if(data.code == 200){

                    $('#suc-msg').attr('data-message',data.message);
                    $('#suc-msg').click();

                }else{
                    $('#err-msg').attr('data-message','Something is going wrong!');
                    $('#err-msg').click();
                }


            }
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
                "ajax": base_url+'get_block_users',
                "columnDefs": [ { orderable: false, targets: [soryFlast, 0] }]
            });


        }
    }
};
