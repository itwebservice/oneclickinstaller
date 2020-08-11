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
	<input type="hidden" name="pckg_id_arr" id="pckg_id_arr"/>
	<input type="hidden" name="pckg_day_id_arr" id="pckg_day_id_arr"/>
	<input type="hidden" name="pckg_img_arr" id="pckg_img_arr"/>
	<div class="row">
		<div class="col-md-12 app_accordion">
  			<div class="panel-group main_block" id="accordion" role="tablist" aria-multiselectable="true">

				<!-- Train Information -->
				<div class="accordion_content main_block mg_bt_10">
					
					<div class="panel panel-default main_block">
						<div class="panel-heading main_block" role="tab" id="heading_<?= $count ?>">
					        <div class="Normal main_block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1" id="collapsed1">          
					        	<div class="col-md-12"><span>Train Information</span></div>
					        </div>
					    </div>
					    <div id="collapse1" class="panel-collapse collapse in main_block" role="tabpanel" aria-labelledby="heading1">
					        <div class="panel-body">
					        	<div class="row">
								    <div class="col-xs-12 text-right mg_bt_20_sm_xs">
								        <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_package_tour_quotation_dynamic_train')"><i class="fa fa-plus"></i></button>
										<button type="button" class="btn btn-pdf btn-sm" onClick="deleteRow('tbl_package_tour_quotation_dynamic_train')"><i class="fa fa-trash"></i></button>
								    </div>
								</div>
								<div class="row">
								    <div class="col-xs-12">
								        <div class="table-responsive">
								        <table id="tbl_package_tour_quotation_dynamic_train" name="tbl_package_tour_quotation_dynamic_train" class="table mg_bt_0 table-bordered pd_bt_51">
								            <tr>
								                <td><input class="css-checkbox" id="chk_tour_group1" type="checkbox"><label class="css-label" for="chk_tour_group1"> <label></td>
								                <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
								                <td class="col-md-2"><select id="train_from_location1" onchange="validate_location('train_to_location1','train_from_location1');" class="app_select2 form-control" name="train_from_location1" title="From Location" style="width:100%">
										                <option value="">*From</option>
										                <?php 
										                    $sq_city = mysql_query("select * from city_master");
										                    while($row_city = mysql_fetch_assoc($sq_city))
										                    {
										                     ?>
										                        <option value="<?php echo $row_city['city_name']; ?>"><?php echo $row_city['city_name']; ?></option>
										                     <?php   
										                    }    
										                ?>
										            </select>
								                </td>
								                 <td class="col-md-2"><select id="train_to_location1"  onchange="validate_location('train_from_location1','train_to_location1');" class="app_select2 form-control" title="To Location" name="train_to_location1" style="width:100%">
									                <option value="">*To</option>
									                <?php 
									                    $sq_city = mysql_query("select * from city_master");
									                    while($row_city = mysql_fetch_assoc($sq_city))
									                    {
									                     ?>
									                        <option value="<?php echo $row_city['city_name']; ?>"><?php echo $row_city['city_name']; ?></option>
									                     <?php   
									                    }    
									                ?>
									            </select></td>
									            <td class="col-md-2"><select name="train_class" id="train_class1" title="Class">
									            	<option value="">Class</option>
									            	<option value="1A">1A</option>
												    <option value="2A">2A</option>
												    <option value="3A">3A</option>
												    <option value="FC">FC</option>
												    <option value="CC">CC</option>
												    <option value="SL">SL</option>
												    <option value="2S">2S</option>
									            </select></td>
									            <td class="col-md-3"><input type="text" id="train_departure_date" name="train_departure_date" placeholder="Departure Date and time" title="Departure Date and time" class="app_datetimepicker" onchange="get_to_datetime(this.id,'train_arrival_date')" value="<?= date('d-m-Y H:i') ?>"  style="width:100%"></td>
									            <td class="col-md-3"><input type="text" id="train_arrival_date" name="train_arrival_date" placeholder="Arrival Date and time" title="Arrival Date and time" class="app_datetimepicker" value="<?= date('d-m-Y H:i') ?>" style="width:100%"></td>
								            </tr>                                
								        </table>
								        </div>
								    </div>
								</div> 
					        </div>
					    </div>
					</div>
				</div>

				<!-- Flight Information -->
				<div class="accordion_content main_block mg_bt_10">
					
					<div class="panel panel-default main_block">
						<div class="panel-heading main_block" role="tab" id="heading_<?= $count ?>">
					        <div class="Normal main_block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="true" aria-controls="collapse2" id="collapsed2">                  
					        	<div class="col-md-12"><span>Flight Information</span></div>
					        </div>
					    </div>
					    <div id="collapse2" class="panel-collapse collapse main_block" role="tabpanel" aria-labelledby="heading2">
					        <div class="panel-body">
								<div class="row">
								    <div class="col-xs-12 text-right mg_bt_20_sm_xs">
								        <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_package_tour_quotation_dynamic_plane')"><i class="fa fa-plus"></i></button>
										<button type="button" class="btn btn-pdf btn-sm" onClick="deleteRow('tbl_package_tour_quotation_dynamic_plane')"><i class="fa fa-trash"></i></button>
								    </div>
								</div>
								<div class="row">
								    <div class="col-xs-12">
								        <div class="table-responsive">
								        <table id="tbl_package_tour_quotation_dynamic_plane" name="tbl_package_tour_quotation_dynamic_plane" class="table mg_bt_0 table-bordered pd_bt_51">
								            <tr>
								                <td><input class="css-checkbox" id="chk_plan1" type="checkbox"><label class="css-label" for="chk_plan1"> <label></td>
								                <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>								                			
								               	<td><select id="from_city-1" name="from_city-1" style="width: 150px;" class="form-control" title="Select City Name" onchange="validate_location('to_city-1','from_city-1');airport_reflect(this.id)">
									                <?php get_cities_dropdown(); ?>
									            </select></td>		
								                <td><select id="plane_from_location-1"  class="form-control" title="Sector From " name="plane_from_location-1" style="width: 160px;">
										                <option value="">*Sector From</option>
										              
										            </select>
								                </td>
									        	<td><select id="to_city-1" name="to_city-1" style="width: 150px;" class="form-control" title="Select City Name" onchange="validate_location('from_city-1','to_city-1');airport_reflect1(this.id)">
								                <?php get_cities_dropdown(); ?>
								                </select></td>
								                 <td><select id="plane_to_location-1" class="form-control"  title="Sector To" name="plane_to_location-1" style="width: 160px;">
									                    <option value="">*Sector To</option>
									            </select></td>
									            <td><select id="airline_name1" class="app_select2 form-control"  title="Airline Name" name="airline_name1" style="width: 120px;">
									                    <option value="">Airline Name</option>
									                    <?php get_airline_name_dropdown(); ?>
									            </select></td>
									            <td><select name="plane_class" id="plane_class1" title="Class"  style="width: 120px;">
									            	<option value="">Class</option>
									            	<option value="Economy">Economy</option>
								                    <option value="Premium Economy">Premium Economy</option>
								                    <option value="Business">Business</option>
								                    <option value="First Class">First Class</option>
									            </select></td>	            
									            <td><input type="text" id="txt_dapart1" name="txt_dapart" class="app_datetimepicker" placeholder="Departure Date and time" title="Departure Date and time" onchange="get_to_datetime(this.id,'txt_arrval1')" value="<?= date('d-m-Y H:i:s') ?>" style="width: 150px;" /></td>
									            <td><input type="text" id="txt_arrval1" name="txt_arrval" class="app_datetimepicker"  placeholder="Arrival Date and time" title="Arrival Date and time" value="<?= date('d-m-Y H:i:s') ?>" style="width: 150px;"/></td>
								            </tr>                                
								        </table>
								        </div>
								    </div>
								</div> 
					        </div>
					    </div>
					</div>
				</div>

				<!-- Cruise Information -->
				<div class="accordion_content main_block mg_bt_10">
					
					<div class="panel panel-default main_block">
						<div class="panel-heading main_block" role="tab" id="heading_<?= $count ?>">
					        <div class="Normal main_block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="true" aria-controls="collapse3" id="collapsed3">                  
					        	<div class="col-md-12"><span>Cruise Information</span></div>
					        </div>
					    </div>
					    <div id="collapse3" class="panel-collapse collapse main_block" role="tabpanel" aria-labelledby="heading3">
					        <div class="panel-body">
								<div class="row mg_bt_10">
								    <div class="col-md-12 text-right text_center_xs">
								        <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_dynamic_cruise_quotation')"><i class="fa fa-plus"></i></button>
										<button type="button" class="btn btn-pdf btn-sm" onClick="deleteRow('tbl_dynamic_cruise_quotation')"><i class="fa fa-trash"></i></button>
								    </div>
								</div>
								<div class="row mg_bt_10">
								    <div class="col-md-12">
								        <div class="table-responsive">
								        <table id="tbl_dynamic_cruise_quotation" name="tbl_dynamic_cruise_quotation" class="table table-bordered no-marg">
								            <tr>
								                <td><input class="css-checkbox" id="chk_cruise1" type="checkbox"><label class="css-label" for="chk_cruise1"><label></td>
								                <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
									            <td><input type="text" id="cruise_departure_date"  name="cruise_departure_date" placeholder="Departure Date and time"  title="Departure Date and time" class="app_datetimepicker" onchange="get_to_datetime(this.id,'cruise_arrival_date')" value="<?= date('d-m-Y H:i:s') ?>"></td>
									            <td><input type="text" id="cruise_arrival_date" name="cruise_arrival_date" placeholder="Arrival Date and time" title="Arrival Date and time" class="app_datetimepicker" value="<?= date('d-m-Y H:i:s') ?>"></td>
									            <td><input type="text" id="route" onchange="validate_spaces(this.id);validate_decimal(this.id);" name="route" placeholder="*Route" title="Route"></td>
									            <td><input type="text" id="cabin" onchange="validate_spaces(this.id);validate_decimal(this.id);" name="cabin" placeholder="*Cabin" title="Cabin"></td>
									            <td><select id="sharing" name="sharing" style="width:100%;" title="Sharing">
									            		<option value="">Sharing</option>
									            		<option value="Single">Single</option>
									            		<option value="Double">Double</option>
									            		<option value="Triple Quad">Triple Quad</option>
									                </select></td>
								            </tr>                                
								        </table>
								        </div>
								    </div>
								</div>
					        </div>
					    </div>
					</div>
				</div>

				<!-- Hotel Information -->
				<div class="accordion_content main_block mg_bt_10">
					
					<div class="panel panel-default main_block">
						<div class="panel-heading main_block" role="tab" id="heading_<?= $count ?>">
					        <div class="Normal main_block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="true" aria-controls="collapse4" id="collapsed4">                  
					        	<div class="col-md-12"><span>Hotel Information</span></div>
					        </div>
					    </div>
					    <div id="collapse4" class="panel-collapse collapse main_block" role="tabpanel" aria-labelledby="heading4">
					        <div class="panel-body">
								<div class="row">
								    <div class="col-xs-12">
								        <div class="table-responsive">
								        <table id="tbl_package_tour_quotation_dynamic_hotel" name="tbl_package_tour_quotation_dynamic_hotel" class="table mg_bt_0 table-bordered mg_bt_10">
								            <tr>
								                <td><input class="css-checkbox" id="chk_hotel1" type="checkbox" checked readonly><label class="css-label" for="chk_hotel1" > <label></td>
								                <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
							                    <td><select id="city_name1" name="city_name1" onchange="hotel_name_list_load(this.id);" class="city_master_dropdown" style="width:160px" title="Select City Name">
							                        <?php get_cities_dropdown(); ?>
							                      </select></td>
							                    <td><select id="hotel_name1" name="hotel_name1" onchange="hotel_type_load(this.id);get_hotel_cost(this.id);" style="width:160px" title="Select Hotel Name">
							                        <option value="">Hotel Name</option>
							                      </select></td>
												<td><select name="room_cat1" id="room_cat1" style="width:145px;" onchange="get_hotel_cost(this.id);" title="Room Category" class="form-control app_select2"><?php get_room_category_dropdown(); ?></select></td>
							                    <td><input type="text" id="hotel_type1" name="hotel_type1" placeholder="Hotel Type" title="Hotel Type" style="width:100px" readonly></td>
											    <td><input type="text" id="hotel_stay_days1" title="Total Nights" name="hotel_stay_days1" placeholder="Total Nights" onchange="validate_balance(this.id);"></td>
											    <td><input type="text" id="no_of_rooms1" title="Total Rooms" name="no_of_rooms1" placeholder="Total Rooms" onchange="validate_balance(this.id);"></td>
						                		<td><input type="text" id="extra_bed1" name="extra_bed1" title="Extra Bed" placeholder="Extra Bed" onchange="validate_balance(this.id);"></td>
								                <td><input type="text" id="package_name1" name="package_name1" placeholder="Package Name" title="Package Name" style="width:160px" readonly></td>  
						                		<td class="hidden"><input type="text" id="hotel_cost1" name="hotel_cost1" placeholder="Hotel Cost" title="Hotel Cost" style="display: none" onchange="validate_balance(this.id)"></td> 
						                		<td class="hidden"><input type="text" id="package_id1" name="package_id1" placeholder="Package ID" title="Package ID" style="display:none;"></td> 
						                		<td class="hidden"><input type="text" id="extra_bed_cost1" name="extra_bed_cost1" placeholder="Extra bed cost" title="Extra bed cost"  style="display: none" onchange="validate_balance(this.id)"></td> 
								            </tr>
								        </table>
								        </div>
								    </div>
								</div>
					        </div>
					    </div>
					</div>
				</div>

				<!-- Transport Information -->
				<div class="accordion_content main_block mg_bt_10">
					
					<div class="panel panel-default main_block">
						<div class="panel-heading main_block" role="tab" id="heading_<?= $count ?>">
					        <div class="Normal main_block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-expanded="true" aria-controls="collapse5" id="collapsed5">                  
					        	<div class="col-md-12"><span>Transport Information</span></div>
					        </div>
					    </div>
					    <div id="collapse5" class="panel-collapse collapse main_block" role="tabpanel" aria-labelledby="heading5">
					        <div class="panel-body">
								<div class="row">

								    <div class="col-xs-12">

								        <div class="table-responsive">

								        <table id="tbl_package_tour_quotation_dynamic_transport" name="tbl_package_tour_quotation_dynamic_transport" class="table mg_bt_0 table-bordered mg_bt_10">

								            <tr>

								                <td><input class="css-checkbox" id="chk_transport1" type="checkbox" checked readonly><label class="css-label" for="chk_transport1" > </label></td>

								                <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>

								                <td><select id="transport_name"  name="transport_name" title="Select Transport" onchange="get_transport_cost();" class="form-control" style="width:100%"> 

										                <option value="">Transport Vehicle</option>

										                 <?php 

										                 $sq_query = mysql_query("select * from transport_agency_bus_master where active_flag != 'Inactive'"); 

										                 while($row_dest = mysql_fetch_assoc($sq_query)){ ?>

										                    <option value="<?php echo $row_dest['bus_id']; ?>"><?php echo $row_dest['bus_name']; ?></option>

										                    <?php } ?>

										              </select></td> 

											    <td><input type="text" id="transport_start_date1" name="transport_start_date1" placeholder="Start Date" title="Start Date" class="app_datepicker"></td>

											    <td><input type="text" id="transport_end_date1" title="End Date" name="transport_end_date1" placeholder="End Date" class="app_datepicker"></td>

								                <td><input type="text" id="package_name1" name="package_name1" placeholder="Package Name" title="Package Name" readonly></td>   

								                <td><input type="text" id="transport_cost1" name="transport_cost1" placeholder="Transport Cost" title="Transport Cost" style="display: none"></td> 

								                <td><input type="text" id="package_id1" name="package_id1" placeholder="Package ID" title="Package ID" style="display:none;"></td> 

								            </tr>                                

								        </table>

								        </div>

								    </div>

								</div> 
					        </div>
					    </div>
					</div>
				</div>

				<!-- Excursion Information -->
				<div class="accordion_content main_block mg_bt_10">
					
					<div class="panel panel-default main_block">
						<div class="panel-heading main_block" role="tab" id="heading_<?= $count ?>">
					        <div class="Normal main_block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse6" aria-expanded="true" aria-controls="collapse6" id="collapsed6">                  
					        	<div class="col-md-12"><span>Excursion Information</span></div>
					        </div>
					    </div>
					    <div id="collapse6" class="panel-collapse collapse main_block" role="tabpanel" aria-labelledby="heading6">
					        <div class="panel-body">
								 <div class="row">
								    <div class="col-xs-12 text-right mg_bt_20_sm_xs">
								        <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_package_tour_quotation_dynamic_excursion')"><i class="fa fa-plus"></i></button>
										<button type="button" class="btn btn-pdf btn-sm" onClick="deleteRow('tbl_package_tour_quotation_dynamic_excursion')"><i class="fa fa-trash"></i></button>
								    </div>
								</div>
								<div class="row">
								    <div class="col-xs-12">
								        <div class="table-responsive">
								        <table id="tbl_package_tour_quotation_dynamic_excursion" name="tbl_package_tour_quotation_dynamic_excursion" class="table mg_bt_0 table-bordered pd_bt_51">
								            <tr>
								                <td><input class="css-checkbox" id="chk_tour_group-1" type="checkbox"><label class="css-label" for="chk_tour_group2"> <label></td>
								                <td style="width:10%"><input maxlength="15" value="1" type="text" name="username1" placeholder="Sr. No." class="form-control" disabled /></td>
								                <td><select id="city_name-1" class="app_select2 form-control" name="city_name-1" title="City Name" style="width:100%" onchange="get_excursion_list(this.id);">
										                <option value="">*City</option>
										                <?php 
										                    $sq_city = mysql_query("select * from city_master order by city_name asc");
										                    while($row_city = mysql_fetch_assoc($sq_city))
										                    {
										                     ?>
										                        <option value="<?php echo $row_city['city_id'] ?>"><?php echo $row_city['city_name'] ?></option>
										                     <?php   
										                    }    
										                ?>
										            </select>
								                </td>
								                 <td><select id="excursion-1" class="app_select2 form-control" title="Excursion Name" name="excursion-1" style="width:100%" onchange="get_excursion_amount(this.id);">
									                <option value="">*Excursion Name</option>						               
									            </select></td>
									            <td><input type="text" id="excursion_amount-1" name="excursion_amount-1" placeholder="Excursion Amount" title="Excursion Amount" style="width:100%" onchange="validate_balance(this.id);" ></td>						            
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

	<div class="row text-center mg_tp_20">
		<div class="col-xs-12">
			<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab2()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>
			&nbsp;&nbsp;
			<button class="btn btn-info btn-sm ico_right">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
		</div>
	</div>

