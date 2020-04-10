<?php 
include_once('../../../../../model/model.php');

$hotel_id_arr = $_POST['hotel_id_arr'];
$room_cat_arr = $_POST['room_cat_arr'];
$from_date = $_POST['from_date'];
$hotel_arr = array();
$from_date = date('Y-m-d',strtotime($from_date));

	for($i=0;$i<sizeof($hotel_id_arr);$i++){
		$sq_hotel_cost = mysql_fetch_assoc(mysql_query("select * from hotel_vendor_price_list where (from_date <='$from_date' and to_date>='$from_date') and without_bed_cost='$room_cat_arr[$i]' and pricing_id in ( select pricing_id from  hotel_vendor_price_master where hotel_id ='$hotel_id_arr[$i]')"));

		$arr = array(
			'hotel_cost' => $sq_hotel_cost['double_bed_cost'],
			'extra_bed_cost' => $sq_hotel_cost['with_bed_cost']
		 	);
		array_push($hotel_arr, $arr);
	}
	
echo json_encode($hotel_arr);
exit;
?>