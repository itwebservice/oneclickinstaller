<?php 
include "../../../../../model/model.php";
$filter_date = $_POST['filter_date'];
$branch_admin_id = $_POST['branch_admin_id'];

$query = "select * from cash_reconcl_master where 1 ";
if($filter_date != ''){
	$filter_date = get_date_db($filter_date);
	$query .= " and reconcl_date = '$filter_date'";
}
if($branch_admin_id != '0'){
	 $query .= " and branch_admin_id = '$branch_admin_id'";
}
$sq_query = mysql_query($query);
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_cash_reconcl" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>SR.NO</th>
			<th>Date</th>
			<th>Cash as per System</th>
			<th>Cash as per Tills</th>
			<th>Difference</th>
			<th>Reconciliation Amount</th>
			<th>Reconciliation difference</th>
			<th>Admin Approval</th>
		</tr>
	</thead>
	<tbody>	
	<?php 
		$count = 1;
		$bg = '';
		while($row_query = mysql_fetch_assoc($sq_query))
		{		
		if($row_query['approval_status'] == 'true') {  $bg = 'success'; }
		else if($row_query['approval_status'] == 'false')  {  $bg = 'danger'; }
		else{
			 $bg = '';
		}
	?>	 
		<tr class="<?= $bg ?>">
			<td><?= $count++ ?></td>
			<td><?= get_date_user($row_query['reconcl_date']) ?></td>
			<td class="text-right"><?= number_format($row_query['system_cash'],2) ?></td>
			<td class="text-right"><?= number_format($row_query['till_cash'],2) ?></td>
			<td class="text-right"><?= number_format($row_query['diff_prior'],2) ?></td>
			<td class="text-right"><?= number_format($row_query['reconcl_amount'],2) ?></td>
			<td class="text-right"><?= number_format($row_query['diff_reconcl'],2) ?></td>
			<?php if($row_query['approval_status'] == ''){ $class = 'fa fa-check-square-o'; }
			else{  $class = 'fa fa-eye'; }?>
			<td>
				<button class="btn btn-info btn-sm" onclick="display_modal(<?= $row_query['id'] ?>)" title="Admin Approval"><i class="<?= $class ?>"></i></button>
			</td>
		</tr>	
	<?php } ?>		 
	</tbody>
	<tfoot>
		 
	</tfoot>
</table>
</div> </div> </div>
<script>
$('#tbl_cash_reconcl').dataTable({
		"pagingType": "full_numbers"
	});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>