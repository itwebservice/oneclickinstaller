<?php
include "../../../../model/model.php";
/*======******Header******=======*/
require_once('../../../layouts/admin_header.php');
?>
<?= begin_panel('Refund Package Booking',69) ?>
<div class="app_panel_content Filter-panel">
<form action="refund_booking.php" id="frm_refund_booking" method="POST" onsubmit="return validate_submit()" class="no-marg">

<div class="row">
	
<div class="row">
	<div class="col-sm-4 col-sm-offset-3 col-xs-10 col-xs-offset-1 mg_bt_10_xs">
		<select id="booking_id" name="booking_id" style="width:100%" title="Select Booking"> 
		    <option value="">Select Booking</option>
		    <?php 
		        $sq_booking = mysql_query("select * from package_tour_booking_master order by booking_id desc");
		        while($row_booking = mysql_fetch_assoc($sq_booking)){
		            $sq_traveler = mysql_query("select m_honorific, first_name, last_name from package_travelers_details where booking_id='$row_booking[booking_id]' and status='Cancel'");
		            $date = $row_booking['booking_date'];
			         $yr = explode("-", $date);
			         $year =$yr[0];
		            while($row_traveler = mysql_fetch_assoc($sq_traveler)){
		             ?>
		             <option value="<?php echo $row_booking['booking_id'] ?>"><?php echo get_package_booking_id($row_booking['booking_id'],$year)."-".$row_traveler['m_honorific']." ".$row_traveler['first_name']." ".$row_traveler['last_name']; ?></option>
		             <?php    
		            }
		        }  
		     ?>
		</select>
	</div>
	<div class="col-sm-4 col-xs-10 col-xs-offset-1">
		<button class="btn btn-sm btn-info ico_right">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></button>
	</div>
</div>
</div>

</form>
</div>
<script>
	$("#booking_id").select2();
	$('#frm_refund_booking').validate();
	function validate_submit()
	{
	    var tourwise_traveler_id = $("#booking_id").val();

	    if(tourwise_traveler_id=="")
	    {
	        error_msg_alert("Please select Booking ID.");
	        return false;
	    }
	    
	}
</script>

<?= end_panel() ?>
<?php
/*======******Footer******=======*/
require_once('../../../layouts/admin_footer.php'); 
?>