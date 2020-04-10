<?php 
include_once('../../../../model/model.php');

include_once('../../inc/vendor_generic_functions.php');

$estimate_type = $_POST['estimate_type'];
$vendor_type = $_POST['vendor_type'];
$estimate_type_id = $_POST['estimate_type_id'];
$vendor_type_id = $_POST['vendor_type_id'];
$emp_id = $_SESSION['emp_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$branch_status = $_POST['branch_status']; 
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];

$query = "select * from vendor_estimate where financial_year_id='$financial_year_id' ";
if($estimate_type!=""){
	$query .= "and estimate_type='$estimate_type'";
}
if($vendor_type!=""){
	$query .= "and vendor_type='$vendor_type'";
}
if($estimate_type_id!=""){
	$query .= "and estimate_type_id='$estimate_type_id'";
}
if($vendor_type_id!=""){
	$query .= "and vendor_type_id='$vendor_type_id'";
}
include "../../../../model/app_settings/branchwise_filteration.php";
$query .= " order by estimate_id desc";
?>

<div class="row mg_tp_20"> 
	<div class="col-md-12 no-pad"> 
	 <div class="table-responsive">
			<table class="table table-hover" id="tbl_estimate_list" style="margin: 20px 0 !important;">
				<thead>
					<tr class="active table-heading-row">
						<th>S_No.</th>
						<th>Purchase_Type</th>
						<th>Purchase_ID</th>
						<th>Supplier_Type</th>
						<th>Supplier_Name</th>
						<th>Remark</th>
						<th class="info">Amount</th>
						<th class="danger">cncl_Amount</th>
						<th class="info">Total</th>
						<th>Edit</th>
						<th>Invoice</th>
						<th>Cancel</th>
						<th>Created_by</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$total_estimate_amt = 0;
					$count = 0;
					$sq_estimate = mysql_query($query);
					while($row_estimate = mysql_fetch_assoc($sq_estimate)){
						$sq_emp =  mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_estimate[emp_id]'"));
						$emp_name = ($row_estimate['emp_id'] != 0) ? $sq_emp['first_name'].' '.$sq_emp['last_name'] : 'Admin';
						$date = $row_estimate['purchase_date'];
						$yr = explode("-", $date);
						$year =$yr[0];
						$total_estimate_amt = $total_estimate_amt + $row_estimate['net_total'];
						$total_cancel_amt += $row_estimate['cancel_amount'];

						$estimate_type_val = get_estimate_type_name($row_estimate['estimate_type'], $row_estimate['estimate_type_id']);
						$vendor_type_val = get_vendor_name($row_estimate['vendor_type'], $row_estimate['vendor_type_id']);

						$purchase_amount=$row_estimate['net_total']-$row_estimate['cancel_amount'];
						$total_purchase_amt += $purchase_amount;

						$sq_paid_amount_query = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from vendor_payment_master where vendor_type='$row_estimate[vendor_type]' and vendor_type_id='$row_estimate[vendor_type_id]' and estimate_type='$row_estimate[estimate_type]' and estimate_type_id='$row_estimate[estimate_type_id]' and clearance_status!='Pending' and clearance_status!='Cancelled'"));
						$total_paid_amt += $sq_paid_amount_query['sum'];
					    if($total_paid_amt==""){ $total_paid_amt = 0; }

						$bg = ($row_estimate['status']=="Cancel") ? "danger" : "";

						$newUrl = $row_estimate['invoice_proof_url'];
						if($newUrl!=""){
							$newUrl = preg_replace('/(\/+)/','/',$row_estimate['invoice_proof_url']); 
							$newUrl_arr = explode('uploads/', $newUrl);
							$newUrl = BASE_URL.'uploads/'.$newUrl_arr[1];	
						}						
						
						?>
						<tr class="<?= $bg ?>">
							<td><?= ++$count ?></td>
							<td><?= $row_estimate['estimate_type'] ?></td>
							<td><?= $estimate_type_val ?></td>
							<td><?= $row_estimate['vendor_type'] ?></td>
							<td><?= $vendor_type_val ?></td>
							<td><?= $row_estimate['remark'] ?></td>
							<td class="info"><?= $row_estimate['net_total'] ?></td>
							<td class="danger"><?= ($row_estimate['cancel_amount']=="") ? 0 : $row_estimate['cancel_amount'] ?></td>
							<td class="info"><?= number_format($purchase_amount, 2); ?></td>
							<td>
								<button class="btn btn-info btn-sm" onclick="vendor_estimate_update_modal(<?= $row_estimate['estimate_id'] ?>)" title="Edit Entry"><i class="fa fa-pencil-square-o"></i></button>
							</td>	
							<td>
								<?php if($newUrl!=""){ ?>
									<a class="btn btn-info btn-sm" href="<?php echo $newUrl; ?>" download  title="Download Invoice"><i class="fa fa-download"></i></a>
								<?php } ?>								
							</td>
							<?php if($bg == 'danger'){?>
								<td><?= 'Cancelled' ?></td>
							<?php } else { ?>
							<td>
								<button class="btn btn-danger btn-sm" onclick="vendor_estimate_cancel(<?= $row_estimate['estimate_id'] ?>)" title="Cancel Entry"><i class="fa fa-ban"></i></button>
						    </td>
						    <?php } ?>
							<td><?= $emp_name ?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
				<tfoot>
					<tr class="active">
						<th colspan="4" class="text-right info">Total Amount : <?= number_format($total_estimate_amt, 2); ?></th>
						<th colspan="2" class="text-right danger">Total Cancel : <?= number_format($total_cancel_amt, 2); ?></th>
						<th colspan="2" class="text-right info">Total Purchase : <?= number_format($total_purchase_amt, 2); ?></th>
						<th colspan="2" class="text-right success">Total Paid : <?= number_format($total_paid_amt, 2); ?></th>
						<th colspan="3" class="text-right warning">Balance : <?= number_format(($total_estimate_amt - $total_cancel_amt - $total_paid_amt), 2); ?></th>		
					</tr>
				</tfoot>	
			</table>
		</div>
		</div>
</div>
<div id="div_vendor_estimate_update"></div>
<div id="div_vendor_payment_content"></div>
<script>
$('#tbl_estimate_list').dataTable({
		"pagingType": "full_numbers"
});
function vendor_estimate_update_modal(estimate_id)
{
	$.post('estimate/vendor_estimate_update_modal.php', { estimate_id : estimate_id }, function(data){
		$('#div_vendor_estimate_update').html(data);
	});
}
function vendor_payment_modal(estimate_id)
{
	$.post('payment/vendor_payment_modal.php', { estimate_id : estimate_id }, function(data){
		$('#div_vendor_payment_content').html(data);
	});
}
function vendor_estimate_cancel(estimate_id)
{
	$('#vi_confirm_box').vi_confirm_box({
        message: 'Are you sure?',
      	callback: function(data1){
          if(data1=="yes"){
            
              $.ajax({
                type: 'post',
                url: base_url()+'controller/vendor/dashboard/estimate/cancel_estimate.php',
                data:{ estimate_id : estimate_id },
                success: function(result){
                  msg_alert(result);
                  vendor_estimate_list_reflect();
                }
              });

          }
        }
  	});
}
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>