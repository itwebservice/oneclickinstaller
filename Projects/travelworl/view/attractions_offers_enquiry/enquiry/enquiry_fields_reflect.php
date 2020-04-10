<?php

include "../../../model/model.php";



$enquiry_type = $_POST['enquiry_type'];



if(isset($_POST['enquiry_id'])){

	$enquiry_id = $_POST['enquiry_id'];



	$sq_enquiry = mysql_fetch_assoc(mysql_query("select * from enquiry_master where enquiry_id='$enquiry_id'"));

	$enquiry_content = $sq_enquiry['enquiry_content'];

	$enquiry_content_arr1 = json_decode($enquiry_content, true);	

}
if($enquiry_type=="Group Booking" || $enquiry_type=="Package Booking"){

	if(isset($_POST['enquiry_id'])){

		foreach($enquiry_content_arr1 as $enquiry_content_arr2){

			if($enquiry_content_arr2['name']=="tour_name"){ $sq_c['tour_name'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_members"){ $sq_c['total_members'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_adult"){ $sq_c['total_adult'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_children"){ $sq_c['total_children'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_infant"){ $sq_c['total_infant'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="hotel_type"){ $sq_c['hotel_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="children_without_bed"){ $sq_c['children_without_bed'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="children_with_bed"){ $sq_c['children_with_bed'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="travel_from_date"){ $sq_c['travel_from_date'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="travel_to_date"){ $sq_c['travel_to_date'] = $enquiry_content_arr2['value']; }
		}
	}
	else{
		$sq_c['tour_name'] = $sq_c['budget'] = $sq_c['total_members'] = $sq_c['total_adult'] = $sq_c['total_children'] = $sq_c['total_infant'] = $sq_c['children_without_bed'] = $sq_c['children_with_bed'] = $sq_c['travel_from_date'] = $sq_c['travel_to_date']  = $sq_c['hotel_type'] = "";
	}

?>

<div class="row">

	<div class="col-md-4 col-sm-6 mg_bt_10">

	   <input type="text" id="tour_name" name="tour_name" onchange="validate_spaces(this.id)" placeholder="*Interested Tour" title="Interested Tour" value="<?= $sq_c['tour_name'] ?>" class="form-control">

	</div>

	<div class="col-md-4 col-sm-6 mg_bt_10">

		<input type="text" id="travel_from_date" name="travel_from_date"  title="Travel From Date" placeholder="*Travel From Date" value="<?= $sq_c['travel_from_date'] ?>" class="form-control" onchange="get_to_date(this.id,'travel_to_date')">

	</div>

	<div class="col-md-4 col-sm-6 mg_bt_10">
		<input type="text" id="travel_to_date" name="travel_to_date"  title="Travel To Date" placeholder="*Travel To Date" value="<?= $sq_c['travel_to_date'] ?>" onchange="validate_validDate('travel_from_date',this.id)" class="form-control">
	</div>	  

	<div class="col-md-4 col-sm-6 mg_bt_10">
	    <input type="text" id="budget" name="budget" placeholder="Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">
	</div>

	<div class="col-md-4 col-sm-6 mg_bt_10">

        <input type="text" id="total_adult" name="total_adult" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Adults" title="Total Adults" value="<?= $sq_c['total_adult'] ?>" class="form-control">            

    </div>

    <div class="col-md-4 col-sm-6 mg_bt_10">

        <input type="text" id="total_children" name="total_children" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Children" title="Total Children" value="<?= $sq_c['total_children'] ?>" class="form-control">            

    </div>      

	<div class="col-md-4 col-sm-6 mg_bt_10">

        <input type="text" id="total_infant" name="total_infant" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Infant" title="Total Infant" value="<?= $sq_c['total_infant'] ?>" class="form-control" >            

    </div>  

    <div class="col-md-4 col-sm-6 mg_bt_10">

	    <input type="text" id="total_members" name="total_members" onchange="number_validate(this.id)" placeholder="Total Passenger" title="Total Passenger" value="<?= $sq_c['total_members'] ?>" class="form-control" readonly>

	</div>
	<div class="col-md-4 col-sm-6 mg_bt_10">
			<select name="hotel_type" id="hotel_type" title="Hotel Type" class="form-control">
				<?php 
				if($sq_c['hotel_type']!=""){
					?>
					<option value="<?= $sq_c['hotel_type'] ?>"><?= $sq_c['hotel_type'] ?></option>
					<?php } ?>
				<option value="">Hotel Type</option>
				<option value="1-Star">1-Star</option>
				<option value="2-Star">2-Star</option>
				<option value="3-Star">3-Star</option>
				<option value="4-Star">4-Star</option>
				<option value="5-Star">5-Star</option>
				<option value="Economy">Economy</option>
				<option value="Resort">Resort</option>
				<option value="Other">Other</option>
			</select>
	</div>
	<div class="col-md-4 col-sm-6 mg_bt_10">
	    <input type="text" id="children_without_bed" name="children_without_bed" onchange="validate_balance(this.id)" placeholder="Children Without Bed" title="Children Without Bed" value="<?= $sq_c['children_without_bed'] ?>" class="form-control">
	</div>

	<div class="col-md-4 col-sm-6 mg_bt_10">

	    <input type="text" id="children_with_bed" name="children_with_bed" onchange="validate_balance(this.id)" placeholder="Children With Bed" title="Children With Bed" value="<?= $sq_c['children_with_bed'] ?>" class="form-control">

	</div> 

	

</div>

<script>

$('#travel_from_date, #travel_to_date').datetimepicker({ timepicker:false, format: 'd-m-Y' });

function total_members_calculate()

{

	var total_adult = $('#total_adult').val();	

	var total_children = $('#total_children').val();

	var total_infant = $('#total_infant').val();



	if(total_adult==""){ total_adult = 0; }

	if(total_children==""){ total_children = 0; }

	if(total_infant==""){ total_infant = 0; }



	var total_members = parseFloat(total_adult) + parseFloat(total_children) + parseFloat(total_infant);

	$('#total_members').val(total_members);

}

</script>

<?php

}





if($enquiry_type=="Visa")
{
	if(isset($_POST['enquiry_id']))
	{
		foreach($enquiry_content_arr1 as $enquiry_content_arr2)
		{
			if($enquiry_content_arr2['name']=="country_name"){ $sq_c['country_name']= $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="visa_type"){ $sq_c['visa_type'] = $enquiry_content_arr2['value'];	}
			if($enquiry_content_arr2['name']=="total_adult"){ $sq_c['total_adult'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="total_children"){ $sq_c['total_children'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="total_infant"){ $sq_c['total_infant'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="total_members"){	$sq_c['total_members'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }			 
		}
	}
	else
	{
		$sq_c['country_name'] = $sq_c['budget'] = $sq_c['visa_type'] = $sq_c['total_members'] = "";
	}
?>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<input type="text" id="country_name" name="country_name" placeholder="*Country Name" title="Country Name" value="<?= $sq_c['country_name'] ?>">

	</div>

	<div class="col-md-4">

		<select name="visa_type" id="visa_type" title="Visa Type">

			<?php if($sq_c['visa_type']!=""){?>

			 <option value="<?= $sq_c['visa_type'] ?>"><?= $sq_c['visa_type'] ?></option> <?php } ?>

            <option value="">Visa Type</option>

            <?php 

            $sq_visa_type = mysql_query("select * from visa_type_master");

            while($row_visa_type = mysql_fetch_assoc($sq_visa_type)){

                ?>

                <option value="<?= $row_visa_type['visa_type'] ?>"><?= $row_visa_type['visa_type'] ?></option>

                <?php

            	}
           ?>

        </select>

	</div>

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_adult" name="total_adult" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Adults" title="Total Adults" value="<?= $sq_c['total_adult'] ?>" class="form-control">            

    </div>    

</div>

<div class="row mg_bt_10">

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_children" name="total_children" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Children" title="Total Children" value="<?= $sq_c['total_children'] ?>" class="form-control">            

    </div>  

    <div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_infant" name="total_infant" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Infant" title="Total Infant" value="<?= $sq_c['total_infant'] ?>" class="form-control">            

    </div>    

	<div class="col-md-4">

		<input type="text" id="total_members" name="total_members" placeholder="Total Passenger" title="Total Passenger" value="<?= $sq_c['total_members'] ?>" class="form-control" readonly>

	</div>
	<div class="col-md-4 col-sm-6 mg_tp_10">

	    <input type="text" id="budget" name="budget" onchange="validate_balance(this.id)" placeholder="*Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">

	</div>

</div>

<script>

$('#travel_from_date, #travel_to_date').datetimepicker({ timepicker:false, format: 'd-m-Y' });

function total_members_calculate()

{

	var total_adult = $('#total_adult').val();	

	var total_children = $('#total_children').val();

	var total_infant = $('#total_infant').val();



	if(total_adult==""){ total_adult = 0; }

	if(total_children==""){ total_children = 0; }

	if(total_infant==""){ total_infant = 0; }



	var total_members = parseFloat(total_adult) + parseFloat(total_children) + parseFloat(total_infant);

	$('#total_members').val(total_members);

}

</script>

<?php

}



if($enquiry_type=="Flight Ticket"){



	if(isset($_POST['enquiry_id'])){

		foreach($enquiry_content_arr1 as $enquiry_content_arr2){

			if($enquiry_content_arr2['name']=="travel_datetime"){ $sq_c['travel_datetime'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="sector_from"){ $sq_c['sector_from'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="sector_to"){ $sq_c['sector_to'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="preffered_airline"){ $sq_c['preffered_airline'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="class_type"){ $sq_c['class_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="trip_type"){ $sq_c['trip_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_seats"){ $sq_c['total_seats'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }

		}

	}

	else{

		$sq_c['travel_datetime'] = $sq_c['budget'] = $sq_c['sector_from'] = $sq_c['sector_to'] = $sq_c['preffered_airline'] = $sq_c['class_type'] = $sq_c['trip_type'] = $sq_c['total_adult'] = $sq_c['total_children'] = $sq_c['total_infant'] = $sq_c['total_seats'] = "";		

	}



?>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<input type="text" id="travel_datetime" name="travel_datetime" placeholder="*Travel Date/Time" title="Travel Date/Time" value="<?= get_datetime_user($sq_c['travel_datetime']) ?>" class="form-control">

	</div>

	<div class="col-md-4">

		<select id="sector_from" name="sector_from" class="app_select2" style="width:100%" title="Sector From" class="form-control">

			<?php if($sq_c['sector_from']==""){

				?>

			<option value="">*Sector From</option>

			<?php get_airport_name_dropdown();   

				} 

			?>

			<option  value="<?= $sq_c['sector_from'] ?>"><?= $sq_c['sector_from'] ?></option>

			<?php get_airport_name_dropdown(); ?>

            

        </select>

	</div>

	<div class="col-md-4">

		<select id="sector_to" name="sector_to" class="app_select2" style="width:100%" title="Sector To" class="form-control">

			<?php if($sq_c['sector_to']==""){

			?>

			<option value="">*Sector To</option>

			<?php get_airport_name_dropdown(); }

			?>

			<option value="<?= $sq_c['sector_to']?>"><?= $sq_c['sector_to']?></option>

            <?php get_airport_name_dropdown(); ?>

        </select>

	</div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4">
		<select id="preffered_airline" name="preffered_airline" class="app_select2" style="width:100%" title="Preferred Airline" class="form-control">

			<?php if($sq_c['sector_to']==""){ ?>
			<option value="">*Airline</option>
			<?php get_enqairline_dropdown(); } ?>

			<option value="<?= $sq_c['preffered_airline']?>"><?= $sq_c['preffered_airline']?></option>
			<option value="">*Airline</option>
			<?php get_enqairline_dropdown(); ?>
		</select>

	</div>

	<div class="col-md-4">

		<select name="class_type" id="class_type" title="Class Type" class="form-control">

			<?php 

			if($sq_c['class_type']!=""){

				?>

				<option value="<?= $sq_c['class_type'] ?>"><?= $sq_c['class_type'] ?></option>

				<?php

			}

			?>
			<option value="">*Class Type</option>
			<option value="Economy">Economy</option>

			<option value="Bussiness">Business</option>

			<option value="First">First</option>

			<option value="Other">Other</option>

		</select>

	</div>

	<div class="col-md-4">

		<select name="trip_type" id="trip_type" class="form-control" title="Trip Type">

			<?php 

			if($sq_c['trip_type']!=""){

				?>

				<option value="<?= $sq_c['trip_type'] ?>"><?= $sq_c['trip_type'] ?></option>

				<?php

			}

			?>

			<option value="">*Trip Type</option>

			<option value="Single">Single</option>

			<option value="Round">Round</option>

		</select>

	</div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<input type="text" id="total_seats" name="total_seats" onchange="validate_balance(this.id)" placeholder="*Total Seats" title="Total Seats" value="<?= $sq_c['total_seats'] ?>" class="form-control" >

	</div>
	<div class="col-md-4 col-sm-6 mg_bt_10">

	    <input type="text" id="budget" name="budget" onchange="validate_balance(this.id)" placeholder="Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">

	</div>

</div>

<script>

$('#travel_datetime').datetimepicker({ format:'d-m-Y H:i:s' });

$('#sector_to,#sector_from,#preffered_airline').select2();

</script>

<?php

}



if($enquiry_type=="Train Ticket"){

	if(isset($_POST['enquiry_id'])){

		foreach($enquiry_content_arr1 as $enquiry_content_arr2){

			if($enquiry_content_arr2['name']=="travel_datetime"){ $sq_c['travel_datetime'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="location_from"){ $sq_c['location_from'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="location_to"){ $sq_c['location_to'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="class_type"){ $sq_c['class_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="trip_type"){ $sq_c['trip_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_adult"){ $sq_c['total_adult'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_children"){ $sq_c['total_children'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_infant"){ $sq_c['total_infant'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_seats"){ $sq_c['total_seats'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="travel_type"){ $sq_c['travel_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }
		}

	}

	else{

		$sq_c['travel_datetime'] = $sq_c['budget'] = $sq_c['location_from'] =  $sq_c['location_to'] =  $sq_c['class_type'] =  $sq_c['trip_type'] = $sq_c['total_adult'] = $sq_c['total_children'] = $sq_c['total_infant'] =  $sq_c['total_seats'] = $sq_c['travel_type'] = "";	

	}

?>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<input type="text" id="travel_datetime" name="travel_datetime"  placeholder="*Travel Date/Time" title="Travel Date/Time" value="<?= get_datetime_user($sq_c['travel_datetime']) ?>" class="form-control">

	</div>

	<div class="col-md-4">

		<input type="text" id="location_from" name="location_from" onchange="validate_specialChar(this.id);" placeholder="*Location From" title="Location From" value="<?= $sq_c['location_from'] ?>" class="form-control">

	</div>

	<div class="col-md-4">

		<input type="text" id="location_to" name="location_to" onchange="validate_specialChar(this.id);" placeholder="*Location To" title="Location To" value="<?= $sq_c['location_to'] ?>" class="form-control">

	</div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<select name="class_type" id="class_type" title="Class Type" class="form-control">

			<?php 

			if($sq_c['class_type']!=""){

				?>

				<option value="<?= $sq_c['class_type'] ?>"><?= $sq_c['class_type'] ?></option>

				<?php

			}

			?>
			<option value="">Class Type</option>

			<option value="SC">SC</option>

			<option value="SL">SL</option>

			<option value="3A">3A</option>

			<option value="2A">2A</option>

			<option value="1A">1A</option>

		</select>

	</div>

	<div class="col-md-4">

		<select name="trip_type" id="trip_type" placeholder="*Trip type" class="form-control">

			<?php 

			if($sq_c['trip_type']!=""){

				?>

				<option value="<?= $sq_c['trip_type'] ?>"><?= $sq_c['trip_type'] ?></option>

				<?php

			}

			?>

			<option value="">*Trip Type</option>

			<option value="Single">Single</option>

			<option value="Round">Round</option>

		</select>

	</div>	

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_adult" name="total_adult" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Adults" title="Total Adults" value="<?= $sq_c['total_adult'] ?>" class="form-control">            

    </div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_children" name="total_children" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Children" title="Total Children" value="<?= $sq_c['total_children'] ?>" class="form-control">            

    </div>  

    <div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_infant" name="total_infant" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Infant" title="Total Infant" value="<?= $sq_c['total_infant'] ?>" class="form-control">            

    </div>    

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

		<input type="text" id="total_seats" name="total_seats" onchange="validate_balance(this.id)" placeholder="*Total Seats" title="Total Seats" value="<?= $sq_c['total_seats'] ?>" class="form-control">

	</div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs " class="form-control">

		<select name="travel_type" id="travel_type">

			<?php 

			if($sq_c['travel_type']!=""){

				?>

				<option value="<?= $sq_c['travel_type'] ?>"><?= $sq_c['travel_type'] ?></option>

				<?php

			}

			?>

			<option value="General">General</option>

			<option value="Tatkal">Tatkal</option>

		</select>

	</div>
	<div class="col-md-4 col-sm-6 mg_bt_10">

	    <input type="text" id="budget" name="budget" onchange="validate_balance(this.id)" placeholder="*Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">

	</div>

</div>

<script>

$('#travel_datetime').datetimepicker({ format:'d-m-Y H:i:s' });



function total_members_calculate()

{

	var total_adult = $('#total_adult').val();	

	var total_children = $('#total_children').val();

	var total_infant = $('#total_infant').val();



	if(total_adult==""){ total_adult = 0; }

	if(total_children==""){ total_children = 0; }

	if(total_infant==""){ total_infant = 0; }



	var total_seats = parseFloat(total_adult) + parseFloat(total_children) + parseFloat(total_infant);

	$('#total_seats').val(total_seats);

}

</script>

<?php

}



if($enquiry_type=="Hotel"){



	if(isset($_POST['enquiry_id'])){

		foreach($enquiry_content_arr1 as $enquiry_content_arr2){

			if($enquiry_content_arr2['name']=="city_id"){ $sq_c['city_id'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="check_in_date"){ $sq_c['check_in_date'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="check_out_date"){ $sq_c['check_out_date'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="no_of_nights"){ $sq_c['no_of_nights'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="no_of_rooms"){ $sq_c['no_of_rooms'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="hotel_type"){ $sq_c['hotel_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="room_type"){ $sq_c['room_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="accomodation_type"){ $sq_c['accomodation_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="meal_plan"){ $sq_c['meal_plan'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_adult"){ $sq_c['total_adult'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_children"){ $sq_c['total_children'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_infant"){ $sq_c['total_infant'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_members"){ $sq_c['total_members'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }

		}

	}

	else{

		$sq_c['city_id'] = $sq_c['budget'] = $sq_c['check_in_date'] =  $sq_c['check_out_date'] =  $sq_c['no_of_nights'] =  $sq_c['no_of_rooms'] =  $sq_c['hotel_type'] =  $sq_c['room_type'] =  $sq_c['accomodation_type'] =  $sq_c['meal_plan'] = $sq_c['total_adult'] = $sq_c['total_children'] = $sq_c['total_infant'] = $sq_c['total_members'] =  "";		

	}



?>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<select name="city_id" id="city_id" title="City Name" style="width:100%">

			<?php 

			if($sq_c['city_id']!=""){

				$sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$sq_c[city_id]'"));

				?>

				<option value="<?= $sq_city['city_id'] ?>"><?= $sq_city['city_name'] ?></option>

				<?php

			}

			?>

			<?php get_cities_dropdown(); ?>

		</select>

	</div>

	<div class="col-md-4">

		<input type="text" id="check_in_date" name="check_in_date" placeholder="*Check-In Date" onchange="get_to_datetime(this.id,'check_out_date')" title="Check-In Date" value="<?= ($sq_c['check_in_date']!="") ? $sq_c['check_in_date'] : date('d-m-Y H:i') ?>" class="form-control">

	</div>

	<div class="col-md-4">

		<input type="text" id="check_out_date" name="check_out_date" placeholder="*Check-Out Date" title="Check-Out Date" value="<?= ($sq_c['check_out_date']!="") ? $sq_c['check_out_date'] : date('d-m-Y H:i') ?>" class="form-control">

	</div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<input type="text" id="no_of_nights" name="no_of_nights" placeholder="*No Of Nights" title="No Of Nights" value="<?= $sq_c['no_of_nights'] ?>" class="form-control" onchange="validate_balance(this.id)">

	</div>

	<div class="col-md-4">

		<input type="text" id="no_of_rooms" name="no_of_rooms" placeholder="*Total Rooms" title="Total Rooms" value="<?= $sq_c['no_of_rooms'] ?>" onchange="validate_balance(this.id)">

	</div>

	<div class="col-md-4">

		<select name="hotel_type" id="hotel_type" title="Hotel Type" class="form-control">

			<?php 

			if($sq_c['hotel_type']!=""){

				?>

				<option value="<?= $sq_c['hotel_type'] ?>"><?= $sq_c['hotel_type'] ?></option>

				<?php

			}

			?>

			<option value="">Hotel Type</option>

			<option value="1-Star">1-Star</option>

			<option value="2-Star">2-Star</option>

			<option value="3-Star">3-Star</option>

			<option value="4-Star">4-Star</option>

			<option value="5-Star">5-Star</option>
			<option value="Economy">Economy</option>

			<option value="Resort">Resort</option>

			<option value="Other">Other</option>

		</select>

	</div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<select name="room_type" id="room_type" title="Room Type" class="form-control">

			<?php 

			if($sq_c['room_type']!=""){

				?>

				<option value="<?= $sq_c['room_type'] ?>"><?= $sq_c['room_type'] ?></option>

				<?php

			}

			?>
			<option value="">Room Type</option>
			<option value="AC">AC</option>

			<option value="Non AC">Non AC</option>

		</select>

	</div>

	<div class="col-md-4">

		<select name="accomodation_type" id="accommodation_type" title="Accommodation Type" class="form-control">

			<?php 

			if($sq_c['accomodation_type']!=""){

				?>

				<option value="<?= $sq_c['accomodation_type'] ?>"><?= $sq_c['accomodation_type'] ?></option>

				<?php

			}

			?>			

			<option value="">Accommodation Type</option>

			<option value="Twin Sharing">Twin Sharing</option>

			<option value="Single Adult">Single Adult</option>

			<option value="Triple Sharing">Triple Sharing</option>

			<option value="Quadruple Sharing">Quadruple Sharing</option>

		</select>

	</div>

	<div class="col-md-4">

		<select name="meal_plan" id="meal_plan" title="Meal Plan" class="form-control">

			<?php 

			if($sq_c['meal_plan']!=""){

				?>

				<option value="<?= $sq_c['meal_plan'] ?>"><?= $sq_c['meal_plan'] ?></option>

				<?php

			}

			?>
			<option value="">Meal Plan</option>
			<option value="AP">AP</option>

			<option value="CP">CP</option>

			<option value="EP">EP</option>

			<option value="MAP">MAP</option>

		</select>

	</div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_adult" name="total_adult" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Adults" title="Total Adults" value="<?= $sq_c['total_adult'] ?>" class="form-control">            

    </div>

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_children" name="total_children" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Children" title="Total Children" value="<?= $sq_c['total_children'] ?>" class="form-control">            

    </div>  

    <div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_infant" name="total_infant" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Infant" title="Total Infant" value="<?= $sq_c['total_infant'] ?>" class="form-control">            

    </div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<input type="text" id="total_members" name="total_members" placeholder="Total Passenger" title="Total Passenger" value="<?= $sq_c['total_members'] ?>" class="form-control" readonly>

	</div>
	<div class="col-md-4 col-sm-6 mg_bt_10">

	    <input type="text" id="budget" name="budget" onchange="validate_balance(this.id)" placeholder="*Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">

	</div>

</div>

<script>

$('#check_in_date, #check_out_date').datetimepicker({ timepicker:true, format:'d-m-Y H:i:s' });

$('#city_id').select2();

function total_members_calculate()

{

	var total_adult = $('#total_adult').val();	

	var total_children = $('#total_children').val();

	var total_infant = $('#total_infant').val();



	if(total_adult==""){ total_adult = 0; }

	if(total_children==""){ total_children = 0; }

	if(total_infant==""){ total_infant = 0; }



	var total_members = parseFloat(total_adult) + parseFloat(total_children) + parseFloat(total_infant);

	$('#total_members').val(total_members);

}

</script>

<?php

}



if($enquiry_type=="Car Rental"){



	if(isset($_POST['enquiry_id'])){

		foreach($enquiry_content_arr1 as $enquiry_content_arr2){

			if($enquiry_content_arr2['name']=="total_pax"){ $sq_c['total_pax'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="days_of_traveling"){ $sq_c['days_of_traveling'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="traveling_date"){ $sq_c['traveling_date'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="vehicle_type"){ $sq_c['vehicle_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="travel_type"){ $sq_c['travel_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="places_to_visit"){ $sq_c['places_to_visit'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }

		}

	}

	else{

		$sq_c['total_pax'] = $sq_c['budget'] = $sq_c['days_of_traveling'] = $sq_c['vehicle_type'] = $sq_c['travel_type'] = $sq_c['places_to_visit'] = "";

		$sq_c['traveling_date'] = date('d-m-Y');

	}



?>

<div class="row mg_bt_10">

	<div class="col-md-4">

	    <input type="text" id="total_pax" name="total_pax" placeholder="*No Of Pax" title="No Of Pax" value="<?= $sq_c['total_pax'] ?>" class="form-control" onchange="validate_balance(this.id)">

	</div>  

	<div class="col-md-4">

	    <input type="text" id="days_of_traveling" name="days_of_traveling" placeholder="*Days Of Travelling" title="Days Of Travelling" value="<?= $sq_c['days_of_traveling'] ?>"  class="form-control" onchange="validate_balance(this.id)">

	</div> 

	<div class="col-md-4">

    	<input type="text" id="traveling_date" name="traveling_date" placeholder="*Travelling Date" title="Travelling Date" value="<?= get_date_user($sq_c['traveling_date']) ?>" class="form-control">

  	</div>       

</div>

<div class="row mg_bt_10">

	<div class="col-md-4">

	    <select name="vehicle_type" id="vehicle_type" title="Vehicle Type" class="form-control">

	      <?php 

	      if($sq_c['vehicle_type']!=""){

	      	?>

			<option value="<?= $sq_c['vehicle_type'] ?>"><?= $sq_c['vehicle_type'] ?></option>

	      	<?php

	      }

	      ?>

	      <option value="">*Vehicle Type</option>

	      <option value="AC">AC</option>

	      <option value="Non AC">Non AC</option>

	    </select>

	</div>

	<div class="col-md-4">

	    <select name="travel_type" id="travel_type" title="Travel Type" class="form-control">

	      <?php 

	      if($sq_c['travel_type']!=""){

	      	?>

			<option value="<?= $sq_c['travel_type'] ?>"><?= $sq_c['travel_type'] ?></option>

	      	<?php

	      }

	      ?>

	      <option value="">*Travel Type</option>

	      <option value="Local">Local</option>

	      <option value="Outstation">Outstation</option>

	    </select>

	</div>
	<div class="col-md-4 col-sm-6 mg_bt_10">

	    <input type="text" id="budget" name="budget" onchange="validate_balance(this.id)" placeholder="*Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">

	</div>

	<div class="col-md-4">

	    <textarea name="places_to_visit" onchange="validate_spaces(this.id)" id="places_to_visit" placeholder="*Places To Visit" title="Places To Visit"><?= $sq_c['places_to_visit'] ?></textarea>

	</div>


</div>

<script>

$('#traveling_date').datetimepicker({ timepicker:false, format:'d-m-Y' });

</script>

<?php	

}



if($enquiry_type=="Bus"){



	if(isset($_POST['enquiry_id'])){

		foreach($enquiry_content_arr1 as $enquiry_content_arr2){

			if($enquiry_content_arr2['name']=="travel_datetime"){ $sq_c['travel_datetime'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="location_from"){ $sq_c['location_from'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="location_to"){ $sq_c['location_to'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="seat_type"){ $sq_c['seat_type'] = $enquiry_content_arr2['value']; } 

			if($enquiry_content_arr2['name']=="total_seats"){ $sq_c['total_seats'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="bus_name_and_type"){ $sq_c['bus_name_and_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }

		}

	}

	else{

		$sq_c['travel_datetime'] = $sq_c['budget'] = $sq_c['location_from'] = $sq_c['location_to'] = $sq_c['seat_type'] = $sq_c['total_seats'] = $sq_c['bus_name_and_type'] = "";	

	}



?>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<input type="text" id="travel_datetime" name="travel_datetime"  placeholder="*Travel Date/Time" title="Travel Date/Time" value="<?= get_datetime_user($sq_c['travel_datetime']) ?>" class="form-control">

	</div>

	<div class="col-md-4">

		<input type="text" id="location_from" name="location_from" onchange="validate_specialChar(this.id)" placeholder="*Location From" title="Location From" value="<?= $sq_c['location_from'] ?>" class="form-control">

	</div>

	<div class="col-md-4">

		<input type="text" id="location_to" name="location_to" placeholder="*Location To" onchange="validate_specialChar(this.id)" title="Location To" value="<?= $sq_c['location_to'] ?>" class="form-control">

	</div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4">		

		<select name="seat_type" id="seat_type" title="Seat Type" placeholder="*Seat Type"  class="form-control">

			<?php 

			if($sq_c['seat_type']!=""){

				?>

				<option value="<?= $sq_c['seat_type'] ?>"><?= $sq_c['seat_type'] ?></option>

				<?php

			}

			?>

			<option value="Seating">Seating</option>

			<option value="Semi sleeper">Semi sleeper</option>

			<option value="Sleeper">Sleeper</option>

			<option value="Window">Window</option>

		</select>

	</div>	

	<div class="col-md-4">

		<input type="text" id="total_seats" name="total_seats" onchange="validate_balance(this.id)" placeholder="*Total Seats" title="Total Seats" value="<?= $sq_c['total_seats'] ?>" class="form-control">

	</div>

	<div class="col-md-4">

		<input type="text" id="bus_name_and_type" name="bus_name_and_type" onchange="validate_specialChar(this.id)" placeholder="*Bus Name & Type" title="Bus Name & Type" value="<?= $sq_c['bus_name_and_type'] ?>" class="form-control">

	</div>

	<div class="col-md-4 col-sm-6 mg_tp_10">

	    <input type="text" id="budget" name="budget" onchange="number_validate(this.id)" placeholder="*Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">

	</div>

</div>

<script>

$('#travel_datetime').datetimepicker({ format:'d-m-Y H:i:s' });

</script>

<?php

}

if($enquiry_type=="Passport"){



	if(isset($_POST['enquiry_id'])){

		foreach($enquiry_content_arr1 as $enquiry_content_arr2){
			 
			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }

		}

	}

	else{

		  $sq_c['budget'] = "";	

	}



?>
 

<div class="row mg_bt_10">

	<div class="col-md-4 col-sm-6 mg_tp_10">

	    <input type="text" id="budget" name="budget" onchange="validate_balance(this.id)" placeholder="*Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">

	</div>

</div>
<?php 
}
?>

<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>