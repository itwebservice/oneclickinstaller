<?php
include "../../../model/model.php";
$role = $_SESSION['role'];
$emp_id = $_SESSION['emp_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$sq = mysql_fetch_assoc(mysql_query("select * from branch_assign where link='b2b_customer/index.php'"));
$branch_status = $sq['branch_status'];
?>
<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>">
<input type="hidden" id="branch_admin_id" name="branch_admin_id" value="<?= $branch_admin_id ?>">
 
<div class="row">
    <div class="col-sm-12 text-right text_left_sm_xs">
        <?php if($role == 'Admin' || $role == 'Branch Admin'){?>
        <button class="btn btn-excel btn-sm mg_bt_20" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>&nbsp;&nbsp;
        <?php } ?>
    </div>
</div>

<div class="app_panel_content Filter-panel">
    <div class="row">
        <div class="col-md-3 col-sm-6 mg_bt_10_xs">
            <select name="approve_statuss" id="approve_statuss" title="Status" onchange="customer_list_reflect()">
                <option value="">Approval Status</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
        </div>
    </div>
</div>

<div class="row"></div>

<div id="div_customer_list" class="loader_parent"></div>
<div id="div_view_modal"></div>
<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>
<script>
function customer_list_reflect(){
  $('#div_customer_list').append('<div class="loader"></div>');
  var approve_status = $('#approve_statuss').val();
  var branch_status = $('#branch_status').val();
 
	$.post('credit_management/customer_list_reflect.php',{  branch_status : branch_status,approve_status:approve_status}, function(data){
		$('#div_customer_list').html(data);
	});
}
customer_list_reflect();

function excel_report(){
    var approve_status = $('#approve_statuss').val();
    var branch_id = $('#branch_id_filter1').val();
    
    window.location = 'credit_management/excel_report.php?branch_status='+branch_status+'&approve_status='+approve_status;
}
function change_fields_status(approval_status){
    var approve_status1 = $('#'+approval_status).val();
    if(approve_status1 == 'Approved'){
      $('input[name="app_credit"]').prop('disabled', false);
    }
    else{
      $('input[name="app_credit"]').prop('disabled', true);
    }
}
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>