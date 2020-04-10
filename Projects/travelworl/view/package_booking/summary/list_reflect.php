<?php 
include "../../../model/model.php";
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
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
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">

<table class="table" id="tbl_package_list" style="margin: 20px 0 !important;">
	<thead>
	    <tr class="table-heading-row">
	    	<th>S_No.</th>
			<th>Booking_ID</th>
			<th>Customer_Name</th>
			<th>Contact&nbsp;</th>
			<th>EMAIL_ID&nbsp;</th>
			<th>Total_Guest</th>
			<th>Booking_Date</th>
			<th>View</th>
			<th>Tour_name</th>
			<th>Tour_date&nbsp;&nbsp;&nbsp;</th>
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
			<th>Incentive</th>
			<th>Invoice</th>
			<th>Booking_Form</th>
	    </tr>
	</thead>
	<tbody>
		<?php 
		$query = "select * from package_tour_booking_master where 1 ";
		if($customer_id!=""){
			$query .= " and customer_id='$customer_id'";
		}
		if($booking_id!=""){
			$query .= " and booking_id='$booking_id'";
		}
		if($from_date!="" && $to_date!=""){
			$from_date = date('Y-m-d', strtotime($from_date));
			$to_date = date('Y-m-d', strtotime($to_date));
			$query .= " and booking_date between '$from_date' and '$to_date'";
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
		include "../../../model/app_settings/branchwise_filteration.php";
		$query .= " order by booking_id desc";
		$count = 0;
		$total_balance=0;
		$total_refund=0;	
		$cancel_total =0;
		$sale_total = 0;
		$paid_total = 0;
		$balance_total = 0;

		$sq_package = mysql_query($query);
		while($row_package = mysql_fetch_assoc($sq_package)){


        $date = $row_package['booking_date'];
         $yr = explode("-", $date);
         $year =$yr[0];
			$date = $row_package['booking_date'];
                $yr = explode("-", $date);
                $year =$yr[0];
			$pass_count= mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_package[booking_id]'"));
			$cancle_count= mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_package[booking_id]' and status='Cancel'"));
			if($pass_count==$cancle_count){
					$bg="danger";
			}else{
					$bg="#fff";
			}
			
			$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_package[emp_id]'"));
			if($sq_emp['first_name'] == '') { $emp_name='Admin';}
			else{ $emp_name = $sq_emp['first_name'].' '.$sq_emp['last_name']; }

			$sq_branch = mysql_fetch_assoc(mysql_query("select * from branches where branch_id='$sq_emp[branch_id]'"));
			$branch_name = $sq_branch['branch_name']==''?'NA':$sq_branch['branch_name'];
			$sq_total_member = mysql_num_rows(mysql_query("select booking_id from package_travelers_details where booking_id = '$row_package[booking_id]' AND status!='Cancel'"));
			$sq_customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_package[customer_id]'"));
			if($sq_customer_info['type'] == 'Corporate'){
				$customer_name = $sq_customer_info['company_name'];
			}else{
				$customer_name = $sq_customer_info['first_name'].' '.$sq_customer_info['last_name'];
			}

			$total_paid = 0;
			$sq_paid_amount = mysql_fetch_assoc(mysql_query("SELECT sum(amount) as sum from  package_payment_master where booking_id='$row_package[booking_id]' and clearance_status!='Pending' and clearance_status!='Cancelled'"));
			$total_paid =  $sq_paid_amount['sum']; 
			if($total_paid == ''){ $total_paid = 0; }
			
			//sale amount
			$tour_fee = $row_package['actual_tour_expense'] + $row_package['total_travel_expense'];

			//cancel amount
			$row_esti= mysql_fetch_assoc(mysql_query("SELECT * from package_refund_traveler_estimate where booking_id='$row_package[booking_id]'"));
			$tour_esti=$row_esti['cancel_amount'];

			//total amount
			$total_amount = $tour_fee - $tour_esti;

			//balance
			$total_balance=$total_amount - $total_paid;	

			//Footer
			$cancel_total = $cancel_total + $tour_esti;
			$sale_total = $sale_total + $total_amount;
			$paid_total = $paid_total + $sq_paid_amount['sum'];
			$balance_total = $balance_total + $total_balance;
			/////// Purchase ////////
			$total_purchase = 0;
			$purchase_amt = 0;
			$i=0;
			$p_due_date = '';
			$sq_purchase_count = mysql_num_rows(mysql_query("select * from vendor_estimate where estimate_type='Package Tour' and estimate_type_id='$row_package[booking_id]'"));
			if($sq_purchase_count == 0){  $p_due_date = 'NA'; }
			$sq_purchase = mysql_query("select * from vendor_estimate where estimate_type='Package Tour' and estimate_type_id='$row_package[booking_id]'");
			while($row_purchase = mysql_fetch_assoc($sq_purchase)){			
				$purchase_amt = $row_purchase['net_total'] - $row_purchase['refund_net_total'];
				$total_purchase = $total_purchase + $purchase_amt;
			}
			$sq_purchase1 = mysql_fetch_assoc(mysql_query("select * from vendor_estimate where estimate_type='Package Tour' and estimate_type_id='$row_package[booking_id]'"));		
			$vendor_name = get_vendor_name_report($sq_purchase1['vendor_type'], $sq_purchase1['vendor_type_id']);
			if($vendor_name == ''){ $vendor_name1 = 'NA';  }
			else{ $vendor_name1 = $vendor_name; }

			/////// Incetive ////////
			$sq_incentive = mysql_fetch_assoc(mysql_query("select * from booker_incentive_package_tour where booking_id='$row_package[booking_id]'"));
			
			//////////Invoice//////////////
			$invoice_no = get_package_booking_id($row_package['booking_id'],$year);
			$invoice_date = date('d-m-Y',strtotime($row_package['booking_date']));
			$customer_id = $row_package['customer_id'];
			$quotation_id = $row_package['quotation_id'];
			$service_name = "Package Invoice";			
			
			//**Service Tax
			$taxation_type = $row_package['taxation_type'];
			
			//basic amount
			$train_expense = $row_package['train_expense'];
			$plane_expense = $row_package['plane_expense'];
			$cruise_expense = $row_package['cruise_expense'];
			$visa_amount = $row_package['visa_amount'];
			$insuarance_amount = $row_package['insuarance_amount'];
			$tour_subtotal = $row_package['subtotal'] - $cancel_tour_amount;
			$basic_cost = $train_expense +$plane_expense +$cruise_expense +$visa_amount +$insuarance_amount +$tour_subtotal;

			//Service charge	
			$train_service_charge = $row_package['train_service_charge'];
			$plane_service_charge = $row_package['plane_service_charge'];
			$cruise_service_charge = $row_package['cruise_service_charge'];
			$visa_service_charge = $row_package['visa_service_charge'];
			$insuarance_service_charge = $row_package['insuarance_service_charge'];
			$service_charge = $train_service_charge +$plane_service_charge +$cruise_service_charge +$visa_service_charge +$insuarance_service_charge +$tour_subtotal;

			//service tax
			$train_service_tax = $row_package['train_service_tax'];
			$plane_service_tax = $row_package['plane_service_tax'];
			$cruise_service_tax = $row_package['cruise_service_tax'];
			$visa_service_tax = $row_package['visa_service_tax'];
			$insuarance_service_tax = $row_package['insuarance_service_tax'];
			$tour_service_tax = $row_package['tour_service_tax'];
			
			//service tax subtotal	
			$train_service_tax_subtotal = $row_package['train_service_tax_subtotal'];
			$plane_service_tax_subtotal = $row_package['plane_service_tax_subtotal'];
			$cruise_service_tax_subtotal = $row_package['cruise_service_tax_subtotal'];
			$visa_service_tax_subtotal = $row_package['visa_service_tax_subtotal'];
			$insuarance_service_tax_subtotal = $row_package['insuarance_service_tax_subtotal'];
			$tour_service_tax_subtotal = $row_package['tour_service_tax_subtotal'];
			$service_tax_subtotal = $train_service_tax_subtotal +$plane_service_tax_subtotal +$cruise_service_tax_subtotal +$visa_service_tax_subtotal +$insuarance_service_tax_subtotal+$tour_service_tax_subtotal;

			// Net amount
			$net_amount = 0;
			$tour_total_amount= ($row_package['actual_tour_expense']!="") ? $row_package['actual_tour_expense']: 0;
			$net_amount  =  $tour_total_amount + $row_package['total_travel_expense'] - $cancel_tour_amount;
			
			$sq_sac = mysql_fetch_assoc(mysql_query("select * from sac_master where service_name='Package Tour'"));   
			$sac_code = $sq_sac['hsn_sac_code'];
			$tour_date = get_date_user($row_package['tour_from_date']);
			$destination_city = $row_package['tour_name'];

			if($app_invoice_format == 4)			
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/git_fit_tax_invoice.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&train_expense=$train_expense&plane_expense=$plane_expense&cruise_expense=$cruise_expense&visa_amount=$visa_amount&insuarance_amount=$insuarance_amount&tour_subtotal=$tour_subtotal&train_service_charge=$train_service_charge&plane_service_charge=$plane_service_charge&cruise_service_charge=$cruise_service_charge&visa_service_charge=$visa_service_charge&insuarance_service_charge=$insuarance_service_charge&train_service_tax=$train_service_tax&plane_service_tax=$plane_service_tax&cruise_service_tax=$cruise_service_tax&visa_service_tax=$visa_service_tax&insuarance_service_tax=$insuarance_service_tax&tour_service_tax=$tour_service_tax&train_service_tax_subtotal=$train_service_tax_subtotal&plane_service_tax_subtotal=$plane_service_tax_subtotal&cruise_service_tax_subtotal=$cruise_service_tax_subtotal&visa_service_tax_subtotal=$visa_service_tax_subtotal&insuarance_service_tax_subtotal=$insuarance_service_tax_subtotal&tour_service_tax_subtotal=$tour_service_tax_subtotal&total_paid=$total_paid&net_amount=$net_amount&sac_code=$sac_code&branch_status=$branch_status&pass_count=$pass_count&tour_date=$tour_date&destination_city=$destination_city";
			else
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/git_fit_body_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&quotation_id=$quotation_id&service_name=$service_name&taxation_type=$taxation_type&train_expense=$train_expense&plane_expense=$plane_expense&cruise_expense=$cruise_expense&visa_amount=$visa_amount&insuarance_amount=$insuarance_amount&tour_subtotal=$tour_subtotal&train_service_charge=$train_service_charge&plane_service_charge=$plane_service_charge&cruise_service_charge=$cruise_service_charge&visa_service_charge=$visa_service_charge&insuarance_service_charge=$insuarance_service_charge&train_service_tax=$train_service_tax&plane_service_tax=$plane_service_tax&cruise_service_tax=$cruise_service_tax&visa_service_tax=$visa_service_tax&insuarance_service_tax=$insuarance_service_tax&tour_service_tax=$tour_service_tax&train_service_tax_subtotal=$train_service_tax_subtotal&plane_service_tax_subtotal=$plane_service_tax_subtotal&cruise_service_tax_subtotal=$cruise_service_tax_subtotal&visa_service_tax_subtotal=$visa_service_tax_subtotal&insuarance_service_tax_subtotal=$insuarance_service_tax_subtotal&tour_service_tax_subtotal=$tour_service_tax_subtotal&total_paid=$total_paid&net_amount=$net_amount&sac_code=$sac_code&branch_status=$branch_status";


			// Booking Form
			$b_url = BASE_URL."model/app_settings/print_html/booking_form_html/package_tour.php?booking_id=$row_package[booking_id]";
			?>	
			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= get_package_booking_id($row_package['booking_id'],$year) ?></td>
				<td><?= $customer_name ?></td>
				<td><?= ($sq_customer_info['contact_no']) ?></td>
				<td><?= ($sq_customer_info['email_id']) ?></td>
				<td><?= $sq_total_member ?></td>	
				<td><?php echo get_date_user($row_package['booking_date']); ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="package_view_modal(<?= $row_package['booking_id'] ?>)" title="View Detail"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</td>	
				<td><?= ($row_package['tour_name']) ?></td>
				<td><?= get_date_user($row_package['tour_from_date']).' To '.get_date_user($row_package['tour_to_date']) ?></td>
				<td class="info text-right"><?= number_format($tour_fee,2) ?></td>
				<td class="danger text-right"><?= number_format($tour_esti,2)?></td>
				<td class="info text-right"><?= number_format($total_amount,2)?></td>
				<td class="success text-right"><?= number_format($total_paid,2) ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="payment_view_modal(<?= $row_package['booking_id'] ?>)" title="View Detail"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</td>
				<td class="warning text-right" text-right><?= number_format($total_balance, 2); ?></td>		
				<td><?php echo get_date_user($row_package['due_date']); ?></td>
				<td><?php echo number_format($total_purchase,2); ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="supplier_view_modal(<?= $row_package['booking_id'] ?>)" title="View Detail"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</td>
				<td><?php echo $branch_name; ?></td>
				<td><?php echo $emp_name; ?></td>
				<td><?php echo number_format($sq_incentive['incentive_amount'],2); ?></td>				
				<td class="text-center">
					<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>
				<td>
					<a onclick="loadOtherPage('<?= $b_url ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
	<tfoot>
		<th colspan="10"></th>
		<th colspan="2" class="info text-right"><?php echo "TOTAL SALE : ".number_format($sale_total,2); ?></th>
		<th colspan="2" class="danger text-right"><?php echo "TOTAL CANCEL : ".number_format($cancel_total,2); ?></th>
		<th colspan="2" class="success text-right"><?php echo "TOTAL PAID : ".number_format($paid_total,2); ?></th>
		<th colspan="2" class="warning text-right"><?php echo "TOTAL BALANCE : ".number_format($balance_total,2); ?></th>
		<th colspan="5"></th>
	</tfoot>
</table>
</div> </div> </div>
<script>
$('#tbl_package_list').dataTable({
		"pagingType": "full_numbers"
	});
</script>