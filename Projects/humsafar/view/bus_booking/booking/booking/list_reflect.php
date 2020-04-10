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


$query = "select * from bus_booking_master where financial_year_id='$financial_year_id' ";

if($booking_id!=""){

	$query .= " and booking_id='$booking_id'";

}

if($customer_id!=""){

	$query .= " and customer_id='$customer_id'";

}

if($from_date!='' && $to_date!=''){

			$from_date = get_date_db($from_date);

			$to_date = get_date_db($to_date);

			$query .=" and created_at between '$from_date' and '$to_date'";

}

if($cust_type != ""){

	$query .= " and customer_id in (select customer_id from customer_master where type = '$cust_type')";

}
if($role == "B2b"){

	$query .= " and emp_id='$emp_id'";

}


if($company_name != ""){

	$query .= " and customer_id in (select customer_id from customer_master where company_name = '$company_name')";

}
include "../../../../model/app_settings/branchwise_filteration.php";
$query .= " order by booking_id desc";

?>

<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">

	

<table class="table table-hover" id="tbl_list" style="margin: 20px 0 !important;">

	<thead>

		<tr class="table-heading-row">

			<th>S_No.</th>

			<th>Booking_ID</th>

			<th>Customer_name</th>

			<th>Booking_Date</th>

			<th>Total_bus</th>

			<th>Bus_Operator</th>

			<!-- <th>Bus Type</th> -->

			<th class="text-right">Amount</th>

			<th class="text-right">cncl_Amount</th>

			<th class="text-right">Total</th>

			<th>Invoice</th>

			<th>View</th>

			<th>Edit</th>
			<th>Created_by</th>

		</tr>

	</thead>

	<tbody>

		<?php 

		$count = 0;

		$sq_booking = mysql_query($query);

		while($row_booking = mysql_fetch_assoc($sq_booking)){
			$sq_emp =  mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_booking[emp_id]'"));
			$emp_name = ($row_booking['emp_id'] != 0) ? $sq_emp['first_name'].' '.$sq_emp['last_name'] : 'Admin';

			$pass_count = mysql_num_rows(mysql_query("select * from bus_booking_entries where booking_id='$row_booking[booking_id]'"));
			$cancel_count = mysql_num_rows(mysql_query("select * from bus_booking_entries where booking_id='$row_booking[booking_id]' and status='Cancel'"));
		 	if($pass_count==$cancel_count){
   				$bg="danger";
   			}
   			else{
   				$bg="#fff";
   			}

			$date = $row_booking['created_at'];
			$yr = explode("-", $date);
			$year =$yr[0];

			$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_booking[customer_id]'"));
			if($sq_customer['type'] == 'Corporate'){
				$customer_name = $sq_customer['company_name'];
			}else{
				$customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
			}


			$sq_bus = mysql_fetch_assoc(mysql_query("select * from bus_booking_entries where booking_id='$row_booking[booking_id]'"));
			$sq_total_seates = mysql_num_rows(mysql_query("select booking_id from bus_booking_entries where booking_id='$row_booking[booking_id]'")); 
			$sq_paid_amount = mysql_fetch_assoc(mysql_query("SELECT sum(payment_amount) as sum from bus_booking_payment_master where booking_id='$row_booking[booking_id]' and clearance_status!='Pending' and clearance_status!='Cancelled'"));

			$paid_amount = $sq_paid_amount['sum'];
			$paid_amount = ($paid_amount == '')?0:$paid_amount;
			$sale_amount = $row_booking['net_total']-$row_booking['cancel_amount'];
			$bal_amount = $sale_amount - $paid_amount;

			$cancel_amt = $row_booking['cancel_amount'];
			if($cancel_amt == ""){ $cancel_amt = 0;}

			$total_sale = $total_sale+$row_booking['net_total'];
			$total_cancelation_amount = $total_cancelation_amount+$cancel_amt;
			$total_balance = $total_balance+$sale_amount;

			$invoice_no = get_bus_booking_id($row_booking['booking_id'],$year);
			$booking_id = $row_booking['booking_id'];
			$invoice_date = date('d-m-Y',strtotime($row_booking['created_at']));
			$customer_id = $row_booking['customer_id'];
			$service_name = "Bus Invoice";

			//**Service tax
			$taxation_type = $row_booking['taxation_type'];
			$service_tax_per = $row_booking['service_tax'];
			$service_charge = $row_booking['service_charge'];
			$service_tax = $row_booking['service_tax_subtotal'];

			//**Basic Cost
			$basic_cost = $row_booking['basic_cost']-$row_booking['cancel_amount'];			
			$net_amount = $row_booking['net_total']-$row_booking['cancel_amount'];
			$sq_sac = mysql_fetch_assoc(mysql_query("select * from sac_master where service_name='Bus'"));   
			$sac_code = $sq_sac['hsn_sac_code'];

			if($app_invoice_format == 4)
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/tax_invoice_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax&net_amount=$net_amount&service_charge=$service_charge&total_paid=$paid_amount&balance_amount=$bal_amount&sac_code=$sac_code&branch_status=$branch_status&booking_id=$booking_id&pass_count=$pass_count";
			else
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/bus_body_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax&net_amount=$net_amount&service_charge=$service_charge&total_paid=$paid_amount&balance_amount=$bal_amount&sac_code=$sac_code&branch_status=$branch_status&booking_id=$booking_id";

			?>

			<tr class="<?= $bg ?>">
				<td ><?= ++$count ?></td>
				<td><?= get_bus_booking_id($row_booking['booking_id'],$year) ?></td>
				<td><?= $customer_name ?></td>
				<td><?= date('d-m-Y', strtotime($row_booking['created_at'])) ?></td>
				<td><?= $sq_total_seates ?></td>
				<td><?= $sq_bus['company_name'] ?></td>
				<td class="text-right info"><?= $row_booking['net_total'] ?></td>
				<td class="text-right danger"><?= $cancel_amt?></td>
				<td class="text-right success"><?= number_format(($row_booking['net_total']-$row_booking['cancel_amount']), 2); ?></td>
				<td>
					<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>
				<td>

					<button class="btn btn-info btn-sm" onclick="view_modal(<?= $row_booking['booking_id'] ?>)" title="View Information"><i class="fa fa-eye"></i></button>
				</td>
				<td>
					<button class="btn btn-info btn-sm" onclick="update_modal(<?= $row_booking['booking_id'] ?>)" title="Edit Booking"><i class="fa fa-pencil-square-o"></i></button>
				</td>
				<td><?= $emp_name ?></td>
			</tr>
			<?php
		}
		?>	
	</tbody>
	<tfoot>
		<tr class="active">
			<th class="text-right" colspan="6">Total</th>
			<th class="text-right info"> <?= number_format($total_sale, 2); ?></th>
			<th class="text-right danger"> <?= number_format($total_cancelation_amount, 2); ?></th>
			<th class="text-right success"> <?= number_format($total_balance, 2); ?></th>
			<th colspan="4"></th>
		</tr>
	</tfoot>
</table>

</div> </div> </div>

<script>

$('#tbl_list').dataTable({

		"pagingType": "full_numbers"

	});

</script>