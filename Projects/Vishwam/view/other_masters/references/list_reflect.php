<?php
include_once("../../../model/model.php");
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
	
<table class="table table-hover" id="tbl_list" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>Reference_Id</th>
			<th>Reference</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_ref = mysql_query("select * from references_master");
		while($row_ref = mysql_fetch_assoc($sq_ref)){
			$bg = ($row_ref['active_flag']=="Inactive") ? "danger" : "";
			?>
			<tr class="<?= $bg ?>">
				<td><?= $row_ref['reference_id'] ?></td>
				<td><?= $row_ref['reference_name'] ?></td>
				<td>
					<?php if($row_ref['reference_id']!='1'&&$row_ref['reference_id']!='2'&&$row_ref['reference_id']!='3'){?>
						<button class="btn btn-info btn-sm" onclick="update_modal(<?= $row_ref['reference_id'] ?>)" title="Edit Reference"><i class="fa fa-pencil-square-o"></i></button>
					<?php } else{ ?>
					<span><?= 'NA' ?></span><?php } ?>
				</td>
			</tr>
			<?php

		}
		?>
	</tbody>
</table>

</div> </div> </div>

<script>
$('#tbl_list').dataTable({
		"pagingType": "full_numbers"
	});
</script>