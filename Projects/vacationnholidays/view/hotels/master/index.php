<?php
include "../../../model/model.php";
/*======******Header******=======*/
require_once('../../layouts/admin_header.php');
?>

<?= begin_panel('Hotel Supplier Information',20) ?>
      <div class="header_bottom">
        <div class="row text-center">
            <label for="rd_hotel" class="app_dual_button active">
		        <input type="radio" id="rd_hotel" name="rd_hotel_tarrif" checked onchange="hotel_tarrif_reflect()">
		        &nbsp;&nbsp;Hotel
		    </label>    
		    <label for="rd_tarrif" class="app_dual_button">
		        <input type="radio" id="rd_tarrif" name="rd_hotel_tarrif" onchange="hotel_tarrif_reflect()">
		        &nbsp;&nbsp;Tariff
		    </label>   
		    <!-- <label for="rd_tarrifb2b" class="app_dual_button">
		        <input type="radio" id="rd_tarrifb2b" name="rd_hotel_tarrif" onchange="hotel_tarrif_reflect()">
		        &nbsp;&nbsp;B2B Tariff
		    </label> -->
        </div>
      </div> 

  <!--=======Header panel end======-->
<div class="app_panel_content">
<div id="div_hotel_tarrif"></div>

<?= end_panel() ?>
<script>
function hotel_tarrif_reflect(){
	var id = $('input[name="rd_hotel_tarrif"]:checked').attr('id');
	if(id=="rd_hotel"){
		$.post('hotel/index.php', {}, function(data){
			$('#div_hotel_tarrif').html(data);
		});
	}
	if(id=="rd_tarrif"){
		$.post('tarrif/index.php', {}, function(data){
			$('#div_hotel_tarrif').html(data);
		});
	}
	if(id=="rd_tarrifb2b"){
		$.post('b2b_tarrif/index.php', {}, function(data){
			$('#div_hotel_tarrif').html(data);
		});
	}
}
hotel_tarrif_reflect();
</script>

<?php
/*======******Footer******=======*/
require_once('../../layouts/admin_footer.php'); 
?>