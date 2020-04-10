<?php 
include_once('../../../model/model.php');
$city_id = $_POST['city_id'];
?>

<?php
$sq_excursion = mysql_query("select * from itinerary_paid_services where city_id='$city_id' and active_flag!='Inactive'");
?>
<option value="">Select Excursion</option>
<?php
while($row_excursion = mysql_fetch_assoc($sq_excursion))
{
?>
	<option value="<?php echo $row_excursion['service_id'] ?>"><?php echo $row_excursion['service_name'] ?></option>
<?php	
}
?>