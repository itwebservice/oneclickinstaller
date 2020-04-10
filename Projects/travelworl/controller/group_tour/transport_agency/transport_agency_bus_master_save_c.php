<?php 
include "../../../model/model.php"; 
include "../../../model/group_tour/transport_agency/transport_agency_bus.php"; 

$bus_name = $_POST["bus_name"];
$bus_capacity = $_POST["bus_capacity"];
$active_flag_arr = $_POST['active_flag_arr'];
$per_day_cost = $_POST['per_day_cost'];

$transport_agency_bus = new transport_agency_bus();
$transport_agency_bus->transport_agency_bus_master_save($bus_name, $bus_capacity,$per_day_cost, $active_flag_arr);
?>