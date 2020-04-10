<form id="frm_tab3">

 

	<div class="row">



		<div class="col-md-6 col-sm-12 col-xs-12 mg_bt_10_xs">

			<div class="panel panel-default panel-body app_panel_style feildset-panel">
				<legend>Basic Amount</legend> 
				

				<div class="row">

					<div class="col-sm-4 col-xs-12 mg_bt_10">

						<input type="text" id="adults" name="adults" placeholder="*Adults" title="Adults" readonly>

					</div>

					<div class="col-sm-4 col-xs-12 mg_bt_10">

						<input type="text" id="childrens" name="childrens" placeholder="*Children" title="Childrens" readonly>

					</div>

					<div class="col-sm-4 col-xs-12 mg_bt_10">

						<input type="text" id="infant" name="infant" placeholder="*Infants" title="Infant" readonly>

					</div>			

					<div class="col-sm-4 col-xs-12 mg_bt_10_sm_xs">

						<input type="text" id="adult_fair" name="adult_fair" placeholder="*Adult Fare" title="Adult Fare" onchange="calculate_total_amount();validate_balance(this.id)">

					</div>		

					<div class="col-sm-4 col-xs-12 mg_bt_10_sm_xs">

						<input type="text" id="children_fair" name="children_fair" placeholder="*Children Fare" title="Children Fare" onchange="calculate_total_amount();validate_balance(this.id)">

					</div>		

					<div class="col-sm-4 col-xs-12">

						<input type="text" id="infant_fair" name="infant_fair" placeholder="*Infant Fare" title="Infant Fare" onchange="calculate_total_amount();validate_balance(this.id)">

					</div>

				</div>



			</div>

		</div>



		<div class="col-md-6 col-sm-12 col-xs-12 mg_bt_10_xs">

			<div class="panel panel-default panel-body app_panel_style feildset-panel">
				<legend>Other Calculations</legend> 


				<div class="row">

					<div class="col-sm-4 col-xs-12 mg_bt_10">

						<input type="text" id="basic_cost" name="basic_cost" placeholder="*Basic Amount" title="Basic Amount" readonly>

					</div>

					<div class="col-sm-4 col-xs-12 mg_bt_10">

						<input type="text" id="basic_cost_markup" name="basic_cost_markup" placeholder="Markup" title="Markup Cost" onchange="calculate_total_amount();validate_balance(this.id)">

					</div>

					<div class="col-sm-4 col-xs-12 mg_bt_10">

						<input type="text" id="basic_cost_discount" name="basic_cost_discount" placeholder="Discount" title="Discount" onchange="calculate_total_amount();validate_balance(this.id)">

					</div>			

					<div class="col-sm-4 col-xs-12 mg_bt_10_sm_xs">

						<input type="text" id="yq_tax" name="yq_tax" placeholder="YQ Tax" title="YQ Tax" onchange="calculate_total_amount();validate_balance(this.id)">

					</div>

					<div class="col-sm-4 col-xs-12 mg_bt_10_sm_xs">

						<input type="text" id="yq_tax_markup" name="yq_tax_markup" placeholder="YQ Markup" title="YQ Markup Cost" onchange="calculate_total_amount();validate_balance(this.id)">

					</div>

					<div class="col-sm-4 col-xs-12">

						<input type="text" id="yq_tax_discount" name="yq_tax_discount" placeholder="YQ Discount" title="YQ Discount" onchange="calculate_total_amount();validate_balance(this.id)">

					</div>

				</div>



			</div>

		</div>



	</div>



	<div class="panel panel-default panel-body">

				

		<div class="row">

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<input type="text" id="g1_plus_f2_tax" name="g1_plus_f2_tax" placeholder="G1+F2 Tax" title="G1+F2 Tax" onchange="calculate_total_amount();validate_balance(this.id)">

			</div>

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<input type="text" name="service_charge" id="service_charge" placeholder="Service Charge" title="Service Charge" onchange="calculate_total_amount();validate_balance(this.id)">

			</div>

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

              <select name="taxation_type" id="taxation_type" title="Taxation Type">

                <?php get_taxation_type_dropdown($setup_country_id) ?>

              </select>

            </div>

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<select name="taxation_id" id="taxation_id" title="Tax" onchange="generic_tax_reflect(this.id, 'service_tax', 'calculate_total_amount');">

		            <?php get_taxation_dropdown(); ?>

		        </select>

		        <input type="hidden" id="service_tax" name="service_tax" value="0">			        

			</div>

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<input type="text" id="service_tax_subtotal" name="service_tax_subtotal" placeholder="*Tax Amount" title="Tax Amount" readonly>

			</div>	

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<input type="text" id="tds" name="tds" placeholder="TDS" title="TDS" onchange="calculate_total_amount()">

			</div>			

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<input type="text" name="ticket_total_cost" id="ticket_total_cost" placeholder="Net Total" title="Net Amount" class="amount_feild_highlight text-right" readonly>

			</div>

			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<input type="text" name="due_date" id="due_date" placeholder="Due Date" title="Due Date" value="<?= date('d-m-Y') ?>" >

			</div>
			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<input type="text" name="booking_date" id="booking_date" placeholder="Booking Date" value="<?= date('d-m-Y') ?>" title="Booking Date" onchange="check_valid_date(this.id)">

			</div>
			<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">

				<select id="sup_id" name="sup_id" style="width:100%" title="Supplier">
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

			<button class="btn btn-info btn-sm ico_right">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>

		</div>

	</div>





</form>



<script>
$('#sup_id').select2();
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

				basic_cost_markup: {number : true },

				taxation_type: { required : true },

				taxation_id: { required : true, number : true },

				service_tax: { required : true, number : true },

				service_tax_subtotal: { required : true, number : true },

				ticket_total_cost: { required : true, number : true },
				booking_date : { required : true},

		},

		submitHandler:function(form){
			var base_url = $('#base_url').val();
			var taxation_id = $('#taxation_id').val();
			if(parseFloat(taxation_id) == "0"){ error_msg_alert("Please select Tax Percentage"); return false; }
			//Validation for booking and payment date in login financial year
			var check_date1 = $('#booking_date').val();
				$.post(base_url+'view/load_data/finance_date_validation.php', { check_date: check_date1 }, function(data){
					if(data !== 'valid'){
						error_msg_alert("The Booking date does not match between selected Financial year.");
						return false;
					}else{
						$('a[href="#tab4"]').tab('show');
					}
				});

		}

	});

});

function switch_to_tab2(){ $('a[href="#tab2"]').tab('show'); }

</script>



