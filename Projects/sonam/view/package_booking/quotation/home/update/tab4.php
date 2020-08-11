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

		<div class="row">

			<div class="col-xs-12">
				<h3 class="editor_title">Group Costing</h3>
				<div class="panel panel-default panel-body app_panel_style">
					<div class="row mg_bt_20_sm_xs">
					    <div class="col-xs-12">

					        <div class="table-responsive">
					        <table id="tbl_package_tour_quotation_dynamic_costing" name="tbl_package_tour_quotation_dynamic_costing" class="table no-marg border_0">
							<?php
							$count = 0;
							$sq_q_costing = mysql_query("select * from package_tour_quotation_costing_entries where quotation_id='$quotation_id' ");
							while($row_q_costing = mysql_fetch_assoc($sq_q_costing)){
								$count++;
								$sq_package = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id = '$row_q_costing[package_id]'"));
								$package_id = $sq_package['package_name'];	
								  
								$add_class1 = '';
								if($role == 'B2b'){
									$add_class1 = "hidden";
								} 
								else{
									 $add_class1 = "text";
								}							
							?>
					            <tr>

					                <td class="header_btn"><input class="css-checkbox" id="chk_costing1" type="checkbox" checked disabled><span class="css-label" for="chk_costing1" > </span></td>

					                <td class="header_btn"><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /><span>SR.NO</span></td>

					                <td class="header_btn"><input type="text" id="tour_cost" name="tour_cost" placeholder="Hotel Cost" title="Hotel Cost" onchange="validate_balance(this.id);quotation_cost_calculate1();" value="<?php echo $row_q_costing['tour_cost']; ?>" style="width:100px"><span>Hotel Cost</span></td>    

								    <td class="header_btn"><input type="text" id="transport_cost" name="transport_cost" placeholder="Transport Cost" title="Transport Cost" onchange="validate_balance(this.id);quotation_cost_calculate1()" value="<?php echo $row_q_costing['transport_cost']; ?>" style="width:100px"><span>Transport Cost</span></td>  
									
								    <td class="header_btn"><input type="text" id="excursion_cost" name="excursion_cost" onchange="quotation_cost_calculate1(); validate_balance(this.id)"  placeholder="Excursion Cost" title="Excursion Cost" value="<?= $row_q_costing['excursion_cost'] ?>" style="width:100px"><span>Excursion Cost</span></td>

								    <td class="header_btn"><input type="<?= $add_class1 ?>" id="markup_cost" name="markup_cost" onchange="quotation_cost_calculate1(); validate_balance(this.id)"  placeholder="Markup Cost(%)" title="Markup Cost(%)" style="width:100px" value="<?= $row_q_costing['markup_cost'] ?>"><span>Markup(%)</span></td>
									
									<td class="header_btn"><input type="<?= $add_class1 ?>" id="markup_cost_subtotal" name="markup_cost_subtotal" onchange="quotation_cost_calculate1(this.id); validate_balance(this.id)" style="width:100px"  placeholder="Markup Subtotal" title="Markup Subtotal" value="<?= $row_q_costing['markup_subtotal'] ?>"><span>Markup Subtotal</span></td>	

								    <td class="header_btn"><select name="taxation_id1" id="taxation_id1" title="Tax" onchange=" quotation_cost_calculate1(); generic_tax_reflect(this.id, 'service_tax', 'quotation_cost_calculate1');" style="width: 110px;">
										<?php
										if($row_q_costing['taxation_id']=="" || $row_q_costing['taxation_id']==0){
										?>
										<?php get_taxation_dropdown(); ?>
										<?php }
										else{

											$sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_q_costing[taxation_id]'"));

											$sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));

											?>

								         <option value="<?= $sq_taxation['taxation_id'] ?>"><?= $sq_tax_type['tax_type'].'-'.$sq_taxation['tax_in_percentage'] ?></option>

								         <?php  get_taxation_dropdown();

								           }

								           ?>


										
								        </select><span>Tax</span></td>

					    			<td class="header_btn"><input type="hidden" id="service_tax" name="service_tax" value="<?= $row_q_costing['service_tax'] ?>"></td>

					    			<td class="header_btn"><input type="text" id="service_tax_subtotal" name="service_tax_subtotal" readonly placeholder="Tax Amount" title="Tax Amount" value="<?= $row_q_costing['service_tax_subtotal'] ?>" style="width:100px"><span>Tax Amount</span></td>

					    			<td class="header_btn"><input type="text" id="total_tour_cost" class="amount_feild_highlight text-right" name="total_tour_cost" placeholder="Total Cost" title="Total Cost" value="<?= $row_q_costing['total_tour_cost'] ?>" style="width: 100px;"  readonly><span>Total Cost</span></td>

					                <td class="header_btn"><input type="text" id="package_name1" name="package_name1" placeholder="Package Name" title="Package Name" value="<?php echo $package_id; ?>" style="display: none" readonly></td> 

					                <td class="header_btn"><input type="hidden" value="<?= $row_q_costing['id'] ?>"></td>

					            </tr>

					            <?php

		        				}

		        			?>	                                   

					        </table>

					        </div>

					    </div>

					</div>
				</div> 
		    </div>

	</div>
	
	<div class="row">
	<div class="col-xs-12">
	<h3 class="editor_title">Travel & Other Costing for group</h3>
		<div class="panel panel-default panel-body app_panel_style">
			<!-- Other costs -->
			<div class="row">
				<div class="col-md-2 header_btn col-xs-12 mg_bt_10">
					<span>Train Cost</span>
				 	<input type="text" id="train_cost1" name="train_cost" value="<?php echo $sq_quotation['train_cost']; ?>" placeholder="Train Cost" title="Train Cost" onchange="validate_balance(this.id)">
				</div>
				<div class="col-md-2 header_btn col-xs-12 mg_bt_10">
					<span>Flight Cost</span>
				 	<input type="text" id="flight_cost1" value="<?php echo $sq_quotation['flight_cost']; ?>" name="flight_cost" placeholder="Flight Cost" title="Flight Cost" onchange="validate_balance(this.id)">
				</div>
				<div class="col-md-2 header_btn mg_bt_10">
					<span>Cruise Cost</span>
				 	<input type="text" id="cruise_cost1" name="cruise_cost1" placeholder="Cruise Cost" value="<?php echo $sq_quotation['cruise_cost']; ?>" title="Cruise Cost" onchange="validate_balance(this.id)">
				</div>
				<div class="col-md-2 header_btn col-xs-12 mg_bt_10">
					<span>Visa Cost</span>
				 	<input type="text" id="visa_cost1" value="<?php echo $sq_quotation['visa_cost']; ?>" name="visa_cost" placeholder="Visa Cost" title="Visa Cost" onchange="validate_balance(this.id)">
				</div>
				<div class="col-md-2 header_btn mg_bt_10">
					<span>Guide Cost</span>
				 	<input type="text" id="guide_cost1" name="guide_cost1" placeholder="Guide Cost" value="<?php echo $sq_quotation['guide_cost']; ?>" title="Guide Cost" onchange="validate_balance(this.id)">
				</div>
				<div class="col-md-2 header_btn mg_bt_10">
					<span>Miscellaneous Cost</span>
					<input type="text" id="misc_cost1" name="misc_cost1" placeholder="Miscellaneous Cost" value="<?php echo $sq_quotation['misc_cost']; ?>" title="Miscellaneous Cost" onchange="validate_balance(this.id)">
				</div>
			</div>
					
		</div>
	</div>
	</div>
	<div class="row">
	<div class="col-xs-12">
	<h3 class="editor_title">Per Person Costing</h3>
		<div class="panel panel-default panel-body app_panel_style">			
		    <!-- Adult & child cost -->
		 	<div class="row">
		        <?php
		        $count = 0;
        		$sq_q_costing1 = mysql_query("select * from package_tour_quotation_costing_entries where quotation_id='$quotation_id' ");
        		while($row_q_costing1 = mysql_fetch_assoc($sq_q_costing1)){
        			$sq_package = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id = '$row_q_costing1[package_id]'"));				
				     ?>
					 <div class="col-md-2">
						<span>Adult Cost</span>
				     	<input type="text" id="adult_cost1" onchange="validate_balance(this.id);" name="adult_cost1" placeholder="Adult Cost" title="Adult Cost" value="<?= $row_q_costing1['adult_cost'] ?>">
				     </div>
				     <div class="col-md-2">
						<span>Child Cost</span>
				     	<input type="text" id="child_cost1" onchange="validate_balance(this.id);" name="child_cost1" placeholder="Child Cost" title="Child Cost" value="<?= $row_q_costing1['child_cost'] ?>">
				     </div>
					 <div class="col-md-2">
						<span>Infant Cost</span>
				     	<input type="text" id="infant_cost1" onchange="validate_balance(this.id);" name="infant_cost1" placeholder="Infant Cost" title="Infant Cost" value="<?= $row_q_costing1['infant_cost'] ?>">
				     </div>
				     <div class="col-md-2">
						<span>Child with Bed Cost</span>
				     	<input type="text" id="child_with1" onchange="validate_balance(this.id);" name="child_with1" placeholder="Child with Bed Cost" title="Child with Bed Cost" value="<?= $row_q_costing1['child_with'] ?>">
				     </div>
					 <div class="col-md-2">
						<span>Child w/o Bed Cost</span>
				     	<input type="text" id="child_without1" onchange="validate_balance(this.id);" name="child_without1" placeholder="Child w/o Cost" title="Child w/o Cost" value="<?= $row_q_costing1['child_without'] ?>">
				     </div>
				     <div class="col-md-2">
						<span>Extra Bed Cost</span>
				     	<input type="text" id="extra_bed1" onchange="validate_balance(this.id);" name="extra_bed1" placeholder="Extra Bed Cost" title="Extra Bed Cost" value="<?= $row_q_costing1['extra_bedc'] ?>">
				     </div>
		        <?php } ?>
			</div>
		</div>
	</div>
	</div>
	<div class="row mg_tp_20">
	  <div class="col-md-6 col-sm-12">
		<div class="col-md-8 col-sm-12">
			<select name="costing_type1" id="costing_type1" title="Select Costing type" class="form-control">
			<?php  $costing_type = ($sq_quotation['costing_type'] == 1) ? 'Group Costing':'Per Person costing';?>
				<option value="<?= $sq_quotation['costing_type'] ?>"><?= $costing_type ?></option>
				<option value="1">Group Costing</option>
				<option value="2">Per Person costing</option>
			</select>
		</div>
	  </div>
      <div class="col-md-6 col-sm-12 text-right">
        <div class="div-upload">
          <div id="price_structure1" class="upload-button1"><span>Price Structure</span></div>
          <span id="photo_status" ></span>
          <ul id="files" ></ul>
          <input type="hidden" id="upload_url1" name="upload_url1" value="<?= $sq_quotation['price_str_url'] ?>">
        </div>
      </div>
    </div>
	<div class="row mg_tp_20">
		<div class="col-md-6 col-sm-12">
			<span style="color: red;" class="note">Note : Group Costing or Per person costing to display on quotation</span>
		</div>
		<div class="col-md-6 text-right col-sm-12"> 
        <span style="color: red;" class="note">Note : Only Excel or Word files are allowed</span>  
		</div>
	</div>
	<div class="row mg_tp_20 text-center">
		<div class="col-xs-12">
			<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab3()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>
			&nbsp;&nbsp;
			<button class="btn btn-sm btn-success" id="btn_quotation_update"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>
		</div>
	</div>
