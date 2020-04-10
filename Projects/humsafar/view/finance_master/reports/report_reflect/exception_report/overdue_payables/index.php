<?php include "../../../../../../model/model.php"; 
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];

$sq = mysql_fetch_assoc(mysql_query("select * from branch_assign where link='finance_master/reports/index.php'"));
$branch_status = $sq['branch_status'];
?>

<input type="hidden" name="branch_status" value="<?= $branch_status ?>" id="branch_status">
<input type="hidden" name="role" value="<?= $role ?>" id="role">
<input type="hidden" name="branch_admin_id" value="<?= $branch_admin_id ?>" id="branch_admin_id">

<div class="row mg_bt_10">
	<div class="col-xs-12 text-right">
		<button class="btn btn-excel btn-sm" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>
	</div>
</div>

<div class="app_panel_content Filter-panel">
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<select name="vendor_type2" id="vendor_type2" title="Supplier Type" onchange="report_reflect_exc();">
				<option value="">Supplier Type</option>
				<?php 
				$sq_vendor = mysql_query("select * from vendor_type_master order by vendor_type");
				while($row_vendor = mysql_fetch_assoc($sq_vendor)){
					?>
					<option value="<?= $row_vendor['vendor_type'] ?>"><?= $row_vendor['vendor_type'] ?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>
</div>

<hr>

<div id="div_report_pay" class="main_block loader_parent"></div>

<script type="text/javascript">
$('#party_name').select2();

function report_reflect_exc()
{
	$('#div_report_pay').append('<div class="loader"></div>');
	var base_url = $('#base_url').val();
	var party_name = $('#vendor_type2').val();
	var branch_status = $('#branch_status').val();
	var branch_admin_id = $('#branch_admin_id').val();
	var role = $('#role').val();
	$.post(base_url+'view/finance_master/reports/report_reflect/exception_report/overdue_payables/report_reflect.php',{ party_name : party_name, branch_status : branch_status , role : role,branch_admin_id : branch_admin_id}, function(data){
		$('#div_report_pay').html(data);
	});
}
report_reflect_exc();

function excel_report()
{
	var party_name = $('#vendor_type2').val();
  	var financial_year_id = $('#financial_year_id_filter').val();
	var branch_status = $('#branch_status').val();
	var branch_admin_id = $('#branch_admin_id').val();
	var role = $('#role').val();
  	window.location = 'report_reflect/exception_report/overdue_payables/excel_report.php?party_name='+party_name+'&branch_status='+branch_status+'&branch_admin_id='+branch_admin_id+'&role='+role;
}
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>