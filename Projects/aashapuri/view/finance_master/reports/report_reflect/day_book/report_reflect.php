<?php include "../../../../../model/model.php";
$today_date = date('Y-m-d');
$from_date = $_POST['from_date'];
$branch_status = $_POST['branch_status'];
$branch_admin_id = $_POST['branch_admin_id'];
$role = $_POST['role'];
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_day_book" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Particulars</th>
			<th>Description</th>
			<th class="danger">Debit</th>
			<th class="success">Credit</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$count = 1; $total_debit_amount = 0; $total_credit_amount = 0;
			$query = "select * from finance_transaction_master where 1 and gl_id != '165' and gl_id != '0'";
			if($from_date != ''){
				$from_date = get_date_db($from_date);
				$query .= " and payment_date='$from_date'";
			}
			else{
				$query .= " and payment_date='$today_date'";
			}
			if($branch_status == 'yes'){
				if($role == 'Branch Admin'){
					$query .= " and branch_admin_id='$branch_admin_id'";
				}
			}
			$sq_query = mysql_query($query);
			while($row_query = mysql_fetch_assoc($sq_query)){
				$debit_amount = 0; $credit_amount = 0;
				$sq_ledger = mysql_fetch_assoc(mysql_query("select * from ledger_master where ledger_id='$row_query[gl_id]'"));
				if($row_query['payment_side'] == 'Debit'){ $debit_amount = $row_query['payment_amount']; }
				else{ $credit_amount = $row_query['payment_amount']; }
				$debit_amount = ($debit_amount == '0') ? '' : $debit_amount;
				$credit_amount = ($credit_amount == '0') ? '' : $credit_amount;
				$total_debit_amount += $debit_amount;
				$total_credit_amount += $credit_amount;
				if($row_query['payment_amount'] != '0'){
			?>
				<tr>
					<td style="width:20px"><?= $count++ ?></td>
					<td style="width:350px"><?= $sq_ledger['ledger_name'] ?></td>
					<td style="width:350px"><?= ($row_query['row_specification']) ?></td>
					<td class="danger text-right"><?= $debit_amount ?></td>
					<td class="success text-right"><?= $credit_amount ?></td>
				</tr>
			<?php } }?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3" class="text-right"><b>Total</b></td>
			<td class="danger text-right"><b><?= number_format($total_debit_amount,2) ?></b></td>
			<td class="success text-right"><b><?= number_format($total_credit_amount,2) ?></b></td>
		</tr>
	</tfoot>
</table>
</div> </div> </div>
<script>
$('#tbl_day_book').dataTable({
		"pagingType": "full_numbers"
	});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>