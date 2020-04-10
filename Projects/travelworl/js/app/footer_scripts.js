$(function () {
	$('form').attr('autocomplete', 'off');
	$('input').attr('autocomplete', 'off');
});

$(function () {
	$('.feature_editor').wysiwyg({
		controls       : 'bold,italic,|,undo,redo,image|h1,h2,h3,decreaseFontSize,highlight',
		initialContent : ' '
	});
});

//**Sidebar Scroll
(function ($) {
	$(window).on('load', function () {
		$('.sidebar_wrap').mCustomScrollbar();
	});
})(jQuery);

//**Site Tooltips
$(function () {

    $("[data-toggle='tooltip']").tooltip({placement: 'bottom'});

});

$(function () {
	$('input[type="text"], input[type="number"], select, textarea').addClass('form-control');

	$('.no_form_control').removeClass('form-control');
});

//* round off values function *//

function round_off_value (amount) {
	var amount1 = parseFloat(amount).toFixed(2);

	return amount1;
}

//**Message alert

function msg_alert (message) {
	var msg = message.split('--');

	if (msg[0] == 'error') {
		error_msg_alert(msg[1]);
	}
	else {
		success_msg_alert(message);
	}
}
//branch reflect
function emp_branch_reflect () {
	var base_url = $('#base_url').val();
	var emp_id = $('#booker_id_filter').val();

	$.post(base_url + 'view/load_data/branch_reflect.php', { emp_id: emp_id }, function (data) {
		$('#branch_id_filter').html(data);
	});
}

//Customer branch reflect
function cust_branch_reflect () {
	var base_url = $('#base_url').val();
	var cust_id = $('#customer_filter').val();

	$.post(base_url + 'view/load_data/cust_branch_reflect.php', { cust_id: cust_id }, function (data) {
		$('#branch_id_filter').html(data);
	});
}
//**Error Message Alert

function error_msg_alert(message, delay='4000')

{
	$('#site_alert').empty();    // to only display one error message
	$('#site_alert').vialert({ type:"error", title:"Error", message:message, delay:delay });
}

//**Success Message Alert

function success_msg_alert(message)

{
	$('#site_alert').empty();	// to only display one success message
	$('#site_alert').vialert({ message:message });
}

//**Message popup reload

function msg_popup_reload (message) {
	var msg = message.split('--');

	if (msg[0] == 'error') {
		error_msg_alert(msg[1]);
	}
	else {
		$('#vi_confirm_box').vi_confirm_box({
			false_btn     : false,

			message       : message,

			true_btn_text : 'Ok',

			callback      : function (data1) {
				if (data1 == 'yes') {
					document.location.reload();
				}
			}
		});
	}
}

//**Reset Form

function reset_form (form_id) {
	$('#' + form_id).find('input[type="text"]').each(function () {
		$(this).val('');
	});

	$('#' + form_id).find('textarea').each(function () {
		$(this).val('');
	});

	$('#' + form_id).find('select').each(function () {
		$(this).prop('selected', function () {
			return this.defaultSelected;
		});
	});
}

//**Element count in array

function isInArray (value, array1) {
	for (var arr_count = 0; arr_count < array1.length; arr_count++) {
		if (array1[arr_count] == value) {
			return false;
		}
	}

	return true;
}

//**Generic Tooltip

/*$(function() {

    $('input, select, textarea, span, a').tooltip({placement: 'bottom'});

});*/
$(function () {
	//$('input,  textarea, span, a').tooltip();

	$('input,textarea,span, a').tooltip({ placement: 'bottom' });
	$('input,textarea,span, a').focus(function () {
		$('input,textarea,span, a').tooltip('hide');
	});
});

//**Radio button and checkboxes

$(document).ready(function () {
	$("input[type='radio'], input[type='checkbox']").labelauty({ label: false, maximum_width: '20px' });
});

//**Dual button

$(function () {
	$('.app_dual_button input[type="checkbox"], .app_dual_button input[type="radio"]').change(function () {
		$(this).parent().siblings().removeClass('active');

		$(this).parent().addClass('active');
	});
});

//**First letter capital event start**//

$(function () {
	var exception_fields_arr = [
		'app_website',
		'sms_username',
		'sms_password',
		'server_username',
		'txt_username',
		'app_smtp_host',
		'app_smtp_port',
		'app_smtp_password',
		'app_smtp_method',
		'airport_code1',
		'check_in',
		'check_out',
		'check_in1',
		'check_out1',
		're_password',
		'new_password',
		'current_password',
		'app_name',
		'bank_name',
		'bank_ifsc_code',
		'bank_swift_code',
		'package_name',
		'package_code',
		'package_name1',
		'package_code1',
		'corpo_company_name'
	];

	$('input[type="text"]').change(function () {
		var str_arr = $(this).val();

		var id = $(this).attr('id');

		if (jQuery.inArray(id, exception_fields_arr) == -1) {
			if (!id.includes('email')) {
				//	$(this).val( toTitleCase(str_arr) );
			}
		}
	});
});

