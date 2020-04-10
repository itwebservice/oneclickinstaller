<?php
$profit_loss_per = 0;
if($sale_type == 'Visa'){ ?>
<div class="row mg_tp_20"> 
	<div class="col-md-12"> <div class="table-responsive">
	
		<table class="table table-bordered no-marg" id="sale_history_table">
			<thead>
				<tr class="active table-heading-row">
					<th>S_No.</th>
					<th>booking_id</th>
					<th>Booking_date</th>
					<th>User_Name</th>
					<th>Sale_amount</th>
					<th>Supplier_type</th>
					<th>Supplier_name</th>
					<th>Purchase_amount</th>
					<th>Profit/Loss(%)</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$count = 1;
			$sq_query = mysql_query("select * from visa_master order by visa_id desc");
			while ($row_visa = mysql_fetch_assoc($sq_query)) {

				$date = $row_visa['created_at'];
				$yr = explode("-", $date);
				$year =$yr[0];

				$sq_visa_entry = mysql_num_rows(mysql_query("select * from visa_master_entries where visa_id='$row_visa[visa_id]'"));
				$sq_visa_cancel = mysql_num_rows(mysql_query("select * from visa_master_entries where visa_id='$row_visa[visa_id]' and status = 'Cancel'"));
				$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_visa[emp_id]'"));
				$emp = ($row_query['emp_id'] == 0)?'Admin': $sq_emp['first_name'].' '.$sq_emp['last_name']; 

				//Purchase 
				$sq_pquery = mysql_fetch_assoc(mysql_query("select sum(net_total) as net_total,sum(service_tax_subtotal) as service_tax_subtotal from vendor_estimate where estimate_type='Visa Booking' and estimate_type_id ='$row_visa[visa_id]' and status!='Cancel'"));
				$vendor_name = get_vendor_name_report($sq_pquery['vendor_type'],$sq_pquery['vendor_type_id']);

				$total_sale = $row_visa['visa_total_cost'] - $row_visa['service_tax_subtotal'];
				$total_purchase = $sq_pquery['net_total']-$sq_pquery['service_tax_subtotal'];
				$profit_amount = $total_sale - $total_purchase;
				$profit_loss_per = ($profit_amount / $total_sale) * 100;
				$profit_loss_per = round($profit_loss_per, 2);
				$var = ($total_sale > $total_purchase) ? 'Profit':'Loss';

				if($sq_visa_entry != $sq_visa_cancel){ 	
				?>
					<tr>
						<td><?= $count++ ?></td>
						<td><?= get_visa_booking_id($row_visa['visa_id'],$year) ?></td>
						<td><?= get_date_user($row_visa['created_at']) ?></td>
						<td><?= $emp ?></td>
						<td><?= number_format($total_sale,2) ?></td>
						<td><?= ($sq_pquery['vendor_type'] !='')?$sq_pquery['vendor_type']:'NA' ?></td>
						<td><?= ($vendor_name !='')?$vendor_name:'NA'  ?></td>
						<td><?= number_format($total_purchase,2) ?></td>
						<td><?= $profit_loss_per.'%('.$var.')' ?></td>
					</tr>
				<?php } 
			} ?>
			</tbody>
		</table>

	</div></div>
</div>
<?php } ?>
<?php
if($sale_type == 'Passport'){ ?>
<div class="row mg_tp_20"> 
	<div class="col-md-12"> <div class="table-responsive">
	
		<table class="table table-bordered no-marg" id="sale_history_table">
			<thead>
				<tr class="active table-heading-row">
					<th>S_No.</th>
					<th>booking_id</th>
					<th>Booking_date</th>
					<th>User_Name</th>
					<th>Sale_amount</th>
					<th>Supplier_type</th>
					<th>Supplier_name</th>
					<th>Purchase_amount</th>
					<th>Profit/Loss(%)</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$count = 1;
			$sq_passport = mysql_query("select * from passport_master order by passport_id desc");
			while ($row_passport = mysql_fetch_assoc($sq_passport)) {

				$date = $row_passport['created_at'];
	            $yr = explode("-", $date);
	            $year =$yr[0];

				$sq_passport_entry = mysql_num_rows(mysql_query("select * from passport_master_entries where passport_id='$row_passport[passport_id]'"));
				$sq_passport_cancel = mysql_num_rows(mysql_query("select * from passport_master_entries where passport_id='$row_passport[passport_id]' and status = 'Cancel'"));
				$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_passport[emp_id]'"));
				$emp = ($row_query['emp_id'] == 0)?'Admin': $sq_emp['first_name'].' '.$sq_emp['last_name']; 

				//Purchase 
				$sq_pquery = mysql_fetch_assoc(mysql_query("select sum(net_total) as net_total,sum(service_tax_subtotal) as service_tax_subtotal from vendor_estimate where estimate_type='Passport Booking' and estimate_type_id ='$row_passport[passport_id]' and status!='Cancel'"));
				$vendor_name = get_vendor_name_report($sq_pquery['vendor_type'],$sq_pquery['vendor_type_id']);

				$total_sale = $row_passport['passport_total_cost'] - $row_passport['service_tax_subtotal'];
				$total_purchase = $sq_pquery['net_total']-$sq_pquery['service_tax_subtotal'];
				$profit_amount = $total_sale - $total_purchase;
				$profit_loss_per = ($profit_amount / $total_sale) * 100;
				$profit_loss_per = round($profit_loss_per, 2);
				$var = ($total_sale > $total_purchase) ? 'Profit':'Loss';
				if($sq_passport_entry != $sq_passport_cancel){ 
				?>
					<tr>
						<td><?= $count++ ?></td>
						<td><?= get_passport_booking_id($row_passport['passport_id'],$year) ?></td>
						<td><?= get_date_user($row_passport['created_at']) ?></td>
						<td><?= $emp ?></td>
						<td><?= number_format($total_sale,2) ?></td>
						<td><?= ($sq_pquery['vendor_type'] !='')?$sq_pquery['vendor_type']:'NA' ?></td>
						<td><?= ($vendor_name !='')?$vendor_name:'NA'  ?></td>
						<td><?= number_format($total_purchase,2) ?></td>
						<td><?= $profit_loss_per.'%('.$var.')' ?></td>
					</tr>
				<?php } 
			} ?>
			</tbody>
		</table>

	</div></div>
</div>
<?php } ?>

