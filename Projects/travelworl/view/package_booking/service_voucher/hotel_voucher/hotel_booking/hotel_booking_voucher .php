<?php
include "../../../../../model/model.php";
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];
?>
<div class="row"> 
   <div class="col-md-12">
			  <div class="app_panel_content Filter-panel">
	
		<div class="row">
			<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			  	 <select id="cmb_booking_id" name="cmb_booking_id" style="width:100%" title="Select Booking" class="form-control" onchange="hotel_list_reflect()"> 
	                  <?php   get_hotel_booking_dropdown($role, $branch_admin_id, $branch_status,$emp_id,$role_id)  ?>

	            </select>
			</div>


		</div>

			  </div>
	</div> 
</div>

			<div id="div_hotel_list_reflect" class="main_block"></div>

<script>
	$('#cmb_booking_id').select2();
	function hotel_list_reflect()
	{
		var base_url = $('#base_url').val();
		var booking_id = $('#cmb_booking_id').val();
		
		var url1 = base_url+'model/app_settings/print_html/voucher_html/hotel_voucher.php?hotel_accomodation_id='+booking_id;
		loadOtherPage(url1);
	}
</script>