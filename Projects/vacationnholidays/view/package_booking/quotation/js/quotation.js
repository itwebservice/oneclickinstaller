$('#train_arrival_date1, #train_departure_date1').datetimepicker({ format:'d-m-Y H:i:s' });
$('#transport_start_date1, #transport_end_date1').datetimepicker({ timepicker:false, format:'d-m-Y' });

function total_days_reflect(offset=''){
    var from_date = $('#from_date'+offset).val(); 
    var to_date = $('#to_date'+offset).val(); 

    var edate = from_date.split("-");
    e_date = new Date(edate[2],edate[1]-1,edate[0]).getTime();
    var edate1 = to_date.split("-");
    e_date1 = new Date(edate1[2],edate1[1]-1,edate1[0]).getTime();

    var one_day=1000*60*60*24;

    var from_date_ms = new Date(e_date).getTime();
    var to_date_ms = new Date(e_date1).getTime();
    
    var difference_ms = to_date_ms - from_date_ms;
    var total_days = Math.round(Math.abs(difference_ms)/one_day); 

    total_days = parseFloat(total_days)+1;
    $('#total_days'+offset).val(total_days);
}

function package_dynamic_reflect(dest_name){
	var dest_id = $("#"+dest_name).val();
  var base_url = $('#base_url').val();

	$.ajax({
		type:'post',
		url: base_url+'view/package_booking/quotation/inc/get_packages.php', 
		data: { dest_id : dest_id}, 
		success: function(result){
			$('#package_name_div').html(result);
		},
		error:function(result){
			console.log(result.responseText);
		}
	});
}

/////////////////////////////////////Site seeing related info start/////////////////////////////////////
function site_seeing_save_modal(){
  var base_url = $('#base_url').val();
  $.post(base_url+'view/site_seeing/site_seeing_save_modal.php', { }, function(data){
    $('#div_site_seeing_save').html(data);
  });
}
function site_seeing_save_msg(result){
  var base_url = $('#base_url').val();
  $('#site_seeing_save_modal').modal('hide');
  $.post(base_url+'view/package_booking/booking/inc/site_seeing_list_reflect.php', { }, function(data){
    $('#ul_site_seeing_list').html(data);
  });
}

function citywise_site_seeing_dynamic_reflect(city_id){
    if(city_id==""){
        $('#ul_site_seeing_list li').removeClass('hidden');
    }
    else{
        $('#ul_site_seeing_list li').addClass('hidden');
        $('#ul_site_seeing_list li[data-city-id="'+city_id+'"]').removeClass('hidden');    
    }
    
}

/////////////////////////////////////Site seeing related info end/////////////////////////////////////

function total_passangers_calculate(offset="")
{
	var total_adult = $('#total_adult'+offset).val();
	var total_children = $('#total_children'+offset).val();
	var total_infant = $('#total_infant'+offset).val();

	var total_passangers = parseFloat(total_adult) + parseFloat(total_children) + parseFloat(total_infant);

	$('#total_passangers'+offset).val(total_passangers);

}

function group_quotation_cost_calculate(){
  var total_adult = $('#total_adult').val();
  var total_infant = $('#total_infant').val();
  var total_children = $('#total_children').val();
  var adult_cost = $('#adult_cost').val();
  var children_cost = $('#children_cost').val();
  var infant_cost = $('#infant_cost').val();
  var with_bed_cost = $('#with_bed_cost').val();
  var markup_cost = $('#markup_cost').val();
  var markup_cost_subtotal = $('#markup_cost_subtotal').val();
  var total_tour_cost = $('#total_tour_cost').val();
  var tour_cost = $('#tour_cost').val();
  var service_tax = $('#service_tax').val();

  if(adult_cost==""){ adult_cost = 0;}
  if(children_cost==""){children_cost = 0;}
  if(infant_cost==""){ infant_cost = 0;}
  if(markup_cost==""){ markup_cost = 0;}
  if(markup_cost_subtotal==""){ markup_cost_subtotal = 0;}
  if(tour_cost==""){tour_cost = 0;}
  if(total_tour_cost==""){total_tour_cost1 = 0;}

  $('#adult_cost').val(adult_cost);
  var total = parseFloat(adult_cost) + parseFloat(children_cost) + parseFloat(infant_cost) + parseFloat(with_bed_cost);
  $('#tour_cost').val(total.toFixed(2));

  
  if(parseFloat(markup_cost) == 0){
    total = total + parseFloat(markup_cost_subtotal);    
  }else{
     markup_cost_subtotal = (parseFloat(total)/100) * parseFloat(markup_cost);
    total = total + parseFloat(markup_cost_subtotal); 
  }
  
  var service_tax_amount = (parseFloat(total)/100) * parseFloat(service_tax);

  total_tour_cost1 = parseFloat(total) + parseFloat(service_tax_amount);

  $('#service_tax_subtotal').val(service_tax_amount.toFixed(2));
  $('#markup_cost_subtotal').val(markup_cost_subtotal);
  $('#total_tour_cost').val(total_tour_cost1.toFixed(2));
}