<?php
if($sale_type == 'Excursion'){ ?>
<div class="row mg_tp_20"> 
	<div class="col-md-12"> <div class="table-responsive">
	
		<table class="table table-bordered no-marg" id="sale_history_table">
			<thead>
				<tr class="active table-heading-row">
					<th>S_No.</th>
					<th>booking_id</th>
					<th>Booking_date</th>
					<th>User_Name</th>
					<th>Sale_amount</th>
					<th>Supplier_type</th>
					<th>Supplier_name</th>
					<th>Purchase_amount</th>
					<th>Profit/Loss(%)</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$count = 1;
			$sq_passport = mysql_query("select * from excursion_master order by exc_id desc");
			while ($row_passport = mysql_fetch_assoc($sq_passport)) {

				$date = $row_passport['created_at'];
	            $yr = explode("-", $date);
	            $year =$yr[0];

				$sq_exc_entry = mysql_num_rows(mysql_query("select * from excursion_master_entries where exc_id='$row_passport[exc_id]'"));
				$sq_exc_cancel = mysql_num_rows(mysql_query("select * from excursion_master_entries where exc_id='$row_passport[exc_id]' and status = 'Cancel'"));
				$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_passport[emp_id]'"));
				$emp = ($row_query['emp_id'] == 0)?'Admin': $sq_emp['first_name'].' '.$sq_emp['last_name']; 

				//Purchase 
				$sq_pquery = mysql_fetch_assoc(mysql_query("select sum(net_total) as net_total,sum(service_tax_subtotal) as service_tax_subtotal from vendor_estimate where estimate_type='Excursion Booking' and estimate_type_id ='$row_passport[exc_id]' and status!='Cancel'"));
				$vendor_name = get_vendor_name_report($sq_pquery['vendor_type'],$sq_pquery['vendor_type_id']);

				$total_sale = $row_passport['exc_total_cost'] - $row_passport['service_tax_subtotal'];
				$total_purchase = $sq_pquery['net_total']-$sq_pquery['service_tax_subtotal'];
				$profit_amount = $total_sale - $total_purchase;
				$profit_loss_per = ($profit_amount / $total_sale) * 100;
				$profit_loss_per = round($profit_loss_per, 2);
				$var = ($total_sale > $total_purchase) ? 'Profit':'Loss';
				if($sq_exc_entry != $sq_exc_cancel){ 
				?>
					<tr>
						<td><?= $count++ ?></td>
						<td><?= get_exc_booking_id($row_passport['exc_id'],$year) ?></td>
						<td><?= get_date_user($row_passport['created_at']) ?></td>
						<td><?= $emp ?></td>
						<td><?= number_format($total_sale,2) ?></td>
						<td><?= ($sq_pquery['vendor_type'] !='')?$sq_pquery['vendor_type']:'NA' ?></td>
						<td><?= ($vendor_name !='')?$vendor_name:'NA'  ?></td>
						<td><?= number_format($total_purchase,2) ?></td>
						<td><?= $profit_loss_per.'%('.$var.')' ?></td>
					</tr>
				<?php } 
			} ?>
			</tbody>
		</table>

	</div></div>
</div>
<?php } ?>
<?php
if($sale_type == 'Forex'){ ?>
<div class="row mg_tp_20"> 
	<div class="col-md-12"> <div class="table-responsive">
	
		<table class="table table-bordered no-marg" id="sale_history_table">
			<thead>
				<tr class="active table-heading-row">
					<th>S_No.</th>
					<th>booking_id</th>
					<th>Booking_date</th>
					<th>User_Name</th>
					<th>Sale_amount</th>
					<th>Supplier_type</th>
					<th>Supplier_name</th>
					<th>Purchase_amount</th>
					<th>Profit/Loss(%)</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$count = 1;
			$sq_passport = mysql_query("select * from forex_booking_master order by booking_id desc");
			while ($row_passport = mysql_fetch_assoc($sq_passport)) {
				$date = $row_passport['created_at'];
	            $yr = explode("-", $date);
				$year =$yr[0];
				
				$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_passport[emp_id]'"));
				$emp = ($row_query['emp_id'] == 0)?'Admin': $sq_emp['first_name'].' '.$sq_emp['last_name']; 

				//Purchase 
				$sq_pquery = mysql_fetch_assoc(mysql_query("select sum(net_total) as net_total,sum(service_tax_subtotal) as service_tax_subtotal from vendor_estimate where estimate_type='Forex Booking' and estimate_type_id ='$row_passport[booking_id]' and status!='Cancel'"));
				$vendor_name = get_vendor_name_report($sq_pquery['vendor_type'],$sq_pquery['vendor_type_id']);

				$total_sale = $row_passport['net_total'] - $row_passport['service_tax_subtotal'];
				$total_purchase = $sq_pquery['net_total']-$sq_pquery['service_tax_subtotal'];
				$profit_amount = $total_sale - $total_purchase;
				$profit_loss_per = ($profit_amount / $total_sale) * 100;
				$profit_loss_per = round($profit_loss_per, 2);
				$var = ($total_sale > $total_purchase) ? 'Profit':'Loss';
				?>
					<tr>
						<td><?= $count++ ?></td>
						<td><?= get_forex_booking_id($row_passport['booking_id'],$year) ?></td>
						<td><?= get_date_user($row_passport['created_at']) ?></td>
						<td><?= $emp ?></td>
						<td><?= number_format($total_sale,2) ?></td>
						<td><?= ($sq_pquery['vendor_type'] !='')?$sq_pquery['vendor_type']:'NA' ?></td>
						<td><?= ($vendor_name !='')?$vendor_name:'NA'  ?></td>
						<td><?= number_format($total_purchase,2) ?></td>
						<td><?= $profit_loss_per.'%('.$var.')' ?></td>
					</tr>
				<?php 
			} ?>
			</tbody>
		</table>

	</div></div>
</div>
<?php } ?>
<?php
if($sale_type == 'Bus'){ ?>
<div class="row mg_tp_20"> 
	<div class="col-md-12"> <div class="table-responsive">
	
		<table class="table table-bordered no-marg" id="sale_history_table">
			<thead>
				<tr class="active table-heading-row">
					<th>S_No.</th>
					<th>booking_id</th>
					<th>Booking_date</th>
					<th>User_Name</th>
					<th>Sale_amount</th>
					<th>Supplier_type</th>
					<th>Supplier_name</th>
					<th>Purchase_amount</th>
					<th>Profit/Loss(%)</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$count = 1;
			$sq_passport = mysql_query("select * from bus_booking_master order by booking_id desc");
			while ($row_passport = mysql_fetch_assoc($sq_passport)) {
				$date = $row_passport['created_at'];
	            $yr = explode("-", $date);
	            $year =$yr[0];
				$sq_exc_entry = mysql_num_rows(mysql_query("select * from bus_booking_entries where booking_id='$row_passport[booking_id]'"));
				$sq_exc_cancel = mysql_num_rows(mysql_query("select * from bus_booking_entries where booking_id='$row_passport[booking_id]' and status = 'Cancel'"));
				
				$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_passport[emp_id]'"));
				$emp = ($row_query['emp_id'] == 0)?'Admin': $sq_emp['first_name'].' '.$sq_emp['last_name']; 

				//Purchase 
				$sq_pquery = mysql_fetch_assoc(mysql_query("select sum(net_total) as net_total,sum(service_tax_subtotal) as service_tax_subtotal from vendor_estimate where estimate_type='Bus Booking' and estimate_type_id ='$row_passport[booking_id]' and status!='Cancel'"));
				$vendor_name = get_vendor_name_report($sq_pquery['vendor_type'],$sq_pquery['vendor_type_id']);

				$total_sale = $row_passport['net_total'] - $row_passport['service_tax_subtotal'];
				$total_purchase = $sq_pquery['net_total']-$sq_pquery['service_tax_subtotal'];
				$profit_amount = $total_sale - $total_purchase;
				$profit_loss_per = ($profit_amount / $total_sale) * 100;
				$profit_loss_per = round($profit_loss_per, 2);
				$var = ($total_sale > $total_purchase) ? 'Profit':'Loss';
				if($sq_exc_entry != $sq_exc_cancel){ 
				?>
					<tr>
						<td><?= $count++ ?></td>
						<td><?= get_bus_booking_id($row_passport['booking_id'],$year) ?></td>
						<td><?= get_date_user($row_passport['created_at']) ?></td>
						<td><?= $emp ?></td>
						<td><?= number_format($total_sale,2) ?></td>
						<td><?= ($sq_pquery['vendor_type'] !='')?$sq_pquery['vendor_type']:'NA' ?></td>
						<td><?= ($vendor_name !='')?$vendor_name:'NA'  ?></td>
						<td><?= number_format($total_purchase,2) ?></td>
						<td><?= $profit_loss_per.'%('.$var.')' ?></td>
					</tr>
				<?php }
			} ?>
			</tbody>
		</table>

	</div></div>
</div>
<?php } ?>
<?php
if($sale_type == 'Hotel'){ ?>
<div class="row mg_tp_20"> 
	<div class="col-md-12"> <div class="table-responsive">
	
		<table class="table table-bordered no-marg" id="sale_history_table">
			<thead>
				<tr class="active table-heading-row">
					<th>S_No.</th>
					<th>booking_id</th>
					<th>Booking_date</th>
					<th>User_Name</th>
					<th>Sale_amount</th>
					<th>Supplier_type</th>
					<th>Supplier_name</th>
					<th>Purchase_amount</th>
					<th>Profit/Loss(%)</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$count = 1;
			$sq_passport = mysql_query("select * from hotel_booking_master order by booking_id desc");
			while ($row_passport = mysql_fetch_assoc($sq_passport)) {

				$date = $row_passport['created_at'];
	            $yr = explode("-", $date);
	            $year =$yr[0];

				$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_passport[emp_id]'"));
				$emp = ($row_query['emp_id'] == 0)?'Admin': $sq_emp['first_name'].' '.$sq_emp['last_name']; 

				//Purchase 
				$sq_pquery = mysql_fetch_assoc(mysql_query("select sum(net_total) as net_total,sum(service_tax_subtotal) as service_tax_subtotal from vendor_estimate where estimate_type='Hotel Booking' and estimate_type_id ='$row_passport[booking_id]' and status!='Cancel'"));
				$vendor_name = get_vendor_name_report($sq_pquery['vendor_type'],$sq_pquery['vendor_type_id']);

				$total_sale = $row_passport['total_fee'] - $row_passport['service_tax_subtotal'];
				$total_purchase = $sq_pquery['net_total']-$sq_pquery['service_tax_subtotal'];
				$profit_amount = $total_sale - $total_purchase;
				$profit_loss_per = ($profit_amount / $total_sale) * 100;
				$profit_loss_per = round($profit_loss_per, 2);
				$var = ($total_sale > $total_purchase) ? 'Profit':'Loss';

				$sq_exc_entry = mysql_num_rows(mysql_query("select * from hotel_booking_entries where booking_id='$row_passport[booking_id]'"));
				$sq_exc_cancel = mysql_num_rows(mysql_query("select * from hotel_booking_entries where booking_id='$row_passport[booking_id]' and status = 'Cancel'"));
				if($sq_exc_entry != $sq_exc_cancel){
				?>
					<tr>
						<td><?= $count++ ?></td>
						<td><?= get_hotel_booking_id($row_passport['booking_id'],$year) ?></td>
						<td><?= get_date_user($row_passport['created_at']) ?></td>
						<td><?= $emp ?></td>
						<td><?= number_format($total_sale,2) ?></td>
						<td><?= ($sq_pquery['vendor_type'] !='')?$sq_pquery['vendor_type']:'NA' ?></td>
						<td><?= ($vendor_name !='')?$vendor_name:'NA'  ?></td>
						<td><?= number_format($total_purchase,2) ?></td>
						<td><?= $profit_loss_per.'%('.$var.')' ?></td>
					</tr>
				<?php } 
			} ?>
			</tbody>
		</table>

	</div></div>
</div>
<?php } ?>

