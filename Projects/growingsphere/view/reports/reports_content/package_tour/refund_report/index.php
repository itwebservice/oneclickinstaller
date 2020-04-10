<?php include "../../../../../model/model.php";

 ?>
<div class="app_panel_content Filter-panel mg_bt_10">
	<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	      <select id="booking_id_filter1" name="booking_id_filter" style="width:100%" title="Booking ID" class="form-control" onchange="refund_reflect()"> 
	          <?php get_package_booking_dropdown($role, $branch_admin_id, $branch_status,$emp_id); ?>
	      </select>
	</div>
</div>
<div id="div_list" class="main_block mg_tp_20"></div>
<script>
	$('#booking_id_filter1').select2();
	
	function refund_reflect(){
		var booking_id = $('#booking_id_filter1').val();
		$.post('reports_content/package_tour/refund_report/refund_report.php', {booking_id : booking_id}, function(data){
		$('#div_list').html(data);
	});
	}
	refund_reflect();
</script>