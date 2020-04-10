<form id="frm_tab4">
	
		<div class="row">
			<div class="col-md-2 col-md-offset-3 col-sm-4 col-xs-12 mg_bt_10">
				<input type="text" id="payment_date" name="payment_date" placeholder="Date" title="Date" value="<?= date('d-m-Y')?>" onchange="check_valid_date(this.id)">
			</div>
			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
				<input type="text" id="payment_amount" name="payment_amount" placeholder="*Amount" title="Amount" onchange="payment_amount_validate(this.id,'payment_mode','transaction_id','bank_name','bank_id')">
			</div>
			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
				<select name="payment_mode" id="payment_mode" title="Payment" onchange="payment_master_toggles(this.id, 'bank_name', 'transaction_id', 'bank_id')">
					<?php get_payment_mode_dropdown(); ?>
				</select>
			</div>	
		</div>

		<div class="row">	
			<div class="col-md-2 col-md-offset-3 col-sm-4 col-xs-12 mg_bt_10">
				<input type="text" id="bank_name" name="bank_name" class="bank_suggest" placeholder="Bank Name" title="Bank Name" disabled>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
				<input type="text" id="transaction_id" name="transaction_id" onchange="validate_specialChar(this.id);" placeholder="Cheque No/ID" title="Cheque No/ID" disabled>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
		        <select name="bank_id" id="bank_id" title="Select Bank" disabled>
		          <?php get_bank_dropdown(); ?>
		        </select>
		    </div>
		</div>
	    <div class="row">
	      <div class="col-md-9 col-md-offset-3 col-sm-9">
	       <span style="color: red;line-height: 35px;" data-original-title="" title="" class="note"><?= $txn_feild_note ?></span>
	     </div>
	    </div>
	<br>

		<div class="row mg_tp_20 text-center">
			<div class="col-xs-12">		
				<button class="btn btn-sm btn-info btn-sm ico_left" type="button" onclick="switch_to_tab3()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>

				&nbsp;&nbsp;
				<button class="btn btn-sm btn-success" id="btn_ticket_save"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
			</div>
		</div>
</form>
<script>

function switch_to_tab3(){ $('a[href="#tab3"]').tab('show'); }

