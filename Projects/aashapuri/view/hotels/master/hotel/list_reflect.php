<?php

include "../../../../model/model.php";

$active_flag = $_POST['active_flag'];
$city_id = $_POST['city_id'];
$query = "select * from hotel_master where 1 ";

if($active_flag!=""){
	$query .=" and active_flag='$active_flag' ";
}

if($city_id!=""){
	$query .=" and city_id='$city_id' ";
}

?>

<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_hotel_list" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Hotel</th>
			<th>City</th>
			<th>Mobile</th>
			<th>Contact_Person</th>
			<!-- <th>Address</th> -->
			<th>View</th>
			<!--<th>Tariff</th> -->
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>

		<?php 
		$count = 0;
		$sq_hotel = mysql_query($query);
		while($row_hotel = mysql_fetch_assoc($sq_hotel)){
			$sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_hotel[city_id]'"));
			$sq_gl = mysql_fetch_assoc(mysql_query("select * from gl_master where gl_id='$row_hotel[gl_id]'"));
			$bg = ($row_hotel['active_flag']=="Inactive") ? "danger" : "";
			?>
			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= ucfirst($row_hotel['hotel_name']) ?></td>
				<td><?= ucfirst($sq_city['city_name']) ?></td>
				<td><?= $row_hotel['mobile_no'] ?></td>
				<td><?= $row_hotel['contact_person_name'] ?></td>
				<!-- <td style="width:220px"><?= $row_hotel['hotel_address'] ?></td> -->
				<td>
					<button class="btn btn-info btn-sm" onclick="view_modal(<?= $row_hotel['hotel_id'] ?>)" title="Supplier Information"><i class="fa fa-eye"></i></button>
				</td>
				<!--<td>
					<button class="btn btn-info btn-sm" onclick="view_tarrif_modal(<?= $row_hotel['hotel_id'] ?>)" title="Tariff Information"><i class="fa fa-money" aria-hidden="true"></i></button>
				</td> -->
				<td>
					<button class="btn btn-info btn-sm" onclick="update_modal(<?= $row_hotel['hotel_id'] ?>)" title="Edit Hotel"><i class="fa fa-pencil-square-o"></i></button>
				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>

</div> </div> </div>

<script>
	$('#tbl_hotel_list').dataTable({
		"pagingType": "full_numbers"
	});
</script>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>