var table_export;
var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);

$(function() {
    // datatables
    altair_datatables.dt_tableExport();



    $('#add').on('click',function(){

        $('#form_validation2').parsley().reset();
		$('#form_validation2')[0].reset();
        statusAdd.setValue('');
        parentAdd.setValue('');
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



	$(document).on('click','.edit-earning',function(){

		var id = $(this).data('id');
		var title = $(this).data('title');
		$('#video-title').text(title);

		$('#form_validation3').parsley().reset();
		$('#form_validation3')[0].reset();
        var modal = UIkit.modal("#edit_model");
        modal.show();


	});

	$('#form_validation3').on('submit',function(e){

		e.preventDefault();
        UIkit.modal.confirm('Are you sure to want update this earning?', function() {
            $.ajax({
                type: "POST",
                url: base_url + "update_earning",
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

	$(document).on('click','.delete-earning',function(){

		var id = $(this).data('id');
		UIkit.modal.confirm('Are you sure to want delete this earning?', function(){
			$.ajax({
				type 	: 'POST',
				url  	: base_url+'delete_earning',
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
	$(document).on('click','.add-earning',function(){

		$('#form_validation4').parsley().reset();
		$('#form_validation4')[0].reset();
		var id = $(this).data('id');
		var title = $(this).data('title');
		$('#video-title').text(title);
		$('#video_id').val(id);
		var modal = UIkit.modal("#earning_model");
		modal.show();

	});
	$(document).on('click','#add_earning_from',function (e) {
		e.preventDefault();
		$('#form_validation4').submit();
	});
	$('#form_validation4').on('submit',function(e){

		e.preventDefault();
		UIkit.modal.confirm('Are you sure you want to add this earning?', function() {
			$.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: base_url + "add_earning",
				data: $('#form_validation4').serialize(),
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
						$('#form_validation4').parsley().reset();
						$.each(data.error, function (i, v) {
							$('#' + i).parent().parent().find('.error').html(v);
						});

					} else if (data.code == 200) {
						$('#suc-msg').attr('data-message', data.message);
						$('#suc-msg').click();
						var modal = UIkit.modal("#earning_model");
						modal.hide();
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
                "ajax": base_url+'get_payments',
                "columnDefs": [ { orderable: false, targets: [soryFlast, 0] }]
            });


        }
    }
};
