<?php

include "../../../model/model.php";
$city_id = $_POST['city_id'];
$active_flag = $_POST['active_flag'];
$query = "select * from site_seeing_vendor where 1 ";
if($city_id!=""){
	$query .=" and city_id='$city_id'";
}
if($active_flag!=""){
	$query .=" and active_flag='$active_flag' ";
}
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_vendor_list" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Company_Name</th>
			<th>City</th>
			<th>Mobile</th>
			<th>Contact_Person</th>
			<th>Address</th>
			<th>View</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_vendor = mysql_query($query);
		while($row_vendor = mysql_fetch_assoc($sq_vendor)){
			$sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_vendor[city_id]'"));
			$sq_gl = mysql_fetch_assoc(mysql_query("select * from gl_master where gl_id='$row_vendor[gl_id]'"));
			$bg = ($row_vendor['active_flag']=="Inactive") ? "danger" : "";
			?>
			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= $row_vendor['vendor_name'] ?></td>
				<td><?= $sq_city['city_name'] ?></td>
				<td><?= $row_vendor['mobile_no'] ?></td>
				<td><?= $row_vendor['concern_person_name'] ?></td>
				<td style="width:220px"><?= $row_vendor['address'] ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="view_modal(<?= $row_vendor['vendor_id'] ?>)" title="View Information"><i class="fa fa-eye"></i></button>
				</td>
				<td>
					<button class="btn btn-info btn-sm" onclick="update_modal(<?= $row_vendor['vendor_id'] ?>)" title="Edit Detail"><i class="fa fa-pencil-square-o"></i></button>
				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
</div> </div> </div>
<script>
$('#tbl_vendor_list').dataTable({
		"pagingType": "full_numbers"
	});
</script>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>