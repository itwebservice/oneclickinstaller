<?php
$sq_tab3 = mysql_query("select * from hotel_blackdated_tarrif where pricing_id='$pricing_id'");
$sq_count3 = mysql_num_rows(mysql_query("select * from hotel_blackdated_tarrif where pricing_id='$pricing_id'"));
?>
<form id="frm_tab3">
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
    <h5 class="booking-section-heading main_block text-center">Black-Dated Rates</h5>
    <input type="hidden" value='<?=$sq_count3 ?>' id="tab3_count" name="tab3_count" />
		<?php if($sq_count3 == 0){ ?>
    <div class="row mg_bt_10">
      <div class="col-md-12 text-right text_center_xs">
        <button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('table_hotel_tarrif3','3')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
      </div>
    </div>
		<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table id="table_hotel_tarrif3" name="table_hotel_tarrif" class="table table-bordered no-marg pd_bt_51" style="min-width:1500px">
					<tr>
        			<td><input class="css-checkbox" id="chk_ticket2" type="checkbox"><label class="css-label" for="chk_ticket"> </label></td>
					<?php include 'hotel_tarrif_list.php';?>
				</table>
			</div>
		</div>
		</div>
		<?php }
		else{ ?>
		<div class="row mg_bt_10">
			<div class="col-md-12 text-right text_center_xs">
				<button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('table_hotel_tarrif')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
			</div>
		</div>
		<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
			<table id="table_hotel_tarrif" name="table_hotel_tarrif" class="table table-bordered no-marg pd_bt_51" style="min-width:1500px">
			<?php
				$count = 1;
				while($row_tab3 = mysql_fetch_assoc($sq_tab3)){ ?>
					<tr>
						<td><input class="css-checkbox" id="chk_ticket1" type="checkbox" checked disabled><label class="css-label" for="chk_ticket"> </label></td>
						<td><input maxlength="15" value="<?= $count++ ?>" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
						<td><select name="room_cat_u1" id="room_cat_u1" style="width:145px;" title="Room Category" class="form-control app_select2">
						<option value='<?= $row_tab3['room_category']?>'><?= $row_tab3['room_category']?></option>
						<?php get_room_category_dropdown(); ?></select></td>                
						<td><input type="text" id="from_date" class="form-control" name="from_date" placeholder="Valid From" title="Valid From" style="width: 100px;" value='<?= get_date_user($row_tab3['from_date']) ?>' /></td>
						<td><input type="text" id="to_date" class="form-control" name="to_date" placeholder="Valid To " title="Valid To" onchange="validate_issueDate('from_date' ,'to_date')" value="<?= get_date_user($row_tab3['to_date']) ?>" style="width: 100px;" /></td>
						<td><input type="text" id="single_bed_u1" name="single_bed_u1" placeholder="Single Bed" title="Single Bed" onchange="validate_balance(this.id)" value='<?= $row_tab3['single_bed'] ?>' style="width: 100px;"/></td>
						<td><input type="text" id="double_bed_u1" name="double_bed_u1" placeholder="Double Bed" title="Double Bed" onchange="validate_balance(this.id)" value='<?= $row_tab3['double_bed'] ?>' style="width: 100px;"/></td>
						<td><input type="text" id="triple_bed_u1" name="triple_bed_u1" placeholder="Triple Bed" title="Triple Bed" onchange="validate_balance(this.id)" value='<?= $row_tab3['triple_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="cwbed_u1" name="cwbed_u1" placeholder="Child With Bed" title="Child With Bed" onchange="validate_balance(this.id)" value='<?= $row_tab3['child_with_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="cwobed_u1" name="cwobed_u1" placeholder="Child Without Bed" title="Child Without Bed" onchange="validate_balance(this.id)" value='<?= $row_tab3['child_without_bed'] ?>' style="width: 120px;" /></td>
						<td><input type="text" id="first_child_u1" name="first_child_u1" placeholder="First Child" title="First Child" onchange="validate_balance(this.id)" value='<?= $row_tab3['first_child'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="second_child_u1" name="second_child_u1" placeholder="Second Child" title="Second Child" onchange="validate_balance(this.id)" value='<?= $row_tab3['second_child'] ?>' style="width: 110px;" /></td>
						<td><input type="text" id="with_bed_u1" name="with_bed_u1" placeholder="Extra Bed" title="Extra Bed" onchange="validate_balance(this.id)" value='<?= $row_tab3['extra_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="queen_u1" name="queen_u1" placeholder="Queen Bed" title="Queen Bed" onchange="validate_balance(this.id)" value='<?= $row_tab3['queen_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="king_u1" name="king_u1" placeholder="King Bed" title="King Bed"  onchange="validate_balance(this.id)" value='<?= $row_tab3['king_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="quad_bed_u1" name="quad_bed_u1" placeholder="Quad Bed" title="Quad Bed"  onchange="validate_balance(this.id)" value='<?= $row_tab3['quad_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="twin_u1" name="twin_u1" placeholder="Twin Bed" title="Twin Bed"  onchange="validate_balance(this.id)" value='<?= $row_tab3['twin_bed'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="markup_per_u1" name="markup_per_u1" placeholder="Markup(%)" title="Markup(%)"  onchange="validate_balance(this.id)" value='<?= $row_tab3['markup_per'] ?>' style="width: 100px;" /></td>
						<td><input type="text" id="flat_markup_u1" name="flat_markup_u1" placeholder="Markup" title="Markup"  onchange="validate_balance(this.id)" value='<?= $row_tab3['markup'] ?>' style="width: 100px;" /></td>
						<td><select name="meal_plan_u1" id="meal_plan_u1" style="width: 110px" class="form-control app_select2" title="Meal Plan">
							<option value='<?= $row_tab3['meal_plan']?>'><?= $row_tab3['meal_plan']?></option>
							<?php get_mealplan_dropdown(); ?></td>
						<td><input type="hidden" id="entry_id" name="entry_id" value='<?= $row_tab3['entry_id']?>'/></td>
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
				<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab2()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp Previous</button>
				&nbsp;&nbsp;
				<button class="btn btn-info btn-sm ico_right">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
			</div>
		</div>

</form>
<?= end_panel() ?>

<script>
$('#to_date,#from_date').datetimepicker({ timepicker:false, format:'d-m-Y' });

function switch_to_tab2(){ 
	$('#tab3_head').removeClass('active');
	$('#tab2_head').addClass('active');
	$('.bk_tab').removeClass('active');
	$('#tab2').addClass('active');
	$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);
 }

$('#frm_tab3').validate({
	rules:{

	},
	submitHandler:function(form){
		var base_url = $('#base_url').val();

		var tab3_count = $('#tab3_count').val();
		var tab3_table_id = (tab3_count == 0)?'table_hotel_tarrif3':'table_hotel_tarrif';
    var table = document.getElementById(tab3_table_id);
		var rowCount = table.rows.length;

    for(var i=0; i<rowCount; i++){
      var row = table.rows[i];
      if(row.cells[0].childNodes[0].checked){
          var room_cat = row.cells[2].childNodes[0].value;
          var from_date = row.cells[3].childNodes[0].value;
          var to_date = row.cells[4].childNodes[0].value;
          if(room_cat==''){
            error_msg_alert('Select Room Category in Row-'+(i+1));
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
      }
    }

	  $('#tab3_head').addClass('done');
		$('#tab4_head').addClass('active');
		$('.bk_tab').removeClass('active');
		$('#tab4').addClass('active');
		$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);
	}
});
</script>