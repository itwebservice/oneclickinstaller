<?php 

include "../../../../model/model.php";

//$login_id = $_SESSION['login_id'];

$city_id = $_POST['city_id'];

$hotel_id = $_POST['hotel_id'];

$from_date = $_POST['from_date'];

$to_date  = $_POST['to_date'];



$query = "select * from hotel_vendor_price_master where 1 ";

if($city_id != ''){

	$query .= " and city_id = '$city_id'";

}

if($hotel_id != ''){

	$query .= " and hotel_id = '$hotel_id'";

}

if($from_date != '' && $to_date != ''){

	$from_date1 = date('Y-m-d', strtotime($from_date));

	$to_date1 = date('Y-m-d', strtotime($to_date));

	$query .= "  and pricing_id in(select pricing_id from hotel_vendor_price_list where from_date between '$from_date1' and '$to_date1')";

}

$sq_query_login = mysql_query($query);

?>

<div class="row"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-hover bg_white" id="tbl_req_list" style="margin: 20px 0 !important;">

		<thead>

			<tr class="table-heading-row">

				<th>S_No.</th>

				<th>Hotel</th>

				<th>Room_Category</th>

				<th>Valid_From</th>

				<th>Valid_To </th>

				<th>Single_Bed</th>

				<th>Double_Bed</th>

				<th>Triple_Bed</th>

				<th>Extra_Bed</th>

				<th>Meal_Plan</th>

				<th>Edit</th>

			</tr>

		</thead>

		<tbody>

			<?php 

			$count = 0;

			while($row_req1 = mysql_fetch_assoc($sq_query_login)){

			$sq_req = mysql_query("select * from hotel_vendor_price_list where pricing_id = '$row_req1[pricing_id]'");

			while($row_req = mysql_fetch_assoc($sq_req)){

				

				$sq_hotel = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$row_req1[hotel_id]'"));

				$sq_query = mysql_fetch_assoc(mysql_query("select * from hotel_vendor_price_master where pricing_id = '$row_req[pricing_id]'"));

				$sq_currency = mysql_fetch_assoc(mysql_query("select * from currency_name_master where id = '$sq_query[currency_id]'"));

				?>

				<tr class="<?= $bg ?>">

					<td><?= ++$count ?></td>

					<td><?= $sq_hotel['hotel_name'] ?></td>

					<td><?= $row_req['without_bed_cost'] ?></td>

					<td><?=  date('d-m-Y', strtotime($row_req['from_date'])) ?></td>

					<td><?= date('d-m-Y', strtotime($row_req['to_date'])) ?></td>

					<td><?= $row_req['single_bed_cost'] ?></td>

					<td><?= $row_req['double_bed_cost'] ?></td>

					<td><?= $row_req['triple_bed_cost'] ?></td>

					<td><?= $row_req['with_bed_cost'] ?></td>

					<td><?= $row_req['meal_plan'] ?></td>

					<td>

						<button class="btn btn-info btn-sm" onclick="vendor_price_edit_modal(<?= $row_req['entry_id'] ?>)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

					</td>

				</tr>

				<?php

			}

			}

			?>

		</tbody>

	</table>
</div></div></div>



<script>

$('#tbl_req_list').dataTable();

</script>

