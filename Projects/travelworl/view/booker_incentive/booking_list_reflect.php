<?php
include "../../model/model.php";

$tour_type_filter = $_POST['tour_type'];
$emp_id_filter = $_POST['emp_id'];
$from_date_filter = $_POST['from_date'];
$to_date_filter = $_POST['to_date'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_status = $_POST['branch_status'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$emp_id_agent = $_SESSION['emp_id'];
$role = $_SESSION['role'];
function dateSort($a,$b){
    $dateA = strtotime($a['booking_date']);
    $dateB = strtotime($b['booking_date']);
    return ($dateA-$dateB);
}


$group_booking_arr = array();

$query1 = "select * from tourwise_traveler_details where 1 and emp_id != '0' and tour_group_status != 'Cancel' ";
if($emp_id_filter!=""){
	$query1 .= " and emp_id='$emp_id_filter' ";	
}
if($from_date_filter!="" && $to_date_filter!=""){
	$from_date_filter = date('Y-m-d', strtotime($from_date_filter));
	$to_date_filter = date('Y-m-d', strtotime($to_date_filter));

	$query1 .= " and date(form_date) between '$from_date_filter' and '$to_date_filter' ";
}
if($role =='B2b'){
	$query1 .= " and emp_id='$emp_id_agent' ";	
}
if($branch_status=='yes' && $role!='Admin'){
	$query .= " and branch_admin_id = '$branch_admin_id'";
}
elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
$query .= " and emp_id='$emp_id'";
}
 
$query1 .= " order by date(form_date) asc";
$sq_group_bookings = mysql_query($query1);
while($row_group_bookings = mysql_fetch_assoc($sq_group_bookings)){


				$date = $row_group_bookings['form_date'];
		         $yr = explode("-", $date);
		         $year =$yr[0];
	$sq_pass_count = mysql_num_rows(mysql_query("select * from travelers_details where traveler_group_id = '$row_group_bookings[traveler_group_id]'"));
	$sq_pass_cancel = mysql_num_rows(mysql_query("select * from travelers_details where traveler_group_id = '$row_group_bookings[traveler_group_id]' and status='Cancel'"));
	if($sq_pass_count != $sq_pass_cancel)
	{
		$tourwise_traveler_id = $row_group_bookings['id'];			
		$emp_id = $row_group_bookings['emp_id'];			
		$tour_id = $row_group_bookings['tour_id'];
		$tour_group_id = $row_group_bookings['tour_group_id'];
		$booking_date = $row_group_bookings['form_date'];
		$tour_type = "Group Tour";
		$file_no = get_group_booking_id($row_group_bookings['id'],$year);

		$sq_booker = mysql_fetch_assoc( mysql_query("select first_name, last_name from emp_master where emp_id='$emp_id'") );
		if($sq_booker['first_name']==''){
			$booker_name = 'Admin';
		}
		else{
			$booker_name = $sq_booker['first_name'].' '.$sq_booker['last_name'];;
		}

		$sq_tour = mysql_fetch_assoc( mysql_query("select tour_name from tour_master where tour_id='$tour_id'") );
		$tour_name = $sq_tour['tour_name'];

		$sq_tour_group = mysql_fetch_assoc( mysql_query("select from_date, to_date from tour_groups where tour_id='$tour_id' and group_id='$tour_group_id'") );
		$tour_group = date('d-m-Y', strtotime($sq_tour_group['from_date']));
		$booking_amount = $row_group_bookings['total_travel_expense'] + $row_group_bookings['total_tour_fee'];

		/////// Purchase ////////
		$total_purchase = 0;
		$purchase_amt = 0;
		$i=0;
		$p_due_date = '';
		$sq_purchase = mysql_query("select * from vendor_estimate where estimate_type='Group Tour' and estimate_type_id='$row_group_bookings[tour_group_id]'");
		while($row_purchase = mysql_fetch_assoc($sq_purchase)){		
			$purchase_amt = $row_purchase['net_total'] - $row_purchase['refund_net_total'];
			$total_purchase = $total_purchase + $purchase_amt;
		}

		$array1 = array(	
						'booking_date' => $booking_date,
						'other' => array(
										'tourwise_traveler_id' => $tourwise_traveler_id,
										'emp_id' => $emp_id,
										'booker_name' => $booker_name,
										'tour_type' => $tour_type,
										'file_no' => $file_no,
										'tour_name' => $tour_name,
										'tour_date' => $tour_group,		
										'booking_amount' => $booking_amount,	
										'total_purchase' => $total_purchase,						
								  )
					   );

		array_push($group_booking_arr, $array1);
	}			

}


$package_booking_arr = array();

$tour_type_filter = $_POST['tour_type'];
$emp_id_filter = $_POST['emp_id'];
$from_date_filter = $_POST['from_date'];
$to_date_filter = $_POST['to_date'];

$query2 = "select * from package_tour_booking_master where 1  and emp_id != '0' and tour_status != 'Cancel'";
if($emp_id_filter!=""){
	$query2 .= " and emp_id='$emp_id_filter'";	
}
if($from_date_filter!="" && $to_date_filter!=""){
	$from_date_filter = date('Y-m-d', strtotime($from_date_filter));
	$to_date_filter = date('Y-m-d', strtotime($to_date_filter));

	$query2 .= " and date(booking_date) between '$from_date_filter' and '$to_date_filter' ";
}
if($role =='B2b'){
	$query2 .= " and emp_id='$emp_id_agent' ";	
}

if($branch_status=='yes' && $role!='Admin'){
	$query .= " and branch_admin_id = '$branch_admin_id'";
}
elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
$query .= " and emp_id='$emp_id'";
}
 
