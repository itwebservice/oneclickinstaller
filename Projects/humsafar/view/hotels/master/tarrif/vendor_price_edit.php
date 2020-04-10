<?php 
include "../../../../model/model.php";

$entry_id = $_POST['entry_id'];
$query = "select * from hotel_vendor_price_list where entry_id='$entry_id'";
$entry_row = mysql_fetch_assoc(mysql_query($query));
$count_m = 1;
$sq_query = mysql_fetch_assoc(mysql_query("select * from hotel_vendor_price_master where pricing_id = '$entry_row[pricing_id]'"));
$sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$sq_query[city_id]'"));
$sq_hotel = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$sq_query[hotel_id]'"));
?>
<form id="frm_bid_update">
<div class="modal fade" id="price_update_modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width:1200px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Hotel Tariff Details</h4>
      </div>
      	<div class="modal-body">  	               
            <div class="row">
            	<input type="hidden" name="price_entry" id="price_entry1" value="<?php echo $entry_id; ?>">
            	<div class="col-md-2">
            		<input type="text" value="<?= $sq_city['city_name'] ?>" title="City Name" readonly/>
            	</div>
            	<div class="col-md-2">
            		<input type="text" value="<?= $sq_hotel['hotel_name'] ?>" title="Hotel Name" readonly/>
            	</div>
            	<div class="col-md-2">
                  <?php 
                  
				  $sq_currency1 = mysql_fetch_assoc(mysql_query("select * from currency_name_master where id = '$sq_query[currency_id]'")); 
				  ?>
	                <select name="currency_code" id="currency_code2" style="width:100%">
					  <option value="<?= $sq_currency1['id'] ?>"><?= $sq_currency1['currency_code'] ?></option>		                  
					  <?php
					  $sq_currency = mysql_query("select * from currency_name_master order by default_currency desc");
	                  while($row_currency = mysql_fetch_assoc($sq_currency)){
	                    ?>
	                    <option value="<?= $row_currency['id'] ?>"><?= $row_currency['currency_code'] ?></option>
	                    <?php
	                  }
	                  ?>
	                </select>
	            </div>
            	<div class="col-md-3">
            		<input type="text" id="<?php echo "check_in".$count_m ?>" name="check_in" placeholder="Check In Time" title="Check In Time" value="<?php echo $sq_query['check_in']; ?>" />
            	</div>
            	<div class="col-md-3">
            		<input type="text" id="<?php echo "check_out".$count_m ?>" name="check_out" placeholder="Check Out Time" title="Check Out Time" value="<?php echo $sq_query['check_out']; ?>" />
            	</div>
            </div>

      		<div class="panel panel-default panel-body app_panel_style mg_tp_20"> 
	            <div class="row">
	                <div class="col-md-2">
						<select name="room_cat_u<?=$count_m?>" id="room_cat_u<?=$count_m?>" style="width:100%;" title="Room Category" class="form-control app_select2">
						<option value='<?= $entry_row['without_bed_cost']?>'><?= $entry_row['without_bed_cost']?></option>
						<?php get_room_category_dropdown(); ?></select>
					</div>
					<div class="col-md-2">
						<input type="text" id="<?php echo "from_date".$count_m ?>" class="form-control" name="from_date" placeholder="Valid From" title="Valid From" onchange="validate_validDate('<?php echo 'from_date'.$count_m ?>', '<?php echo 'to_date'.$count_m ?>');" value="<?php echo get_date_user($entry_row['from_date']);?>" />
					</div>
					<div class="col-md-2">	
						<input type="text" id="<?php echo "to_date".$count_m ?>" class="form-control" name="to_date" placeholder="Valid To " title="Valid To" onchange="validate_issueDate('from_date' ,'to_date')"  value="<?php echo get_date_user($entry_row['to_date']);?>" />
					</div>
					<div class="col-md-2">
						<input type="text" id="<?php echo "single_bed".$count_m ?>" name="single_bed" placeholder="Single Bed Cost" title="Single Bed Cost" value="<?php echo $entry_row['single_bed_cost']; ?>"  onchange="validate_balance(this.id)" />
					</div>
					<div class="col-md-2">
						<input type="text" id="<?php echo "double_bed".$count_m ?>" name="double_bed" placeholder="Double Bed Cost" title="Double Bed Cost" value="<?php echo $entry_row['double_bed_cost']; ?>"  onchange="validate_balance(this.id)" />
					</div>
					<div class="col-md-2">
						<input type="text" id="<?php echo "triple_bed".$count_m ?>" name="triple_bed" placeholder="Triple Bed Cost" title="Triple Bed Cost" value="<?php echo $entry_row['triple_bed_cost']; ?>"  onchange="validate_balance(this.id)"/>
					</div>
				</div>
				<div class="row mg_tp_10">
					<div class="col-md-2">
						<input type="text" id="<?php echo "quad_bed".$count_m ?>" name="quad_bed" placeholder="Quad Bed Cost" title="Quad Bed Cost" value="<?php echo $entry_row['quad_bed_cost']; ?>"  onchange="validate_balance(this.id)" />
					</div>
					<div class="col-md-2">
						<input type="text" id="<?php echo "with_bed".$count_m ?>" name="with_bed" placeholder="Extra Bed Cost" title="Extra Bed Cost" value="<?php echo $entry_row['with_bed_cost']; ?>"  onchange="validate_balance(this.id)"/>
					</div>
					<div class="col-md-2">
						<input type="text" id="<?php echo "queen".$count_m ?>" name="queen" placeholder="Queen Bed Cost" title="Queen Bed Cost" value="<?php echo $entry_row['queen']; ?>"  onchange="validate_balance(this.id)"/>
					</div>
					<div class="col-md-2">
						<input type="text" id="<?php echo "king".$count_m ?>" name="king" placeholder="King Bed Cost" title="King Bed Cost" value="<?php echo $entry_row['king']; ?>"  onchange="validate_balance(this.id)" />
					</div>
					<div class="col-md-2">
						<input type="text" id="<?php echo "twin".$count_m ?>" name="twin" placeholder="Twin Bed Cost" title="Twin Bed Cost" value="<?php echo $entry_row['twin']; ?>"  onchange="validate_balance(this.id)" />
					</div>
					<div class="col-md-2">
						<select name="meal_plan" id="<?php echo "meal_plan".$count_m ?>" class="app_select2 form-control" style="width: 100%">
							<?php if($entry_row['meal_plan']!=''){?> 
		               		  <option value="<?php echo $entry_row['meal_plan']; ?>"><?php echo $entry_row['meal_plan']; ?></option>
       						  <?php get_mealplan_dropdown(); ?>
       						<?php }else{ ?>
								<?php get_mealplan_dropdown(); ?>
       						<?php } ?>
       					</select>
       				</div>
       			</div>
       		</div>
            <div class="row mg_tp_20">
            	<div class="col-md-4">
            		<h3 class="editor_title">Inclusions</h3>
              		<textarea  id="<?php echo "inclusions".$count_m ?>" class="feature_editor" style="width: 100%;" name="inclusions" placeholder="Inclusions" title="Inclusions"><?php echo $sq_query['inclusions']; ?></textarea>
              	</div>
              	<div class="col-md-4">
              		<h3 class="editor_title">Exclusions</h3>
             		 <textarea id="<?php echo "exclusions".$count_m ?>" class="feature_editor" style="width: 100%;" name="exclusions"  title="Exclusions"><?php echo $sq_query['exclusions']; ?></textarea>
             	</div>
             	<div class="col-md-4">
             		<h3 class="editor_title">Terms & Conditions</h3>
			  		<textarea id="<?php echo "terms_conditions".$count_m ?>" class="feature_editor" style="width: 100%;" name="terms_conditions"  title="Terms & Conditions"><?php echo $sq_query['terms_conditions']; ?></textarea>
			  	</div>
            </div>        		
			<div class="row text-center mg_tp_20">
				<div class="col-md-12">
					<button class="btn btn-sm btn-success" id="btn_price_update"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;Update</button>
				</div>
			</div>
	    </div>
