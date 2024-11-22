var table_export;
var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);
var statuss;
var statusAdd;
var videos;
var videosAdd;
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

	videos = $('#videos_ids_e').selectize();

    if(videos.length > 0){
		videos = videos[0].selectize;
    }

	videosAdd = $('#videos').selectize();

    if(videosAdd.length > 0){
		videosAdd = videosAdd[0].selectize;
    }

    $('#add').on('click',function(){

        $('#form_validation2').parsley().reset();
		$('#form_validation2')[0].reset();
		$('.error').html('');
        statusAdd.setValue('');
		videosAdd.setValue('');
		var modal = UIkit.modal("#add_model");
    	modal.show();

    });
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
			url  	: base_url+"add_compilation",
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
                    //table_export.ajax.reload();
					setTimeout(function (){
						location.href = base_url+"compilations"
					},5000);

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

	$(document).on('click','.edit-client',function(){

		var id = $(this).data('id');

		$('#form_validation3').parsley().reset();
		$('#form_validation3')[0].reset();
		$.ajax({
			type 	: 'POST',
			url  	: base_url+'get_compilation',
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
                        if($('#'+i+'_e').hasAttr('select') && i === 'status'){
                            statuss.setValue(v);
                        }

                        if($('#'+i+'_e').hasAttr('select') && i === 'videos_ids'){
							videos.setValue(v.split(','));
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
			url  	: base_url+"update_compilation",
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
					$('#war-msg').attr('data-message',data.message);
					$('#war-msg').click();

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
	$('#add_use').on('click',function(e){

		e.preventDefault();

		$('#form_validation4').submit();

	});
	$('#form_validation4').on('submit',function(e){

		e.preventDefault();

		$.ajax({
			type 	: "POST",
			url  	: base_url+"compilation_use",
			data    : $('#form_validation4').serialize(),
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
					$('#war-msg').attr('data-message',data.message);
					$('#war-msg').click();

					var modal = UIkit.modal("#edit_model");
					modal.hide();
					$('#form_validation4').parsley().reset();
					$('#form_validation4')[0].reset();
					location.reload();
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

	$(document).on('click','.delete-client',function(){

		var id = $(this).data('id');
		UIkit.modal.confirm('Are you sure to want delete this?', function(){
			$.ajax({
				type 	: 'POST',
				url  	: base_url+'delete_compilation',
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
						$('#err-msg').attr('data-message',data.message);
						$('#err-msg').click();
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
                "ajax": base_url+'get_compilations',
                "columnDefs": [ { orderable: false, targets: [soryFlast, 0] }]
            });


        }
    }
};
