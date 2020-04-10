<?php include "../../../../../model/model.php"; 
$sale_type = $_POST['sale_type'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$branch_status = $_POST['branch_status'];
$branch_admin_id = $_POST['branch_admin_id'];
$role = $_POST['role'];
?>
 
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_report" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Booking Type</th>
			<th>Customer Name</th>
			<th>Booking ID</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$count = 1;
	$total_amount = 0;
	$query = "SELECT * FROM `ledger_master` WHERE `group_sub_id` = '87'"; 
	$sq_query = mysql_query($query);
	while($row_query = mysql_fetch_assoc($sq_query))
	{
		$f_query = "select * from finance_transaction_master where gl_id='$row_query[ledger_id]' and module_name!='Journal Entry'";
		if($from_date != '' && $to_date != ''){
			$from_date = get_date_db($from_date);
			$to_date = get_date_db($to_date);
			$f_query .= " and payment_date between '$from_date' and '$to_date'";
		}
		if($sale_type != ''){
			$f_query .= " and module_name = '$sale_type'";
		}
		if($branch_status == 'yes'){
			if($role == 'Branch Admin'){
				$f_query .= " and branch_admin_id='$branch_admin_id'";
			}
		}
		$sq_finance = mysql_query($f_query);
		while($row_finance = mysql_fetch_assoc($sq_finance)){
			$total_amount += $row_finance['payment_amount'];
			$customer_name = get_customer_name($row_finance['module_name'],$row_finance['module_entry_id']);
		?>
			<tr>
				<td style="width:20px"><?= $count++ ?></td>
				<td><?= $row_finance['module_name'] ?></td>
				<td><?= $customer_name ?></td>
				<td><?php echo $row_finance['module_entry_id']; ?></td>
				<td class="text-right success"><?= $row_finance['payment_amount'] ?></td>
			</tr>
		<?php } 
	} ?>		 
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4" class="text-right"><b>Total</b></td>
			<td class="text-right success"><b><?= number_format($total_amount,2) ?></b></td>
		</tr>
	</tfoot>
</table>
</div> </div> </div>
<script>
$('#tbl_report').dataTable({
		"pagingType": "full_numbers"
	});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>