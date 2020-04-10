<?php include "../../../../../../model/model.php";
include_once('../itc_report/vendor_generic_functions.php');

$branch_status = $_POST['branch_status'];
$role = $_POST['role'];
$branch_admin_id = $_POST['branch_admin_id'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$taxation_id = $_POST['taxation_id'];

$scgst_total = 0;
$igst_total = 0;
$ugst_total = 0;
$query = "select * from vendor_estimate where status='' ";
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
<table class="table table-bordered" id="tbl_report" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Service_Name</th>
			<th>SAC/HSN_Code</th>
			<th>Supplier_Name</th>
			<th>GSTIN/UIN</th>
			<th>Account_State</th>
			<th>Purchase_ID</th>
			<th>Purchase_Date</th>
			<th>Type_of_Supplies</th>
			<th>Place_of_Supply</th>
			<th>Tax_Type</th>
			<th>Rate</th>
			<th>Taxable_Amount</th>
			<th>IGST_%</th>
			<th>IGST_Amount</th>
			<th>CGST_%</th>
			<th>CGST_Amount</th>
			<th>SGST_%</th>
			<th>SGST_Amount</th>
			<th>UTGST_%</th>
			<th>UTGST_Amount</th>
			<th>Cess%</th>
			<th>Cess_Amount</th>
			<th>ITC_Eligibility</th>
			<th>Reverse_Charge</th>
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

				if($sq_tax_name['tax_type'] == 'IGST'){ $igst_total += $tax_amount; }
				else if($sq_tax_name['tax_type'] == 'SGST+CGST'){ $scgst_total += $tax_amount; }
				else if($sq_tax_name['tax_type'] == 'UGST'){ $ugst_total += $tax_amount; }
				else{}
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= $row_query['estimate_type'] ?></td>
			<td><?= $hsn_code ?></td>
			<td><?= $vendor_name ?></td>
			<td><?= ($vendor_info['service_tax'] == '') ? 'NA' : $vendor_info['service_tax'] ?></td>
			<td><?= ($sq_state['state_name'] == '') ? 'NA' : $sq_state['state_name'] ?></td>
			<td><?= $row_query['estimate_id'] ?></td>
			<td><?= get_date_user($row_query['created_at']) ?></td>
			<td><?= ($vendor_info['service_tax'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= ($sq_supply['state_name'] == '') ? 'NA' : $sq_supply['state_name'] ?></td>
			<td><?= $row_query['taxation_type'] ?></td>
			<td><?= $row_query['service_tax'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?php if($sq_tax_name['tax_type'] == 'IGST'){ echo $tax_per; } else{ echo '0'; } ?></td>
			<td><?php if($sq_tax_name['tax_type'] == 'IGST'){ echo $tax_amount; } else{ echo '0'; } ?></td>
			<td><?php if($sq_tax_name['tax_type'] == 'SGST+CGST'){ echo ($tax_per/2); } else{ echo '0'; } ?></td>
			<td><?php if($sq_tax_name['tax_type'] == 'SGST+CGST'){ echo ($tax_amount/2); } else{ echo '0'; } ?></td>
			<td><?php if($sq_tax_name['tax_type'] == 'SGST+CGST'){ echo ($tax_per/2); } else{ echo '0'; } ?></td>
			<td><?php if($sq_tax_name['tax_type'] == 'SGST+CGST'){ echo ($tax_amount/2); } else{ echo '0'; } ?></td>
			<td><?php if($sq_tax_name['tax_type'] == 'UGST'){ echo $tax_per; } else{ echo '0'; } ?></td>
			<td><?php if($sq_tax_name['tax_type'] == 'UGST'){ echo $tax_amount; } else{ echo '0'; } ?></td>
			<td><?= 0.00 ?></td>
			<td><?= 0.00 ?></td>
			<td></td>
			<td></td>
		</tr>
	<?php } 
	//Expense Booking
	$query = "select * from other_expense_master where 1 ";
			if($from_date !='' && $to_date != ''){
				$from_date = get_date_db($from_date);
				$to_date = get_date_db($to_date);
				$query .= " and receipt_date between '$from_date' and '$to_date' ";
			}
			if($taxation_id != '0'){
				$query .= " and taxation_id = '$taxation_id'";
			}
			$sq_query = mysql_query($query);
				while($row_query = mysql_fetch_assoc($sq_query))
				{
					  $tax_per = 0;
					  $tax_amount = 0;
						$taxable_amount = $row_query['amount'];
						$sq_income_type_info = mysql_fetch_assoc(mysql_query("select * from ledger_master where ledger_id='$row_query[expense_type_id]'"));
					  $sq_customer = mysql_fetch_assoc(mysql_query("select * from other_vendors where vendor_id='$row_query[supplier_id]'"));

						$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_customer[state_id]'"));
					  $sq_sac = get_service_info('Other Expense');

						$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[taxation_id]'"));
						$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
						
						$tax_per = $row_query['service_tax'];
						$tax_amount = $row_query['service_tax_subtotal'];
						if($sq_tax_name['tax_type'] == 'IGST'){ $igst_total += $tax_amount; }
						else if($sq_tax_name['tax_type'] == 'SGST+CGST'){ $scgst_total += $tax_amount; }
						else if($sq_tax_name['tax_type'] == 'UGST'){ $ugst_total += $tax_amount; }
						else{}?>
	
			<tr>
				<td><?= $count++ ?></td>
				<td><?= $sq_income_type_info['ledger_name'] ?></td>
				<td><?= $sq_sac ?></td>
				<td><?= $sq_customer['vendor_name'] ?></td>
				<td><?= ($sq_customer['service_tax_no'] == '') ? 'NA' : $sq_customer['service_tax_no'] ?></td>
				<td><?= ($sq_state['state_name'] == '') ? 'NA' : $sq_state['state_name'] ?></td>
				<td><?= $row_query['expense_id'] ?></td>
				<td><?= get_date_user($row_query['expense_date']) ?></td>
				<td><?= ($sq_customer['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
				<td><?= ($sq_supply['state_name'] == '') ? 'NA' : $sq_supply['state_name'] ?></td>
				<td><?= $row_query['taxation_type'] ?></td>
				<td><?= $row_query['service_tax'] ?></td>
				<td><?= number_format($taxable_amount,2) ?></td>
				<td><?php if($sq_tax_name['tax_type'] == 'IGST'){ echo $tax_per; } else{ echo '0'; } ?></td>
				<td><?php if($sq_tax_name['tax_type'] == 'IGST'){ echo $tax_amount; } else{ echo '0'; } ?></td>
				<td><?php if($sq_tax_name['tax_type'] == 'SGST+CGST'){ echo ($tax_per/2); } else{ echo '0'; } ?></td>
				<td><?php if($sq_tax_name['tax_type'] == 'SGST+CGST'){ echo ($tax_amount/2); } else{ echo '0'; } ?></td>
				<td><?php if($sq_tax_name['tax_type'] == 'SGST+CGST'){ echo ($tax_per/2); } else{ echo '0'; } ?></td>
				<td><?php if($sq_tax_name['tax_type'] == 'SGST+CGST'){ echo ($tax_amount/2); } else{ echo '0'; } ?></td>
				<td><?php if($sq_tax_name['tax_type'] == 'UGST'){ echo $tax_per; } else{ echo '0'; } ?></td>
				<td><?php if($sq_tax_name['tax_type'] == 'UGST'){ echo $tax_amount; } else{ echo '0'; } ?></td>
				<td><?= 0.00 ?></td>
				<td><?= 0.00 ?></td>
				<td></td>
				<td></td>
			</tr>
		<?php } ?>
	

	</tbody>
	<tfoot class="table-heading-row">
		<tr class="active">
			<th colspan="13" class="info text-right">TOTAL : </th>
			<th colspan="2" class="info text-right"><?= 'IGST :'.number_format($igst_total,2) ?></th>
			<th colspan="2" class="info text-right"><?= 'CGST :'.number_format($scgst_total/2,2) ?></th>
			<th colspan="2" class="info text-right"><?= 'SGST :'.number_format($scgst_total/2,2) ?></th>
			<th colspan="2" class="info text-right"><?= 'UGST :'.number_format($ugst_total,2) ?></th>
			<th colspan="4" class="info text-right"></th>
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