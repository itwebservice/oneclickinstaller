<?php

include "../../../../model/model.php";

$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$branch_status = $_POST['branch_status'];

$customer_id = $_POST['customer_id'];

$booking_id = $_POST['booking_id'];

$payment_from_date = $_POST['from_date'];

$payment_to_date = $_POST['to_date'];

$cust_type = $_POST['cust_type'];

$company_name = $_POST['company_name'];
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];





$query = "select * from forex_booking_master where financial_year_id='$financial_year_id' ";

if($booking_id!=""){

	$query .= " and booking_id='$booking_id'";

}

if($customer_id!=""){

	$query .= " and customer_id='$customer_id'";

}

if($payment_from_date!='' && $payment_to_date!=''){

			$payment_from_date = get_date_db($payment_from_date);

			$payment_to_date = get_date_db($payment_to_date);

			$query .=" and date(created_at) between '$payment_from_date' and '$payment_to_date'";

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

<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">

	

<table class="table table-bordered" id="tbl_list_b" style="margin: 20px 0 !important;">

	<thead>

		<tr class="table-heading-row">

			<th>S_No.</th>

			<th>Booking_ID</th>

			<th>Customer_Name</th>

			<th>Sale/Buy</th>

			<th>Currency</th>

			<th>INR_Cost</th>

			<th class="success">Amount</th>

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

			$date = $row_booking['created_at'];
			$yr = explode("-", $date);
			$year =$yr[0];

			$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_booking[customer_id]'"));
			if($sq_customer['type']=='Corporate'){
				$customer_name = $sq_customer['company_name'];
			}else{
				$customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
			}

			$sq_paid_amount = mysql_fetch_assoc(mysql_query("SELECT sum(payment_amount) as sum from forex_booking_payment_master where booking_id='$row_booking[booking_id]' and clearance_status!='Pending' and clearance_status!='Cancelled'"));

			$paid_amount = $sq_paid_amount['sum'];
			$paid_amount = ($paid_amount == '')?0:$paid_amount;

			$invoice_no = get_forex_booking_id($row_booking['booking_id'],$year);
			$booking_id = $row_booking['booking_id'];
			$invoice_date = date('d-m-Y',strtotime($row_booking['created_at']));

			$customer_id = $row_booking['customer_id'];

			$service_name = "Forex Invoice";

			$basic_cost = $row_booking['net_total'] - $row_booking['service_tax_subtotal']- $row_booking['service_charge'];

			$taxation_type = $row_booking['taxation_type'];

			$service_tax_per = $row_booking['service_tax'];

			$service_tax = $row_booking['service_tax_subtotal'];

			$service_charge = $row_booking['service_charge'];

			$net_amount = $row_booking['net_total'];
			$bal_amount = $net_amount - $paid_amount;

			$sq_sac = mysql_fetch_assoc(mysql_query("select * from sac_master where service_name='Forex'"));   
			$sac_code = $sq_sac['hsn_sac_code'];

			if($app_invoice_format == 4)
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/tax_invoice_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax&net_amount=$net_amount&service_charge=$service_charge&total_paid=$paid_amount&balance_amount=$bal_amount&sac_code=$sac_code&branch_status=$branch_status&booking_id=$booking_id";
			else
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/forex_body_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax&net_amount=$net_amount&service_charge=$service_charge&total_paid=$paid_amount&balance_amount=$bal_amount&sac_code=$sac_code&branch_status=$branch_status&booking_id=$booking_id";

			?>

			<tr>

				<td><?= ++$count ?></td>

				<td><?= get_forex_booking_id($row_booking['booking_id'],$year) ?></td>

				<td><?= $customer_name ?></td>

				<td><?= $row_booking['booking_type'] ?></td>

				<td><?= $row_booking['currency_code'] ?></td>

				<td class="text-right"><?= $row_booking['forex_amount'] ?></td>

				<td class="success text-right"><?= $row_booking['net_total'] ?></td>
				<td>
					<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>

				<td>

					<button class="btn btn-info btn-sm" onclick="display_modal(<?= $row_booking['booking_id'] ?>)" title="View Information"><i class="fa fa-eye"></i></button>

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

</table>



</div> </div> </div>



<script>

$('#tbl_list_b').dataTable({

		"pagingType": "full_numbers"

	});

</script>