<?php
include "../../../../model/model.php";
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];
$customer_id = $_POST['customer_id'];
$booking_id = $_POST['booking_id'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$cust_type = $_POST['cust_type'];
$company_name = $_POST['company_name'];
$booker_id = $_POST['booker_id'];
$branch_id = $_POST['branch_id'];

$query = "select * from hotel_booking_master where 1 ";
if($customer_id!=""){
	$query .=" and customer_id='$customer_id'";
}
if($booking_id!=""){
	$query .=" and booking_id='$booking_id'";
}
if($from_date!="" && $to_date!=""){
	$from_date = date('Y-m-d', strtotime($from_date));
	$to_date = date('Y-m-d', strtotime($to_date));
	$query .= " and created_at between '$from_date' and '$to_date'";
}
if($cust_type != ""){
	$query .= " and customer_id in (select customer_id from customer_master where type = '$cust_type')";
}
if($company_name != ""){
	$query .= " and customer_id in (select customer_id from customer_master where company_name = '$company_name')";
}
if($booker_id!=""){
	$query .= " and emp_id='$booker_id'";
}
if($branch_id!=""){
	$query .= " and emp_id in(select emp_id from emp_master where branch_id = '$branch_id')";
}
include "../../../../model/app_settings/branchwise_filteration.php";
$query .= " order by booking_id desc";
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">

<table class="table table-hover" id='hotel_list' style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Booking_ID</th>
			<th>Customer_Name</th>
			<th>Contact&nbsp;</th>
			<th>EMAIL_ID&nbsp;</th>
			<th>Total_Hotel</th>
			<th>Booking_Date</th>
			<th>View</th>
			<th class="info text-right">Basic_Amount</th>
			<th class="info text-right">Service_Charge</th>
			<th class="info text-right">Tax</th>
			<th class="info text-right">Discount</th>
			<th class="info text-right">TDS</th>
			<th class="info text-right">Sale</th>
			<th class="danger text-right">Cancel</th>
			<th class="info text-right" text-right>Total</th>
			<th class="success text-right" text-right>Paid</th>
			<th>View</th>
			<th class="warning text-right">Outstanding_Balance</th>
			<th>Due_Date</th>
			<th>Purchase</th>
			<th>Purchased_From</th>
			<th>Branch</th>
			<th>Booked_By</th>
			<th>Invoice</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$total_balance=0;
		$total_refund=0;		
		$cancel_total =0;
		$sale_total = 0;
		$paid_total = 0;
		$balance_total = 0;

		$sq_booking = mysql_query($query);
		while($row_booking = mysql_fetch_assoc($sq_booking)){

			$pass_count = mysql_num_rows(mysql_query("select * from hotel_booking_entries where booking_id='$row_booking[booking_id]'"));
			$cancel_count = mysql_num_rows(mysql_query("select * from hotel_booking_entries where booking_id='$row_booking[booking_id]' and status='Cancel'"));
		 	if($pass_count==$cancel_count){
   				$bg="danger";
   			}
   			else {
   				$bg="#fff";
   			}

   			$date = $row_booking['created_at'];
	        $yr = explode("-", $date);
	        $year =$yr[0];
			$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_booking[customer_id]'"));
			if($sq_customer['type']=='Corporate'){
				$customer_name = $sq_customer['company_name'];
			}else{
				$customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
			}

			$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_booking[emp_id]'"));
			if($sq_emp['first_name'] == '') { $emp_name='Admin';}
			else{ $emp_name = $sq_emp['first_name'].' '.$sq_emp['last_name']; }

			$sq_branch = mysql_fetch_assoc(mysql_query("select * from branches where branch_id='$sq_emp[branch_id]'"));
			$branch_name = $sq_branch['branch_name']==''?'NA':$sq_branch['branch_name'];
			$sq_total_member = mysql_num_rows(mysql_query("select booking_id from hotel_booking_entries where booking_id = '$row_booking[booking_id]' and status!='Cancel'"));

			$due_date = ($row_booking['due_date'] == '1970-01-01') ? 'NA' : get_date_user($row_booking['due_date']);
			$sq_payment_total = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from hotel_booking_payment where booking_id='$row_booking[booking_id]' and clearance_status!='Pending' and clearance_status!='Cancelled'"));

			$sq_hotel_info = mysql_fetch_assoc(mysql_query("select * from hotel_booking_entries where booking_id='$row_booking[booking_id]'"));
 
			$sale_bal = $row_booking['total_fee'] ;
			$paid_amount = $sq_payment_total['sum'];
			$total_bal = $sale_bal - $paid_amount;
			if($total_bal>=0)
			{
				$available_bal = $available_bal + $total_bal;
			}else
			{
				$pending_bal = $pending_bal + ($total_bal);
			}
			if($paid_amount==""){ $paid_amount = 0; }
			
			$sale_amount=$row_booking['total_fee']-$row_booking['cancel_amount'];

			$canc_amount=$row_booking['cancel_amount'];
			if($canc_amount=="") {$canc_amount = 0; }

			$total_amount1 = $row_booking['total_fee'] - $canc_amount;
			$total_bal = $sale_bal - $canc_amount;   
      $bal_amount =$total_bal - $paid_amount;
			//Footer
			$cancel_total = $cancel_total + $canc_amount;
			$sale_total = $sale_total + $total_bal;
			$paid_total = $paid_total + $paid_amount;
			$balance_total = $balance_total + $bal_amount;

			/////// Purchase ////////
			$total_purchase = 0;
			$purchase_amt = 0;
			$i=0;
			$p_due_date = '';
			$sq_purchase_count = mysql_num_rows(mysql_query("select * from vendor_estimate where estimate_type='Hotel Booking' and estimate_type_id='$row_booking[booking_id]'"));
			if($sq_purchase_count == 0){  $p_due_date = 'NA'; }
			$sq_purchase = mysql_query("select * from vendor_estimate where estimate_type='Hotel Booking' and estimate_type_id='$row_booking[booking_id]'");
			while($row_purchase = mysql_fetch_assoc($sq_purchase)){		
				$purchase_amt = $row_purchase['net_total'] - $row_purchase['refund_net_total'];
				$total_purchase = $total_purchase + $purchase_amt;
			}
			$sq_purchase1 = mysql_fetch_assoc(mysql_query("select * from vendor_estimate where estimate_type='Hotel Booking' and estimate_type_id='$row_booking[booking_id]'"));		
			$vendor_name = get_vendor_name_report($sq_purchase1['vendor_type'], $sq_purchase1['vendor_type_id']);
			if($vendor_name == ''){ $vendor_name1 = 'NA';  }
			else{ $vendor_name1 = $vendor_name; }

			$invoice_no = get_hotel_booking_id($row_booking['booking_id'],$year);
			$booking_id = $row_booking['booking_id'];
			$invoice_date = date('d-m-Y',strtotime($row_booking['created_at']));
			$customer_id = $row_booking['customer_id'];
			$service_name = "Hotel Invoice";
			//**Service Tax
			$taxation_type = $row_booking['taxation_type'];
			$service_tax_per = $row_booking['service_tax'];			
			$service_tax = $row_booking['service_tax_subtotal'];
			//**Basic Cost
			$basic_cost = $row_booking['sub_total'] - $row_booking['tds'] - $row_booking['discount'] - $row_booking['cancel_amount'];;
			$service_charge = $row_booking['service_charge'];
			//**Net Amount
			$net_amount = $row_booking['total_fee'] - $row_booking['cancel_amount'];;
			$sq_sac = mysql_fetch_assoc(mysql_query("select * from sac_master where service_name='Hotel / Accommodation'"));   
			$sac_code = $sq_sac['hsn_sac_code'];
			if($app_invoice_format == 4)
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/tax_invoice_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax&net_amount=$net_amount&service_charge=$service_charge&total_paid=$paid_amount&balance_amount=$total_bal&sac_code=$sac_code&branch_status=$branch_status&booking_id=$booking_id&pass_count=$pass_count";
			else
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/hotel_body_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax&net_amount=$net_amount&service_charge=$service_charge&total_paid=$paid_amount&balance_amount=$total_bal&sac_code=$sac_code&branch_status=$branch_status&booking_id=$booking_id";


			?>
			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= get_hotel_booking_id($row_booking['booking_id'],$year) ?></td>
				<td><?= $customer_name ?></td>
				<td><?= ($sq_customer['contact_no']) ?></td>
				<td><?= ($sq_customer['email_id']) ?></td>
				<td><?= $sq_total_member ?></td>		
				<td><?php echo get_date_user($row_booking['created_at']); ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="hotel_view_modal(<?= $row_booking['booking_id'] ?>)" title="View Detail"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</td>
				<td class="info text-right"><?= number_format($row_booking['sub_total'],2) ?></td>
				<td class="info text-right"><?= number_format($row_booking['service_charge'],2)?></td>
				<td class="info text-right"><?= number_format($row_booking['service_tax_subtotal'],2); ?></td>
				<td class="info text-right"><?= number_format($row_booking['discount'],2); ?></td>
				<td class="info text-right"><?= number_format($row_booking['tds'],2); ?></td>
				<td class="info text-right"><?= number_format($row_booking['total_fee'],2) ?></td>
				<td class="danger text-right" text-right><?= number_format($canc_amount, 2); ?></td>
				<td class="info text-right" text-right><?= number_format($total_bal, 2); ?></td>
				<td class="success text-right" text-right><?= number_format($paid_amount, 2); ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="payment_view_modal(<?= $row_booking['booking_id'] ?>)" title="View Detail"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</td>
				<td class="warning text-right" text-right><?= number_format($bal_amount, 2); ?></td>
				<td><?php echo $due_date; ?></td>
				<td><?php echo number_format($total_purchase,2); ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="supplier_view_modal(<?= $row_booking['booking_id'] ?>)" title="View Detail"><i class="fa fa-eye" aria-hidden="true"></i></button></td>
				<td><?php echo $branch_name; ?></td>
				<td><?php echo $emp_name; ?></td>
				<td>
					<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
	<tfoot>
		<th colspan="13"></th>
		<th colspan="2" class="danger text-right"><?php echo "TOTAL CANCEL : ".number_format($cancel_total,2); ?></th>
		<th colspan="2" class="info text-right"><?php echo "TOTAL SALE : ".number_format($sale_total,2); ?></th>
		<th colspan="2" class="success text-right"><?php echo "TOTAL PAID : ".number_format($paid_total,2); ?></th>
		<th colspan="2" class="warning text-right"><?php echo "TOTAL BALANCE : ".number_format($balance_total,2); ?></th>
		<th colspan="3"></th>
	</tfoot>
</table>
</div> </div> </div>
<script>
$('#hotel_list').dataTable({
		"pagingType": "full_numbers"
	});
</script>