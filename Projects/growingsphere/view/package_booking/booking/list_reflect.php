<?php
include "../../../model/model.php";

$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$branch_status = $_POST['branch_status'];
$customer_id = $_POST['customer_id'];
$booking_id = $_POST['booking_id'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$cust_type = $_POST['cust_type'];
$company_name = $_POST['company_name'];
?>



<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">

	

<table class="table" id="tbl_list" style="margin: 20px 0 !important;">

	<thead>

		<tr class="table-heading-row">

			<th>S_No.</th>

			<th>Booking_ID</th>

			<th>Customer_Name</th>

			<th>Tour</th>

			<th>Tour_Date</th>

			<th>Booking_Form</th>

			<th>Invoice</th>

			<th>View</th>

			<th>Edit</th>
			<th>Created_by</th>

		</tr>

	</thead>

	<tbody>

		<?php 

		$query = "select * from package_tour_booking_master where financial_year_id='$financial_year_id' ";

		if($customer_id!=""){

			$query .=" and customer_id='$customer_id'";

		}

		if($booking_id!=""){

			$query .=" and booking_id='$booking_id'";

		}

		if($from_date!="" && $to_date!=""){

			$from_date = get_date_db($from_date);

			$to_date = get_date_db($to_date);



			$query .= " and date(booking_date) between '$from_date' and '$to_date'";

		}

		if($cust_type != ""){

			$query .= " and customer_id in (select customer_id from customer_master where type = '$cust_type')";

		}

		if($company_name != ""){

			$query .= " and customer_id in (select customer_id from customer_master where company_name = '$company_name')";

		}

		if($role == "B2b"){

			$query .= " and emp_id ='$emp_id'";

		}
		include "../../../model/app_settings/branchwise_filteration.php";
	 	$query .= " order by booking_id desc";

		$count = 0;

		$sq_booking = mysql_query($query);

		while($row_booking = mysql_fetch_assoc($sq_booking)){
			$sq_emp =  mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_booking[emp_id]'"));
			$emp_name = ($row_booking['emp_id'] != 0) ? $sq_emp['first_name'].' '.$sq_emp['last_name'] : 'Admin';

			$pass_count= mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_booking[booking_id]'"));
			$cancle_count= mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_booking[booking_id]' and status='Cancel'"));
			if($pass_count==$cancle_count){
				$bg="danger";
			}else{
				$bg="#fff";
			}
			$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_booking[customer_id]'"));
			if($sq_customer['type']=='Corporate'){
				$customer_name = $sq_customer['company_name'];
			}else{
				$customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
			}

			$date = $row_booking['booking_date'];
			$yr = explode("-", $date);
			$year =$yr[0];


			$sq_esti = mysql_fetch_assoc(mysql_query("select * from package_refund_traveler_estimate where booking_id='$row_booking[booking_id]'"));
 			$cancel_tour_amount=($sq_esti['cancel_amount']);
			if($cancel_tour_amount==""){	$cancel_tour_amount = 0; }


			$sq_train = mysql_num_rows(mysql_query("select * from package_train_master where booking_id='$row_booking[booking_id]'"));

			$sq_plane = mysql_num_rows(mysql_query("select * from package_plane_master where booking_id='$row_booking[booking_id]'"));

			$sq_visa = $row_booking['visa_amount'];

			$sq_insurance = $row_booking['insuarance_amount'];

			$total_paid = 0;
			$sq_paid_amount = mysql_fetch_assoc(mysql_query("SELECT sum(amount) as sum from  package_payment_master where booking_id='$row_booking[booking_id]' and clearance_status!='Pending' and clearance_status!='Cancelled'"));
			$total_paid =  $sq_paid_amount['sum']; 
			if($total_paid == ''){ $total_paid = 0; }

			$average = 1;

			if($sq_train > 0){ $average++; }

			if($sq_plane > 0){ $average++; }

			if($sq_visa !=0 && $sq_visa!=""){ $average++; }

			if($sq_insurance !=0 && $sq_insurance!=""){ $average++; }			


			$invoice_no = get_package_booking_id($row_booking['booking_id'],$year);
			$invoice_date = date('d-m-Y',strtotime($row_booking['booking_date']));
			$customer_id = $row_booking['customer_id'];
			$quotation_id = $row_booking['quotation_id'];
			$service_name = "Package Invoice";
			$tour_name = $row_booking['tour_name'];
			
			//**Service Tax
			$taxation_type = $row_booking['taxation_type'];
			
			//basic amount
			$train_expense = $row_booking['train_expense'];
			$plane_expense = $row_booking['plane_expense'];
			$cruise_expense = $row_booking['cruise_expense'];
			$visa_amount = $row_booking['visa_amount'];
			$insuarance_amount = $row_booking['insuarance_amount'];
			$tour_subtotal = $row_booking['subtotal'] - $cancel_tour_amount;
			$basic_cost = $train_expense +$plane_expense +$cruise_expense +$visa_amount +$insuarance_amount +$tour_subtotal;

			//Service charge	
			$train_service_charge = $row_booking['train_service_charge'];
			$plane_service_charge = $row_booking['plane_service_charge'];
			$cruise_service_charge = $row_booking['cruise_service_charge'];
			$visa_service_charge = $row_booking['visa_service_charge'];
			$insuarance_service_charge = $row_booking['insuarance_service_charge'];
			$service_charge = $train_service_charge +$plane_service_charge +$cruise_service_charge +$visa_service_charge +$insuarance_service_charge +$tour_subtotal;		

			//service tax
			$train_service_tax = $row_booking['train_service_tax'];
			$plane_service_tax = $row_booking['plane_service_tax'];
			$cruise_service_tax = $row_booking['cruise_service_tax'];
			$visa_service_tax = $row_booking['visa_service_tax'];
			$insuarance_service_tax = $row_booking['insuarance_service_tax'];
			$tour_service_tax = $row_booking['tour_service_tax'];
			
			//service tax subtotal	
			$train_service_tax_subtotal = $row_booking['train_service_tax_subtotal'];
			$plane_service_tax_subtotal = $row_booking['plane_service_tax_subtotal'];
			$cruise_service_tax_subtotal = $row_booking['cruise_service_tax_subtotal'];
			$visa_service_tax_subtotal = $row_booking['visa_service_tax_subtotal'];
			$insuarance_service_tax_subtotal = $row_booking['insuarance_service_tax_subtotal'];
			$tour_service_tax_subtotal = $row_booking['tour_service_tax_subtotal'];
			$service_tax_subtotal = $train_service_tax_subtotal +$plane_service_tax_subtotal +$cruise_service_tax_subtotal +$visa_service_tax_subtotal +$insuarance_service_tax_subtotal+$tour_service_tax_subtotal;

			// Net amount
			$net_amount = 0;
			$tour_total_amount= ($row_booking['actual_tour_expense']!="") ? $row_booking['actual_tour_expense']: 0;
			$net_amount  =  $tour_total_amount + $row_booking['total_travel_expense'] - $cancel_tour_amount;
			
			$sq_sac = mysql_fetch_assoc(mysql_query("select * from sac_master where service_name='Package Tour'"));
			$sac_code = $sq_sac['hsn_sac_code'];
			$tour_date = get_date_user($row_booking['tour_from_date']);
			$destination_city = $row_booking['tour_name'];
	
			if($app_invoice_format == 4)			
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/git_fit_tax_invoice.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&train_expense=$train_expense&plane_expense=$plane_expense&cruise_expense=$cruise_expense&visa_amount=$visa_amount&insuarance_amount=$insuarance_amount&tour_subtotal=$tour_subtotal&train_service_charge=$train_service_charge&plane_service_charge=$plane_service_charge&cruise_service_charge=$cruise_service_charge&visa_service_charge=$visa_service_charge&insuarance_service_charge=$insuarance_service_charge&train_service_tax=$train_service_tax&plane_service_tax=$plane_service_tax&cruise_service_tax=$cruise_service_tax&visa_service_tax=$visa_service_tax&insuarance_service_tax=$insuarance_service_tax&tour_service_tax=$tour_service_tax&train_service_tax_subtotal=$train_service_tax_subtotal&plane_service_tax_subtotal=$plane_service_tax_subtotal&cruise_service_tax_subtotal=$cruise_service_tax_subtotal&visa_service_tax_subtotal=$visa_service_tax_subtotal&insuarance_service_tax_subtotal=$insuarance_service_tax_subtotal&tour_service_tax_subtotal=$tour_service_tax_subtotal&total_paid=$total_paid&net_amount=$net_amount&sac_code=$sac_code&branch_status=$branch_status&pass_count=$pass_count&tour_date=$tour_date&destination_city=$destination_city";
			else
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/git_fit_body_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&quotation_id=$quotation_id&service_name=$service_name&taxation_type=$taxation_type&train_expense=$train_expense&plane_expense=$plane_expense&cruise_expense=$cruise_expense&visa_amount=$visa_amount&insuarance_amount=$insuarance_amount&tour_subtotal=$tour_subtotal&train_service_charge=$train_service_charge&plane_service_charge=$plane_service_charge&cruise_service_charge=$cruise_service_charge&visa_service_charge=$visa_service_charge&insuarance_service_charge=$insuarance_service_charge&train_service_tax=$train_service_tax&plane_service_tax=$plane_service_tax&cruise_service_tax=$cruise_service_tax&visa_service_tax=$visa_service_tax&insuarance_service_tax=$insuarance_service_tax&tour_service_tax=$tour_service_tax&train_service_tax_subtotal=$train_service_tax_subtotal&plane_service_tax_subtotal=$plane_service_tax_subtotal&cruise_service_tax_subtotal=$cruise_service_tax_subtotal&visa_service_tax_subtotal=$visa_service_tax_subtotal&insuarance_service_tax_subtotal=$insuarance_service_tax_subtotal&tour_service_tax_subtotal=$tour_service_tax_subtotal&total_paid=$total_paid&net_amount=$net_amount&sac_code=$sac_code&branch_status=$branch_status&tour_name=$tour_name";

			// Booking Form
			$b_url = BASE_URL."model/app_settings/print_html/booking_form_html/package_tour.php?booking_id=$row_booking[booking_id]&quotation_id=$quotation_id&branch_status=$branch_status&year=$year";
			?>

			<tr class="<?php echo $bg; ?>">

				<td><?= ++$count ?></td>

				<td><?= get_package_booking_id($row_booking['booking_id'],$year) ?></td>

				<td><?= $customer_name ?></td>

				<td><?= $row_booking['tour_name'] ?></td>

				<td><?= get_date_user($row_booking['tour_from_date']).' to '.get_date_user($row_booking['tour_to_date']) ?></td>
				<td>
					<a onclick="loadOtherPage('<?= $b_url ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>	
				<td>
					<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>
				<td>
					<button class="btn btn-info btn-sm" onclick="package_view_modal(<?= $row_booking['booking_id'] ?>)" title="View Detail"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</td>
				<td>
					<form action="booking_update/package_booking_master_update.php" id="frm_booking_<?= $count ?>" method="POST">
						<input type="hidden" id="booking_id" name="booking_id" value="<?= $row_booking['booking_id'] ?>">
						<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>">
						<button class="btn btn-info btn-sm" title="Edit booking"><i class="fa fa-pencil-square-o"></i></button>
					</form>
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
$('#tbl_list').dataTable({
	"pagingType": "full_numbers"
});
</script>