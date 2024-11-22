$(function() {
    // file upload
    altair_form_file_upload.init();
});


altair_form_file_upload = {
    init: function() {

        var progressbar = $("#file_upload-progressbar"),
            bar         = progressbar.find('.uk-progress-bar'),
            settings    = {
                action:  base_url+'upload_video',
                allow : '*.(avi|mp4|wmv|flv|mkv|AVI|MP4|FLV|MKV)', // File filter
                single: true,
                param : 'files',
                loadstart: function() {
                    bar.css("width", "0%").text("0%");
                    progressbar.removeClass("uk-hidden");
                },
                progress: function(percent) {
                    percent = Math.ceil(percent);
                    bar.css("width", percent+"%").text(percent+"%");
                },
                allcomplete: function(response,xhr) {
                    data = JSON.parse(response);
                    if(data.code == 204){
                        $('#err-msg').attr('data-message',data.message);
                        $('#err-msg').click();
                        setTimeout(function(){
                            window.location = data.url;
                        },1000);
                    }else if(data.code == 201){
                        $('#err-msg').attr('data-message',data.message);
                        $('#err-msg').click();
                        $('#form_validation2').parsley().reset();
                        $.each(data.error,function(i,v){
                            $('#'+i).parent().parent().find('.error').html(v);
                        });
                        
                    }else if(data.code == 200){
                        $('#suc-msg').attr('data-message',data.message);
                        $('#suc-msg').click();
                        $('#url').val(data.url);
                        $('#url').focus();
                        
                    }else{
                        $('#err-msg').attr('data-message','Something is going wrong!');
                        $('#err-msg').click();
                    }
                    bar.css("width", "100%").text("100%");
                    setTimeout(function(){
                        progressbar.addClass("uk-hidden");
                    }, 250);
                    setTimeout(function() {
                        UIkit.notify({
                            message: "Upload Completed",
                            pos: 'top-right'
                        });
                    },280);
                }
            };

        var select = UIkit.uploadSelect($("#file_upload-select"), settings),
            drop   = UIkit.uploadDrop($("#file_upload-drop"), settings);
    }
};