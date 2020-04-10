<?php
include "../../../../model/model.php";

$customer_id = $_POST['customer_id'];
$booking_id = $_POST['booking_id'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$cust_type = $_POST['cust_type'];
$company_name = $_POST['company_name'];
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$branch_status = $_POST['branch_status'];
 
$query = "select * from hotel_booking_master where financial_year_id='$financial_year_id' ";
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
if($role == "B2b"){
	$query .= " and emp_id='$emp_id'";
}
include "../../../../model/app_settings/branchwise_filteration.php";
$query .= " order by booking_id desc";
 
?>
<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>" >

<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">

<table class="table table-bordered" id='hotel_list' style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Booking_ID</th>
			<th>Customer_Name</th>
			<th>Booking_Date</th>
			<th class="info">Amount</th>
			<th class="danger">Cncl_Amount</th>
			<th class="success">Total</th>
			<th>Invoice</th>
			<th>View</th>
			<th>Edit</th>
			<th>Created_by</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$total_sale = 0;
		$total_cancelation_amount = 0;
		$total_balance = 0;
		$available_bal=0;
		$pending_bal=0;
		$sq_booking = mysql_query($query);
		while($row_booking = mysql_fetch_assoc($sq_booking)){
			$sq_emp =  mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_booking[emp_id]'"));
			$emp_name = ($row_booking['emp_id'] != 0) ? $sq_emp['first_name'].' '.$sq_emp['last_name'] : 'Admin';

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

			$sq_payment_total = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from hotel_booking_payment where booking_id='$row_booking[booking_id]' and clearance_status!='Pending' and clearance_status!='Cancelled'"));

			$sq_hotel_info = mysql_fetch_assoc(mysql_query("select * from hotel_booking_entries where booking_id='$row_booking[booking_id]'"));
 
			$sale_bal = $row_booking['total_fee'] - $row_booking['cancel_amount'];
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

			$total_sale = $total_sale+$row_booking['total_fee'];
			$total_cancelation_amount = $total_cancelation_amount+$canc_amount;
			$total_balance = $total_balance+$sale_amount;

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
				<td><?php echo date('d-m-Y', strtotime($row_booking['created_at'])); ?></td>
				<td class="info text-right"><?php echo $row_booking['total_fee']; ?></td>
				<td class="danger text-right"><?php echo $canc_amount; ?></td>
				<td class="success text-right"><?php echo number_format($total_amount1, 2); ?></td>
				<td>
					<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>
				<td>
					<button class="btn btn-info btn-sm" onclick="booking_display_modal(<?= $row_booking['booking_id'] ?>)" title="View Booking"><i class="fa fa-eye"></i></button>
				</td>				
				<td>
					<button class="btn btn-info btn-sm" onclick="booking_update_modal(<?= $row_booking['booking_id'] ?>)" title="Edit Booking"><i class="fa fa-pencil-square-o"></i></button>
				</td>
				<td><?= $emp_name ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
	<tfoot>
		<tr class="active">
			<th class="text-right" colspan="4">Total</th>
			<th class="text-right info"> <?= number_format($total_sale, 2);?></th>
			<th class="text-right danger"> <?= number_format($total_cancelation_amount, 2); ?></th>
			<th class="text-right success"> <?= number_format($total_balance, 2) ?></th>
			<th colspan="4"></th>
		</tr>
	</tfoot>
</table>

</div> </div> </div>

<div id="div_booking_update_content"></div>
<script>
$('#hotel_list').dataTable({
		"pagingType": "full_numbers"
	});

function booking_update_modal(booking_id)
{	
	var branch_status = $('#branch_status').val();
	$.post('booking/booking_update_modal.php', { booking_id : booking_id, branch_status : branch_status }, function(data){
		$('#div_booking_update_content').html(data);
	});
}
</script>