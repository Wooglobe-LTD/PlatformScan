var table_export_earning;
var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);
var earning_type_id;
var earning_type_id_a;
var social_source_id;
var social_source_id_a;
var partner_id;
var partner_id_a;
var media_id;
var currency_id;
var media_id_a;
var currency_id_a;
$(function() {
    // datatables
    altair_datatables.dt_tableExport();
	currency_id = $('#currency_id_e').selectize();
    if(currency_id.length > 0){
		currency_id = currency_id[0].selectize;
    }

	currency_id_a = $('#currency_id').selectize();
	if(currency_id_a.length > 0){
		currency_id_a = currency_id_a[0].selectize;
	}

    media_id = $('#earning_type_id_e').selectize();
    if(media_id.length > 0){
        media_id = media_id[0].selectize;
    }
    media_id.on('change', function() {
        var val = media_id.getValue();
        $('.ms').hide();
        if(val == 1){
            $('#ss').show();

        }else if(val == 2) {
            $('#p').show();

        }
        $('#form_validation4').parsley();

    });

	media_id_a = $('#earning_type_id').selectize();
	if(media_id_a.length > 0){
		media_id_a = media_id_a[0].selectize;
	}
	media_id_a.on('change', function() {
		var val = media_id_a.getValue();
		$('.ms_a').hide();
		if(val == 1){
			$('#ss_a').show();

		}else if(val == 2) {
			$('#p_a').show();

		}
		$('#form_validation15').parsley();

	});

    earning_type_id = $('#earning_type_id_e').selectize();

    if(earning_type_id.length > 0){
        earning_type_id = earning_type_id[0].selectize;
    }

    social_source_id = $('#social_source_id_e').selectize();

    if(social_source_id.length > 0){
        social_source_id = social_source_id[0].selectize;
    }

    partner_id = $('#partner_id_e').selectize();

    if(partner_id.length > 0){
        partner_id = partner_id[0].selectize;
    }


	earning_type_id_a = $('#earning_type_id').selectize();

	if(earning_type_id_a.length > 0){
		earning_type_id_a = earning_type_id_a[0].selectize;
	}

	social_source_id_a = $('#social_source_id').selectize();

	if(social_source_id_a.length > 0){
		social_source_id_a = social_source_id_a[0].selectize;
	}

	partner_id_a = $('#partner_id').selectize();

	if(partner_id_a.length > 0){
		partner_id_a = partner_id_a[0].selectize;
	}


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

		$('#form_validation16').submit();

    });

    $('#form_validation2').on('submit',function(e){

		e.preventDefault();

		$.ajax({
			type 	: "POST",
			url  	: base_url+"add_category",
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

	$(document).on('click','.edit-earning',function(){

		var id = $(this).data('id');
		var title = $(this).data('title');
		$('#video-title-id').text(title);

		$('#form_validation16').parsley().reset();
		$('#form_validation16')[0].reset();
		$.ajax({
			type 	: 'POST',
			url  	: base_url+'get_earning',
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
					$('#form_validation16').parsley().reset();

				}else if(data.code == 200){
					var modal = UIkit.modal("#edit_model");
					modal.show();
					$.each(data.data,function(i,v){
						$('#'+i+'_e').val(v);
                        if($('#'+i+'_e').hasAttr('select1')){
                            earning_type_id.setValue(v);
                            $('.ms').hide();
                            if(v == 1){
                                $('#ss').show();

                            }else if(v == 2) {
                                $('#p').show();

                            }
                        }

                        if($('#'+i+'_e').hasAttr('select2')){
                            social_source_id.setValue(v);
                        }
                        if($('#'+i+'_e').hasAttr('select3')){
                            partner_id.setValue(v);
                        }

						$('#'+i+'_e').focus();
						$('.uk-datepicker').removeClass('uk-dropdown-shown');
						$('.uk-datepicker').removeClass('uk-dropdown-active');
					});

				}else{
					$('#err-msg').attr('data-message','Something is going wrong!');
					$('#err-msg').click();
				}


			}
		});

	});

	$('#form_validation16').on('submit',function(e){

		e.preventDefault();
        UIkit.modal.confirm('Are you sure to want update this earning?', function() {
            $.ajax({
                type: "POST",
                url: base_url + "update_earning",
                data: $('#form_validation16').serialize(),
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
                        $('#form_validation16').parsley().reset();
                        $.each(data.error, function (i, v) {
                            $('#' + i + '_e').parent().parent().find('.error').html(v);
                        });

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();

                        var modal = UIkit.modal("#edit_model");
                        modal.hide();
                        $('#form_validation16').parsley().reset();
                        $('#form_validation16')[0].reset();
						table_export_earning.ajax.reload();
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
						table_export_earning.ajax.reload();

					}else{
						$('#err-msg').attr('data-message','Something is going wrong!');
						$('#err-msg').click();
					}


				}
			});

		 });


	});
	$(document).on('click','.add-earning',function(){

		$('#form_validation15').parsley().reset();
		$('#form_validation15')[0].reset();
		var id = $(this).data('id');
		var title = $(this).data('title');
		$('#video-title').text(title);
		$('#ern_video_id').val(id);
		var modal = UIkit.modal("#earning_model");
		modal.show();
		$('#form_validation15').parsley();


	});
	$(document).on('click','#add_earning_from',function (e) {
		e.preventDefault();
		$('#form_validation15').submit();
	});
	$('#form_validation15').on('submit',function(e){

		e.preventDefault();
		UIkit.modal.confirm('Are you sure you want to add this earning?', function() {
			$.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: base_url + "add_earning",
				data: $('#form_validation15').serialize(),
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
						$('#form_validation15').parsley().reset();
						$.each(data.error, function (i, v) {
							$('#' + i).parent().parent().find('.error').html(v);
						});

					} else if (data.code == 200) {
						$('#suc-msg').attr('data-message', data.message);
						$('#suc-msg').click();
						var modal = UIkit.modal("#earning_model");
						modal.hide();
						$('#form_validation15').parsley().reset();
						$('#form_validation15')[0].reset();
						table_export_earning.ajax.reload();
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
        var $dt_tableExport = $('#dt_tableExport');
            //$dt_buttons = $dt_tableExport.prev('.dt_colVis_buttons');

        if($dt_tableExport.length) {
			table_export_earning = $dt_tableExport.DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": base_url+'get_earnings?video_id='+video_id,
                "columnDefs": [ { orderable: false, targets: [soryFlast, 0] }]
            });


        }
    }
};
