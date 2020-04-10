<?php include "../../../model/model.php";
$city_id = $_GET['city_id'];?>
<option value="">Select Excursion</option>
<?php
$sq_exc = mysql_query("select service_id, service_name from itinerary_paid_services where city_id='$city_id' and active_flag='Active'");
while($row_exc = mysql_fetch_assoc($sq_exc)){
?>
	<option value="<?php echo $row_exc['service_id'] ?>"><?php echo $row_exc['service_name']; ?></option>
<?php } ?>