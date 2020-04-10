<?php include "../../../../../model/model.php";
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
?>
<input type="hidden" id="branch_admin_id1" name="branch_admin_id1" value="<?= $branch_admin_id ?>" >
<div class="row text-right mg_bt_10">
	<div class="col-md-4 col-md-offset-8">
            <button class="btn btn-excel btn-sm" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>&nbsp;&nbsp;
            <button class="btn btn-info btn-sm ico_left" id="btn_save_modal" onclick="save_modal()" title="Add New"><i class="fa fa-plus"></i>&nbsp;&nbsp;Reconciliation</button>
	</div>
</div>

<div class="app_panel_content Filter-panel">
	<div class="row">
        <div class="col-md-3 col-sm-6">
          <select id="bank_id1" name="bank_id1" title="Select Bank" style="width: 100%;" onchange="report_reflect();">
            <option value="">Select Bank</option>
            <?php $query = mysql_query("Select * from bank_master where 1 ");
            while($row_query = mysql_fetch_assoc($query)){ ?>
              <option value="<?= $row_query['bank_id'] ?>"><?= $row_query['bank_name'].'('.$row_query['branch_name'].')' ?></option>
            <?php } ?>
          </select>
        </div>
		<div class="col-md-3 mg_bt_10_xs">
			<input type="text" name="from_date_filter1" id="from_date_filter1" placeholder="Till Date" title="Till Date" class="form-control" onchange="report_reflect()">
		</div>
	</div>
</div
<hr>
<div id="div_report" class="main_block loader_parent"></div>
<div id="display_modal1" class="main_block"></div>

<script type="text/javascript">
$('#bank_id1').select2();
$('#from_date_filter1').datetimepicker({ timepicker:false, format:'d-m-Y' }); 
function save_modal()
{ 
	var base_url = $('#base_url').val();
  	var branch_admin_id = $('#branch_admin_id1').val();
	$('#btn_save').button('loading');

	$.post(base_url+'view/finance_master/reports/report_reflect/bank_reconcilation/save_modal.php', {branch_admin_id : branch_admin_id }, function(data){
		$('#btn_save').button('reset');
		$('#div_modal').html(data);
	});
}
function report_reflect()
{
	$('#div_report').append('<div class="loader"></div>');
	var bank_id = $('#bank_id1').val();
	var filter_date = $('#from_date_filter1').val();
  	var branch_admin_id = $('#branch_admin_id1').val();
	$.post('report_reflect/bank_reconcilation/report_reflect.php',{  bank_id : bank_id,filter_date : filter_date,branch_admin_id : branch_admin_id  }, function(data){
		$('#div_report').html(data);
	});
}
report_reflect();

function display_modal(id)
{
    $.post('report_reflect/bank_reconcilation/view/index.php', {id : id}, function(data){
        $('#display_modal1').html(data);
    });
}
function excel_report()
{
	var bank_id = $('#bank_id1').val();
	var filter_date = $('#from_date_filter1').val();
  	var branch_admin_id = $('#branch_admin_id1').val();
	
	window.location = 'report_reflect/bank_reconcilation/excel_report.php?filter_date='+filter_date+'&branch_admin_id='+branch_admin_id+'&bank_id='+bank_id;
}

</script>
<script src="report_reflect/bank_reconcilation/bank.js"></script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>