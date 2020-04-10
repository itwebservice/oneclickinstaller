<?php
$sq_tab5 = mysql_query("select * from hotel_offers_tarrif where pricing_id='$pricing_id'");
$sq_count5 = mysql_num_rows(mysql_query("select * from hotel_offers_tarrif where pricing_id='$pricing_id'"));
?>
<form id="frm_tab5">
<div class="app_panel"> 
<!--=======Header panel======-->
    <div class="app_panel_head mg_bt_20">
      <div class="container">
          <h2 class="pull-left"></h2>
          <div class="pull-right header_btn">
            <button>
                <a>
                    <i class="fa fa-arrow-right"></i>
                </a>
            </button>
          </div>
          <div class="pull-right header_btn">
            <button data-target="#myModalHint" data-toggle="modal">
              <a title="Help">
                <i class="fa fa-question" aria-hidden="true"></i>
              </a>
            </button>
          </div>
      </div>
    </div> 
<!--=======Header panel end======-->
        <div class="container">
        <h5 class="booking-section-heading main_block text-center">Offers/Discounts/Coupon</h5>
        <input type="hidden" value='<?=$sq_count5 ?>' id="tab5_count" name="tab5_count" />
        <?php if($sq_count5 == 0){ ?>
            <div class="row text-right mg_bt_10">
                <button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('table_hotel_tarrif_offer')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
                <button type="button" class="btn btn-danger btn-sm ico_left" onClick="deleteRow('table_hotel_tarrif_offer')"><i class="fa fa-times"></i>&nbsp;&nbsp;Delete</button>
            </div>
            <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="table_hotel_tarrif_offer" name="table_hotel_tarrif_offer" class="table table-bordered no-marg pd_bt_51" style="width:100%">
                    <tr>
                        <td><input class="css-checkbox" id="chk_offer" type="checkbox"><label class="css-label" for="chk_offer"> </label></td>
                        <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
                        <td><select name="offer_type" id="offer_type" style="width: 150px" class="form-control app_select2">
                            <option value=''>Select Type</option>
                            <option value='Offer'>Offer</option>
                            <option value='Discount'>Discount</option>
                            <option value='Coupon'>Coupon</option></td>    
                        <td><input type="text" id="from_date_h" class="form-control" name="from_date_h" placeholder="Valid From" title="Valid From" onchange="validate_validDate('from_date' , 'to_date');" value="<?= date('d-m-Y') ?>" style="width: 130px;" /></td>
                        <td><input type="text" id="to_date_h" class="form-control" name="to_date_h" placeholder="Valid To " title="Valid To" onchange="validate_issueDate('from_date' ,'to_date')" value="<?= date('d-m-Y') ?>" style="width: 130px;" /></td>
                        <td><Textarea id="offer" name="offer" placeholder="*Offer" title="Offer"  style="width: 420px;"></Textarea></td>
                        <td><select name="agent_type" id="agent_type" style="width: 150px" class="form-control app_select2">
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
		<?php }
		else{ ?>
		<div class="row mg_bt_10">
			<div class="col-md-12 text-right text_center_xs">
				<button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('table_hotel_tarrif_offer')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
			</div>
		</div>
		<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
			<table id="table_hotel_tarrif_offer" name="table_hotel_tarrif_offer" class="table table-bordered no-marg pd_bt_51" style="min-width:1500px">
			<?php
				$count = 1;
				while($row_tab5 = mysql_fetch_assoc($sq_tab5)){ ?>
                    <tr>
                        <td><input class="css-checkbox" id="chk_offer" type="checkbox" checked disabled><label class="css-label" for="chk_offer"> </label></td>
                        <td><input maxlength="15" value="<?= $count++ ?>" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
                        <td><select name="offer_type_h" id="offer_type_h" style="width: 150px" class="form-control app_select2">
                            <option value='<?= $row_tab5['type'] ?>'><?= $row_tab5['type'] ?></option>
                            <option value=''>Select Type</option>
                            <option value='Offer'>Offer</option>
                            <option value='Discount'>Discount</option>
                            <option value='Coupon'>Coupon</option></td>
                        <td><input type="text" id="from_date_h" class="form-control" name="from_date_h" placeholder="Valid From" title="Valid From" value="<?= get_date_user($row_tab5['from_date']) ?>" style="width: 130px;" /></td>
                        <td><input type="text" id="to_date_h" class="form-control" name="to_date_h" placeholder="Valid To " title="Valid To" onchange="validate_issueDate('from_date' ,'to_date')" value="<?= get_date_user($row_tab5['to_date']) ?>" style="width: 130px;" /></td>
                        <td><Textarea id="offer_u1" name="offer_u1" placeholder="*Offer" title="Offer"  style="width: 420px;"><?= $row_tab5['offer']?></Textarea></td>
                        <td><select name="agent_type_u1" id="agent_type_u1" style="width: 150px" class="form-control app_select2">
                            <?php if($row_tab5['agent_type']!=''){?><option value='<?= $row_tab5['agent_type'] ?>'><?= $row_tab5['agent_type'] ?></option><?php } ?>
                            <option value=''>Agent Type</option>
                            <option value='Platinum'>Platinum</option>
                            <option value='Gold'>Gold</option>
                            <option value='Silver'>Silver</option>
                            <option value='NA'>NA</option></td>
                        <td><input type="hidden" id="entry_id" name="entry_id" value='<?= $row_tab5['entry_id']?>' /></td>
                    </tr>
                    <script>
                        $('#to_date_h,#from_date_h').datetimepicker({ timepicker:false, format:'d-m-Y' });
                    </script>
                <?php } ?>
                </table>
                </div>
            </div>
            </div>
            <?php } ?>

            <div class="row text-center mg_tp_20 mg_bt_150">
                <div class="col-xs-12">
                    <button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab4()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp Previous</button>
                    &nbsp;&nbsp;
                    <button class="btn btn-sm btn-success" id="btn_price_save"><i class="fa fa-pencil"></i>&nbsp;&nbsp;Update</button>
                </div>
            </div>
        </div>