</form>
<?= end_panel() ?>


<script>

$('#plane_from_location1,#plane_to_location1,#train_from_location1,#train_to_location1,#airline_name1,#city_name-1,#room_cat1').select2();
$('#cruise_departure_date,#cruise_arrival_date').datetimepicker({ format:"d-m-Y H:i:s" });

// App_accordion
jQuery(document).ready(function() {
	jQuery(".panel-heading").click(function(){ 
		jQuery('#accordion .panel-heading').not(this).removeClass('isOpen');
		jQuery(this).toggleClass('isOpen');
		jQuery(this).next(".panel-collapse").addClass('thePanel');
		jQuery('#accordion .panel-collapse').not('.thePanel').slideUp("slow"); 
		jQuery(".thePanel").slideToggle("slow").removeClass('thePanel'); 
	});
});

//Get Hotel Cost
function get_hotel_cost(hotel_id1)

{

	var from_date = $('#from_date').val();

	var hotel_name_arr = new Array();
	var room_cat_arr = new Array();

	var table = document.getElementById("tbl_package_tour_quotation_dynamic_hotel");

		var rowCount = table.rows.length;

		  for(var i=0; i<rowCount; i++)

		  {

		    var row = table.rows[i];
		    if(row.cells[0].childNodes[0].checked)

		    {

				var hotel_id = row.cells[3].childNodes[0].value;
				var room_category = row.cells[4].childNodes[0].value;

				hotel_name_arr.push(hotel_id);
				room_cat_arr.push(room_category);

			}

		  }
		  var base_url = $('#base_url').val();
		  $.ajax({

		      type:'post',

		      url: base_url+'view/package_booking/quotation/home/hotel/get_hotel_cost.php',

		      data:{ hotel_id_arr : hotel_name_arr,from_date : from_date,room_cat_arr:room_cat_arr },

		      success:function(result){

		      	     var hotel_arr = JSON.parse(result);

		      	     for(var i=0; i<hotel_arr.length; i++){

				      		var row = table.rows[i]; 

			      	     	row.cells[10].childNodes[0].value = hotel_arr[i]['hotel_cost'];

			      	     	row.cells[12].childNodes[0].value = hotel_arr[i]['extra_bed_cost'];

		               }

		      }

		    });	

		

}			



