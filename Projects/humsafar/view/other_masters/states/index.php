<?php
include "../../../model/model.php";
?>
<div class="row text-right mg_tp_20"> <div class="col-md-12">
   <button class="btn btn-info btn-sm ico_left" onclick="state_save()" id="btn_city_save_modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;State</button>
</div> </div>

<div id="div_list_content"></div>
<div id="div_state_list_update_modal"></div>
<script>
function list_reflect()
{
  $.post('states/list_reflect.php', {}, function(data){
      $('#div_list_content').html(data);
  });
}
list_reflect();

function state_master_update_modal(id)
{
  $('#div_state_list_update_modal').load('states/update_modal.php', { id : id }).hide().fadeIn(500);
}

function state_save() {
	 $('#state_save_modal').button('loading');
	$.post('states/save_modal.php', {}, function(data){
		$('#state_save_modal').button('reset');
		$('#div_state_list_update_modal').html(data);
	});
}
</script>