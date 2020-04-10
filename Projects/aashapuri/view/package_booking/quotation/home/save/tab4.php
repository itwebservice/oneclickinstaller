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
 	<div class="row mg_tp_10">
		<div class="col-xs-12">
			<h3 class="editor_title">Group Costing</h3>
			<div class="panel panel-default panel-body app_panel_style">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							<table id="tbl_package_tour_quotation_dynamic_costing" name="tbl_package_tour_quotation_dynamic_costing" class="table border_0 no-marg">
								<tr>
									<td class="header_btn header_btn"><input class="css-checkbox" id="chk_costing1" type="checkbox" checked readonly><span class="css-label" for="chk_costing1" > </span></td>
									<td class="header_btn header_btn"><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /><span>SR.NO</span></td>
									<td class="header_btn header_btn"><input type="text" id="tour_cost-" name="tour_cost-" placeholder="Hotel Cost" title="Hotel Cost" value="0" onchange="quotation_cost_calculate(this.id);validate_balance(this.id)" style="width:100px"><span>Hotel Cost</span></td>
									<td class="header_btn header_btn"><input type="text" id="transport_cost-" name="transport_cost-" placeholder="Transport Cost" title="Transport Cost" onchange="quotation_cost_calculate(this.id);validate_balance(this.id)" style="width:100px" value="0"><span>Transport Cost</span></td>    
									<?php
									$add_class1 = '';
									if($role == 'B2b'){
										$add_class1 = "hidden";
									} 
									else{
										$add_class1 = "text";
									}?>
									<td class="header_btn header_btn"><input type="text" id="excursion_cost-" name="excursion_cost-" onchange="quotation_cost_calculate(this.id);validate_balance(this.id);"  placeholder="Excursion Cost" title="Excursion Cost" style="width:100px" value="0"><span>Excursion Cost</span></td>

									<td class="header_btn header_btn"><input type="<?= $add_class1 ?>" id="markup_cost-" name="markup_cost-" onchange="quotation_cost_calculate(this.id);validate_balance(this.id);"  placeholder="Markup(%)" title="Markup Cost(%)" style="width:100px"><span>Markup(%)</span></td>
									
									<td class="header_btn header_btn"><input type="<?= $add_class1 ?>" id="markup_cost_subtotal-" name="markup_cost_subtotal-" onchange="quotation_cost_calculate(this.id); validate_balance(this.id)"  placeholder="Markup Subtotal" title="Markup Subtotal" style="width:100px"><span>Markup Subtotal</span></td>	

									<td class="header_btn header_btn"><select name="taxation_id-" id="taxation_id-" title="Tax" onchange="generic_tax_reflect_temp(this.id, 'service_tax-', 'quotation_cost_calculate');" style="width: 110px;">
											<?php get_taxation_dropdown(); ?>
										</select><span>Tax</span></td>
										<td class="header_btn header_btn"><input type="hidden" id="service_tax-" name="service_tax-" value="0"></td>

									<td class="header_btn header_btn"><input type="text" id="service_tax_subtotal-" name="service_tax_subtotal-" readonly  placeholder="Tax Amount" title="Tax Amount" style="width:100px"><span>Tax Amount</span></td>

									<td class="header_btn header_btn"><input type="text" id="total_tour_cost-" class="amount_feild_highlight text-right" name="total_tour_cost-" placeholder="Total Cost" title="Total Cost" style="width: 100px;" readonly><span>Total Cost</span></td>

									<td class="header_btn header_btn"><input type="text" id="package_name1" name="package_name1" placeholder="Package Name" title="Package Name" style="width: 160px;" readonly><span>Package Name</span></td> 

									<td class="header_btn header_btn"><input type="text" id="package_id1" name="package_id1" placeholder="Package ID" title="Package ID" style="display:none;"></td>
								</tr>
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
						<input type="text" id="train_cost" name="train_cost" placeholder="Train Cost" title="Train Cost" onchange="validate_balance(this.id)">
					</div>
					<div class="col-md-2 header_btn mg_bt_10">
						<span>Flight Cost</span>
						<input type="text" id="flight_cost" name="flight_cost" placeholder="Flight Cost" title="Flight Cost" onchange="validate_balance(this.id)">
					</div>
					<div class="col-md-2 header_btn mg_bt_10">
						<span>Cruise Cost</span>
						<input type="text" id="cruise_cost" name="cruise_cost" placeholder="Cruise Cost" title="Cruise Cost" onchange="validate_balance(this.id)">
					</div>
					<div class="col-md-2 header_btn mg_bt_10">
						<span>Visa Cost</span>
						<input type="text" id="visa_cost" name="visa_cost" placeholder="Visa Cost" title="Visa Cost" onchange="validate_balance(this.id)">
					</div>
					<div class="col-md-2 header_btn mg_bt_10">
						<span>Guide Cost</span>
						<input type="text" id="guide_cost" name="guide_cost" placeholder="Guide Cost" title="Guide Cost" onchange="validate_balance(this.id)">
					</div>
					<div class="col-md-2 header_btn mg_bt_10">
						<span>Miscellaneous Cost</span>
						<input type="text" id="misc_cost" name="misc_cost" placeholder="Miscellaneous Cost" title="Miscellaneous Cost" onchange="validate_balance(this.id)">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
		<h3 class="editor_title">Per Person Costing</h3>
			<div class="panel panel-default panel-body app_panel_style">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							<table id="tbl_adult_child_head" name="tbl_adult_child_head" class="table border_0 no-marg">
								<tr>
									<td class="col-md-3"><span>Package_Name</span></th>
									<td><span>Adult_Cost</span></th>
									<td><span>Child_Cost</span></th>
									<td><span>Infant_Cost</span></th>
									<td><span>Child_with_bed</span></th>
									<td><span>Child_w/o_bed</span></th>
									<td><span>Extra_Bed</span></th>
								</tr>
							</table>
						<div>
					</div>
				</div>
					<!-- Adult & child cost -->
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							<table id="tbl_package_tour_quotation_adult_child" name="tbl_package_tour_quotation_adult_child" class="table border_0 no-marg">
								<tr>
									<td class="col-md-3"><input type="text" id="package_name1" name="package_name1" placeholder="Package Name" title="Package Name" readonly></td>
									<td><input type="text" onchange="validate_balance(this.id);" id="adult_cost" name="adult_cost" placeholder="Adult Cost" title="Adult Cost"></td> 
									<td><input type="text" onchange="validate_balance(this.id);" id="child_cost" name="child_cost" placeholder="Child Cost" title="Child Cost"></td> 
									<td><input type="text" onchange="validate_balance(this.id);" id="infant_cost" name="infant_cost" placeholder="Infant Cost" title="Infant Cost"></td> 
									<td><input type="text" onchange="validate_balance(this.id);" id="child_with" name="child_with" placeholder="Child with Bed Cost" title="Child with Bed Cost"></td> 
									<td><input type="text" onchange="validate_balance(this.id);" id="child_without" name="child_without" placeholder="Child w/o Bed Cost" title="Child w/o Bed Cost"></td> 
									<td><input type="text" onchange="validate_balance(this.id);" id="extra_bed" name="extra_bed" placeholder="Extra Bed Cost" title="Extra Bed Cost"></td> 
									<td><input type="hidden" id="pacakge_id2" name="pacakge_id2" placeholder="Package Id" title="Package Id"></td> 
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mg_tp_20">
		<div class="col-md-6 col-sm-12">
			<div class="col-md-8 col-sm-12">
				<select name="costing_type" id="costing_type" title="Select Costing type" class="form-control">
					<option value="1">Group Costing</option>
					<option value="2">Per Person costing</option>
				</select>
			</div>
		</div>
      	<div class="col-md-6 col-sm-12 text-right">
        	<div class="div-upload">
				<div id="price_structure" class="upload-button1"><span>Price Structure</span></div>
				<span id="photo_status" ></span>
				<ul id="files" ></ul>
				<input type="hidden" id="upload_url" name="upload_url">
        	</div>
      	</div>
  	</div>
	<div class="row mg_tp_20">
		<div class="col-md-6 col-sm-12">
			<span style="color: red;" class="note">Note : Group Costing or Per person costing to display on quotation</span>
		</div>
		<div class="col-md-6 col-sm-12 text-right"> 
        	<span style="color: red;" class="note">Note : Only Excel or Word files are allowed</span>  
		</div>
	</div>
	<div class="row mg_tp_20 text-center mg_bt_30">
		<div class="col-md-12">
			<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab3()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>
			&nbsp;&nbsp;
			<button class="btn btn-sm btn-success" id="btn_quotation_save"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
		</div>
	</div>
