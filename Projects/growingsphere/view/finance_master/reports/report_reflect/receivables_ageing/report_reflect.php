<?php include "../../../../../model/model.php";
$role = $_POST['role'];
$branch_admin_id = $_POST['branch_admin_id'];
$branch_status = $_POST['branch_status']; ?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_report_receivable" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>SR.NO</th>
			<th>Customer_name</th>
			<th>Booking_Type</th>
			<th>View</th>
			<th>Total_Outstanding</th>
			<th>Not_Due</th>
			<th>Total_Due</th>
			<th>0_To_30</th>
			<th>31_To_60</th>
			<th>61_To_90</th>
			<th>91_To_120</th>
			<th>121_To_180</th>
			<th>181_To_360</th>
			<th>361_&_above</th>
		</tr>
	</thead>
	<tbody>	
		<?php include "get_customer_booking.php"; ?>
	</tbody>
</table>
</div> </div> </div>
<script>
$('#tbl_report_receivable').dataTable({
		"pagingType": "full_numbers"
	});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>