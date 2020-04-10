<form id="frm_tab3">

<div class="row mg_tp_10">

	<div class="col-md-2">

	    <input type="text" id="subtotal" name="subtotal" placeholder="Subtotal" title="Subtotal" value="0.00" onchange="flight_quotation_cost_calculate();validate_balance(this.id)">

	</div>
	<div class="col-md-2">
  		<input type="text" id="markup_cost" name="markup_cost" placeholder="Markup Cost(%)" title="Markup Cost(%)" onchange="flight_quotation_cost_calculate();validate_balance(this.id)" value="0.00">  
  	</div>
	<div class="col-md-2">
  		<input type="text" id="markup_cost_subtotal" name="markup_cost_subtotal" placeholder="Markup Cost Subtotal" title="Markup Cost Subtotal" onchange="flight_quotation_cost_calculate();" value="0.00">  
  	</div>
 	<div class="col-md-2">

  		<select name="taxation_id" id="taxation_id" title="Tax" onchange="flight_quotation_cost_calculate();generic_tax_reflect(this.id, 'service_tax', 'flight_quotation_cost_calculate');">

	        <?php get_taxation_dropdown(); ?>

	    </select>

	</div>    

	<input type="hidden" id="service_tax" name="service_tax" value="0">

	<div class="col-md-2">

		<input type="text" id="service_tax_subtotal" name="service_tax_subtotal" readonly placeholder="Tax Amount" title="Tax Amount">

	</div>

	<div class="col-md-2">

		<input type="text" id="total_tour_cost" class="amount_feild_highlight text-right" name="total_tour_cost" placeholder="Quotation Cost" title="Quotation Cost" value="0"  readonly>

	</div>

 </div>

	<div class="row mg_tp_20 text-center">

		<div class="col-md-12">

			<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab2()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>

			&nbsp;&nbsp;

			<button class="btn btn-sm btn-success" id="btn_quotation_save"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>

		</div>

	</div>

</form>



<script>

function switch_to_tab2(){ $('a[href="#tab2"]').tab('show'); }

$('#frm_tab3').validate({

	rules:{

	},

	submitHandler:function(form){

		var enquiry_id = $("#enquiry_id").val();

		var login_id = $("#login_id").val();

		var emp_id = $("#emp_id").val();

		var customer_name = $("#customer_name").val();

		var email_id = $('#email_id').val();

		var mobile_no = $('#mobile_no').val();

		var travel_datetime = $('#travel_datetime').val();

		var sector_from = $('#sector_from').val();

		var sector_to = $('#sector_to').val();

		var preffered_airline = $('#preffered_airline').val();

		var class_type = $('#class_type').val();

		var trip_type = $('#trip_type').val();

		var total_seats = $('#total_seats').val();

		var quotation_date = $('#quotation_date').val();

		var subtotal = $('#subtotal').val();
		var markup_cost = $('#markup_cost').val();
		var markup_cost_subtotal = $('#markup_cost_subtotal').val();

		var service_tax = $('#service_tax').val();

		var taxation_id = $('#taxation_id').val();

		var service_tax_subtotal = $('#service_tax_subtotal').val();

		var total_tour_cost = $('#total_tour_cost').val();
		var branch_admin_id = $('#branch_admin_id1').val();
		var financial_year_id = $('#financial_year_id').val();

		if(parseFloat(taxation_id) == "0"){ error_msg_alert("Please select Tax Percentage"); return false; }

		//Plane Information  
		var from_city_id_arr = new Array();
	    var to_city_id_arr = new Array();
		var plane_from_location_arr = new Array();

		var plane_to_location_arr = new Array();

		var airline_name_arr = new Array();

		var plane_class_arr = new Array();

		var arraval_arr = new Array();

		var dapart_arr = new Array();



		var table = document.getElementById("tbl_flight_quotation_dynamic_plane");

		  var rowCount = table.rows.length;

		  

		  for(var i=0; i<rowCount; i++)

		  {

		    var row = table.rows[i];

		     

		    if(row.cells[0].childNodes[0].checked)

		    {

		        var from_city_id1 = row.cells[2].childNodes[0].value;
		       var plane_from_location1 = row.cells[3].childNodes[0].value;   
		       var to_city_id1 = row.cells[4].childNodes[0].value;          

		       var plane_to_location1 = row.cells[5].childNodes[0].value;

		       var airline_name = row.cells[6].childNodes[0].value;  

		       var plane_class = row.cells[7].childNodes[0].value;         

		       var arraval1 = row.cells[8].childNodes[0].value;

		       var dapart1 = row.cells[9].childNodes[0].value;

		       if(from_city_id1=="")

			    {

			          error_msg_alert('Enter plane from city in row'+(i+1));

			          return false;

			    }

		       if(plane_from_location1=="")

		       {

		          error_msg_alert('Enter plane from location in row'+(i+1));

		          return false;

		       }

		       if(to_city_id1=="")

			    {

			          error_msg_alert('Enter plane To city in row'+(i+1));

			          return false;

			    }



		       if(plane_to_location1=="")

		       {

		          error_msg_alert('Enter plane to location in row'+(i+1));

		          return false;

		       }
		       
				if(arraval1=="")

				{ 

					error_msg_alert('Arraval Date time is required in row:'+(i+1)); 

					return false;

				}

				if(dapart1=="")

				{ 

					error_msg_alert("Daparture Date time is required in row:"+(i+1)); 

					return false;

				}

			   from_city_id_arr.push(from_city_id1);
			   to_city_id_arr.push(to_city_id1);

		       plane_from_location_arr.push(plane_from_location1);

		       plane_to_location_arr.push(plane_to_location1);

		       airline_name_arr.push(airline_name);

		       plane_class_arr.push(plane_class);

		       arraval_arr.push(arraval1);

		       dapart_arr.push(dapart1);



		    }      

		  }



		var base_url = $('#base_url').val();

		$('#btn_quotation_save').button('loading');

		$.ajax({

			type:'post',

			url: base_url+'controller/package_tour/quotation/flight/quotation_save.php',

			data:{ enquiry_id : enquiry_id , login_id : login_id, emp_id : emp_id, customer_name : customer_name, email_id : email_id, mobile_no : mobile_no ,travel_datetime : travel_datetime, sector_from : sector_from, sector_to : sector_to,preffered_airline : preffered_airline,class_type : class_type, trip_type : trip_type, total_seats : total_seats, quotation_date : quotation_date, subtotal : subtotal,markup_cost:markup_cost,markup_cost_subtotal : markup_cost_subtotal, service_tax : service_tax , taxation_id : taxation_id, service_tax_subtotal : service_tax_subtotal, total_tour_cost : total_tour_cost, plane_from_location_arr : plane_from_location_arr, plane_to_location_arr : plane_to_location_arr,airline_name_arr : airline_name_arr , plane_class_arr : plane_class_arr, arraval_arr : arraval_arr, dapart_arr : dapart_arr, from_city_id_arr : from_city_id_arr , to_city_id_arr : to_city_id_arr, branch_admin_id : branch_admin_id,financial_year_id :financial_year_id},

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

						        	quotation_list_reflect();

						        	//document.location.reload();

						        }

						      }

						});

					}



                }  



                

		});

	}  



});



        	 

</script>