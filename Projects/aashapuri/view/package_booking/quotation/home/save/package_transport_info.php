<?php
include_once('../../../../../model/model.php');

$package_id_arr = $_POST['package_id_arr'];
$transport_info_arr =array();

for($i=0; $i<sizeof($package_id_arr); $i++){
	
	$sq_package = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id='$package_id_arr[$i]'"));
	$sq_transport = mysql_query("select * from custom_package_transport where package_id='$package_id_arr[$i]'");

	while($row_transport = mysql_fetch_assoc($sq_transport)){
		$q_transport = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id='$row_transport[vehicle_name]'"));
		$arr1 = array(
			'bus_name' => $q_transport['bus_name'],
			'bus_id' => $row_transport['vehicle_name'],
			'package_name' => $sq_package['package_name'],
			'package_id' => $sq_package['package_id'],
			'transport_cost' => $row_transport['cost']
		);	
		array_push($transport_info_arr, $arr1);
	}


}
echo json_encode($transport_info_arr);
?>