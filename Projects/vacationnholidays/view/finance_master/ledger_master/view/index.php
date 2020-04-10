<?php 
include "../../../../model/model.php";

/*======******Header******=======*/
include_once('../../../layouts/fullwidth_app_header.php');
 
$ledger_id = $_GET['ledger_id'];
$financial_year_id = $_SESSION['financial_year_id']; 
$branch_admin_id = $_SESSION['branch_admin_id']; 

$sq_ledger = mysql_fetch_assoc(mysql_query("select * from ledger_master where ledger_id='$ledger_id'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Online Booking</title>	

	<?php admin_header_scripts(); ?>

</head>
 
<input type="hidden" id="base_url" name="base_url" value="<?= BASE_URL ?>">
<input type="hidden" id="ledger_id" name="ledger_id" value="<?= $ledger_id ?>">
<input type="hidden" id="financial_year_id" name="financial_year_id" value="<?= $financial_year_id ?>">
<input type="hidden" id="branch_admin_id" name="branch_admin_id" value="<?= $branch_admin_id ?>">

<div class="container">
	<h5 class="booking-section-heading text-center main_block"><?= $sq_ledger['ledger_name'] ?></h5>
	<div class="main_block mg_bt_20 app_panel">
	<div class="app_panel_content Filter-panel" style="margin: 0 !important;  width: 100% !important;">
	  <div class="row">
			<div class="col-md-4">
				<input type="text" id="lfrom_date_filter" name="lfrom_date_filter" placeholder="From Date" title="From Date">
			</div>
			<div class="col-md-4">
				<input type="text" id="lto_date_filter" name="lto_date_filter" placeholder="To Date" title="To Date">
			</div>
	    <div class="col-md-3 text-left">
	          <button class="btn btn-sm btn-info ico_right"  onclick="list_reflect()">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
	    </div>
	  </div>
	</div>
	</div>
		<div class="row text-right">
			<div class="col-md-6 col-md-offset-6 text-right">
				<button class="btn btn-pdf btn-sm mg_bt_10" onclick="excel_report()" id="print_button" title="Print PDF"><i class="fa fa-print"></i></button>
			</div>
		</div>
	<div id="div_list_content">
	</div>
</div>
<div id="div_list_content1">
</div>
<script>
$('#lfrom_date_filter, #lto_date_filter').datetimepicker({ timepicker:false, format:'d-m-Y' });
function list_reflect()
{
	var from_date_filter = $('#lfrom_date_filter').val();
	var to_date_filter = $('#lto_date_filter').val();
	var ledger_id = $('#ledger_id').val();
	var base_url = $('#base_url').val();
	var financial_year_id = $('#financial_year_id').val();
	var branch_admin_id = $('#branch_admin_id').val();

	$.post(base_url+'view/finance_master/ledger_master/view/list_reflect.php', {ledger_id : ledger_id, from_date_filter : from_date_filter , to_date_filter : to_date_filter,financial_year_id : financial_year_id,branch_admin_id : branch_admin_id}, function(data){
        $('#div_list_content').html(data);
        
    });
}
list_reflect();

function show_history(module_entry_id,module_name,finance_transaction_id,payment_perticular,ledger_name)
{
	var base_url = $('#base_url').val();
	$.post(base_url+'view/finance_master/ledger_master/view/display_history.php', {module_entry_id : module_entry_id, module_name : module_name , finance_transaction_id : finance_transaction_id, payment_perticular : payment_perticular,ledger_name : ledger_name}, function(data){
        $('#div_list_content1').html(data);        
    });
}

function financial_from_date_v(from_date)
{	
   var from_date = $('#'+from_date).val();
   var financial_year_id = $('#financial_year_id').val();
   var base_url = $('#base_url').val();

   $.post(base_url+'view/finance_master/ledger_master/view/date_validation.php', {from_date : from_date,financial_year_id : financial_year_id}, function(data){
  
        if(data == '0'){
        	error_msg_alert("Date should be between Financial Year"); return false; }
        else{        		

        }
    });
}
function excel_report(){
	var base_url = $('#base_url').val();
	var from_date = $('#lfrom_date_filter').val();
	var to_date = $('#lto_date_filter').val();
	var ledger_id = $('#ledger_id').val();
	var financial_year_id = $('#financial_year_id').val();
	var branch_admin_id = $('#branch_admin_id').val();
	$('#print_button').button('loading');
	$.post(base_url+'view/finance_master/ledger_master/view/excel_report.php',{ from_date : from_date, to_date : to_date,ledger_id :ledger_id, financial_year_id : financial_year_id,branch_admin_id : branch_admin_id }, function(data){
		$('#print_button').button('reset');
		$('#div_list_content1').html(data);
	});
}


</script>
<?php
/*======******Footer******=======*/
include_once('../../../layouts/fullwidth_app_footer.php');
?>