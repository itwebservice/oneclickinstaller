$('#traveling_date,#quotation_date').datetimepicker({ timepicker: false, format: 'd-m-Y' });

function quotation_cost_calculate () {
	var quotation_cost = 0;
	var subtotal = $('#subtotal').val();
	var markup_cost = $('#markup_cost').val();
	var markup_cost_subtotal = $('#markup_cost_subtotal').val();
	var service_tax = $('#service_tax').val();
	var permit = $('#permit').val();
	var toll_parking = $('#toll_parking').val();
	var driver_allowance = $('#driver_allowance').val();
	var state_entry = $('#state_entry').val();
	var other_charges = $('#other_charges').val();

	if (subtotal == '') {
		subtotal = 0;
	}
	if (markup_cost == '') {
		markup_cost = 0;
	}
	if (markup_cost_subtotal == '') {
		markup_cost_subtotal = 0;
	}
	if (service_tax == '') {
		service_tax = 0;
	}
	if (permit == '') {
		permit = 0;
	}
	if (toll_parking == '') {
		toll_parking = 0;
	}
	if (driver_allowance == '') {
		driver_allowance = 0;
	}
	if (state_entry == '') {
		state_entry = 0;
	}
	if (other_charges == '') {
		other_charges = 0;
	}

	if (parseFloat(markup_cost) == 0) {
		var t_subtotal = parseFloat(subtotal) + parseFloat(markup_cost_subtotal);
	}
	else {
		markup_cost_subtotal = parseFloat(subtotal) / 100 * parseFloat(markup_cost);
		var t_subtotal = parseFloat(subtotal) + parseFloat(markup_cost_subtotal);
	}

	var service_tax_amount = parseFloat(t_subtotal) / 100 * parseFloat(service_tax);
	total_tour_cost =
		parseFloat(subtotal) +
		parseFloat(markup_cost_subtotal) +
		parseFloat(permit) +
		parseFloat(toll_parking) +
		parseFloat(driver_allowance) +
		parseFloat(state_entry) +
		parseFloat(other_charges) +
		parseFloat(service_tax_amount);
	quotation_cost = parseFloat(total_tour_cost);

	$('#service_tax_subtotal').val(service_tax_amount.toFixed(2));
	$('#markup_cost_subtotal').val(markup_cost_subtotal);
	$('#total_tour_cost').val(total_tour_cost.toFixed(2));
}

function get_enquiry_details (offset = '') {
	var enquiry_id = $('#enquiry_id' + offset).val();
	var base_url = $('#base_url').val();
	$.ajax({
		type     : 'post',
		url      : base_url + 'view/package_booking/quotation/car_flight/car_rental/get_enquiry_details.php',
		dataType : 'json',
		data     : { enquiry_id: enquiry_id },
		success  : function (result) {
			$('#customer_name' + offset).val(result.name);
			$('#email_id' + offset).val(result.email_id);
			$('#mobile_no' + offset).val(result.mobile_no);
			$('#total_pax' + offset).val(result.total_pax);
			$('#days_of_traveling' + offset).val(result.days_of_traveling);
			$('#traveling_date' + offset).val(result.traveling_date);
			$('#vehicle_name' + offset).val(result.vehicle_type);
			$('#travel_type' + offset).val(result.travel_type);
			// $('#local_places_to_visit' + offset).val(result.places_to_visit);
			reflect_feilds();
			get_car_cost();
			get_capacity();
		},
		error    : function (result) {
			// alert(result);
			console.log(result.responseText);
		}
	});
}
function get_car_cost(){
	var travel_type = $('#travel_type').val();
	var vehicle_name = $('#vehicle_name').val();
	var places_to_visit = $('#places_to_visit').val();
	
	var base_url = $('#base_url').val();
	$.ajax({
		type     : 'post',
		url      : base_url + 'view/package_booking/quotation/car_flight/car_rental/get_car_cost.php',
		dataType : 'json',
		data     : { travel_type: travel_type, vehicle_name: vehicle_name,places_to_visit:places_to_visit },
		success  : function (result) {
			console.log(result);
			// var hotel_arr = JSON.parse(result);

			$('#total_hr').val(result[0].total_hrs);
			$('#total_km').val(result[0].total_km);
			$('#extra_hr_cost').val(result[0].extra_hrs_rate);
			$('#extra_km_cost').val(result[0].extra_km_rate);
			$('#route').val(result[0].route);
			$('#days_of_traveling').val(result[0].total_days);
			$('#total_max_km').val(result[0].total_max_km);
			$('#rate').val(result[0].rate);
			$('#driver_allowance').val(result[0].driver_allowance);
			$('#permit').val(result[0].permit_charges);
			$('#toll_parking').val(result[0].toll_parking);
			$('#state_entry').val(result[0].state_entry_pass);
			$('#other_charges').val(result[0].other_charges);
		},
		error    : function (result) {
			// alert(result);
			console.log(result.responseText);
		}
	});
}

