<?php

include "../../../model/model.php";

$active_flag = $_POST['active_flag'];
$email_for = $_POST['email_for'];
$email_type = $_POST['email_type'];

$query = "select * from cms_master_entries where 1 ";

if($active_flag!=""){
	$query .=" and active_flag='$active_flag' ";
}
if($email_for!=""){
	$query .=" and id in(select id from cms_master where id='$email_for') ";
}
if($email_type!=""){
	$query .=" and id in(select id from cms_master where type_id='$email_type') ";
}
?>

<div class="row mg_tp_20"> <div class="col-md-12"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_cms_list" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Type</th>
			<th>Email_for</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>

		<?php 
		$count = 0;
		$sq_cms = mysql_query($query);
		while($row_cms = mysql_fetch_assoc($sq_cms)){
			$sq_cms_name = mysql_fetch_assoc(mysql_query("select * from cms_master where id='$row_cms[id]'"));

			if($sq_cms_name['type_id'] == '1'){ $type = 'Transactional'; }
			elseif($sq_cms_name['type_id'] == '2'){ $type = 'Reminder'; }

			$bg = ($row_cms['active_flag']=="Inactive") ? "danger" : "";
			?>
			<tr class="<?= $bg ?>">
				<td><?= $row_cms['entry_id'] ?></td>
				<td><?= $type ?></td>
				<td><?= $sq_cms_name['draft_for'] ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="update_modal(<?= $row_cms['entry_id'] ?>)" title="Update CMS"><i class="fa fa-pencil-square-o"></i></button>
				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>

</div> </div> </div>

<script>
$('#tbl_cms_list').dataTable({
	"pagingType": "full_numbers"
});
</script>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>