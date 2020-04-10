<?php
include "../../../../../model/model.php";
/*======******Header******=======*/
include_once('../../../../layouts/fullwidth_app_header.php'); 

$quotation_id = $_GET['quotation_id'];
$role = $_SESSION['role'];

$sq_quotation = mysql_fetch_assoc(mysql_query("select * from flight_quotation_master where quotation_id='$quotation_id'"));
$sq_login = mysql_fetch_assoc(mysql_query("select * from roles where id='$sq_quotation[login_id]'"));
$sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$sq_login[emp_id]'"));

if($sq_emp_info['first_name']==''){
	$emp_name = 'Admin';
}
else{
	$emp_name = $sq_emp_info['first_name'].' '.$sq_emp_info['last_name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Online Booking</title>	

	<?php admin_header_scripts(); ?>

</head>
<input type="hidden" id="base_url" name="base_url" value="<?= BASE_URL ?>">

<?= begin_panel('Quotation View') ?>
<div class="container">


<div class="main_block mg_tp_30"></div>
<h3 class="editor_title main_block">Enquiry Information</h3>
<div class="app_panel_content Filter-panel">
	<div class="row">
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Customer Name</label> : <?= $sq_quotation['customer_name'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Email ID</label> : <?=  $sq_quotation['email_id']  ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Mobile No</label> : <?=  $sq_quotation['mobile_no'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Travel Date/Time</label> : <?= get_datetime_user($sq_quotation['traveling_date']) ?> </div>
	</div>
	<div class="row">
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Sector From</label> : <?= $sq_quotation['sector_from'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Sector To</label> : <?= $sq_quotation['sector_to'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Preferred Airline</label> : <?= $sq_quotation['preffered_airline'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Class Type</label> : <?= $sq_quotation['class_type'] ?> </div>
	</div>
	<div class="row">
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Trip Type</label> : <?= $sq_quotation['trip_type'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Total Seats</label> : <?= $sq_quotation['total_seats'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Quotation Date</label> : <?= get_date_user($sq_quotation['quotation_date']) ?> </div>
	</div>
	<div class="row">
		<div class="col-md-3 mg_bt_10_xs" style="border-right: 1px solid #ddd;"> <div class="highlighted_cost"><label>Quotation Cost</label> : <?= number_format($sq_quotation['quotation_cost'],2) ?> </div></div>
		<div class="col-md-3" style="border-right: 1px solid #ddd;"> <div class="highlighted_cost"><label>Created By</label> : <?= $emp_name ?> </div></div>
	</div>
</div>

<div class="main_block mg_tp_30"></div>
<h3 class="editor_title main_block">Flight Information</h3>
<table class="table table-bordered">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>From_City</th>
			<th>Sector_From</th>
			<th>To_City</th>
			<th>Sector_To</th>
			<th>Airline</th>
			<th>Class</th>
			<th>Departure_Date</th>
			<th>Arrival_Date</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_plane= mysql_query("select * from flight_quotation_plane_entries where quotation_id='$quotation_id'");
		while($row_train = mysql_fetch_assoc($sq_plane))
		{
			$sq_airline = mysql_fetch_assoc(mysql_query("select * from airline_master where airline_id='$row_train[airline_name]'"));
			$sq_city = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$row_train[from_city]'"));
		    $sq_city1 = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$row_train[to_city]'"));
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?php echo $sq_city['city_name']; ?></td>
				<td><?php echo $row_train['from_location']; ?></td>
				<td><?php echo $sq_city1['city_name']; ?></td>
				<td><?= $row_train['to_location'] ?></td>
				<td><?= $sq_airline['airline_name'].' ('.$sq_airline['airline_code'].')' ?></td>
				<td><?= $row_train['class'] ?></td>
				<td><?= get_datetime_user($row_train['dapart_time']) ?></td>
				<td><?= get_datetime_user($row_train['arraval_time']) ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>

<div class="main_block mg_tp_30"></div>
<h3 class="editor_title main_block">Costing Information</h3>
<table class="table table-bordered">
	<thead>
		<tr class="table-heading-row">
			<th>Subtotal</th>			
			<?php if($sq_quotation['markup_cost'] != '0'){ ?>
			<th>Markup Cost(%)</th> <?php } ?>
			<th>Markup Cost Subtotal</th>
			<th>Tax</th>
			<th>Quotation_cost</th>
		</tr>
	</thead>
	<tbody>
			<tr>
				<td><?= number_format($sq_quotation['subtotal'],2) ?></td>		
				<?php if($sq_quotation['markup_cost'] != '0'){ ?>
				<td><?= number_format($sq_quotation['markup_cost'],2) ?></td> <?php } ?>
				<td><?= number_format($sq_quotation['markup_cost_subtotal'],2) ?></td>
				<td><?= number_format($sq_quotation['service_tax_subtotal'],2) ?></td>
				<td><?= number_format($sq_quotation['quotation_cost'],2) ?></td>
			</tr>
	</tbody>
</table>
	
</div>
<?= end_panel() ?>

</script>
<?php
/*======******Footer******=======*/
include_once('../../../../layouts/fullwidth_app_footer.php');
?>