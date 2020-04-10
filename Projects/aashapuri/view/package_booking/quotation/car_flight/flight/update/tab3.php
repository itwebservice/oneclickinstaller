<form id="frm_tab31">

<div class="row mg_tp_10">

	<div class="col-md-2">

	    <input type="text" id="subtotal1" name="subtotal1" placeholder="Subtotal" title="Subtotal"  onchange="flight_quotation_cost_calculate('1');validate_balance(this.id)" value="<?= $sq_quotation['subtotal'] ?>">

	</div>
	<div class="col-md-2">

	    <input type="text" id="markup_cost1" name="markup_cost1" placeholder="Markup Cost" title="Markup Cost"  onchange="flight_quotation_cost_calculate('1');validate_balance(this.id)" value="<?= $sq_quotation['markup_cost'] ?>">

	</div>
	<div class="col-md-2">
  		<input type="text" id="markup_cost_subtotal1" name="markup_cost_subtotal1" placeholder="Markup Cost Subtotal" title="Markup Cost Subtotal" onchange="flight_quotation_cost_calculate('1');" value="<?= $sq_quotation['markup_cost_subtotal'] ?>">  
  	</div>
 	<div class="col-md-2">

  		<select name="taxation_id1" id="taxation_id1" title="Tax" onchange="flight_quotation_cost_calculate('1');generic_tax_reflect(this.id, 'service_tax1', 'flight_quotation_cost_calculate','1');">
			<?php 
	           if($sq_quotation['taxation_id']=="" || $sq_quotation['taxation_id']==0){
	             get_taxation_dropdown();  
	           }
	           else{
	             $sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$sq_quotation[taxation_id]'"));
	             $sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));
	             ?>
		         <option value="<?= $sq_taxation['taxation_id'] ?>"><?= $sq_tax_type['tax_type'].'-'.$sq_taxation['tax_in_percentage'] ?></option>
		         <?php get_taxation_dropdown();  
	            } ?>

	    </select>

	</div>    

	<input type="hidden" id="service_tax1" name="service_tax1" value="<?= $sq_quotation['service_tax'] ?>">

	<div class="col-md-2">

		<input type="text" id="service_tax_subtotal1" name="service_tax_subtotal1" readonly placeholder="Tax Amount" title="Tax Amount" value="<?= $sq_quotation['service_tax_subtotal'] ?>">

	</div>

	<div class="col-md-2">

		<input type="text" id="total_tour_cost1" class="amount_feild_highlight text-right" name="total_tour_cost1" placeholder="Quotation Cost" title="Quotation Cost" value="<?= $sq_quotation['quotation_cost'] ?>"readonly>

	</div>

 </div>

	<div class="row mg_tp_20 text-center">

		<div class="col-md-12">

			<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab2()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>

			&nbsp;&nbsp;

			<button class="btn btn-sm btn-success" id="btn_quotation_update"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>

		</div>

	</div>

</form>

<script>
 
function switch_to_tab2(){ $('a[href="#tab_2"]').tab('show'); }

$('#frm_tab31').validate({
	rules:{
		taxation_id1 : { required : true },
	},
	submitHandler:function(form){
		var quotation_id = $('#quotation_id1').val();
		var enquiry_id = $('#enquiry_id1').val();
		var customer_name = $("#customer_name1").val();
		var email_id = $('#email_id1').val();
		var mobile_no = $('#mobile_no1').val();
		var travel_datetime = $('#travel_datetime1').val();
		var sector_from = $('#sector_from1').val();
		var sector_to = $('#sector_to1').val();
		var preffered_airline = $('#preffered_airline1').val();
		var class_type = $('#class_type1').val();
		var trip_type = $('#trip_type1').val();
		var total_seats = $('#total_seats1').val();
		var quotation_date = $('#quotation_date1').val();
		var subtotal = $('#subtotal1').val();
		var markup_cost = $('#markup_cost1').val();
		var markup_cost_subtotal = $('#markup_cost_subtotal1').val();
		var service_tax = $('#service_tax1').val();
		var taxation_id = $('#taxation_id1').val();
		var service_tax_subtotal = $('#service_tax_subtotal1').val();
		var total_tour_cost = $('#total_tour_cost1').val();
		 
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
		var plane_id_arr = new Array();

		var table = document.getElementById("tbl_flight_quotation_dynamic_plane_update");
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

	           var dapart1 = row.cells[8].childNodes[0].value;

	           var arraval1 = row.cells[9].childNodes[0].value;

		        if(row.cells[10] && row.cells[10].childNodes[0]){
	            var plane_id = row.cells[10].childNodes[0].value;
	           }

		       else{
		       	var plane_id = "";
		       }     
		       
		       from_city_id_arr.push(from_city_id1);
               to_city_id_arr.push(to_city_id1);
		       plane_from_location_arr.push(plane_from_location1);
		       plane_to_location_arr.push(plane_to_location1);
		       airline_name_arr.push(airline_name);
		       plane_class_arr.push(plane_class);
		       arraval_arr.push(arraval1);
		       dapart_arr.push(dapart1);
		       plane_id_arr.push(plane_id);
		      
		    }      
		  }
 
		var base_url = $('#base_url').val();
		$('#btn_quotation_update').button('loading');

		$.ajax({
			type:'post',
			url: base_url+'controller/package_tour/quotation/flight/quotation_update.php',
			data:{ quotation_id : quotation_id, enquiry_id : enquiry_id , customer_name : customer_name, email_id : email_id, mobile_no : mobile_no ,travel_datetime : travel_datetime, sector_from : sector_from, sector_to : sector_to,preffered_airline : preffered_airline,class_type : class_type, trip_type : trip_type, total_seats : total_seats, quotation_date : quotation_date, subtotal : subtotal,markup_cost:markup_cost,markup_cost_subtotal : markup_cost_subtotal , service_tax : service_tax , taxation_id : taxation_id, service_tax_subtotal : service_tax_subtotal, total_tour_cost : total_tour_cost, plane_from_location_arr : plane_from_location_arr, plane_to_location_arr : plane_to_location_arr,airline_name_arr : airline_name_arr , plane_class_arr : plane_class_arr, arraval_arr : arraval_arr, dapart_arr : dapart_arr,plane_id_arr :plane_id_arr, from_city_id_arr : from_city_id_arr, to_city_id_arr : to_city_id_arr},
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
					        	  $('#quotation_update_modal').modal('hide');
					        	 document.location.reload();
					        	 //quotataion_list_reflect();
						        }
						      }
						});
					}

                }  
		});

	}
});

        	 
</script>