</form>

<script>
$('#price_update_modal').modal('show');
$('#currency_code2,#meal_plan1').select2();
$('#to_date1,#from_date1').datetimepicker({ timepicker:false, format:'d-m-Y' });
$('#check_in1,#check_out1').datetimepicker({ datepicker:false, format:'H:i A',showMeridian: true });

$('#frm_bid_update').validate({
	rules:{
			hotel_id1 : { required : true},
	},
	submitHandler:function(form){

		var base_url = $('#base_url').val();
		var price_entry = $('#price_entry1').val();
		var currency_code = $('#currency_code2').val();
		var inclusions = $('#inclusions1').val();
		var exclusions = $('#exclusions1').val();
		var terms_conditions = $('#terms_conditions1').val();
		var check_in = $('#check_in1').val();
		var check_out = $('#check_out1').val();
		var without_bed_cost =  $('#room_cat_u1').val();
		var from_date = $('#from_date1').val();		 
		var to_date =  $('#to_date1').val();		
		var single_bed_cost =  $('#single_bed1').val();
		var double_bed_cost =  $('#double_bed1').val();
		var triple_bed_cost = $('#triple_bed1').val();
		var quad_bed_cost =  $('#quad_bed1').val();
		var with_bed_cost =  $('#with_bed1').val();
		var queen =  $('#queen1').val();		
		var king =  $('#king1').val();		
		var twin =  $('#twin1').val();
		var meal_plan =  $('#meal_plan1').val();

		$('#btn_price_update').button('loading');

		$.ajax({
			type:'post',
			url: base_url+'controller/vendor/hotel_pricing/vendor_price_update.php',
			data:{ from_date : from_date, to_date : to_date, single_bed_cost : single_bed_cost, double_bed_cost : double_bed_cost, triple_bed_cost : triple_bed_cost, with_bed_cost: with_bed_cost,without_bed_cost : without_bed_cost,inclusions : inclusions, exclusions : exclusions, terms_conditions : terms_conditions,currency_code : currency_code, check_in : check_in, check_out : check_out, quad_bed_cost : quad_bed_cost,queen : queen, king : king, twin : twin, meal_plan : meal_plan, price_entry : price_entry},
			success:function(result){
				msg_alert(result);
				$('#btn_price_update').button('reset');
				$('#price_update_modal').modal('hide');
				$('#price_update_modal').on('hidden.bs.modal', function(){
					vendor_price_list_reflect();
				});
			}
		});

	}
});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>