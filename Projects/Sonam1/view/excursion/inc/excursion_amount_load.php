<?php 
include_once('../../../model/model.php');
$service_id = $_POST['service_id'];
$amount_arr = array();
$sq_excursion = mysql_fetch_assoc(mysql_query("select * from itinerary_paid_services where service_id='$service_id'"));
$total_cost = ($sq_excursion['adult_cost'] + $sq_excursion['child_cost']);

$arr = array(
			'total_cost' => $total_cost,
			'adult_cost' => $sq_excursion['adult_cost'],
			'child_cost' => $sq_excursion['child_cost'],
		);
	 array_push($amount_arr, $arr);

echo json_encode($amount_arr);	 

?>