</form>
<?= end_panel(); ?>
<script>

function switch_to_tab3(){
	$('#tab4_head').removeClass('active');
	$('#tab3_head').addClass('active');
	$('.bk_tab').removeClass('active');
	$('#tab3').addClass('active');
	$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);
}

upload_price_struct();

function upload_price_struct()
{
    var btnUpload=$('#price_structure1');
    $(btnUpload).find('span').text('Price Structure');
    
    new AjaxUpload(btnUpload, {
      action: '../upload_price_structure.php',
      name: 'uploadfile',
      onSubmit: function(file, ext)
      {  
        if (! (ext && /^(xlsx|docx)$/.test(ext))){ 
         error_msg_alert('Only Excel or word files are allowed');
         return false;
        }
        $(btnUpload).find('span').text('Uploading...');
      },
      onComplete: function(file, response){
        if(response==="error"){          
          error_msg_alert("File is not uploaded.");           
          $(btnUpload).find('span').text('Upload');
        }else
        { 
          $(btnUpload).find('span').text('Uploaded');
          $("#upload_url1").val(response);
        }
      }
    });
}


function quotation_cost_calculate1()
{
	var tour_cost = $('#tour_cost').val();  
	var transport_cost = $('#transport_cost').val();
	var service_tax = $('#service_tax').val();
	var markup_cost = $('#markup_cost').val();
  	var markup_cost_subtotal = $('#markup_cost_subtotal').val();
	var excursion_cost = $('#excursion_cost').val();


	  if(tour_cost==""){ tour_cost = 0;}

	  if(transport_cost==""){ transport_cost = 0;}

	  if(service_tax==""){service_tax = 0;}

	  if(markup_cost==""){ markup_cost = 0;}
	  if(markup_cost_subtotal==""){markup_cost_subtotal = 0;}
	  if(excursion_cost==""){ excursion_cost = 0;}
	 
	  var sub_total = parseFloat(tour_cost) + parseFloat(transport_cost) + parseFloat(excursion_cost) ;
	  
	  if(parseFloat(markup_cost) == 0){
	    var markup_total = parseFloat(markup_cost_subtotal);
	    total = markup_total + sub_total;
	  }
	  else{
	    var markup_total = (parseFloat(sub_total)/100) * parseFloat(markup_cost);
	    total = markup_total + sub_total;
	  }
	    $('#markup_cost_subtotal').val(markup_total.toFixed(2));

		var service_tax_amount = (parseFloat(total)/100) * parseFloat(service_tax);
		total_tour_cost = parseFloat(total) + parseFloat(service_tax_amount);

		$('#service_tax_subtotal').val(service_tax_amount.toFixed(2));
		$('#total_tour_cost').val(total_tour_cost.toFixed(2));

}