$query2 .= " order by date(booking_date) asc ";
$sq_package_booking = mysql_query($query2);
while($row_package_booking = mysql_fetch_assoc($sq_package_booking)){
	$date = $row_package_booking['booking_date'];
         $yr = explode("-", $date);
         $year =$yr[0];
	$sq_pass_count = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id = '$row_package_booking[booking_id]'"));
	$sq_pass_cancel = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id = '$row_package_booking[booking_id]' and status='Cancel'"));
	if($sq_pass_count != $sq_pass_cancel)
	{
		$booking_id = $row_package_booking['booking_id'];
		$emp_id = $row_package_booking['emp_id'];
		$tour_name = $row_package_booking['tour_name'];
		$tour_date = date('d-m-Y', strtotime($row_package_booking['tour_from_date']));
		$booking_date = $row_package_booking['booking_date'];
		$tour_type = "Package Tour";
		$file_no = get_package_booking_id($row_package_booking['booking_id'],$year);
		$booking_amount = $row_package_booking['total_travel_expense'] + $row_package_booking['actual_tour_expense'];

		/////// Purchase ////////
		$total_purchase = 0;
		$purchase_amt = 0;
		$i=0;
		$p_due_date = '';
		$sq_purchase = mysql_query("select * from vendor_estimate where estimate_type='Package Tour' and estimate_type_id='$row_package_booking[booking_id]'");
		while($row_purchase = mysql_fetch_assoc($sq_purchase)){			
			$purchase_amt = $row_purchase['net_total'] - $row_purchase['refund_net_total'];
			$total_purchase = $total_purchase + $purchase_amt;
		}

		$sq_booker = mysql_fetch_assoc( mysql_query("select first_name, last_name from emp_master where emp_id='$emp_id'") );
		 
		if($sq_booker['first_name']==''){
			$booker_name = 'Admin';
		}
		else{
			$booker_name = $sq_booker['first_name'].' '.$sq_booker['last_name'];;
		}
		$array1 = array(
						'booking_date' => $booking_date,
						'other' => array(
										'booking_id' => $booking_id,
										'emp_id' => $emp_id,
										'booker_name' => $booker_name,
										'tour_type' => $tour_type,
										'file_no' => $file_no,							
										'tour_name' => $tour_name,
										'tour_date' => $tour_date,		
										'booking_amount' => $booking_amount,	
										'total_purchase' => $total_purchase,				
								  )
					   );

		array_push($package_booking_arr, $array1);	
	}

}


