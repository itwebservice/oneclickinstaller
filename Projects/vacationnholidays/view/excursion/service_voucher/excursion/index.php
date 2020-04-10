<?php
include "../../../../model/model.php";
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$sq = mysql_fetch_assoc(mysql_query("select * from branch_assign where link='excursion/service_voucher/index.php'"));
$branch_status = $sq['branch_status'];
?>
<input type="hidden" value="<?php echo $branch_status;?>" id="branch_status" name="branch_status"/>
<div class="row"> <div class="col-md-12">
		<div class="row mg_bt_10">
			<div class="col-md-12">
				<div class="app_panel_content Filter-panel">
					<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
						<select id="cmb_booking_id" name="cmb_booking_id" title="Select Booking" style="width:100%" onchange="service_voucher_reflect()">
									<?php get_excursion_booking_dropdown($role, $branch_admin_id, $branch_status,$emp_id,$role_id);?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div id="div_service_voucher"></div>

</div> </div>


<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>

<script>
	$('#cmb_booking_id').select2();
	function service_voucher_reflect()
	{
		var booking_id = $('#cmb_booking_id').val();
		$.post('excursion/service_voucher_reflect.php', { booking_id : booking_id }, function(data){
			$('#div_service_voucher').html(data);
		});
	}
</script>