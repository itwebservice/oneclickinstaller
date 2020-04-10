<form id="frm_tab1">

	<div class="row">

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

		    <input type="hidden" id="emp_id" name="emp_id" value="<?= $emp_id ?>">
		    <input type="hidden" id="branch_admin_id1" name="branch_admin_id1" value="<?= $branch_admin_id ?>" >
		    <input type="hidden" id="financial_year_id" name="financial_year_id" value="<?= $financial_year_id ?>" >
			<input type="hidden" id="login_id" name="login_id" value="<?= $login_id ?>">

			<select name="enquiry_id" id="enquiry_id" title="Enquiry No" style="width:100%" onchange="get_enquiry_details()">

				<option value="">*Enquiry No</option>
				<option value="0"><?= "New Enquiry" ?></option>

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

	      <input type="text" id="customer_name" name="customer_name" onchange="fname_validate(this.id)" placeholder="Customer Name" title="Customer Name">

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<input type="text" id="email_id" name="email_id" placeholder="Email ID" title="Email ID">

		</div>	

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<input type="text" id="mobile_no" name="mobile_no" onchange="mobile_validate(this.id)" placeholder="Mobile No" title="Mobile No">

		</div>

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" id="total_pax" name="total_pax"  onchange="validate_balance(this.id)" placeholder="No Of Guest" title="No Of Guest">

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	     <input type="text" id="days_of_traveling" name="days_of_traveling" onchange="validate_balance(this.id)" placeholder="Days Of Travelling" title="Days Of Travelling">

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" id="traveling_date" name="traveling_date" placeholder="Travelling Date" title="Travelling Date">

	    </div>	        		            

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<input type="text" id="vehicle_type" name="vehicle_type" placeholder="Vehicle Type" title="Vehicle Type">

		</div>	

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" id="travel_type" name="travel_type" placeholder="Travel Type" onchange="fname_validate(this.id)" title="Travel Type">

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
 			<textarea name="places_to_visit" id="places_to_visit" onchange="validate_specialChar(this.id)" rows="1" placeholder="Places To Visit" title="Places To Visit"></textarea>

	    </div>


	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" id="vehicle_name" name="vehicle_name" onchange="validate_specialChar(this.id)" placeholder="Vehicle Name" title="Vehicle Name" >

	    </div>	        		           

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<input type="text" id="from_date" name="from_date" placeholder="Travel From Date" title="Travel From Date" value="<?= date('d-m-Y H:i')?>" onchange="get_to_datetime(this.id,'to_date')">

		</div>	

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" id="to_date" name="to_date" placeholder="Travel To Date" title="Travel To Date" value="<?= date('d-m-Y H:i')?>">

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	     <input type="text" class="form-control" id="trip_type" name="trip_type" onchange="fname_validate(this.id)" placeholder="Trip Type" title="Trip Type"> 

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" class="form-control" id="route" name="route" onchange="validate_spaces(this.id)" placeholder="Route" title="Route"> 

	    </div>	        		                			        		        	        		


		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<input type="text" id="extra_km_cost" name="extra_km_cost" placeholder="Extra KM Cost" title="Extra KM Cost" onchange="validate_balance(this.id)">

		</div>	

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" class="form-control" id="extra_hr_cost" name="extra_hr_cost" placeholder="Extra Hr Cost" title="Extra Hr Cost" onchange="validate_balance(this.id)"> 

	    </div>

	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	     	<input type="text" name="daily_km" id="daily_km" title="Daily KM" placeholder="Daily KM" onchange="validate_balance(this.id)">
	        	 
	    </div>
	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

	      <input type="text" class="form-control" id="quotation_date" name="quotation_date" placeholder="Quotation Date" title="Quotation Date" value="<?= date('d-m-Y')?>"> 

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
$('#from_date,#to_date').datetimepicker({ format:'d-m-Y H:i:s' });
$('#frm_tab1').validate({

	rules:{

			enquiry_id : { required : true },

	},

	submitHandler:function(form){

 

		  

		$('a[href="#tab2"]').tab('show');



	}

});



</script>