if($tour_type_filter=="Group Tour"){
	$booking_array = $group_booking_arr;
}
if($tour_type_filter=="Package Tour"){
	$booking_array = $package_booking_arr;
}
if($tour_type_filter==""){
	$booking_array = array_merge($group_booking_arr,$package_booking_arr);
	usort($booking_array, 'dateSort');
}

$incentive_total = 0; $paid_amount = 0; $balance_amount = 0;
?>
<div class="row mg_tp_20"> <div class="col-xs-12 no-pad"> <div class="table-responsive">
	
<table class="table table-bordered table-hover" id="incentive_table" style="margin: 20px 0 !important;">
	<thead>
		<tr class="active table-heading-row">
			<th>S_No.</th>		
			<th>User_Name</th>
			<th>Tour_Type</th>
			<th>Booking_id</th>
			<th>Tour_Name</th>
			<th>Tour_Date</th>
			<th>Booking_Date</th>
			<th>Booking_Amount</th>
			<th>Purchase_Amount</th>
			<th>Profit/Loss</th>
			<th>Incentive</th>
			<?php if($role== 'Admin' || $role=='Branch Admin'){ ?>
				<th>Add/Edit</th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php 
		foreach($booking_array as $booking_array_item){

			$other_data_arr = $booking_array_item['other'];

			$emp_id = $other_data_arr['emp_id'];

			if($other_data_arr['tour_type']=="Group Tour"){ $row_bg = "warning"; }
			if($other_data_arr['tour_type']=="Package Tour"){ $row_bg = "info"; }
			?>
			<tr class="<?= $row_bg ?>">
				<td><?= ++$count ?></td>
				<td><?= $other_data_arr['booker_name'] ?></td>
				<td><?= $other_data_arr['tour_type'] ?></td>
				<td><?= $other_data_arr['file_no'] ?></td>
				<td><?= $other_data_arr['tour_name'] ?></td>
				<td><?= date('d/m/Y', strtotime($other_data_arr['tour_date'])) ?></td>
				<td><?= date('d/m/Y', strtotime($booking_array_item['booking_date'])) ?></td>
				<td><?= number_format($other_data_arr['booking_amount'],2) ?></td>
				<td><?= number_format($other_data_arr['total_purchase'],2) ?></td>
				<td><?= number_format($other_data_arr['booking_amount'] - $other_data_arr['total_purchase'],2) ?></td>
				<td class="text-center">
					<?php 
					if($other_data_arr['tour_type']=="Group Tour"){ 
						$tourwise_traveler_id = $other_data_arr['tourwise_traveler_id'];
						$incentive_count = mysql_num_rows(mysql_query("select * from booker_incentive_group_tour where tourwise_traveler_id='$tourwise_traveler_id' and emp_id='$emp_id'"));
						if($incentive_count==0){
							echo "N/A";
						}
						else{
							$sq_incentive = mysql_fetch_assoc(mysql_query("select * from booker_incentive_group_tour where tourwise_traveler_id='$tourwise_traveler_id' and emp_id='$emp_id'"));	
							echo $sq_incentive['incentive_amount'];
					

							$incentive_total = $incentive_total + $sq_incentive['incentive_amount'];
						}
					}
					if($other_data_arr['tour_type']=="Package Tour"){ 
						$booking_id = $other_data_arr['booking_id'];
						$incentive_count = mysql_num_rows(mysql_query("select * from booker_incentive_package_tour where booking_id='$booking_id' and emp_id='$emp_id'"));
						if($incentive_count==0){
							echo "N/A";
						}
						else{
							$sq_incentive = mysql_fetch_assoc(mysql_query("select * from booker_incentive_package_tour where booking_id='$booking_id' and emp_id='$emp_id'"));	
							echo $sq_incentive['incentive_amount'];

							$incentive_total = $incentive_total + $sq_incentive['incentive_amount'];
						}
					}
					?>
				</td>
				<?php if($role== 'Admin' || $role=='Branch Admin'){ ?>
				<td class="text-left">
					<?php 
					if($other_data_arr['tour_type']=="Group Tour"){ 
						$tourwise_traveler_id = $other_data_arr['tourwise_traveler_id'];
						$incentive_count = mysql_num_rows(mysql_query("select * from booker_incentive_group_tour where tourwise_traveler_id='$tourwise_traveler_id' and emp_id='$emp_id'"));
						if($incentive_count==0){
							?>
							<a href="javascript:void(0)" onclick="group_tour_incentive_save_modal(<?= $other_data_arr['tourwise_traveler_id'] ?>, <?= $other_data_arr['emp_id'] ?>)" class="btn btn-sm btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</a>
							<?php
						}
						else{
							?>
							<a href="javascript:void(0)" onclick="group_tour_incentive_edit_modal(<?= $other_data_arr['tourwise_traveler_id'] ?>, <?= $other_data_arr['emp_id'] ?>)" class="btn btn-info btn-sm" title="Edit Incentive"><i class="fa fa-pencil-square-o"></i></a>
							<?php
						}
					}
					if($other_data_arr['tour_type']=="Package Tour"){ 
						$booking_id = $other_data_arr['booking_id'];
						$incentive_count = mysql_num_rows(mysql_query("select * from booker_incentive_package_tour where booking_id='$booking_id' and emp_id='$emp_id'"));
						if($incentive_count==0){
							?>
							<a href="javascript:void(0)" onclick="package_tour_incentive_save_modal(<?= $other_data_arr['booking_id'] ?>, <?= $other_data_arr['emp_id'] ?>)" class="btn btn-sm btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</a>
							<?php
						}
						else{
							?>
							<a href="javascript:void(0)" onclick="package_tour_incentive_edit_modal(<?= $other_data_arr['booking_id'] ?>, <?= $other_data_arr['emp_id'] ?>)" class="btn btn-info btn-sm" title="Edit Incentive"><i class="fa fa-pencil-square-o"></i></a>
							<?php
						}
					}
					?>
				</td>
				<?php } ?>
			</tr>
			<?php		
		}

		?>
		</tbody>
		<tfoot>
			<tr class="success">
			   <th colspan="11" class="text-right">Total Incentive : <?= number_format($incentive_total, 2); ?></th>
			</tr>
		</tfoot>
	
</table>

</div> </div> </div>
<div id="div_incentive_save_popup"></div>

<script>
$('#incentive_table').dataTable({
		"pagingType": "full_numbers"
	});
	function group_tour_incentive_save_modal(tourwise_traveler_id, emp_id)
	{
		$.post('group_tour_incentive_save_modal.php', { tourwise_traveler_id : tourwise_traveler_id, emp_id : emp_id }, function(data){
			$('#div_incentive_save_popup').html(data);	
		});
	}
	function group_tour_incentive_edit_modal(tourwise_traveler_id, emp_id)
	{
		$.post('group_tour_incentive_edit_modal.php', { tourwise_traveler_id : tourwise_traveler_id, emp_id : emp_id }, function(data){
			$('#div_incentive_save_popup').html(data);	
		});
	}
	function package_tour_incentive_save_modal(booking_id, emp_id)
	{
		$.post('package_tour_incentive_save_modal.php', { booking_id : booking_id, emp_id : emp_id }, function(data){
			$('#div_incentive_save_popup').html(data);	
		});
	}
	function package_tour_incentive_edit_modal(booking_id, emp_id)
	{
		$.post('package_tour_incentive_edit_modal.php', { booking_id : booking_id, emp_id : emp_id }, function(data){
			$('#div_incentive_save_popup').html(data);	
		});
	}
</script>