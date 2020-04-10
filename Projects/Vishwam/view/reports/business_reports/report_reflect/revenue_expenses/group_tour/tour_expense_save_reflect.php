<?php 
include "../../../../../../model/model.php";
$tour_id = $_POST['tour_id'];
$tour_group_id = $_POST['tour_group_id'];

$total_sale = 0; $total_purchase = 0;

//Sale
$q1 = mysql_query("select *  from tourwise_traveler_details where tour_id='$tour_id' and tour_group_id ='$tour_group_id' ");
while($tourwise_details = mysql_fetch_assoc($q1)){
	$sq_sum = mysql_fetch_assoc(mysql_query("select sum(basic_amount) as incentive_amount from booker_incentive_group_tour where tourwise_traveler_id='$tourwise_details[id]'"));
	$incentive_amount = $sq_sum['incentive_amount'];
	//Cancel consideration
	$sq_tr_refund = mysql_num_rows(mysql_query("select * from refund_traveler_estimate where tourwise_traveler_id='$tourwise_details[id]'"));
	$sq_tour_refund = mysql_num_rows(mysql_query("select * from refund_tour_estimate where tourwise_traveler_id='$tourwise_details[id]'"));
	if($sq_tour_refund == '0' || $sq_tr_refund == '0'){
		$actual_travel_expense = $tourwise_details['total_travel_expense'];
		$actual_tour_expense = $tourwise_details['total_tour_fee'];
		$sale_amount = $actual_travel_expense + $actual_tour_expense - $incentive_amount;
		$tax_amount = $tourwise_details['train_service_tax_subtotal'] + $tourwise_details['plane_service_tax_subtotal'] + $tourwise_details['cruise_service_tax_subtotal'] + $tourwise_details['visa_service_tax_subtotal'] + $tourwise_details['insuarance_service_tax_subtotal'] + $tourwise_details['service_tax'];
		$sale_amount -= $tax_amount;
		$total_sale += $sale_amount;
	}
}

// Purchase
$sq_purchase = mysql_query("select * from vendor_estimate where estimate_type='Group Tour' and estimate_type_id ='$tour_group_id' and status!='Cancel'");
while($row_purchase = mysql_fetch_assoc($sq_purchase)){
	$total_purchase += $row_purchase['net_total'] ;
	$total_purchase -= $row_purchase['service_tax_subtotal'];
}

//Other Expense
$sq_other_purchase = mysql_fetch_assoc(mysql_query("select sum(amount) as amount_total from group_tour_estimate_expense where tour_id='$tour_id' and tour_group_id ='$tour_group_id'"));
$total_purchase += $sq_other_purchase['amount_total'];


//Revenue & Expenses
$result = $total_sale - $total_purchase;

if($total_sale > $total_purchase){
	$var = 'Total Profit';
}else{
	$var = 'Total Loss';
}
$profit_loss = $total_sale - $total_purchase;
?>

<div class="main_block mg_bt_30">
	<div class="col-sm-4 mg_bt_10 no-pad-sm">
		<div class="widget_parent-bg-img bg-green mg_bt_10_sm_xs">
			<div class="widget_parent">
				<div class="row">
			         <div class="widget col-sm-12">
			            <div class="title success-col">
			               <span class="succes_name">Total Sale</span> : <span class="succes_count"><?= number_format($total_sale,2) ?></span>
			            </div>
			        </div>    
			    </div>
			    <div class="row"> <div class="col-md-12">
			        <div class="progress mg_bt_0">
			          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:<?= 100 ?>%"></div>
			        </div>
			    </div> </div>
			</div>
		</div>		
	</div>
	<div class="col-sm-4 mg_bt_10 no-pad-sm">
		<?php
	        $percent = ($closed_count/$enquirty_count)*100;
	        $percent = round($percent, 2);
	    ?>
	    <div class="widget_parent-bg-img bg-red mg_bt_10_sm_xs">
	    	<div class="widget_parent">
				<div class="row">
			         <div class="widget col-sm-12">
			            <div class="title success-col">
			            	<span class="succes_name">Total Purchase</span> : <span class="succes_count"><?= number_format($total_purchase,2) ?></span>
			            </div>
			        </div>
			    </div>
			    <div class="row"> <div class="col-md-12">
			        <div class="progress mg_bt_0">
			          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:<?= 100 ?>%"></div>
			        </div>
			    </div></div>
			</div>
	    </div>
	</div>
	<div class="col-sm-4 mg_bt_10 no-pad-sm">
		<?php
		   $profit_loss_per = 0;
		   $profit_amount = $total_sale - $total_purchase;
		   $profit_loss_per = ($profit_amount / $total_sale) * 100;
	       $profit_loss_per = round($profit_loss_per, 2);
	    ?>
	    <div class="widget_parent-bg-img bg-img-purp mg_bt_10_sm_xs">
	    	<div class="widget_parent">
				<div class="row">
			         <div class="widget col-sm-12">
			            <div class="title success-col">
			            	<span class="succes_name"><?= $var ?></span> : <span class="succes_count"><?= number_format($profit_loss,2) ?></span>
			            </div>
			        </div>    
			    </div>
			    <div class="row"><div class="col-md-12">
			       <div class="widget-badge">
			            <div class="label label-warning">+ <?= $profit_loss_per ?> %</div>&nbsp;&nbsp;
			        </div> 
			    </div></div>
			    <div class="row"> <div class="col-md-12">
			        <div class="progress mg_bt_0">
			          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:<?= $profit_loss_per ?>%"></div>
			        </div>
			    </div> </div>
			</div>
	    </div>
	</div>