function toTitleCase (str) {
	return str.replace(/\w\S*/g, function (txt) {
		return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
	});
}

//**First letter capital event end**//

//**App Base URL start**//

function base_url () {
	var base_url = $('#base_url').val();

	return base_url;
}

//**App Base URL end**//

//**Bank List reflect autocomplete start**//

function bank_list_reflect () {
	var base_url = $('#base_url').val();

	$.post(base_url + 'view/load_data/bank_list_json_response.php', {}, function (data) {
		var data = jQuery.parseJSON(data);
		bank_name_autocomplete(data);
	});
}
bank_list_reflect();

function bank_name_autocomplete(data){
	$('.bank_suggest').each(function(){		$(this).autocomplete({	source: data	});	});
}
//**Bank List reflect autocomplete end**//
//**Calculate age generic start**//
function calculate_age_generic(from, to) 

{

  var dateString1=$("#"+from).val();

  var get_new = dateString1.split('-');



  var day=get_new[0];

  var month=get_new[1];

  var year=get_new[2];



  var dateString = month+"/"+day+"/"+year;




	var get_new = dateString1.split('-');

	var day = get_new[0];

	var month = get_new[1];

	var year = get_new[2];

	var dateString = month + '/' + day + '/' + year;

	tagText = dateString.replace(/-/g, '/');

	var today = new Date();

	var birthDate = new Date(tagText);

	var age = today.getFullYear() - birthDate.getFullYear();

	var m = today.getMonth() - birthDate.getMonth();

	if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
		age--;
	}

	$('#' + to).val(age);
}

//**Calculate age generic start**//

//**Generic Customer save start**//

function customer_save_modal (client_modal_type = 'other') {
	var base_url = $('#base_url').val();

	$.post(base_url + 'view/customer_master/save_modal.php', { client_modal_type: client_modal_type }, function (data) {
		$('#div_customer_save_modal').html(data);
	});
}

function customer_dropdown_reload (cust_id = '') {
	var base_url = $('#base_url').val();

	$('.customer_dropdown').each(function () {
		var cur_ele = $(this);

		$.post(base_url + 'view/customer_master/customer_dropdown_load.php', {}, function (data) {
			$(cur_ele).select2();

			$(cur_ele).css('width', '100%');

			$(cur_ele).html(data);

			if (cust_id != '') {
				$(cur_ele).val(cust_id);
			}

			$(cur_ele).trigger('change');
		});
	});
}

//**Generic Customer save end**//

//**Generic Hotel save start**//

function hotel_save_modal () {
	var base_url = $('#base_url').val();
	var target = '_blank';
	window.open(base_url + 'view/hotels/master/index.php', target);
}

function hotel_dropdown_reload (hotel_id = '') {
	var base_url = $('#base_url').val();

	$('.hotel_dropdown').each(function () {
		var cur_ele = $(this);

		$.post(base_url + 'view/hotels/master/hotel/hotel_dropdown_load.php', {}, function (data) {
			$(cur_ele).select2();

			$(cur_ele).css('width', '100%');

			$(cur_ele).html(data);

			if (hotel_id != '') {
				$(cur_ele).val(hotel_id);
			}

			$(cur_ele).trigger('change');
		});
	});
}

//**Generic Hotel save end**//

function corporate_fields_reflect () {
	var base_url = $('#base_url').val();

	var cust_type = $('#cust_type').val();

	var customer_id = $('#customer_id').val();

	$.post(
		base_url + 'view/customer_master/corporate_fields_reflect.php',
		{ cust_type: cust_type, customer_id: customer_id },
		function (data) {
			$('#corporate_fields').html(data);
		}
	);
}

//**Generic City save modal start**//

function generic_city_save_modal (modal_type = '') {
	$('#btn_city_save_modal').button('loading');

	var base_url = $('#base_url').val();

	$.post(base_url + 'view/other_masters/cities/save_modal.php', { modal_type: modal_type }, function (data) {
		$('#btn_city_save_modal').button('reset');

		$('#div_city_save_modal').html(data);
	});
}

function city_master_dropdown_reload () {
	var city_master_dropdown = 'city_master_dropdown';

	var base_url = $('#base_url').val();

	$('.city_master_dropdown').each(function () {
		var cur_ele = $(this);

		$.post(
			base_url + 'modal/app_settings/dropdown_master.php',
			{ city_master_dropdown: city_master_dropdown },
			function (data) {
				$(cur_ele).select2();

				$(cur_ele).css('width', '100%');

				$(cur_ele).html(data).trigger('change');
			}
		);
	});
}

//**Generic City save modal end**//

//**Generic PAyment fields toggle function start**//

function payment_master_toggles (payment_mode_id, bank_name_id, transaction_id_id, bank_id_id) {
	var payment_mode = $('#' + payment_mode_id).val();

	if (payment_mode == 'Cash' || payment_mode == '' || payment_mode == 'Credit Note' || payment_mode == 'Debit Note') {
		$('#' + bank_name_id).prop({ disabled: 'disabled', readonly: 'readonly', value: '' });
		$('#' + transaction_id_id).prop({ disabled: 'disabled', readonly: 'readonly', value: '' });
		$('#' + bank_id_id).prop({ disabled: 'disabled', readonly: 'readonly', value: '' });
	}
	else {
		$('#' + bank_name_id).prop({ disabled: '', readonly: '' });

		$('#' + transaction_id_id).prop({ disabled: '', readonly: '' });

		$('#' + bank_id_id).prop({ disabled: '', readonly: '' });
	}
}

