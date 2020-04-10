<?php
include "../../../../../../model/model.php";
$vendor_type = $_POST['party_name'];
$role = $_POST['role'];
$branch_admin_id = $_POST['branch_admin_id'];
$branch_status = $_POST['branch_status'];
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_report_payables" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Supplier_type</th>
			<th>Supplier_name</th>
			<th>Overdue_amount</th>
			<th>Overdue_from</th>
		</tr>
	</thead>
	<tbody>
		<?php
		include "get_supplier_purchase.php"; ?>
	</tbody>
	<tfoot>
		 
	</tfoot>
</table>
</div> </div> </div>
<script>
$('#tbl_report_payables').dataTable({
		"pagingType": "full_numbers"
	});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>