</div>

<div class="row mg_tp_30"> <div class="col-md-12"> <div class="table-responsive">
<h3 class="editor_title">Sale/Purchase History</h3>
	<table class="table table-bordered no-marg">
		<thead>
			<tr class="active table-heading-row">
				<th>S_No.</th>
				<th>Booking_ID</th>
				<th>Booking_date</th>
				<th>Amount</th>
				<th>User_Name</th>
				<th>Purchase/Expenses</th>
				<th>Other_Expense</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$count = 1;
		$q1 = mysql_query("select *  from tourwise_traveler_details where tour_id='$tour_id' and tour_group_id ='$tour_group_id' ");
		while($row_query = mysql_fetch_assoc($q1)){
			$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_query[emp_id]'"));
			$emp = ($row_query['emp_id'] == 0)?'Admin': $sq_emp['first_name'].' '.$sq_emp['last_name']; 

			$actual_travel_expense = $row_query['total_travel_expense'];
			$actual_tour_expense = $row_query['total_tour_fee'];
			$sale_amount = $actual_travel_expense + $actual_tour_expense - $incentive_amount;
			$tax_amount = $row_query['train_service_tax_subtotal'] + $row_query['plane_service_tax_subtotal'] + $row_query['cruise_service_tax_subtotal'] + $row_query['visa_service_tax_subtotal'] + $row_query['insuarance_service_tax_subtotal'] + $row_query['service_tax'];
			$sale_amount -= $tax_amount;

			$date = $row_query['form_date'];
			$yr = explode("-", $date);
			$year =$yr[0];
			?>
				<tr>
					<td><?= $count++ ?></td>
					<td><?= get_group_booking_id($row_query['id'],$year) ?></td>
					<td><?= get_date_user($row_query['form_date']) ?></td>
					<td><?= number_format($sale_amount,2) ?></td>
					<td><?= $emp ?></td>
					<td><button class="btn btn-info btn-sm" onclick="view_purchase_modal('<?= $tour_id ?>','<?= $tour_group_id?>')" title="View Purchase"><i class="fa fa-eye"></i></button></td>
					<td><button class="btn btn-info btn-sm" onclick="other_expnse_modal('<?= $tour_id ?>','<?= $tour_group_id?>')" title="Add Other Miscellaneous amount"><i class="fa fa-plus"></i></button></td>
				</tr>
			<?php
		} ?>
		</tbody>
	</table>
</div></div></div>

<div id="other_expnse_display"></div>

<script>
function view_purchase_modal(tour_id,tour_group_id)
{
	var base_url = $('#base_url').val();
	$.post(base_url+'view/reports/business_reports/report_reflect/revenue_expenses/group_tour/view_purchase_modal.php', { tour_id : tour_id, tour_group_id : tour_group_id}, function(data){
		$('#other_expnse_display').html(data);
	});
}
function other_expnse_modal(tour_id,tour_group_id)
{
	var base_url = $('#base_url').val();
	$.post(base_url+'view/reports/business_reports/report_reflect/revenue_expenses/group_tour/other_expnse_modal.php', { tour_id : tour_id, tour_group_id : tour_group_id }, function(data){
		$('#other_expnse_display').html(data);
	});
}
</script>