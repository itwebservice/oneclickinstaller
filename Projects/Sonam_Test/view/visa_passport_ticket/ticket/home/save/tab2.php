<form id="frm_tab2">
	<div class="row">
		<div class="col-md-8 col-sm-12 col-xs-12 mg_bt_20_xs">
			<strong>*Type Of Trip :</strong>&nbsp;&nbsp;&nbsp;
			<input type="radio" name="type_of_tour" id="type_of_tour-one_way" value="One Way">&nbsp;&nbsp;<label for="type_of_tour-one_way">One Way</label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" name="type_of_tour" id="type_of_tour-round_trip" value="Round Trip">&nbsp;&nbsp;<label for="type_of_tour-round_trip">Round Trip</label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" name="type_of_tour" id="type_of_tour-multi_city" value="Multi City">&nbsp;&nbsp;<label for="type_of_tour-multi_city">Multi City</label>
			&nbsp;&nbsp;&nbsp;
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12 text-right">
			<button type="button" class="btn btn-info btn-sm ico_left" onclick="addDyn('div_dynamic_ticket_info'); event_airport();copy_values()"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Section</button>
		</div>
	</div>
	<div class="dynform-wrap" id="div_dynamic_ticket_info" data-counter="1">
		<div class="dynform-item">		
			<div class="row">
				<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
					<input type="text" id="departure_datetime-1" name="departure_datetime" class="app_datetimepicker" placeholder="Departure Date-Time" title="Departure Date-Time" value="<?php echo date('d-m-Y H:i:s') ?>" onchange="get_arrival_dateid(this.id);" data-dyn-valid="required">
				</div>
				<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
					<input type="text" id="arrival_datetime-1" name="arrival_datetime" class="app_datetimepicker" placeholder="Arrival Date-Time" title="Arrival Date-Time" value="<?php echo date('d-m-Y H:i:s') ?>" data-dyn-valid="required">
				</div>
				<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
					<select id="airlines_name-1" name="airlines_name" title="Airlines Name" style="width:100%" data-dyn-valid="required" class="app_select">
						<option value="">Airline Name</option>
					<?php $sq_airline = mysql_query("select airline_name from airline_master where active_flag!='Inactive' order by airline_name asc");
						while($row_airline = mysql_fetch_assoc($sq_airline)){
					    ?>
					    <option value="<?= $row_airline['airline_name'] ?>"><?= $row_airline['airline_name'] ?></option>
					    <?php

					  }

					?>
				</select>
				</div>
				<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
					<select name="class" id="class-1" title="Class" data-dyn-valid="required">
						<option value="">Class</option>
						<option value="Economy">Economy</option>
						<option value="Business">Business</option>
						<option value="Premium Economy">Premium Economy</option>
						<option value="First class">First class</option>
					</select>
				</div>
				<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
					<input type="text" id="flight_no-1" style="text-transform: uppercase;" name="flight_no" onchange="validate_specialChar(this.id)" placeholder="Flight No" title="Flight No" data-dyn-valid="">
				</div>
				<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
					<input type="text" id="airlin_pnr-1" style="text-transform: uppercase;" onchange=" validate_specialChar(this.id)" name="airlin_pnr" placeholder="Airline PNR" title="Airline PNR" data-dyn-valid="">
				</div>
			</div>
		<div class="row">
				<div class="col-md-4 col-sm-4 col-xs-12 mg_bt_10_xs">
					<input id="airpf-1" name="airpf" class="form-control autocomplete" placeholder="Enter Departure Airport" data-dyn-valid="required">
					<input type="hidden" name="from_city" id="from_city-1" data-dyn-valid="required"/>
					<input type="hidden" name="departure_city" id="departure_city-1" data-dyn-valid="required">
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12 mg_bt_10_xs">
					<input id="airpt-1" name="airpt" class="form-control autocomplete" placeholder="Enter Arrival Airport" data-dyn-valid="required">
					<input type="hidden" name="to_city" id="to_city-1" data-dyn-valid="required"/>
					<input type="hidden" name="arrival_city" id="arrival_city-1" data-dyn-valid="required">
				</div>
				<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
					<input type="text" id="meal_plan-1" name="meal_plan" onchange="validate_specialChar(this.id)" placeholder="Meal Plan" title="Meal Plan" data-dyn-valid="">
				</div>
				<div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
					<input type="text" id="luggage-1" name="luggage" onchange="validate_specialChar(this.id)" placeholder="Luggage" title="Luggage" data-dyn-valid="">
				</div>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<textarea name="special_note" id="special_note-1" onchange="validate_address(this.id)" rows="1" placeholder="Special Note" title="Special Note" data-dyn-valid=""></textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="row text-center mg_tp_20">

		<div class="col-xs-12">

			<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab1()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>

			&nbsp;&nbsp;

			<button class="btn btn-info btn-sm ico_right">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>

		</div>

	</div>



