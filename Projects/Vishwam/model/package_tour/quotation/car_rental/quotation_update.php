<?php 
class quotation_update{

public function quotation_master_update()
{
	$quotation_id = $_POST['quotation_id'];
	$enquiry_id = $_POST['enquiry_id'];
	$customer_name = $_POST['customer_name'];
	$email_id = $_POST['email_id'];
    $mobile_no = $_POST['mobile_no'];
    $total_pax = $_POST['total_pax'];
    $days_of_traveling = $_POST['days_of_traveling'];
	$traveling_date =  $_POST['traveling_date'];
	$vehicle_type =  $_POST['vehicle_type'];
	$travel_type =  $_POST['travel_type'];
	$places_to_visit =  $_POST['places_to_visit'];
	$vehicle_name =  $_POST['vehicle_name'];
	$from_date =  $_POST['from_date'];
	$to_date = $_POST['to_date'];
	$trip_type =  $_POST['trip_type'];
	$route =  $_POST['route'];
	$extra_km_cost =  $_POST['extra_km_cost'];
	$extra_hr_cost =  $_POST['extra_hr_cost'];
	$daily_km =  $_POST['daily_km'];		
	$subtotal =  $_POST['subtotal'];	
	$markup_cost =  $_POST['markup_cost'];
	$markup_cost_subtotal =  $_POST['markup_cost_subtotal'];
	$taxation_id =  $_POST['taxation_id'];
	$service_tax =  $_POST['service_tax'];
	$service_tax_subtotal =  $_POST['service_tax_subtotal'];
	$permit =  $_POST['permit'];
	$toll_parking =  $_POST['toll_parking'];
	$driver_allowance =  $_POST['driver_allowance'];
	$total_tour_cost =  $_POST['total_tour_cost'];
	$quotation_date  = $_POST['quotation_date'];

	$traveling_date = get_date_db($traveling_date);	
	$quotation_date = get_date_db($quotation_date);
	$from_date = get_datetime_db($from_date);
	$to_date = get_datetime_db($to_date);

	$customer_name = addslashes($customer_name);
	$places_to_visit = addslashes($places_to_visit);
	$query = "update car_rental_quotation_master set enquiry_id = '$enquiry_id',customer_name='$customer_name', total_pax = '$total_pax', days_of_traveling ='$days_of_traveling', traveling_date = '$traveling_date', vehicle_type = '$vehicle_type', travel_type='$travel_type', places_to_visit = '$places_to_visit', vehicle_name = '$vehicle_name', from_date = '$from_date', to_date = '$to_date', trip_type = '$trip_type', route = '$route', extra_km_cost='$extra_km_cost', extra_hr_cost = '$extra_hr_cost', daily_km = '$daily_km', subtotal = '$subtotal',markup_cost ='$markup_cost',markup_cost_subtotal='$markup_cost_subtotal', taxation_id = '$taxation_id', service_tax = '$service_tax', service_tax_subtotal = '$service_tax_subtotal', permit='$permit', toll_parking='$toll_parking',driver_allowance='$driver_allowance',email_id='$email_id',mobile_no='$mobile_no', total_tour_cost = '$total_tour_cost', quotation_date='$quotation_date' where quotation_id = '$quotation_id'";
	$sq_quotation = mysql_query($query);

	if($sq_quotation){
		echo "Quotation has been successfully updated.";	
		exit;
	}
	else{
		echo "error--Quotation not updated!";
		exit;
	}

}

}
?>