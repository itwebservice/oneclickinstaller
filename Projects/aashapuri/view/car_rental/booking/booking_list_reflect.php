<?php
include "../../../model/model.php";

$customer_id = $_POST['customer_id'];
$traveling_date_from = $_POST['traveling_date_from'];
$traveling_date_to = $_POST['traveling_date_to'];
$cust_type = $_POST['cust_type'];
$company_name = $_POST['company_name'];
$emp_id = $_SESSION['emp_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$branch_status = $_POST['branch_status']; 
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];

$query = "select * from car_rental_booking where financial_year_id='$financial_year_id' ";
if($customer_id!=""){
	$query .= " and customer_id='$customer_id'";
}
if($traveling_date_from!='' && $traveling_date_to!=''){
	$traveling_date_from = get_date_db($traveling_date_from);
	$traveling_date_to = get_date_db($traveling_date_to);
	$query .=" and date(created_at) between '$traveling_date_from' and '$traveling_date_to'";
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
include "../../../model/app_settings/branchwise_filteration.php";
 
$query .= " order by booking_id desc";
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> 
<div class="table-responsive">
<table class="table table-hover" id="tbl_vendor_list" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Booking_ID</th>
			<th>Customer_Name</th>
			<th>Mobile</th>
			<th>Email_ID</th>
			<th>No_Of_Pax</th>
			<th>Travelling_Date&Time</th>
			<th>Amount</th>
			<th>Cancel</th>
			<th>View</th>
			<th>Edit</th>
			<th class="text-center">Invoice</th>
			<th class="text-center">Duty_Slip</th>
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
		
			$count++;
			$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_booking[customer_id]'"));
			
			if($sq_customer['type']=='Corporate'){
				$customer_name = $sq_customer['company_name'];
			}else{
				$customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
			}
			$bg="";
			($row_booking['status']=='Cancel') ? $bg='danger' : $bg='fff';

			$date = $row_booking['created_at'];
			$yr = explode("-", $date);
			$year =$yr[0];

			$sq_paid_amount = mysql_fetch_assoc(mysql_query("SELECT sum(payment_amount) as sum from car_rental_payment where booking_id='$row_booking[booking_id]' and clearance_status!='Pending' and clearance_status!='Cancelled'"));

			$paid_amount = $sq_paid_amount['sum'];
			$paid_amount = ($paid_amount == '')?0:$paid_amount;

			$invoice_no = get_car_rental_booking_id($row_booking['booking_id'],$year);
			$invoice_date = date('d-m-Y',strtotime($row_booking['created_at']));
			$customer_id = $row_booking['customer_id'];
			$booking_id = $row_booking['booking_id'];
			$service_name = "Car Rental Invoice";
			//**Service Tax
			$taxation_type = $row_booking['taxation_type'];
			$service_tax_per = $row_booking['service_tax'];
			$service_charge1 = $row_booking['actual_cost'] + $row_booking['km_total_fee'];
			$service_tax1 = $row_booking['service_tax_subtotal'];
			//**Basic Cost
			$basic_cost = $row_booking['total_cost'] - $service_tax1 - $row_booking['cancel_amount'];
			$other_charge = $row_booking['driver_allowance'] + $row_booking['permit_charges'] + $row_booking['toll_and_parking'] + $row_booking['state_entry_tax']  ;
			$net_amount = $row_booking['total_fees'] - $row_booking['cancel_amount'];
			$basic_cost1 = $net_amount - $service_charge1 - $service_tax1;
			$bal_amount = $net_amount - $paid_amount;

			$sq_sac = mysql_fetch_assoc(mysql_query("select * from sac_master where service_name='Car Rental'"));   
			$sac_code = $sq_sac['hsn_sac_code'];

			if($app_invoice_format == 4)
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/tax_invoice_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost1&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax1&net_amount=$net_amount&service_charge=$service_charge1&total_paid=$paid_amount&balance_amount=$bal_amount&sac_code=$sac_code&branch_status=$branch_status&booking_id=$booking_id&pass_count=$pass_count";
			else
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/carrental_body_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax1&net_amount=$net_amount&service_charge=$other_charge&total_paid=$paid_amount&balance_amount=$bal_amount&sac_code=$sac_code&branch_status=$branch_status&booking_id=$booking_id";
			?>
			<tr class="<?= $bg ?>">
				<td><?= $count ?></td>
				<td><?= get_car_rental_booking_id($row_booking['booking_id'],$year) ?></td>
				<td><?= $customer_name ?></td>
				<td><?= $sq_customer['contact_no'] ?></td>
				<td><?= $sq_customer['email_id'] ?></td>
				<td><?= $row_booking['total_pax'] ?></td>
				<td><?= date('d/m/Y H:i:s', strtotime($row_booking['traveling_date'])) ?></td>
				<td><?= number_format($row_booking['total_fees'] - $row_booking['cancel_amount'],2) ?></td>
				<td class="text-center">
					<button class="btn btn-danger btn-sm" onclick="booking_cancel(<?= $row_booking['booking_id'] ?>)" title="cancel Booking"><i class="fa fa-times"></i></button>
				</td>
				<td>
					<button class="btn btn-info btn-sm" onclick="car_display_modal(<?= $row_booking['booking_id'] ?>)" title="View"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</td>
				<td>
					<button class="btn btn-info btn-sm" onclick="booking_update_modal(<?= $row_booking['booking_id'] ?>)" title="Edit Booking"><i class="fa fa-pencil-square-o"></i></button>
				</td>
				<td>
					<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>
				<td class="text-center">
					<button class="btn btn-danger btn-sm" onclick="booking_registration_pdf(<?= $row_booking['booking_id'] ?>)" title="Registration"><i class="fa fa-file-pdf-o"></i></button>
				</td>
				<td><?= $emp_name ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
</div>
</div></div>
<script>
function booking_cancel(booking_id){
	var base_url = $('#base_url').val();
	$('#vi_confirm_box').vi_confirm_box({
        message: 'Are you sure you want to cancel?',
        callback: function(data1){
            if(data1=="yes"){
            	$.ajax({
                  type:'post',
                  url: base_url+'controller/car_rental/cancel/cancel_booking.php',
                  data:{ booking_id : booking_id },
                  success:function(result){
                    msg_alert(result);
                    booking_list_reflect();
                  },
                  error:function(result){
                    console.log(result.responseText);
                  }
                });
            }
        }
    });                	
}
function booking_registration_pdf(booking_id){
	url = "booking_registration_pdf.php?booking_id="+booking_id;
	window.open(url, '_BLANK');
}
$('#tbl_vendor_list').dataTable({
	"pagingType": "full_numbers"
});
</script>