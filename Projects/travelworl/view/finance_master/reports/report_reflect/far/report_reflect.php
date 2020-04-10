<?php 
include "../../../../../model/model.php"; 
$asset_name = $_POST['asset_name'];
$asset_type = $_POST['asset_type'];
$branch_status = $_POST['branch_status'];
$branch_admin_id = $_POST['branch_admin_id'];
$role = $_POST['role'];
?>
 
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_report_credit" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>asset_type</th>
			<th>Asset_name</th>
			<th>ASset_Ledger_name</th>
			<th>Purchase_Date</th>
			<th>Purchase_Amount</th>
			<th>Opening_Carrying_amount</th>
			<th>Rate_Of_Depreciation</th>
			<th>Depreciation</th>
			<th>Accumulated_Depreciation</th>
			<th>Closing_Carrying_amount</th>
			<th>View</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$count = 1;
	$total_amount = 0;
	$query = "SELECT * FROM `fixed_asset_entries` where 1 "; 
	if($asset_type != ''){
		$query .= " and asset_id in(select entry_id from fixed_asset_master where asset_type ='$asset_type')";
	}
	if($asset_name != ''){
		$query .= " and asset_id in(select entry_id from fixed_asset_master where entry_id ='$asset_name')";
	}
	if($branch_status == 'yes'){
		if($role == 'Branch Admin'){
			$query .= " and branch_admin_id='$branch_admin_id'";
		}
	}
	$query .= "group by asset_ledger order by entry_id desc ";
	$sq_query = mysql_query($query);
	while($row_query = mysql_fetch_assoc($sq_query))
	{
		$sq_asset = mysql_fetch_assoc(mysql_query("select * from fixed_asset_master where entry_id ='$row_query[asset_id]'"));
		$sq_depr = mysql_fetch_assoc(mysql_query("select sum(depr_till_date) as depr_till_date from fixed_asset_entries where asset_id ='$row_query[asset_id]' and asset_ledger ='$row_query[asset_ledger]'"));
		$closing_c_amount = $row_query['purchase_amount'] - $sq_depr['depr_till_date'];

		$sq_latest = mysql_fetch_assoc(mysql_query("select * from fixed_asset_entries where asset_id ='$row_query[asset_id]' and asset_ledger ='$row_query[asset_ledger]' order by entry_id desc"));
		$opening_c_amount = $closing_c_amount + $sq_latest['depr_till_date'];

		if($row_query['sold_amount'] != '0'){ $bg = 'danger'; }else{ $bg = ''; }
		?>
			<tr class="<?= $bg ?>">
				<td style="width:20px"><?= $count++ ?></td>
				<td><?= $sq_asset['asset_type'] ?></td>
				<td><?= $sq_asset['asset_name'] ?></td>
				<td><?php echo $row_query['asset_ledger']; ?></td>
				<td><?php echo get_date_user($row_query['purchase_date']); ?></td>
				<td><?= $row_query['purchase_amount'] ?></td>
				<td><?= number_format($opening_c_amount,2) ?></td>
				<td><?= $sq_latest['rate_of_depr'] ?></td>
				<td><?= $sq_latest['depr_till_date'] ?></td>
				<td><?= $sq_depr['depr_till_date'] ?></td>
				<td><?= number_format($closing_c_amount,2) ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="display_modal('<?= $sq_latest['entry_id'] ?>','<?= $sq_latest['asset_ledger'] ?>','<?= $sq_latest['asset_id'] ?>')" title="View"><i class="fa fa-eye"></i></button>
				</td>
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