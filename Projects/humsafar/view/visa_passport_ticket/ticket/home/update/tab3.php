<form id="frm_tab3">



	<div class="row">



		<div class="col-md-6">

			<div class="panel panel-default panel-body app_panel_style feildset-panel">
				<legend>Basic Amount</legend>

				

				<div class="row mg_bt_10">

					<div class="col-sm-4 col-xs-12 mg_bt_10">

						<input type="text" id="adults" name="adults" placeholder="Adults" title="Adults" readonly value="<?= $sq_ticket['adults'] ?>">

					</div>

					<div class="col-sm-4 col-xs-12 mg_bt_10">

						<input type="text" id="childrens" name="childrens" placeholder="Childrens" title="Childrens" readonly value="<?= $sq_ticket['childrens'] ?>">

					</div>

					<div class="col-sm-4 col-xs-12 mg_bt_10">

						<input type="text" id="infant" name="infant" placeholder="Infant" title="Infant" readonly value="<?= $sq_ticket['infant'] ?>">

					</div>			

					<div class="col-sm-4 col-xs-12 mg_bt_10_sm_xs">

						<input type="text" id="adult_fair" name="adult_fair" placeholder="Adult Fare" title="Adult Fare" onchange="calculate_total_amount();validate_balance(this.id)" value="<?= $sq_ticket['adult_fair'] ?>">

					</div>		

					<div class="col-sm-4 col-xs-12 mg_bt_10_sm_xs">

						<input type="text" id="children_fair" name="children_fair" placeholder="Children Fare" title="Children Fare" onchange="calculate_total_amount();validate_balance(this.id)" value="<?= $sq_ticket['children_fair'] ?>">

					</div>		

					<div class="col-sm-4 col-xs-12">

						<input type="text" id="infant_fair" name="infant_fair" placeholder="Infant Fare" title="Infant Fare" onchange="calculate_total_amount();validate_balance(this.id)" value="<?= $sq_ticket['infant_fair'] ?>">

					</div>

				</div>



			</div>

		</div>



		<div class="col-md-6">

			<div class="panel panel-default panel-body app_panel_style feildset-panel">
				<legend>Other Calculations</legend> 



				<div class="row mg_bt_10">

					<div class="col-sm-4 col-xs-12 mg_bt_10">

						<input type="text" id="basic_cost" name="basic_cost" placeholder="Basic Amount" title="Basic Amount" readonly value="<?= $sq_ticket['basic_cost'] ?>">

					</div>

					<div class="col-sm-4 col-xs-12 mg_bt_10">

						<input type="text" id="basic_cost_markup" name="basic_cost_markup" placeholder="Markup" title="Markup Cost" onchange="calculate_total_amount();validate_balance(this.id)" value="<?= $sq_ticket['basic_cost_markup'] ?>">

					</div>

					<div class="col-sm-4 col-xs-12 mg_bt_10">

						<input type="text" id="basic_cost_discount" name="basic_cost_discount" placeholder="Discount" title="Discount" onchange="calculate_total_amount();validate_balance(this.id)" value="<?= $sq_ticket['basic_cost_discount'] ?>">

					</div>			

					<div class="col-sm-4 col-xs-12 mg_bt_10_sm_xs">

						<input type="text" id="yq_tax" name="yq_tax" placeholder="YQ Tax" title="YQ Tax" onchange="calculate_total_amount();validate_balance(this.id)" value="<?= $sq_ticket['yq_tax'] ?>">

					</div>

					<div class="col-sm-4 col-xs-12 mg_bt_10_sm_xs">

						<input type="text" id="yq_tax_markup" name="yq_tax_markup" placeholder="YQ Markup" title="YQ Markup Cost" onchange="calculate_total_amount();validate_balance(this.id)" value="<?= $sq_ticket['yq_tax_markup'] ?>">

					</div>

					<div class="col-sm-4 col-xs-12">

						<input type="text" id="yq_tax_discount" name="yq_tax_discount" placeholder="YQ Discount" title="YQ Discount" onchange="calculate_total_amount();validate_balance(this.id)" value="<?= $sq_ticket['yq_tax_discount'] ?>">

					</div>

				</div>



			</div>

		</div>



	</div>



	<div class="panel panel-default panel-body">



		<div class="row mg_bt_10">

				

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<input type="text" id="g1_plus_f2_tax" name="g1_plus_f2_tax" placeholder="G1+F2 Tax" title="G1+F2 Tax" onchange="calculate_total_amount();validate_balance(this.id)" value="<?= $sq_ticket['g1_plus_f2_tax'] ?>">

			</div>

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<input type="text" name="service_charge" id="service_charge" placeholder="Service Charge" title="Service Charge" onchange="calculate_total_amount();validate_balance(this.id)" value="<?= $sq_ticket['service_charge'] ?>">

			</div>

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

              <select name="taxation_type" id="taxation_type" title="Taxation Type">

                <option value="<?= $sq_ticket['taxation_type'] ?>"><?= $sq_ticket['taxation_type'] ?></option>

                <?php get_taxation_type_dropdown($setup_country_id) ?>

              </select>

            </div>    

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
				<select name="taxation_id" id="taxation_id" title="Tax" onchange="generic_tax_reflect(this.id, 'service_tax', 'calculate_total_amount');">
					<?php 

                       if($sq_ticket['taxation_id']!='0'){
                         $sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$sq_ticket[taxation_id]'"));
                         $sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));
                         ?>
                     	<option value="<?= $sq_taxation['taxation_id'] ?>"><?= $sq_tax_type['tax_type'].'-'.$sq_taxation['tax_in_percentage'] ?></option>
                     	<?php } ?>
		            <?php get_taxation_dropdown(); ?>

		        </select>
		        <input type="hidden" id="service_tax" name="service_tax"  value="<?= $sq_ticket['service_tax'] ?>">			        
			</div>

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<input type="text" id="service_tax_subtotal" name="service_tax_subtotal" placeholder="Tax Amount" title="Tax Amount" disabled value="<?= $sq_ticket['service_tax_subtotal'] ?>">

			</div>	

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<input type="text" id="tds" name="tds" placeholder="TDS" title="TDS" onchange="calculate_total_amount();validate_balance(this.id)" value="<?= $sq_ticket['tds'] ?>">

			</div>			



		</div>



		<div class="row">

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_sm_xs">

				<input type="text" name="ticket_total_cost" id="ticket_total_cost" class="amount_feild_highlight text-right" placeholder="Net Total" title="Net Total" readonly value="<?= $sq_ticket['ticket_total_cost'] ?>">

			</div>

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<input type="text" name="due_date" id="due_date" onchange="validate_dueDate('booking_date1','due_date');" placeholder="Due Date" title="Due Date" value="<?= get_date_user($sq_ticket['due_date']) ?>">

			</div>

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<input type="text" name="booking_date1" id="booking_date1" onchange="validate_bookingDate('booking_date1','due_date');check_valid_date(this.id)" placeholder="Booking Date" title="Booking Date" value="<?= get_date_user($sq_ticket['created_at']) ?>">

			</div>
			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
				<select id="sup_id1" name="sup_id1" style="width:100%" title="Supplier">
					<?php 
	                  $supplier_id = $sq_ticket['supplier_id'];
	                  $sq_sup1 = mysql_fetch_assoc(mysql_query("select * from ticket_vendor where vendor_id='$sq_ticket[supplier_id]'"));

	                  if ($sq_sup1['vendor_id']!="") {
	                 ?>
	                  	  <option value="<?= $sq_sup1['vendor_id'] ?>"><?= $sq_sup1['vendor_name'] ?></option>
					<?php 
	                  }
	                  else{
	                 ?>
	                  	<option value="">Select Supplier</option>
					<?php 
	                  }
	                 ?>
	                
					<option value="">Select Supplier</option>
					<?php 
					$sq_sup = mysql_query("select * from ticket_vendor where active_flag='Active' ");
					while($row_sup = mysql_fetch_assoc($sq_sup)){
						?>
						<option value="<?= $row_sup['vendor_id'] ?>"><?= $row_sup['vendor_name'] ?></option>
						<?php
					}
					?>
				</select>
			</div>

		</div>



	</div>



	<div class="row text-center mg_tp_20">

		<div class="col-xs-12">

			<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab2()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>

			&nbsp;&nbsp;

			<button class="btn btn-sm btn-success" id="btn_ticket_save"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>

		</div>

	</div>





