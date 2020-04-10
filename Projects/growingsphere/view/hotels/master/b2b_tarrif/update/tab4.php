<?php
$sq_tab4 = mysql_query("select * from hotel_weekend_tarrif where pricing_id='$pricing_id'");
$sq_count4 = mysql_num_rows(mysql_query("select * from hotel_weekend_tarrif where pricing_id='$pricing_id'"));
?>
<form id="frm_tab4">
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
    <h5 class="booking-section-heading main_block text-center">Weekend Rates</h5>
    <input type="hidden" value='<?=$sq_count4 ?>' id="tab4_count" name="tab4_count" />
    <?php if($sq_count4 == 0){ ?>
    <div class="row mg_bt_10">
      <div class="col-md-12 text-right text_center_xs">
          <button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('table_hotel_weekend_tarrif','4')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
      </div>
    </div>
		<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table id="table_hotel_weekend_tarrif" name="table_hotel_weekend_tarrif" class="table table-bordered no-marg pd_bt_51" style="min-width:1500px">
				<tr>
        			<td><input class="css-checkbox" id="chk_ticket3" type="checkbox"><label class="css-label" for="chk_ticket"> </label></td>
              <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
              <td><select name="room_cat" id="room_cat" style="width:145px;" title="Room Category" class="form-control app_select2"><?php get_room_category_dropdown(); ?></select></td>
              <td><select name="day" id="day" style="width:150px;" title="Weekend Day" class="form-control app_select2">
                <option value=""> Select Weekend Day</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option></select></td>
              <td><input type="text" id="single_bed" name="single_bed" placeholder="Single Bed" title="Single Bed" onchange="validate_balance(this.id)" style="width: 100px;"/></td>
              <td><input type="text" id="double_bed" name="double_bed" placeholder="Double Bed" title="Double Bed"  onchange="validate_balance(this.id)" style="width: 100px;"/></td>
              <td><input type="text" id="triple_bed" name="triple_bed" placeholder="Triple Bed" title="Triple Bed"  onchange="validate_balance(this.id)" style="width: 100px;" /></td>
              <td><input type="text" id="cwbed" name="cwbed" placeholder="Child With Bed" title="Child With Bed"  onchange="validate_balance(this.id)" style="width: 100px;" /></td>
              <td><input type="text" id="cwobed" name="cwobed" placeholder="Child Without Bed" title="Child Without Bed"  onchange="validate_balance(this.id)" style="width: 120px;" /></td>
              <td><input type="text" id="first_child" name="first_child" placeholder="First Child" title="First Child"  onchange="validate_balance(this.id)" style="width: 100px;" /></td>
              <td><input type="text" id="second_child" name="second_child" placeholder="Second Child" title="Second Child"  onchange="validate_balance(this.id)" style="width: 110px;" /></td>
              <td><input type="text" id="with_bed" name="with_bed" placeholder="Extra Bed" title="Extra Bed"  onchange="validate_balance(this.id)" style="width: 100px;" /></td>
              <td><input type="text" id="queen" name="queen" placeholder="Queen Bed" title="Queen Bed"  onchange="validate_balance(this.id)" style="width: 100px;" /></td>
              <td><input type="text" id="king" name="king" placeholder="King Bed" title="King Bed"  onchange="validate_balance(this.id)" style="width: 100px;" /></td>
              <td><input type="text" id="quad_bed" name="quad_bed" placeholder="Quad Bed" title="Quad Bed"  onchange="validate_balance(this.id)" style="width: 100px;" /></td>
              <td><input type="text" id="twin" name="twin" placeholder="Twin Bed" title="Twin Bed"  onchange="validate_balance(this.id)" style="width: 100px;" /></td>
              <td><input type="text" id="markup_per" name="markup_per" placeholder="Markup(%)" title="Markup(%)"  onchange="validate_balance(this.id)" style="width: 100px;" /></td>
              <td><input type="text" id="flat_markup" name="flat_markup" placeholder="Markup" title="Markup"  onchange="validate_balance(this.id)" style="width: 100px;" /></td>
              <td><select name="meal_plan" id="meal_plan" style="width: 110px" class="form-control app_select2" title="Meal Plan">
              <?php get_mealplan_dropdown(); ?></td>
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
				<button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('table_hotel_weekend_tarrif')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
			</div>
		</div>
		<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
			<table id="table_hotel_weekend_tarrif" name="table_hotel_weekend_tarrif" class="table table-bordered no-marg pd_bt_51" style="min-width:1500px">
			<?php
				$count = 1;
				while($row_tab4 = mysql_fetch_assoc($sq_tab4)){ ?>
					<tr>
						<td><input class="css-checkbox" id="chk_ticket3" type="checkbox" checked disabled><label class="css-label" for="chk_ticket"> </label></td>
						<td><input maxlength="15" value="<?= $count++ ?>" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
						<td><select name="room_cat_u1" id="room_cat_u1" style="width:145px;" title="Room Category" class="form-control app_select2">
						  <option value='<?= $row_tab4['room_category']?>'><?= $row_tab4['room_category']?></option>
						  <?php get_room_category_dropdown(); ?></select></td>
						<td><select name="day" id="day" style="width:150px;" title="Weekend Day" class="form-control app_select2">
                <option value='<?= $row_tab4['day']?>'><?= $row_tab4['day']?></option>
                <option value=""> Select Weekend Day</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option></select></td>
						<td><input type="text" id="single_bed_u1" name="single_bed_u1" placeholder="Single Bed" title="Single Bed" onchange="validate_balance(this.id)" value='<?= $row_tab4['single_bed'] ?>' style="width: 100px;"/></td>
						<td><input type="text" id="double_bed_u1" name="double_bed_u1" placeholder="Double Bed" title="Double Bed" onchange="validate_balance(this.id)" value='<?= $row_tab4['double_bed'] ?>' style="width: 100px;"/></td>
						<td><input type="text" id="triple_bed_u1" name="triple_bed_u1" placeholder="Triple Bed" title="Triple Bed" onchange="validate_balance(this.id)" value='<?= $row_tab4['triple_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="cwbed_u1" name="cwbed_u1" placeholder="Child With Bed" title="Child With Bed" onchange="validate_balance(this.id)" value='<?= $row_tab4['child_with_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="cwobed_u1" name="cwobed_u1" placeholder="Child Without Bed" title="Child Without Bed" onchange="validate_balance(this.id)" value='<?= $row_tab4['child_without_bed'] ?>' style="width: 120px;" /></td>
						<td><input type="text" id="first_child_u1" name="first_child_u1" placeholder="First Child" title="First Child" onchange="validate_balance(this.id)" value='<?= $row_tab4['first_child'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="second_child_u1" name="second_child_u1" placeholder="Second Child" title="Second Child" onchange="validate_balance(this.id)" value='<?= $row_tab4['second_child'] ?>' style="width: 110px;" /></td>
						<td><input type="text" id="with_bed_u1" name="with_bed_u1" placeholder="Extra Bed" title="Extra Bed" onchange="validate_balance(this.id)" value='<?= $row_tab4['extra_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="queen_u1" name="queen_u1" placeholder="Queen Bed" title="Queen Bed" onchange="validate_balance(this.id)" value='<?= $row_tab4['queen_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="king_u1" name="king_u1" placeholder="King Bed" title="King Bed"  onchange="validate_balance(this.id)" value='<?= $row_tab4['king_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="quad_bed_u1" name="quad_bed_u1" placeholder="Quad Bed" title="Quad Bed"  onchange="validate_balance(this.id)" value='<?= $row_tab4['quad_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="twin_u1" name="twin_u1" placeholder="Twin Bed" title="Twin Bed"  onchange="validate_balance(this.id)" value='<?= $row_tab4['twin_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="markup_per_u1" name="markup_per_u1" placeholder="Markup(%)" title="Markup(%)"  onchange="validate_balance(this.id)" value='<?= $row_tab4['markup_per'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="flat_markup_u1" name="flat_markup_u1" placeholder="Markup" title="Markup"  onchange="validate_balance(this.id)" value='<?= $row_tab4['markup'] ?>' style="width: 100px;" /></td>
						<td><select name="meal_plan_u1" id="meal_plan_u1" style="width: 110px" class="form-control app_select2" title="Meal Plan">
							<option value='<?= $row_tab4['meal_plan']?>'><?= $row_tab4['meal_plan']?></option>
							<?php get_mealplan_dropdown(); ?></td>
						<td><input type="hidden" id="entry_id" name="entry_id" value='<?= $row_tab4['entry_id']?>'/></td>
					</tr>
				<script>
					$('#to_date,#from_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
				</script>
			<?php } ?>
			</table>
			</div>
		</div>
		</div>
		<?php } ?>

		<div class="row text-center mg_tp_20 mg_bt_150">
			<div class="col-xs-12">
				<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab3()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp Previous</button>
				&nbsp;&nbsp;
				<button class="btn btn-info btn-sm ico_right">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
			</div>
		</div>

</form>
<?= end_panel() ?>

<script>
$('#to_date,#from_date').datetimepicker({ timepicker:false, format:'d-m-Y' });

function display_format_modal1(){
    var base_url = $('#base_url').val();
    window.location = base_url+"images/csv_format/hotel_weekend_tariff_import.csv";
}

function switch_to_tab3(){ 
	$('#tab4_head').removeClass('active');
	$('#tab3_head').addClass('active');
	$('.bk_tab').removeClass('active');
	$('#tab3').addClass('active');
	$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);
 }

$('#frm_tab4').validate({
	rules:{

	},
	submitHandler:function(form){
		var base_url = $('#base_url').val();
    
		var table = document.getElementById("table_hotel_weekend_tarrif");
		var rowCount = table.rows.length;

    for(var i=0; i<rowCount; i++){
      var row = table.rows[i];           
      if(row.cells[0].childNodes[0].checked){
        var room_cat = row.cells[2].childNodes[0].value;
        var day = row.cells[3].childNodes[0].value;
        if(room_cat==''){
          error_msg_alert('Select Room Category in Row-'+(i+1));
          return false;
        }
        if(day==''){
          error_msg_alert('Select Weekend Day in Row-'+(i+1));
          return false;
        }
      }
    }

	  $('#tab4_head').addClass('done');
		$('#tab5_head').addClass('active');
		$('.bk_tab').removeClass('active');
		$('#tab5').addClass('active');
		$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);
	}
});
</script>