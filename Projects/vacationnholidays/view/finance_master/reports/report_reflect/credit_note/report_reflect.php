<?php 
include "../../../../../model/model.php"; 
$to_date = $_POST['to_date'];
$branch_status = $_POST['branch_status'];
$branch_admin_id = $_POST['branch_admin_id'];
$role = $_POST['role'];
?>
 
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_report_credit" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Date</th>
			<th>Customer_name</th>
			<th>Booking_type</th>
			<th>Booking_Id</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$count = 1;
	$total_amount = 0;
	$query = "SELECT * FROM `credit_note_master` where 1 "; 
	if($to_date != ''){
		$to_date = get_date_db($to_date);
		$query .= " and created_at <= '$to_date'";
	}
	if($branch_status == 'yes'){
		if($role == 'Branch Admin'){
			$query .= " and branch_admin_id='$branch_admin_id'";
		}
	}
	$sq_query = mysql_query($query);
	while($row_query = mysql_fetch_assoc($sq_query))
	{
		$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_query[customer_id]'"));
		if($sq_cust['type']=='Corporate'){
			$cust_name = $sq_cust['company_name'];
		}else{
			$cust_name = $sq_cust['first_name'].' '.$sq_cust['last_name'];
		}
		?>
			<tr>
				<td style="width:20px"><?= $count++ ?></td>
				<td><?= get_date_user($row_query['created_at']) ?></td>
				<td><?= $cust_name ?></td>
				<td><?php echo $row_query['module_name']; ?></td>
				<td><?php echo $row_query['module_entry_id']; ?></td>
				<td class="text-right success"><?= $row_query['payment_amount'] ?></td>
			</tr>
		<?php 
	} ?>		 
	</tbody>
</table>
</div> </div> </div>
<script>
$('#tbl_report_credit').dataTable({
		"pagingType": "full_numbers"
	});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>