<form id="frm_tab11_c">

	<div class="row">

		<input type="hidden" id="quotation_id1" name="quotation_id1" value="<?= $quotation_id ?>">


		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<input type="hidden" id="login_id" name="login_id" value="<?= $login_id ?>">

			<select name="enquiry_id1" title="Enquiry No" id="enquiry_id1" style="width:100%" onchange="get_enquiry_details('1')">

				<?php 

				$sq_enq = mysql_fetch_assoc(mysql_query("select * from enquiry_master where enquiry_id='$sq_quotation[enquiry_id]' and enquiry_type='Car Rental'"));

					?>

					<option value="<?= $sq_enq['enquiry_id'] ?>">Enq<?= $sq_enq['enquiry_id'] ?> : <?= $sq_enq['name'] ?></option>

					<?php

				if($role=='Admin'){
				    $sq_enq = mysql_query("select * from enquiry_master where enquiry_type in('Car Rental') and status!='Disabled' order by enquiry_id desc");
				}	
				if($branch_status=='yes'){
					if($role=='Branch Admin'){
						$sq_enq = mysql_query("select * from enquiry_master where enquiry_type in('Car Rental') and status!='Disabled' and branch_admin_id='$branch_admin_id' order by enquiry_id desc");
					}
					elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){

						$sq_enq = mysql_query("select * from enquiry_master where enquiry_type in('Car Rental') and assigned_emp_id='$emp_id' and status!='Disabled' order by enquiry_id desc");
					}
					else{
						$sq_enq = mysql_query("select * from enquiry_master where enquiry_type in('Car Rental') and status!='Disabled' and branch_admin_id='$branch_admin_id' order by enquiry_id desc");
					}
					
				}
				else{
					if($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
						$q = "select * from enquiry_master where enquiry_type in('Car Rental') and assigned_emp_id='$emp_id' and status!='Disabled' order by enquiry_id desc";
						$sq_enq = mysql_query($q);
					}
					else{
						 $sq_enq = mysql_query("select * from enquiry_master where enquiry_type in('Car Rental') and status!='Disabled' order by enquiry_id desc");
					}
				}
				 

				while($row_enq = mysql_fetch_assoc($sq_enq)){

					?>

					<option value="<?= $row_enq['enquiry_id'] ?>">Enq<?= $row_enq['enquiry_id'] ?> : <?= $row_enq['name'] ?></option>

				<?php

				}

				?>

			</select>

		</div>	

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" id="customer_name1" name="customer_name1" onchange="fname_validate(this.id)" placeholder="Customer Name" title="Customer Name" value="<?php echo $sq_quotation['customer_name'];?>" >

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<input type="text" id="email_id1" name="email_id1" placeholder="Email ID" title="Email ID" onchange="validate_email(this.id)" value="<?= $sq_quotation['email_id'] ?>">

		</div>	

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<input type="text" id="mobile_no1" name="mobile_no1" onchange="mobile_validate(this.id)" placeholder="Mobile No" title="Mobile No" value="<?= $sq_quotation['mobile_no'] ?>">

		</div>

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" id="total_pax1" name="total_pax1" placeholder="No Of Pax" onchange="validate_balance(this.id)" title="No Of Pax" value="<?php echo $sq_quotation['total_pax'];?>" >

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	     <input type="text" id="days_of_traveling1" name="days_of_traveling1" onchange="validate_balance(this.id)" placeholder="Days Of Travelling" value="<?php echo $sq_quotation['days_of_traveling'];?>" title="Days Of Travelling">

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" id="traveling_date1" name="traveling_date1" placeholder="Travelling Date" title="Travelling Date" value="<?= get_date_user($sq_quotation['traveling_date']) ?>">

	    </div>	        		            

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<input type="text" id="vehicle_type1" name="vehicle_type1" placeholder="Vehicle Type" title="Vehicle Type" value="<?= $sq_quotation['vehicle_type'] ?>">

		</div>	

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" id="travel_type1" name="travel_type1" placeholder="Travel Type" onchange="fname_validate(this.id)" title="Travel Type" value="<?= $sq_quotation['travel_type'] ?>">

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
 			<textarea name="places_to_visit1" id="places_to_visit1" rows="1" onchange="validate_specialChar(this.id)" placeholder="Places To Visit" title="Places To Visit"><?= $sq_quotation['places_to_visit'] ?></textarea>

	    </div>


	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" id="vehicle_name" name="vehicle_name" placeholder="Vehicle Name" title="Vehicle Name" onchange="validate_specialChar(this.id);" value="<?= $sq_quotation['vehicle_name'] ?>" >

	    </div>	        		           

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<input type="text" id="from_date" name="from_date" placeholder="Travel D/T From" title="Travel D/T From" value="<?= get_datetime_user($sq_quotation['from_date'])?>">

		</div>	

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" id="to_date" name="to_date" placeholder="To Date" title="To Date" value="<?= get_datetime_user($sq_quotation['to_date'])?>">

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	     <input type="text" class="form-control" id="trip_type" name="trip_type" onchange="fname_validate(this.id)" placeholder="Trip Type" title="Trip Type" value="<?= $sq_quotation['trip_type'] ?>"> 

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" class="form-control" id="route" name="route" placeholder="Route" onchange="validate_spaces(this.id)" title="Route" value="<?= $sq_quotation['route'] ?>"> 

	    </div>	        		                			        		        	        		


		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<input type="text" id="extra_km_cost" name="extra_km_cost" placeholder="Extra KM Cost" title="Extra KM Cost" value="<?= $sq_quotation['extra_km_cost'] ?>" onchange="validate_balance(this.id)">

		</div>	

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" class="form-control" id="extra_hr_cost" name="extra_hr_cost" placeholder="Extra Hr Cost" title="Extra Hr Cost" value="<?= $sq_quotation['extra_hr_cost'] ?>" onchange="validate_balance(this.id)"> 

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	     	<input type="text" name="daily_km" id="daily_km" title="Daily KM" placeholder="Daily KM" value="<?= $sq_quotation['daily_km'] ?>" onchange="validate_balance(this.id)">
	        	 
	    </div>
	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" class="form-control" id="quotation_date" name="quotation_date" placeholder="Quotation Date" title="Quotation Date" value="<?= get_date_user($sq_quotation['quotation_date']) ?>"> 

	    </div>
	</div>	



	<br><br>

	

	<div class="row text-center">

		<div class="col-xs-12">

			<button class="btn btn-info btn-sm ico_right">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>

		</div>

	</div>

</form>



<script>

 

$('#frm_tab11_c').validate({

	rules:{

			 

	},

	submitHandler:function(form){

		  

		$('a[href="#tab_2_c"]').tab('show');



	}

});

</script>

