<?php
include "../../model/model.php";
/*======******Header******=======*/

require_once('../layouts/admin_header.php');

$start_date = date('01-m-Y');
$end_date = date('t-m-Y');

$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$emp_id = $_SESSION['emp_id'];

$branch_status = $_POST['branch_status'];

$sq = mysql_fetch_assoc(mysql_query("select * from branch_assign where link='booker_incentive/booker_incentive.php'"));
$branch_status = $sq['branch_status'];
 

 
?>
<?= begin_panel('Incentive/Commission dashboard',84) ?>

<div class="app_panel_content Filter-panel">

	<div class="row">

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">

				<select name="tour_type" id="tour_type" class="form-control" title="Tour Type" style="width: 100%;" onchange="booking_list_reflect()" title="Tour Type">

					<option value="">Tour Type</option>

					<option value="Group Tour">Group Tour</option>

					<option value="Package Tour">Package Tour</option>

				</select>

			</div>

			<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">

				<select name="emp_id" id="emp_id1" onchange="booking_list_reflect()" class="form-control" style="width: 100%;" title="Select User" title="Sales User">

					<option value="">Select User</option>

					<?php 

					if($role=='B2b'){

						$sq_booker = mysql_query("select emp_id, first_name, last_name from emp_master where role_id='4' and emp_id='$emp_id' and active_flag='Active'");	

					}
					elseif($role=='Branch Admin' && $branch_status=='yes')

					{

						$sq_booker = mysql_query("select emp_id, first_name, last_name from emp_master where role_id !='1' and active_flag='Active' and branch_id='$branch_admin_id' order by first_name");

					}

					elseif($role=='Admin')

					{

						$sq_booker = mysql_query("select emp_id, first_name, last_name from emp_master where role_id !='1' and active_flag='Active' order by first_name");

					}

					while($row_booker = mysql_fetch_assoc($sq_booker)){

						?>

						<option value="<?= $row_booker['emp_id'] ?>"><?= $row_booker['first_name'].' '.$row_booker['last_name'] ?></option>

						<?php

					}

					?>

				</select>

			</div>

			<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">

				<input type="text" id="from_date" name="from_date" class="form-control" placeholder="From Date" title="From Date" onchange="booking_list_reflect()">

			</div>

			<div class="col-md-3 col-sm-6 col-xs-12">

				<input type="text" id="to_date" name="to_date" class="form-control" placeholder="To Date" title="To Date" onchange="booking_list_reflect()">
				<input type="hidden" name="branch_status" id="branch_status" value="<?= $branch_status?>">
			</div>

	</div>

</div>		

<div id="div_booker_incentive_reflect" class="main_block loader_parent">

	

</div>
<script src="<?php echo BASE_URL ?>js/app/field_validation.js"></script>                    

<script>
	$('#emp_id1').select2();
	$('#from_date, #to_date').datetimepicker({ timepicker:false, format:'d-m-Y' });

	function booking_list_reflect()

	{
		$('#div_booker_incentive_reflect').append('<div class="loader"></div>');
		var tour_type = $('#tour_type').val();

		var emp_id = $('#emp_id1').val();

		var from_date = $('#from_date').val();

		var to_date = $('#to_date').val();
		var branch_status = $('#branch_status').val();
		
		$.post('booking_list_reflect.php', { tour_type : tour_type, emp_id : emp_id, from_date : from_date, to_date : to_date , branch_status : branch_status}, function(data){

			$('#div_booker_incentive_reflect').html(data);

		});

	}

	booking_list_reflect();



	function incentive_calculate(basic_amount, tds, total_id)
	{
		var basic_amount = $('#'+basic_amount).val();
		var tds = $('#'+tds).val();

		if(basic_amount==""){ basic_amount = 0; }
		if(tds==""){ tds = 0; }
		var tds = (parseFloat(basic_amount)/100)*parseFloat(tds);
		var total = parseFloat(basic_amount)-parseFloat(tds);
		var total1 = total.toFixed(2);
		
		$('#'+total_id).val(total1);
	}

</script>


<?= end_panel() ?>
<?php
/*======******Footer******=======*/
require_once('../layouts/admin_footer.php'); 
?>