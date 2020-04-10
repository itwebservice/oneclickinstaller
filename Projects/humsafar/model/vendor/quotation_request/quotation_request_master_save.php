<?php
$flag = true;
class quotation_request_master_save{
public function quotation_request_save()
{	
	$enquiry_id = $_POST['enquiry_id'];
	$quotation_for = $_POST['quotation_for'];
	$city_name = $_POST['city_name'];
	$city_name=implode(',' , $city_name);
	$city_id_arr = $_POST['city_id_arr'];
	$city_id_arr = implode(',',$city_id_arr);
	$tour_type = $_POST['tour_type'];
	$quotation_date = $_POST['quotation_date'];
	$airport_pickup = $_POST['airport_pickup'];
	$cab_type = $_POST['cab_type'];
	$transfer_type = $_POST['transfer_type'];
	$enquiry_specification = $_POST['enquiry_specification'];
	$vehicle_name = $_POST['vehicle_name'];
	$vehicle_type = $_POST['vehicle_type'];
	$branch_admin_id = $_POST['branch_admin_id'];
	$emp_id = $_POST['emp_id'];

	$dynamic_fields = $_POST['dynamic_fields'];
	$dynamic_tbl = $_POST['dynamic_tbl'];
	$excursion_specification = $_POST['excursion_specification'];

	$dynamic_fields = json_encode($dynamic_fields);
	$dynamic_tbl = json_encode($dynamic_tbl);

	if($quotation_for=="Hotel"){
		$hotel_entries = $dynamic_tbl;
		$dmc_entries = "";
		$transport_entries = "";
	}
	else if($quotation_for=="DMC"){
		$hotel_entries = "";
		$dmc_entries = $dynamic_tbl;
		$transport_entries = "";
	}
	else if($quotation_for=="Transport"){
		$hotel_entries = "";
		$dmc_entries = "";
		$transport_entries = $dynamic_tbl;
	}

	$quotation_date = get_date_db($quotation_date);

	$created_at = date('Y-m-d H:i:s');

	begin_t();

	$sq_max = mysql_fetch_assoc(mysql_query("select max(request_id) as max from vendor_request_master"));
	$request_id = $sq_max['max']+1;
	$enquiry_specification = addslashes($enquiry_specification);
	$excursion_specification = addslashes($excursion_specification);
	$sq_request = mysql_query("insert into vendor_request_master(request_id, enquiry_id, emp_id, quotation_for,city_id,vendor_city_id, branch_admin_id, tour_type, quotation_date, airport_pickup, cab_type, transfer_type, enquiry_specification, dynamic_fields, hotel_entries, dmc_entries, transport_entries, excursion_specification, created_at) values ('$request_id', '$enquiry_id', '$emp_id', '$quotation_for','$city_name','$city_id_arr', '$branch_admin_id', '$tour_type', '$quotation_date', '$airport_pickup', '$cab_type', '$transfer_type', '$enquiry_specification', '$dynamic_fields', '$hotel_entries', '$dmc_entries', '$transport_entries', '$excursion_specification', '$created_at')");
	if($sq_request){
		if($GLOBALS['flag']){
			commit_t();
			$this->email_send($request_id,$enquiry_id);
			echo "Request has been successfully send.";
			exit;
		}
		else{
			rollback_t();
		}
	}
	else{
		echo "error--Sorry, Quotation Request not sent!";
		rollback_t();		
		exit;
	}

}

public function email_send($request_id,$enquiry_id)
{	
	$sq_request = mysql_fetch_assoc(mysql_query("select * from vendor_request_master where request_id='$request_id'"));

	$quotation_for = $sq_request['quotation_for'];
	$date = $sq_request['created_at'];
    $yr = explode("-", $date);
    $year = $yr[0];

	if($quotation_for=="Hotel"){
			$to_arr = array();
			$vendor_name_arr = array();
			$vendor_address_arr = array();
			$contact_person_arr = array();
			$supplier_id_arr = array();

			$sq_hotel = mysql_query("select * from hotel_master where hotel_id in($sq_request[city_id])");

			while($row_dmc = mysql_fetch_assoc($sq_hotel)){
				array_push($to_arr,$row_dmc['email_id']);
				array_push($vendor_name_arr,$row_dmc['hotel_name']);
				array_push($vendor_address_arr,$row_dmc['hotel_address']);
				array_push($contact_person_arr,$row_dmc['contact_person_name']);
				array_push($supplier_id_arr,$row_dmc['hotel_id']);
			}
		$link = 'hotel_quotation_reply.php';	 
	    $this->quotation_mail($sq_request, $quotation_for, $to_arr, $vendor_name_arr, $vendor_address_arr, $contact_person_arr, $supplier_id_arr, $link, $request_id,$enquiry_id,$year);
	 }

	if($quotation_for=="DMC"){
		$to_arr = array();
		$vendor_name_arr = array();
		$vendor_address_arr = array();
		$contact_person_arr = array();
		$supplier_id_arr = array();
		$sq_dmc = mysql_query("select * from dmc_master where dmc_id in($sq_request[city_id])");
		while($row_dmc = mysql_fetch_assoc($sq_dmc)){
			array_push($to_arr,$row_dmc['email_id']);
			array_push($vendor_name_arr,$row_dmc['company_name']);
			array_push($vendor_address_arr,$row_dmc['dmc_address']);
			array_push($contact_person_arr,$row_dmc['contact_person_name']);
			array_push($supplier_id_arr,$row_dmc['dmc_id']);

		}
		$link = 'dmc_quotation_reply.php';		 
		$this->quotation_mail($sq_request, $quotation_for, $to_arr, $vendor_name_arr, $vendor_address_arr, $contact_person_arr, $supplier_id_arr, $link, $request_id,$enquiry_id,$year);
	}

	if($quotation_for=="Transport"){
		 	$supplier_id_arr = array();
			$to_arr = array();
			$vendor_name_arr = array();
			$vendor_address_arr = array();
			$contact_person_arr = array();
			
			$sq_dmc1 = mysql_query("select * from transport_agency_master where transport_agency_id in($sq_request[city_id])");
			while($row_dmc1 = mysql_fetch_assoc($sq_dmc1)){
				array_push($to_arr,$row_dmc1['email_id']);
				array_push($vendor_name_arr,$row_dmc1['transport_agency_name']);
				array_push($vendor_address_arr,$row_dmc1['transport_agency_address']);
				array_push($contact_person_arr,$row_dmc1['contact_person_name']);
				array_push($supplier_id_arr,$row_dmc1['transport_agency_id']);
			}
			$link = 'transport_quotation_reply.php';	
			$this->quotation_mail($sq_request, $quotation_for, $to_arr, $vendor_name_arr, $vendor_address_arr, $contact_person_arr, $supplier_id_arr, $link, $request_id,$enquiry_id,$year);	
        }
}
public function quotation_mail($sq_request, $quotation_for, $to, $vendor_name, $vendor_address, $contact_person_name, $supplier_id_arr, $link, $request_id,$enquiry_id,$year)
{
	global $app_name, $app_address;
	$sq_request1 = mysql_fetch_assoc(mysql_query("select * from vendor_request_master where request_id='$request_id'"));
	$quotation_for = $sq_request1['quotation_for'];
	$booker_id=$sq_request1['emp_id']; 
	$row=mysql_fetch_assoc(mysql_query("select emp_id,first_name, last_name from emp_master where emp_id='$booker_id'"));
	$booker_id = $row['emp_id'];
	$first_name=$row['first_name'];
	$last_name=$row['last_name'];
	$booker_name = $first_name." ".$last_name;
	$booker_name  = ($booker_id == 0)?'Admin':$booker_name;
	//////////////////////
	$content_e='';

	$request_id = base64_encode($request_id);

    $count = 0;
	
    for($i=0;$i<sizeof($to);$i++)
	{		
		$supplier_id = base64_encode($supplier_id_arr[$i]);
	$content = '

	<tr>
		<td>
			<table style="width:100%;" cellpadding=0 cellspacing=0>
				<tr>
					<td>
						<p>
						From,<br>
						'.$app_address.'<br>
						Quotation requested By :'.$booker_name.'
						</p>
					</td>
				</tr>		
				<tr>
					<td>
						<p>
						To, <br>
						Sales/Reservation Manager,<br>
						'.$quotation_for.' Name: '.$vendor_name[$i].'<br>
						Address: '.$vendor_address[$i].'
						</p>
					</td>
				</tr>	 
				<tr>
					<td colspan="2" style="padding-top:20px">
						<p><a style="font-weight:500;font-size:14px;display:block;color:#ffffff;background:#009898;text-decoration:none;padding:5px 10px;border-radius:25px;width:95px;text-align:center" href="'.BASE_URL.'view/vendor/quotation_request/reply/'.$link.'?request='.$request_id.'&supplier='.$supplier_id.'&enquiry_id='.$enquiry_id.'" target="_blank">Quick Reply</a></p>
					</td>
				</tr>	 

			</table>
		</td>
	</tr>

	';
	$subject = 'New quotation request : (Enquiry ID : '.get_enquiry_id($enquiry_id,$year).' )';
	global $model;
	$model->app_email_send('23',$to[$i], $content,$subject);
 }
}
}

?>