<?php
if($sale_type == 'Car Rental'){ ?>
<div class="row mg_tp_20"> 
	<div class="col-md-12"> <div class="table-responsive">
	
		<table class="table table-bordered no-marg" id="sale_history_table">
			<thead>
				<tr class="active table-heading-row">
					<th>S_No.</th>
					<th>booking_id</th>
					<th>Booking_date</th>
					<th>User_Name</th>
					<th>Sale_amount</th>
					<th>Supplier_type</th>
					<th>Supplier_name</th>
					<th>Purchase_amount</th>
					<th>Profit/Loss(%)</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$count = 1;
			$sq_passport = mysql_query("select * from car_rental_booking where status != 'Cancel' order by booking_id desc");
			while ($row_passport = mysql_fetch_assoc($sq_passport)) {

				$date = $row_passport['created_at'];
	            $yr = explode("-", $date);
	            $year =$yr[0];
				$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_passport[emp_id]'"));
				$emp = ($row_query['emp_id'] == 0)?'Admin': $sq_emp['first_name'].' '.$sq_emp['last_name']; 

				//Purchase 
				$sq_pquery = mysql_fetch_assoc(mysql_query("select sum(net_total) as net_total,sum(service_tax_subtotal) as service_tax_subtotal from vendor_estimate where estimate_type='Car Rental' and estimate_type_id ='$row_passport[booking_id]' and status!='Cancel'"));
				$vendor_name = get_vendor_name_report($sq_pquery['vendor_type'],$sq_pquery['vendor_type_id']);

				$total_sale = $row_passport['total_fees'] - $row_passport['service_tax_subtotal'];
				$total_purchase = $sq_pquery['net_total']-$sq_pquery['service_tax_subtotal'];
				$profit_amount = $total_sale - $total_purchase;
				$profit_loss_per = ($profit_amount / $total_sale) * 100;
				$profit_loss_per = round($profit_loss_per, 2);
				$var = ($total_sale > $total_purchase) ? 'Profit':'Loss';

				?>
					<tr>
						<td><?= $count++ ?></td>
						<td><?= get_car_rental_booking_id($row_passport['booking_id'],$year) ?></td>
						<td><?= get_date_user($row_passport['created_at']) ?></td>
						<td><?= $emp ?></td>
						<td><?= number_format($total_sale,2) ?></td>
						<td><?= ($sq_pquery['vendor_type'] !='')?$sq_pquery['vendor_type']:'NA' ?></td>
						<td><?= ($vendor_name !='')?$vendor_name:'NA'  ?></td>
						<td><?= number_format($total_purchase,2) ?></td>
						<td><?= $profit_loss_per.'%('.$var.')' ?></td>
					</tr>
				<?php 
			} ?>
			</tbody>
		</table>

	</div></div>
</div>
<?php } ?>
<?php
if($sale_type == 'Flight Ticket'){ ?>
<div class="row mg_tp_20"> 
	<div class="col-md-12"> <div class="table-responsive">
	
		<table class="table table-bordered no-marg" id="sale_history_table">
			<thead>
				<tr class="active table-heading-row">
					<th>S_No.</th>
					<th>booking_id</th>
					<th>Booking_date</th>
					<th>User_Name</th>
					<th>Sale_amount</th>
					<th>Supplier_type</th>
					<th>Supplier_name</th>
					<th>Purchase_amount</th>
					<th>Profit/Loss(%)</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$count = 1;
			$sq_passport = mysql_query("select * from ticket_master order by ticket_id desc");
			while ($row_passport = mysql_fetch_assoc($sq_passport)) {

				$date = $row_passport['created_at'];
	            $yr = explode("-", $date);
	            $year =$yr[0];
				$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_passport[emp_id]'"));
				$emp = ($row_query['emp_id'] == 0)?'Admin': $sq_emp['first_name'].' '.$sq_emp['last_name']; 

				//Purchase 
				$sq_pquery = mysql_fetch_assoc(mysql_query("select sum(net_total) as net_total,sum(service_tax_subtotal) as service_tax_subtotal from vendor_estimate where estimate_type='Ticket Booking' and estimate_type_id ='$row_passport[ticket_id]' and status!='Cancel'"));
				$vendor_name = get_vendor_name_report($sq_pquery['vendor_type'],$sq_pquery['vendor_type_id']);

				$total_sale = $row_passport['ticket_total_cost'] - $row_passport['service_tax_subtotal'];
				$total_purchase = $sq_pquery['net_total']-$sq_pquery['service_tax_subtotal'];
				$profit_amount = $total_sale - $total_purchase;
				$profit_loss_per = ($profit_amount / $total_sale) * 100;
				$profit_loss_per = round($profit_loss_per, 2);
				$var = ($total_sale > $total_purchase) ? 'Profit':'Loss';

				$sq_exc_entry = mysql_num_rows(mysql_query("select * from ticket_master_entries where ticket_id='$row_passport[ticket_id]'"));
				$sq_exc_cancel = mysql_num_rows(mysql_query("select * from ticket_master_entries where ticket_id='$row_passport[ticket_id]' and status = 'Cancel'"));
				if($sq_exc_entry != $sq_exc_cancel){ 
				?>
					<tr>
						<td><?= $count++ ?></td>
						<td><?= get_ticket_booking_id($row_passport['ticket_id'],$year) ?></td>
						<td><?= get_date_user($row_passport['created_at']) ?></td>
						<td><?= $emp ?></td>
						<td><?= number_format($total_sale,2) ?></td>
						<td><?= ($sq_pquery['vendor_type'] !='')?$sq_pquery['vendor_type']:'NA' ?></td>
						<td><?= ($vendor_name !='')?$vendor_name:'NA'  ?></td>
						<td><?= number_format($total_purchase,2) ?></td>
						<td><?= $profit_loss_per.'%('.$var.')' ?></td>
					</tr>
				<?php } 
			} ?>
			</tbody>
		</table>

	</div></div>
</div>
<?php } ?>

