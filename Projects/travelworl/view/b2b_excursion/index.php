<?php
include "../../model/model.php";
/*======******Header******=======*/
require_once('../layouts/admin_header.php');
?>
<?= begin_panel('Excursions',17) ?>

<div class="row text-right">
	<div class="col-md-12 mg_bt_20">
		<button class="btn btn-info btn-sm ico_left" id="btn_save_modal" onclick="save_modal()"><i class="fa fa-plus"></i>Excursion</button>
	</div>
</div>
<div class="app_panel_content Filter-panel">
   <div class="row">
        <div class="col-md-3 col-sm-6 mg_bt_10_sm_xs">
            <select id="city_id_filter1" name="city_id_filter1" style="width:100%" title="Select City Name" onchange="list_reflect()">
                <?php get_cities_dropdown(); ?>
            </select>
        </div>
    </div>
</div>

<div id="div_list" class="main_block"></div>
<div id="div_modal"></div>

<?= end_panel() ?>
<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>
<script src="<?= BASE_URL ?>js/ajaxupload.3.5.js"></script>

<script>
$('#city_id_filter1').select2({minimumInputLength: 1});
function save_modal(){
	$('#btn_save_modal').button('loading');
	$.post('save_modal.php', {}, function(data){
		$('#btn_save_modal').button('reset');
		$('#div_modal').html(data);
	});
}
function list_reflect(){
	var city_id = $('#city_id_filter1').val();
	$.post('list_reflect.php', {city_id : city_id}, function(data){
		$('#div_list').html(data);
	});
}
list_reflect();

function update_modal(service_id){
	$.post('update_modal.php', {entry_id : service_id}, function(data){
		$('#div_modal').html(data);
	});
}
function display_format_modal(){
    var base_url = $('#base_url').val();
    window.location = base_url+"images/csv_format/tour_excursions.csv";
}
</script>
<?php
/*======******Footer******=======*/
require_once('../layouts/admin_footer.php'); 
?>