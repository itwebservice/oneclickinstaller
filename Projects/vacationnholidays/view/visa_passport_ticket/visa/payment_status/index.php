<?php 
include "../../../../model/model.php";
$sq = mysql_fetch_assoc(mysql_query("select * from branch_assign where link='visa_passport_ticket/visa/index.php'"));
$branch_status = $sq['branch_status'];
 
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$emp_id = $_SESSION['emp_id'];
?>
 <input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>" >
<div class="row mg_bt_20">
	<div class="col-md-12 text-right">
		<button class="btn btn-excel btn-sm" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>
	</div>
</div>
 
 <div class="app_panel_content Filter-panel">
 	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
		        <select name="cust_type_filter" id="cust_type_filter" style="width: 100%" onchange="dynamic_customer_load(this.value,'company_filter'); company_name_reflect();" title="Customer Type">
		            <?php get_customer_type_dropdown(); ?>
		            
		            
		            
                    
		        </select>
	    </div>
	    <div id="company_div" class="hidden mg_bt_10">
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10" id="customer_div">    
	    </div> 
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<select name="visa_id_filter" id="visa_id_filter" title="Booking ID" style="width: 100%">
		        <option value="">Booking ID</option>
		        <?php 
		        $query = "select * from visa_master where 1";
	            include"../../../../model/app_settings/branchwise_filteration.php";
	            $query .= " order by visa_id desc";
	            $sq_visa = mysql_query($query);
		        while($row_visa = mysql_fetch_assoc($sq_visa)){

		        	$date = $row_visa['created_at'];
				      $yr = explode("-", $date);
				      $year =$yr[0];
		          $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_visa[customer_id]'"));
		          ?>
		          <option value="<?= $row_visa['visa_id'] ?>"><?= get_visa_booking_id($row_visa['visa_id'],$year).' : '.$sq_customer['first_name'].' '.$sq_customer['last_name'] ?></option>
		          <?php
		        }
		        ?>
		    </select>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<input type="text" id="from_date" name="from_date" class="form-control" placeholder="From Date" title="From Date">
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<input type="text" id="to_date" name="to_date" class="form-control" placeholder="To Date" title="To Date">
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<select name="booker_id_filter" id="booker_id_filter" title="User Name" style="width: 100%" onchange="emp_branch_reflect()">
		        <?php  get_user_dropdown($role, $branch_admin_id, $branch_status,$emp_id) ?>
		    </select>
		</div>
	 
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<select name="branch_id_filter" id="branch_id_filter" title="Branch Name" style="width: 100%">
		         <option value="">Select Branch</option>
		    </select>
		</div>
		 
		<div class="col-md-3 col-sm-6 col-xs-12">
			<button class="btn btn-sm btn-info ico_right" onclick="list_reflect()">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
		</div>
	</div>
 </div>	
	
		
<div id="div_list" class="main_block loader_parent"></div>
<div id="div_package_content_display"></div>
<script>
$('#customer_id_filter, #visa_id_filter, #cust_type_filter,#booker_id_filter,#branch_id_filter').select2();
$('#from_date,#to_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
dynamic_customer_load('','');
function list_reflect()
{
	$('#div_list').append('<div class="loader"></div>');
	var customer_id = $('#customer_id_filter').val();
	var visa_id = $('#visa_id_filter').val();
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	var cust_type = $('#cust_type_filter').val();
	var company_name = $('#company_filter').val();
	var booker_id = $('#booker_id_filter').val();
	var branch_id = $('#branch_id_filter').val();
	var base_url = $('#base_url').val();
	var branch_status = $('#branch_status').val();
	$.post(base_url+'view/visa_passport_ticket/visa/payment_status/list_reflect.php', { customer_id : customer_id, visa_id : visa_id, from_date : from_date, to_date : to_date, cust_type : cust_type, company_name : company_name,booker_id:booker_id,branch_id : branch_id, branch_status : branch_status }, function(data){
		$('#div_list').html(data);
	});
}
list_reflect();

	function excel_report()
	{
		var customer_id = $('#customer_id_filter').val()
		var visa_id = $('#visa_id_filter').val()
		var from_date = $('#from_date').val();
		var to_date = $('#to_date').val();
		var cust_type = $('#cust_type_filter').val();
		var company_name = $('#company_filter').val();
	    var booker_id = $('#booker_id_filter').val();
	    var branch_id = $('#branch_id_filter').val();
		var base_url = $('#base_url').val();
		var branch_status = $('#branch_status').val();
		window.location = base_url+'view/visa_passport_ticket/visa/payment_status/excel_report.php?customer_id='+customer_id+'&visa_id='+visa_id+'&from_date='+from_date+'&to_date='+to_date+'&cust_type='+cust_type+'&company_name='+company_name+'&booker_id='+booker_id+'&branch_id='+branch_id+'&branch_status='+branch_status;
	}
	//*******************Get Dynamic Customer Name Dropdown**********************//
	function dynamic_customer_load(cust_type, company_name)
	{
	  var cust_type = $('#cust_type_filter').val();
 	  var company_name = $('#company_filter').val();
 	  var branch_status = $('#branch_status').val();

      var base_url = $('#base_url').val();
	    $.get(base_url+"view/visa_passport_ticket/visa/home/get_customer_dropdown.php", { cust_type : cust_type , company_name : company_name, branch_status : branch_status}, function(data){
	    $('#customer_div').html(data);
	  });   
	}
	function company_name_reflect()
	{  
		var cust_type = $('#cust_type_filter').val();
	    var base_url = $('#base_url').val();
	    var branch_status = $('#branch_status').val();
	  	$.post(base_url+'view/visa_passport_ticket/visa/home/company_name_load.php', { cust_type : cust_type, branch_status : branch_status }, function(data){
	  		if(cust_type=='Corporate'){
		  		$('#company_div').addClass('company_class');	
		    }
		    else
		    {
		    	$('#company_div').removeClass('company_class');		
		    }
		    $('#company_div').html(data);
	    });
	}
function visa_view_modal(visa_id,year)
{
var base_url = $('#base_url').val();
$.post(base_url+'view/visa_passport_ticket/visa/payment_status/view/index.php', { visa_id : visa_id, year : year }, function(data){
  $('#div_package_content_display').html(data);
});
}

function visa_id_dropdown_load(customer_id_filter, visa_id_filter)
{
	var customer_id = $('#'+customer_id_filter).val();
	var base_url = $('#base_url').val();
	var branch_status = $('#branch_status').val();
	$.post(base_url+'view/visa_passport_ticket/visa/visa_id_dropdown_load.php', { customer_id : customer_id , branch_status : branch_status}, function(data){
		$('#'+visa_id_filter).html(data);
	});
}
function supplier_view_modal(visa_id)
{
	
	var base_url = $('#base_url').val();
	$.post(base_url+'view/visa_passport_ticket/visa/payment_status/view/supplier_view_modal.php', { visa_id : visa_id }, function(data){
      $('#div_package_content_display').html(data);
    });
}
function payment_view_modal(visa_id)
{	
	var base_url = $('#base_url').val();
	$.post(base_url+'view/visa_passport_ticket/visa/payment_status/view/payment_view_modal.php', { visa_id : visa_id }, function(data){
      $('#div_package_content_display').html(data);
    });
}
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>