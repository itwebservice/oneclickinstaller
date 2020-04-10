<?php include "../../../../../../model/model.php"; ?>
<?php
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_GET['branch_status']; 
?>
<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>" >

<div class="row mg_bt_10">
	<div class="col-xs-12 text-right">
		<button class="btn btn-excel btn-sm pull-right" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>
	</div>
</div>

<div class="app_panel_content Filter-panel">
	<div class="row">
		<div class="col-md-2">
			<input type="text" name="from_date_filter" id="from_date_filter" placeholder="From Date" title="From Date">
		</div>
		<div class="col-md-2">
			<input type="text" name="to_date_filter" id="to_date_filter" placeholder="To Date" title="To Date">
		</div>
    	<div class="col-md-2 col-sm-6 col-xs-12">
			<select name="taxation_id" id="taxation_id" title="Tax">
				<?php get_taxation_dropdown(); ?>
	        </select>
		</div>
		<div class="col-md-3">
			<button class="btn btn-sm btn-info ico_right" onclick="report_reflect()">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
		</div>
	</div>
</div>

<hr>

<div id="div_report_gstsale" class="main_block loader_parent"></div>
<script type="text/javascript">
$('#from_date_filter, #to_date_filter').datetimepicker({ timepicker:false, format:'d-m-Y' });
function report_reflect()
{
	$('#div_report_gstsale').append('<div class="loader"></div>');
	var from_date = $('#from_date_filter').val();
	var to_date = $('#to_date_filter').val();
	var taxation_id = $('#taxation_id').val();
	var branch_status = $('#branch_status').val();
	 
	$.post('report_reflect/taxation_reports/gst_sale/report_reflect.php',{ from_date : from_date, to_date : to_date , branch_status : branch_status ,taxation_id : taxation_id }, function(data){
		$('#div_report_gstsale').html(data);
	});
}
report_reflect();
 function excel_report()
{
	var from_date = $('#from_date_filter').val();
	var to_date = $('#to_date_filter').val();
	var taxation_id = $('#taxation_id').val();
	var branch_status = $('#branch_status').val();
	window.location = 'report_reflect/taxation_reports/gst_sale/excel_report.php?from_date='+from_date+'&to_date='+to_date+'&taxation_id='+taxation_id+'&taxation_id='+taxation_id;
}
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>