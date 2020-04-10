<?php 
include "../../../../model/model.php";
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$branch_status = $_POST['branch_status'];
$customer_id = $_POST['customer_id'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$ticket_id=$_POST['ticket_id_filter'];
$cust_type = $_POST['cust_type'];
$company_name = $_POST['company_name'];
 
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">

<table class="table table-hover" id="ticket_list" style="margin: 20px 0 !important;">
	<thead>
	    <tr class="table-heading-row">
	    	<th>S_No.</th>
			<th>Booking_ID</th>
			<th>Customer_Name</th>
			<th>Mobile</th>
			<th>Trip_Type</th>
			<th class="info">Amount</th>
			<th class="danger">Cncl_Amount</th>
			<th class="success">Total</th>
			<th>Invoice</th>
			<th>E_Ticket</th>
			<th>view</th>
			<th>Edit</th>
			<th>Created_by</th>
	    </tr>
	</thead>
	<tbody>
		<?php 
		$query = "select * from ticket_master where financial_year_id='$financial_year_id'";
		if($customer_id!=""){
			$query .= " and customer_id='$customer_id'";
		}
		if($ticket_id!="")
		{
			$query .= " and ticket_id='$ticket_id'";
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
    	$query .= " order by ticket_id desc ";
		$count = 0;
		$total_sale = 0;
		$total_cancelation_amount = 0;
		$total_balance = 0;
		$sq_ticket = mysql_query($query);
		while($row_ticket = mysql_fetch_assoc($sq_ticket)){

			$sq_emp =  mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_ticket[emp_id]'"));
			$emp_name = ($row_ticket['emp_id'] != 0) ? $sq_emp['first_name'].' '.$sq_emp['last_name'] : 'Admin';
			$pass_count = mysql_num_rows(mysql_query("select * from ticket_master_entries where ticket_id='$row_ticket[ticket_id]'"));
			$cancel_count = mysql_num_rows(mysql_query("select * from ticket_master_entries where ticket_id='$row_ticket[ticket_id]' and status='Cancel'"));

			if($pass_count==$cancel_count){
       				$bg="danger";
       		}
			else {
				$bg="#fff";
			}

			$date = $row_ticket['created_at'];
			$yr = explode("-", $date);
			$year =$yr[0];

			$sq_customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_ticket[customer_id]'"));
			if($sq_customer_info['type']=='Corporate'){
				$customer_name = $sq_customer_info['company_name'];
			}else{
				$customer_name = $sq_customer_info['first_name'].' '.$sq_customer_info['last_name'];
			}

			$sq_paid_amount = mysql_fetch_assoc(mysql_query("SELECT sum(payment_amount) as sum from ticket_payment_master where ticket_id='$row_ticket[ticket_id]' and clearance_status!='Pending' and clearance_status!='Cancelled'"));
				
			$paid_amount = $sq_paid_amount['sum'];
			$paid_amount = ($paid_amount == '')?0:$paid_amount;
			$sale_amount = $row_ticket['ticket_total_cost'] - $row_ticket['cancel_amount'];
			$bal_amount = $sale_amount - $paid_amount;

			$cancel_amt = $row_ticket['cancel_amount'];
			if($cancel_amt==""){ $cancel_amt = 0;}
			
			$total_sale = $total_sale + $row_ticket['ticket_total_cost'];
			$total_cancelation_amount = $total_cancelation_amount + $cancel_amt;
			$total_balance = $total_balance + $sale_amount;

			$ticket_id = $row_ticket['ticket_id'];
			$invoice_no = get_ticket_booking_id($row_ticket['ticket_id'],$year);
			$invoice_date = date('d-m-Y',strtotime($row_ticket['created_at']));
			$customer_id = $row_ticket['customer_id'];
			$service_name = "Flight Invoice";			
			//**Discount
			$taxation_type = $row_ticket['taxation_type'];
			//**Service tax
			$service_tax_per = $row_ticket['service_tax'];
			$service_charge = $row_ticket['service_charge'];
			$service_tax = $row_ticket['service_tax_subtotal'];
			//**Basic Cost
			$basic_cost = $row_ticket['basic_cost'] + $row_ticket['basic_cost_markup'] - $row_ticket['basic_cost_discount'] - $row_ticket['cancel_amount'];

			$sq_sac = mysql_fetch_assoc(mysql_query("select * from sac_master where service_name='Flight'"));   
			$sac_code = $sq_sac['hsn_sac_code'];
			$net_amount = $row_ticket['ticket_total_cost'] - $row_ticket['cancel_amount'];

			if($app_invoice_format == 4)
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/tax_invoice_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&service_charge=$service_charge&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax&net_amount=$net_amount&ticket_id=$ticket_id&total_paid=$paid_amount&balance_amount=$bal_amount&sac_code=$sac_code&branch_status=$branch_status&pass_count=$pass_count";
			else
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/flight_body_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&service_charge=$service_charge&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax&net_amount=$net_amount&ticket_id=$ticket_id&total_paid=$paid_amount&balance_amount=$bal_amount&sac_code=$sac_code&branch_status=$branch_status";

			$voucher_name = "AIR TICKET VOUCHER";
			$pass_url = BASE_URL."view/visa_passport_ticket/ticket/home/e_ticket.php?ticket_id=$row_ticket[ticket_id]&service_name=$voucher_name&invoice_date=$invoice_date";
			?>	
			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= get_ticket_booking_id($row_ticket['ticket_id'],$year) ?></td>
				<td><?= $customer_name ?></td>
				<td><?= $sq_customer_info['contact_no'] ?></td>
				<td><?= $row_ticket['type_of_tour'] ?></td>
				<td class="info text-right"><?= $row_ticket['ticket_total_cost'] ?></td>
				<td class="danger text-right"><?= $cancel_amt ?></td>
				<td class="success text-right"><?= number_format(($row_ticket['ticket_total_cost'] - $cancel_amt), 2); ?></td>
				<td>
					<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>
				<td>
					<a href="<?= $pass_url ?>" target="_blank" class="btn btn-danger btn-sm" title="E_Ticket"><i class="fa fa-file-pdf-o"></i></a>
				</td>
				<td>
					<button class="btn btn-info btn-sm" onclick="ticket_display_modal(<?= $row_ticket['ticket_id'] ?>)" title="View Information"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</td>
				<td>
					<button class="btn btn-info btn-sm" onclick="ticket_update_modal(<?= $row_ticket['ticket_id'] ?>)" title="Edit Detail"><i class="fa fa-pencil-square-o"></i></button>
				</td>
				<td><?= $emp_name ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
	<tfoot>
		<tr class="active">
			<th class="text-right" colspan="5">Total</th>
			<th class="text-right info"> <?= number_format($total_sale, 2); ?></th>
			<th class="text-right danger"> <?= number_format($total_cancelation_amount, 2); ?></th>
			<th class="text-right success"> <?= number_format($total_balance, 2); ?></th>
			<th colspan="5"></th>
		</tr>
	</tfoot>
</table>
</div> </div> </div>
<script>
$('#ticket_list').dataTable({
		"pagingType": "full_numbers"
	});
</script>