//**Generic PAyment fields toggle function end**//

//If payment amount 0 disable payment mode

function payment_amount_validate (payment_amount_id, payment_mode_id, transaction_id_id, bank_name_name, bank_id_id) {
	var payment_amt = $('#' + payment_amount_id).val();

	if (payment_amt == 0) {
		$('#' + payment_mode_id).prop({ disabled: 'disabled', value: '' });

		$('#' + transaction_id_id).prop({ disabled: 'disabled', value: '' });

		$('#' + bank_name_name).prop({ disabled: 'disabled', value: '' });

		$('#' + bank_id_id).prop({ disabled: 'disabled', value: '' });
	}
	else {
		$('#' + payment_mode_id).prop({ disabled: '' });
	}
}

function generic_tax_reflect_temp (src_id, desc_id, funct_call) {
	var offset = src_id.split('-');

	desc_id = desc_id + '' + offset[1];

	generic_tax_reflect(src_id, desc_id, funct_call, src_id);
}

//**Generic service tax reflect start**//

function generic_tax_reflect (src_id, desc_id, funct_call, offset = '', temp_data = '') {
	var taxation_id = $('#' + src_id).val();

	$.post(base_url() + 'view/load_data/generic_tax_reflect.php', { taxation_id: taxation_id }, function (data) {
		$('#' + desc_id).val(data);

		if (temp_data != '') {
			window[funct_call](offset, temp_data);
		}
		else {
			if (offset == '') {
				window[funct_call]();
			}
			else {
				window[funct_call](offset);
			}
		}
	});
}

//**Generic service tax reflect end**//
//**PHP to Javascript date converter**//
function php_to_js_date_converter (dateString1) {
	var get_new = dateString1.split('-');

	var day = get_new[0];

	var month = get_new[1];

	var year = get_new[2];

	var dateString = month + '/' + day + '/' + year;

	tagText = dateString.replace(/-/g, '/');

	var new_date = new Date(tagText);

	return new_date;
}

//**Trim characters**//

String.prototype.trimChars = function (chars) {
	var l = 0;

	var r = this.length - 1;

	while (chars.indexOf(this[l]) >= 0 && l < r) l++;

	while (chars.indexOf(this[r]) >= 0 && r >= l) r--;

	return this.substring(l, r + 1);
};

function printdiv (printpage, tbl_id) {
	$('#' + tbl_id).dataTable().fnDestroy();

	var headstr = '<html><head><title></title></head><body>';

	var footstr = '</body>';

	var newstr = document.all.item(printpage).innerHTML;

	var oldstr = document.body.innerHTML;

	document.body.innerHTML = headstr + newstr + footstr;

	window.print();

	document.body.innerHTML = oldstr;

	$('#' + tbl_id).dataTable();

	return false;
}

function check_pdf_size (pdf_size, url, url1) {
	var pdf_size = $('#' + pdf_size).val();

	if (pdf_size == 'A4 Full Size') {
		window.open(url, '_blank');
	}
	else {
		window.open(url1, '_blank');
	}
}
//Print
function loadOtherPage (url) {
	$('<iframe>').hide().attr('src', url).appendTo('body');
	//window.location.href= url;
}

function check_package_type (setup_package, module_name) {
	var base_url = $('#base_url').val();
	if (module_name == 'user') {
		$.ajax({
			type    : 'POST',
			url     : base_url + 'view/package_permission/user_permission.php',
			data    : {},
			async   : false,
			success : function (data1) {
				$('#user_count').val(data1);
			}
		});
	}
	if (module_name == 'branch') {
		$.ajax({
			type    : 'POST',
			url     : base_url + 'view/package_permission/branch_permission.php',
			data    : {},
			async   : false,
			success : function (data1) {
				$('#branch_count').val(data1);
			}
		});
	}
}
function remove_hidden_class () {
	$('#package_permission').addClass('hidden');
}
function display_description (type, entry_id) {
	var base_url = $('#base_url').val();
	$.post(base_url + 'view/load_data/module_description_modal.php', { entry_id: entry_id, type: type }, function (
		data
	) {
		$('#div_content_modal').html(data);
	});
}

function select_all_check (id, custom_package) {
	var checked = $('#' + id).is(':checked');
	// Select all
	if (checked) {
		$('.' + custom_package).each(function () {
			$(this).prop('checked', true);
		});
	}
	else {
		// Deselect All
		$('.' + custom_package).each(function () {
			$(this).prop('checked', false);
		});
	}
}

function show_password (password) {
	var x = document.getElementById(password);
	if (x.type === 'password') {
		x.type = 'text';
	}
	else {
		x.type = 'password';
	}
}
