<?php
include "../../../../model/model.php";
/*======******Header******=======*/
include_once('../../../layouts/fullwidth_app_header.php'); 
global $similar_text;
$quotation_id = $_GET['quotation_id'];
$role = $_SESSION['role'];
$sq_quotation = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_master where quotation_id='$quotation_id'"));
$sq_package = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id ='$sq_quotation[package_id]'"));
$quotation_date = $sq_quotation['quotation_date'];
$yr = explode("-", $quotation_date);
$year =$yr[0];

$transport_agency_id = $sq_quotation['transport_agency_id'];
$sq_transport = mysql_fetch_assoc(mysql_query("select * from transport_agency_master where transport_agency_id='$transport_agency_id'"));
$sq_cost =  mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_costing_entries where quotation_id = '$sq_quotation[quotation_id]'"));
$quotation_cost = $sq_quotation['train_cost'] + $sq_quotation['flight_cost'] + $sq_quotation['cruise_cost'] + $sq_quotation['visa_cost'] + $sq_cost['total_tour_cost'] + $sq_quotation['guide_cost'] + $sq_quotation['misc_cost']; 
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
<h3 class="editor_title main_block">Enquiry Details</h3>
<div class="app_panel_content Filter-panel">
	<div class="row">
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Package Name</label> : <?= $sq_package['package_name'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Tour Name</label> : <?= $sq_quotation['tour_name'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>From Date</label> : <?= date('d/m/Y', strtotime($sq_quotation['from_date'])) ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>To Date</label> : <?= date('d/m/Y', strtotime($sq_quotation['to_date'])) ?> </div>
	</div>
	<div class="row">
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Total Days</label> : <?= $sq_quotation['total_days'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Customer Name</label> : <?= $sq_quotation['customer_name'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Adults</label> : <?= $sq_quotation['total_adult'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Childrens</label> : <?= $sq_quotation['total_children'] ?> </div>
	</div>
	<div class="row">
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Infants</label> : <?= $sq_quotation['total_infant'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Total Guest</label> : <?= $sq_quotation['total_passangers'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Children Without Bed</label> : <?= $sq_quotation['children_without_bed'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Children With Bed</label> : <?= $sq_quotation['children_with_bed'] ?> </div>
	</div>
	<div class="row">
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Quotation Date</label> : <?= date('d/m/Y', strtotime($sq_quotation['quotation_date'])) ?> </div>
		<div class="col-md-3 mg_bt_10_xs" style="border-right: 1px solid #ddd;"> <div class="highlighted_cost"><label>Quotation Cost</label> : <?= number_format($quotation_cost,2) ?> </div></div>
		<div class="col-md-3" style="border-right: 1px solid #ddd;"> <div class="highlighted_cost"><label>Created By</label> : <?= $emp_name ?> </div></div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label class="highlighted_cost">Quotation ID : <?= get_quotation_id($sq_quotation['quotation_id'],$year) ?> </label></div>
		<?php

        if($sq_quotation['price_str_url']!=""){

        	$newUrl1 = preg_replace('/(\/+)/','/',$sq_quotation['price_str_url']); 
        ?>	
		<div class="col-md-3 mg_bt_10"> <label class="highlighted_cost">Price Structure :<a href="<?php echo $newUrl1; ?>" download title="Download Price Structure">&nbsp;&nbsp;&nbsp;<i class="fa fa-download" aria-hidden="true"></i></a></label></div>

        <?php }

        ?>
	</div>
</div>

<!-- Train-->
<?php $sq_t_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_train_entries where quotation_id='$quotation_id'"));
if($sq_t_count != '0'){
?>
<div class="main_block mg_tp_30"></div>
<h3 class="editor_title main_block">Train Details</h3>
<table class="table table-bordered">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Location_From</th>
			<th>Location_To</th>
			<th>Class</th>
			<th>Departure_Date_Time</th>
			<th>Arrival_Date_Time</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_train = mysql_query("select * from package_tour_quotation_train_entries where quotation_id='$quotation_id'");
		while($row_train = mysql_fetch_assoc($sq_train))
		{
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= $row_train['from_location'] ?></td>
				<td><?= $row_train['to_location'] ?></td>
				<td><?= $row_train['class'] ?></td>
				<td><?= get_datetime_user($row_train['departure_date']) ?></td>
				<td><?= get_datetime_user($row_train['arrival_date']) ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
<?php } ?>

<!-- Flight-->
<?php $sq_f_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_plane_entries where quotation_id='$quotation_id'"));
if($sq_f_count != '0'){
?>
<div class="main_block mg_tp_30"></div>
<h3 class="editor_title main_block">Flight Details</h3>
<table class="table table-bordered">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Sector_From</th>
			<th>Sector_To</th>
			<th>Airline_Name</th>
			<th>Class</th>
			<th>Departure_Date_Time</th>
			<th>Arrival_Date_Time</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_train = mysql_query("select * from package_tour_quotation_plane_entries where quotation_id='$quotation_id'");
		while($row_train = mysql_fetch_assoc($sq_train))
		{
			$sq_airline = mysql_fetch_assoc(mysql_query("select * from airline_master where airline_id='$row_train[airline_name]'"));
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= $row_train['from_location'] ?></td>
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
<?php } ?>

<?php $sq_c_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_cruise_entries where quotation_id='$quotation_id'"));
if($sq_c_count != '0'){
?>
<!-- Cruise -->
<div class="main_block mg_tp_30"></div>
<h3 class="editor_title main_block">Cruise Details</h3>
<table class="table table-bordered">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Departure_Date_Time</th>
			<th>Arrival_Date_Time</th>
			<th>Route</th>
			<th>Cabin</th>
			<th>Sharing</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_train = mysql_query("select * from package_tour_quotation_cruise_entries where quotation_id='$quotation_id'");
		while($row_train = mysql_fetch_assoc($sq_train))
		{
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= get_datetime_user($row_train['dept_datetime']) ?></td>
				<td><?= get_datetime_user($row_train['arrival_datetime']) ?></td>
				<td><?= $row_train['route'] ?></td>
				<td><?= ($row_train['cabin']) ?></td>
				<td><?= ($row_train['sharing']) ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
<?php } ?>

<?php $sq_h_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_hotel_entries where quotation_id='$quotation_id'"));
if($sq_h_count != '0'){
?>
<!-- Hotel -->
<div class="main_block mg_tp_30"></div>
<h3 class="editor_title main_block">Hotel Details</h3>
<table class="table table-bordered">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>City</th>
			<th>Hotel_Name</th>
			<th>Hotel_Type</th>
			<th>Total_Nights</th>
			<th>Total_Rooms</th>
			<th>Extra_Bed</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_hotel = mysql_query("select * from package_tour_quotation_hotel_entries where quotation_id='$quotation_id'");
		while($row_hotel = mysql_fetch_assoc($sq_hotel))
		{
			$hotel_name = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$row_hotel[hotel_name]'"));
			$city_name = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_hotel[city_name]'"));
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= $city_name['city_name'] ?></td>
				<td><?= $hotel_name['hotel_name'].$similar_text ?></td>
				<td><?= $row_hotel['hotel_type'] ?></td>
				<td><?= $row_hotel['total_days'] ?></td>
				<td><?= $row_hotel['total_rooms'] ?></td>
				<td><?= $row_hotel['extra_bed'] ?></td>
			</tr>
			<?php
		}	
		?>
	</tbody>
</table>
<?php } ?>

<?php $sq_t_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_transport_entries2 where quotation_id='$quotation_id'"));
if($sq_t_count != '0'){
?>
<!-- Transport -->
<div class="main_block mg_tp_30"></div>
<h3 class="editor_title main_block">Transport Details</h3>
<table class="table table-bordered">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Vehicle_Name</th>
			<th>Start_Date</th>
			<th>End_Date</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_hotel = mysql_query("select * from package_tour_quotation_transport_entries2 where quotation_id='$quotation_id'");
		while($row_hotel = mysql_fetch_assoc($sq_hotel))
		{
			$transport_name = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id='$row_hotel[vehicle_name]'"));
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= $transport_name['bus_name'].$similar_text ?></td>
				<td><?= date('d-m-Y', strtotime($row_hotel['start_date'])) ?></td>
				<td><?= date('d-m-Y', strtotime($row_hotel['end_date'])) ?></td>
			</tr>
			<?php
		}	
		?>
	</tbody>
</table>
<?php } ?>

<?php $sq_e_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_excursion_entries where quotation_id='$quotation_id'"));
if($sq_e_count != '0'){
?>
<div class="main_block mg_tp_30"></div>
<h3 class="editor_title main_block">Excursion Details</h3>
<table class="table table-bordered">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>City_Name</th>
			<th>Excursion_Name</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_ex = mysql_query("select * from package_tour_quotation_excursion_entries where quotation_id='$quotation_id'");
		while($row_ex = mysql_fetch_assoc($sq_ex))
		{
			$sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_ex[city_name]'"));
			$sq_ex_name = mysql_fetch_assoc(mysql_query("select * from itinerary_paid_services where service_id='$row_ex[excursion_name]'"));
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= $sq_city['city_name'] ?></td>
				<td><?= $sq_ex_name['service_name'] ?></td>
				<td><?= $row_ex['excursion_amount'] ?></td>
			</tr>
			<?php
		}	
		?>
	</tbody>
</table>
<?php } ?>

<div class="main_block mg_tp_30"></div>
<h3 class="editor_title main_block">Itinerary Details</h3>
<table class="table table-bordered">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Special_Attraction</th>
			<th>Day-wise_Program</th>
			<th>Overnight_Stay</th>
			<th>Meal_Plan</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		//$sq_hotel = mysql_query("select * from package_tour_quotation_transport_entries2 where quotation_id='$quotation_id'");
		$sq_package_program = mysql_query("select * from package_quotation_program where quotation_id='$quotation_id'");
		while($row_itinarary = mysql_fetch_assoc($sq_package_program)){
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= $row_itinarary['attraction'] ?></td>
				<td><pre class="real_text"><?= $row_itinarary['day_wise_program'] ?></pre></td>
				<td><?= $row_itinarary['stay'] ?></td>
				<td><?= $row_itinarary['meal_plan'] ?></td>
			</tr>
			<?php
		}	
		?>
	</tbody>
</table>


<?php 
$count = 0;

$row_hotel = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_costing_entries where quotation_id='$quotation_id'"));
$tour_cost = $row_hotel['tour_cost'] + $row_hotel['transport_cost'] + $row_hotel['markup_cost'] + $row_hotel['excursion_cost'];
?>
<div class="row mg_tp_30">
<div class="col-md-12">
<h3 class="editor_title">Costing Details</h3>
	<div class="table-responsive">
	<table class="table table-hover table-bordered no-marg">
		<thead>
		<?php if($sq_quotation['costing_type'] == 1){ ?>
			<tr class="table-heading-row">
				<?php if($role != 'B2b'){		 ?>
				<th>Hotel</th>
				<th>Transport</th>
				<th>Excursion</th>
				<?php if($row_hotel['markup_cost'] != '0'){ ?>
				<th>Markup(%)</th> <?php } ?>
				<th>Markup_Cost</th>
				<?php } 
				else{ ?>
				<th>Tour</th>
			    <?php } ?>
				<th>Tax</th>
				<th>Total_Tour</th>
				<th>Train</th>
				<th>Flight</th>
				<th>Cruise</th>
				<th>TOTAL_TRAVEL</th>
				<th>Visa</th>
				<th>Guide</th>
				<th>Misc</th>
			</tr>
		</thead>
		<tbody>
				<tr>
					<?php if($role != 'B2b'){		 ?>
					<td><?= number_format($row_hotel['tour_cost'],2) ?></td>
					<td><?= number_format($row_hotel['transport_cost'],2) ?></td>
					<td><?= number_format($row_hotel['excursion_cost'],2) ?></td>
					<?php if($row_hotel['markup_cost'] != '0'){ ?>
					<td><?= $row_hotel['markup_cost'] ?></td> <?php } ?>
					<td><?= number_format($row_hotel['markup_subtotal'],2) ?></td>
					<?php } 
					else{ ?>
					<td><?= number_format($tour_cost,2) ?></td>
					<?php } ?>
					<td><?= number_format($row_hotel['service_tax_subtotal'],2) ?></td>
					<td><?= number_format($row_hotel['total_tour_cost'],2) ?></td>
					<td><?= number_format($sq_quotation['train_cost'],2)  ?></td>
					<td><?= number_format($sq_quotation['flight_cost'],2)  ?></td>
					<td><?= number_format($sq_quotation['cruise_cost'],2)  ?></td>
					<td><?= number_format($sq_quotation['train_cost'] + $sq_quotation['flight_cost'] + $sq_quotation['cruise_cost'],2)  ?></td>
					<td><?= number_format($sq_quotation['visa_cost'],2)  ?></td>
					<td><?= number_format($sq_quotation['guide_cost'],2)  ?></td>
					<td><?= number_format($sq_quotation['misc_cost'],2)  ?></td>
				</tr>
		</tbody>
		<?php }else{?>
			<tr class="table-heading-row">
				<th>Adult Cost</th>
				<th>Child Cost</th>
				<th>Infant Cost</th>
				<th>Child_with_Bed_Cost</th>
				<th>Child_w/o_Cost</th>
				<th>Extra_Bed_Cost</th>
			</tr>
		</thead>
		<tbody>
				<tr>
					<td><?= number_format($row_hotel['adult_cost'],2) ?></td>
					<td><?= number_format($row_hotel['child_cost'],2) ?></td>
					<td><?= number_format($row_hotel['infant_cost'],2)  ?></td>
					<td><?= number_format($row_hotel['child_with'],2)  ?></td>
					<td><?= number_format($row_hotel['child_without'],2)  ?></td>
					<td><?= number_format($row_hotel['extra_bedc'],2)  ?></td>
				</tr>
		</tbody>
		<?php } ?>
	</table>
	</div>
</div>
</div>


<div class="row mg_tp_30">
	<div class="col-md-12">
		<h3 class="editor_title">Inclusions</h3>
		<div class="panel panel-default panel-body app_panel_style">
			<?= $sq_quotation['inclusions'] ?>
		</div>
	</div>
</div>
<div class="row mg_tp_30">
	<div class="col-md-12">
		<h3 class="editor_title">Exclusions</h3>
		<div class="panel panel-default panel-body app_panel_style">
			<?= $sq_quotation['exclusions'] ?>
		</div>
	</div>
</div>
<div class="row mg_tp_30">
	<div class="col-md-12">
		<h3 class="editor_title">Terms & Conditions</h3>
		<div class="panel panel-default panel-body app_panel_style">
			<?php
			$sq_query = mysql_fetch_assoc(mysql_query("select * from terms_and_conditions where type='Package Quotation' and active_flag='Active'")); 
			echo $sq_query['terms_and_conditions'] ?>
		</div>
	</div>
</div>


</div>
<?= end_panel() ?>

<?php
/*======******Footer******=======*/
include_once('../../../layouts/fullwidth_app_footer.php');
?>