$('#frm_tab4').validate({

	rules:{

		payment_date : { required : true },

		payment_amount : { required : true, number: true },

		payment_mode : { required : true },

		type_of_tour : { required : true },

		bank_name : { required : function(){  if($('#payment_mode').val()!="Cash"){ return true; }else{ return false; }  }  },

        transaction_id : { required : function(){  if($('#payment_mode').val()!="Cash"){ return true; }else{ return false; }  }  },     

        bank_id : { required : function(){  if($('#payment_mode').val()!="Cash"){ return true; }else{ return false; }  }  },  

	},

	submitHandler:function(form){

		var base_url = $('#base_url').val();
		var customer_id = $('#customer_id').val();
		var cust_first_name = $('#cust_first_name').val();
	    var cust_middle_name = $('#cust_middle_name').val();
	    var cust_last_name = $('#cust_last_name').val();
	    var gender = $('#cust_gender').val();
	    var cust_birth_date = $('#cust_birth_date').val();
	    var age = $('#cust_age').val();
	    var contact_no = $('#cust_contact_no').val();
	    var email_id = $('#cust_email_id').val();
	    var address = $('#cust_address1').val();
	    var address2 = $('#cust_address2').val();
	    var city = $('#city').val();
	    var service_tax_no = $('#cust_service_tax_no').val();  
	    var landline_no = $('#cust_landline_no').val();
	    var alt_email_id = $('#cust_alt_email_id').val();
	    var company_name = $('#corpo_company_name').val();
	    var credit_amount = $('#credit_amount').val();
	    var cust_type = $('#cust_type').val();
	    var state = $('#cust_state').val();
		var active_flag = 'Active';
		var branch_admin_id = $('#branch_admin_id1').val();
		var financial_year_id = $('#financial_year_id').val();
 		
 		//New Customer save
		if(customer_id == '0'){
		    $.ajax({
		        type: 'post',
		        url: base_url+'controller/customer_master/customer_save.php',
		        data:{ first_name : cust_first_name, middle_name : cust_middle_name, last_name : cust_last_name, gender : gender, birth_date : cust_birth_date, age : age, contact_no : contact_no, email_id : email_id, address : address,address2 : address2,city:city,  active_flag : active_flag ,service_tax_no : service_tax_no, landline_no : landline_no, alt_email_id : alt_email_id,company_name : company_name, cust_type : cust_type,state : state, branch_admin_id : branch_admin_id},
		        success: function(result){
		        }
		    });
		}
		//Flight Save
		var emp_id = $('#emp_id').val();
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
		var booking_date = $('#booking_date').val();
		var sup_id = $('#sup_id').val();

		var ticket_total_cost = $('#ticket_total_cost').val();


		var payment_date = $('#payment_date').val();

		var payment_amount = $('#payment_amount').val();

		var payment_mode = $('#payment_mode').val();

		var bank_name = $('#bank_name').val();

		var transaction_id = $('#transaction_id').val();	

		var bank_id = $('#bank_id').val();


		var first_name_arr = new Array(); 

		var middle_name_arr = new Array(); 

		var last_name_arr = new Array(); 

		var birth_date_arr = new Array(); 

		var adolescence_arr = new Array(); 

		var ticket_no_arr = new Array(); 

		var gds_pnr_arr = new Array(); 		


		if(credit_amount != ''){ 
        	if(parseFloat(payment_amount) > parseFloat(credit_amount)) { error_msg_alert('Low Credit note balance'); return false; }
        }

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



			  first_name_arr.push(first_name);

			  middle_name_arr.push(middle_name);

			  last_name_arr.push(last_name);

			  birth_date_arr.push(birth_date);

			  adolescence_arr.push(adolescence);

			  ticket_no_arr.push(ticket_no);

			  gds_pnr_arr.push(gds_pnr);



          }      

        }	

        var from_city_id_arr = getDynFields('from_city');
        var to_city_id_arr = getDynFields('to_city');

        var departure_datetime_arr = getDynFields('departure_datetime');

		var arrival_datetime_arr = getDynFields('arrival_datetime');

		var airlines_name_arr = getDynFields('airlines_name');

		var class_arr = getDynFields('class');

		var flight_no_arr = getDynFields('flight_no');

		var airlin_pnr_arr = getDynFields('airlin_pnr');

		var departure_city_arr = getDynFields('arrival_city');

		var arrival_city_arr = getDynFields('departure_city');
		var meal_plan_arr = getDynFields('meal_plan');
		var luggage_arr = getDynFields('luggage');
		var special_note_arr = getDynFields('special_note');

		var payment_date = $('#payment_date').val();
		$.post(base_url+'view/load_data/finance_date_validation.php', { check_date: payment_date }, function(data){
		if(data !== 'valid'){
			error_msg_alert("The Payment date does not match between selected Financial year.");
			return false;
		}else{

				$('#btn_ticket_save').button('loading');

						$.ajax({

							type:'post',

							url: base_url+'controller/visa_passport_ticket/ticket/ticket_master_save.php',

							data:{ emp_id : emp_id,customer_id : customer_id, tour_type : tour_type, type_of_tour : type_of_tour, adults : adults, childrens : childrens, infant : infant, adult_fair : adult_fair, children_fair : children_fair, infant_fair : infant_fair, basic_cost : basic_cost, basic_cost_markup : basic_cost_markup, basic_cost_discount : basic_cost_discount, yq_tax : yq_tax, yq_tax_markup : yq_tax_markup, yq_tax_discount : yq_tax_discount, g1_plus_f2_tax : g1_plus_f2_tax, service_charge : service_charge, taxation_type : taxation_type, taxation_id : taxation_id, service_tax : service_tax, service_tax_subtotal : service_tax_subtotal, tds : tds, due_date : due_date, ticket_total_cost : ticket_total_cost, payment_date : payment_date, payment_amount : payment_amount, payment_mode : payment_mode, bank_name : bank_name, transaction_id : transaction_id, bank_id : bank_id, first_name_arr : first_name_arr, middle_name_arr : middle_name_arr, last_name_arr : last_name_arr, birth_date_arr : birth_date_arr, adolescence_arr : adolescence_arr, ticket_no_arr : ticket_no_arr, gds_pnr_arr : gds_pnr_arr, from_city_id_arr : from_city_id_arr, to_city_id_arr : to_city_id_arr, departure_datetime_arr : departure_datetime_arr, arrival_datetime_arr : arrival_datetime_arr, airlines_name_arr : airlines_name_arr, class_arr : class_arr, flight_no_arr : flight_no_arr, airlin_pnr_arr : airlin_pnr_arr, departure_city_arr : departure_city_arr, arrival_city_arr : arrival_city_arr, special_note_arr : special_note_arr, booking_date : booking_date,sup_id : sup_id, meal_plan_arr : meal_plan_arr, luggage_arr : luggage_arr, branch_admin_id : branch_admin_id,financial_year_id : financial_year_id },

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

</script>