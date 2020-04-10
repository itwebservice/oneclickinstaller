<?php
 include_once('../model.php');
?>
<head>

  <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,500" rel="stylesheet">

  <link rel="stylesheet" href="<?php echo BASE_URL ?>css/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL ?>css/jquery-ui.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo BASE_URL ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>css/jquery.datetimepicker.css">
  <link rel="stylesheet" href="<?php echo BASE_URL ?>css/app/admin.php">
  <link rel="stylesheet" href="<?php echo BASE_URL ?>css/app/app.php">
            
  <script src="<?php echo BASE_URL ?>js/jquery-3.1.0.min.js"></script>
  <script src="<?php echo BASE_URL ?>js/jquery-ui.min.js"></script>
  <script src="<?php echo BASE_URL ?>js/bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL ?>js/jquery.datetimepicker.full.js"></script> 
    <script src="<?php echo BASE_URL ?>js/jquery.validate.min.js"></script>

</head>
<div class="container">
    <div class="app_panel_head">
      <h2>Enquiry Information</h2>
    </div>
<form id="frm_enquiry_save" class="mg_tp_20">
<div class="panel panel-default panel-body app_panel_style feildset-panel" style="margin-bottom: 30px;">
	<div class="main_block mg_tp_20">
		<div class="col-md-2 text-right"><label for="app_name">Customer Name</label></div>
		<div class="col-md-2 no-pad">
			<input type="text" class="form-control" id="txt_name" name="txt_name" placeholder="*Customer Name" title="Customer Name">
		</div>
		<div class="col-md-2 text-right"><label for="app_website">Mobile No</label></div>
		<div class="col-md-2 no-pad">
			<input type="text" class="form-control" id="txt_mobile_no" name="txt_mobile_no" placeholder="*Mobile No" title="Mobile No"> 
		</div>
		<div class="col-md-2 text-right"><label for="app_contact_no">Whatsapp No</label></div>
		<div class="col-md-2 no-pad">
			<input type="text" class="form-control" id="txt_landline_no" name="txt_landline_no" placeholder="Whatsapp No" title="Whatsapp No with country code"> 
		</div>			
	</div>
	<div class="main_block mg_tp_20">
		<div class="col-md-2 text-right"><label for="app_landline_no">Email ID</label></div>
		<div class="col-md-2 no-pad">
			<input type="text" class="form-control" id="txt_email_id" name="txt_email_id" placeholder="*Email ID:e.g.abc@gmail.com" title="Email ID">
		</div>

		<div class="col-md-2 text-right"><label for="app_name">Interested Tour</label></div>
		<div class="col-md-2 no-pad">
			<input type="text" id="tour_name" name="tour_name" class="form-control"  placeholder="*Interested Tour" title="*Interested Tour">
		</div>

		<div class="col-md-2 text-right"><label for="app_landline_no">Budget</label></div>
		<div class="col-md-2 no-pad">			
			    <input type="text" id="budget" class="form-control" name="budget" placeholder="*Budget" title="Budget">
		</div>

		
	</div>
	<div class="main_block mg_tp_20">
	    <div class="col-md-2 text-right"><label for="app_website">Travel From</label></div>
		<div class="col-md-2 no-pad">
			<input type="text" id="travel_from_date" class="form-control" name="travel_from_date" title="Travel From Date" placeholder="*Travel From Date">
		</div>
		<div class="col-md-2 text-right"><label for="app_contact_no">Travel To</label></div>
		<div class="col-md-2 no-pad">
			<input type="text" id="travel_to_date" class="form-control" name="travel_to_date" title="Travel To Date" placeholder="*Travel To Date">
		</div>	

		<div class="col-md-2 text-right"><label for="app_landline_no">Total Adult</label></div>
		<div class="col-md-2 no-pad">
			<input type="text" id="total_adult" class="form-control" name="total_adult" placeholder="*Total Adult" title="Total Adult">   
		</div>
	</div>
	<div class="main_block mg_tp_20">

		<div class="col-md-2 text-right"><label for="app_name">Total Children</label></div>
		<div class="col-md-2 no-pad">
			<input type="text" id="total_children" class="form-control" name="total_children" placeholder="*Total Children" title="*Total Children"> 
		</div>

		<div class="col-md-2 text-right"><label for="app_website">Total Infant</label></div>
		<div class="col-md-2 no-pad">
			<input type="text" id="total_infant" class="form-control" name="total_infant" placeholder="*Total Infant" title="*Total Infant">   
		</div>
		<div class="col-md-2 text-right"><label for="app_website">Hotel Type</label></div>
		<div class="col-md-2 no-pad">
				<select name="hotel_type" id="hotel_type" title="Hotel Type" class="form-control">
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
	<div class="main_block mg_tp_20">

		<div class="col-md-2 text-right"><label for="app_name">Reference</label></div>
		<div class="col-md-2 no-pad">
			<select name="reference_id" id="reference_id" class="form-control" style="width:100%" title="References">
										<option value="">*Reference</option>
										<option value="Walk-in">Walk-in</option>
										<option value="Website">Website</option>
										<option value="Customer">Customer</option>
										<option value="Advertise">Advertise</option>
										<option value="Telephone">Telephone</option>
										
										<option value="Others">Others</option>
								</select>
		</div>
		<div class="col-md-2 text-right"><label for="app_name">Enquiry Specification</label></div>
		<div class="col-md-6 no-pad">
			<textarea class="form-control" id="txt_enquiry_specification" name="txt_enquiry_specification" placeholder="Other Enquiry specification (If any)" class="form-control" title="Enquiry Specification"></textarea>
		</div>
	</div>
	<div class="main_block text-center mg_tp_20">
	 <div class="col-md-12">
		<button class="btn btn-success" id="form_send"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
	 </div>
    </div>
