<?php include "../../../../../model/model.php"; ?>
<div class="panel panel-default panel-body mg_bt_10">
<div class="row"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table trable-hover" id="fit_ticket" style="margin: 20px 0 !important;">
<?php 
$booking_id = $_POST['booking_id'];	
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_GET['branch_status'];
$branch_id= $_GET['branch_id_filter'];

$query = "select * from package_tour_booking_master where 1 ";
if($booking_id!=""){
	$query .= " and booking_id='$booking_id'";	
}
if($branch_id!=""){

	$query .=" and  branch_admin_id = '$branch_id'";
}
if($branch_status=='yes' && $role!='Admin'){
    $query .=" and  branch_admin_id = '$branch_admin_id'";
}
 
?>
<thead>
	<tr class="table-heading-row">
		<th>S_No</th>
		<th>Tour</th>
		<th>Tour_Date</th>
		<th>Booking_ID</th>
		<th>Train_Ticket</th>
		<th>Flight_Ticket</th>
		<th>Cruise_Ticket</th>
	</tr>	
</thead>	
<tbody>
	<?php 

	$count = 0;

	$sq = mysql_query($query);
	while($row = mysql_fetch_assoc($sq))
	{
		$count++;

		$tour_group_from = date("d/m/Y", strtotime($row['tour_from_date']));
		$tour_group_to = date("d/m/Y", strtotime($row['tour_to_date']));	

	?>
		<tr>
			<td><?php echo $count ?></td>
			<td><?php echo $row['tour_name'] ?></td>
			<td><?php echo $tour_group_from." to ".$tour_group_to ?></td>
			<td><?php echo get_package_booking_id($row['booking_id']) ?></td>
			<td>
				<?php
				if($row['train_upload_ticket']!="")
				{
					$newUrl = preg_replace('/(\/+)/','/',$row['train_upload_ticket']);
					$newUrl = str_replace("../","", $newUrl);
					$newUrl = BASE_URL.$newUrl;
				?>
					<a href="<?php echo $newUrl; ?>" class="btn btn-info btn-sm" download><i class="fa fa-download"></i></a>
				<?php
				}	
				else
				{	
				?>
				NA
				<?php }?>			
			</td>
			<td>
				<?php
				if($row['plane_upload_ticket']!="")
				{
					$newUrl = preg_replace('/(\/+)/','/',$row['plane_upload_ticket']);      
					$newUrl = str_replace("../","", $newUrl);  
					$newUrl = BASE_URL.$newUrl;        
				?>
					<a href="<?php echo $newUrl; ?>" class="btn btn-info btn-sm" download><i class="fa fa-download"></i></a>
				<?php	
				}
				else{
				?>
				NA
				<?php }?>
			</td><td>
				<?php
				if($row['cruise_upload_ticket']!="")
				{
					$newUrl = preg_replace('/(\/+)/','/',$row['cruise_upload_ticket']);      
					$newUrl = str_replace("../","", $newUrl);  
					$newUrl = BASE_URL.$newUrl;        
				?>
					<a href="<?php echo $newUrl; ?>" class="btn btn-info btn-sm" download><i class="fa fa-download"></i></a>
				<?php	
				}	
				else
				{	
				?>
				NA
				<?php } ?>
			</td>
		</tr>
	<?php	
	}	

$count++;
	?>
</tbody>	
</table>
</div>	</div> </div>
</div>
<script>
$('#fit_ticket').dataTable({
		"pagingType": "full_numbers"
});
</script>