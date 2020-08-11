<?php 
include_once('../../../../../model/model.php');

$transport_id_arr = $_POST['transport_id_arr'];

$transport_info_arr =array();
for($i=0;$i<sizeof($transport_id_arr);$i++){
	$q_transport = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id='$transport_id_arr[$i]'"));
	
		$arr1 = array(
			'transport_cost' => $q_transport['per_day_cost']
		);	
	
	array_push($transport_info_arr, $arr1);
}
echo json_encode($transport_info_arr);
?>