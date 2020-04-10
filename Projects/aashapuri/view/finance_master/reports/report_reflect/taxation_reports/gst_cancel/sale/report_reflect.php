<?php 
include "../../../../../../../model/model.php";
include_once('../sale/sale_generic_functions.php');

$branch_status = $_POST['branch_status'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$taxation_id = $_POST['taxation_id'];
$role = $_POST['role'];
$branch_admin_id = $_POST['branch_admin_id'];
$scgst_total = 0;
$igst_total = 0;
$ugst_total = 0;


$sq_setting = mysql_fetch_assoc(mysql_query("select * from app_settings where setting_id='1'"));
$sq_supply = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_setting[state_id]'"));
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_report" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Service_Name</th>
			<th>SAC/HSN_Code</th>
			<th>Customer_Name</th>
			<th>GSTIN/UIN</th>
			<th>Account_State</th>
			<th>Booking_ID</th>
			<th>Booking_Date</th>
			<th>Type_of_Customer</th>
			<th>Place_of_Supply</th>
			<th>Tax_Type</th>
			<th>Rate</th>
			<th>NET_AMOUNT</th>
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
	//Passport Booking
	$query = "select * from passport_master where 1 ";
	if($from_date !='' && $to_date != ''){
		$from_date = get_date_db($from_date);
		$to_date = get_date_db($to_date);
		$query .= " and created_at between '$from_date' and '$to_date' ";
	}
	if($taxation_id != '0'){
		$query .= " and taxation_id = '$taxation_id'";
	}
	include "../../../../../../../model/app_settings/branchwise_filteration.php";
	$sq_query = mysql_query($query);
    while($row_query = mysql_fetch_assoc($sq_query))
    {
    	//Total count
	 	$sq_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as booking_count from passport_master_entries where passport_id ='$row_query[passport_id]'"));

	 	//Cancelled count
	 	$sq_cancel_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as cancel_count from passport_master_entries where passport_id ='$row_query[passport_id]' and status ='Cancel'"));
	 	if($sq_count['booking_count'] == $sq_cancel_count['cancel_count'])
		{
	    	$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_query[customer_id]'"));
	    	if($sq_cust['type'] == 'Corporate'){
	    		$cust_name = $sq_cust['company_name'];
	    	}else{
	    		$cust_name = $sq_cust['first_name'].' '.$sq_cust['last_name'];
	    	}
	    	$taxable_amount = $row_query['passport_issue_amount'] + $row_query['service_charge'];
	    	$hsn_code = get_service_info('Passport');

	    	$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_cust[state_id]'"));

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
			<td><?= "Passport Booking" ?></td>
			<td><?= $hsn_code ?></td>
			<td><?= $cust_name ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
			<td><?= ($sq_supply['state_name'] == '') ? 'NA' : $sq_supply['state_name'] ?></td>
			<td><?= get_passport_booking_id($row_query['passport_id']) ?></td>
			<td><?= get_date_user($row_query['created_at']) ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= ($sq_state['state_name'] == '') ? 'NA' : $sq_state['state_name'] ?></td>
			<td><?= $row_query['taxation_type'] ?></td>
			<td><?= $row_query['service_tax'] ?></td>
			<td><?= $row_query['passport_total_cost'] ?></td>
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
	    }
		//Visa Booking
		$query = "select * from visa_master where 1 ";
		if($from_date !='' && $to_date != ''){
			$from_date = get_date_db($from_date);
			$to_date = get_date_db($to_date);
			$query .= " and created_at between '$from_date' and '$to_date' ";
		}
		if($taxation_id != '0'){
			$query .= " and taxation_id = '$taxation_id'";
		}
		include "../../../../../../../model/app_settings/branchwise_filteration.php";
		$sq_query = mysql_query($query);
	    while($row_query = mysql_fetch_assoc($sq_query))
	    {
	    	//Total count
		 	$sq_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as booking_count from visa_master_entries where visa_id ='$row_query[visa_id]'"));

		 	//Cancelled count
		 	$sq_cancel_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as cancel_count from visa_master_entries where visa_id ='$row_query[visa_id]' and status ='Cancel'"));
		 	if($sq_count['booking_count'] == $sq_cancel_count['cancel_count'])
			{
		    	$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_query[customer_id]'"));
		    	if($sq_cust['type'] == 'Corporate'){
		    		$cust_name = $sq_cust['company_name'];
		    	}else{
		    		$cust_name = $sq_cust['first_name'].' '.$sq_cust['last_name'];
		    	}
		    	$taxable_amount = $row_query['visa_issue_amount'] + $row_query['service_charge'];
		    	$hsn_code = get_service_info('Visa');

		    	$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_cust[state_id]'"));

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
				<td><?= "Visa Booking" ?></td>
				<td><?= $hsn_code ?></td>
				<td><?= $cust_name ?></td>
				<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
				<td><?= ($sq_supply['state_name'] == '') ? 'NA' : $sq_supply['state_name'] ?></td>
				<td><?= get_visa_booking_id($row_query['visa_id']) ?></td>
				<td><?= get_date_user($row_query['created_at']) ?></td>
				<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
				<td><?= ($sq_state['state_name'] == '') ? 'NA' : $sq_state['state_name'] ?></td>
				<td><?= $row_query['taxation_type'] ?></td>
				<td><?= $row_query['service_tax'] ?></td>
				<td><?= $row_query['visa_total_cost'] ?></td>
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
		} ?>
		<?php 
		//Bus Booking
		$query = "select * from bus_booking_master where 1 ";
		if($from_date !='' && $to_date != ''){
			$from_date = get_date_db($from_date);
			$to_date = get_date_db($to_date);
			$query .= " and created_at between '$from_date' and '$to_date' ";
		}
		if($taxation_id != '0'){
			$query .= " and taxation_id = '$taxation_id'";
		}
		include "../../../../../../../model/app_settings/branchwise_filteration.php";
		$sq_query = mysql_query($query);
	    while($row_query = mysql_fetch_assoc($sq_query))
	    {
	    	//Total count
		 	$sq_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as booking_count from bus_booking_entries where booking_id ='$row_query[booking_id]'"));

		 	//Cancelled count
		 	$sq_cancel_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as cancel_count from bus_booking_entries where booking_id ='$row_query[booking_id]' and status ='Cancel'"));
		 	if($sq_count['booking_count'] == $sq_cancel_count['cancel_count'])
			{
		    	$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_query[customer_id]'"));
		    	if($sq_cust['type'] == 'Corporate'){
		    		$cust_name = $sq_cust['company_name'];
		    	}else{
		    		$cust_name = $sq_cust['first_name'].' '.$sq_cust['last_name'];
		    	}
		    	$taxable_amount = $row_query['basic_cost'] + $row_query['service_charge'];
		    	$hsn_code = get_service_info('Bus');

		    	$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_cust[state_id]'"));

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
				<td><?= "Bus Booking" ?></td>
				<td><?= $hsn_code ?></td>
				<td><?= $cust_name ?></td>
				<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
				<td><?= ($sq_supply['state_name'] == '') ? 'NA' : $sq_supply['state_name'] ?></td>
				<td><?= get_bus_booking_id($row_query['booking_id']) ?></td>
				<td><?= get_date_user($row_query['created_at']) ?></td>
				<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
				<td><?= ($sq_state['state_name'] == '') ? 'NA' : $sq_state['state_name'] ?></td>
				<td><?= $row_query['taxation_type'] ?></td>
				<td><?= $row_query['service_tax'] ?></td>
				<td><?= $row_query['net_total'] ?></td>
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
		} 
		//Excursion Booking
		$query = "select * from excursion_master where 1 ";
		if($from_date !='' && $to_date != ''){
			$from_date = get_date_db($from_date);
			$to_date = get_date_db($to_date);
			$query .= " and created_at between '$from_date' and '$to_date' ";
		}
		if($taxation_id != '0'){
			$query .= " and taxation_id = '$taxation_id'";
		}
		include "../../../../../../../model/app_settings/branchwise_filteration.php";
		$sq_query = mysql_query($query);
	    while($row_query = mysql_fetch_assoc($sq_query))
	    {
	    	//Total count
		 	$sq_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as booking_count from excursion_master_entries where exc_id ='$row_query[exc_id]'"));

		 	//Cancelled count
		 	$sq_cancel_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as cancel_count from excursion_master_entries where exc_id ='$row_query[exc_id]' and status ='Cancel'"));
		 	if($sq_count['booking_count'] == $sq_cancel_count['cancel_count'])
			{
		    	$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_query[customer_id]'"));
		    	if($sq_cust['type'] == 'Corporate'){
		    		$cust_name = $sq_cust['company_name'];
		    	}else{
		    		$cust_name = $sq_cust['first_name'].' '.$sq_cust['last_name'];
		    	}
		    	$taxable_amount = $row_query['exc_issue_amount'] + $row_query['service_charge'];
		    	$hsn_code = get_service_info('Excursion');

		    	$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_cust[state_id]'"));

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
				<td><?= "Excursion Booking" ?></td>
				<td><?= $hsn_code ?></td>
				<td><?= $cust_name ?></td>
				<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
				<td><?= ($sq_supply['state_name'] == '') ? 'NA' : $sq_supply['state_name'] ?></td>
				<td><?= get_exc_booking_id($row_query['exc_id']) ?></td>
				<td><?= get_date_user($row_query['created_at']) ?></td>
				<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
				<td><?= ($sq_state['state_name'] == '') ? 'NA' : $sq_state['state_name'] ?></td>
				<td><?= $row_query['taxation_type'] ?></td>
				<td><?= $row_query['service_tax'] ?></td>
				<td><?= $row_query['exc_total_cost'] ?></td>
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
		} 
		//Miscellaneous Booking
		$query = "select * from miscellaneous_master where 1 ";
		if($from_date !='' && $to_date != ''){
			$from_date = get_date_db($from_date);
			$to_date = get_date_db($to_date);
			$query .= " and created_at between '$from_date' and '$to_date' ";
		}
		if($taxation_id != '0'){
			$query .= " and taxation_id = '$taxation_id'";
		}
		include "../../../../../../../model/app_settings/branchwise_filteration.php";
		$sq_query = mysql_query($query);
	    while($row_query = mysql_fetch_assoc($sq_query))
	    {
	    	//Total count
		 	$sq_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as booking_count from miscellaneous_master_entries where misc_id ='$row_query[misc_id]'"));

		 	//Cancelled count
		 	$sq_cancel_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as cancel_count from miscellaneous_master_entries where misc_id ='$row_query[misc_id]' and status ='Cancel'"));
		 	if($sq_count['booking_count'] == $sq_cancel_count['cancel_count'])
			{
		    	$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_query[customer_id]'"));
		    	if($sq_cust['type'] == 'Corporate'){
		    		$cust_name = $sq_cust['company_name'];
		    	}else{
		    		$cust_name = $sq_cust['first_name'].' '.$sq_cust['last_name'];
		    	}
		    	$taxable_amount = $row_query['misc_issue_amount'] + $row_query['service_charge'];
		    	$hsn_code = get_service_info('Miscellaneous');

		    	$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_cust[state_id]'"));

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
				<td><?= "Miscellaneous Booking" ?></td>
				<td><?= $hsn_code ?></td>
				<td><?= $cust_name ?></td>
				<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
				<td><?= ($sq_supply['state_name'] == '') ? 'NA' : $sq_supply['state_name'] ?></td>
				<td><?= get_misc_booking_id($row_query['misc_id']) ?></td>
				<td><?= get_date_user($row_query['created_at']) ?></td>
				<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
				<td><?= ($sq_state['state_name'] == '') ? 'NA' : $sq_state['state_name'] ?></td>
				<td><?= $row_query['taxation_type'] ?></td>
				<td><?= $row_query['service_tax'] ?></td>
				<td><?= $row_query['misc_total_cost'] ?></td>
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
		} ?>
		<?php 

		//Hotel Booking
		$query = "select * from hotel_booking_master where 1 ";
		if($from_date !='' && $to_date != ''){
			$from_date = get_date_db($from_date);
			$to_date = get_date_db($to_date);
			$query .= " and created_at between '$from_date' and '$to_date' ";
		}
		if($taxation_id != '0'){
			$query .= " and taxation_id = '$taxation_id'";
		}
		include "../../../../../../../model/app_settings/branchwise_filteration.php";
		$sq_query = mysql_query($query);
	    while($row_query = mysql_fetch_assoc($sq_query))
	    {
	    	//Total count
		 	$sq_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as booking_count from hotel_booking_entries where booking_id ='$row_query[booking_id]'"));

		 	//Cancelled count
		 	$sq_cancel_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as cancel_count from hotel_booking_entries where booking_id ='$row_query[booking_id]' and status ='Cancel'"));

		 	if($sq_count['booking_count'] == $sq_cancel_count['cancel_count'])
			{
		    	$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_query[customer_id]'"));
		    	if($sq_cust['type'] == 'Corporate'){
		    		$cust_name = $sq_cust['company_name'];
		    	}else{
		    		$cust_name = $sq_cust['first_name'].' '.$sq_cust['last_name'];
		    	}
		    	$taxable_amount = $row_query['sub_total'] + $row_query['service_charge'];
		    	$hsn_code = get_service_info('Hotel / Accommodation');

		    	$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_cust[state_id]'"));

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
				<td><?= "Hotel Booking" ?></td>
				<td><?= $hsn_code ?></td>
				<td><?= $cust_name ?></td>
				<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
				<td><?= ($sq_supply['state_name'] == '') ? 'NA' : $sq_supply['state_name'] ?></td>
				<td><?= get_hotel_booking_id($row_query['booking_id']) ?></td>
				<td><?= get_date_user($row_query['created_at']) ?></td>
				<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
				<td><?= ($sq_state['state_name'] == '') ? 'NA' : $sq_state['state_name'] ?></td>
				<td><?= $row_query['taxation_type'] ?></td>
				<td><?= $row_query['service_tax'] ?></td>
				<td><?= $row_query['total_fee'] ?></td>
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
		} 
		//Car Rental Booking
		$query = "select * from car_rental_booking where status = 'Cancel' ";
		if($from_date !='' && $to_date != ''){
			$from_date = get_date_db($from_date);
			$to_date = get_date_db($to_date);
			$query .= " and created_at between '$from_date' and '$to_date' ";
		}
		if($taxation_id != '0'){
			$query .= " and taxation_id = '$taxation_id'";
		}
		include "../../../../../../../model/app_settings/branchwise_filteration.php";
		$sq_query = mysql_query($query);
	    while($row_query = mysql_fetch_assoc($sq_query))
	    {
	    	$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_query[customer_id]'"));
	    	if($sq_cust['type'] == 'Corporate'){
	    		$cust_name = $sq_cust['company_name'];
	    	}else{
	    		$cust_name = $sq_cust['first_name'].' '.$sq_cust['last_name'];
	    	}
	    	$taxable_amount = $row_query['actual_cost'] + $row_query['km_total_fee'];
	    	$hsn_code = get_service_info('Car Rental');

	    	$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_cust[state_id]'"));

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
				<td><?= "Car Rental Booking" ?></td>
				<td><?= $hsn_code ?></td>
				<td><?= $cust_name ?></td>
				<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
				<td><?= ($sq_supply['state_name'] == '') ? 'NA' : $sq_supply['state_name'] ?></td>
				<td><?= get_car_rental_booking_id($row_query['booking_id']) ?></td>
				<td><?= get_date_user($row_query['created_at']) ?></td>
				<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
				<td><?= ($sq_state['state_name'] == '') ? 'NA' : $sq_state['state_name'] ?></td>
				<td><?= $row_query['taxation_type'] ?></td>
				<td><?= $row_query['service_tax'] ?></td>
				<td><?= $row_query['total_fees'] ?></td>
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
			<?php  
		    } 
		    //Flight Booking
			$query = "select * from ticket_master where 1 ";
			if($from_date !='' && $to_date != ''){
				$from_date = get_date_db($from_date);
				$to_date = get_date_db($to_date);
				$query .= " and created_at between '$from_date' and '$to_date' ";
			}
			if($taxation_id != '0'){
				$query .= " and taxation_id = '$taxation_id'";
			}
			include "../../../../../../../model/app_settings/branchwise_filteration.php";
			$sq_query = mysql_query($query);
		    while($row_query = mysql_fetch_assoc($sq_query))
		    {
		    	//Total count
			 	$sq_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as booking_count from ticket_master_entries where ticket_id ='$row_query[ticket_id]'"));

			 	//Cancelled count
			 	$sq_cancel_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as cancel_count from ticket_master_entries where ticket_id ='$row_query[ticket_id]' and status ='Cancel'"));
			 	if($sq_count['booking_count'] == $sq_cancel_count['cancel_count'])
				{
			    	$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_query[customer_id]'"));
			    	if($sq_cust['type'] == 'Corporate'){
			    		$cust_name = $sq_cust['company_name'];
			    	}else{
			    		$cust_name = $sq_cust['first_name'].' '.$sq_cust['last_name'];
			    	}
			    	$taxable_amount = $row_query['basic_cost'] + $row_query['basic_cost_markup'] - $row_query['basic_cost_discount']+ $row_query['yq_tax'] + $row_query['yq_tax_markup'] - $row_query['yq_tax_discount'] + $row_query['g1_plus_f2_tax'] + $row_query['service_charge'];
			    	$hsn_code = get_service_info('Flight');

			    	$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_cust[state_id]'"));

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
					<td><?= "Ticket Booking" ?></td>
					<td><?= $hsn_code ?></td>
					<td><?= $cust_name ?></td>
					<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
					<td><?= ($sq_supply['state_name'] == '') ? 'NA' : $sq_supply['state_name'] ?></td>
					<td><?= get_ticket_booking_id($row_query['ticket_id']) ?></td>
					<td><?= get_date_user($row_query['created_at']) ?></td>
					<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
					<td><?= ($sq_state['state_name'] == '') ? 'NA' : $sq_state['state_name'] ?></td>
					<td><?= $row_query['taxation_type'] ?></td>
					<td><?= $row_query['service_tax'] ?></td>
					<td><?= $row_query['ticket_total_cost'] ?></td>
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
			} 
			//Train Booking
			$query = "select * from train_ticket_master where 1 ";
			if($from_date !='' && $to_date != ''){
				$from_date = get_date_db($from_date);
				$to_date = get_date_db($to_date);
				$query .= " and created_at between '$from_date' and '$to_date' ";
			}
			if($taxation_id != '0'){
				$query .= " and taxation_id = '$taxation_id'";
			}
			include "../../../../../../../model/app_settings/branchwise_filteration.php";
			$sq_query = mysql_query($query);
		    while($row_query = mysql_fetch_assoc($sq_query))
		    {
		    	//Total count
			 	$sq_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as booking_count from train_ticket_master_entries where train_ticket_id ='$row_query[train_ticket_id]'"));

			 	//Cancelled count
			 	$sq_cancel_count = mysql_fetch_assoc(mysql_query("select count(entry_id) as cancel_count from train_ticket_master_entries where train_ticket_id ='$row_query[train_ticket_id]' and status ='Cancel'"));
			 	if($sq_count['booking_count'] == $sq_cancel_count['cancel_count'])
				{
			    	$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_query[customer_id]'"));
			    	if($sq_cust['type'] == 'Corporate'){
			    		$cust_name = $sq_cust['company_name'];
			    	}else{
			    		$cust_name = $sq_cust['first_name'].' '.$sq_cust['last_name'];
			    	}
			    	$taxable_amount = $row_query['basic_fair'] + $row_query['service_charge'] + $row_query['delivery_charges'];
			    	$hsn_code = get_service_info('Train');

			    	$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_cust[state_id]'"));

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
					<td><?= "Train Ticket Booking" ?></td>
					<td><?= $hsn_code ?></td>
					<td><?= $cust_name ?></td>
					<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
					<td><?= ($sq_supply['state_name'] == '') ? 'NA' : $sq_supply['state_name'] ?></td>
					<td><?= get_train_ticket_booking_id($row_query['train_ticket_id']) ?></td>
					<td><?= get_date_user($row_query['created_at']) ?></td>
					<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
					<td><?= ($sq_state['state_name'] == '') ? 'NA' : $sq_state['state_name'] ?></td>
					<td><?= $row_query['taxation_type'] ?></td>
					<td><?= $row_query['service_tax'] ?></td>
					<td><?= $row_query['net_total'] ?></td>
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
			} 
			include_once '../sale/get_git_fit_amount.php'; ?>
	</tbody>
	<tfoot class="table-heading-row">
		<tr class="active">
			<th colspan="14" class="info text-right">TOTAL : </th>
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