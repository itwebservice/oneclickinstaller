<?php include "../../../../../model/model.php"; ?>
<div class="panel panel-default panel-body mg_bt_10">
<div class="row"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table trable-hover" id="fit_refund_rep" style="margin: 20px 0 !important;">
<thead>
	<tr class="table-heading-row">
		<th>S_No.</th>
		<th>Date</th>
		<th>Tour_name</th>
		<th>Booking_ID</th>
		<th>Mode</th>
		<th class="success">Total_Refund</th>
	</tr>
	
</thead>
<tbody>
<?php
$booking_id=$_POST['booking_id'];
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_GET['branch_status'];
$branch_id= $_GET['branch_id_filter'];
$bg;

$sq_pending_amount=0;
$sq_cancel_amount=0;
$sq_paid_amount=0;
$Total_payment=0;
$refund_amount = 0;



	$query = "select * from package_refund_traveler_cancelation where 1"; 
	// $booking_id = $_GET['booking_id'];
	if($booking_id!=""){
		$query .= " and booking_id='$booking_id'";
	}
	if($branch_id!=""){

		$query .=" and booking_id in(select booking_id from package_tour_booking_master where branch_admin_id = '$branch_id')";
	}
	if($branch_status=='yes' && $role!='Admin'){
	    $query .=" and booking_id in(select booking_id from package_tour_booking_master where branch_admin_id = '$branch_admin_id')";
	}	
	$date;
	$query .=" and clearance_status!='Cancelled'";
 
	$sq_refund = mysql_query($query);
	
			$status=1;
	while($row_refund = mysql_fetch_assoc($sq_refund)){
		if($row_refund['clearance_status']=="Pending"){ $bg='warning';
		$sq_pending_amount = $sq_pending_amount + $row_refund['total_refund'];
		}

		if($row_refund['clearance_status']==""){ $bg='';
			$sq_paid_amount = $sq_paid_amount + $row_refund['total_refund'];
		}
		$refund_amount += $row_refund['total_refund']; 

		$sq_tour=mysql_fetch_assoc(mysql_query("SELECT * from package_tour_booking_master where booking_id='$row_refund[booking_id]'"));
		$sq_ref = mysql_query("SELECT * from package_refund_traveler_estimate where booking_id='$row_refund[booking_id]'");
		

		($row_refund['refund_date']=='0000-00-00')?$date='':$date=date("d/m/Y",strtotime($row_refund['refund_date']));
		

		?>
		<tr class ='<?= $bg;?>'>
			<td><?= ++$count ?></td>
			<td><?= $date;?></td>
			<td><?= $sq_tour['tour_name']?></td>
			<td><?= get_package_booking_id($row_refund['booking_id']) ?></td>
			<td><?= $row_refund['refund_mode'] ?></td>
			<td class="success"><?= $row_refund['total_refund'] ?></td>			
		</tr>
		<?php		

	}
?>
</tbody>
<tfoot>
	<tr class="active">
		<th colspan="2" class="text-right success">Refund Amount : <?= number_format((($refund_amount=='')?0:$refund_amount), 2); ?></th>
		<th colspan="1" class="text-right warning">Pending Amount : <?= number_format((($sq_pending_amount=='')?0:$sq_pending_amount), 2);?></th>
		<th colspan="1" class="text-right danger">Total Cancel: <?= number_format((($sq_cancel_amount=='')?0:$sq_cancel_amount), 2);?> </th>
		<th colspan="2" class="text-right success">Total Refund : <?= number_format(($refund_amount - $sq_pending_amount), 2); ?></th>
	</tr>
</tfoot>
</table>
</div>	</div> </div>
</div>
<script>
$('#fit_refund_rep').dataTable({
		"pagingType": "full_numbers"
});
</script>
