<?php 
class quotation_update{

public function quotation_master_update()
{
	$quotation_id = $_POST['quotation_id'];
	$enquiry_id = $_POST['enquiry_id'];
	$customer_name = $_POST['customer_name'];
    $email_id = $_POST['email_id'];
    $mobile_no = $_POST['mobile_no'];
	$travel_datetime =  $_POST['travel_datetime'];
	$sector_from =  $_POST['sector_from'];
	$sector_to =  $_POST['sector_to'];
	$preffered_airline =  $_POST['preffered_airline'];
	$class_type =  $_POST['class_type'];
	$trip_type =  $_POST['trip_type'];
	$total_seats = $_POST['total_seats'];
	$quotation_date =  $_POST['quotation_date'];
	$subtotal =  $_POST['subtotal'];
	$markup_cost =  $_POST['markup_cost'];
	$markup_cost_subtotal =  $_POST['markup_cost_subtotal'];
	$service_tax =  $_POST['service_tax'];
	$taxation_id =  $_POST['taxation_id'];
	$service_tax_subtotal =  $_POST['service_tax_subtotal'];		
	$total_tour_cost =  $_POST['total_tour_cost'];
 
	//Plane
	$from_city_id_arr = $_POST['from_city_id_arr'];
	$to_city_id_arr = $_POST['to_city_id_arr'];
    $plane_from_location_arr = $_POST['plane_from_location_arr'];
    $plane_to_location_arr = $_POST['plane_to_location_arr'];
    $airline_name_arr = $_POST['airline_name_arr'];
    $plane_class_arr = $_POST['plane_class_arr'];
    $arraval_arr = $_POST['arraval_arr'];
    $dapart_arr = $_POST['dapart_arr'];
    $plane_id_arr = $_POST['plane_id_arr'];
 
	$quotation_date = get_date_db($quotation_date);
	$travel_datetime = get_datetime_db($travel_datetime);

	$query = "update flight_quotation_master set enquiry_id ='$enquiry_id', customer_name='$customer_name', email_id='$email_id', mobile_no='$mobile_no',traveling_date='$travel_datetime',sector_from='$sector_from',sector_to='$sector_to', preffered_airline='$preffered_airline',class_type='$class_type',trip_type='$trip_type',total_seats='$total_seats',  subtotal = '$subtotal',markup_cost='$markup_cost',markup_cost_subtotal='$markup_cost_subtotal', taxation_id = '$taxation_id', service_tax = '$service_tax', service_tax_subtotal = '$service_tax_subtotal', quotation_cost = '$total_tour_cost', quotation_date='$quotation_date' where quotation_id = '$quotation_id'";
	$sq_quotation = mysql_query($query);

	if($sq_quotation){
		$this->plane_entries_update($quotation_id,$from_city_id_arr, $to_city_id_arr,  $plane_from_location_arr, $plane_to_location_arr, $plane_class_arr,$airline_name_arr, $arraval_arr, $dapart_arr, $plane_id_arr);
		echo "Quotation has been successfully updated.";	
		exit;
	}
	else{
		echo "error--Quotation not updated!";
		exit;
	}

}

public function plane_entries_update($quotation_id,$from_city_id_arr, $to_city_id_arr,  $plane_from_location_arr, $plane_to_location_arr, $plane_class_arr,$airline_name_arr, $arraval_arr, $dapart_arr, $plane_id_arr)
{
	for($i=0; $i<sizeof($plane_from_location_arr); $i++){
			$arraval_arr[$i] = date('Y-m-d H:i:s', strtotime($arraval_arr[$i]));
		    $dapart_arr[$i] = date('Y-m-d H:i:s', strtotime($dapart_arr[$i]));
			if($plane_id_arr[$i]=="")
			{
				$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from flight_quotation_plane_entries"));
				$id = $sq_max['max']+1;

				$sq_plane = mysql_query("insert into flight_quotation_plane_entries ( id, quotation_id,  from_city, from_location, to_city, to_location,airline_name, class, arraval_time, dapart_time) values ( '$id', '$quotation_id', '$from_city_id_arr[$i]', '$plane_from_location_arr[$i]', '$to_city_id_arr[$i]', '$plane_to_location_arr[$i]','$airline_name_arr[$i]', '$plane_class_arr[$i]', '$arraval_arr[$i]', '$dapart_arr[$i]' )");
				if(!$sq_plane)
				{
					echo "record not inserted.";
					exit;
				}
			}else
			{
				$sq_update=mysql_query("UPDATE `flight_quotation_plane_entries` SET `from_location`='$plane_from_location_arr[$i]',`to_location`='$plane_to_location_arr[$i]',airline_name='$airline_name_arr[$i]',`class`='$plane_class_arr[$i]',`arraval_time`='$arraval_arr[$i]',`dapart_time`='$dapart_arr[$i]', from_city='$from_city_id_arr[$i]', to_city='$to_city_id_arr[$i]' WHERE `id`='$plane_id_arr[$i]'");
				if(!$sq_update)
				{
					echo "record not updated";
					exit;
				}
			}
	}

}
}
?>