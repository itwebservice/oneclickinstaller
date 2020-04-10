<?php
include '../../../../model/model.php';
$vehicle_id = $_POST['vehicle_id'];
$sq_transport = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id = '$vehicle_id'"));
echo $sq_transport['per_day_cost'];
?>