//Get Transport Cost

function get_transport_cost()

{

	var transport_id_arr = new Array();

	var total_days_arr = new Array();

	var total_days = 0;

	var table = document.getElementById("tbl_package_tour_quotation_dynamic_transport");

		var rowCount = table.rows.length;
		  for(var i=0; i<rowCount; i++)
		  {
		    var row = table.rows[i];		   
		    if(row.cells[0].childNodes[0].checked)
		    {
		    	var transport_id = row.cells[2].childNodes[0].value;    
		    	transport_id_arr.push(transport_id);
			}
		  }
		  $.ajax({
		      type:'post',
		      url: '../hotel/get_transport_cost.php',
		      data:{ transport_id_arr : transport_id_arr },
		      success:function(result){
		      	     var transport_arr = JSON.parse(result);
		      	     
		      	     for(var i=0; i<transport_arr.length; i++){
			      		var row = table.rows[i];
		      	     	row.cells[6].childNodes[0].value = transport_arr[i]['transport_cost'];			      	     	
		             }
		      }
		    });	
		
}

// Get Transport cost (total_days)
function total_transport_days(from_date,to_date)
{
    var parts = from_date.split('-');
    var date = new Date();
    var new_month = parseInt(parts[1])-1;
    date.setFullYear(parts[2]);
    date.setDate(parts[0]);
    date.setMonth(new_month);

    var parts1 = to_date.split('-');
    var date1 = new Date();
    var new_month1 = parseInt(parts1[1])-1;
    date1.setFullYear(parts1[2]);
    date1.setDate(parts1[0]);
    date1.setMonth(new_month1);

    var one_day=1000*60*60*24;

    var from_date_ms = date.getTime();
    var to_date_ms = date1.getTime();

    var difference_ms = to_date_ms - from_date_ms;

    var total_days = Math.round(difference_ms/one_day); 

    total_days = parseFloat(total_days) + 1;
	return total_days;

}

