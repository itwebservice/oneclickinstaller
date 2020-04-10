<?php 
include "../../../model/model.php";
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];
$customer_id = $_POST['customer_id'];
$id = $_POST['id'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$cust_type = $_POST['cust_type'];
$company_name = $_POST['company_name'];
$booker_id = $_POST['booker_id'];
$branch_id = $_POST['branch_id'];
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">

<table class="table" id="tbl_group_list" style="margin: 20px 0 !important;">
	<thead>
	    <tr class="table-heading-row">
	    	<th>S_No.</th>
			<th>booking_id</th>
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
		$query = "select * from tourwise_traveler_details where 1 ";
		if($customer_id!=""){
			$query .= " and customer_id='$customer_id'";
		}
		if($id!=""){
			$query .= " and id='$id'";
		}
		if($from_date!="" && $to_date!=""){
			$from_date = date('Y-m-d', strtotime($from_date));
			$to_date = date('Y-m-d', strtotime($to_date));
			$query .= " and form_date between '$from_date' and '$to_date'";
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
		$query .= " order by id desc";
		$count = 0;
		$total_balance=0;
		$total_refund=0;		
		$cancel_total =0;
		$sale_total = 0;
		$paid_total = 0;
		$balance_total = 0;

		$sq_package = mysql_query($query);
		while($row_booking = mysql_fetch_assoc($sq_package))
		{
		    $bg="";      
   			if($row_booking['tour_group_status']=="Cancel") 	{
   				$bg="danger";
   				$sq_total_member = 0;
   			}
   			else  {
   				$bg="#fff";
   			}

   			$booking_date = $row_booking['form_date'];
	        $yr = explode("-", $booking_date);
	        $year =$yr[0];
			
			$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_booking[emp_id]'"));
			if($sq_emp['first_name'] == '') { $emp_name='Admin';}
			else{ $emp_name = $sq_emp['first_name'].' '.$sq_emp['last_name']; }

			$sq_branch = mysql_fetch_assoc(mysql_query("select * from branches where branch_id='$sq_emp[branch_id]'"));
			$branch_name = $sq_branch['branch_name']==''?'NA':$sq_branch['branch_name'];
			if($row_booking['tour_group_status']!="Cancel") {
				$sq_total_member = mysql_num_rows(mysql_query("select traveler_group_id from travelers_details where traveler_group_id = '$row_booking[id]' AND status!='Cancel'"));
			}
			$sq_customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_booking[customer_id]'"));
			if($sq_customer_info['type'] == 'Corporate'){
				$customer_name = $sq_customer_info['company_name'];
			}else{
				$customer_name = $sq_customer_info['first_name'].' '.$sq_customer_info['last_name'];
			}

			$sq_paid_amount = mysql_fetch_assoc(mysql_query("select sum(amount) as sum from payment_master where tourwise_traveler_id='$row_booking[id]' and clearance_status!='Pending' and clearance_status!='Cancelled'"));
			$paid_amount = $sq_paid_amount['sum'];

			//sale amount
			$tour_fee = $row_booking['total_tour_fee'] + $row_booking['total_travel_expense'];

			//cancel amount
			$sq_est_count = mysql_num_rows(mysql_query("select * from refund_tour_estimate where tourwise_traveler_id='$row_booking[id]'"));
			if($sq_est_count!='0'){
				$sq_est_info= mysql_fetch_assoc(mysql_query("SELECT * from refund_tour_estimate where tourwise_traveler_id='$row_booking[id]'"));
				$tour_esti=$sq_est_info['cancel_amount'];
		    }
		    else{
		    	 $sq_est_info = mysql_fetch_assoc(mysql_query("select * from refund_traveler_estimate where tourwise_traveler_id='$row_booking[id]'"));
			 	 $tour_esti=$sq_est_info['cancel_amount'];
		    }
			//total amount
			$total_amount = $tour_fee - $tour_esti;

			//balance
			$total_balance=$total_amount - $paid_amount;	
			
			//Footer
			$cancel_total = $cancel_total + $tour_esti;
			$sale_total = $sale_total + $total_amount;
			$paid_total = $paid_total + $sq_paid_amount['sum'];
			$balance_total = $balance_total + $total_balance;

			/////// Purchase ////////
			$total_purchase = 0;
			$purchase_amt = 0;
			$i=0;
			$sq_purchase_count = mysql_num_rows(mysql_query("select * from vendor_estimate where estimate_type='Group Tour' and estimate_type_id='$row_booking[tour_group_id]'"));
			if($sq_purchase_count == 0){  $p_due_date = 'NA'; }
			$sq_purchase = mysql_query("select * from vendor_estimate where estimate_type='Group Tour' and estimate_type_id='$row_booking[tour_group_id]'");
			while($row_purchase = mysql_fetch_assoc($sq_purchase)){
				$p_due_date = get_date_user($row_purchase['due_date']); 			
				$purchase_amt = $row_purchase['net_total'] - $row_purchase['refund_net_total'];
				$total_purchase = $total_purchase + $purchase_amt;
			}
			$sq_purchase1 = mysql_fetch_assoc(mysql_query("select * from vendor_estimate where estimate_type='Group Tour' and estimate_type_id='$row_booking[tour_group_id]'"));		
			$vendor_name = get_vendor_name_report($sq_purchase1['vendor_type'], $sq_purchase1['vendor_type_id']);
			if($vendor_name == ''){ $vendor_name1 = 'NA';  }
			else{ $vendor_name1 = $vendor_name; }

			/////// Incetive ////////
			$sq_incentive = mysql_fetch_assoc(mysql_query("select * from booker_incentive_group_tour where tourwise_traveler_id='$row_booking[id]'"));

			///Tour
			$sq_tour = mysql_fetch_assoc(mysql_query("select * from tour_master where tour_id='$row_booking[tour_id]'"));

			$sq_group = mysql_fetch_assoc(mysql_query("select * from tour_groups where group_id='$row_booking[tour_group_id]'"));
			$tour = $sq_tour['tour_name'];

			$group = get_date_user($sq_group['from_date']).' To '.get_date_user($sq_group['to_date']);
			
			//////////Invoice//////////////

			$invoice_no = get_group_booking_id($row_booking['id'],$year);

			$invoice_date = date('d-m-Y',strtotime($row_booking['form_date']));

			$customer_id = $row_booking['customer_id'];

			$service_name = "Group Invoice";			

			// Net amount
			$net_amount = 0;
			$tour_total_amount= ($row_booking['total_tour_fee']!="") ? $row_booking['total_tour_fee']: 0;
			$net_amount  =  $tour_total_amount + $row_booking['total_travel_expense'] - $cancel_tour_amount;
			//**Service Tax

			$taxation_type = $row_booking['taxation_type'];

			//basic amount
			$train_expense = $row_booking['train_expense'];
			$plane_expense = $row_booking['plane_expense'];
			$cruise_expense = $row_booking['cruise_expense'];
			$visa_amount = $row_booking['visa_amount'];
			$insuarance_amount = $row_booking['insuarance_amount'];
			$tour_subtotal = $row_booking['tour_fee_subtotal_1'] - $cancel_tour_amount;
			$basic_cost = $train_expense +$plane_expense +$cruise_expense +$visa_amount +$insuarance_amount +$tour_subtotal;

			//Service charge	
			$train_service_charge = $row_booking['train_service_charge'];
			$plane_service_charge = $row_booking['plane_service_charge'];
			$cruise_service_charge = $row_booking['cruise_service_charge'];
			$visa_service_charge = $row_booking['visa_service_charge'];
			$insuarance_service_charge = $row_booking['insuarance_service_charge'];
			$service_charge = $train_service_charge +$plane_service_charge +$cruise_service_charge +$visa_service_charge +$insuarance_service_charge;

			//service tax
			$train_service_tax = $row_booking['train_service_tax'];
			$plane_service_tax = $row_booking['plane_service_tax'];
			$cruise_service_tax = $row_booking['cruise_service_tax'];
			$visa_service_tax = $row_booking['visa_service_tax'];
			$insuarance_service_tax = $row_booking['insuarance_service_tax'];
			$tour_service_tax = $row_booking['service_tax_per'];
			
			//service tax subtotal	
			$train_service_tax_subtotal = $row_booking['train_service_tax_subtotal'];
			$plane_service_tax_subtotal = $row_booking['plane_service_tax_subtotal'];
			$cruise_service_tax_subtotal = $row_booking['cruise_service_tax_subtotal'];
			$visa_service_tax_subtotal = $row_booking['visa_service_tax_subtotal'];
			$insuarance_service_tax_subtotal = $row_booking['insuarance_service_tax_subtotal'];
			$tour_service_tax_subtotal = $row_booking['service_tax'];
			$service_tax_subtotal = $train_service_tax_subtotal +$plane_service_tax_subtotal +$cruise_service_tax_subtotal +$visa_service_tax_subtotal +$insuarance_service_tax_subtotal+$tour_service_tax_subtotal;	
			
			$sq_sac = mysql_fetch_assoc(mysql_query("select * from sac_master where service_name='Group Tour'"));   
			$sac_code = $sq_sac['hsn_sac_code'];
			$tour_date = get_date_user($sq_group['from_date']);
			
			if($app_invoice_format == 4)
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/git_fit_tax_invoice.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&train_expense=$train_expense&plane_expense=$plane_expense&cruise_expense=$cruise_expense&visa_amount=$visa_amount&insuarance_amount=$insuarance_amount&tour_subtotal=$tour_subtotal&train_service_charge=$train_service_charge&plane_service_charge=$plane_service_charge&cruise_service_charge=$cruise_service_charge&visa_service_charge=$visa_service_charge&insuarance_service_charge=$insuarance_service_charge&train_service_tax=$train_service_tax&plane_service_tax=$plane_service_tax&cruise_service_tax=$cruise_service_tax&visa_service_tax=$visa_service_tax&insuarance_service_tax=$insuarance_service_tax&tour_service_tax=$tour_service_tax&train_service_tax_subtotal=$train_service_tax_subtotal&plane_service_tax_subtotal=$plane_service_tax_subtotal&cruise_service_tax_subtotal=$cruise_service_tax_subtotal&visa_service_tax_subtotal=$visa_service_tax_subtotal&insuarance_service_tax_subtotal=$insuarance_service_tax_subtotal&tour_service_tax_subtotal=$tour_service_tax_subtotal&total_paid=$total_paid&net_amount=$net_amount&sac_code=$sac_code&branch_status=$branch_status&pass_count=$pass_count&tour_date=$tour_date&destination_city=$tour";
			else
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/git_fit_body_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&train_expense=$train_expense&plane_expense=$plane_expense&cruise_expense=$cruise_expense&visa_amount=$visa_amount&insuarance_amount=$insuarance_amount&tour_subtotal=$tour_subtotal&train_service_charge=$train_service_charge&plane_service_charge=$plane_service_charge&cruise_service_charge=$cruise_service_charge&visa_service_charge=$visa_service_charge&insuarance_service_charge=$insuarance_service_charge&train_service_tax=$train_service_tax&plane_service_tax=$plane_service_tax&cruise_service_tax=$cruise_service_tax&visa_service_tax=$visa_service_tax&insuarance_service_tax=$insuarance_service_tax&tour_service_tax=$tour_service_tax&train_service_tax_subtotal=$train_service_tax_subtotal&plane_service_tax_subtotal=$plane_service_tax_subtotal&cruise_service_tax_subtotal=$cruise_service_tax_subtotal&visa_service_tax_subtotal=$visa_service_tax_subtotal&insuarance_service_tax_subtotal=$insuarance_service_tax_subtotal&tour_service_tax_subtotal=$tour_service_tax_subtotal&total_paid=$paid_amount&net_amount=$net_amount&sac_code=$sac_code&branch_status=$branch_status";

			// Booking Form
			$b_url = BASE_URL."model/app_settings/print_html/booking_form_html/group_tour.php?booking_id=$row_booking[id]";
			?>	
			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= get_group_booking_id($row_booking['id'],$year) ?></td>
				<td><?= $customer_name ?></td>
				<td><?= ($sq_customer_info['contact_no']) ?></td>
				<td><?= ($sq_customer_info['email_id']) ?></td>
				<td><?= $sq_total_member ?></td>	
				<td><?php echo get_date_user($row_booking['form_date']); ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="group_view_modal(<?= $row_booking['id'] ?>)" title="View Detail"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</td>	
				<td><?= ($tour) ?></td>
				<td><?= ($group) ?></td>
				<td class="info text-right"><?= number_format($tour_fee,2) ?></td>
				<td class="danger text-right"><?= number_format($tour_esti,2)?></td>
				<td class="info text-right"><?= number_format($total_amount,2)?></td>
				<td class="success text-right"><?= number_format($sq_paid_amount['sum'],2) ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="payment_view_modal(<?= $row_booking['id'] ?>)" title="View Detail"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</td>
				<td class="warning text-right"><?= number_format($total_balance, 2); ?></td>		
				<td><?php echo get_date_user($row_booking['balance_due_date']); ?></td>
				<td><?php echo number_format($total_purchase,2); ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="supplier_view_modal(<?= $row_booking['tour_group_id'] ?>)" title="View Detail"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</td>
				<td><?php echo $branch_name; ?></td>
				<td><?php echo $emp_name; ?></td>
				<td><?php echo number_format($sq_incentive['incentive_amount'],2); ?></td>
				<td>
					<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>
				<td class="text-center">
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
$('#tbl_group_list').dataTable({
	"pagingType": "full_numbers"
});
</script>