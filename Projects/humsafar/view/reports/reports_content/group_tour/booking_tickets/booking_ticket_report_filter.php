<?php include "../../../../../model/model.php"; ?>
<div class="panel panel-default panel-body mg_bt_10">
<div class="row"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table trable-hover" id="booking_ticket" style="margin: 20px 0 !important;">
<?php 
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_GET['branch_status'];
$branch_id= $_GET['branch_id_filter'];
$tour_id= $_POST['tour_id'];
$group_id = $_POST['group_id'];
$booking_id = $_POST['booking_id'];

$query = "select * from tourwise_traveler_details where 1";

if(isset($_SESSION['booker_id']))
{
	$booker_id = $_SESSION['booker_id'];
	$query = $query." and emp_id ='$booker_id' ";
} 

if($tour_id!="")
{
	$query = $query." and id='$booking_id' ";
}
	
if($branch_id!=""){

	$query .=" and  branch_admin_id = '$branch_id'";
	}	
if($branch_status=='yes' && $role!='Admin'){
	    $query .=" and  branch_admin_id = '$branch_admin_id'";
	}
if($from_date!="" && $to_date!=""){
			$from_date = date('Y-m-d', strtotime($from_date));
			$to_date = date('Y-m-d', strtotime($to_date));
			$query .= " and form_date between '$from_date' and '$to_date'";
		}

	$query .=" and tour_group_status!='Cancel'";
 
?>
<thead>
	<tr class="table-heading-row">
		<th>S_No.</th>
		<th>Tour_Name</th>
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
		$date = $row['form_date'];
         $yr = explode("-", $date);
         $year =$yr[0];
		$tour_name = mysql_fetch_assoc(mysql_query("select tour_name from tour_master where tour_id='$row[tour_id]'"));

		$sq_tour_group_name = mysql_query("select from_date,to_date from tour_groups where group_id='$row[tour_group_id]'");
		$row_tour_group_name = mysql_fetch_assoc($sq_tour_group_name);
		$tour_group_from = date("d/m/Y", strtotime($row_tour_group_name['from_date']));
		$tour_group_to = date("d/m/Y", strtotime($row_tour_group_name['to_date']));	

	?>
		<tr>
			<td><?php echo $count ?></td>
			<td><?php echo $tour_name['tour_name'] ?></td>
			<td><?php echo $tour_group_from." to ".$tour_group_to ?></td>
			<td><?php echo get_group_booking_id($row['id'],$year) ?></td>
			<td>
				<?php
				if($row['train_upload_ticket'] !="")
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
				if($row['plane_upload_ticket'] != "")
				{
					$newUrl = preg_replace('/(\/+)/','/',$row['plane_upload_ticket']);      
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
				if($row['cruise_upload_ticket'] != "")
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
				<?php }?>
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
$('#booking_ticket').dataTable({
		"pagingType": "full_numbers"
	});

</script>