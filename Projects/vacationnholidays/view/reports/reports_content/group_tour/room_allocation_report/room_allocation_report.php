<?php include "../../../../../model/model.php"; ?>
<div class="panel panel-default panel-body mg_bt_10">
<div class="row"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table trable-hover" id="group_room_allocation" style="margin: 20px 0 !important;">
<thead>
<tr class="table-heading-row">
    <th>Sr_No.</th>
    <th>Tour_Name</th>
    <th>Tour_Date</th>    
    <th>Booking_ID</th>
    <th>Seats</th>
    <th>Double_Bed_Room</th>
    <th>Extra_Bed</th>
    <th>On_Floor</th>
</tr>
</thead>
<tbody>
<?php 
$tour_id= $_POST['tour_id'];
$group_id= $_POST['group_id'];
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_GET['branch_status'];
$count=0;

$query = "select * from tourwise_traveler_details where 1";

if($tour_id!="")
{
	$query .= " and tour_id = '$tour_id'";
}
if($group_id!="")
{
	$query .= " and tour_group_id = '$group_id'";
}
if($branch_id!=""){

	$query .= " and branch_admin_id = '$branch_id'";
}
if($branch_status=='yes' && $role=='Branch Admin'){
    $query .= " and  branch_admin_id = '$branch_admin_id'";
}
 
$sq_tourwise_det = mysql_query($query);
while($row_tourwise_det = mysql_fetch_assoc($sq_tourwise_det))
{
	$bg="";
		if($row_tourwise_det['tour_group_status']=="Cancel") 	{

			$bg="danger";

		}

		else  {

			$bg="#fff";

		}

	$count++;
	$date = $row_tourwise_det['form_date'];
         $yr = explode("-", $date);
         $year =$yr[0];
	$sq_total_member_count = mysql_num_rows(mysql_query("select traveler_id from travelers_details where traveler_group_id='$row_tourwise_det[traveler_group_id]' and status!='Cancel'"));

	$tour_name1 = mysql_fetch_assoc(mysql_query("select tour_name from tour_master where tour_id= '$row_tourwise_det[tour_id]'"));
	$tour_name = $tour_name1['tour_name'];
	$tour_group1 = mysql_fetch_assoc(mysql_query("select from_date, to_date from tour_groups where group_id= '$row_tourwise_det[tour_group_id]'"));
	$tour_group = date("d/m/Y", strtotime($tour_group1['from_date']))." to ".date("d/m/Y", strtotime($tour_group1['to_date']));

	$sq_adjust_with = mysql_fetch_assoc(mysql_query("select first_name, last_name from travelers_details where traveler_id='$row_tourwise_det[s_adjust_with]'"));
	$adjust_with = $sq_adjust_with['first_name']." ".$sq_adjust_with['last_name'];
?>
	<tr class="<?= $bg ?>">
		<td><?php echo $count; ?></td>
		<td><?php echo $tour_name; ?></td>
		<td><?php echo $tour_group; ?></td>
		<td><?php echo get_group_booking_id($row_tourwise_det['id'],$year); ?></td>
		<td><?php echo $sq_total_member_count; ?></td>
		<td><?php echo $row_tourwise_det['s_double_bed_room']; ?></td>
		<td><?php echo $row_tourwise_det['s_extra_bed']; ?></td>
		<td><?php echo $row_tourwise_det['s_on_floor']; ?></td>
	</tr>	
<?php	
}

?>	
</tbody>
</table>
</div>	</div> </div>
</div>
<script>
$('#group_room_allocation').dataTable({
		"pagingType": "full_numbers"
});
</script>