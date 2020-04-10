<?php include "../../../../../model/model.php"; ?>
<div class="panel panel-default panel-body mg_bt_10">
<div class="row"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table trable-hover" id="gruop_refund_rep" style="margin: 20px 0 !important;">
<thead>
<tr class="table-heading-row">
    <th>S_No.</th>
    <th>Tour_Name</th>
    <th>Tour_Date</th>
    <th>Booking_ID</th>
    <th>Payment_Date</th>
    <th>Mode</th>
    <th>Pay_For</th>
    <th class="info">Total_sale</th>
    <th class="success">Paid_Amount</th>
    <th class="warning">Balance</th>
</tr>
</thead>
<tbody>
<?php 
$tour_id = $_POST['tour_id'];
$group_id = $_POST['group_id'];
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_GET['branch_status'];
$id = $_POST['id'];
$count=0;
$sq_pending_amount=0;
$sq_cancel_amount=0;
$sq_paid_amount=0;
$Total_payment=0;
$total = 0;
$query1 = "select * from payment_master where 1";

if($tour_id != ''){
	$query1 .= " and tourwise_traveler_id in(select id from tourwise_traveler_details where tour_id='$tour_id') ";
}
if( $group_id != ''){
	$query1 .= " and tourwise_traveler_id in(select traveler_group_id from tourwise_traveler_details where tour_group_id='$group_id') ";
}

if($branch_id!=""){

	$query1 .= " and tourwise_traveler_id in (select id from tourwise_traveler_details where branch_admin_id = '$branch_id')";
}
if($branch_status=='yes' && $role=='Branch Admin'){
    $query1 .= " and branch_admin_id = '$branch_admin_id'";
}
 
$sq_payment_det = mysql_query($query1);
$bg;
while($row_payment_det = mysql_fetch_assoc($sq_payment_det))
{

	$total += $row_payment_det['amount']; 
	$sq_tourwise_details = mysql_fetch_assoc(mysql_query("select * from tourwise_traveler_details where id='$row_payment_det[tourwise_traveler_id]'"));
	$date = $sq_tourwise_details['from_date'];
         $yr = explode("-", $date);
         $year =$yr[0];
	$sq_tour_det = mysql_fetch_assoc(mysql_query("select tour_name from tour_master where tour_id='$sq_tourwise_details[tour_id]'"));
	$sq_tour_group_det = mysql_fetch_assoc(mysql_query("select from_date, to_date from tour_groups where group_id='$sq_tourwise_details[tour_group_id]'"));
	$tour_group = date("d-m-Y", strtotime($sq_tour_group_det['from_date']))." to ".date("d/m/Y", strtotime($sq_tour_group_det['to_date']));

	$count++;
	if($row_payment_det['clearance_status']=="Pending"){ $bg='warning';
		$sq_pending_amount = $sq_pending_amount + $row_payment_det['amount'];
	}

	if($row_payment_det['clearance_status']=="Cancelled"){ $bg='danger';
		$sq_cancel_amount = $sq_cancel_amount + $row_payment_det['amount'];
	}

	if($row_payment_det['clearance_status']=="Cleared"){ $bg='success';
		$sq_paid_amount = $sq_paid_amount + $row_payment_det['amount'];
	}

	if($row_payment_det['clearance_status']==""){ $bg='';
		$sq_paid_amount = $sq_paid_amount + $row_payment_det['amount'];
	}

?>
	<tr class="<?= $bg?>">
		<td><?php echo $count; ?></td>
		<td><?php echo $sq_tour_det['tour_name'] ?></td>
		<td><?php echo $tour_group ?></td>
		<td><?php echo get_group_booking_id($row_payment_det['tourwise_traveler_id'],$year) ?></td>
		<td><?php echo date("d/m/Y", strtotime($row_payment_det['date']));  ?></td>
		<td><?php echo $row_payment_det['payment_mode']; ?></td>		
		<td><?php echo $row_payment_det['payment_for'] ?></td>
		<td class="info"><?php  if($row_payment_det['payment_for']=='Tour')
		{
			echo ($sq_tourwise_details['total_travel_expense']=="") ? number_format(0,2) : $sq_tourwise_details['total_travel_expense'];
		}
		if($row_payment_det['payment_for']=='Travelling'){
			echo $sq_tourwise_details['total_tour_fee'];
		}?></td>
		<td class="success"><?php echo ($row_payment_det['amount']=="") ? number_format(0,2) : $row_payment_det['amount']; ?></td>

		<td class="warning"><?php if($row_payment_det['payment_for']=='Tour')
		{
			echo ($sq_tourwise_details['total_travel_expense']- $row_payment_det['amount']);
		}
		if($row_payment_det['payment_for']=='Travelling'){
			echo number_format(($sq_tourwise_details['total_tour_fee']-$row_payment_det['amount']), 2);
		}?></td>
	</tr>	
<?php	
}

?>	
</tbody>

<tfoot>
	<tr class="active">
		<th colspan="3" class="text-right info">Paid Amount : <?=  number_format($total, 2)?></th>

		<th colspan="2" class="text-right warning">Pending Amount : <?= number_format($sq_pending_amount, 2)?></th>

		<th colspan="2" class="text-right danger">Cancel Amount : <?= number_format($sq_cancel_amount, 2)?></th>
		<?php $Total_payment = ($total-$sq_pending_amount - $sq_cancel_amount); ?>
		<th colspan="3" class="text-right success"> Total Paid : <?= number_format($Total_payment, 2); ?></th>
		 
	</tr>
</tfoot>	

</table>
</div>	</div> </div>
</div>
<script>
$('#gruop_refund_rep').dataTable({
		"pagingType": "full_numbers"
});
</script>