</div>
</form>
</div>
<script>
$("#travel_from_date,#travel_to_date").datetimepicker({timepicker:false, format  : 'd-m-Y'});
/*function send_enq_info()
{*/
 $(function(){
  $('#frm_enquiry_save').validate({
    rules:{
            txt_name : { required :true },
            txt_mobile_no : { required :true },
            txt_email_id : { required :true, email:true }, 
            reference_id : { required : true },
            tour_name : { required : true },
            travel_from_date : { required : true },
            travel_to_date : { required : true },
            total_adult : { required : true },
            total_children : { required : true },
            total_infant : { required : true },
            enquiry_spec : { required : true },
            budget : { required : true },
    },
    submitHandler:function(form){
	   var name = $("#txt_name").val(); 
	   var mobile_no = $("#txt_mobile_no").val(); 
	   var landline_no = $("#txt_landline_no").val();
	   var email_id = $("#txt_email_id").val(); 
	   var tour_name  = $('#tour_name').val();
	   var travel_from_date = $("#travel_from_date").val(); 
	   var travel_to_date = $("#travel_to_date").val();
	   var budget = $('#budget').val();
	   var total_adult = $('#total_adult').val();
	   var total_children = $('#total_children').val();
	   var total_infant = $('#total_infant').val();
	   var reference_id = $('#reference_id').val();
		 var enquiry_spec = $("#txt_enquiry_specification").val();
		 var hotel_type = $("#hotel_type").val();
	   
	   $('#form_send').button('loading');
	   
	    $.ajax({
	      type:'post',
	      url: 'admin_tour_enquiry.php',
	      data:{name : name,mobile_no : mobile_no,landline_no : landline_no, email_id : email_id,tour_name : tour_name,travel_from_date : travel_from_date,travel_to_date : travel_to_date,budget : budget,total_adult : total_adult,total_children : total_children,total_infant : total_infant,reference_id: reference_id,enquiry_spec : enquiry_spec,hotel_type : hotel_type},
	      success: function(message){
	          alert(message); 
	          $('#form_send').button('reset');
	           location.reload();
	     }  
	    }); 
    }
  });
});
</script>