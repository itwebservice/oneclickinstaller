<?php
include "../../../../model/model.php";
?>
<div class="row text-right">
    <div class="col-xs-12 mg_bt_20">
        <form action="b2b_tarrif/save/index.php" method="POST">
            <button class="btn btn-info btn-sm ico_left" id="btn_save_modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tariff</button>
        </form>
    </div>
</div>

<div class="app_panel_content Filter-panel">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
            <select id="city_id_filter" name="city_id_filter" onchange="hotel_name_list_load(this.id);tarrif_list_reflect();" class="form-control" style="width:100%" title="Select City Name">
                <?php get_cities_dropdown(); ?>
            </select>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
            <select id="hotel_id_filter" name="hotel_id_filter" style="width:100%" onchange="tarrif_list_reflect();" title="Select Hotel Name" class="form-control">
                <option value="">Select Hotel</option>
            </select>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
            <input type="text" placeholder="From date" title="From Date" onchange="tarrif_list_reflect();" class="form-control" id="from_date_filter"/>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
            <input type="text" placeholder="To date" title="To Date" onchange="tarrif_list_reflect();" class="form-control" id="to_date_filter"/>
        </div>
    </div>
</div>
<div id="div_request_list" class="main_block loader_parent mg_tp_20"></div>
<div id="div_bid_modal"></div>
<div id='div_view_modal'></div>

<script>
$('#tbl_req_list').dataTable();
$('#hotel_id_filter').select2();
$('#from_date_filter,#to_date_filter').datetimepicker({ timepicker:false, format:'d-m-Y' });
$('#city_id_filter').select2({minimumInputLength: 1});
function tarrif_list_reflect(){
    $('#div_request_list').append('<div class="loader"></div>');
	var city_id = $('#city_id_filter').val();
	var hotel_id = $('#hotel_id_filter').val();
	var from_date = $('#from_date_filter').val();
	var to_date = $('#to_date_filter').val();

	$.post('b2b_tarrif/vendor_price_list_reflect.php', {hotel_id : hotel_id, city_id : city_id, from_date : from_date , to_date : to_date }, function(data){
		$('#div_request_list').html(data);
	});
}
tarrif_list_reflect();

// function tarrif_list_edit_modal(pricing_id){
// 	$.post('b2b_tarrif/vendor_price_edit.php', { pricing_id : pricing_id }, function(data){
// 		$('#div_bid_modal').html(data);
// 	});
// }
function hotel_name_list_load(id){
  var city_id = $("#"+id).val();
  $.get( "tarrif/hotel_name_load.php" , { city_id : city_id } , function ( data ) {
        $ ("#hotel_id_filter").html( data ) ;                            
  });
}
function view_modal(pricing_id){
    $.post('b2b_tarrif/view/index.php', {pricing_id : pricing_id}, function(data){
        $('#div_view_modal').html(data);
    });
}

</script>

