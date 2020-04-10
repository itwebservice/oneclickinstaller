<?php include "../../../../../model/model.php"; ?>

<div class="panel panel-default panel-body mg_bt_10">
<div class="row"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table trable-hover" id="tbl_group_passenger" style="margin: 20px 0 !important;">
<thead>
<tr class="table-heading-row">
    <th>S_No.</th>
    <th>Name</th>
    <th>M/F</th>
    <th>Birth_Date</th>
    <th>Age</th>
</tr>
</thead>
<tbody>
<?php 
$id = $_POST['id'];
$tour_id = $_POST['tour_id'];
$group_id = $_POST['group_id'];
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_GET['branch_status'];
$count=0;
$cancel_count=0;
$query1 = "select * from travelers_details where 1";
if($id != "")
{
	$query1 .= " and traveler_group_id in(select traveler_group_id from tourwise_traveler_details where id='$id') ";
}	
if($branch_id!=""){

	$query1 .= " and traveler_group_id in (select traveler_group_id from tourwise_traveler_details where branch_admin_id = '$branch_id')";
}
if($branch_status=='yes' && $role=='Branch Admin'){
    $query1 .= " and traveler_group_id in (select traveler_group_id from tourwise_traveler_details where branch_admin_id = '$branch_admin_id')";
}
if($tour_id != ''){
	$query1 .= " and traveler_group_id in(select traveler_group_id from tourwise_traveler_details where tour_id='$tour_id') ";
}
if( $group_id != ''){
	$query1 .= " and traveler_group_id in(select traveler_group_id from tourwise_traveler_details where tour_group_id='$group_id') ";
}

$sq_traveler_det = mysql_query($query1);
while($row_traveler_det = mysql_fetch_assoc($sq_traveler_det))
{
	$count++;
	$sq_entry1 = mysql_fetch_assoc(mysql_query("select * from tourwise_traveler_details where traveler_group_id='$row_traveler_det[traveler_group_id]'"));
	if($row_traveler_det['status']=="Cancel"|| $sq_entry1['tour_group_status']=='Cancel') {	$bg = "danger"; 
	$cancel_count++;
	}	
	else { $bg="#000";	}	
	if($row_traveler_det['birth_date']=="") { $birth_date=""; }
	else { $birth_date=date("d/m/Y",strtotime($row_traveler_det['birth_date'])); }

	?>
	<tr class="<?= $bg ?>">
		<td><?php echo $count; ?></td>
		<td><?php echo $row_traveler_det['first_name']." ".$row_traveler_det['last_name']; ?></td>
		<td><?php echo $row_traveler_det['gender']; ?></td>
		<td><?php echo $birth_date ?></td>
		<td><?php echo $row_traveler_det['age'] ?></td>
	</tr>	
	<?php
}	
?>	
</tbody>
<tfoot>
	<tr class="active"> 
		<th colspan="1" class="text-right"></th>
		<th colspan="4">Total Cancelled Passenger : <?= $cancel_count?></th>
	</tr>
</tfoot>
</table>
</div>	</div> </div>
</div>
<script>
$('#tbl_group_passenger').dataTable({
		"pagingType": "full_numbers"
});
</script>