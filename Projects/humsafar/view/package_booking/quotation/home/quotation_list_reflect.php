<?php
include "../../../../model/model.php";
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$booking_type = $_POST['booking_type'];
$package_id = $_POST['package_id'];
$quotation_id = $_POST['quotation_id'];
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_status = $_POST['branch_status'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$branch_id = $_POST['branch_id'];

global $app_quot_format;

$query = "select * from package_tour_quotation_master where financial_year_id='$financial_year_id' ";
if($from_date!='' && $to_date!=""){

	$from_date = date('Y-m-d', strtotime($from_date));
	$to_date = date('Y-m-d', strtotime($to_date));

	$query .= " and created_at between '$from_date' and '$to_date' "; 
}
if($booking_type!=''){
	$query .= " and booking_type='$booking_type'";
}
if($package_id!=''){
	$query .= " and package_id in(select package_id from custom_package_master where package_id = '$package_id')";
}
if($quotation_id!=''){
	$query .= " and quotation_id='$quotation_id'";

}
if($branch_status=='yes'){
	if($role=='Branch Admin' || $role=='Accountant' || $role_id>'7'){
	    $query .= " and branch_admin_id = '$branch_admin_id'";
	}
	elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
	    $query .= " and emp_id='$emp_id' and branch_admin_id = '$branch_admin_id'";
	}
}
elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
	$query .= " and emp_id='$emp_id'";
}
if($branch_id!=""){
	$query .= " and branch_admin_id = '$branch_id'";
}
$query .=" order by quotation_id desc ";
?>
<div class="row mg_tp_20">
	<div class="col-md-12 no-pad">
		<div class="table-responsive">
			<table class="table table-hover" id="quotation_table" style="margin: 20px 0 !important;">
				<thead>
				  <tr class="table-heading-row">
					<th>S_No.</th>
					<th class="text-center">Quotation_ID</th>
					<th>Package_Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>Customer</th>
					<th>Quotation_Date&nbsp;&nbsp;</th>
					<th>Amount</th>
					<th>Created_by</th>
					<th>PDF</th>
					<!-- <th>Proforma</th> -->
					<th>Email</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Acknowledgement&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>Edit</th>
					<th>View</th>
					<th>Copy</th>
				  </tr>
				</thead>
				<tbody>
					<?php 
						$count = 0;
						$quotation_cost = 0;
						$sq_quotation = mysql_query($query);
						while($row_quotation = mysql_fetch_assoc($sq_quotation)){
							$bg = ($row_quotation['clone'] == 'yes') ? 'warning' : '';
							$sq_emp =  mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_quotation[emp_id]'"));
							$emp_name = ($row_quotation['emp_id'] != 0) ? $sq_emp['first_name'].' '.$sq_emp['last_name'] : 'Admin';
							$quotation_date = $row_quotation['quotation_date'];
							$yr = explode("-", $quotation_date);
							$year =$yr[0];

							$sq_cost =  mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_costing_entries where quotation_id = '$row_quotation[quotation_id]'"));
							$sq_package_program = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id ='$row_quotation[package_id]'"));

							$quotation_cost = $row_quotation['train_cost'] + $row_quotation['flight_cost'] + $row_quotation['cruise_cost'] + $row_quotation['visa_cost'] + $row_quotation['guide_cost'] + $sq_cost['total_tour_cost'] + $row_quotation['misc_cost'];

							//Proforma Invoice
							$for = 'Package Tour'; 
							$invoice_no = get_quotation_id($row_quotation['quotation_id'],$year);
							$invoice_date = get_date_user($row_quotation['created_at']);
							$customer_id = $row_quotation['customer_name'];
							$customer_email = $row_quotation['email_id'];
							$service_name = "Proforma Invoice";

							//**Basic Cost
							$basic_cost = $sq_cost['tour_cost'] + $sq_cost['markup_subtotal'] + $sq_cost['transport_cost'] + $sq_cost['excursion_cost'];

							//GST
							$service_tax =  $sq_cost['service_tax_subtotal'];

							// Travel + visa
							$travel_cost = $row_quotation['train_cost']+ $row_quotation['flight_cost'] + $row_quotation['cruise_cost'] + $row_quotation['visa_cost'] + $row_quotation['guide_cost'] + $row_quotation['misc_cost'];

							//Net cost
							$net_amount = $sq_cost['total_tour_cost'] + $row_quotation['train_cost']+ $row_quotation['flight_cost'] + $row_quotation['visa_cost'] + $row_quotation['guide_cost'] + $row_quotation['misc_cost'] + $row_quotation['cruise_cost'];

							$quotation_id = $row_quotation['quotation_id'];
							$p_url = BASE_URL."model/app_settings/print_html/invoice_html/body/proforma_invoice_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&customer_email=$customer_email&service_name=$service_name&basic_cost=$basic_cost&service_tax=$service_tax&net_amount=$net_amount&travel_cost=$travel_cost&for=$for";

							if($app_quot_format == 2){
								$url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_2/fit_quotation_html.php?quotation_id=$quotation_id";
							}
							else if($app_quot_format == 3){
								$url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_3/fit_quotation_html.php?quotation_id=$quotation_id";
							}
							else if($app_quot_format == 4){
								$url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_4/fit_quotation_html.php?quotation_id=$quotation_id";
							}
							else if($app_quot_format == 5){
								$url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_5/fit_quotation_html.php?quotation_id=$quotation_id";
							}
							else if($app_quot_format == 6){
								$url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_6/fit_quotation_html.php?quotation_id=$quotation_id";
							}
							else{
								$url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_1/fit_quotation_html.php?quotation_id=$quotation_id";
							}
							?>
							<tr class="<?= $bg ?>">
								<td><?= ++$count ?></td>
								<td><?= get_quotation_id($row_quotation['quotation_id'],$year) ?></td>
								<td><?= $sq_package_program['package_name'] ?></td>
								<td><?= $row_quotation['customer_name'] ?></td>
								<td><?= get_date_user($row_quotation['quotation_date']) ?></td>
								<td><?= number_format($quotation_cost,2) ?></td>
								<td><?= $emp_name ?></td>
								<td>
									<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
								</td>
								<!-- <td>
									<a onclick="loadOtherPage('<?= $p_url ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
								</td> -->
								<td>
									<a href="javascript:void(0)" id="btn_email_<?= $count ?>" class="btn btn-info btn-sm" onclick="quotation_email_send(this.id, <?= $row_quotation['quotation_id'] ?>,'<?=  $row_quotation['email_id'] ?>','<?=  $row_quotation['mobile_no'] ?>')" title="Send Email"><i class="fa fa-envelope-o"></i></a>
								</td>
								<td>
									<input type="text" id="email_id<?= $count ?>" name="email_id<?= $count ?>" class="form-control" title="Enter Email ID" placeholder="Backoffice Email ID" style="width:150px;">
									<a href="javascript:void(0)" id="btn_email1_<?= $count ?>" class="btn btn-info btn-sm" onclick="quotation_email_send_backoffice(this.id, <?= $row_quotation['quotation_id'] ?>,'email_id<?= $count ?>')"><i class="fa fa-paper-plane-o"></i></a>
								</td>
								<td>
									<form action="update/index.php" id="frm_booking_<?= $count ?>" method="POST">

										<input type="hidden" id="quotation_id" name="quotation_id" value="<?= $row_quotation['quotation_id'] ?>">
										<input type="hidden" id="package_id" name="package_id" value="<?= $row_quotation['package_id'] ?>">

										<button class="btn btn-info btn-sm" title="Edit Quotation"><i class="fa fa-pencil-square-o"></i></button>

									</form>
									</form>
								</td>
								<td>
									<a href="quotation_view.php?quotation_id=<?= $row_quotation['quotation_id'] ?>" target="_BLANK" class="btn btn-info btn-sm" title="View Quotation"><i class="fa fa-eye"></i></a>
								</td>
								<td><button class="btn btn-warning btn-sm" onclick="quotation_clone(<?= $row_quotation['quotation_id'] ?>)" title="Copy"><i class="fa fa-files-o"></i></button></td>
								
								
							</tr>
							<?php

						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<script>
$('#quotation_table').dataTable({
		"pagingType": "full_numbers"
	});
function quotation_email_send(btn_id, quotation_id, email_id, mobile_no)
{
	$('#'+btn_id).button('loading');
	var base_url = $('#base_url').val();
	$.post('send_quotation.php', { email_id : email_id,mobile_no : mobile_no}, function(data){
		$('#div_quotation_form').html(data);
		$('#'+btn_id).button('reset');    
	});

}

function quotation_email_send_backoffice(btn_id1, quotation_id,email_id1)
{
	var base_url = $('#base_url').val();
	var email_id = $('#'+email_id1).val();
	if(email_id == ''){
		error_msg_alert("Enter Backoffice Email please!"); return false;
	}
	$('#'+btn_id1).button('loading');
	$.ajax({
		type:'post',
		url: base_url+'controller/package_tour/quotation/quotation_email_send_backoffice.php',
		data:{ quotation_id : quotation_id , email_id : email_id},
		success: function(message){
				msg_alert(message);
				$('#'+btn_id1).button('reset');        
				quotation_list_reflect();      	
            }  
	});

}
</script>