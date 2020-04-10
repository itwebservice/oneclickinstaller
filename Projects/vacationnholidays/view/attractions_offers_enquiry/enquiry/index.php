<?php
include "../../../model/model.php";
/*======******Header******=======*/
require_once('../../layouts/admin_header.php');
$branch_admin_id = $_SESSION['branch_admin_id'];
$sq = mysql_fetch_assoc(mysql_query("select * from branch_assign where link='attractions_offers_enquiry/enquiry/index.php'"));
$branch_status = $sq['branch_status'];

$login_id = $_SESSION['login_id'];
$role = $_SESSION['role'];
$emp_id = $_SESSION['emp_id'];
$financial_year_id = $_SESSION['financial_year_id'];

include_once('enquiry_master_save.php');

?>
<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>" >
<input type="hidden" id="financial_year_id" name="financial_year_id" value="<?= $financial_year_id ?>" >
<?= begin_panel('Enquiry',39) ?>
<div class="header_bottom mg_tp_10">
	<div class="row">
		<div class="col-sm-6">
			<button class="btn btn-info btn-sm ico_left pull-left" style="margin-right:10px" onclick="display_format_modal();"><i class="fa fa-eye"></i>&nbsp;&nbsp;CSV Format</button>
			<div class="div-upload" id="div_upload_button">
				<div id="enquiry_csv_upload" class="upload-button1"><span>CSV</span></div>
				<span id="id_proof_status" ></span>
				<ul id="files" ></ul>
				<input type="hidden" id="txt_enquiry_csv_upload_dir" name="txt_enquiry_csv_upload_dir">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 text-left col-sm-9">
			<span style="color: red;line-height: 35px;" data-original-title="" title="" class="note"><?= "Use CSV Import for Package Booking Enquiries only" ?></span>
		</div>
		<div class="col-sm-6 text-right text_left_sm_xs">
			<?php if($role=='Admin' || $role=='Branch Admin'){ ?>
			<button class="btn btn-excel btn-sm" onclick="excel_report_followup()" data-toggle="tooltip">
			<a  title="Followup Report">
				<i class="fa fa-file-excel-o"></i>
			</a>
		</button>
		<?php } ?>
		<button class="btn btn-excel btn-sm" onclick="excel_report()" data-toggle="tooltip">
			<a  title="Enquiry Report">
				<i class="fa fa-file-excel-o"></i>
			</a>
		</button>
		<button class="btn btn-excel btn-sm" id="send_btn" onclick="send()" data-toggle="tooltip" title="" data-original-title="Send Enquiry Form"><i class="fa fa-paper-plane-o"></i></button>
		<button class="btn btn-info btn-sm ico_left" id="btn_save_modal" onclick="save_modal()"><i class="fa fa-plus"></i>&nbsp;&nbsp;Enquiry</button>
	</div>
</div>
<!--=======Header panel end======-->

