/**
 * Created by Abdul Rehman Aziz on 4/30/2018.
 */
$(function() {


    $($document).on('click','#send_reminder_email',function () {

        //var email = $('#send_reminder_email').attr('data-email');
        var id = $('#send_reminder_email').attr('data-id');

        $.ajax({
            type: "POST",
            url: base_url + "send-reminder-email",
            data:{id:id},
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

});