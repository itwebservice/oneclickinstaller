<?php
include "../../../../model/model.php";
$query = mysql_query("select * from city_master where active_flag = 'Active'");
$final_array = array();
while($cities = mysql_fetch_assoc($query)){
    $airport_query = mysql_query("select * from airport_master where flag = 'Active' and city_id=".$cities['city_id']);
    while($airports = mysql_fetch_assoc($airport_query)){
        $value = $cities['city_name']." - ".$airports['airport_name']. "(".$airports['airport_code'].")";
        $to_be_push = array(
            "value" => $value,
            "label" => $value,
            "city_id" => $cities['city_id']
        );
        array_push($final_array, $to_be_push);
    }
}
echo json_encode($final_array);
?>