<div class="app_panel_content">
	<div class="div-upload pull-left mg_bt_0 hidden" id="div_upload_button">
			<div id="enq_csv_upload" class="upload-button1"><span>Import Enquiries</span></div>
			<span id="adnary_status"></span>
			<ul id="files" ></ul>
			<input type="hidden" id="enq_csv_dir" name="enq_csv_dir">
	</div>

	<div class="main_block mg_tp_10">
		<div class="col-md-12">
			<div class="app_panel_content Filter-panel">
				<div class="row">
					<?php 
					if($role=="Admin"){
					?>
					<div class="col-md-3 col-sm-6 mg_bt_10">
						<select name="emp_id_filter" id="emp_id_filter" style="width:100%" title="User">
							<option value="">User</option>
							<?php 
							$sq_emp = mysql_query("select * from emp_master where emp_id!='0' and active_flag='Active'");
							while($row_emp = mysql_fetch_assoc($sq_emp)){
								?>
								<option value="<?= $row_emp['emp_id'] ?>"><?= $row_emp['first_name'].' '.$row_emp['last_name'] ?></option>
								<?php
							}
							?>
						</select>
					</div>
					<?php
						}
				elseif($branch_status=='yes' && $role=='Branch Admin'){  ?>
					<div class="col-md-3 col-sm-6 mg_bt_10">
						<select name="emp_id_filter" id="emp_id_filter" style="width:100%" title="Users">
							<option value="">User</option>
							<?php
							$query = "select * from emp_master where active_flag='Active' and branch_id='$branch_admin_id' order by first_name asc";
							$sq_emp = mysql_query($query);
							while($row_emp = mysql_fetch_assoc($sq_emp)){
									?>
									<option value="<?= $row_emp['emp_id'] ?>"><?= $row_emp['first_name'].' '.$row_emp['last_name'] ?></option>
									<?php
								}
							?>
						</select>
					</div>
					<?php } ?>
					<div class="col-md-3 col-sm-6 mg_bt_10">
						<select name="enquiry_status_filter" id="enquiry_status_filter" title="Enquiry Status">
							<option value="">Status</option>
							<option value="Active">Active</option>
							<option value="In-Followup">In-Followup</option>
							<option value="Dropped">Dropped</option>
							<option value="Converted">Converted</option>
						</select>
					</div>
					<div class="col-md-3 col-sm-6 mg_bt_10">
						<input type="text" id="followup_from_date_filter" name="followup_from_date_filter" placeholder="From Date" title="From Date">
					</div>
					<div class="col-md-3 col-sm-6 mg_bt_10">
						<input type="text" id="followup_to_date_filter" name="followup_to_date_filter" placeholder="To Date" title="To Date">
					</div>
				</div>

				<div class="row">
					<div class="col-md-3 col-sm-6 mg_bt_10_xs">
						<select name="enquiry_type_filter" id="enquiry_type_filter" title="Enquiry Type">
													<option value="">Enquiry</option>
													<option value="Package Booking">Package Booking</option>
													<option value="Flight Ticket">Flight Ticket</option>
													<option value="Bus">Bus</option>
													<option value="Car Rental">Car Rental</option>
													<option value="Group Booking">Group Booking</option>
													<option value="Hotel">Hotel</option>
													<option value="Passport">Passport</option>
													<option value="Train Ticket">Train Ticket</option>
													<option value="Visa">Visa</option>
											</select>
					</div>
					<div class="col-md-3 col-sm-6 mg_bt_10_xs">
						<select name="reference_id_filter" id="reference_id_filter" title="Reference">
												<option value="">Reference</option>
												<?php 
												$sq_ref = mysql_query("select * from references_master where active_flag='Active' order by reference_name");
												while($row_ref = mysql_fetch_assoc($sq_ref)){
													?>
													<option value="<?= $row_ref['reference_id'] ?>"><?= $row_ref['reference_name'] ?></option>
													<?php
												}
												?>
										</select>
					</div>
					<div class="col-md-3 col-sm-6 mg_bt_10_xs">
						<select name="enquiry_filter" id="enquiry_filter" title="Type">
												<option value="">Type</option>
													<option value="<?= "Strong" ?>">Strong</option>
													<option value="<?= "Hot" ?>">Hot</option>
													<option value="<?= "Cold" ?>">Cold</option>
										</select>
					</div>
					<div class="col-md-3 col-sm-6 ">
						<button class="btn btn-sm btn-info ico_right" onclick="enquiry_list_reflect(20,0)">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="div_modal"></div>
	<div id="div_enquiries_list" class="main_block loader_parent">

	<div class="row mg_tp_20">
		<div class="col-md-12">
			<div class="col-md-4">
				<p><span style="font-weight:bold; font-size:18px; padding-top:10px;">Enquiry Count:</span><span id="enquiry_count" style="font-weight:bold; font-size:18px; margin-top:10px;"></span></p>
			</div>
			<div class="col-md-4 col-md-offset-4 text-right">
				<div class="col-md-10"><input type="text" name="search" id="search" placeholder="Search...." class="form-control"></div>
				<div class="col-md-2"><button class="btn btn-sm btn-info ico_right" onclick="SearchData(20,0);">Search&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button></div>
			</div>
		</div>
	</div>
	<div id="table_data">
			<div class="row mg_tp_20">
				<div class="col-md-12 no-pad"> 
				<div class="table-responsive">
						<table id="enquiry_table" class="table table-hover table-bordered mg_bt_0 bg_white" style="margin: 20px 0 !important;">
							<thead>
								<tr class="table-heading-row">
									<th>S_No.</th>
									<th>Enquiry_No.</th>
									<th>Customer</th>
									<th>Tour</th>
									<th>Enquiry_date</th>
									<th>Followup_date & Time</th>
									<th>Followup</th>
									<?php if($role=='Admin'|| $role=='Branch Admin'){ ?>
									<th>Allocate_To</th><?php } ?>
									<th>Status</th>
									<th>View</th>
									<th class="hidden">Status</th>
									<?php if($role=='Admin' || $role=='Branch Admin'){ ?>
									<th>Disable</th> <?php } ?>
								</tr>
							</thead>
							<tbody style="height:1238px;">
								</tbody>
							</table>
							<table id="search_table" class="table table-hover table-bordered mg_bt_0 bg_white" style="margin: 20px 0 !important;">
							<thead>
								<tr class="table-heading-row">
									<th>S_No.</th>
									<th>Enquiry_No.</th>
									<th>Customer</th>
									<th>Tour</th>
									<th>Enquiry_date</th>
									<th>Followup_dateTime</th>
									<th>Followup</th>
									<?php if($role=='Admin'|| $role=='Branch Admin'){ ?>
									<th>Allocate_To</th><?php } ?>
									<th>Status</th>
									<th>View</th>
									<th class="hidden">Status</th>
									<?php if($role=='Admin' || $role=='Branch Admin'){ ?>
									<th>Disable</th> <?php } ?>
								</tr>
							</thead>
							<tbody style="height:1238px;">
								</tbody>
							</table>
						</div> </div> 
				</div>
	</div>
  <div id="load_data_message"></div>
