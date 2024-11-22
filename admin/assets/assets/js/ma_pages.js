var table_export;

var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);
var statuss;

$(function() {
    // datatables
    altair_datatables.dt_tableExport();
    


    statuss = $('#status_u').selectize();

    if(statuss.length > 0){
        statuss = statuss[0].selectize;
    }




    if(typeof edit_data !== 'undefined'){
        edit_data = JSON.parse(edit_data);
        statuss.addOption({text: sts, value: edit_data.status});
        statuss.setValue(edit_data.status);
    }




   
    
    $('#edit_from').on('click',function(e){
        
        e.preventDefault();
        
        $('#form_validation3').submit();
        
    });
    
    $('#form_validation2').on('submit',function(e){
        
        e.preventDefault();
        var form = $('#form_validation2')[0];

        var data = new FormData(form);
        $.ajax({
            type    : "POST",
            enctype: 'multipart/form-data',
            url     : base_url+"mobile-add_page",
            cache: false,
            contentType: false,
            processData: false,
            data    : data,
            success : function(data){
                data = JSON.parse(data);
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
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else{
                    $('#err-msg').attr('data-message','Something is going wrong!');
                    $('#err-msg').click();
                }
                
            },
            error   : function(){
                $('#err-msg').attr('data-message','Something is going wrong!');
                $('#err-msg').click();
            }
        });
        
    });

    

    $('#form_validation3').on('submit',function(e){
        
        e.preventDefault();
        var form = $('#form_validation3')[0];

        var data = new FormData(form);
        $.ajax({
            type    : "POST",
            enctype: 'multipart/form-data',
            url     : base_url+"mobile-update_page",
            cache: false,
            contentType: false,
            processData: false,
            data    : data,
            success : function(data){
                data = JSON.parse(data);
                if(data.code == 204){
                    $('#err-msg').attr('data-message',data.message);
                    $('#err-msg').click();
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else if(data.code == 201){
                    $('#err-msg').attr('data-message',data.message);
                    $('#err-msg').click();
                    $('#form_validation3').parsley().reset();
                    $.each(data.error,function(i,v){
                        $('#'+i+'_e').parent().parent().find('.error').html(v);
                    });
                    
                }else if(data.code == 200){
                    $('#suc-msg').attr('data-message',data.message);
                    $('#suc-msg').click();

                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else{
                    $('#err-msg').attr('data-message','Something is going wrong!');
                    $('#err-msg').click();
                }
                
            },
            error   : function(){
                $('#err-msg').attr('data-message','Something is going wrong!');
                $('#err-msg').click();
            }
        });
        
    });

    $(document).on('click','.delete-page',function(){

        var id = $(this).data('id');
        UIkit.modal.confirm('Are you sure to want delete this?', function(){ 
            $.ajax({
                type    : 'POST',
                url     : base_url+'mobile-delete_page',
                data    : {id:id},
                success : function(data){
                    data = JSON.parse(data);
                    
                    if(data.code == 204){
                        $('#err-msg').attr('data-message',data.message);
                        $('#err-msg').click();
                        setTimeout(function(){
                            window.location = data.url;
                        },1000);
                    }else if(data.code == 201){
                        $('#err-msg').attr('data-message',data.message);
                        $('#err-msg').click();
                        
                    }else if(data.code == 200){
                        $('#suc-msg').attr('data-message',data.message);
                        $('#suc-msg').click();
                        setTimeout(function(){
                            window.location = data.url;
                        },1000);
                        
                    }else{
                        $('#err-msg').attr('data-message','Something is going wrong!');
                        $('#err-msg').click();
                    }
                    
                    
                }
            });

         });
        

    });

    $(document).on('click','.play-video',function(){

        var id = $(this).data('id');
         
        $.ajax({
            type    : 'POST',
            url     : base_url+'get_video',
            data    : {id:id},
            success : function(data){
                data = JSON.parse(data);
                
                if(data.code == 204){
                    $('#err-msg').attr('data-message',data.message);
                    $('#err-msg').click();
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else if(data.code == 201){
                    $('#err-msg').attr('data-message',data.message);
                    $('#err-msg').click();
                    
                }else if(data.code == 200){
                    $('#suc-msg').attr('data-message',data.message);
                    $('#suc-msg').click();
                    html = ''
                    if(data.data.embed == 0){
                        html = '<video width="400" controls><source src="'+data.data.url+'">Your browser does not support HTML5 video.</video>'
                    }else{
                        html = data.data.url;
                    }
                    $('#vt').text(data.data.title);
                    $('#play').html(html);
                    var modal = UIkit.modal("#play_model");
                    modal.show();
                    
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
                "ajax": base_url+'mobile-get_pages',
                "columnDefs": [ { orderable: false, targets: [soryFlast] }]
            });


        }
    }
};