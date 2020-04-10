<?php
include "../../../../../model/model.php";

$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$branch_status = $_POST['branch_status'];
$branch_admin_id = $_POST['branch_admin_id'];
$role = $_POST['role'];

$query = "select * from bank_cash_book_master where clearance_status!='Pending' and clearance_status!='Cancelled' and payment_type='Cash'";

if($from_date!="" && $to_date!=""){
	$from_date = get_date_db($from_date);
	$to_date = get_date_db($to_date);

	$query .=" and payment_date between '$from_date' and '$to_date'";
}
if($branch_status == 'yes'){
	if($role == 'Branch Admin'){
		$query .= " and branch_admin_id='$branch_admin_id'";
	}
}

?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
	
<table class="table table-bordered" id="cash_book_id" style="margin: 20px 0 !important;">
	 <thead>
	 	<tr class="table-heading-row">
	 		<th>S_No.</th>
	 		<th>For</th>
	 		<th>Booking_ID</th>
	 		<th>Collected_By</th>
	 		<th>Amount</th>
	 		<th>Payment_Date</th>
	 		<th>Particular</th>
	 		<th class="danger">Debit</th>
	 		<th class="success">Credit</th>
	 		<th class="warning">Balance</th>
	 	</tr>
	 </thead>
	 <tbody>
	 <?php 
	 	$closing_bal = 0;
	 	$count = 0;
	 	$sq = mysql_query($query);
	 	while($row = mysql_fetch_assoc($sq)){

	 		if($row['payment_side']=="Credit"){
	 			$credit_amount = $row['payment_amount'];	
	 			$debit_amount = "";
	 			$closing_bal = $closing_bal + $credit_amount;
	 		}
	 		if($row['payment_side']=="Debit"){
	 			$credit_amount = "";	
	 			$debit_amount = $row['payment_amount'];
	 			$closing_bal = $closing_bal - $debit_amount;
	 		}
	 		if($row['payment_amount'] != 0){
	 		$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row[emp_id]'"));
	 		$emp_name = ($row['emp_id'] != 0) ? $sq_emp['first_name'].' '.$sq_emp['last_name']: 'Admin';
	 		?>
	 		<tr>
	 			<td><?= ++$count ?></td>
	 			<td><?= $row['module_name'] ?></td>
	 			<td><?=  $row['module_entry_id'] ?></td>
				<td><?= $emp_name ?></td>
	 			<td><?= $row['payment_amount'] ?></td>
	 			<td><?= get_date_user($row['payment_date']) ?></td>
	 			<td><?= $row['particular'] ?></td>
	 			<td class="danger"><?= $debit_amount ?></td>
	 			<td class="success"><?= $credit_amount ?></td>
	 			<td class="warning"><?= number_format($closing_bal, 2); ?></td>
	 		</tr>
			 <?php
			 }
	 	}
	 ?>
	 </tbody>
	 <tfoot>
		 <tr class="active">
		 	  <th class="text-right" colspan="9">Closing Balance</th>		
		 	  <th><?= number_format($closing_bal, 2); ?></th>		
		 </tr>
	 </tfoot>
</table>

</div> </div> </div>

<script type="text/javascript">
	$('#cash_book_id').dataTable({
		"pagingType": "full_numbers"
	});
</script>