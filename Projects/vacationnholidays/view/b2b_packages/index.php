 <?php

include "../../model/model.php";
require_once('../layouts/admin_header.php');
?>
<?= begin_panel('B2B Packages',18) ?>
<?php
if($setup_package == '4'){ ?>
<div class="app_panel_content Filter-panel">

	<div class="row">

		<div class="text-left col-md-3 col-sm-6">

			<select id="dest_id"  name="dest_name" title="Select Destination" class="form-control" onchange="list_reflect(this.value)" style="width:100%"> 

	            <option value="">Destination</option>

	             <?php 

	             $sq_query = mysql_query("select * from destination_master where status != 'Inactive'"); 

	             while($row_dest = mysql_fetch_assoc($sq_query)){ ?>

	                <option value="<?php echo $row_dest['dest_id']; ?>"><?php echo $row_dest['dest_name']; ?></option>

	                <?php } ?>

	         </select>

		</div>


	</div>

</div>



<div id="div_modal"></div>



<div class="main_block">	
	<div class="row mg_tp_20"> 
		<div class="col-md-12">
	    	<div id="div_list"></div>
		</div>
	</div>
</div>

<div id="div_view_modal"></div>
<?php } else{ ?>
 <div class="alert alert-danger" role="alert">
   Please upgrade the subscription to use this feature.
 </div>
<?php }?>
<script>
$('#dest_id').select2();
function list_reflect(){
	var dest_id = $('#dest_id').val();
	$.post('list_reflect.php', {dest_id : dest_id }, function(data){
		$('#div_list').html(data);
	});
}
function send_quotation(){
	var target="_blank";
	var base_url = $('#base_url').val();	
	window.open (base_url+'view/package_booking/quotation/home/index.php',target);
}
function view_modal(package_id){
  var base_url = $('#base_url').val();
  $.post(base_url+'view/custom_packages/master/view/index.php', { package_id: package_id }, function(data){
    $('#div_view_modal').html(data);
  });
}
function download_pdf(package_id){
  var url = 'download_pdf.php?package_id='+package_id;
  window.location = url;
}

</script>
<?php require_once('../layouts/admin_footer.php');  ?>