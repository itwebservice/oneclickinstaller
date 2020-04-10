<?php include "../../../../../../model/model.php"; 
include_once('../itc_report/vendor_generic_functions.php');
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$branch_status = $_POST['branch_status'];
$role = $_POST['role'];
$branch_admin_id = $_POST['branch_admin_id'];
$taxation_id = $_POST['taxation_id'];

$query = "select * from vendor_estimate where status ='' ";
if($from_date !='' && $to_date != ''){
	$from_date = get_date_db($from_date);
	$to_date = get_date_db($to_date);
	$query .= " and created_at between '$from_date' and '$to_date' ";
}
if($taxation_id != '0'){
	$query .= " and taxation_id = '$taxation_id'";
}
include "../../../../../../model/app_settings/branchwise_filteration.php";
$sq_setting = mysql_fetch_assoc(mysql_query("select * from app_settings where setting_id='1'"));
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_report_vat_rec" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Service_name</th>
			<th>Supplier_Name</th>
			<th>Purchase_ID</th>
			<th>Purchase_Date</th>
			<th>VAT Number</th>
			<th>Type_of_Supplies</th>
			<th>Rate</th>
			<th>Taxable Amount</th>
			<th>VAT Amount</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	$count = 1;
	$sq_query = mysql_query($query);
	    while($row_query = mysql_fetch_assoc($sq_query))
	    {
	    	$taxable_amount = $row_query['basic_cost'] + $row_query['non_recoverable_taxes'] + $row_query['service_charge'] + $row_query['other_charges'];
	    	$vendor_name = get_vendor_name($row_query['vendor_type'],$row_query['vendor_type_id']);
	    	$vendor_info = get_vendor_info($row_query['vendor_type'], $row_query['vendor_type_id']);
	    	$hsn_code = get_service_info($row_query['estimate_type']);

	    	$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$vendor_info[state_id]'"));
	    	$sq_supply = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_setting[state_id]'"));

	    	$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[taxation_id]'"));
	    	$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
	    	
    		$tax_per = $row_query['service_tax'];
    		$tax_amount = $row_query['service_tax_subtotal'];
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= $row_query['estimate_type'] ?></td>
			<td><?= $vendor_name ?></td>
			<td><?= $row_query['estimate_id'] ?></td>
			<td><?= get_date_user($row_query['created_at']) ?></td>
			<td><?= ($vendor_info['service_tax'] == '') ? 'NA' : $vendor_info['service_tax'] ?></td>
			<td><?= ($vendor_info['service_tax'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= $row_query['service_tax'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?= $tax_amount ?></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
</div> </div> </div>
<script>
$('#tbl_report_vat_rec').dataTable({
		"pagingType": "full_numbers"
	});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>