$('#frm_tab4').validate({

	rules:{

		markup_cost1 : { required : true, number : true },

		tour_cost1 : { required : true, number: true },

	},

	submitHandler:function(form){



		var quotation_id = $('#quotation_id1').val();

		var enquiry_id = $('#enquiry_id12').val();

		var package_id = $('#package_id1').val();
		var tour_name = $('#tour_name12').val();
		var from_date = $('#from_date12').val();

		var to_date = $('#to_date12').val();

		var total_days = $('#total_days12').val();

		var customer_name = $('#customer_name12').val();

		var email_id = $('#email_id12').val();
		var mobile_no = $('#mobile_no12').val();

		var total_adult = $('#total_adult12').val();

		var total_children = $('#total_children12').val();

		var total_infant = $('#total_infant12').val();

		var total_passangers = $('#total_passangers12').val();

		var children_without_bed = $('#children_without_bed12').val();

		var children_with_bed = $('#children_with_bed12').val();		

		var quotation_date = $('#quotation_date').val();

		var booking_type = $('#booking_type2').val();

		var train_cost = $('#train_cost1').val();

		var flight_cost = $('#flight_cost1').val();
		var cruise_cost = $('#cruise_cost1').val();
		var visa_cost = $('#visa_cost1').val();
		var guide_cost = $('#guide_cost1').val();
		var misc_cost = $('#misc_cost1').val();
		var price_str_url = $("#upload_url1").val();
		

		var checked_programe_arr = new Array();
		var day_count_arr = new Array();
		var attraction_arr = new Array();
		var program_arr = new Array();
		var stay_arr = new Array();
		var meal_plan_arr = new Array();
		var package_p_id_arr = new Array();

		  var table = document.getElementById("dynamic_table_list_update");
		  var rowCount = table.rows.length;	
		  for(var i=0; i<rowCount; i++)
		  {		 
			    var row = table.rows[i];		   
			    // if(row.cells[0].childNodes[0].checked)
			    // {
			       var checked_programe = row.cells[0].childNodes[0].checked;
			       var day_count = row.cells[1].childNodes[0].value;
			       var attraction = row.cells[2].childNodes[0].value;         
			       var program = row.cells[3].childNodes[0].value;         
			       var stay = row.cells[4].childNodes[0].value;         
			       var meal_plan = row.cells[5].childNodes[0].value;  
				   var package_id1 = row.cells[6].childNodes[0].value;  
				   
			       if(program==""){
			          error_msg_alert('Daywise program is mandatory in row'+(i+1));
			          return false;
			       }
			       	   checked_programe_arr.push(checked_programe);
					   day_count_arr.push(day_count);
				       attraction_arr.push(attraction);
				       program_arr.push(program);
				       stay_arr.push(stay);
				       meal_plan_arr.push(meal_plan);
				       package_p_id_arr.push(package_id1);				 
			    // }      
		  }



		//Train Information
		var train_status_arr = new Array();
		var train_from_location_arr = new Array();
		var train_to_location_arr = new Array();
		var train_class_arr = new Array();
		var train_arrival_date_arr = new Array();
		var train_departure_date_arr = new Array();
		var train_id_arr = new Array();

		var table = document.getElementById("tbl_package_tour_quotation_dynamic_train");
		var rowCount = table.rows.length;			  

		for(var i=0; i<rowCount; i++){
			var row = table.rows[i];
			var status = row.cells[0].childNodes[0].checked;
			var train_from_location1 = row.cells[2].childNodes[0].value;         
			var train_to_location1 = row.cells[3].childNodes[0].value;   
			var train_class = row.cells[4].childNodes[0].value;  
			var train_departure_date = row.cells[5].childNodes[0].value;          
			var train_arrival_date = row.cells[6].childNodes[0].value;

			if(row.cells[7] && row.cells[7].childNodes[0]){
			var train_id = row.cells[7].childNodes[0].value;
			}
			else{
			var train_id = "";
			}    

			train_status_arr.push(status);
			train_from_location_arr.push(train_from_location1);
			train_to_location_arr.push(train_to_location1);
			train_class_arr.push(train_class);
			train_arrival_date_arr.push(train_arrival_date);
			train_departure_date_arr.push(train_departure_date);
			train_id_arr.push(train_id); 
		}

		//Plane Information
		var plane_status_arr = new Array();
		var plane_from_city_arr = new Array();
		var plane_to_city_arr = new Array();
		var plane_from_location_arr = new Array();
		var plane_to_location_arr = new Array();
		var airline_name_arr = new Array();
		var plane_class_arr = new Array();
		var arraval_arr = new Array();
		var dapart_arr = new Array();
		var plane_id_arr = new Array();

		var table = document.getElementById("tbl_package_tour_quotation_dynamic_plane");
		var rowCount = table.rows.length;
		  
		  for(var i=0; i<rowCount; i++){
		    var row = table.rows[i];

			var status = row.cells[0].childNodes[0].checked;			
			var plane_from_city = row.cells[2].childNodes[0].value;         
			var plane_from_location1 = row.cells[3].childNodes[0].value;	
			var plane_to_city = row.cells[4].childNodes[0].value;         
			var plane_to_location1 = row.cells[5].childNodes[0].value;
			var airline_name = row.cells[6].childNodes[0].value;  
			var plane_class = row.cells[7].childNodes[0].value;  
			var dapart1 = row.cells[8].childNodes[0].value;       
			var arraval1 = row.cells[9].childNodes[0].value;

			if(row.cells[10] && row.cells[10].childNodes[0]){
			var plane_id = row.cells[10].childNodes[0].value;
			}
			else{
			var plane_id = "";
			}

			plane_status_arr.push(status);
			plane_from_city_arr.push(plane_from_city);
			plane_to_city_arr.push(plane_to_city);
			plane_from_location_arr.push(plane_from_location1);
			plane_to_location_arr.push(plane_to_location1);
			airline_name_arr.push(airline_name);
			plane_class_arr.push(plane_class);
			arraval_arr.push(arraval1);
			dapart_arr.push(dapart1);
			plane_id_arr.push(plane_id);
		}
 
		//Cruise Information
		var cruise_status_arr = new Array();
		var cruise_departure_date_arr = new Array();
		var cruise_arrival_date_arr = new Array();
		var route_arr = new Array();
		var cabin_arr = new Array();
		var sharing_arr = new Array();
		var c_entry_id_arr = new Array();

		var table = document.getElementById("tbl_dynamic_cruise_quotation");
		var rowCount = table.rows.length;

		  for(var i=0; i<rowCount; i++){
		    var row = table.rows[i];

			var status = row.cells[0].childNodes[0].checked;	
			var cruise_from_date = row.cells[2].childNodes[0].value;    
			var cruise_to_date = row.cells[3].childNodes[0].value;    
			var route = row.cells[4].childNodes[0].value;    
			var cabin = row.cells[5].childNodes[0].value;  
			var sharing = row.cells[6].childNodes[0].value; 
			var c_entry_id = row.cells[7].childNodes[0].value;   
			
			if(c_entry_id == ''){
				c_entry_id = 0;
			}
			else{
				c_entry_id = row.cells[7].childNodes[0].value;   
			}
			cruise_status_arr.push(status);
			cruise_departure_date_arr.push(cruise_from_date);
			cruise_arrival_date_arr.push(cruise_to_date);
			route_arr.push(route);
			cabin_arr.push(cabin);
			sharing_arr.push(sharing);
			c_entry_id_arr.push(c_entry_id);   
		  }

		//Hotel Information
		var hotel_status_arr = new Array();
		var city_name_arr = new Array();
		var hotel_name_arr = new Array();
		var hotel_cat_arr = new Array();
		var hotel_stay_days_arr = new Array();
		var hotel_type_arr = new Array();
		var package_name_arr = new Array();
		var total_rooms_arr = new Array();
		var hotel_cost_arr = new Array();
		var extra_bed_cost_arr = new Array();
		var extra_bed_arr = new Array();
		var hotel_id_arr = new Array();

		var table = document.getElementById("tbl_package_tour_quotation_dynamic_hotel_update");
		var rowCount = table.rows.length;
 
		for(var i=0; i<rowCount; i++){

			var row = table.rows[i];
			var status = row.cells[0].childNodes[0].checked;
			var city_name = row.cells[2].childNodes[0].value;
			var hotel_id = row.cells[3].childNodes[0].value;
			var hotel_cat = row.cells[4].childNodes[0].value;    
			var hotel_type = row.cells[5].childNodes[0].value;
			var hotel_stay_days1 = row.cells[6].childNodes[0].value;   
			var total_rooms = row.cells[7].childNodes[0].value;
			var extra_bed = row.cells[8].childNodes[0].value;		       
			var package_name1 = row.cells[9].childNodes[0].value;   
			var hotel_cost = row.cells[10].childNodes[0].value;
			var extra_bed_cost = row.cells[12].childNodes[0].value;
			
			if(row.cells[13] && row.cells[13].childNodes[0]){
				var hotel_id1 = row.cells[13].childNodes[0].value;
			}
			else{
				var hotel_id1 = '';
			}
			
			hotel_status_arr.push(status);
			city_name_arr.push(city_name);
			hotel_name_arr.push(hotel_id);
			hotel_cat_arr.push(hotel_cat);
			hotel_stay_days_arr.push(hotel_stay_days1);
			hotel_type_arr.push(hotel_type);
			total_rooms_arr.push(total_rooms);
			extra_bed_arr.push(extra_bed); 
			package_name_arr.push(package_name1);
			hotel_cost_arr.push(hotel_cost);
			extra_bed_cost_arr.push(extra_bed_cost);
			hotel_id_arr.push(hotel_id1);
		}

	 	//Transport Information
		var transport_status_arr = new Array();
		var vehicle_name_arr = new Array();
		var start_date_arr = new Array();
		var end_date_arr = new Array();
		var package_name_arr1 = new Array();
		var transport_cost_arr1 = new Array();
		var transport_id_arr = new Array();

		var table = document.getElementById("tbl_package_tour_quotation_dynamic_transport");
		var rowCount = table.rows.length;

		  for(var i=0; i<rowCount; i++){
		    var row = table.rows[i];
			var status = row.cells[0].childNodes[0].checked;
			var vahicle_name = row.cells[2].childNodes[0].value;         
			var start_date = row.cells[3].childNodes[0].value;         
			var end_date = row.cells[4].childNodes[0].value;   
			var package_name2 = row.cells[5].childNodes[0].value;
			var transport_cost1 = row.cells[6].childNodes[0].value; 

			if(row.cells[8] && row.cells[8].childNodes[0]){
			var transport_id = row.cells[8].childNodes[0].value; 
			}else{
			var transport_id = '';
			}
			transport_status_arr.push(status);
			vehicle_name_arr.push(vahicle_name);
			start_date_arr.push(start_date);
			end_date_arr.push(end_date);
			package_name_arr1.push(package_name2);
			transport_cost_arr1.push(transport_cost1);
			transport_id_arr.push(transport_id);
		}

		//Excursion Info
		var table = document.getElementById("tbl_package_tour_quotation_dynamic_excursion");
		var rowCount = table.rows.length;

		var exc_status_arr = new Array();
		var city_name_arr_e = new Array();
		var excursion_name_arr = new Array();
		var excursion_amt_arr = new Array();
		var excursion_id_arr = new Array();

	    for(var e=0; e<rowCount; e++){
		    var row = table.rows[e];

			var status = row.cells[0].childNodes[0].checked;
			var city_name = row.cells[2].childNodes[0].value;         
			var excursion_name = row.cells[3].childNodes[0].value;         
			var excursion_amount = row.cells[4].childNodes[0].value;

			if(row.cells[5] && row.cells[5].childNodes[0]){
				var excursion_id = row.cells[5].childNodes[0].value;
			}
			else{
				var excursion_id = "";
			} 
			exc_status_arr.push(status);
			city_name_arr_e.push(city_name);
			excursion_name_arr.push(excursion_name);
			excursion_amt_arr.push(excursion_amount);
			excursion_id_arr.push(excursion_id);	
			transport_status_arr.push(status);   
	  	}

		//Costing Information
		var tour_cost_arr = new Array();
		var transport_cost_arr = new Array();
		var markup_cost_arr = new Array();
		var markup_subtotal_arr = new Array();
		var excursion_cost_arr = new Array();
		var taxation_id_arr = new Array();
		var service_tax_arr = new Array();
		var service_tax_subtotal_arr = new Array();
		var total_tour_cost_arr = new Array();
		var package_name_arr2 = new Array();
		var costing_id_arr = new Array();

		var table = document.getElementById("tbl_package_tour_quotation_dynamic_costing");
		var rowCount = table.rows.length;
		  

		  for(var i=0; i<rowCount; i++){

		    var row = table.rows[i];		     

		    if(row.cells[0].childNodes[0].checked){

		       var tour_cost = row.cells[2].childNodes[0].value;         
		   	   var transport_cost = row.cells[3].childNodes[0].value;         	
		       var excursion_cost = row.cells[4].childNodes[0].value;        
		       var markup_cost = row.cells[5].childNodes[0].value;     
		       var markup_cost_subtotal = row.cells[6].childNodes[0].value;          
		       var taxation_id = row.cells[7].childNodes[0].value;   
		       var service_tax = row.cells[8].childNodes[0].value;   
		       var service_tax_subtotal = row.cells[9].childNodes[0].value;   
		       var total_tour_cost = row.cells[10].childNodes[0].value;   
		       var package_name3 = row.cells[11].childNodes[0].value;  
		       var costing_id = row.cells[12].childNodes[0].value;  

		       if(tour_cost==""){
		          error_msg_alert('Select Tour cost in row'+(i+1));
		          return false;
		       }

		       tour_cost_arr.push(tour_cost);
		       transport_cost_arr.push(transport_cost);
		       markup_cost_arr.push(markup_cost);
		       markup_subtotal_arr.push(markup_cost_subtotal);
		       excursion_cost_arr.push(excursion_cost);
		       taxation_id_arr.push(taxation_id);
		       service_tax_arr.push(service_tax);
		       service_tax_subtotal_arr.push(service_tax_subtotal);
		       total_tour_cost_arr.push(total_tour_cost);
		       package_name_arr2.push(package_name3);
		       costing_id_arr.push(costing_id);
		    }      
		  }

		var child_cost = $('#child_cost1').val();
		var adult_cost = $('#adult_cost1').val();
		var infant_cost = $('#infant_cost1').val();
		var child_with = $('#child_with1').val();
		var child_without = $('#child_without1').val();
		var extra_bed = $('#extra_bed1').val();
		var costing_type = $('#costing_type1').val();

		var inclusions = $('#inclusions1').val();
		var exclusions = $('#exclusions1').val();
		var image_url_id = $('#image_url_id').val();
		var pckg_daywise_url = $('#pckg_daywise_url').val();
		var image_url = $('#delete_image_url').val();
		var updated_url = pckg_daywise_url + image_url;
		var base_url = $('#base_url').val();

		$("#vi_confirm_box").vi_confirm_box({
		callback: function(result){
		if(result=="yes")
		{					
			$('#btn_quotation_update').button('loading');
			$.ajax({

				type:'post',
				url: base_url+'controller/package_tour/quotation/quotation_update.php',
				data:{ quotation_id : quotation_id,package_id : package_id, tour_name : tour_name, from_date : from_date, to_date : to_date, total_days : total_days, customer_name : customer_name, email_id : email_id,mobile_no : mobile_no, total_adult : total_adult, total_children : total_children, total_infant : total_infant, total_passangers : total_passangers, children_without_bed : children_without_bed, children_with_bed : children_with_bed, quotation_date : quotation_date, booking_type : booking_type,train_cost : train_cost,flight_cost : flight_cost,cruise_cost:cruise_cost, visa_cost : visa_cost, train_from_location_arr : train_from_location_arr, train_to_location_arr : train_to_location_arr, train_class_arr : train_class_arr, train_arrival_date_arr : train_arrival_date_arr, train_departure_date_arr : train_departure_date_arr, train_id_arr : train_id_arr,plane_from_city_arr : plane_from_city_arr,plane_to_city_arr : plane_to_city_arr, plane_from_location_arr : plane_from_location_arr, plane_to_location_arr : plane_to_location_arr, plane_id_arr : plane_id_arr,airline_name_arr : airline_name_arr, plane_class_arr : plane_class_arr, arraval_arr : arraval_arr, dapart_arr : dapart_arr,cruise_departure_date_arr : cruise_departure_date_arr, cruise_arrival_date_arr : cruise_arrival_date_arr, route_arr : route_arr,cabin_arr : cabin_arr,sharing_arr : sharing_arr,c_entry_id_arr : c_entry_id_arr, city_name_arr: city_name_arr, hotel_name_arr : hotel_name_arr,hotel_cat_arr:hotel_cat_arr, hotel_type_arr : hotel_type_arr, hotel_stay_days_arr : hotel_stay_days_arr,package_name_arr : package_name_arr,total_rooms_arr : total_rooms_arr,hotel_cost_arr : hotel_cost_arr,extra_bed_arr : extra_bed_arr,extra_bed_cost_arr : extra_bed_cost_arr,hotel_id_arr : hotel_id_arr,vehicle_name_arr : vehicle_name_arr,start_date_arr : start_date_arr,end_date_arr : end_date_arr,package_name_arr1 : package_name_arr1,tour_cost_arr : tour_cost_arr, markup_cost_arr : markup_cost_arr,markup_subtotal_arr : markup_subtotal_arr,taxation_id_arr : taxation_id_arr,service_tax_arr : service_tax_arr,service_tax_subtotal_arr : service_tax_subtotal_arr,total_tour_cost_arr : total_tour_cost_arr,package_name_arr2 : package_name_arr2,transport_cost_arr1 : transport_cost_arr1,transport_cost_arr : transport_cost_arr,costing_id_arr : costing_id_arr, enquiry_id : enquiry_id ,transport_id_arr : transport_id_arr,city_name_arr_e : city_name_arr_e,excursion_name_arr : excursion_name_arr,excursion_amt_arr : excursion_amt_arr,excursion_id_arr : excursion_id_arr,excursion_cost_arr : excursion_cost_arr,guide_cost : guide_cost,misc_cost :misc_cost,child_cost : child_cost,adult_cost : adult_cost,infant_cost :infant_cost,child_with :child_with,child_without: child_without,extra_bed: extra_bed,price_str_url : price_str_url,attraction_arr : attraction_arr,program_arr : program_arr,stay_arr : stay_arr,meal_plan_arr : meal_plan_arr,package_p_id_arr : package_p_id_arr,inclusions : inclusions,exclusions : exclusions,checked_programe_arr : checked_programe_arr,day_count_arr:day_count_arr,costing_type :costing_type,train_status_arr:train_status_arr,plane_status_arr:plane_status_arr,cruise_status_arr:cruise_status_arr,hotel_status_arr:hotel_status_arr,transport_status_arr:transport_status_arr,exc_status_arr:exc_status_arr,updated_url:updated_url,image_url_id:image_url_id},

				success: function(message){
	                	$('#btn_quotation_update').button('reset');
	                	var msg = message.split('--');
						if(msg[0]=="error"){
							error_msg_alert(msg[1]);
						}
						else{
							$('#vi_confirm_box').vi_confirm_box({
							            false_btn: false,
							            message: message,
							            true_btn_text:'Ok',
							    callback: function(data1){
							        if(data1=="yes"){
							          $('#btn_quotation_update').button('reset');
						        	  $('#quotation_update_modal').modal('hide');
						        	  window.location.href = base_url+'view/package_booking/quotation/home/index.php';
							        }
							      }
							});
						}
	                }
			});
	    }
	    else{
	    	$('#btn_quotation_update').button('reset'); 
	    }
        }
    });
	}
});
</script>