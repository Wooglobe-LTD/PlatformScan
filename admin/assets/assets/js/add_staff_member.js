var table_export;
var statuss;
var statusAdd;
var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);
$(function() {
    // datatables
    altair_datatables.dt_tableExport();
    statuss = $('#status_e').selectize();

    if(statuss.length > 0){
        statuss = statuss[0].selectize;
    }

    statusAdd = $('#status').selectize();

    if(statusAdd.length > 0){
        statusAdd = statusAdd[0].selectize;
    }
    $(".single").eq(0).css('z-index',9999);
    $(".single").eq(1).css('z-index',9999);
    $(".single").eq(2).css('z-index',9999);
    $(".single").eq(3).css('z-index',9999);
    $(".single").eq(4).css('z-index',9999);
    $(".single").eq(5).css('z-index',9999);

    $(".multi").eq(0).css('z-index',9999);
    $(".multi").eq(1).css('z-index',9999);
    $(".multi").eq(2).css('z-index',9999);
    $(".multi").eq(3).css('z-index',9999);
    $(".multi").eq(4).css('z-index',9999);
    $(".multi").eq(5).css('z-index',9999);

    $('#add').on('click',function(){

        $('#form_validation2').parsley().reset();
		$('#form_validation2')[0].reset();
        statusAdd.setValue('');
        $('.error').html('');
		var modal = UIkit.modal("#add_model");
    	modal.show();

    });

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
			url  	: base_url+"add_add_staff_group",
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

	$(document).on('click','.edit-add_staff_group',function(){

		var id = $(this).data('id');
        $('.error').html('');
		$('#form_validation3').parsley().reset();
		$('#form_validation3')[0].reset();
		$.ajax({
			type 	: 'POST',
			url  	: base_url+'get_add_staff_group',
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
						if($('#'+i+'_e').hasAttr('select')){
                            statuss.setValue(v);
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

		$.ajax({
			type 	: "POST",
			url  	: base_url+"update_add_staff_group",
			data    : $('#form_validation3').serialize(),
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

					var modal = UIkit.modal("#edit_model");
					modal.hide();
					$('#form_validation3').parsley().reset();
                    $('#form_validation3')[0].reset();
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

	$(document).on('click','.delete-add_staff_group',function(){

        var id = $(this).data('id');
        UIkit.modal.confirm('Are you sure to want delete this?', function(){
            $.ajax({
                type 	: 'POST',
                url  	: base_url+'delete_add_staff_group',
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

    $(document).on('click','.role-permissions',function(){

        var id = $(this).data('id');

        window.location = base_url+'role_permissions/'+id;


    });

    var checkecdBox = $('.list-checkbox');
    $.each(checkecdBox,function(){
    	var is_checked = $(this).prop('checked');
    	if(is_checked === true){
			$(this).parent().parent().parent().parent().find('.child-checkbox').iCheck('enable');

		}else {
            $(this).parent().parent().parent().parent().find('.child-checkbox').iCheck('disable');
		}
	});

    $(document).on('ifChanged','.list-checkbox',function(){
    	//console.log($(this).parent().parent().parent().parent().find('.child-checkbox'));
    	if($(this).is(':checked') === true){
            $(this).parent().parent().parent().parent().find('.child-checkbox').iCheck('enable');
            $(this).parent().parent().parent().parent().find('.child-checkbox').iCheck('uncheck');
		}else{
            $(this).parent().parent().parent().parent().find('.child-checkbox').iCheck('disable');
            $(this).parent().parent().parent().parent().find('.child-checkbox').iCheck('uncheck');
		}
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
                "ajax": base_url+'get_add_staff_group',
                "columnDefs": [ { orderable: false, targets: [soryFlast] }]
            });


        }
    }
};