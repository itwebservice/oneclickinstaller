<?php 
include_once('../../../../model/model.php');
$service_id = $_POST['service_id'];
$total_children = $_POST['total_children'];
$total_adult = $_POST['total_adult'];

$sq_excursion = mysql_fetch_assoc(mysql_query("select * from itinerary_paid_services where service_id='$service_id'"));
$total_adult_cost = ($total_adult * $sq_excursion['adult_cost']);
$total_child_cost = ($total_children * $sq_excursion['child_cost']);	
$total_cost = ($total_adult_cost + $total_child_cost);
echo $total_cost;
?>