function reflect_feilds() {
	var type = $('#travel_type').val();

	if (type == 'Local') {
		$('#from_date,#to_date,#total_hr,#total_km,#local_places_to_visit').show();
		$(
			'#total_max_km,#driver_allowance,#permit,#toll_parking,#state_entry,#other_charges,#places_to_visit,#traveling_date'
		).hide();
	}
	if (type == 'Outstation') {
		$('#from_date,#to_date,#total_hr,#total_km,#local_places_to_visit').hide();
		$(
			'#total_max_km,#driver_allowance,#permit,#toll_parking,#state_entry,#other_charges,#places_to_visit,#traveling_date'
		).show();
	}
}

function get_flight_enquiry_details (offset = '') {
	var enquiry_id = $('#enquiry_id' + offset).val();
	var base_url = $('#base_url').val();
	$.ajax({
		type     : 'post',
		url      : base_url + 'view/package_booking/quotation/car_flight/flight/get_enquiry_details.php',
		dataType : 'json',
		data     : { enquiry_id: enquiry_id },
		success  : function (result) {
			$('#customer_name' + offset).val(result.name);
			$('#email_id' + offset).val(result.email_id);
			$('#mobile_no' + offset).val(result.mobile_no);
			$('#travel_datetime' + offset).val(result.travel_datetime);
			$('#sector_from' + offset).val(result.sector_from);
			$('#sector_to' + offset).val(result.sector_to);
			$('#preffered_airline' + offset).val(result.preffered_airline);
			$('#class_type' + offset).val(result.class_type);
			$('#trip_type' + offset).val(result.trip_type);
			$('#total_seats' + offset).val(result.total_seats);
			$('#from_city-1').val(result.from_city_name.city_id);
			$('#from_city-1').trigger('change');
			$('#to_city-1').val(result.to_city_name.city_id);
			$('#to_city-1').trigger('change');
			$('#handler').on('click', function () {
				$('#plane_from_location-1').val(result.sector_from_added);
				$('#plane_from_location-1').trigger('change');
				$('#plane_to_location-1').val(result.sector_to_added);
				$('#plane_to_location-1').trigger('change');
			});
			$('#plane_class-1').val(result.class_type);
			$('#plane_class-1').trigger('change');
			$('#airline_name-1').val(result.preffered_airline_id);
			$('#airline_name-1').trigger('change');
		},
		error    : function (result) {
			console.log(result.responseText);
		}
	});
}

function flight_quotation_cost_calculate (offset = '') {
	var quotation_cost = 0;
	var subtotal = $('#subtotal' + offset).val();
	var service_tax = $('#service_tax' + offset).val();
	var markup_cost = $('#markup_cost' + offset).val();
	var markup_cost_subtotal = $('#markup_cost_subtotal' + offset).val();

	if (subtotal == '') {
		subtotal = 0;
	}
	if (markup_cost == '') {
		markup_cost = 0;
	}
	if (service_tax == '') {
		service_tax = 0;
	}
	if (markup_cost_subtotal == '') {
		markup_cost_subtotal = 0;
	}

	if (parseFloat(markup_cost) == 0) {
		var t_subtotal = parseFloat(subtotal) + parseFloat(markup_cost_subtotal);
	}
	else {
		markup_cost_subtotal = parseFloat(subtotal) / 100 * parseFloat(markup_cost);
		var t_subtotal = parseFloat(subtotal) + parseFloat(markup_cost_subtotal);
	}
	var service_tax_amount = parseFloat(markup_cost_subtotal) / 100 * parseFloat(service_tax);
	total_tour_cost = parseFloat(subtotal) + parseFloat(markup_cost_subtotal) + parseFloat(service_tax_amount);
	quotation_cost = parseFloat(total_tour_cost);

	$('#service_tax_subtotal' + offset).val(service_tax_amount.toFixed(2));
	$('#markup_cost_subtotal' + offset).val(markup_cost_subtotal);
	$('#total_tour_cost' + offset).val(total_tour_cost.toFixed(2));
}
