<?php
include "../../../model/model.php";
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
	
<table class="table table-bordered" id="tbl_list" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>From</th>
			<th>To</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_financial_year = mysql_query("select * from financial_year");
		while($row_financial_year = mysql_fetch_assoc($sq_financial_year)){

			$bg = ($row_financial_year['active_flag']=="Active") ? "" : "danger";
			?>
			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= get_date_user($row_financial_year['from_date']) ?></td>
				<td><?= get_date_user($row_financial_year['to_date']) ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="update_modal(<?= $row_financial_year['financial_year_id'] ?>)" title="Edit Detail"><i class="fa fa-pencil-square-o"></i></button>
				</td>
			</tr>
			<?php } ?>
	</tbody>
</table>
</div> </div> </div>
<script>
$('#tbl_list').dataTable({
		"pagingType": "full_numbers"
	});
</script>