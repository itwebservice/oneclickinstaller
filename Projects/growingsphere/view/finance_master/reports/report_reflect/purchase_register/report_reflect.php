<?php include "../../../../../model/model.php"; 
$purchase_type = $_POST['purchase_type'];
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
			<th>Purchase_Type</th>
			<th>Supplier_type</th>
			<th>Supplier_Name</th>
			<th>Purchase_ID</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$count = 1;
	$total_amount = 0;
	$query = "SELECT * FROM `ledger_master` WHERE `group_sub_id` in(select subgroup_id from subgroup_master where group_id='3' and subgroup_id not in('27','32','37','45','49','56','73','79','95','101','108','109','110'))"; 
	$sq_query = mysql_query($query);
	while($row_query = mysql_fetch_assoc($sq_query))
	{
		$f_query = "select * from finance_transaction_master where gl_id='$row_query[ledger_id]' and row_specification = 'purchase' ";
		if($from_date != '' && $to_date != ''){
			$from_date = get_date_db($from_date);
			$to_date = get_date_db($to_date);
			$f_query .= " and payment_date between '$from_date' and '$to_date'";
		}
		if($purchase_type != ''){
			$f_query .= " and module_name = '$purchase_type'";
		}
		if($branch_status == 'yes'){
			if($role == 'Branch Admin'){
				$f_query .= " and branch_admin_id='$branch_admin_id'";
			}
		}
		$sq_finance = mysql_query($f_query);
		while($row_finance = mysql_fetch_assoc($sq_finance)){
			$total_amount += $row_finance['payment_amount'];
            $supplier_info_arr = get_supplier_info($row_finance['module_name'], $row_finance['module_entry_id']);     
            $supplier_name = get_vendor_name_report($row_finance['module_name'],$supplier_info_arr['vendor_type_id']);      
		?>
			<tr>
				<td style="width:20px"><?= $count++ ?></td>
				<td><?= ($supplier_info_arr['estimate_type'] == '') ? 'NA' : $supplier_info_arr['estimate_type'] ?></td>
				<td><?= $row_finance['module_name'] ?></td>
				<td><?= ($supplier_name == '') ? 'NA' : $supplier_name?></td>
				<td><?php echo $row_finance['module_entry_id']; ?></td>
				<td class="text-right success"><?= $row_finance['payment_amount'] ?></td>
			</tr>
		<?php } 
	} ?>		 
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5" class="text-right"><b>Total</b></td>
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