</form>
<?= end_panel() ?>

<script>
$('#agent_type').select2();
$('#to_date_h,#from_date_h').datetimepicker({ timepicker:false, format:'d-m-Y' });

function switch_to_tab4(){ 
	$('#tab5_head').removeClass('active');
	$('#tab4_head').addClass('active');
	$('.bk_tab').removeClass('active');
	$('#tab4').addClass('active');
	$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);
 }

$('#frm_tab5').validate({
	rules:{

	},
	submitHandler:function(form){
        var base_url = $('#base_url').val();
        var pricing_id = $('#pricing_id').val();
		var tab1_count = $('#tab1_count').val();
        var tab1_table_id = (tab1_count == 0)?'table_hotel_tarrif1':'table_hotel_tarrif';
		var tab3_count = $('#tab3_count').val();
		var tab3_table_id = (tab3_count == 0)?'table_hotel_tarrif3':'table_hotel_tarrif';
        
        //TAB-1
        var city_id = $('#cmb_city_id').val();
        var hotel_id = $('#hotel_id1').val();
        var currency_id = $('#currency_code1').val();
        var check_in = $('#check_in').val();
        var check_out = $('#check_out').val();
        var inclusions = $('#inclusions').val();
        var exclusions = $('#exclusions').val();
        var termsnconditions = $('#terms_conditions').val();
        
        //TAB-2
        var tab2_room_cat_array = new Array();
        var tab2_from_date_array = new Array();
        var tab2_to_date_array = new Array();
        var tab2_single_bed_array = new Array();
        var tab2_double_bed_array = new Array();
        var tab2_triple_bed_array = new Array();
        var tab2_chwithbed_array = new Array();
        var tab2_chwobed_array = new Array();
        var tab2_fchild_array = new Array();
        var tab2_schild_array = new Array();
        var tab2_extra_bed_array = new Array();
        var tab2_queen_bed_array = new Array();
        var tab2_king_bed_array = new Array();
        var tab2_quad_bed_array = new Array();
        var tab2_twin_bed_array = new Array();
        var tab2_markup_per_array = new Array();
        var tab2_markup_cost_array = new Array();
        var tab2_meal_plan_array = new Array();
        var tab2_entry_id_array = new Array();
        var table = document.getElementById(tab1_table_id);
		var rowCount = table.rows.length;
        for(var i=0; i<rowCount; i++){
          var row = table.rows[i];
          if(row.cells[0].childNodes[0].checked){

			  var room_cat = row.cells[2].childNodes[0].value;
			  var from_date = row.cells[3].childNodes[0].value;
			  var to_date = row.cells[4].childNodes[0].value;
			  var single_bed = row.cells[5].childNodes[0].value;
			  var double_bed = row.cells[6].childNodes[0].value;
			  var triple_bed = row.cells[7].childNodes[0].value;
			  var chwithbed = row.cells[8].childNodes[0].value;
			  var chwobed = row.cells[9].childNodes[0].value;
			  var fchild = row.cells[10].childNodes[0].value;
			  var schild = row.cells[11].childNodes[0].value;
			  var extra_bed = row.cells[12].childNodes[0].value;
			  var queen_bed = row.cells[13].childNodes[0].value;
			  var king_bed = row.cells[14].childNodes[0].value;
			  var quad_bed = row.cells[15].childNodes[0].value;
			  var twin_bed = row.cells[16].childNodes[0].value;
			  var markup_per = row.cells[17].childNodes[0].value;
			  var markup_cost = row.cells[18].childNodes[0].value;
              var meal_plan = row.cells[19].childNodes[0].value;
              var entry_id = row.cells[20].childNodes[0].value;

              tab2_room_cat_array.push(room_cat);
              tab2_from_date_array.push(from_date);
              tab2_to_date_array.push(to_date);
              tab2_single_bed_array.push(single_bed);
              tab2_double_bed_array.push(double_bed);
              tab2_triple_bed_array.push(triple_bed);
              tab2_chwithbed_array.push(chwithbed);
              tab2_chwobed_array.push(chwobed);
              tab2_fchild_array.push(fchild);
              tab2_schild_array.push(schild);
              tab2_extra_bed_array.push(extra_bed);
              tab2_queen_bed_array.push(queen_bed);
              tab2_king_bed_array.push(king_bed);
              tab2_quad_bed_array.push(quad_bed);
              tab2_twin_bed_array.push(twin_bed);
              tab2_markup_per_array.push(markup_per);
              tab2_markup_cost_array.push(markup_cost);
              tab2_meal_plan_array.push(meal_plan);
              tab2_entry_id_array.push(entry_id);
          }
        }

        //TAB-3
        var tab3_room_cat_array = new Array();
        var tab3_from_date_array = new Array();
        var tab3_to_date_array = new Array();
        var tab3_single_bed_array = new Array();
        var tab3_double_bed_array = new Array();
        var tab3_triple_bed_array = new Array();
        var tab3_chwithbed_array = new Array();
        var tab3_chwobed_array = new Array();
        var tab3_fchild_array = new Array();
        var tab3_schild_array = new Array();
        var tab3_extra_bed_array = new Array();
        var tab3_queen_bed_array = new Array();
        var tab3_king_bed_array = new Array();
        var tab3_quad_bed_array = new Array();
        var tab3_twin_bed_array = new Array();
        var tab3_markup_per_array = new Array();
        var tab3_markup_cost_array = new Array();
        var tab3_meal_plan_array = new Array();
        var tab3_entry_id_array = new Array();
        var table = document.getElementById(tab3_table_id);
		var rowCount = table.rows.length;
        for(var i=0; i<rowCount; i++){
          var row = table.rows[i];
          if(row.cells[0].childNodes[0].checked){

			  var room_cat = row.cells[2].childNodes[0].value;
			  var from_date = row.cells[3].childNodes[0].value;
			  var to_date = row.cells[4].childNodes[0].value;
			  var single_bed = row.cells[5].childNodes[0].value;
			  var double_bed = row.cells[6].childNodes[0].value;
			  var triple_bed = row.cells[7].childNodes[0].value;
			  var chwithbed = row.cells[8].childNodes[0].value;
			  var chwobed = row.cells[9].childNodes[0].value;
			  var fchild = row.cells[10].childNodes[0].value;
			  var schild = row.cells[11].childNodes[0].value;
			  var extra_bed = row.cells[12].childNodes[0].value;
			  var queen_bed = row.cells[13].childNodes[0].value;
			  var king_bed = row.cells[14].childNodes[0].value;
			  var quad_bed = row.cells[15].childNodes[0].value;
			  var twin_bed = row.cells[16].childNodes[0].value;
			  var markup_per = row.cells[17].childNodes[0].value;
			  var markup_cost = row.cells[18].childNodes[0].value;
              var meal_plan = row.cells[19].childNodes[0].value;
              var entry_id = row.cells[20].childNodes[0].value;

              tab3_room_cat_array.push(room_cat);
              tab3_from_date_array.push(from_date);
              tab3_to_date_array.push(to_date);
              tab3_single_bed_array.push(single_bed);
              tab3_double_bed_array.push(double_bed);
              tab3_triple_bed_array.push(triple_bed);
              tab3_chwithbed_array.push(chwithbed);
              tab3_chwobed_array.push(chwobed);
              tab3_fchild_array.push(fchild);
              tab3_schild_array.push(schild);
              tab3_extra_bed_array.push(extra_bed);
              tab3_queen_bed_array.push(queen_bed);
              tab3_king_bed_array.push(king_bed);
              tab3_quad_bed_array.push(quad_bed);
              tab3_twin_bed_array.push(twin_bed);
              tab3_markup_per_array.push(markup_per);
              tab3_markup_cost_array.push(markup_cost);
              tab3_meal_plan_array.push(meal_plan);
              tab3_entry_id_array.push(entry_id);
          }
        }

        //TAB-4
        var tab4_room_cat_array = new Array();
        var tab4_day_array = new Array();
        var tab4_single_bed_array = new Array();
        var tab4_double_bed_array = new Array();
        var tab4_triple_bed_array = new Array();
        var tab4_chwithbed_array = new Array();
        var tab4_chwobed_array = new Array();
        var tab4_fchild_array = new Array();
        var tab4_schild_array = new Array();
        var tab4_extra_bed_array = new Array();
        var tab4_queen_bed_array = new Array();
        var tab4_king_bed_array = new Array();
        var tab4_quad_bed_array = new Array();
        var tab4_twin_bed_array = new Array();
        var tab4_markup_per_array = new Array();
        var tab4_markup_cost_array = new Array();
        var tab4_meal_plan_array = new Array();
        var tab4_entry_id_array = new Array();
        var table = document.getElementById("table_hotel_weekend_tarrif");
		var rowCount = table.rows.length;
        for(var i=0; i<rowCount; i++){
          var row = table.rows[i];
          if(row.cells[0].childNodes[0].checked){

			  var room_cat = row.cells[2].childNodes[0].value;
			  var day = row.cells[3].childNodes[0].value;
			  var single_bed = row.cells[4].childNodes[0].value;
			  var double_bed = row.cells[5].childNodes[0].value;
			  var triple_bed = row.cells[6].childNodes[0].value;
			  var chwithbed = row.cells[7].childNodes[0].value;
			  var chwobed = row.cells[8].childNodes[0].value;
			  var fchild = row.cells[9].childNodes[0].value;
			  var schild = row.cells[10].childNodes[0].value;
			  var extra_bed = row.cells[11].childNodes[0].value;
			  var queen_bed = row.cells[12].childNodes[0].value;
			  var king_bed = row.cells[13].childNodes[0].value;
			  var quad_bed = row.cells[14].childNodes[0].value;
			  var twin_bed = row.cells[15].childNodes[0].value;
			  var markup_per = row.cells[16].childNodes[0].value;
			  var markup_cost = row.cells[17].childNodes[0].value;
              var meal_plan = row.cells[18].childNodes[0].value;
              var entry_id = row.cells[19].childNodes[0].value;

              tab4_room_cat_array.push(room_cat);
              tab4_day_array.push(day);
              tab4_single_bed_array.push(single_bed);
              tab4_double_bed_array.push(double_bed);
              tab4_triple_bed_array.push(triple_bed);
              tab4_chwithbed_array.push(chwithbed);
              tab4_chwobed_array.push(chwobed);
              tab4_fchild_array.push(fchild);
              tab4_schild_array.push(schild);
              tab4_extra_bed_array.push(extra_bed);
              tab4_queen_bed_array.push(queen_bed);
              tab4_king_bed_array.push(king_bed);
              tab4_quad_bed_array.push(quad_bed);
              tab4_twin_bed_array.push(twin_bed);
              tab4_markup_per_array.push(markup_per);
              tab4_markup_cost_array.push(markup_cost);
              tab4_meal_plan_array.push(meal_plan);
              tab4_entry_id_array.push(entry_id);
          }
        }
        
        //TAB-5
        var type_array = new Array();
        var from_date_array = new Array();
        var to_date_array = new Array();
        var offer_array = new Array();
        var agent_array = new Array();
        var tab5_entry_id_array = new Array();
		var table = document.getElementById("table_hotel_tarrif_offer");
		var rowCount = table.rows.length;
        for(var i=0; i<rowCount; i++){
          var row = table.rows[i];
          if(row.cells[0].childNodes[0].checked){
			  var type = row.cells[2].childNodes[0].value;
			  var from_date = row.cells[3].childNodes[0].value;
			  var to_date = row.cells[4].childNodes[0].value;
			  var offer = row.cells[5].childNodes[0].value;
			  var agent_type = row.cells[6].childNodes[0].value;
			  var entry_id = row.cells[7].childNodes[0].value;
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
			  if(offer==''){
				  error_msg_alert('Enter Offer in Row-'+(i+1));
				  return false;
              }
              type_array.push(type);
              from_date_array.push(from_date);
              to_date_array.push(to_date);
              offer_array.push(offer);
              agent_array.push(agent_type);
              tab5_entry_id_array.push(entry_id);
            // alert(room_cat);
            // alert(day);
            // alert(single_bed);
            // alert(double_bed);
            // alert(triple_bed);
            // alert(chwithbed);
            // alert(chwobed);
            // alert(fchild);
            // alert(schild);
            // alert(extra_bed);
            // alert(queen_bed);
            // alert(king_bed);
            // alert(quad_bed);
            // alert(twin_bed);
            // alert(markup_per);
            // alert(markup_cost);
            // alert(meal_plan);
          }
        }

        $('#btn_price_save').button('loading');
		$.ajax({
			type:'post',
			url: base_url+'controller/vendor/hotel_pricing/b2b_hotel_tarrif_update.php',
			data:{ pricing_id:pricing_id,city_id : city_id,hotel_id : hotel_id,currency_id:currency_id,check_in:check_in,check_out:check_out,inclusions:inclusions,exclusions:exclusions,termsnconditions:termsnconditions,
                    tab2_room_cat_array:tab2_room_cat_array,tab2_from_date_array:tab2_from_date_array,tab2_to_date_array:tab2_to_date_array,tab2_single_bed_array:tab2_single_bed_array,tab2_double_bed_array:tab2_double_bed_array,tab2_triple_bed_array:tab2_triple_bed_array,tab2_chwithbed_array:tab2_chwithbed_array,tab2_chwobed_array:tab2_chwobed_array,tab2_fchild_array:tab2_fchild_array,tab2_schild_array:tab2_schild_array,tab2_extra_bed_array:tab2_extra_bed_array,tab2_queen_bed_array:tab2_queen_bed_array,tab2_king_bed_array:tab2_king_bed_array,tab2_quad_bed_array:tab2_quad_bed_array,tab2_twin_bed_array:tab2_twin_bed_array,tab2_markup_per_array:tab2_markup_per_array,tab2_markup_cost_array:tab2_markup_cost_array,tab2_meal_plan_array:tab2_meal_plan_array,tab2_entry_id_array:tab2_entry_id_array,
                    tab3_room_cat_array:tab3_room_cat_array,tab3_from_date_array:tab3_from_date_array,tab3_to_date_array:tab3_to_date_array,tab3_single_bed_array:tab3_single_bed_array,tab3_double_bed_array:tab3_double_bed_array,tab3_triple_bed_array:tab3_triple_bed_array,tab3_chwithbed_array:tab3_chwithbed_array,tab3_chwobed_array:tab3_chwobed_array,tab3_fchild_array:tab3_fchild_array,tab3_schild_array:tab3_schild_array,tab3_extra_bed_array:tab3_extra_bed_array,tab3_queen_bed_array:tab3_queen_bed_array,tab3_king_bed_array:tab3_king_bed_array,tab3_quad_bed_array:tab3_quad_bed_array,tab3_twin_bed_array:tab3_twin_bed_array,tab3_markup_per_array:tab3_markup_per_array,tab3_markup_cost_array:tab3_markup_cost_array,tab3_meal_plan_array:tab3_meal_plan_array,tab3_entry_id_array:tab3_entry_id_array,
                    tab4_room_cat_array:tab4_room_cat_array,tab4_day_array:tab4_day_array,tab4_single_bed_array:tab4_single_bed_array,tab4_double_bed_array:tab4_double_bed_array,tab4_triple_bed_array:tab4_triple_bed_array,tab4_chwithbed_array:tab4_chwithbed_array,tab4_chwobed_array:tab4_chwobed_array,tab4_fchild_array:tab4_fchild_array,tab4_schild_array:tab4_schild_array,tab4_extra_bed_array:tab4_extra_bed_array,tab4_queen_bed_array:tab4_queen_bed_array,tab4_king_bed_array:tab4_king_bed_array,tab4_quad_bed_array:tab4_quad_bed_array,tab4_twin_bed_array:tab4_twin_bed_array,tab4_markup_per_array:tab4_markup_per_array,tab4_markup_cost_array:tab4_markup_cost_array,tab4_meal_plan_array:tab4_meal_plan_array,tab4_entry_id_array:tab4_entry_id_array,
                    type_array:type_array,from_date_array:from_date_array,to_date_array:to_date_array,offer_array:offer_array,agent_array:agent_array,tab5_entry_id_array:tab5_entry_id_array},
			success:function(result){
                $('#btn_price_save').button('reset');
                var msg = result.split('--');
                if(msg[0]=="error"){
                    error_msg_alert(msg[1]);
                }
                else{
                $('#vi_confirm_box').vi_confirm_box({
                            false_btn: false,
                            message: result,
                            true_btn_text:'Ok',
                    callback: function(data1){
                        if(data1=="yes"){
                        window.location.href = base_url+'view/hotels/master/index.php';
                        hotel_tarrif_reflect();
                        }
                    }
                });
            }
        }
    }); 
}
});
</script>