<?php
include "../../model/model.php";
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$active_flag = $_POST['active_flag'];
$cust_type = $_POST['cust_type'];
$company_name = $_POST['company_name'];
$branch_status = $_POST['branch_status'];
$branch_id = $_POST['branch_id'];

$query = "select * from customer_master where 1 ";

if($active_flag!=""){
	$query .=" and active_flag='$active_flag' ";
}
if($cust_type != ""){
	$query .=" and type = '$cust_type' ";
}
if($company_name != ""){
	$query .=" and company_name='$company_name' ";
}
if($branch_status=='yes' && $role!='Admin'){
	$query .= " and branch_admin_id = '$branch_admin_id'";
}
if($branch_id!=""){
	$query .= " and branch_admin_id = '$branch_id'";
} 
 
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">

<table class="table table-hover" id="tbl_customer_list" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Customer_Name</th>
			<th>Date_of_birth</th>
			<th>Mobile</th>
			<th>Email ID</th>
			<th>View</th>
			<th>Edit</th>
			<th>Outstanding</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		
		$count = 0;
		$sq_customer = mysql_query($query);
		while($row_customer = mysql_fetch_assoc($sq_customer)){

			$bg = ($row_customer['active_flag']=="Inactive") ? "danger" : "";
			$masked =  str_pad(substr($row_customer['contact_no'], -4), strlen($row_customer['contact_no']), '*', STR_PAD_LEFT);
			$masked_email =  str_pad(substr($row_customer['email_id'], 4), strlen($row_customer['email_id']), '*', STR_PAD_LEFT);
			$birth_date =  ($row_customer['birth_date'] == '1970-01-01') ? 'NA': get_date_user($row_customer['birth_date']);
			$masked_email1 =  ($masked_email == "") ? 'NA' : $masked_email;
			?>
		
			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= $row_customer['first_name'].' '.$row_customer['last_name']  ?></td>
				<td><?=  $birth_date ?></td>
				<td>
				<span onclick="showNum(<?= $count ?>);" id="phone-y<?= $count?>" class="row_value phone"><?= $masked ?></span><span id="phone-x<?= $count?>" class="hidden" ><?= $row_customer['contact_no'];?></span>
				</td>
				<td>
				<span onclick="showEmail(<?= $count ?>);" id="phone-ye<?= $count?>" class="row_value phone"><?= $masked_email1 ?></span><span id="phone-xe<?= $count?>" class="hidden" ><?= $row_customer['email_id'] ?></span>
				</td>
				<td>
					<button class="btn btn-info btn-sm" onclick="customer_display_modal(<?= $row_customer['customer_id'] ?>)" title="View customer"><i class="fa fa-eye"></i></button>
				</td>				
				<td>
					<button class="btn btn-info btn-sm" onclick="customer_update_modal(<?= $row_customer['customer_id'] ?>)" title="Edit customer"><i class="fa fa-pencil-square-o"></i></button>
				</td>
				<td>
					<button class="btn btn-info btn-sm" onclick="customer_history_modal(<?= $row_customer['customer_id'] ?>)" title="View"><i class="fa fa-print"></i></button>
				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>	

</div> </div> </div>
<div id="div_customer_update_modal"></div>
<script>
$('#tbl_customer_list').dataTable({
		"pagingType": "full_numbers"
	});
function customer_update_modal(customer_id)
{
	$.post('customer_update_modal.php', { customer_id : customer_id }, function(data){
		$('#div_customer_update_modal').html(data);
	})
}
function customer_display_modal(customer_id)
{
	$.post('view/index.php', { customer_id : customer_id }, function(data){
		$('#div_customer_update_modal').html(data);
	})
}
function customer_history_modal(customer_id)
{
	$.post('customer_history_modal.php', { customer_id : customer_id }, function(data){
		$('#div_customer_update_modal').html(data);
	})
}
</script>