<?php
include "../../model/model.php";
?>
<form id="frm_b2b_exc_save">
<div class="modal fade" id="save_modal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:90%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Excursion</h4>
      </div>
      <div class="modal-body">	
		<div class="row">
		<div class="col-md-12 app_accordion">
		<div class="panel-group main_block" id="accordion" role="tablist" aria-multiselectable="true">
				<div class="accordion_content package_content">
					<div class="panel panel-default main_block">
						<div class="panel-heading main_block" role="tab" id="heading1">
							<div class="Normal collapsed main_block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="false" aria-controls="collapse1" id="collapsed1">                  
								<div class="col-md-12"><span><em style="margin-left: 15px;"><?php echo "Excursion Information"; ?></em></span></div>
							</div>
						</div>
						<div id="collapse1" class="panel-collapse collapse main_block" role="tabpanel" aria-labelledby="heading1">
							<div class="panel-body">
								<div class="row mg_bt_10">
									<div class="col-sm-2 mg_bt_10_xs">
										<select name="city_id" id="city_id" title="Select City" style="width:100%">
										<?php get_cities_dropdown(); ?>
										</select>
									</div>
									<div class="col-sm-2 mg_bt_10_xs">
										<input type="text" id="service_name" name="service_name" placeholder="*Excursion Name" title="Excursion Name">
									</div>
									<div class="col-sm-2 mg_bt_10_xs">
										<select name="transfer_option" id="transfer_option" title="Transfer Option" style="width:100%">
										<option value="">*Transfer Option</option>
										<option value="Sharing Transfer">Sharing Transfer</option>
										<option value="Privare Transfer">Privare Transfer</option>
										<option value="SIC">SIC</option>
										</select>
									</div>
									<div class="col-sm-2 mg_bt_10_xs">
										<input type="text" id="duration" name="duration" placeholder="Duration" title="Duration">
									</div>
									<div class="col-sm-2 mg_bt_10_xs">
										<input type="text" id="dep_point" name="dep_point" placeholder="Departure Point" title="Departure Point">
									</div>
									<div class="col-sm-2 mg_bt_10_xs">
										<input type="text" id="rep_time" name="rep_time" placeholder="Reporting Time" title="Reporting Time">
									</div>
								</div>
								<div class="row mg_bt_10">
									<div class="col-sm-6">
										<textarea id="description" name="description" onchange="validate_spaces(this.id)" placeholder="Description" title="Description"></textarea>
									</div>
									<div class="col-sm-6">
										<textarea id="note" name="note" onchange="validate_spaces(this.id)" placeholder="Note" title="Note"></textarea>
									</div>
								</div>
							</div>
							<div class="row mg_bt_20">
								<div class="col-md-4 col-sm-4 mg_bt_10_sm_xs">
									<h3 class="editor_title">Inclusions</h3>
									<textarea class="feature_editor" id="inclusions" name="inclusions" placeholder="Inclusions" title="Inclusions" rows="3"></textarea>
								</div>
								<div class="col-md-4 col-sm-4"> 
									<h3 class="editor_title">Exclusions</h3>
									<textarea class="feature_editor" id="exclusions" name="exclusions" class="form-control"  placeholder="Exclusions" title="Exclusions" rows="3"></textarea>
								</div>
								<div class="col-md-4 col-sm-4 mg_bt_10_sm_xs">
									<h3 class="editor_title">Terms & Conditions</h3>
									<textarea class="feature_editor" id="terms" name="terms" placeholder="Terms & Conditions" title="Terms & Conditions" rows="3"></textarea>
								</div>
							</div>	
							<div class="row mg_bt_20">
								<div class="col-md-4 col-sm-4 mg_bt_10_sm_xs">
									<h3 class="editor_title">Useful Information</h3>
									<textarea class="feature_editor" id="upolicy" name="upolicy" placeholder="Booking Policy" title="Booking Policy" rows="3"></textarea>
								</div>
								<div class="col-md-4 col-sm-4 mg_bt_10_sm_xs">
									<h3 class="editor_title">Booking Policy</h3>
									<textarea class="feature_editor" id="bpolicy" name="bpolicy" placeholder="Booking Policy" title="Booking Policy" rows="3"></textarea>
								</div>
								<div class="col-md-4 col-sm-4 mg_bt_10_sm_xs">
									<h3 class="editor_title">Cancellation Policy</h3>
									<textarea class="feature_editor" id="cpolicy" name="cpolicy" placeholder="Cancellation Policy" title="Cancellation Policy" rows="3"></textarea>
								</div>
							</div>
							</div>
						</div>
					</div>
				</div>
				<div class="accordion_content package_content mg_bt_10">
					<div class="panel panel-default main_block">
						<div class="panel-heading main_block" role="tab" id="heading2">
							<div class="Normal collapsed main_block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse2" id="collapsed1">                  
							<div class="col-md-12"><span><em style="margin-left: 15px;"><?php echo "Costing Information"; ?></em></span></div>
							</div>
						</div>
						<div id="collapse2" class="panel-collapse collapse main_block" role="tabpanel" aria-labelledby="heading2">
						<div class="panel-body">
							<div class="row mg_bt_10">
								<div class="col-md-2 mg_bt_10">
									<select name="currency_code" id="currency_code" title="Currency" style="width:100%">
									<?php
										$sq_currency = mysql_query("select * from currency_name_master order by default_currency desc");
										while($row_currency = mysql_fetch_assoc($sq_currency)){
										?>
										<option value="<?= $row_currency['id'] ?>"><?= $row_currency['currency_code'] ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-12">
							<div class="row mg_bt_10">
								<h5 style='border-bottom: 1px solid #e5e5e5;'>Basic Costing</h5>
									<div class="row text-right mg_bt_10">
										<button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('table_exc_tarrif_basic')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
										<button type="button" class="btn btn-danger btn-sm ico_left" onClick="deleteRow('table_exc_tarrif_basic')"><i class="fa fa-times"></i>&nbsp;&nbsp;Delete</button>
									</div>
									<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											<table id="table_exc_tarrif_basic" name="table_exc_tarrif_basic" class="table table-bordered no-marg pd_bt_51" style="width:100%">
											<tr>
												<td><input class="css-checkbox" id="chk_basic" type="checkbox" checked><label class="css-label" for="chk_basic"> </label></td>
												<td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
												<td><input type="text" id="from_date_basic" class="form-control" name="from_date_basic" placeholder="Valid From" title="Valid From" value="<?= date('d-m-Y') ?>" /></td>
												<td><input type="text" id="to_date_basic" class="form-control" name="to_date_basic" placeholder="Valid To " title="Valid To" onchange="validate_issueDate('from_date' ,'to_date')" value="<?= date('d-m-Y') ?>" /></td>
												<td><input type="text" id="adult_cost" name="adult_cost" placeholder="*Adult Cost" title="Adult Cost" onchange="validate_balance(this.id);"></td>
												<td><input type="text" id="child_cost" name="child_cost" placeholder="*Child Cost" title="Child Cost" onchange="validate_balance(this.id);"></td>
												<td><input type="hidden" id="entry_id" name="entry_id" /></td>
											</tr>
											</table>
										</div>
									</div>
									</div>
							</div>
							</div>
							<div class="col-md-12">
							<div class="row mg_bt_10">
								<h5 style='border-bottom: 1px solid #e5e5e5;'>Offers/Discounts</h5>
									<div class="row text-right mg_bt_10">
										<button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('table_exc_tarrif_offer')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
										<button type="button" class="btn btn-danger btn-sm ico_left" onClick="deleteRow('table_exc_tarrif_offer')"><i class="fa fa-times"></i>&nbsp;&nbsp;Delete</button>
									</div>
									<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											<table id="table_exc_tarrif_offer" name="table_exc_tarrif_offer" class="table table-bordered no-marg pd_bt_51" style="width:100%">
											<tr>
												<td><input class="css-checkbox" id="chk_offer" type="checkbox"><label class="css-label" for="chk_offer"> </label></td>
												<td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
												<td><select name="offer_type" id="offer_type" style="width: 250px" class="form-control app_select2">
													<option value=''>Select Type</option>
													<option value='Offer'>Offer</option>
													<option value='Discount'>Discount</option></td>
												<td><input type="text" id="from_date" class="form-control" name="from_date" placeholder="Valid From" title="Valid From" value="<?= date('d-m-Y') ?>" style="width: 230px;" /></td>
												<td><input type="text" id="to_date" class="form-control" name="to_date" placeholder="Valid To " title="Valid To" onchange="validate_issueDate('from_date' ,'to_date')" value="<?= date('d-m-Y') ?>" style="width: 230px;" /></td>
												<td><select name="offer_in" id="offer_in" style="width: 100px" class="form-control app_select2">
													<option value=''>Offer In</option>
													<option value='Percentage'>Percentage</option>
													<option value='Flat Amount'>Flat Amount</option></td>
												<td><input type="text" id="offer" name="offer" placeholder="*Offer/Discount" title="Offer/Discount"  style="width: 320px;"/></td>
												<td><select name="agent_type" id="agent_type" style="width: 230px" class="form-control app_select2">
													<option value=''>Agent Type</option>
													<option value='Platinum'>Platinum</option>
													<option value='Gold'>Gold</option>
													<option value='Silver'>Silver</option>
													<option value='NA'>NA</option></td>
												<td><input type="hidden" id="entry_id" name="entry_id" /></td>
											</tr>
											</table>
										</div>
									</div>
									</div>
							</div>
							</div>
							<div class="col-md-12">
							<div class="row mg_bt_10">
								<h5 style='border-bottom: 1px solid #e5e5e5;'>Coupons</h5>
									<div class="row text-right mg_bt_10">
										<button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('table_exc_tarrif_coupon')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
										<button type="button" class="btn btn-danger btn-sm ico_left" onClick="deleteRow('table_exc_tarrif_coupon')"><i class="fa fa-times"></i>&nbsp;&nbsp;Delete</button>
									</div>
									<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											<table id="table_exc_tarrif_coupon" name="table_exc_tarrif_coupon" class="table table-bordered no-marg pd_bt_51" style="width:100%">
											<tr>
												<td><input class="css-checkbox" id="chk_coupon" type="checkbox"><label class="css-label" for="chk_coupon"> </label></td>
												<td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
												<td><input type="text" id="from_date_c" class="form-control" name="from_date_c" placeholder="Valid From" title="Valid From" value="<?= date('d-m-Y') ?>" style="width: 250px;" /></td>
												<td><input type="text" id="to_date_c" class="form-control" name="to_date_c" placeholder="Valid To " title="Valid To" onchange="validate_issueDate('from_date_c' ,'to_date_c')" value="<?= date('d-m-Y') ?>" style="width: 230px;" /></td>
												<td><input type="text" id="coupon" name="coupon" placeholder="*Coupon Code" title="*Coupon Code"  style="width: 230px;"/></td>
												<td><select name="offer_in" id="offer_in" style="width: 100px" class="form-control app_select2">
													<option value=''>Offer In</option>
													<option value='Percentage'>Percentage</option>
													<option value='Flat Amount'>Flat Amount</option></td>
												<td><input type="text" id="offer" name="offer" placeholder="*Offer" title="Offer"  style="width: 320px;"/></td>
												<td><select name="agent_type" id="agent_type" style="width: 230px" class="form-control app_select2">
													<option value=''>Agent Type</option>
													<option value='Platinum'>Platinum</option>
													<option value='Gold'>Gold</option>
													<option value='Silver'>Silver</option>
													<option value='NA'>NA</option></td>
												<td><input type="hidden" id="entry_id" name="entry_id" /></td>
												</tr>
											</table>
										</div>
									</div>
									</div>
							</div>
							</div>
						</div>
						</div>
					</div>
				</div>			
		</div>
		</div>
		<div class="row mg_bt_10">
			<div class="col-md-3">          
				<div class="div-upload">
					<div id="photo_upload_btn_i" class="upload-button1"><span>Image</span></div>
					<span id="photo_status" ></span>
					<ul id="files" ></ul>
					<input type="hidden" id="photo_upload_url_i" name="photo_upload_url_i">
				</div>(Upload Maximum 3 images)
			</div>
		</div>
		<div class="row mg_tp_10">
			<div class="col-sm-6">  
				<span style="color: red;" class="note">Note : Image size should be less than 100KB, resolution : 900X450.</span>
			</div>
		</div>
      	<input type="hidden" name="hotel_image_path" id="hotel_image_path">
		<div class="row mg_tp_20 text-center">
			<div class="col-md-12">
				<button class="btn btn-sm btn-success" id="btn_save"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
			</div>
		</div>
	</div>
	</div>
	</div>
</div>
</form>
<script>
$('#save_modal').modal('show');
$('#currency_code').select2();
$('#rep_time').datetimepicker({ datepicker:false, format:'H:i A',showMeridian: true });
$('#to_date,#from_date,#to_date_c,#from_date_c,#from_date_basic,#to_date_basic').datetimepicker({ timepicker:false, format:'d-m-Y' });
$('#city_id').select2({minimumInputLength: 1});
upload_user_pic_attch();
function upload_user_pic_attch(){

    var img_array = new Array(); 
    var btnUpload=$('#photo_upload_btn_i');
    $(btnUpload).find('span').text('Image');

    $("#photo_upload_url_i").val('');
    new AjaxUpload(btnUpload, {
      action: 'upload_image_proof.php',
      name: 'uploadfile',
      onSubmit: function(file, ext){
        if (! (ext && /^(jpg|png|jpeg)$/.test(ext))){ 
         error_msg_alert('Only JPG, PNG or GIF files are allowed');
         return false;
        }
        $(btnUpload).find('span').text('Uploading...');
      },
      onComplete: function(file, response){
        if(response==="error"){          
          error_msg_alert("File is not uploaded.");
          $(btnUpload).find('span').text('Upload');
        }
        else{
          if(response=="error1"){
            $(btnUpload).find('span').text('Upload Images');
            error_msg_alert('Maximum size exceeds');
            return false;
          }
		  else{
              $(btnUpload).find('span').text('Uploaded'); 
              $("#photo_upload_url_i").val(response);
          }
          img_array.push(response);
        }
        if(img_array.length>3){
          error_msg_alert("You can upload only 3 images"); return false;
        }
        $("#hotel_image_path").val(img_array);
    }
});
}
$(function(){
	$('#frm_b2b_exc_save').validate({
		rules:{
				city_id : { required : true },
				service_name : { required : true },
				transfer_option: { required : true },
		},
		submitHandler:function(form){

			var city_id = $('#city_id').val();
			var service_name = $('#service_name').val();
			var transfer_option = $('#transfer_option').val();
			var duration = $('#duration').val();
			var dep_point = $('#dep_point').val();
			var rep_time = $('#rep_time').val();
			var description = $('#description').val();
			var note = $('#note').val();
			var inclusions = $('#inclusions').val();
			var exclusions = $('#exclusions').val();
			var terms = $('#terms').val();
			var upolicy = $('#upolicy').val();
			var bpolicy = $('#bpolicy').val();
			var cpolicy = $('#cpolicy').val();
			var currency_code = $('#currency_code').val();
			var active_flag = 'Active';

			var photo_upload_url = $('#hotel_image_path').val();

			if(city_id==''){
				error_msg_alert('Select City in Excursion Information!'); return false;
			}
			if(service_name==''){
				error_msg_alert('Enter Excursion in Excursion Information!'); return false;
			}
			if(transfer_option==''){
				error_msg_alert('Select Transfer Option in Excursion Information!'); return false;
			}

			//Tariff Basics
			var bfrom_date_array = new Array();
			var bto_date_array = new Array();
			var adult_cost_array = new Array();
			var child_cost_array = new Array();
			var table = document.getElementById("table_exc_tarrif_basic");
			var rowCount = table.rows.length;

			for(var i=0; i<rowCount; i++){
			var row = table.rows[i];           

				if(row.cells[0].childNodes[0].checked){
					var from_date = row.cells[2].childNodes[0].value;
					var to_date = row.cells[3].childNodes[0].value;
					var adult_cost = row.cells[4].childNodes[0].value;
					var child_cost = row.cells[5].childNodes[0].value;

					if(from_date==''){
						error_msg_alert('Select Valid From Date in Row-'+(i+1));
						return false;
					}
					if(to_date==''){
						error_msg_alert('Select Valid To Date in Row-'+(i+1));
						return false;
					}
					if(adult_cost==''){
						error_msg_alert('Enter Adult Cost in Row-'+(i+1));
						return false;
					}
					if(child_cost==''){
						error_msg_alert('Enter Child Cost in Row-'+(i+1));
						return false;
					}

					bfrom_date_array.push(from_date);
					bto_date_array.push(to_date);
					adult_cost_array.push(adult_cost);
					child_cost_array.push(child_cost);
				}
			}
			if(bfrom_date_array.length == 0){ error_msg_alert('Please Enter Basic Costing in Costing Information!'); return false;}

			var type_array = new Array();
			var from_date_array = new Array();
			var to_date_array = new Array();
			var offer_in_array = new Array();
			var offer_array = new Array();
			var agent_type_array = new Array();
			var table = document.getElementById("table_exc_tarrif_offer");
			var rowCount = table.rows.length;

			for(var i=0; i<rowCount; i++){
			var row = table.rows[i];           

				if(row.cells[0].childNodes[0].checked){
					var type = row.cells[2].childNodes[0].value;
					var from_date = row.cells[3].childNodes[0].value;
					var to_date = row.cells[4].childNodes[0].value;
					var offer_in = row.cells[5].childNodes[0].value;
					var offer = row.cells[6].childNodes[0].value;
					var agent_type = row.cells[7].childNodes[0].value;

					if(type==''){
						error_msg_alert('Select Type in Row-'+(i+1));
						return false;
					}
					if(from_date==''){
						error_msg_alert('Select Valid From Date in Row-'+(i+1));
						return false;
					}
					if(to_date==''){
						error_msg_alert('Select Valid To Date in Row-'+(i+1));
						return false;
					}
					if(offer_in==''){
						error_msg_alert('Select Offer-In in Row-'+(i+1));
						return false;
					}
					if(offer==''){
						error_msg_alert('Enter Offer/Discount in Row-'+(i+1));
						return false;
					}

					type_array.push(type);
					from_date_array.push(from_date);
					to_date_array.push(to_date);
					offer_in_array.push(offer_in);
					offer_array.push(offer);
					agent_type_array.push(agent_type);
				}
			}

			var type_c_array = new Array();
			var from_c_date_array = new Array();
			var to_c_date_array = new Array();
			var offer_c_in_array = new Array();
			var offer_c_array = new Array();
			var agent_c_type_array = new Array();
			var table = document.getElementById("table_exc_tarrif_coupon");
			var rowCount = table.rows.length;

			for(var i=0; i<rowCount; i++){
			var row = table.rows[i];           

				if(row.cells[0].childNodes[0].checked){
					var from_date = row.cells[2].childNodes[0].value;
					var to_date = row.cells[3].childNodes[0].value;
					var coupon_code = row.cells[4].childNodes[0].value;
					var offer_in = row.cells[5].childNodes[0].value;
					var offer = row.cells[6].childNodes[0].value;
					var agent_type = row.cells[7].childNodes[0].value;

					if(from_date==''){
						error_msg_alert('Select Valid From Date in Row-'+(i+1));
						return false;
					}
					if(to_date==''){
						error_msg_alert('Select Valid To Date in Row-'+(i+1));
						return false;
					}
					if(coupon_code==''){
						error_msg_alert('Enter Coupon Code in Row-'+(i+1));
						return false;
					}
					if(offer_in==''){
						error_msg_alert('Select Offer-In in Row-'+(i+1));
						return false;
					}
					if(offer==''){
						error_msg_alert('Enter Offer/Discount in Row-'+(i+1));
						return false;
					}

					type_c_array.push(coupon_code);
					from_c_date_array.push(from_date);
					to_c_date_array.push(to_date);
					offer_c_in_array.push(offer_in);
					offer_c_array.push(offer);
					agent_c_type_array.push(agent_type);
				}
			}

			$('#btn_save').button('loading');
			$.ajax({
				type:'post',
				url: base_url()+'controller/b2b_excursion/b2b_exc_save.php',
				data:{ city_id : city_id, service_name : service_name,transfer_option:transfer_option,duration:duration,dep_point:dep_point,rep_time:rep_time,description:description,note:note,inclusions:inclusions,exclusions:exclusions,terms:terms,upolicy:upolicy,bpolicy:bpolicy,cpolicy:cpolicy,currency_code:currency_code, active_flag : active_flag , adult_cost : adult_cost,child_cost : child_cost,photo_upload_url : photo_upload_url,type_array:type_array,from_date_array:from_date_array,to_date_array:to_date_array,offer_in_array:offer_in_array,offer_array:offer_array,agent_type_array:agent_type_array,type_c_array:type_c_array,from_c_date_array:from_c_date_array,to_c_date_array:to_c_date_array,offer_c_in_array:offer_c_in_array,offer_c_array:offer_c_array,agent_c_type_array:agent_c_type_array,bfrom_date_array:bfrom_date_array,bto_date_array:bto_date_array,adult_cost_array:adult_cost_array,child_cost_array:child_cost_array},
				success:function(result){
					var msg = result.split('--');
					if(msg[0] != 'error'){
						msg_alert(result);
						$('#save_modal').modal('hide');
						list_reflect();
					}
					else{
						error_msg_alert(msg[1]);
						$('#btn_save').button('reset');
						return false;
					}
				}
			});
		}
	});
});
</script>

<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>