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

    var detail = $('#appearance_detail_map').val();
    if(detail.length == 0){
        alert('Please fill the identity field');
        return false;
    }
    $.ajax({
        type: "POST",
        url: base_url + "manual-ar",
        data: $('#form_validation26').serialize(),
        dataType:'json',
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
});