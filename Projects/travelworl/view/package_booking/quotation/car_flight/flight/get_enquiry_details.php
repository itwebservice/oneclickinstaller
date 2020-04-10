<?php
include "../../../../../model/model.php";

$enquiry_id = $_POST['enquiry_id'];
$sq_enq = mysql_fetch_assoc(mysql_query("select * from enquiry_master where enquiry_id='$enquiry_id'"));

$enquiry_content = $sq_enq['enquiry_content'];
$enquiry_content_arr1 = json_decode($enquiry_content, true);	
foreach($enquiry_content_arr1 as $enquiry_content_arr2){
	if($enquiry_content_arr2['name']=="travel_datetime"){ $sq_enq['travel_datetime'] = $enquiry_content_arr2['value']; }
	if($enquiry_content_arr2['name']=="sector_from"){ $sq_enq['sector_from'] = $enquiry_content_arr2['value']; }
	if($enquiry_content_arr2['name']=="sector_to"){ $sq_enq['sector_to'] = $enquiry_content_arr2['value']; }
	if($enquiry_content_arr2['name']=="preffered_airline"){ $sq_enq['preffered_airline'] = $enquiry_content_arr2['value']; }
	if($enquiry_content_arr2['name']=="class_type"){ $sq_enq['class_type'] = $enquiry_content_arr2['value']; }
	if($enquiry_content_arr2['name']=="trip_type"){ $sq_enq['trip_type'] = $enquiry_content_arr2['value']; }
	if($enquiry_content_arr2['name']=="total_seats"){ $sq_enq['total_seats'] = $enquiry_content_arr2['value']; }
}
echo json_encode($sq_enq);
exit;
?>