<?php
if($sale_type == 'Train Ticket'){ ?>
<div class="row mg_tp_20"> 
	<div class="col-md-12"> <div class="table-responsive">
	
		<table class="table table-bordered no-marg" id="sale_history_table">
			<thead>
				<tr class="active table-heading-row">
					<th>S_No.</th>
					<th>booking_id</th>
					<th>Booking_date</th>
					<th>User_Name</th>
					<th>Sale_amount</th>
					<th>Supplier_type</th>
					<th>Supplier_name</th>
					<th>Purchase_amount</th>
					<th>Profit/Loss(%)</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$count = 1;
			$sq_passport = mysql_query("select * from train_ticket_master order by train_ticket_id desc");
			while ($row_passport = mysql_fetch_assoc($sq_passport)) {

				$date = $row_passport['created_at'];
				$yr = explode("-", $date);
				$year =$yr[0];
				$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_passport[emp_id]'"));
				$emp = ($row_query['emp_id'] == 0)?'Admin': $sq_emp['first_name'].' '.$sq_emp['last_name']; 

				//Purchase 
				$sq_pquery = mysql_fetch_assoc(mysql_query("select sum(net_total) as net_total,sum(service_tax_subtotal) as service_tax_subtotal from vendor_estimate where estimate_type='Train Ticket Booking' and estimate_type_id ='$row_passport[train_ticket_id]' and status!='Cancel'"));
				$vendor_name = get_vendor_name_report($sq_pquery['vendor_type'],$sq_pquery['vendor_type_id']);

				$total_sale = $row_passport['net_total'] - $row_passport['service_tax_subtotal'];
				$total_purchase = $sq_pquery['net_total']-$sq_pquery['service_tax_subtotal'];
				$profit_amount = $total_sale - $total_purchase;
				$profit_loss_per = ($profit_amount / $total_sale) * 100;
				$profit_loss_per = round($profit_loss_per, 2);
				$var = ($total_sale > $total_purchase) ? 'Profit':'Loss';

				$sq_exc_entry = mysql_num_rows(mysql_query("select * from train_ticket_master_entries where train_ticket_id='$row_passport[train_ticket_id]'"));
				$sq_exc_cancel = mysql_num_rows(mysql_query("select * from train_ticket_master_entries where train_ticket_id='$row_passport[train_ticket_id]' and status = 'Cancel'"));
				if($sq_exc_entry != $sq_exc_cancel){ 
				?>
					<tr>
						<td><?= $count++ ?></td>
						<td><?= get_train_ticket_booking_id($row_passport['train_ticket_id'],$year) ?></td>
						<td><?= get_date_user($row_passport['created_at']) ?></td>
						<td><?= $emp ?></td>
						<td><?= number_format($total_sale,2) ?></td>
						<td><?= ($sq_pquery['vendor_type'] !='')?$sq_pquery['vendor_type']:'NA' ?></td>
						<td><?= ($vendor_name !='')?$vendor_name:'NA'  ?></td>
						<td><?= number_format($total_purchase,2) ?></td>
						<td><?= $profit_loss_per.'%('.$var.')' ?></td>
					</tr>
				<?php } 
			} ?>
			</tbody>
		</table>

	</div></div>
</div>
<?php } ?>
<?php
if($sale_type == 'Miscellaneous'){ ?>
<div class="row mg_tp_20">
	<div class="col-md-12"> <div class="table-responsive">
	
		<table class="table table-bordered no-marg" id="sale_history_table">
			<thead>
				<tr class="active table-heading-row">
					<th>S_No.</th>
					<th>booking_id</th>
					<th>Booking_date</th>
					<th>User_Name</th>
					<th>Sale_amount</th>
					<th>Supplier_type</th>
					<th>Supplier_name</th>
					<th>Purchase_amount</th>
					<th>Profit/Loss(%)</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$count = 1;
			$sq_query = mysql_query("select * from miscellaneous_master order by misc_id desc");
			while ($row_visa = mysql_fetch_assoc($sq_query)){

				$date = $row_visa['created_at'];
				$yr = explode("-", $date);
				$year =$yr[0];
				$sq_visa_entry = mysql_num_rows(mysql_query("select * from miscellaneous_master_entries where misc_id='$row_visa[misc_id]'"));
				$sq_visa_cancel = mysql_num_rows(mysql_query("select * from miscellaneous_master_entries where misc_id='$row_visa[misc_id]' and status = 'Cancel'"));
				$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_visa[emp_id]'"));
				$emp = ($row_query['emp_id'] == 0)?'Admin': $sq_emp['first_name'].' '.$sq_emp['last_name']; 

				//Purchase 
				$sq_pquery = mysql_fetch_assoc(mysql_query("select sum(net_total) as net_total,sum(service_tax_subtotal) as service_tax_subtotal from vendor_estimate where estimate_type='Miscellaneous Booking' and estimate_type_id ='$row_visa[misc_id]' and status!='Cancel'"));
				$vendor_name = get_vendor_name_report($sq_pquery['vendor_type'],$sq_pquery['vendor_type_id']);

				$total_sale = $row_visa['misc_total_cost'] - $row_visa['service_tax_subtotal'];
				$total_purchase = $sq_pquery['net_total']-$sq_pquery['service_tax_subtotal'];
				$profit_amount = $total_sale - $total_purchase;
				$profit_loss_per = ($profit_amount / $total_sale) * 100;
				$profit_loss_per = round($profit_loss_per, 2);
				$var = ($total_sale > $total_purchase) ? 'Profit':'Loss';
				if($sq_visa_entry != $sq_visa_cancel){
				?>
					<tr>
						<td><?= $count++ ?></td>
						<td><?= get_misc_booking_id($row_visa['misc_id'],$year) ?></td>
						<td><?= get_date_user($row_visa['created_at']) ?></td>
						<td><?= $emp ?></td>
						<td><?= number_format($total_sale,2) ?></td>
						<td><?= ($sq_pquery['vendor_type'] !='')?$sq_pquery['vendor_type']:'NA' ?></td>
						<td><?= ($vendor_name !='')?$vendor_name:'NA'  ?></td>
						<td><?= number_format($total_purchase,2) ?></td>
						<td><?= $profit_loss_per.'%('.$var.')' ?></td>
					</tr>
				<?php }
			} ?>
			</tbody>
		</table>

	</div></div>
</div>
<?php } ?>