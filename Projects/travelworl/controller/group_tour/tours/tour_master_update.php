<?php 
include "../../../model/model.php"; 
include "../../../model/group_tour/tours_master.php"; 

$tour_id = $_POST['tour_id'];
$tour_type = $_POST['tour_type'];
$tour_name = $_POST['tour_name'];
$adult_cost = $_POST['adult_cost'];
$children_cost = $_POST['children_cost'];
$infant_cost = $_POST['infant_cost'];
$with_bed_cost = $_POST['with_bed_cost'];
$active_flag = $_POST['active_flag'];
$visa_country_name =$_POST['visa_country_name'];
$company_name = $_POST['company_name'];
$inclusions = $_POST['inclusions'];
$exclusions = $_POST['exclusions'];

$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$capacity = $_POST['capacity'];

$train_from_location_arr = $_POST['train_from_location_arr'];
$train_to_location_arr = $_POST['train_to_location_arr'];
$train_class_arr = $_POST['train_class_arr'];
$train_arrival_date_arr = $_POST['train_arrival_date_arr'];
$train_departure_date_arr = $_POST['train_departure_date_arr'];
$train_id_arr = $_POST['train_id_arr'];

$from_city_id_arr = $_POST['from_city_id_arr'];
$to_city_id_arr = $_POST['to_city_id_arr'];
$plane_from_location_arr = $_POST['plane_from_location_arr'];
$plane_to_location_arr = $_POST['plane_to_location_arr'];
$airline_name_arr = $_POST['airline_name_arr'];
$plane_class_arr = $_POST['plane_class_arr'];
$arraval_arr = $_POST['arraval_arr'];
$dapart_arr = $_POST['dapart_arr'];
$plane_id_arr = $_POST['plane_id_arr'];

$day_program_arr = $_POST['day_program_arr'];
$special_attaraction_arr = $_POST['special_attaraction_arr'];
$overnight_stay_arr = $_POST['overnight_stay_arr'];
$meal_plan_arr  = $_POST['meal_plan_arr'];
$entry_id_arr  = $_POST['entry_id_arr'];

$cruise_departure_date_arr = $_POST['cruise_departure_date_arr'];
$cruise_arrival_date_arr = $_POST['cruise_arrival_date_arr'];
$route_arr = $_POST['route_arr'];
$cabin_arr = $_POST['cabin_arr'];
$c_entry_id_arr  = $_POST['c_entry_id_arr'];

$tour_group_id = $_POST['tour_group_id'];

$tours_master = new tours_master();
$tours_master->tour_master_update($tour_id,$tour_type, $tour_name, $adult_cost, $children_cost, $infant_cost, $with_bed_cost,$visa_country_name,$company_name, $from_date, $to_date, $capacity,$tour_group_id,$active_flag,$train_from_location_arr,$train_to_location_arr,$train_class_arr,$train_arrival_date_arr,$train_departure_date_arr, $train_id_arr, $from_city_id_arr, $to_city_id_arr,$plane_from_location_arr,$plane_to_location_arr,$airline_name_arr,$plane_class_arr,$arraval_arr, $dapart_arr, $plane_id_arr, $day_program_arr, $special_attaraction_arr, $overnight_stay_arr, $meal_plan_arr, $entry_id_arr, $cruise_departure_date_arr, $cruise_arrival_date_arr,$route_arr,$cabin_arr,$c_entry_id_arr,$inclusions,$exclusions);

?>