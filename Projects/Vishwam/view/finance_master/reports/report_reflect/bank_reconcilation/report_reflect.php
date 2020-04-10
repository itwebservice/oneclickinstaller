<?php include "../../../../../model/model.php"; ?>
<?php
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];
$filter_date = $_POST['filter_date'];
$bank_id = $_POST['bank_id'];

$query = "SELECT * FROM `bank_reconcl_master` where 1 ";
if($bank_id != ''){
	$query .= " and bank_id ='$bank_id'";
}
if($filter_date != ''){
	$filter_date = get_date_db($filter_date);
	$query .= " and reconcl_date <='$filter_date'";
}
$sq_query = mysql_query($query);
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_report" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Bank_Name</th>
			<th>Reconcl_Date</th>
			<th>Balance_as_per Books</th>
			<th>Cheque_Deposited but_not_Cleared</th>
			<th>Cheque_Issued but not Presented_for_Payment</th>
			<th>Bank_Debits</th>
			<th>Bank_Credits</th>
			<th>View</th>
			<th>Reconciliation_Amount</th>
			<th>Balance_as_per Bank_Books</th>
			<th>Difference_after Reconciliation</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$count = 1;
		while($row_query = mysql_fetch_assoc($sq_query)){
			$sq_bank = mysql_fetch_assoc(mysql_query("select * from bank_master where bank_id='$row_query[bank_id]'"));
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= $sq_bank['bank_name'].'('.$sq_bank['branch_name'].')' ?></td>
			<td><?= get_date_user($row_query['reconcl_date']) ?></td>
			<td><?= $row_query['book_balance'] ?></td>
			<td><?= $row_query['cheque_deposit'] ?></td>
			<td><?= $row_query['cheque_payment'] ?></td>
			<td><?= $row_query['bank_debit_amount'] ?></td>
			<td><?= $row_query['bank_credit_amount'] ?></td>
			<td>
				<button class="btn btn-info btn-sm" onclick="display_modal(<?= $row_query['id'] ?>)" title="Admin Approval"><i class="fa fa-eye"></i></button></td>
			<td><?= $row_query['reconcl_amount'] ?></td>
			<td><?= $row_query['bank_book_balance'] ?></td>
			<td><?= $row_query['diff_amount'] ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div> </div> </div>
<div id="div_view_modal"></div>
<script>
$('#tbl_report').dataTable({
		"pagingType": "full_numbers"
	});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>