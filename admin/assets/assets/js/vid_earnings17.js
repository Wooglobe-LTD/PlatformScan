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

    $('#edit_from_advance').on('click',function(e){

        e.preventDefault();

        $('#form_validation111').submit();

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

                        if($('#'+i+'_e').hasAttr('select4')){
                            currency_id.setValue(v);
                        }

                        $('#'+i+'_e').focus();
                        $('.uk-datepicker').removeClass('uk-dropdown-shown');
                        $('.uk-datepicker').removeClass('uk-dropdown-active');
                    });
                    var earning_amount = $('#earning_amount_e').val();
                    var expense = $('#expense_e').val();
                    if(earning_amount.length > 0 && earning_amount > 0){
                        if(expense.length > 0 && expense > 0){
                            var expense_amount = percentage(earning_amount,expense);
                            var after_expense = (earning_amount-expense_amount);
                            var wooglobe_amount = percentage(after_expense,revenue_share);
                            $('#expense_earning_e').text(expense_amount);
                            $('#wooglobe_net_earning_text_e').text(after_expense - wooglobe_amount);
                            $('#client_net_earning_text_e').text(wooglobe_amount);
                            $('#wooglobe_total_earning_e').text((after_expense - wooglobe_amount)+(expense_amount));
                            $('#expense_amount_e').val(expense_amount);
                            $('#wooglobe_net_earning_e').val(after_expense - wooglobe_amount);
                            $('#wooglobe_total_share_e').val((after_expense - wooglobe_amount)+(expense_amount));
                            $('#revenue_share_amount_e').val(wooglobe_amount);
                            $('#client_net_earning_e').val(wooglobe_amount);

                        }else{
                            var revenue_amount = percentage(earning_amount,revenue_share);
                            $('#expense_earning_e').text(0);
                            $('#wooglobe_net_earning_text_e').text(parseInt(parseInt(earning_amount) - parseInt(revenue_amount)));
                            $('#client_net_earning_text_e').text(revenue_amount);
                            $('#wooglobe_total_earning_e').text(parseInt(parseInt(earning_amount) - parseInt(revenue_amount)));
                            $('#expense_amount_e').val(0);
                            $('#wooglobe_net_earning_e').val(parseInt(parseInt(earning_amount) - parseInt(revenue_amount)));
                            $('#wooglobe_total_share_e').val(parseInt(parseInt(earning_amount) - parseInt(revenue_amount)));
                            $('#revenue_share_amount_e').val(revenue_amount);
                            $('#client_net_earning_e').val(revenue_amount);

                        }

                    }else{
                        $('#expense_earning_e').text(0);
                        $('#wooglobe_net_earning_text_e').text(0);
                        $('#client_net_earning_text_e').text(0);
                        $('#wooglobe_total_earning_e').text(0);

                        $('#expense_amount_e').val(0);
                        $('#wooglobe_net_earning_e').val(0);
                        $('#wooglobe_total_share_e').val(0);
                        $('#revenue_share_amount_e').val(0);
                        $('#client_net_earning_e').val(0);
                    }
                }else{
                    $('#err-msg').attr('data-message','Something is going wrong!');
                    $('#err-msg').click();
                }


            }
        });

    });
    $(document).on('click','.edit-earning-advance',function(){

        var id = $(this).data('id');
        var title = $(this).data('title');
        $('#video-title-id-advance').text(title);

        $('#form_validation111').parsley().reset();
        $('#form_validation111')[0].reset();
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
                    $('#form_validation111').parsley().reset();

                }else if(data.code == 200){
                    var modal = UIkit.modal("#edit_model_advance");
                    modal.show();
                    $.each(data.data,function(i,v){
                        $('#'+i+'_e_advance').val(v);
                        if($('#'+i+'_e_advance').hasAttr('select1')){
                            earning_type_id.setValue(v);
                            $('.ms').hide();
                            if(v == 1){
                                $('#ss').show();

                            }else if(v == 2) {
                                $('#p').show();

                            }
                        }

                        if($('#'+i+'_e_advance').hasAttr('select2')){
                            social_source_id.setValue(v);
                        }
                        if($('#'+i+'_e_advance').hasAttr('select3')){
                            partner_id.setValue(v);
                        }

                        if($('#'+i+'_e_advance').hasAttr('select4')){
                            currency_id.setValue(v);
                        }

                        $('#'+i+'_e_advance').focus();
                        $('.uk-datepicker').removeClass('uk-dropdown-shown');
                        $('.uk-datepicker').removeClass('uk-dropdown-active');
                    });
                    var earning_amount = $('#earning_amount_e_advance').val();


                    $('#client_net_earning_e_advance').val(earning_amount);

                }else{
                    $('#err-msg').attr('data-message','Something is going wrong!');
                    $('#err-msg').click();
                }


            }
        });

    });

	$(document).on('click','.transaction-detail',function(){

		var id = $(this).data('id');
		var detail = $(this).data('detail');
		var date = $(this).data('date');
		$('#transaction_detail_id').text(id);
		$('#transaction_detail_detail').text(detail);
		$('#transaction_detail_date').text(date);
        var modal = UIkit.modal("#transaction_model");
        modal.show();


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
                error: function (msg) {
                    console.log(msg);
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });
        });

	});
    $('#form_validation111').on('submit',function(e){

        e.preventDefault();
        UIkit.modal.confirm('Are you sure to want update this advance?', function() {
            $.ajax({
                type: "POST",
                url: base_url + "update_earning",
                data: $('#form_validation111').serialize(),
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
                        $('#form_validation111').parsley().reset();
                        $.each(data.error, function (i, v) {
                            $('#' + i + '_e_advance').parent().parent().find('.error').html(v);
                        });

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();

                        var modal = UIkit.modal("#edit_model_advance");
                        modal.hide();
                        $('#form_validation111').parsley().reset();
                        $('#form_validation111')[0].reset();
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

        set_conversion_rate(false);

		$('#form_validation15').parsley();


	});
    $(document).on('click','.add-earning-advance',function(){

        $('#form_validation000').parsley().reset();
        $('#form_validation000')[0].reset();
        var id = $(this).data('id');
        var title = $(this).data('title');
        $('#video-title-advance').text(title);
        $('#ern_video_id_advance').val(id);
        var modal = UIkit.modal("#earning_model_advance");
        modal.show();
        $('#form_validation16').parsley();


    });
	$(document).on('click','#add_earning_from',function (e) {
		e.preventDefault();
		$('#form_validation15').submit();
	});
    $(document).on('click','#add_earning_from_advance',function (e) {
        console.log(1);
        e.preventDefault();
        $('#form_validation000').submit();
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

    $('#form_validation000').on('submit',function(e){
        console.log(2);
        e.preventDefault();
        UIkit.modal.confirm('Are you sure you want to add this Advance?', function() {
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: base_url + "add_earning",
                data: $('#form_validation000').serialize(),
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
                        $('#form_validation000').parsley().reset();
                        $.each(data.error, function (i, v) {
                            $('#' + i+'_advance').parent().parent().find('.error').html(v);
                        });

                    } else if (data.code == 200) {
                        $('#suc-msg').attr('data-message', data.message);
                        $('#suc-msg').click();
                        var modal = UIkit.modal("#earning_model_advance");
                        modal.hide();
                        $('#form_validation000').parsley().reset();
                        $('#form_validation000')[0].reset();
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

    $("#currency_id, #partner_currency").on('change', function(){
        set_conversion_rate(false);
    });

    $("#currency_id_e, #partner_currency_e").on('change', function(){
        set_conversion_rate(true);
    });

    // $(document).on('change', '#actual_amount, #actual_amount_e', function(){
	// 	var actual_amount = $('#actual_amount').val();
    //     var conversion_rate = $("#conversion_rate").val();

	// 	$('#earning_amount').val(actual_amount * conversion_rate);
        

    // });
	$(document).on('keyup','#actual_amount, #earning_amount, #expense',function () {
        update_values();
    })
    
    function update_values(){
        var actual_amount = $('#actual_amount').val();
        var conversion_rate = $("#conversion_rate").val();
        var e_amount = (actual_amount * conversion_rate).toFixed(2);
		$("#earning_amount").attr("value", e_amount);
        $("#earning_amount").val(e_amount);


		var earning_amount = $('#earning_amount').val();
		var expense = $('#expense').val();
		if(earning_amount.length > 0 && earning_amount > 0){
			if(expense.length > 0 && expense > 0){
                var expense_amount = parseFloat(percentage(earning_amount,expense).toFixed(2));
                var after_expense = parseFloat((earning_amount-expense_amount).toFixed(2));
                var wooglobe_amount = parseFloat(percentage(after_expense,revenue_share).toFixed(2));

                $('#expense_earning').text(expense_amount);
                
                $('#wooglobe_net_earning_text').text(parseFloat((after_expense - wooglobe_amount).toFixed(2)));
                $('#client_net_earning_text').text(parseFloat(wooglobe_amount.toFixed(2)));

                $('#wooglobe_total_earning').text(((after_expense - wooglobe_amount)+(expense_amount)).toFixed(2));
                $('#expense_amount').val(expense_amount);
                $('#wooglobe_net_earning').val(parseFloat((after_expense - wooglobe_amount).toFixed(2)));
                $('#wooglobe_total_share').val(parseFloat(((after_expense - wooglobe_amount)+(expense_amount)).toFixed(2)));
                $('#revenue_share_amount').val(wooglobe_amount);
                $('#client_net_earning').val(wooglobe_amount);

			}else{
				var revenue_amount = percentage(earning_amount,revenue_share);
				console.log(parseInt(earning_amount));
				console.log(parseInt(revenue_amount));
				console.log(parseInt(parseInt(earning_amount) - parseInt(revenue_amount)));
                $('#expense_earning').text(0);
                $('#wooglobe_net_earning_text').text(parseInt(parseInt(earning_amount) - parseInt(revenue_amount)));
                $('#client_net_earning_text').text(revenue_amount);
                $('#wooglobe_total_earning').text((parseInt(parseInt(earning_amount) - parseInt(revenue_amount))).toFixed(2));
                $('#expense_amount').val(0);
                $('#wooglobe_net_earning').val(parseInt(parseInt(earning_amount) - parseInt(revenue_amount)));
                $('#wooglobe_total_share').val(parseInt(parseInt(earning_amount) - parseInt(revenue_amount)));
                $('#revenue_share_amount').val(revenue_amount);
                $('#client_net_earning').val(revenue_amount);
			}

		}else{
			$('#expense_earning').text(0);
			$('#wooglobe_net_earning_text').text(0);
			$('#client_net_earning_text').text(0);
			$('#wooglobe_total_earning').text(0);

            $('#expense_amount').val(0);
            $('#wooglobe_net_earning').val(0);
            $('#wooglobe_total_share').val(0);
            $('#revenue_share_amount').val(0);
            $('#client_net_earning').val(0);
		}
    }
    function update_values_e(){
        var actual_amount = $('#actual_amount_e').val();
        var conversion_rate = $("#conversion_rate_e").val();

		$("#earning_amount_e").attr("value", actual_amount * conversion_rate);
        $("#earning_amount_e").val(actual_amount * conversion_rate);


        var earning_amount = $('#earning_amount_e').val();
        var expense = $('#expense_e').val();
        if(earning_amount.length > 0 && earning_amount > 0){
            if(expense.length > 0 && expense > 0){
                var expense_amount = parseFloat(percentage(earning_amount,expense).toFixed(2));
                var after_expense = parseFloat((earning_amount-expense_amount).toFixed(2));
                var wooglobe_amount = parseFloat(percentage(after_expense,revenue_share).toFixed(2));

                $('#expense_earning_e').text(expense_amount);
                $('#wooglobe_net_earning_text_e').text(parseFloat((after_expense - wooglobe_amount).toFixed(2)));
                $('#client_net_earning_text_e').text(parseFloat(wooglobe_amount.toFixed(2)));
                $('#wooglobe_total_earning_e').text(parseFloat((after_expense - wooglobe_amount)+(expense_amount)).toFixed(2));
                $('#expense_amount_e').val(expense_amount);
                $('#wooglobe_net_earning_e').val(parseFloat((after_expense - wooglobe_amount).toFixed(2)));
                $('#wooglobe_total_share_e').val(parseFloat((after_expense - wooglobe_amount)+(expense_amount).toFixed(2)));
                $('#revenue_share_amount_e').val(wooglobe_amount);
                $('#client_net_earning_e').val(wooglobe_amount);

            }else{
                var revenue_amount = percentage(earning_amount,revenue_share);
                $('#expense_earning_e').text(0);
                $('#wooglobe_net_earning_text_e').text(parseInt(parseInt(earning_amount) - parseInt(revenue_amount)));
                $('#client_net_earning_text_e').text(revenue_amount);
                $('#wooglobe_total_earning_e').text(parseInt(parseInt(earning_amount) - parseInt(revenue_amount)));
                $('#expense_amount_e').val(0);
                $('#wooglobe_net_earning_e').val(parseInt(parseInt(earning_amount) - parseInt(revenue_amount)));
                $('#wooglobe_total_share_e').val(parseInt(parseInt(earning_amount) - parseInt(revenue_amount)));
                $('#revenue_share_amount_e').val(revenue_amount);
                $('#client_net_earning_e').val(revenue_amount);

            }

        }else{
            $('#expense_earning_e').text(0);
            $('#wooglobe_net_earning_text_e').text(0);
            $('#client_net_earning_text_e').text(0);
            $('#wooglobe_total_earning_e').text(0);

            $('#expense_amount_e').val(0);
            $('#wooglobe_net_earning_e').val(0);
            $('#wooglobe_total_share_e').val(0);
            $('#revenue_share_amount_e').val(0);
            $('#client_net_earning_e').val(0);
        }
    }
    $(document).on('keyup','#earning_amount_advance',function () {
        var earning_amount = $('#earning_amount_advance').val();

        $('#client_net_earning_advance').val(earning_amount);

    })
    $(document).on('keyup','#actual_amount_e, #earning_amount_e, #expense_e',function () {
        update_values_e();
    })
    $("#earning_amount").focus();
    function set_conversion_rate(editing){
        var from;
        var to;
        if(!editing){
            from = $("#partner_currency").val();
            to = $("#currency_id").val();
        } else{
            from = $("#partner_currency_e").val();
            to = $("#currency_id_e").val();
    
        }
        $.ajax({
            type: "POST",
            url: base_url + "get_conversion_rate",
            data: {"from": from, "to": to},
            success: function (data) {
                data = JSON.parse(data);
                console.log(data);
                if (data.code == 200) {
                    if(!editing){
                        $("#conversion_rate").focus();
                        $("#conversion_rate").attr("value", data.message);
                        $("#conversion_rate").val(data.message);
                        update_values();
                    }
                    else{
                        $("#conversion_rate_e").attr("value", data.message);
                        $("#conversion_rate_e").val(data.message);
                        update_values_e();
                    }
    
                }
                else if(data.code == 404){
                    $('#err-msg').attr('data-message', data.message);
                    $('#err-msg').click();
                }
            },
            error: function(msg){
                console.log(msg);
            }
        });
    
    }
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
            });


        }
    }
};
function percentage(num, per)
{
    return (num/100)*per;
}

