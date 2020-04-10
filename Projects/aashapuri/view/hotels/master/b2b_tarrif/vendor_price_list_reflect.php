<?php
include "../../../../model/model.php";

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
$query .= ' order by pricing_id desc';
$sq_query_login = mysql_query($query);
?>

<div class="row"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-hover bg_white" id="tbl_req_list" style="margin: 20px 0 !important;">
		<thead>
			<tr class="table-heading-row">
				<th>S_No.</th>
				<th>City_name</th>
				<th>Hotel_name</th>
				<th>Currency</th>
				<th>View</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$count = 0;
			while($row_req = mysql_fetch_assoc($sq_query_login)){
			$sq_req = mysql_query("select * from hotel_contracted_tarrif where pricing_id = '$row_req[pricing_id]'");
			$sq_hotel = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$row_req[hotel_id]'"));
			$sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_req[city_id]'"));
			$sq_currency = mysql_fetch_assoc(mysql_query("select * from currency_name_master where id='$row_req[currency_id]'"));
				?>
				<tr class="<?= $bg ?>">
					<td><?= ++$count ?></td>
					<td><?= $sq_city['city_name'] ?></td>
					<td><?= $sq_hotel['hotel_name'] ?></td>
					<td><?= $sq_currency['currency_code'] ?></td>
					<td>
						<button class="btn btn-info btn-sm" onclick="view_modal(<?= $row_req['pricing_id'] ?>)" title="Supplier Information"><i class="fa fa-eye"></i></button>
					</td>
					<td>
						<form action="b2b_tarrif/update/index.php" id="frm_booking_<?= $count ?>" method="POST">
							<input type="hidden" id="pricing_id" name="pricing_id" value="<?= $row_req['pricing_id'] ?>">
							<button class="btn btn-info btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
						</form>
					</td>
				</tr>
				<?php } ?>
		</tbody>
	</table>
</div></div></div>
<script>
$('#tbl_req_list').dataTable();
</script>

