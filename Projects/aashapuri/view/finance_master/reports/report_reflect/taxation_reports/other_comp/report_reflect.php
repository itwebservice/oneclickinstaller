<?php include "../../../../../../model/model.php"; 
$till_date = $_POST['till_date'];
$branch_status = $_POST['branch_status'];
$role = $_POST['role'];
$branch_admin_id = $_POST['branch_admin_id'];

$query = "select * from other_complaince_master where 1 ";
if($till_date != ''){
	$till_date = get_date_db($till_date);
	$query .= " and comp_date <= '$till_date'";
}
if($branch_status=='yes'){
	if($role=='Branch Admin'){
		$query .= " and branch_admin_id='$branch_admin_id'";
	}
}
$sq_query = mysql_query($query);
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_other_comp" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>Sr.no</th>
			<th>Compliance Name</th>
			<th>Statute Name</th>
			<th>Due Date</th>
			<th>Payment</th>
			<th>Responsible Person</th>
			<th>Description</th>
			<th>Date_Complied on</th>
		</tr>
	</thead>
	<tbody>
		 <?php
		 $count = 1;
		 while($row_query = mysql_fetch_assoc($sq_query)){ ?>
			<tr>
				<td><?= $count++ ?></td>
				<td><?= $row_query['comp_name'] ?></td>
				<td><?= $row_query['under_statue'] ?></td>
				<td><?= get_date_user($row_query['due_date']) ?></td>
				<td><?= $row_query['payment'] ?></td>
				<td><?= $row_query['resp_person'] ?></td>
				<td><?= $row_query['description'] ?></td>
				<td> <?php if($row_query['comp_date'] == '0000-00-00'){ ?>
					<button class="btn btn-info btn-sm" onclick="update_modal(<?= $row_query['id'] ?>)" title="Complied Date"><i class="fa fa-eye"></i></button>
				<?php } else{ ?>
				    <?= get_date_user($row_query['comp_date']) ?>
				<?php } ?>
				</td>
			</tr>
		<?php } ?> 
	</tbody>
	<tfoot>
		 
	</tfoot>
</table>
</div> </div> </div>
<script>
$('#tbl_other_comp').dataTable({
		"pagingType": "full_numbers"
	});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>