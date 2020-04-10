<?php
include "../../../../../model/model.php";
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];

?>
<div class="row"> <div class="col-md-12">
	
	<div class="app_panel_content Filter-panel">
		<div class="row">
			<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			  	<select id="cmb_booking_id" name="cmb_booking_id" title="Select Booking" style="width:100%" onchange="hotel_list_reflect()"> 
			  		<option value="">Select Booking</option>
	                 <?php

				      $query = "select * from package_tour_booking_master where 1 ";
				      if($role != 'Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
								$query .= " and emp_id='$emp_id'";
							}
							if($branch_status=='yes' && $role!='Admin'){
									$query .= " and branch_admin_id = '$branch_admin_id'";
							}
				      $query .= " order by booking_id desc";
				      $sq_booking = mysql_query($query);
				      while($row_booking = mysql_fetch_assoc($sq_booking))

				      	{
				      		$booking_date = $row_booking['booking_date'];
							$yr = explode("-", $booking_date);
							$year =$yr[0];
				      		$sq_count=mysql_num_rows(mysql_query("select * from package_hotel_accomodation_master  where booking_id='$row_booking[booking_id]'"));
				      		if($sq_count!=0){
				          $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_booking[customer_id]'"));
				          if($sq_customer['type'] == 'Corporate'){
				           ?>
				           <option value="<?php echo $row_booking['booking_id'] ?>"><?php echo get_package_booking_id($row_booking['booking_id'],$year)."-"." ".$sq_customer['company_name']; ?></option>
				           <?php }
				           else{ ?> 
				           <option value="<?php echo $row_booking['booking_id'] ?>"><?php echo get_package_booking_id($row_booking['booking_id'],$year)."-"." ".$sq_customer['first_name']." ".$sq_customer['last_name']; ?></option>

				           <?php    
				         }
     				 	} 
     				 	}  
     				 ?>

	            </select>
			</div>
		</div>
	</div>
	
	<div id="div_hotel_list_reflect" class="main_block"></div>
</div>
 </div>


<script>
	$('#cmb_booking_id').select2();
	function hotel_list_reflect()
	{
		var base_url = $('#base_url').val();
		var booking_id = $('#cmb_booking_id').val();
		var url1 = base_url+'model/app_settings/print_html/voucher_html/fit_voucher.php?hotel_accomodation_id='+booking_id;
		loadOtherPage(url1);
	}
</script>