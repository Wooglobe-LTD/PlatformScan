var table_export;
var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);
var videoAdd;
var video;

$(function() {
    // datatables
    altair_datatables.dt_tableExport();


    videoAdd = $('#video_id').selectize();

    if(videoAdd.length > 0){
        videoAdd = videoAdd[0].selectize;
    }

    video = $('#video_id_e').selectize();

    if(video.length > 0){
        video = video[0].selectize;
    }

    $('#add').on('click',function(){

        $('#form_validation2').parsley().reset();
		$('#form_validation2')[0].reset();
        videoAdd.setValue('');
		var modal = UIkit.modal("#add_model");
    	modal.show();

    });

    $(".single").eq(1).css('z-index',9999);
    $(".single").eq(2).css('z-index',9999);
    $(".single").eq(3).css('z-index',9999);
    $(".single").eq(4).css('z-index',9999);
    $(".single").eq(5).css('z-index',9999);
    $(".single").eq(6).css('z-index',9999);
    $(".single").eq(7).css('z-index',9999);
    $('#add_from').on('click',function(e){
		
		e.preventDefault();
		
		$('#form_validation2').submit();
		
	});
	
	$('#edit_from').on('click',function(e){
		
		e.preventDefault();
		
		$('#form_validation3').submit();
		
    });
    
    $('#form_validation2').on('submit',function(e){
		
		e.preventDefault();
		
		$.ajax({
			type 	: "POST",
			url  	: base_url+"add_video_expense",
			data    : $('#form_validation2').serialize(),
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
					
					var modal = UIkit.modal("#add_model");
					modal.hide();
					$('#form_validation2').parsley().reset();
                    $('#form_validation2')[0].reset();
                    table_export.ajax.reload();
				}else{
					$('#err-msg').attr('data-message','Something is going wrong!');
					$('#err-msg').click();
				}
				
			},
			error 	: function(){
				$('#err-msg').attr('data-message','Something is going wrong!');
				$('#err-msg').click();
			}
		});
		
	});

	$(document).on('click','.edit-expense',function(){

		var id = $(this).data('id');
		var title = $(this).data('title');
		$('#video-title').text(title);

		$('#form_validation3').parsley().reset();
		$('#form_validation3')[0].reset();
		$.ajax({
			type 	: 'POST',
			url  	: base_url+'get_video_expense',
			data 	: {id:id},
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
					
				}else if(data.code == 200){
					var modal = UIkit.modal("#edit_model");
					modal.show();
					$.each(data.data,function(i,v){
						$('#'+i+'_e').val(v);
                        if($('#'+i+'_e').hasAttr('select1')){
                            video.setValue(v);

                        }

                        if($('#'+i+'_e').hasAttr('select2')){
                            social_source_id.setValue(v);
                        }
                        if($('#'+i+'_e').hasAttr('select3')){
                            partner_id.setValue(v);
                        }
						
						$('#'+i+'_e').focus();
					});
					
				}else{
					$('#err-msg').attr('data-message','Something is going wrong!');
					$('#err-msg').click();
				}
				
				
			}
		});

	});

	$('#form_validation3').on('submit',function(e){
		
		e.preventDefault();
        UIkit.modal.confirm('Are you sure to want update this expense?', function() {
            $.ajax({
                type: "POST",
                url: base_url + "update_video_expense",
                data: $('#form_validation3').serialize(),
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
                        $('#form_validation3').parsley().reset();
                        $.each(data.error, function (i, v) {
                            $('#' + i + '_e').parent().parent().find('.error').html(v);
                        });

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();

                        var modal = UIkit.modal("#edit_model");
                        modal.hide();
                        $('#form_validation3').parsley().reset();
                        $('#form_validation3')[0].reset();
                        table_export.ajax.reload();
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

	$(document).on('click','.delete-expense',function(){

		var id = $(this).data('id');
		UIkit.modal.confirm('Are you sure to want delete this expense?', function(){
			$.ajax({
				type 	: 'POST',
				url  	: base_url+'delete_video_expense',
				data 	: {id:id},
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
						table_export.ajax.reload();
						
					}else{
						$('#err-msg').attr('data-message','Something is going wrong!');
						$('#err-msg').click();
					}
					
					
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
                "ajax": base_url+'get_video_expenses?video_id='+video_id,
                "columnDefs": [ { orderable: false, targets: [soryFlast] }]
            });


        }
    }
};