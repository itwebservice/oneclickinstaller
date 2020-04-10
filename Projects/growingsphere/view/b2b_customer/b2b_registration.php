<?php
include "../../model/model.php";
$role = $_SESSION['role'];
$emp_id = $_SESSION['emp_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$sq = mysql_fetch_assoc(mysql_query("select * from branch_assign where link='b2b_customer/index.php'"));
$branch_status = $sq['branch_status'];
?>
<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>" >
<input type="hidden" id="branch_admin_id" name="branch_admin_id" value="<?= $branch_admin_id ?>" >
 
<div class="row">
    <div class="col-sm-12 text-right text_left_sm_xs">
        <button class="btn btn-excel btn-sm mg_bt_20" id="send_btn" onclick="send_reg()" data-toggle="tooltip" title="" data-original-title="Send Registration Form"><i class="fa fa-paper-plane-o"></i></button>
        <?php if($role == 'Admin' || $role == 'Branch Admin'){?>
        <button class="btn btn-excel btn-sm mg_bt_20" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>&nbsp;&nbsp;
        <?php } ?>
    </div>
</div>

<div class="app_panel_content Filter-panel">
    <div class="row">
        <div class="col-md-3 col-sm-6 mg_bt_10_xs">
            <select name="approve_status" id="approve_status" title="Status" onchange="customer_list_reflect()">
                <option value="">Approval Status</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
        </div>
        <div class="col-md-3 col-sm-6 mg_bt_10_xs">
            <select name="active_flag_filter" id="active_flag_filter" title="Status" onchange="customer_list_reflect()">
                <option value="">Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
    </div>
</div>

<div class="row"></div>

<div id="div_customer_list" class="loader_parent"></div>
<div id="div_view_modal"></div>
<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>
<script>
function send_reg(){
	$('#send_btn').button('loading');
	$.post('send_reg_form.php', { }, function(data){
			$('#div_modal').html(data);
			$('#send_btn').button('reset');
	});
}

function customer_list_reflect(){
  $('#div_customer_list').append('<div class="loader"></div>');
  var approve_status = $('#approve_status').val();
	var active_flag = $('#active_flag_filter').val();
  var branch_status = $('#branch_status').val();
 
	$.post('customer_list_reflect.php',{ active_flag : active_flag, branch_status : branch_status,approve_status:approve_status}, function(data){
		$('#div_customer_list').html(data);
	});
}
customer_list_reflect();

function excel_report(){
    var approve_status = $('#approve_status').val();
    var active_flag = $('#active_flag_filter').val();
    var branch_id = $('#branch_id_filter1').val();
    
    window.location = 'excel_report.php?active_flag='+active_flag+'&branch_status='+branch_status+'&approve_status='+approve_status;
}
function change_fields_status(approval_status){
    var approve_status1 = $('#'+approval_status).val();
    if(approve_status1 == 'Approved'){
      $('input[name="credit_limit"]').prop('disabled', false);
      $('input[name="reflect_details1"]').prop('disabled', false);
    }
    else{
      $('input[name="credit_limit"]').prop('disabled', true);
      $('input[name="reflect_details1"]').prop('disabled', true);
      $('input[name="reflect_details1"]').prop('checked', false);
      $('#deposit_fields').html('');
    }
}
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>