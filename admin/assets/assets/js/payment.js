var table_export;
var table_export_2;
var soryFlast = parseInt(parseInt($('thead').find('th').length) - 1);

$(function () {
	// datatables
	altair_datatables.dt_tableExport();
	altair_datatables.dt_tableExport_2();



	$('#add').on('click', function () {

		$('#form_validation2').parsley().reset();
		$('#form_validation2')[0].reset();
		statusAdd.setValue('');
		parentAdd.setValue('');
		var modal = UIkit.modal("#add_model");
		modal.show();

	});

	$(".single").eq(1).css('z-index', 9999);
	$(".single").eq(2).css('z-index', 9999);
	$(".single").eq(3).css('z-index', 9999);
	$(".single").eq(4).css('z-index', 9999);
	$(".single").eq(5).css('z-index', 9999);
	$(".single").eq(6).css('z-index', 9999);
	$(".single").eq(7).css('z-index', 9999);
	$('#add_from').on('click', function (e) {

		e.preventDefault();

		$('#form_validation2').submit();

	});

	$('#edit_from').on('click', function (e) {

		e.preventDefault();

		$('#make_payment_form').submit();

	});

	$('#edit_email_from').on('click', function (e) {

		e.preventDefault();

		$('#form_validation4').submit();

	});


	$(document).on('click', '.edit-earning', function () {

		var id = $(this).data('id');
		var title = $(this).data('title');
		var amount = $(this).data('amount').toFixed(2);
		var tax = $(this).data('tax');
		var country = $(this).data('country');
		var address = $(this).data('address');
		var auth = $(this).data('auth');
		var tax_amount = (amount * (tax / 100)).toFixed(2);
		var final_amount = (amount - tax_amount).toFixed(2);
		var modal = UIkit.modal("#edit_model");
		modal.show();
		$('#video-title-id').text(title);
		$('#id').val(id);
		$('#authenticate').val(auth);
		$('#tax-rate').attr('value', tax).focus();
		$('#total-amount').text(amount);
		$('#tax-amount').text(tax_amount);
		$('#final-amount').text(final_amount);
		$('#country').text(country);
		$('#address').text(address);
		$('#make_payment_form').parsley().reset();
		$('#make_payment_form')[0].reset();

	});

	$(document).on('click', '.edit-email', function () {

		var id = $(this).data('id');
		var title = $(this).data('title');
		$('#video-title-id-2').text(title);
		$('#id2').val(id);

		$('#form_validation4').parsley().reset();
		$('#form_validation4')[0].reset();
		var modal = UIkit.modal("#email_model");
		modal.show();


	});

	$(document).on('input', '#tax-rate', function () {

		var tax = $(this).val();
		var amount = $('#total-amount').text();
		var tax_amount = (amount * (tax / 100)).toFixed(2);
		var final_amount = (amount - tax_amount).toFixed(2);
		$('#tax-rate').attr('value', tax).focus();
		$('#tax-amount').text(tax_amount);
		$('#final-amount').text(final_amount);

	});

	$(document).on('change', '#above-100-fltr', function () {
		table_export.ajax.reload();
	});

	$(document).on('click', '.invoice-detail', function () {

		var id = $(this).data('id');
		var title = $(this).data('invoice-id');
		$('#video-title-id').text(title + ' Details');
		//$('#id').val(id);
		$.ajax({
			type: "POST",
			url: base_url + "get_invoice_detail",
			data: { id: id },
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
					$('#make_payment_form').parsley().reset();
					$.each(data.error, function (i, v) {
						$('#' + i + '_e').parent().parent().find('.error').html(v);
					});

				} else if (data.code == 200) {
					$('#suc-msg').attr('data-message', data.message);
					$('#suc-msg').click();
					$('#inv_details').html(data.html);
					var modal = UIkit.modal("#edit_model");
					modal.show();

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
		/*$('#make_payment_form').parsley().reset();
		$('#make_payment_form')[0].reset();*/
		var modal = UIkit.modal("#edit_model");
		modal.show();

	});

	$('#make_payment_form').on('submit', function (e) {

		e.preventDefault();
		UIkit.modal.confirm('Are you sure to want to make this payment?', function () {
			$.ajax({
				type: "POST",
				url: base_url + "make_payment",
				data: $('#make_payment_form').serialize(),
				success: function (data) {
					data = JSON.parse(data);
					console.log(data);
					if (data.url) {
						window.open(data.url, '_blank');
					}
					if (data.code == 204) {
						$('#err-msg').attr('data-message', data.message);
						$('#err-msg').click();
						setTimeout(function () {
							window.location = data.url;
						}, 1000);
					} else if (data.code == 201) {
						$('#err-msg').attr('data-message', data.message);
						$('#err-msg').click();
						$('#make_payment_form').parsley().reset();
						$.each(data.error, function (i, v) {
							$('#' + i + '_e').parent().parent().find('.error').html(v);
						});

					} else if (data.code == 200) {
						$('#suc-msg').attr('data-message', data.message);
						$('#suc-msg').click();

						var modal = UIkit.modal("#edit_model");
						modal.hide();
						$('#make_payment_form').parsley().reset();
						$('#make_payment_form')[0].reset();
						table_export.ajax.reload();
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

	$(document).on('click', '.delete-earning', function () {

		var id = $(this).data('id');
		UIkit.modal.confirm('Are you sure to want delete this earning?', function () {
			$.ajax({
				type: 'POST',
				url: base_url + 'delete_earning',
				data: { id: id },
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

					} else if (data.code == 200) {
						$('#suc-msg').attr('data-message', data.message);
						$('#suc-msg').click();
						table_export.ajax.reload();

					} else {
						$('#err-msg').attr('data-message', 'Something is going wrong!');
						$('#err-msg').click();
					}
				}
			});
		});

	});

	$(document).on('click', '#send-paypal-email', function () {
		var id = $('input[id=id]').val();
		$.ajax({
			type: 'POST',
			url: base_url + 'request_paypal_email',
			data: { user_id: id },
			success: function (data) {
				data = JSON.parse(data);
				if (data.code == 200) {
					$('#suc-msg').attr('data-message', data.message);
					$('#suc-msg').click();

				} else {
					$('#err-msg').attr('data-message', 'Something is going wrong!');
					$('#err-msg').click();
				}
			}
		});
	});
	$(document).on('click', '.add-earning', function () {

		$('#form_validation4').parsley().reset();
		$('#form_validation4')[0].reset();
		var id = $(this).data('id');
		var title = $(this).data('title');
		$('#video-title').text(title);
		$('#video_id').val(id);

		var modal = UIkit.modal("#earning_model");
		modal.show();

	});
	$(document).on('click', '#add_earning_from', function (e) {
		e.preventDefault();
		$('#form_validation4').submit();
	});
	$('#form_validation4').on('submit', function (e) {

		e.preventDefault();
		$.ajax({
			type: "POST",
			enctype: 'multipart/form-data',
			url: base_url + "update_user_paypal_email",
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
					var modal = UIkit.modal("#email_model");
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

altair_datatables = {

	dt_tableExport: function () {
		var $dt_tableExport = $('#dt_tableExport'),
			$dt_buttons = $dt_tableExport.prev('.dt_colVis_buttons');

		if ($dt_tableExport.length) {
			table_export = $dt_tableExport.DataTable({
				lengthMenu: [
					[10, 25, 50, 100, 500, -1],
					[10, 25, 50, 100, 500, 'All']
				],
				"processing": true,
				"serverSide": true,
				"ajax": {
                    url: base_url + 'get_payments',
                    type: "GET",
                    data: function (data) {
                        data.payables_only = $('#above-100-fltr').prop('checked');
                    }
                },
				"columnDefs": [{ orderable: false, targets: [0] }]
			});
		}
	},
	dt_tableExport_2: function () {
		var $dt_tableExport_2 = $('#table_history'),
			$dt_buttons_2 = $dt_tableExport_2.prev('.dt_colVis_buttons_2');
		if ($dt_tableExport_2.length) {
			table_export_2 = $dt_tableExport_2.DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": base_url + 'get_payments_history',
				"columnDefs": [{ orderable: false, targets: [soryFlast, 0] }]
			});


		}
	}

};
