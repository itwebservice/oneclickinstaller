<?php 
include "../../../model/model.php";
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];
?>

<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
	
<table id="tbl_sms_group" class="table table-bordered" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Email_ID_group</th>
			<th>Edit</th>
		</tr>
		
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$query = "select * from email_group_master where 1";
		if($branch_status=='yes' && $role=='Branch Admin'){
	      $query .=" and branch_admin_id = '$branch_admin_id'";
	    }
		$sq_sms_group = mysql_query($query);
		 
		while($row_sms_group = mysql_fetch_assoc($sq_sms_group)){
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= $row_sms_group['email_group_name'] ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="email_group_edit_modal(<?= $row_sms_group['email_group_id'] ?>)" title="Edit"><i class="fa fa-pencil-square-o"></i></button>
				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>

</div> </div> </div>

<div id="div_sms_group_edit_content"></div>

<script>
	$('#tbl_sms_group').dataTable({"pagingType": "full_numbers"});	
</script>