$(function(){
	$('#frm_tab3').validate({
		rules:{
			/*transport_vehicle : { required : true },
		 	transport_start_date : { required : true },
		 	transport_end_date : { required : true },*/	 
		},
		submitHandler:function(form){
		
		//Train Info	
		var train_from_location_arr = new Array();
		var train_to_location_arr = new Array();
		var train_class_arr = new Array();
		var train_arrival_date_arr = new Array();
		var train_departure_date_arr = new Array();
	    var table = document.getElementById("tbl_package_tour_quotation_dynamic_train");
		var rowCount = table.rows.length;
		  
		  for(var i=0; i<rowCount; i++)
		  {
		    var row = table.rows[i];
		     
		    if(row.cells[0].childNodes[0].checked)
		    {
		       var train_from_location1 = row.cells[2].childNodes[0].value;         
		       var train_to_location1 = row.cells[3].childNodes[0].value;         
			   var train_class = row.cells[4].childNodes[0].value;         
			   var train_arrival_date = row.cells[5].childNodes[0].value;         
			   var train_departure_date = row.cells[6].childNodes[0].value;         
		     
		       if(train_from_location1=="")
		       {
		          error_msg_alert('Enter train from location in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_package_tour_quotation_dynamic_train').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		       }

		       if(train_to_location1=="")
		       {
		          error_msg_alert('Enter train to location in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_package_tour_quotation_dynamic_train').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		       }

		       train_from_location_arr.push(train_from_location1);
		       train_to_location_arr.push(train_to_location1)
			   train_class_arr.push(train_class);
			   train_arrival_date_arr.push(train_arrival_date);
			   train_departure_date_arr.push(train_departure_date);

		    }      
		  }

		 // Flight Info
		var plane_from_city_arr = new Array();
		var plane_to_city_arr = new Array();
		var plane_from_location_arr = new Array();
		var plane_to_location_arr = new Array();
		var airline_name_arr = new Array();
		var plane_class_arr = new Array();
		var arraval_arr = new Array();
		var dapart_arr = new Array();

		var table = document.getElementById("tbl_package_tour_quotation_dynamic_plane");
		var rowCount = table.rows.length;
		  
		  for(var i=0; i<rowCount; i++){
		    var row = table.rows[i];
		     
		    if(row.cells[0].childNodes[0].checked){
		       var plane_from_city = row.cells[2].childNodes[0].value;         
		       var plane_from_location1 = row.cells[3].childNodes[0].value;	
		       var plane_to_city = row.cells[4].childNodes[0].value;         
		       var plane_to_location1 = row.cells[5].childNodes[0].value;
		       var airline_name = row.cells[6].childNodes[0].value;  
		       var plane_class = row.cells[7].childNodes[0].value;  
		       var dapart1 = row.cells[8].childNodes[0].value;       
		       var arraval1 = row.cells[9].childNodes[0].value;
		       
		       if(plane_from_location1 == ""){
		          error_msg_alert('Enter flight from location in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_package_tour_quotation_dynamic_plane').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		       }

		       if(plane_to_location1 == ""){
		          error_msg_alert('Enter flight to location in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_package_tour_quotation_dynamic_plane').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		       }

				if(dapart1==""){ 
					error_msg_alert("Departure Datetime is required in row:"+(i+1));
		          $('.accordion_content').removeClass("indicator"); 
	          	  $('#tbl_package_tour_quotation_dynamic_plane').parent('div').closest('.accordion_content').addClass("indicator");
				  return false;
				}

				if(arraval1==""){
				  error_msg_alert('Arrival Datetime is required in row:'+(i+1)); 
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_package_tour_quotation_dynamic_plane').parent('div').closest('.accordion_content').addClass("indicator");
				  return false;
				}		   
			   plane_from_city_arr.push(plane_from_city);
		       plane_to_city_arr.push(plane_to_city);
		       plane_from_location_arr.push(plane_from_location1);
		       plane_to_location_arr.push(plane_to_location1);
		       airline_name_arr.push(airline_name);
		       plane_class_arr.push(plane_class);
		       arraval_arr.push(arraval1);
		       dapart_arr.push(dapart1);
		       var from_date = $('#from_date').val();
		    }
		  }

		
		//Cruise Information
		var cruise_departure_date_arr = new Array();
		var cruise_arrival_date_arr = new Array();
		var route_arr = new Array();
		var cabin_arr = new Array();
		var sharing_arr = new Array();

		var table = document.getElementById("tbl_dynamic_cruise_quotation");
		var rowCount = table.rows.length;

		  for(var i=0; i<rowCount; i++)
		  {
		    var row = table.rows[i];	 
		    if(row.cells[0].childNodes[0].checked)
		    {
		       var cruise_from_date = row.cells[2].childNodes[0].value;    
		       var cruise_to_date = row.cells[3].childNodes[0].value;    
		       var route = row.cells[4].childNodes[0].value;    
		       var cabin = row.cells[5].childNodes[0].value;  
		       var sharing = row.cells[6].childNodes[0].value;        
			         	     
		       if(cruise_from_date=="")
		       {
		          error_msg_alert('Enter cruise Departure datetime in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_dynamic_cruise_quotation').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		       }

		       if(cruise_to_date=="")
		       {
		          error_msg_alert('Enter cruise Arrival datetime  in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_dynamic_cruise_quotation').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		       }
		       if(route=="")
		       {
		          error_msg_alert('Enter route in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_dynamic_cruise_quotation').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		       }
		       if(cabin=="")
		       {
		          error_msg_alert('Enter cabin in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_dynamic_cruise_quotation').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		       }
		       cruise_departure_date_arr.push(cruise_from_date);
			   cruise_arrival_date_arr.push(cruise_to_date);
			   route_arr.push(route);
			   cabin_arr.push(cabin);
			   sharing_arr.push(sharing);

		    }      
		  }
 
		//Hotel Information  
		var city_name_arr = new Array();
		var hotel_name_arr = new Array();
		var hotel_stay_days_arr = new Array();
		var package_name_arr = new Array();
		var total_rooms_arr = new Array();
		var hotel_cost_arr = new Array();
		var package_id_arr = new Array();

		var table = document.getElementById("tbl_package_tour_quotation_dynamic_hotel");
		var rowCount = table.rows.length;

		  for(var i=0; i<rowCount; i++){

		    var row = table.rows[i];
		    if(row.cells[0].childNodes[0].checked){

		       var city_name = row.cells[2].childNodes[0].value;
			   var hotel_id = row.cells[3].childNodes[0].value;  
			   var hotel_cat = row.cells[4].childNodes[0].value;     
		       var hotel_stay_days1 = row.cells[6].childNodes[0].value;
		       var total_rooms = row.cells[7].childNodes[0].value;
		       var package_name1 = row.cells[9].childNodes[0].value;	      
		       var package_id1 = row.cells[11].childNodes[0].value;  
	           var hotel_cost = row.cells[10].childNodes[0].value;   

		       if(city_name==""){
		          error_msg_alert('Select Hotel city in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_package_tour_quotation_dynamic_hotel').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		       }

		       if(hotel_id==""){
		          error_msg_alert('Enter Hotel in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_package_tour_quotation_dynamic_hotel').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		       }
			    if(hotel_cat==""){
					error_msg_alert('Enter Room Category in row'+(i+1));
					$('.accordion_content').removeClass("indicator");
						$('#tbl_package_tour_quotation_dynamic_hotel').parent('div').closest('.accordion_content').addClass("indicator");
					return false;
				}
		        if(hotel_stay_days1==""){
		          error_msg_alert('Enter Hotel total days in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_package_tour_quotation_dynamic_hotel').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
			    }
			
		       city_name_arr.push(city_name);
		       hotel_name_arr.push(hotel_id);
		       hotel_stay_days_arr.push(hotel_stay_days1);
		       total_rooms_arr.push(total_rooms);
		       package_name_arr.push(package_name1);
		       hotel_cost_arr.push(hotel_cost); //(TODO) put in right value here and place this line in side success of ajax
		       package_id_arr.push(package_id1);
		    }      
		  }

		  var unique_package_id_arr = new Array();
		  for(var i = 0; i<package_id_arr.length; i++){

		  	 var added = false;
		  	 for(var j=0; j<unique_package_id_arr.length; j++){

		  	 	if(unique_package_id_arr[j]['package_id']==package_id_arr[i]){
		  	 		added = true;
		  	 	}
		  	 }

		  	 var hotel_cost_total = 0;
		  	 if(!added){

		  	 	  for(var k=0; k<rowCount; k++){

				    var row = table.rows[k];

				    if(row.cells[0].childNodes[0].checked){

				       var total_days = row.cells[6].childNodes[0].value;
		       		   var total_rooms = row.cells[7].childNodes[0].value;
		       		   var extra_bed = row.cells[8].childNodes[0].value;
				       var hotel_cost = row.cells[10].childNodes[0].value;
				       var package_id1 = row.cells[11].childNodes[0].value;
		       		   var extra_bed_cost = row.cells[12].childNodes[0].value;
						
					   hotel_cost = (hotel_cost=='')?0:hotel_cost;
					   extra_bed_cost = (extra_bed_cost=='')?0:extra_bed_cost;
					   
				       if(package_id1==package_id_arr[i]){

				       	    var temp_hotel_cost = (parseFloat(total_rooms) * parseFloat(hotel_cost) * parseFloat(total_days)) + (parseFloat(extra_bed_cost) * parseFloat(total_days) * parseFloat(extra_bed));
							temp_hotel_cost = (isNaN(temp_hotel_cost)) ? 0 : temp_hotel_cost;
				       		hotel_cost_total = parseFloat(hotel_cost_total) + parseFloat(temp_hotel_cost);
							hotel_cost_total = (isNaN(hotel_cost_total)) ? 0 : hotel_cost_total;
				       }
			        }
				  }
				  unique_package_id_arr.push({
		  	 			package_id: package_id_arr[i],
		  	 			hotel_cost: hotel_cost_total
		  	 	  });
		  	 }
		  }

		  var table = document.getElementById("tbl_package_tour_quotation_dynamic_costing");
		  var rowCount = table.rows.length;
		  for(var j=0; j<rowCount; j++){

			    var row = table.rows[j];
			    if(row.cells[0].childNodes[0].checked){

			    	var package_id = row.cells[12].childNodes[0].value;
			    	for(var i=0;i<unique_package_id_arr.length;i++){
			    		if(unique_package_id_arr[i]['package_id'] == package_id){	
			    			row.cells[2].childNodes[0].value = unique_package_id_arr[i]['hotel_cost'];
			    			row.cells[10].childNodes[0].value = unique_package_id_arr[i]['hotel_cost'];
			    		}
			    	}
			    }
		  }

		//Transport Information  
		var vehicle_name_arr = new Array();
		var start_date_arr = new Array();
		var end_date_arr = new Array();
		var package_name_arr1 = new Array();
		var transport_cost_arr1 = new Array();
		var package_id_arr1 = new Array();

		var table = document.getElementById("tbl_package_tour_quotation_dynamic_transport");
		  var rowCount = table.rows.length;
		  for(var i=0; i<rowCount; i++){

		    var row = table.rows[i];
		    if(row.cells[0].childNodes[0].checked){

		       var vahicle_name = row.cells[2].childNodes[0].value;
		       var start_date = row.cells[3].childNodes[0].value;
		       var end_date = row.cells[4].childNodes[0].value;
		       var package_name2 = row.cells[5].childNodes[0].value;
		       var transport_cost1 = row.cells[6].childNodes[0].value;  
		       var package_id1 = row.cells[7].childNodes[0].value;  

		       if(vahicle_name==""){
		          error_msg_alert('Select Transport Vehicle in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_package_tour_quotation_dynamic_transport').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		       }

		       if(start_date==""){
		          error_msg_alert('Enter Start Transport date in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_package_tour_quotation_dynamic_transport').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		       }

		       if(end_date==""){
		          error_msg_alert('Enter End Transport date in row'+(i+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_package_tour_quotation_dynamic_transport').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		       }

		       vehicle_name_arr.push(city_name);
		       start_date_arr.push(start_date);
		       end_date_arr.push(end_date);
		       package_name_arr1.push(package_name2);
		       transport_cost_arr1.push(transport_cost1);
		       package_id_arr1.push(package_id1);
		    }
		  }

		  var unique_package_id_arr = new Array();
		  for(var i = 0; i<package_id_arr1.length; i++){

		  	 var added = false;
		  	 for(var j=0; j<unique_package_id_arr.length; j++){

		  	 	if(unique_package_id_arr[j]['package_id']==package_id_arr1[i]){
		  	 		added = true;
		  	 	}
		  	 }

		  	 var transport_cost_total = 0;	 
		  	 var total_days = 0;	 	 	  				       

		  	 if(!added){			  	  

		  	 	  for(var k=0; k<rowCount; k++){				  	

				    var row = table.rows[k];			     
				    if(row.cells[0].childNodes[0].checked){
				       var start_date = row.cells[3].childNodes[0].value;      
				       var end_date = row.cells[4].childNodes[0].value;
				       var package_name2 = row.cells[5].childNodes[0].value;
				       var transport_cost1 = row.cells[6].childNodes[0].value;  
				       var package_id1 = row.cells[7].childNodes[0].value;  

				       total_days = total_transport_days(start_date,end_date);

				       if(package_id1==package_id_arr1[i]){
				       	    var transport_cost = parseFloat(transport_cost1) *  parseFloat(total_days); 
				       		transport_cost_total = parseFloat(transport_cost_total) + parseFloat(transport_cost);
					   }
			        }
				  }	  
		  	 	unique_package_id_arr.push({
	  	 			package_id: package_id_arr1[i],
	  	 			transport_cost: transport_cost_total
	  	 	    });
  	 	    }
		  }

	  var table = document.getElementById("tbl_package_tour_quotation_dynamic_excursion");

	  var rowCount = table.rows.length;
	  var total_amount = 0;
	  for(var e=0; e<rowCount; e++)
	  {
		    var row = table.rows[e];
		    if(row.cells[0].childNodes[0].checked)
		    {		
		    	var city_name = row.cells[2].childNodes[0].value;         
		        var excursion_name = row.cells[3].childNodes[0].value;         
		        
		        if(city_name=="") {
		          error_msg_alert('Select Excursion City in row'+(e+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_package_tour_quotation_dynamic_excursion').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		        }
		        if(excursion_name=="") {
		          error_msg_alert('Select Excursion Name in row'+(e+1));
		          $('.accordion_content').removeClass("indicator");
	          	  $('#tbl_package_tour_quotation_dynamic_excursion').parent('div').closest('.accordion_content').addClass("indicator");
		          return false;
		        } 	
    			var e_amount = row.cells[4].childNodes[0].value;	
				total_amount = parseFloat(total_amount) + parseFloat(e_amount);
			}
					   
	  }

	  var table = document.getElementById("tbl_package_tour_quotation_dynamic_costing");
	  var rowCount = table.rows.length;

	  for(var j=0; j<rowCount; j++){
		    var row = table.rows[j];
		    if(row.cells[0].childNodes[0].checked){
				row.cells[4].childNodes[0].value = total_amount;
			}
	  }

	  var table = document.getElementById("tbl_package_tour_quotation_dynamic_costing");
	  var rowCount = table.rows.length;

	  for(var j=0; j<rowCount; j++){
		    var row = table.rows[j];
		    if(row.cells[0].childNodes[0].checked){
		    	var package_id2 = row.cells[12].childNodes[0].value;
		    	for(var i=0;i<unique_package_id_arr.length;i++){
		    		if(unique_package_id_arr[i]['package_id'] == package_id2){
		    			var hotel_cost = row.cells[2].childNodes[0].value;
						row.cells[3].childNodes[0].value = unique_package_id_arr[i]['transport_cost'];
						var total_tour_cost = parseFloat(unique_package_id_arr[i]['transport_cost'])  + parseFloat(hotel_cost);
		    			row.cells[10].childNodes[0].value = total_tour_cost;
		    		}
				}
				
				var total_cost = row.cells[10].childNodes[0].value;
				var exc_cost = row.cells[4].childNodes[0].value;
				row.cells[10].childNodes[0].value = parseFloat(total_cost)+parseFloat(exc_cost);
		    }
	   }


		$('.accordion_content').removeClass("indicator");
	
		$('#tab3_head').addClass('done');
		$('#tab4_head').addClass('active');
		$('.bk_tab').removeClass('active');
		$('#tab4').addClass('active');
    	$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);  
	}	
	});

});

function switch_to_tab2(){
  	$('#tab3_head').removeClass('active');
	$('#tab_daywise_head').addClass('active');
	$('.bk_tab').removeClass('active');
	$('#tab_daywise').addClass('active');
	$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200); }

</script>

