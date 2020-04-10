<?php
include "../../../model/model.php";
/*======******Header******=======*/
require_once('../../layouts/admin_header.php');
$role = $_SESSION['role'];
$financial_year_id = $_SESSION['financial_year_id']; 
$branch_admin_id = $_SESSION['branch_admin_id']; 

?>
<?= begin_panel('Account Ledger',36) ?>

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
<div id="div_list_content" class="main_block loader_parent"></div>
<div id="div_ledger_modal"></div>
<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>

<script>
$('#group_id_filter').select2();
function save_modal()
{
    $('#btn_save_modal').button('loading');
    $.post('save_modal.php', {}, function(data){
        $('#btn_save_modal').button('reset');
        $('#div_modal').html(data);
    });
}

function list_reflect()
{
  $('#div_list_content').append('<div class="loader"></div>');
  var group_id = $('#group_id_filter').val();
  var financial_year_id = $('#financial_year_id').val();
  var branch_admin_id = $('#branch_admin_id').val();
	$.post('list_reflect.php', { group_id : group_id,financial_year_id : financial_year_id,branch_admin_id : branch_admin_id }, function(data){
        $('#div_list_content').html(data);
    });
}
list_reflect();

function update_modal(ledger_id)
{
    $.post('update_modal.php', {ledger_id : ledger_id}, function(data){
        $('#div_modal').html(data);
    });
}
function reflect_side(group_id,div_id)
{
  var group_id = $('#'+group_id).val();
  $.post('get_dr_cr.php', {group_id : group_id}, function(data){
        $('#'+div_id).html(data);
    });
}
function display_modal(ledger_id)
{
    $.post('view/index.php', {ledger_id : ledger_id}, function(data){
        $('#div_ledger_modal').html(data);
    });
}

function excel_report()
{
    var group_id = $('#group_id_filter').val()
    var financial_year_id = $('#financial_year_id').val();
    var branch_admin_id = $('#branch_admin_id').val();
    window.location = 'excel_report.php?group_id='+group_id+'&financial_year_id='+financial_year_id+'&branch_admin_id='+branch_admin_id;
}
</script>
<?= end_panel() ?>
<?php
/*======******Footer******=======*/
require_once('../../layouts/admin_footer.php'); 
?>