</form>



<script>

$('#departure_datetime-1, #arrival_datetime-1').datetimepicker({ format:'d-m-Y H:i:s' });

$('#airlines_name-1,#plane_from_location-1,#plane_to_location-1').select2();

$('#frm_tab2').validate({

	rules:{

			//type_of_tour : { required : true },

	},

	submitHandler:function(form){
		var type_of_tour = $('input[name="type_of_tour"]:checked').val();
		//var status = dyn_validate('div_dynamic_ticket_info');
		//if(!status){ return false; }
		var msg = "Type of trip is required"; 

		if(type_of_tour == undefined) { error_msg_alert(msg); return false;}

		$('a[href="#tab3"]').tab('show');
	}

});

function switch_to_tab1(){ $('a[href="#tab1"]').tab('show'); }
function getAirports() {
	var a = $.ajax({
			method:'post',
			url : 'home/airport_list.php',
			async : false,
		});
	return JSON.parse(a.responseText);
}
var a_list = getAirports();
function event_airport(count = 2){
	if(count == 1)	{id1 = "airpf-1"; id2 = "airpt-1"}
	else	{id1 = "airpf-"+$('#div_dynamic_ticket_info').attr('data-counter');id2 = "airpt-"+$('#div_dynamic_ticket_info').attr('data-counter');}
$("#"+id1).autocomplete({
    source: a_list,
    select: function (event, ui) {
	  var substr_id =  id1.substr(6);
	  $('#from_city-'+substr_id).val(ui.item.city_id);
	  $('#departure_city-'+substr_id).val(ui.item.value.split(" - ")[1]);
	},
	open: function(event, ui) {
		$(this).autocomplete("widget").css({
            "width": document.getElementById(id1).offsetWidth
        });
    },
	change: function(event,ui){
		var substr_id =  id1.substr(6);
		if(!ui.item) {
			 $(this).val('');
			 $('#from_city-'+substr_id).val("");
			 $('#departure_city-'+substr_id).val("");
			 error_msg_alert('Please select Airport from the list!!');
			 $(this).css('border','1px solid red;');
			 }
	}
}).data("ui-autocomplete")._renderItem = function(ul, item) {
      return $("<li disabled>")
        .append("<a>" + item.value.split(" -")[1] + "<br><b>" + item.value.split(" -")[0] + "<b></a>")
        .appendTo(ul);
	
};
$("#"+id2).autocomplete({
    source: a_list,
    select: function (event, ui) {
	  var substr_id =  id2.substr(6);
	  $('#to_city-'+substr_id).val(ui.item.city_id);
	  $('#arrival_city-'+substr_id).val(ui.item.value.split(" - ")[1]);
	},
	open: function(event, ui) {
		$(this).autocomplete("widget").css({
            "width": document.getElementById(id2).offsetWidth
        });
    },
	change: function(event,ui){
		var substr_id =  id2.substr(6);
		if(!ui.item) {
			 $(this).val('');
			 $('#to_city-'+substr_id).val("");
			 $('#arrival_city-'+substr_id).val("");
			 error_msg_alert('Please select Airport from the list!!');
			 $(this).css('border','1px solid red;');
			 }
	}
}).data("ui-autocomplete")._renderItem = function(ul, item) {
      return $("<li disabled>")
        .append("<a>" + item.value.split(" -")[1] + "<br><b>" + item.value.split(" -")[0] + "<b></a>")
        .appendTo(ul);
	
    };
}
event_airport(1);
function copy_values(){
	var count = $('#div_dynamic_ticket_info').attr('data-counter');
	$('#meal_plan-'+count).val($('#meal_plan-1').val());
	$('#luggage-'+count).val($('#luggage-1').val());
	$('#airpf-'+count).val($('#airpt-1').val());
	$('#from_city-'+count).val($('#to_city-1').val());
	$('#departure_city-'+count).val($('#arrival_city-1').val());
	$('#airpt-'+count).val($('#airpf-1').val());
	$('#to_city-'+count).val($('#from_city-1').val());
	$('#arrival_city-'+count).val($('#departure_city-1').val());
}
</script>