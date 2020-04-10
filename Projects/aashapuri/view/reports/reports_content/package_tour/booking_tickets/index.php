<?php include "../../../../../model/model.php";

 ?>
<div class="app_panel_content Filter-panel mg_bt_10">
	<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	      <select id="booking_id_filter3" name="booking_id_filter" style="width:100%" title="Booking ID" class="form-control" onchange="ticket_reflect()"> 
	          <?php get_package_booking_dropdown($role, $branch_admin_id, $branch_status,$emp_id); ?>
	      </select>
	</div>
</div>
<div id="div_list" class="main_block mg_tp_20"></div>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script>
	$('#booking_id_filter3').select2();
</script>
<script>
	function ticket_reflect(){
		var booking_id = $('#booking_id_filter3').val();
		$.post('reports_content/package_tour/booking_tickets/booking_ticket_report_filter.php', {booking_id : booking_id}, function(data){
		$('#div_list').html(data);
	});
	}
	ticket_reflect();
</script>