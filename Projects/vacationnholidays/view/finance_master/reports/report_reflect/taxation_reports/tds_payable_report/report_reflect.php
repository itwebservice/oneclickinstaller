<?php include "../../../../../../model/model.php"; 
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$branch_status = $_POST['branch_status'];
$role = $_POST['role'];
$branch_admin_id = $_POST['branch_admin_id'];
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_report_tds_pay" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Purchase_Date</th>
			<th>Supplier_Name</th>
			<th>PAN_Number/TAN_Number</th>
			<th>TDS_deducted_on_amount</th>
			<th>TDS_Deducted</th>
		</tr>
	</thead>
	<tbody>
		 <?php
		 $count = 1;
		 //Hotel
		 $query = "select * from vendor_estimate where status =''";
		 if($from_date != '' && $to_date != ''){
		 	$from_date = get_date_db($from_date);
		 	$to_date = get_date_db($to_date);
		 	$query .= " and created_at between '$from_date' and '$to_date'"; 		
		 }
		 include "../../../../../../model/app_settings/branchwise_filteration.php";
		 $sq_query = mysql_query($query);
		 while($row_query = mysql_fetch_assoc($sq_query))
		 {
			$tds_on_amount = $row_query['basic_cost'] + $row_query['non_recoverable_taxes'] + $row_query['service_charge']+ $row_query['other_charges'];
			$supp_name = get_vendor_name_report($row_query['vendor_type'],$row_query['vendor_type_id']);
			$supp_pan_no = get_vendor_pan_report($row_query['vendor_type'],$row_query['vendor_type_id']);
			if($row_query['tds'] != '0'){			 
			 ?>
				<tr>
					<td><?= $count++ ?></td>
					<td><?= get_date_user($row_query['created_at']) ?></td>
					<td><?= $supp_name ?></td>
					<td><?= ($supp_pan_no == '') ? 'NA' : $supp_pan_no ?></td>
					<td><?= number_format($tds_on_amount,2) ?></td>
					<td ><?= number_format($row_query['tds'],2) ?></td>
				</tr>
			<?php } 
		 }
		 //Other Expense
		 $query = "select * from other_expense_master where 1 ";
		 if($from_date != '' && $to_date != ''){
		 	$from_date = get_date_db($from_date);
		 	$to_date = get_date_db($to_date);
		 	$query .= " and created_at between '$from_date' and '$to_date'"; 		
		 }
		 include "../../../../../../model/app_settings/branchwise_filteration.php";
		 $sq_query = mysql_query($query);
		 while($row_query = mysql_fetch_assoc($sq_query))
		 {		 
		 	$sq_supp = mysql_fetch_assoc(mysql_query("select * from other_vendors where vendor_id='$row_query[supplier_id]'"));
		 	$sq_exp = mysql_fetch_assoc(mysql_query("select * from ledger_master where ledger_id='$row_query[expense_type_id]'"));
		 	if($row_query['tds'] != '0'){
			 ?>
				<tr>
					<td><?= $count++ ?></td>
					<td><?= get_date_user($row_query['created_at']) ?></td>
					<td><?= ($sq_supp['vendor_name'] == '') ? $sq_exp['ledger_name'] : $sq_supp['vendor_name']?></td>
					<td><?= ($sq_supp['pan_no'] == '') ? 'NA' : $sq_supp['pan_no'] ?></td>
					<td><?= number_format($row_query['amount'],2) ?></td>
					<td ><?= number_format($row_query['tds'],2) ?></td>
				</tr>
			<?php }
		 }
		  ?>
	</tbody>
	<tfoot>
		 
	</tfoot>
</table>
</div> </div> </div>
<script>
$('#tbl_report_tds_pay').dataTable({
		"pagingType": "full_numbers"
	});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>