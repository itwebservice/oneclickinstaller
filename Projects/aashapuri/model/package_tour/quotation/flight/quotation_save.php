<?php 
class quotation_save{

public function quotation_master_save()
{
	$enquiry_id = $_POST['enquiry_id'];
	$login_id = $_POST['login_id'];
	$emp_id = $_POST['emp_id'];
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
	$branch_admin_id = $_POST['branch_admin_id'];
	$financial_year_id = $_POST['financial_year_id'];

	//Plane
	$from_city_id_arr = $_POST['from_city_id_arr'];
	$to_city_id_arr = $_POST['to_city_id_arr'];
    $plane_from_location_arr = $_POST['plane_from_location_arr'];
    $plane_to_location_arr = $_POST['plane_to_location_arr'];
    $airline_name_arr = $_POST['airline_name_arr'];
    $plane_class_arr = $_POST['plane_class_arr'];
    $arraval_arr = $_POST['arraval_arr'];
    $dapart_arr = $_POST['dapart_arr'];
 
	$enquiry_content = '[{"name":"travel_datetime","value":"'.$travel_datetime.'"},{"name":"sector_from","value":"'.$sector_from.'"},{"name":"sector_to","value":"'.$sector_to.'"},{"name":"preffered_airline","value":"'.$preffered_airline.'"},{"name":"class_type","value":"'.$class_type.'"},{"name":"trip_type","value":"'.$trip_type.'"},{"name":"total_seats","value":"'.$total_seats.'"},{"name":"budget","value":"0"}]';
	$quotation_date = get_date_db($quotation_date);
	$travel_datetime = get_datetime_db($travel_datetime);
	$created_at = date('Y-m-d');
	 
	$sq_max = mysql_fetch_assoc(mysql_query("select max(quotation_id) as max from flight_quotation_master"));
	$quotation_id = $sq_max['max']+1;

	$sq_quotation = mysql_query("insert into flight_quotation_master ( quotation_id, enquiry_id, login_id, branch_admin_id,financial_year_id, emp_id,customer_name,  email_id, mobile_no,traveling_date,sector_from,sector_to,preffered_airline,class_type,trip_type,total_seats,subtotal,markup_cost,markup_cost_subtotal,service_tax,service_tax_subtotal,taxation_id,quotation_cost,quotation_date,created_at) values ('$quotation_id','$enquiry_id','$login_id', '$branch_admin_id','$financial_year_id', '$emp_id', '$customer_name','$email_id','$mobile_no','$travel_datetime','$sector_from','$sector_to','$preffered_airline','$class_type','$trip_type','$total_seats','$subtotal','$markup_cost','$markup_cost_subtotal','$service_tax','$service_tax_subtotal','$taxation_id','$total_tour_cost','$quotation_date','$created_at')");
 
	if($sq_quotation){
		////////////Enquiry Save///////////
		if($enquiry_id == 0){
			$sq_max_id = mysql_fetch_assoc(mysql_query("select max(enquiry_id) as max from enquiry_master"));
			$enquiry_id1 = $sq_max_id['max']+1;
			$sq_enquiry = mysql_query("insert into enquiry_master (enquiry_id, login_id,branch_admin_id,financial_year_id, enquiry_type,enquiry, name, mobile_no, landline_no, email_id,location, assigned_emp_id, enquiry_specification, enquiry_date, followup_date, reference_id, enquiry_content ) values ('$enquiry_id1', '$login_id', '$branch_admin_id','$financial_year_id', 'Flight Ticket','Strong', '$customer_name', '$mobile_no', '', '$email_id','', '$emp_id','', '$quotation_date', '$quotation_date', '', '$enquiry_content')");
			if($sq_enquiry){
				$sq_quot_update = mysql_query("update flight_quotation_master set enquiry_id='$enquiry_id1' where quotation_id='$quotation_id'");
			}
			$sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from enquiry_master_entries"));
			$entry_id = $sq_max['max'] + 1;
			$sq_followup = mysql_query("insert into enquiry_master_entries(entry_id, enquiry_id, followup_reply,  followup_status,  followup_type, followup_date, followup_stage, created_at) values('$entry_id', '$enquiry_id1', '', 'Active','', '$quotation_date','Strong', '$quotation_date')");
			$sq_entryid = mysql_query("update enquiry_master set entry_id='$entry_id' where enquiry_id='$enquiry_id1'");
		}
		
		 $this->plane_entries_save($quotation_id, $from_city_id_arr, $plane_from_location_arr, $to_city_id_arr, $plane_to_location_arr, $plane_class_arr,$airline_name_arr, $arraval_arr, $dapart_arr);
		echo "Quotation has been successfully saved.";
		exit;
	}
	else{
		echo "error--Quotation not saved!";
		exit;
	}

}

public function plane_entries_save($quotation_id, $from_city_id_arr, $plane_from_location_arr, $to_city_id_arr, $plane_to_location_arr, $plane_class_arr,$airline_name_arr,$dapart_arr, $arraval_arr )
{
	for($i=0; $i<sizeof($plane_from_location_arr); $i++){
         //$arraval_arr = get_datetime_db($arraval_arr);
		$arraval_arr[$i] = date('Y-m-d H:i:s', strtotime($arraval_arr[$i]));
		$dapart_arr[$i] = date('Y-m-d H:i:s', strtotime($dapart_arr[$i]));

		$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from flight_quotation_plane_entries"));
		$id = $sq_max['max']+1;

		$sq_plane = mysql_query("insert into flight_quotation_plane_entries ( id, quotation_id, from_city, from_location, to_city, to_location,airline_name, class, arraval_time, dapart_time) values ( '$id', '$quotation_id', '$from_city_id_arr[$i]', '$plane_from_location_arr[$i]', '$to_city_id_arr[$i]', '$plane_to_location_arr[$i]','$airline_name_arr[$i]', '$plane_class_arr[$i]', '$arraval_arr[$i]', '$dapart_arr[$i]' )");
		if(!$sq_plane){
			echo "error--Plane information not saved!";
			exit;
		}
	}

}

}
?>
 