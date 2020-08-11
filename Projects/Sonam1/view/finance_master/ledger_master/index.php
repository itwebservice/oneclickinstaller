<?php
include "../../../model/model.php";
$role = $_SESSION['role'];
$financial_year_id = $_SESSION['financial_year_id']; 
$branch_admin_id = $_SESSION['branch_admin_id']; 
?>
<div class="row text-right mg_bt_20">
    <div class="col-md-12">
    <?php if($role=='Admin'){ ?>
      <button class="btn btn-excel btn-sm mg_bt_10" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>&nbsp;&nbsp;
     <?php } ?>
      <button class="btn btn-info btn-sm ico_left" id="btn_save_modal" onclick="save_modal()"><i class="fa fa-plus"></i>&nbsp;&nbsp;Account Ledger</button>
    </div>
</div>

<input type="hidden" id="financial_year_id" name="financial_year_id" value="<?= $financial_year_id ?>">
<input type="hidden" id="branch_admin_id" name="branch_admin_id" value="<?= $branch_admin_id ?>">
<div class="app_panel_content Filter-panel">
    <div class="row">
        <div class="col-md-3">
            <select id="active_filter" name="active_filter" style="width: 100%" title="Status" onchange="list_reflect()">
                <option value="">Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="group_id_filter" name="group_id_filter" style="width: 100%" title="Group" onchange="list_reflect()">
                <option value="">Select Group</option>
                <?php 
                $sq_group = mysql_query("select * from subgroup_master");
                while($row_group = mysql_fetch_assoc($sq_group)){
                ?>
                <option value="<?= $row_group['subgroup_id'] ?>"><?= $row_group['subgroup_name'] ?></option>
                <?php
                }
                ?>
            </select>
        </div>
  </div>
</div>


<div id="div_modal"></div>

<div id="div_list_content" class="main_block loader_parent mg_tp_20">
 <div class="table-responsive">
    <table id="tbl_ledger_list" class="table table-hover" style="margin: 20px 0 !important;">         
    </table>
</div>
</div>

<div id="div_ledger_modal"></div>
<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>
<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>

<script>
$('#group_id_filter').select2();
function save_modal()
{
    $('#btn_save_modal').button('loading');
    $.post('../finance_master/ledger_master/save_modal.php', {}, function(data){
        $('#btn_save_modal').button('reset');
        $('#div_modal').html(data);
    });
}

var columns= [
    { title: "S_NO" },
    { title: "Ledger_Name" },
    { title: "Group_Name" },
    { title: "Balance" },
    { title: "Actions", className:"text-center" }
];
function list_reflect()
{
    $('#div_list_content').append('<div class="loader"></div>');
    var group_id = $('#group_id_filter').val();
    var financial_year_id = $('#financial_year_id').val();
    var branch_admin_id = $('#branch_admin_id').val();
    var active_filter = $('#active_filter').val();
    
    $.post('../finance_master/ledger_master/list_reflect.php', { group_id : group_id,financial_year_id : financial_year_id,branch_admin_id : branch_admin_id,active_filter:active_filter }, function(data){
	setTimeout(() => {
        pagination_load(data,columns, true, false, 20, 'tbl_ledger_list');
        $('.loader').remove();
    }, 1000);
  });
}list_reflect();

function update_modal(ledger_id)
{
    $.post('../finance_master/ledger_master/update_modal.php', {ledger_id : ledger_id}, function(data){
        $('#div_modal').html(data);
    });
}
function reflect_side(group_id,div_id)
{
  var group_id = $('#'+group_id).val();
  $.post('../finance_master/ledger_master/get_dr_cr.php', {group_id : group_id}, function(data){
        $('#'+div_id).html(data);
    });
}
function display_modal(ledger_id)
{
    $.post('../finance_master/ledger_master/view/index.php', {ledger_id : ledger_id}, function(data){
        $('#div_ledger_modal').html(data);
    });
}

function excel_report()
{
    var group_id = $('#group_id_filter').val()
    var financial_year_id = $('#financial_year_id').val();
    var branch_admin_id = $('#branch_admin_id').val();
    var active_filter = $('#active_filter').val();
    window.location = '<?= BASE_URL ?>/view/finance_master/ledger_master/excel_report.php?group_id='+group_id+'&financial_year_id='+financial_year_id+'&branch_admin_id='+branch_admin_id+'&active_filter='+active_filter;
}
</script>
<?php
/*======******Footer******=======*/
require_once('../../layouts/admin_footer.php'); 
?>