</div>
</div>
</div>
<?= end_panel() ?>
<script src="<?= BASE_URL ?>js/ajaxupload.3.5.js"></script>
<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>
<script>
$('#followup_from_date_filter, #followup_to_date_filter').datetimepicker({ timepicker:false, format:'d-m-Y' });
$('#emp_id_filter').select2();

//Enquiry CSV Format File Code
enquiry_csv_upload();
function enquiry_csv_upload()
{   
    var type="id_proof";
    var btnUpload=$('#enquiry_csv_upload');
	var status=$('#id_proof_status');
	var financial_year = $('#financial_year_id').val();
	if(financial_year == ''){
		alert("Please select Financial year then add Enquiries! ");
		return false;
	}
    new AjaxUpload(btnUpload, {
      action: 'upload_enquiry_csv_file.php',
      name: 'uploadfile',
      onSubmit: function(file, ext){
		  
		if (!(ext && /^(csv)$/.test(ext))){
			
			// extension is not allowed
			alert('Only CSV Format files are allowed');
			return false;

			if(!confirm('Do you want to import this file?')){
				return false;
			}
			else{
				status.text('Uploading');
			}

        }
      },
      onComplete: function(file, response){
        //On completion clear the status
        status.text('');
        //Add uploaded file to list
        if(response==="error"){          
          alert("File is not uploaded.");
        } else{
          status.text('');
          document.getElementById("txt_enquiry_csv_upload_dir").value = response;
          enqiury_from_csv_save();
        }
      }
    });

}

function enqiury_from_csv_save(){
	var enq_csv_dir = document.getElementById("txt_enquiry_csv_upload_dir").value;
	var base_url = $('#base_url').val();

	$.ajax({
		type:'post',
		url: base_url+'controller/attractions_offers_enquiry/enquiry_csv_save_v.php',
		data:{ enq_csv_dir : enq_csv_dir },
		success:function(data){

			if(data=="This enquiry is not exists."){
				prompt_error_enquiry(enq_csv_dir, data);                      
			}
			else{                                                
				prompt_error_enquiry(enq_csv_dir, data);                        
			}   
		}
	});
}	
function prompt_error_enquiry(obj, msg){

	var status=$('#id_proof_status');
  	if(msg=="This enquiry is not exists."){
    actual_enq_save(obj);
  	}
  	else{
    $('#vi_confirm_box').vi_confirm_box({
		message: "Are you sure to save?",
		callback: function(data1){
			if(data1=="yes"){
				actual_enq_save(obj);
				status.text('Uploading');
			}
			else{
				status.text('');
				return false;
			}
		}
    });  
  	}
}

function actual_enq_save(obj){
  $('#app_content_wrap').append('<div class="loader"></div>');

  var base_url = $('#base_url').val();
  var status=$('#id_proof_status');
  var obj = {obj : obj};
  $.post( 
       base_url+"controller/attractions_offers_enquiry/enquiry_csv_save.php",
       obj,
       function(data) {  
			var msg = data.split('--');
			if(msg[0]=="error"){
				msg_alert(data);
				status.text('');
			}
			else{
				msg_alert(data);
				status.text('');
				enquiry_list_reflect(20, 0);
			}
       });
}

function display_format_modal()
{
	var base_url = $('#base_url').val();
	window.location = base_url+"images/csv_format/enquiry.csv";
}

function save_modal()
{
	$('#btn_save_modal').button('loading');
	var branch_status = $('#branch_status').val();
	$.post('save_modal.php', {branch_status : branch_status}, function(data){
		$('#btn_save_modal').button('reset');
		$('#div_modal').html(data);
	});
}

/////////////////////////////////////////////////////////// Enquiry Loading Code Start//////////////////////////////////////////////////////////////////
var action = 'inactive'; //Check if current action is going on or not. If not then inactive otherwise active

//On Scroll and page load this will work
function enquiry_proceed_reflect(limit, start){
	$('#search_table').hide();
	$('#enquiry_table').show();
	var enquiry_type = $('#enquiry_type_filter').val();
	var enquiry = $('#enquiry_filter').val();
	var enquiry_status = $('#enquiry_status_filter').val();
	var from_date = $('#followup_from_date_filter').val();
	var to_date = $('#followup_to_date_filter').val();
	var reference_id_filter = $('#reference_id_filter').val();
	var emp_id_filter = $('#emp_id_filter').val();
	var branch_status = $('#branch_status').val();
	var branch_filter = $('#branch_filter').val();

	$.ajax({
	url:"enquiry_proceed_reflect.php",
	method:"POST",
	data:{limit:limit, start:start, enquiry:enquiry,enquiry_type : enquiry_type, enquiry_status : enquiry_status, from_date : from_date, to_date : to_date, emp_id_filter : emp_id_filter , branch_status : branch_status , branch_filter : branch_filter , reference_id_filter:reference_id_filter},
	cache:false,
	success:function(data){
		$('#enquiry_table tbody').append(data);
			action = 'inactive';
	}
	});
}

