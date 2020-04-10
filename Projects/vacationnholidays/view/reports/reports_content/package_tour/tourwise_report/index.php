<?php include "../../../../../model/model.php";

 ?>
<div class="app_panel_content Filter-panel mg_bt_10">
<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
      <select id="booking_id_filter" name="booking_id_filter" style="width:100%" title="Booking ID" class="form-control" onchange="passanger_reflect()"> 
          <?php get_package_booking_dropdown($role, $branch_admin_id, $branch_status,$emp_id); ?>
      </select>
</div>
</div>
<div id="div_list" class="main_block mg_tp_20"></div>
<script>
	$('#booking_id_filter').select2();

	function passanger_reflect(){
		var booking_id = $('#booking_id_filter').val();
		$.post('reports_content/package_tour/tourwise_report/tourwise_report.php', {booking_id : booking_id}, function(data){
		$('#div_list').html(data);
	});
	}
	 passanger_reflect();
</script>