</form>



<script>
$('#sup_id1').select2();
$(function(){

	$('#frm_tab3').validate({

		rules:{

				adults: { required : true, number : true },

				childrens: { required : true, number : true },

				infant: { required : true, number : true },

				adult_fair: { required : true, number : true },

				children_fair: { required : true, number : true },

				infant_fair: { required : true, number : true },

				basic_cost: { required : true, number : true },

				basic_cost_markup: { required : true, number : true },

				taxation_type : { required : true },

				taxation_id: { required : true, number : true },

				service_tax: { required : true, number : true },

				service_tax_subtotal: { required : true, number : true },
				
				ticket_total_cost: { required : true, number : true },

				booking_date1: { required : true },

		},

		submitHandler:function(form){

				

				var ticket_id = $('#ticket_id').val();

				var customer_id = $('#customer_id').val();

				var tour_type = $('#tour_type').val();

				var type_of_tour = $('input[name="type_of_tour"]:checked').val();



				var adults = $('#adults').val();

				var childrens = $('#childrens').val();

				var infant = $('#infant').val();

				var adult_fair = $('#adult_fair').val();

				var children_fair = $('#children_fair').val();

				var infant_fair = $('#infant_fair').val();

				var basic_cost = $('#basic_cost').val();

				var basic_cost_markup = $('#basic_cost_markup').val();

				var basic_cost_discount = $('#basic_cost_discount').val();

				var yq_tax = $('#yq_tax').val();

				var yq_tax_markup = $('#yq_tax_markup').val();

				var yq_tax_discount = $('#yq_tax_discount').val();

				var g1_plus_f2_tax = $('#g1_plus_f2_tax').val();

				var service_charge = $('#service_charge').val();

				var taxation_type = $('#taxation_type').val();

				var taxation_id = $('#taxation_id').val();

				var service_tax = $('#service_tax').val();

				var service_tax_subtotal = $('#service_tax_subtotal').val();

				var tds = $('#tds').val();

				var due_date = $('#due_date').val();
				var booking_date1 = $('#booking_date1').val();
				var sup_id = $('#sup_id1').val();

				var ticket_total_cost = $('#ticket_total_cost').val();

				if(parseFloat(taxation_id) == "0"){ error_msg_alert("Please select Tax Percentage"); return false; }


				var first_name_arr = new Array(); 

				var middle_name_arr = new Array(); 

				var last_name_arr = new Array(); 

				var birth_date_arr = new Array(); 

				var adolescence_arr = new Array(); 

				var ticket_no_arr = new Array(); 

				var gds_pnr_arr = new Array(); 

				var entry_id_arr = new Array(); 

				



		        var table = document.getElementById("tbl_dynamic_ticket_master");

		        var rowCount = table.rows.length;

		        

		        for(var i=0; i<rowCount; i++)

		        {

		          var row = table.rows[i];

		           

		          if(row.cells[0].childNodes[0].checked)

		          {



					  var first_name = row.cells[2].childNodes[0].value;

					  var middle_name = row.cells[3].childNodes[0].value;

					  var last_name = row.cells[4].childNodes[0].value;

					  var birth_date = row.cells[5].childNodes[0].value;

					  var adolescence = row.cells[6].childNodes[0].value;

					  var ticket_no = row.cells[7].childNodes[0].value;

					  var gds_pnr = row.cells[8].childNodes[0].value;

					  

					  if(row.cells[9]){

					  	var entry_id = row.cells[9].childNodes[0].value;

					  }

					  else{

					  	var entry_id = "";

					  }

					  

					  first_name_arr.push(first_name);

					  middle_name_arr.push(middle_name);

					  last_name_arr.push(last_name);

					  birth_date_arr.push(birth_date);

					  adolescence_arr.push(adolescence);

					  ticket_no_arr.push(ticket_no);

					  gds_pnr_arr.push(gds_pnr);

					  entry_id_arr.push(entry_id);



		          }      

		        }	

		        var from_city_id_arr = getDynFields('from_city_id');
                var to_city_id_arr = getDynFields('to_city_id');
 
		        var departure_datetime_arr = getDynFields('departure_datetime');

				var arrival_datetime_arr = getDynFields('arrival_datetime');

				var airlines_name_arr = getDynFields('airlines_name');

				var class_arr = getDynFields('class');

				var flight_no_arr = getDynFields('flight_no');

				var airlin_pnr_arr = getDynFields('airlin_pnr');

				var departure_city_arr = getDynFields('departure_city');

				var arrival_city_arr = getDynFields('arrival_city');

				var special_note_arr = getDynFields('special_note');

				var trip_entry_id_arr = getDynFields('trip_entry_id');
				var meal_plan_arr = getDynFields('meal_plan');
				var luggage_arr = getDynFields('luggage');

				

			//Validation for booking and payment date in login financial year
			var base_url = $('#base_url').val();
			var check_date1 = $('#booking_date1').val();
			$.post(base_url+'view/load_data/finance_date_validation.php', { check_date: check_date1 }, function(data){
				if(data !== 'valid'){
					error_msg_alert("The Booking date does not match between selected Financial year.");
					return false;
				}else{
							$('#btn_ticket_save').button('loading');
							$.ajax({

									type:'post',

									url: base_url+'controller/visa_passport_ticket/ticket/ticket_master_update.php',
									data:{ ticket_id : ticket_id, customer_id : customer_id, tour_type : tour_type, type_of_tour : type_of_tour, adults : adults, childrens : childrens, infant : infant, adult_fair : adult_fair, children_fair : children_fair, infant_fair : infant_fair, basic_cost : basic_cost, basic_cost_markup : basic_cost_markup, basic_cost_discount : basic_cost_discount, yq_tax : yq_tax, yq_tax_markup : yq_tax_markup, yq_tax_discount : yq_tax_discount, g1_plus_f2_tax : g1_plus_f2_tax, service_charge : service_charge, taxation_type : taxation_type, taxation_id : taxation_id, service_tax : service_tax, service_tax_subtotal : service_tax_subtotal, tds : tds, due_date : due_date, ticket_total_cost : ticket_total_cost, first_name_arr : first_name_arr, middle_name_arr : middle_name_arr, last_name_arr : last_name_arr, birth_date_arr : birth_date_arr, adolescence_arr : adolescence_arr, ticket_no_arr : ticket_no_arr, gds_pnr_arr : gds_pnr_arr, entry_id_arr : entry_id_arr, departure_datetime_arr : departure_datetime_arr, arrival_datetime_arr : arrival_datetime_arr, airlines_name_arr : airlines_name_arr, class_arr : class_arr, flight_no_arr : flight_no_arr, airlin_pnr_arr : airlin_pnr_arr, departure_city_arr : departure_city_arr, arrival_city_arr : arrival_city_arr, special_note_arr : special_note_arr, trip_entry_id_arr : trip_entry_id_arr,booking_date1 : booking_date1,sup_id : sup_id, meal_plan_arr : meal_plan_arr, luggage_arr : luggage_arr, from_city_id_arr : from_city_id_arr, to_city_id_arr : to_city_id_arr },

									success:function(result){

										$('#btn_ticket_save').button('reset');

										var msg = result.split('--');

										if(msg[0]=="error"){

											msg_alert(result);

										}

										else{

											msg_alert(result);

											$('#ticket_save_modal').modal('hide');

											ticket_customer_list_reflect();

										}

									}

								});
							}
			});



		}

	});

});

function switch_to_tab2(){ $('a[href="#tab2"]').tab('show'); }

</script>