if(action == 'inactive'){
	var limit = 20; //The number of records to display per request
	var start = 0; //The starting pointer of the data
	action = 'active';
	enquiry_proceed_reflect(limit, start);
}
$("div.app_content_wrap").on('scroll' , function(event){
if($('#enquiry_table').is(":visible")==true){
	if($("div.app_content_wrap").scrollTop() + $("div.app_content_wrap").height() > $("#enquiry_table tbody").height() && action == 'inactive'){
		action = 'active';
		start = start + limit;
		setTimeout(function(){
			enquiry_proceed_reflect(limit, start);
		}, 0);
	}
}
});

//On filter this will work(Proceed button)
function enquiry_list_reflect(limit , start){
		window.limit=20;
		window.start=0;
		$('#search_table').hide();
		$('#enquiry_table').show();
		$('#enquiry_table tbody tr').remove();

		var enquiry_type = $('#enquiry_type_filter').val();
		var enquiry = $('#enquiry_filter').val();
		var enquiry_status = $('#enquiry_status_filter').val();
		var from_date = $('#followup_from_date_filter').val();
		var to_date = $('#followup_to_date_filter').val();
		var emp_id_filter = $('#emp_id_filter').val();
		var reference_id = $('#reference_id_filter').val();
		var branch_status = $('#branch_status').val();

		$.post('enquiry_list_reflect.php', {limit : limit, start : start, enquiry_type : enquiry_type, enquiry : enquiry, enquiry_status : enquiry_status, from_date : from_date, to_date : to_date, emp_id_filter : emp_id_filter, reference_id_filter : reference_id , branch_status : branch_status}, function(data){
			$('#enquiry_table tbody').html(data);
		});
}
function SearchData(limit, start){
	var search=$('#search').val();
	var branch_status = $('#branch_status').val();
	$('#search_table').show();
	$('#enquiry_table').hide();
	$.post('search_data.php', {limit : limit , start:start, search : search,branch_status:branch_status}, function(data){
		$('#search_table tbody').html(data);
	});
}

/////////////////////////////////////////////////////////// Enquiry Loading Code End//////////////////////////////////////////////////////////////////

function excel_report()
{
	var enquiry_type = $('#enquiry_type_filter').val();
	var enquiry = $('#enquiry_filter').val();
	var enquiry_status = $('#enquiry_status_filter').val();
	var from_date = $('#followup_from_date_filter').val();
	var to_date = $('#followup_to_date_filter').val();
	var emp_id_filter = $('#emp_id_filter').val();
	var reference_id = $('#reference_id_filter').val();
	var branch_status = $('#branch_status').val();
	window.location = 'excel_report.php?enquiry_type='+enquiry_type+'&enquiry='+enquiry+'&enquiry_status='+enquiry_status+'&from_date='+from_date+'&to_date='+to_date+'&emp_id_filter='+emp_id_filter+'&reference_id='+reference_id+'&branch_status='+branch_status;
}

function excel_report_followup()
{
	var emp_id_filter = $('#emp_id_filter').val();
	var enquiry = $('#enquiry_filter').val();
	var enquiry_type = $('#enquiry_type_filter').val();
	var branch_status = $('#branch_status').val();
	var from_date = $('#followup_from_date_filter').val();
	var to_date = $('#followup_to_date_filter').val();
	var enquiry_status = $('#enquiry_status_filter').val();
	var reference_id = $('#reference_id_filter').val();
	window.location = 'followup/followup/excel_report.php?branch_status='+branch_status+'&from_date='+from_date+'&to_date='+to_date+'&emp_id_filter='+emp_id_filter+'&enquiry_status='+enquiry_status+'&reference_id='+reference_id+'&enquiry_type='+enquiry_type+'&enquiry='+enquiry;
}
function view_modal(enquiry_id)
{
	$.post('view_modal.php', { enquiry_id : enquiry_id }, function(data){
		$('#div_modal').html(data);
	});
}		
function send()
{	
	$('#send_btn').button('loading');
	$.post('send_enq_form.php', { }, function(data){
			$('#div_modal').html(data);
			$('#send_btn').button('reset');
	});
}

</script>

<?php
/*======******Footer******=======*/
require_once('../../layouts/admin_footer.php'); 
?>