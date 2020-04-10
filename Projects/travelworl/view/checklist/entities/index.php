<?php
include "../../../model/model.php";

include_once('entity_save_modal.php');
$emp_id = $_SESSION['emp_id'];
?>
<div class="row text-right mg_tp_20 mg_bt_20"> <div class="col-md-12">
	<button class="btn btn-info btn-sm ico_left" data-toggle="modal" data-target="#entity_save_modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;Checklist</button>
</div> </div>
<div class="app_panel_content Filter-panel">
<div class="row">
    <div class="col-md-3 col-sm-6 mg_bt_10_sm_xs">
        <select id="entity_for_a" name="entity_for_a" style="width:100%" title="Entity For" onchange="entities_list_reflect();">
           <option value="">Entity For</option>
           <option value="Group Tour">Group Tour</option>
           <option value="Package Tour">Package Tour</option>
        </select>
    </div>
</div>
</div>
<div id="div_entities_list" class="main_block mg_tp_20"></div>
<div id="div_entitiesup_list"></div>
<div id="div_view_entries"></div>

<script>
function entities_list_reflect(){
	var entity_for = $('#entity_for_a').val();
	$.post('entities/entities_list_reflect.php', {entity_for : entity_for}, function(data){
		$('#div_entities_list').html(data);
	});
}

entities_list_reflect();
function update_modal(entity_id)
{
	
    $.post('entities/entity_update_modal.php', {entity_id : entity_id}, function(data){
        $('#div_entitiesup_list').html(data);
    });
}
function view_modal(entity_id)
{
	$.post('entities/view_to_do_name.php', {entity_id : entity_id}, function(data){
        $('#div_view_entries').html(data);
    });
}
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>