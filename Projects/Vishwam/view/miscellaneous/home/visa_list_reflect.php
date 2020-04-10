<?php 
include "../../../model/model.php";
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$branch_status = $_POST['branch_status'];
$customer_id = $_POST['customer_id'];
$misc_id = $_POST['misc_id'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$cust_type = $_POST['cust_type'];
$company_name = $_POST['company_name'];
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];

?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">

<table class="table table-bordered" id="tbl_visa_list" style="margin: 20px 0 !important;">
	<thead>
	    <tr class="table-heading-row">
	    	<th>S_No.</th>
			<th>Miscellaneous_ID</th>
			<th>Customer_Name</th>
			<th>Mobile</th>
			<th>Total_Pax</th>
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
		$query = "select * from miscellaneous_master where financial_year_id='$financial_year_id' ";
		
		if($customer_id!=""){
			$query .= " and customer_id='$customer_id'";
		}
		if($misc_id!=""){
			$query .= " and misc_id='$misc_id'";
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
		include "../../../model/app_settings/branchwise_filteration.php";
		$query .= " order by misc_id desc";
	
		$count = 0;
		$booking_amount = 0;
		$cancelled_amount = 0;
		$total_amount = 0;

		$sq_visa = mysql_query($query);		

			while($row_visa = mysql_fetch_assoc($sq_visa)){ 
				$sq_emp =  mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_visa[emp_id]'"));
				$emp_name = ($row_visa['emp_id'] != 0) ? $sq_emp['first_name'].' '.$sq_emp['last_name'] : 'Admin';
			
			$pass_count = mysql_num_rows(mysql_query("select * from  miscellaneous_master_entries where misc_id='$row_visa[misc_id]'"));
			$cancel_count = mysql_num_rows(mysql_query("select * from  miscellaneous_master_entries where misc_id='$row_visa[misc_id]' and status='Cancel'"));
			 $bg="";
       			if($pass_count==$cancel_count) 	{

       				$bg="danger";

       			}

       			else  {

       				$bg="#fff";

       			}

	        //Get Total no of visa members
            $sq_total_member=mysql_num_rows(mysql_query("select misc_id from miscellaneous_master_entries where misc_id='$row_visa[misc_id]' "));     

    	    $customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_visa[customer_id]'"));
    	    if($customer_info['type']=='Corporate'){
    	    	$customer_name = $customer_info['company_name'];
    	    }else{
    	    	$customer_name = $customer_info['first_name'].' '.$customer_info['last_name'];
    	    }
            //Get Total visa cost
            $visa_total_amount=$row_visa['misc_total_cost'];
            
			//Get total refund amount
			$cancel_amount=$row_visa['cancel_amount'];
			if($cancel_amount==""){	$cancel_amount=0; }
			
            $total_visa_amount=$visa_total_amount-$cancel_amount;
            
            //calculate total amounts
            $booking_amount=$booking_amount+$visa_total_amount;
			$cancelled_amount=$cancelled_amount+$cancel_amount;
            $total_amount=$total_amount+$total_visa_amount;

            $total_paid = 0;

            $sq_paid_amount = mysql_fetch_assoc(mysql_query("SELECT sum(payment_amount) as sum from miscellaneous_payment_master where misc_id='$row_visa[misc_id]' and clearance_status!='Pending' and clearance_status!='Cancelled'"));
            $total_paid =  $sq_paid_amount['sum'];  

            $total_paid = ($total_paid == '') ? '0' : $total_paid;            

            $created_at = $row_visa['created_at'];
            $year = explode("-", $created_at);
           	$year =$year[0];

            $invoice_no = get_misc_booking_id($row_visa['misc_id'],$year);
            $booking_id = $row_visa['misc_id'];
			$invoice_date = date('d-m-Y',strtotime($row_visa['created_at']));
			$customer_id = $row_visa['customer_id'];
			
			$service_name = "Miscellaneous Invoice";
			//**Service Tax
			$taxation_type = $row_visa['taxation_type'];
			$service_tax_per = $row_visa['service_tax'];
			$service_charge = $row_visa['service_charge'];
			$service_tax = $row_visa['service_tax_subtotal'];
			//**Basic Cost
			$basic_cost = $row_visa['misc_issue_amount'] - $row_visa['cancel_amount'];
			$net_amount = $row_visa['misc_total_cost'] - $row_visa['cancel_amount'];
			$balance_amount = $net_amount - $total_paid;

			$sq_sac = mysql_fetch_assoc(mysql_query("select * from sac_master where service_name='Miscellaneous'"));   
			$sac_code = $sq_sac['hsn_sac_code'];
			if($app_invoice_format == 4)
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/tax_invoice_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax&net_amount=$net_amount&service_charge=$service_charge&total_paid=$total_paid&balance_amount=$balance_amount&sac_code=$sac_code&branch_status=$branch_status&booking_id=$booking_id&pass_count=$pass_count";
			else
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/misc_body_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax&net_amount=$net_amount&service_charge=$service_charge&total_paid=$total_paid&balance_amount=$balance_amount&sac_code=$sac_code&branch_status=$branch_status&booking_id=$booking_id";
		    ?>	

			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= get_misc_booking_id($row_visa['misc_id'],$year) ?></td>
				<td><?= $customer_name ?></td>
				<td><?= $customer_info['contact_no'] ?></td>
				<td><?php echo $sq_total_member; ?></td>
				<td class="info text-right"><?php echo $visa_total_amount; ?></td>
				<td class="danger text-right"><?php echo $cancel_amount; ?></td>
				<td class="success text-right"><?php echo number_format($total_visa_amount, 2); ?></td>
				<td>
					<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>
				<td>
					<button class="btn btn-info btn-sm" onclick="visa_display_modal(<?= $row_visa['misc_id'] ?>)" title="View"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</td>
				<td>
					<button class="btn btn-info btn-sm" onclick="visa_update_modal(<?= $row_visa['misc_id'] ?>)" title="Edit Details"><i class="fa fa-pencil-square-o"></i></button>
				</td>
				<td><?= $emp_name ?></td>
			</tr>
			<?php
		//}
	}
		?>
	</tbody>
	<tfoot>
		<tr>
		    <th colspan="5" class="text-right">Total</th>
			<th class="info text-right"><?php echo number_format($booking_amount, 2); ?></th>
			<th class="danger text-right"><?php echo number_format($cancelled_amount, 2); ?></th>
			<th class="success text-right"><?php echo number_format($total_amount, 2); ?></th>
			<th colspan="4"></th>
		</tr>
	</tfoot>
</table>
</div> </div> </div>
<script>

$('#tbl_visa_list').dataTable({
		"pagingType": "full_numbers"
	});

</script>