</div>
	<input type="hidden" id="login_id" name="login_id" value="<?= $login_id ?>">
</form>
<?= end_panel() ?>

<script>

upload_price_struct();

function upload_price_struct()
{
    var btnUpload=$('#price_structure');
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
          $("#upload_url").val(response);
        }
      }
    });
}

function switch_to_tab3(){ 
  	$('#tab4_head').removeClass('active');
	$('#tab3_head').addClass('active');
	$('.bk_tab').removeClass('active');
	$('#tab3').addClass('active');
	$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200); }

$('#frm_tab4').validate({

	rules:{

		
		
	},

	submitHandler:function(form){
		var login_id = $("#login_id").val();

		var emp_id = $("#emp_id").val();

		var enquiry_id = $("#enquiry_id").val();

		var tour_name = $('#tour_name').val();

		var from_date = $('#from_date').val();

		var to_date = $('#to_date').val();

		var total_days = $('#total_days').val();

		var customer_name = $('#customer_name').val();

		var email_id = $('#email_id').val();
		var mobile_no = $('#mobile_no').val();

		var total_adult = $('#total_adult').val();

		var total_children = $('#total_children').val();

		var total_infant = $('#total_infant').val();

		var total_passangers = $('#total_passangers').val();

		var children_without_bed = $('#children_without_bed').val();

		var children_with_bed = $('#children_with_bed').val();		

		var quotation_date = $('#quotation_date').val();

		var booking_type = $('#booking_type').val();

		var train_cost = $('#train_cost').val();

		var flight_cost = $('#flight_cost').val();

		var cruise_cost = $('#cruise_cost').val();

		var visa_cost = $('#visa_cost').val();
		var branch_admin_id = $('#branch_admin_id1').val();
		var financial_year_id = $('#financial_year_id').val();
		

		var guide_cost = $('#guide_cost').val();
		var misc_cost = $('#misc_cost').val();
		var costing_type = $('#costing_type').val();

		//Train Information

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

			   var train_departure_date = row.cells[5].childNodes[0].value;         

			   var train_arrival_date = row.cells[6].childNodes[0].value;         

			          



		       

		       if(train_from_location1=="")

		       {

		          error_msg_alert('Enter train from location in row'+(i+1));

		          return false;

		       }



		       if(train_to_location1=="")

		       {

		          error_msg_alert('Enter train to location in row'+(i+1));

		          return false;

		       }

		      

		   

		       train_from_location_arr.push(train_from_location1);

		       train_to_location_arr.push(train_to_location1);

			   train_class_arr.push(train_class);

			   train_arrival_date_arr.push(train_arrival_date);

			   train_departure_date_arr.push(train_departure_date);



		    }      

		  }



		//Plane Information  
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

		  

		  for(var i=0; i<rowCount; i++)

		  {

		    var row = table.rows[i];

		     

		    if(row.cells[0].childNodes[0].checked)

		    {

		      var plane_from_city = row.cells[2].childNodes[0].value;         
		       var plane_from_location1 = row.cells[3].childNodes[0].value;	
		       var plane_to_city = row.cells[4].childNodes[0].value;         
		       var plane_to_location1 = row.cells[5].childNodes[0].value;
		       var airline_name = row.cells[6].childNodes[0].value;  
		       var plane_class = row.cells[7].childNodes[0].value; 
		       var dapart1 = row.cells[8].childNodes[0].value;        
		       var arraval1 = row.cells[9].childNodes[0].value;

		       

		       if(plane_from_location1=="")

		       {

		          error_msg_alert('Enter plane from location in row'+(i+1));

		          return false;

		       }



		       if(plane_to_location1=="")

		       {

		          error_msg_alert('Enter plane to location in row'+(i+1));

		          return false;

		       }

		      


				if(arraval1=="")

				{ 

					error_msg_alert('Arrival Date time is required in row:'+(i+1)); 

					return false;

				}

				if(dapart1=="")

				{ 

					error_msg_alert("Daparture Date time is required in row:"+(i+1)); 

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
		          error_msg_alert('Enter cruise departure datetime in row'+(i+1));
		          return false;
		       }

		       if(cruise_to_date=="")
		       {
		          error_msg_alert('Enter cruise departure datetime  in row'+(i+1));
		          return false;
		       }
		       if(route=="")
		       {
		          error_msg_alert('Enter route in row'+(i+1));
		          return false;
		       }
		       if(cabin=="")
		       {
		          error_msg_alert('Enter cabin in row'+(i+1));
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
		var hotel_cat_arr = new Array();

		var hotel_stay_days_arr = new Array();

		var hotel_type_arr = new Array();

		var package_name_arr = new Array();

		var total_rooms_arr = new Array();

		var hotel_cost_arr = new Array();

		var extra_bed_cost_arr = new Array();

		var extra_bed_arr = new Array();

		var table = document.getElementById("tbl_package_tour_quotation_dynamic_hotel");

		  var rowCount = table.rows.length;

		  

		  for(var i=0; i<rowCount; i++)

		  {

		    var row = table.rows[i];

		     

		    if(row.cells[0].childNodes[0].checked)

		    {

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

		      

		       if(city_name=="")

		       {

		          error_msg_alert('Select hotel city in row'+(i+1));

		          return false;

		       }

		       if(hotel_id=="")

		       {

		          error_msg_alert('Enter hotel in row'+(i+1));

		          return false;

		       }

		       if(hotel_stay_days1=="")

		       {

		          error_msg_alert('Enter hotel total days in row'+(i+1));

		          return false;

		       }

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

		      

		    }      

		  }

		//Transport Information  

		var vehicle_name_arr = new Array();

		var start_date_arr = new Array();

		var end_date_arr = new Array();

		var package_name_arr1 = new Array();

		var transport_cost_arr1 = new Array();

		



		var table = document.getElementById("tbl_package_tour_quotation_dynamic_transport");

		var rowCount = table.rows.length;

		  

		  for(var i=0; i<rowCount; i++)

		  {

		    var row = table.rows[i];

		     

		    if(row.cells[0].childNodes[0].checked)

		    {

		       var vahicle_name = row.cells[2].childNodes[0].value;         

		       var start_date = row.cells[3].childNodes[0].value;         

		       var end_date = row.cells[4].childNodes[0].value;   

		       var package_name2 = row.cells[5].childNodes[0].value;

		       var transport_cost1 = row.cells[6].childNodes[0].value;  

		     

			       

		       if(vahicle_name=="")

		       {

		          error_msg_alert('Select Transport Vehicle in row'+(i+1));

		          return false;

		       }

		       if(start_date=="")

		       {

		          error_msg_alert('Enter Start Transport date in row'+(i+1));

		          return false;

		       }

		       if(end_date=="")

		       {

		          error_msg_alert('Enter End Transport date in row'+(i+1));

		          return false;

		       }

		       vehicle_name_arr.push(vahicle_name);

		       start_date_arr.push(start_date);

		       end_date_arr.push(end_date);

		       package_name_arr1.push(package_name2);

		       transport_cost_arr1.push(transport_cost1);



		    }      

		  }

	  var table = document.getElementById("tbl_package_tour_quotation_dynamic_excursion");

	  var rowCount = table.rows.length;

	  var city_name_arr_e = new Array();
   	var excursion_name_arr = new Array();
	  var excursion_amt_arr = new Array();
	  
	  for(var e=0; e<rowCount; e++)
	  {
		    var row = table.rows[e];
		    if(row.cells[0].childNodes[0].checked)
		    {		
		    	var city_name = row.cells[2].childNodes[0].value;         
		        var excursion_name = row.cells[3].childNodes[0].value;         
		        var excursion_amount = row.cells[4].childNodes[0].value;         

		        if(city_name=="") {
		          error_msg_alert('Select Excursion City in row'+(i+1));
		          return false;
		        }
		        if(excursion_name=="") {
		          error_msg_alert('Select Excursion Name in row'+(i+1));
		          return false;
		        } 	

		        city_name_arr_e.push(city_name);
		        excursion_name_arr.push(excursion_name);
		        excursion_amt_arr.push(excursion_amount);
    		    			    
		    }		   
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

		var table = document.getElementById("tbl_package_tour_quotation_dynamic_costing");
		var rowCount = table.rows.length;

		  for(var i=0; i<rowCount; i++)
		  {
		    var row = table.rows[i];
		    
		    if(row.cells[0].childNodes[0].checked)
		    {
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
		      
		       if(tour_cost==""){

		          error_msg_alert('Select Hotel cost in row'+(i+1));

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
		    }      
		  }

		 //Adult & Child Costing Information  
		var c_package_id_arr = new Array();
		var adult_cost_arr = new Array();
		var child_cost_arr = new Array();
		var infant_cost_arr = new Array();
		var child_with_arr = new Array();
		var child_without_arr = new Array();
		var extra_bedc_arr = new Array();	

		var table = document.getElementById("tbl_package_tour_quotation_adult_child");
		var rowCount = table.rows.length;
		for(var i=0; i<rowCount; i++){
		    var row = table.rows[i];
		    var adult_cost = row.cells[1].childNodes[0].value;
		    var child_cost = row.cells[2].childNodes[0].value;
			var infant_cost = row.cells[3].childNodes[0].value;
			var child_with = row.cells[4].childNodes[0].value;
			var child_without = row.cells[5].childNodes[0].value;
			var extra_bed = row.cells[6].childNodes[0].value;
			var c_package_id = row.cells[7].childNodes[0].value;

			adult_cost_arr.push(adult_cost);
			child_cost_arr.push(child_cost);
			infant_cost_arr.push(infant_cost);
			child_with_arr.push(child_with);
			child_without_arr.push(child_without);
			extra_bedc_arr.push(extra_bed);
			c_package_id_arr.push(c_package_id);
		}

		var package_id_arr1 = new Array();
		var incl_arr = new Array();
		var excl_arr = new Array();	

		$('input[name="custom_package"]:checked').each(function(){

			package_id_arr1.push($(this).val());
			var package_id = $(this).val();
			//Incl & Excl
			var table = document.getElementById("dynamic_table_incl"+package_id);
			var rowCount = table.rows.length;	
			for(var i=0; i<rowCount; i++){
				var row = table.rows[i];	
				var inclusion = $('#inclusions'+package_id).val();
				var exclusion = $('#exclusions'+package_id).val();

				incl_arr.push(inclusion);
				excl_arr.push(exclusion);
			}
		});

		var attraction_arr = new Array();
		var program_arr = new Array();
		var stay_arr = new Array();
		var meal_plan_arr = new Array();
		var package_p_id_arr = new Array();

		for(var j=0;j<package_id_arr1.length;j++)
		{
		  var table = document.getElementById("dynamic_table_list_p_"+package_id_arr1[j]);
		  var rowCount = table.rows.length;
		  for(var i=0; i<rowCount; i++)
		  {
			    var row = table.rows[i];
			    if(row.cells[0].childNodes[0].checked)
			    {
			       var attraction = row.cells[2].childNodes[0].value;
			       var program = row.cells[3].childNodes[0].value;
			       var stay = row.cells[4].childNodes[0].value;
			       var meal_plan = row.cells[5].childNodes[0].value;
			       var package_id1 = row.cells[6].childNodes[0].value;

			      if(program==""){
			        error_msg_alert('Daywise program is mandatory in row'+(i+1));
			        return false;
			      }
						attraction_arr.push(attraction);
						program_arr.push(program);
						stay_arr.push(stay);
						meal_plan_arr.push(meal_plan);
						package_p_id_arr.push(package_id1);
			    }
		  }
		}

		var price_str_url = $("#upload_url").val();
		var pckg_daywise_url = $('#pckg_daywise_url').val();

		var base_url = $('#base_url').val();

		$("#vi_confirm_box").vi_confirm_box({
			callback: function(result){
				if(result=="yes")
				{					
					$('#btn_quotation_save').button('loading');

					$.ajax({

						type:'post',

						url: base_url+'controller/package_tour/quotation/quotation_save.php',

						data:{ enquiry_id : enquiry_id,tour_name : tour_name, from_date : from_date, to_date : to_date, total_days : total_days, customer_name : customer_name, email_id : email_id,mobile_no : mobile_no, total_adult : total_adult, total_children : total_children, total_infant : total_infant, total_passangers : total_passangers, children_without_bed : children_without_bed, children_with_bed : children_with_bed, quotation_date : quotation_date, booking_type : booking_type,train_cost : train_cost,flight_cost : flight_cost, visa_cost : visa_cost, train_from_location_arr : train_from_location_arr, train_to_location_arr : train_to_location_arr, train_class_arr : train_class_arr, train_arrival_date_arr : train_arrival_date_arr, train_departure_date_arr : train_departure_date_arr,plane_from_city_arr : plane_from_city_arr,plane_to_city_arr : plane_to_city_arr, plane_from_location_arr : plane_from_location_arr, plane_to_location_arr : plane_to_location_arr,airline_name_arr : airline_name_arr , plane_class_arr : plane_class_arr, arraval_arr : arraval_arr, dapart_arr : dapart_arr,cruise_departure_date_arr : cruise_departure_date_arr, cruise_arrival_date_arr : cruise_arrival_date_arr, route_arr : route_arr,cabin_arr : cabin_arr,sharing_arr : sharing_arr, city_name_arr : city_name_arr, hotel_name_arr : hotel_name_arr,hotel_cat_arr:hotel_cat_arr, hotel_type_arr : hotel_type_arr, hotel_stay_days_arr : hotel_stay_days_arr,package_name_arr : package_name_arr,total_rooms_arr : total_rooms_arr,hotel_cost_arr : hotel_cost_arr,extra_bed_arr : extra_bed_arr,extra_bed_cost_arr : extra_bed_cost_arr,vehicle_name_arr : vehicle_name_arr,start_date_arr : start_date_arr,end_date_arr : end_date_arr,package_name_arr1 : package_name_arr1,tour_cost_arr : tour_cost_arr, markup_cost_arr : markup_cost_arr,markup_subtotal_arr : markup_subtotal_arr,excursion_cost_arr : excursion_cost_arr, taxation_id_arr : taxation_id_arr,service_tax_arr : service_tax_arr,service_tax_subtotal_arr : service_tax_subtotal_arr,total_tour_cost_arr : total_tour_cost_arr,package_name_arr2 : package_name_arr2,transport_cost_arr1 : transport_cost_arr1,transport_cost_arr : transport_cost_arr, package_id_arr : package_id_arr1, login_id : login_id,emp_id : emp_id,city_name_arr_e : city_name_arr_e,excursion_name_arr : excursion_name_arr,excursion_amt_arr : excursion_amt_arr,guide_cost : guide_cost,cruise_cost:cruise_cost,misc_cost:misc_cost,attraction_arr : attraction_arr,program_arr : program_arr,stay_arr : stay_arr,meal_plan_arr : meal_plan_arr,package_p_id_arr : package_p_id_arr, branch_admin_id : branch_admin_id,c_package_id_arr : c_package_id_arr,adult_cost_arr : adult_cost_arr,child_cost_arr : child_cost_arr,infant_cost_arr :infant_cost_arr,child_with_arr :child_with_arr,child_without_arr: child_without_arr,extra_bedc_arr: extra_bedc_arr,price_str_url  :price_str_url,incl_arr : incl_arr,excl_arr : excl_arr,financial_year_id : financial_year_id,pckg_daywise_url : pckg_daywise_url,costing_type :costing_type},

						success: function(message){

								$('#btn_quotation_save').button('reset');
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

									        	$('#btn_quotation_save').button('reset');
									        	$('#quotation_save_modal').modal('hide');

												//quotation_list_reflect();
												window.location.href = base_url+'view/package_booking/quotation/home/index.php';

									        }

									      }

									});

								}
			                }  

						});
				}			
                else{
                  $('#btn_quotation_save').button('reset'); 
                }
            }
        });

		}  
});
</script>