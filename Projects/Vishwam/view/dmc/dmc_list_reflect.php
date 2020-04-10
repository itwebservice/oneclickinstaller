<?php

include "../../model/model.php";

$active_flag = $_POST['active_flag'];
$city_id = $_POST['city_id'];

$query = "select * from dmc_master where 1 ";

if($active_flag!=""){
	$query .=" and active_flag='$active_flag' ";
}
if($city_id!=""){
	$query .=" and city_id='$city_id' ";
}
?>

<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">

	

<table class="table table-bordered table-hover" id="tbl_dmc_list" style="margin: 20px 0 !important;">

	<thead>

		<tr  class="table-heading-row">

			<th>S_No.</th>
			<th>DMC_Name</th>
			<th>City</th>
			<th>Mobile</th>
			<th>Contact_Person</th>	
			<!-- <th>Address</th> -->		
			<th>View</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php 

		$count = 0;

		$sq_dmc = mysql_query($query);

		while($row_dmc = mysql_fetch_assoc($sq_dmc)){



			$sq_gl = mysql_fetch_assoc(mysql_query("select * from gl_master where gl_id='$row_dmc[gl_id]'"));

			$bg = ($row_dmc['active_flag']=="Inactive") ? "danger" : "";
			$sq_city = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$row_dmc[city_id]'"));

			?>

			<tr class="<?= $bg ?>">

				<td><?= ++$count ?></td>

				<td><?= $row_dmc['company_name'] ?></td>
				<td><?= $sq_city['city_name'] ?></td>
				<td><?= $row_dmc['mobile_no'] ?></td>
				<td><?= $row_dmc['contact_person_name'] ?></td>
				<!-- <td><?= $row_dmc['dmc_address'] ?></td> -->
				<td>

					<button class="btn btn-info btn-sm" onclick="dmc_view_modal(<?= $row_dmc['dmc_id'] ?>)" title="Supplier Information"><i class="fa fa-eye"></i></button>

				</td>

				<td>

					<button class="btn btn-info btn-sm" onclick="dmc_update_modal(<?= $row_dmc['dmc_id'] ?>)" title="Edit DMC Detail"><i class="fa fa-pencil-square-o"></i></button>

				</td>

			</tr>

			<?php

		}

		?>

	</tbody>

</table>



</div> </div> </div>



<div id="div_dmc_update"></div>
<div id="div_dmc_view"></div>


<script>

$('#tbl_dmc_list').dataTable({
		"pagingType": "full_numbers"
	});

function dmc_update_modal(dmc_id){

	$.post('dmc_update_modal.php', { dmc_id : dmc_id }, function(data){

		$('#div_dmc_update').html(data);

	});
}

function dmc_view_modal(dmc_id){

	$.post('view_modal.php', { dmc_id : dmc_id }, function(data){

		$('#div_dmc_view').html(data);

	});

}

</script>