function quotation_cost_calculate(id){
  var offset = id.split('-');
  var quotation_cost = 0;
	var tour_cost = $('#tour_cost-'+offset[1]).val();  
  var transport_cost = $('#transport_cost-'+offset[1]).val();
	var service_tax = $('#service_tax-'+offset[1]).val();
	var markup_cost = $('#markup_cost-'+offset[1]).val();
  var markup_cost_subtotal = $('#markup_cost_subtotal-'+offset[1]).val();
  var excursion_cost = $('#excursion_cost-'+offset[1]).val();
	var travel_costing = $('#travel_costing-'+offset[1]).val();

	if(tour_cost==""){ tour_cost = 0;}
  if(transport_cost==""){ transport_cost = 0;}
  if(service_tax==""){service_tax = 0;}
  if(markup_cost==""){ markup_cost = 0;}
  if(markup_cost_subtotal==""){markup_cost_subtotal = 0;}
  if(excursion_cost==""){ excursion_cost = 0;}
  if(travel_costing==""){travel_costing = 0;}

	var sub_total = parseFloat(tour_cost) + parseFloat(transport_cost) + parseFloat(excursion_cost) ;
  
  if(parseFloat(markup_cost) == 0){
    var markup_total = parseFloat(markup_cost_subtotal);
    total = markup_total + sub_total;
  }
  else{
    var markup_total = (parseFloat(sub_total)/100) * parseFloat(markup_cost);
    total = markup_total + sub_total;
  }

  var service_tax_amount = (parseFloat(total)/100) * parseFloat(service_tax);
	total_tour_cost = parseFloat(total) + parseFloat(service_tax_amount);

  $('#markup_cost_subtotal-'+offset[1]).val(markup_total.toFixed(2));

  quotation_cost = parseFloat(travel_costing) + parseFloat(total_tour_cost);

	$('#service_tax_subtotal-'+offset[1]).val(service_tax_amount.toFixed(2));
	$('#total_tour_cost-'+offset[1]).val(total_tour_cost.toFixed(2));

}

function get_enquiry_details(offset=""){
	var enquiry_id = $('#enquiry_id'+offset).val();
  var base_url = $('#base_url').val();
	$.ajax({
		type:'post',
		url: base_url+'view/package_booking/quotation/get_enquiry_details.php', 
		dataType: "json",
		data: { enquiry_id : enquiry_id },
		success: function(result){
			$('#tour_name'+offset).val(result.tour_name);
			$('#total_days'+offset).val(result.total_days);
			$('#customer_name'+offset).val(result.name);
			$('#email_id'+offset).val(result.email_id);
      $('#mobile_no'+offset).val(result.landline_no);
			$('#total_adult'+offset).val(result.total_adult);
			$('#total_children'+offset).val(result.total_children);
			$('#total_infant'+offset).val(result.total_infant);

      $('#total_adult1'+offset).val(result.total_adult);
      $('#total_child1'+offset).val(result.total_children);
      $('#total_infant1'+offset).val(result.total_infant);
      
			$('#total_passangers'+offset).val( parseFloat(result.total_adult) + parseFloat(result.total_children) + parseFloat(result.total_infant) );
			$('#children_without_bed'+offset).val(result.children_without_bed);
			$('#children_with_bed'+offset).val(result.children_with_bed);
			$('#from_date'+offset).val(result.travel_from_date);
			$('#to_date'+offset).val(result.travel_to_date);
			total_days_reflect(offset);
		},
		error:function(result){
			console.log(result.responseText);
		}
	});
}