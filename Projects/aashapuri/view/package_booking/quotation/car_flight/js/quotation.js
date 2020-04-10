$('#traveling_date,#quotation_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
 
function quotation_cost_calculate()
{
	var quotation_cost = 0;
	var subtotal = $('#subtotal').val();  
	var markup_cost = $('#markup_cost').val();  
	var markup_cost_subtotal = $('#markup_cost_subtotal').val();
	var service_tax = $('#service_tax').val();
	var permit = $('#permit').val();
	var toll_parking = $('#toll_parking').val();
	var driver_allowance = $('#driver_allowance').val();

  if(subtotal==""){ subtotal = 0;}
  if(markup_cost==""){ markup_cost = 0;}
  if(markup_cost_subtotal==""){ markup_cost_subtotal = 0;}
  if(service_tax==""){ service_tax = 0;}
  if(permit==""){permit = 0;}
  if(toll_parking==""){ toll_parking = 0;}
  if(driver_allowance==""){ driver_allowance = 0;}

   if(parseFloat(markup_cost) == 0){
   		var t_subtotal = parseFloat(subtotal) + parseFloat(markup_cost_subtotal);
   }
   else{
   	   markup_cost_subtotal = (parseFloat(subtotal)/100) * parseFloat(markup_cost);
   	   var t_subtotal = parseFloat(subtotal) + parseFloat(markup_cost_subtotal);
   }

    var service_tax_amount = (parseFloat(markup_cost_subtotal)/100) * parseFloat(service_tax);
	total_tour_cost = parseFloat(subtotal) + parseFloat(markup_cost_subtotal) + parseFloat(permit) + parseFloat(toll_parking) + parseFloat(driver_allowance) + parseFloat(service_tax_amount);
 	quotation_cost = parseFloat(total_tour_cost) ;

	$('#service_tax_subtotal').val(service_tax_amount.toFixed(2));
	$('#markup_cost_subtotal').val(markup_cost_subtotal);
	$('#total_tour_cost').val(total_tour_cost.toFixed(2));

}

function get_enquiry_details(offset="")
{
	var enquiry_id = $('#enquiry_id'+offset).val();
	var base_url = $('#base_url').val();
	$.ajax({
		type:'post',
		url: base_url+'view/package_booking/quotation/car_flight/car_rental/get_enquiry_details.php', 
		dataType: "json",
		data: { enquiry_id : enquiry_id }, 
		success: function(result){
			$('#customer_name'+offset).val(result.name);
			$('#email_id'+offset).val(result.email_id);
			$('#mobile_no'+offset).val(result.mobile_no);
			$('#total_pax'+offset).val(result.total_pax);
			$('#days_of_traveling'+offset).val(result.days_of_traveling);
			$('#traveling_date'+offset).val(result.traveling_date);
			$('#vehicle_type'+offset).val(result.vehicle_type);
			$('#travel_type'+offset).val(result.travel_type);
			$('#places_to_visit'+offset).val(result.places_to_visit);
		},
		error:function(result){
			console.log(result.responseText);
		}
	});
}

function get_flight_enquiry_details(offset="")
{
	 
	var enquiry_id = $('#enquiry_id'+offset).val();
	var base_url = $('#base_url').val();
	$.ajax({
		type:'post',
		url: base_url+'view/package_booking/quotation/car_flight/flight/get_enquiry_details.php', 
		dataType: "json",
		data: { enquiry_id : enquiry_id }, 
		success: function(result){
			$('#customer_name'+offset).val(result.name);
			$('#email_id'+offset).val(result.email_id);
			$('#mobile_no'+offset).val(result.mobile_no);
			$('#travel_datetime'+offset).val(result.travel_datetime);
			$('#sector_from'+offset).val(result.sector_from);
			$('#sector_to'+offset).val(result.sector_to);
			$('#preffered_airline'+offset).val(result.preffered_airline);
			$('#class_type'+offset).val(result.class_type);
			$('#trip_type'+offset).val(result.trip_type);
			$('#total_seats'+offset).val(result.total_seats);
			 
		},
		error:function(result){
			console.log(result.responseText);
		}
	});
}
 

 function flight_quotation_cost_calculate(offset="")
{
    var quotation_cost = 0;
	var subtotal = $('#subtotal'+offset).val();  
	var service_tax = $('#service_tax'+offset).val();
	var markup_cost = $('#markup_cost'+offset).val(); 
	var markup_cost_subtotal = $('#markup_cost_subtotal'+offset).val(); 

    if(subtotal==""){ subtotal = 0;}
    if(markup_cost==""){ markup_cost = 0;}
    if(service_tax==""){ service_tax = 0;}
    if(markup_cost_subtotal==""){ markup_cost_subtotal = 0;}
    
    
    if(parseFloat(markup_cost) == 0){
   		var t_subtotal = parseFloat(subtotal) + parseFloat(markup_cost_subtotal);
    }
    else{
   	   markup_cost_subtotal = (parseFloat(subtotal)/100) * parseFloat(markup_cost);
       var t_subtotal = parseFloat(subtotal) + parseFloat(markup_cost_subtotal);
    }
    var service_tax_amount = (parseFloat(markup_cost_subtotal)/100) * parseFloat(service_tax);
	total_tour_cost = parseFloat(subtotal) + parseFloat(markup_cost_subtotal) + parseFloat(service_tax_amount);
 	quotation_cost = parseFloat(total_tour_cost) ;

	$('#service_tax_subtotal'+offset).val(service_tax_amount.toFixed(2));
	$('#markup_cost_subtotal'+offset).val(markup_cost_subtotal);
	$('#total_tour_cost'+offset).val(total_tour_cost.toFixed(2));

}