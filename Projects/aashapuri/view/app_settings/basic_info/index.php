<?php

include "../../../model/model.php";

$sq_settings = mysql_fetch_assoc(mysql_query("select * from app_settings"));
$sq_settings_g = mysql_fetch_assoc(mysql_query("select * from generic_count_master"));
?>

<form id="frm_basic_info">



	<div class="row mg_bt_30">



		<div class="col-md-2 col-md-offset-6 text-right"><label for="app_version">App Version</label></div>

		<div class="col-md-4">

			<input type="text" id="app_version" name="app_version" onchange="validate_alphanumeric(this.id)" placeholder="App Version(eg. 2017)" title="App Version" value="<?= $sq_settings['app_version'] ?>">

		</div>

	</div>



<div class="panel panel-default panel-body app_panel_style feildset-panel mg_bt_30">

	<legend>Company Details</legend>

	<div class="row mg_tp_20">
		<div class="col-md-2 text-right"><label for="app_name">Company Name</label></div>
		<div class="col-md-4">
			<input type="text" id="app_name" name="app_name" onchange="validate_company(this.id);" placeholder="*Company Name" title="Company Name" value="<?= $sq_settings['app_name'] ?>">
		</div>

		<div class="col-md-2 text-right"><label for="app_website">Website</label></div>
		<div class="col-md-4">
			<input type="text" id="app_website" name="app_website" placeholder="Website" title="Website" value="<?= $sq_settings['app_website'] ?>">
		</div>
	</div>

	<div class="row mg_tp_20">



		<div class="col-md-2 text-right"><label for="app_contact_no">Contact No</label></div>

		<div class="col-md-4">

			<input type="text" id="app_contact_no" name="app_contact_no" onchange="mobile_validate(this.id);" placeholder="Contact No" title="Contact No" value="<?= $sq_settings['app_contact_no'] ?>" maxlength="20">

		</div>	



		<div class="col-md-2 text-right"><label for="app_landline_no">Landline No</label></div>

		<div class="col-md-4">

			<input type="text" id="app_landline_no" name="app_landline_no" onchange="mobile_validate(this.id);" placeholder="Landline No" title="Landline No" value="<?= $sq_settings['app_landline_no'] ?>">

		</div>



	</div>

	<div class="row mg_tp_20">

		

		<div class="col-md-2 text-right"><label for="app_email_id">Email ID</label></div>

		<div class="col-md-4">

			<input type="text" id="app_email_id" name="app_email_id" placeholder="Email ID" title="Email ID" value='<?= $sq_settings['app_email_id'] ?>' onchange="validate_email(this.id)">

			<small>Note : Pls enter webmail Email ID. eg. info@example.com</small>

		</div>



		<div class="col-md-2 text-right"><label for="app_email_id">Backoffice Email ID</label></div>

		<div class="col-md-4">

			<input type="text" id="backoffice_email_id" name="backoffice_email_id" placeholder="Backoffice Email ID" title="Email ID" value="<?= $sq_settings['backoffice_email_id'] ?>">

			<small>Note : Pls enter webmail Email ID. eg. info@example.com</small>

		</div>



	</div>

	<div class="row mg_tp_20">

		

		<div class="col-md-2 text-right"><label for="tax_name">TAX Name</label></div>

		<div class="col-md-4">

			<input type="text" id="tax_name" name="tax_name" placeholder="Tax Name" onchange="validate_taxname(this.id)" title="Tax Name" style="text-transform: uppercase;" value="<?= $sq_settings['tax_name'] ?>">

		</div>



	    <div class="col-md-2 text-right"><label for="service_tax_no">TAX No</label></div>

		<div class="col-md-4">

			<input type="text" id="service_tax_no" name="service_tax_no" placeholder="TAX No" title="TAX No" value="<?= $sq_settings['service_tax_no'] ?>" style="text-transform: uppercase;">

		</div>



	</div>

	<div class="row mg_tp_20">



		<div class="col-md-2 text-right"><label for="bank_cin_no">CIN No</label></div>

		<div class="col-md-4">

			<input type="text" id="cin_no" name="cin_no" onchange="validate_CINCode(this.id);" placeholder="CIN No" title="CIN No" style="text-transform: uppercase;" value="<?= $sq_settings['app_cin'] ?>">

		</div>	



		<div class="col-md-2 text-right"><label for="app_address">Address</label></div>

		<div class="col-md-4">

			<textarea id="app_address" name="app_address" onchange="validate_address(this.id);" placeholder="Address" title="Address"><?= $sq_settings['app_address'] ?></textarea>

		</div>



	</div>
	<div class="row mg_tp_20">
		<div class="col-md-2 text-right"><label for="state">State</label></div> 
        <div class="col-sm-4 ">
		    <select name="state" id="state" title="Select State">
			<?php
			if($sq_settings['state_id']!=""){
			 $sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_settings[state_id]'"));
	                ?>
	        <option value="<?= $sq_settings['state_id'] ?>"><?= $sq_state['state_name'] ?></option>
	        <?php } ?>
			
	        <?php get_states_dropdown() ?>
		    </select>
	    </div>
	    <div class="col-md-2 text-right"><label for="acc_email">Accountant</label></div> 
        <div class="col-sm-4 ">
        	<input type="text" id="acc_email" name="acc_email" placeholder="Accountant Email Id" title="Accountant Email Id" value="<?= $sq_settings['accountant_email'] ?>">
        	<small>Note : Pls enter webmail Email ID. eg. info@example.com</small>
	    </div>

	</div>

	<div class="row mg_tp_20">
		<div class="col-md-2 text-right"><label for="tax_type">Tax Pay Type</label></div> 
        <div class="col-sm-4 ">
		    <select name="tax_type1" id="tax_type1" title="Select Tax Pay">
		    	<?php	if($sq_settings['tax_type']!=""){ ?>
		        <option value="<?= $sq_settings['tax_type'] ?>"><?= $sq_settings['tax_type'] ?></option>
		        <?php } ?>
		        <option value="">Select Tax Type</option>
		        <option value="Monthly">Monthly</option> 
		        <option value="Quarterly">Quarterly</option>
		        <option value="Yearly">Yearly</option>
		    </select>
	    </div>
	    <div class="col-md-2 text-right"><label for="tax_pay_date">Tax Pay Date</label></div> 
        <div class="col-sm-4 ">
        	<input type="text" id="tax_pay_date" name="tax_pay_date" placeholder="Tax Pay Date" title="Tax Pay Date" value="<?= get_date_user($sq_settings['tax_pay_date']) ?>">
	    </div>

	</div>

	<div class="row mg_tp_20">
		<div class="col-md-2 text-right"><label for="tax_type">Credit Card Charges</label></div> 
        <div class="col-sm-4 ">
		   <input type="text" id="credit_card" name="credit_card" onchange="validate_balance(this.id)" placeholder="Credit Card(%)" value="<?= $sq_settings['credit_card_charges'] ?>" title="Credit Card(%)">
	    </div>

		<div class="col-md-2 text-right"><label for="pdf_upload_btn">Cancellation Policy</label></div> 
			<?php
			if($sq_settings['policy_url'] != ''){
				$url = explode('uploads', $sq_settings['policy_url']);
				$url1 = BASE_URL.'uploads'.$url[1];	
				$text = "Uploaded";
			}	
			else{
				$text = "PDF";
			}			
			?>
		<div class="col-md-4">     

          	<div class="div-upload">

          	<div id="pdf_upload_btn" class="upload-button1"><span><?= $text ?></span></div>

          	<span id="id_proof_status" ></span>

         	<ul id="files"></ul>

          	<input type="hidden" id="pdf_upload_url" name="pdf_upload_url" value="<?= $sq_settings['policy_url'] ?>">

            </div>  

		</div>



		

	</div>

</div>



<div class="panel panel-default panel-body app_panel_style feildset-panel mg_bt_30">

	<legend>Bank Details</legend>

	<div class="row mg_tp_20">
		<div class="col-md-2 text-right"><label for="bank_name">Bank Name</label></div>

		<div class="col-md-4">

			<input type="text" id="bank_name" name="bank_name" placeholder="Bank Name" title="Bank Name" value="<?= $sq_settings['bank_name'] ?>">

		</div>


		<div class="col-md-2 text-right"><label for="acc_name">Account Name</label></div>

		<div class="col-md-4">

			<input type="text" id="acc_name" name="acc_name" placeholder="Account Name" title="Account Name" value="<?= $sq_settings['acc_name'] ?>">

		</div>

	</div>

	<div class="row mg_tp_20">

	    <div class="col-md-2 text-right"><label for="bank_acc_no">Bank A/c No</label></div>

		<div class="col-md-4">

			<input type="text" id="bank_acc_no" name="bank_acc_no"  onchange="validate_accountNo(this.id);" placeholder="Bank A/c No" title="Bank A/c No" value="<?= $sq_settings['bank_acc_no'] ?>">

		</div>
		

		<div class="col-md-2 text-right"><label for="bank_branch_name">Branch Name</label></div>

		<div class="col-md-4">

			<input type="text" id="bank_branch_name" name="bank_branch_name"  onchange="validate_branch(this.id);" placeholder="Branch Name" title="Branch Name" value="<?= $sq_settings['bank_branch_name'] ?>">

		</div>		



	</div>

	<div class="row mg_tp_20">

		
		<div class="col-md-2 text-right"><label for="bank_ifsc_code">IFSC Code</label></div>

		<div class="col-md-4">

			<input type="text" id="bank_ifsc_code" name="bank_ifsc_code" onchange="validate_IFSC(this.id);" placeholder="IFSC Code" title="IFSC Code" value="<?= $sq_settings['bank_ifsc_code'] ?>" >

		</div>

		<div class="col-md-2 text-right"><label for="bank_swift_code">Swift Code</label></div>

		<div class="col-md-4">

			<input type="text" id="bank_swift_code" name="bank_swift_code" onchange="validate_IFSC(this.id);" placeholder="Swift Code" title="Swift Code" value="<?= $sq_settings['bank_swift_code'] ?>">

		</div>



	</div>

	



</div>

<div class="row text-center mg_tp_20">

	<div class="col-md-12">

		<button class="btn btn-sm btn-success" id="setting_save"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>

	</div>

</div>



</form>

<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>

<script src="<?= BASE_URL ?>js/ajaxupload.3.5.js"></script>

<script type="text/javascript">

$('#tax_pay_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
$('#state').select2();
upload_can_policy_pdf();

function upload_can_policy_pdf()
{
	var btnUpload=$('#pdf_upload_btn');
	var status = $("#pdf_upload_url").val();
	var text = (status == '')?'PDF':'Uploaded';
    $(btnUpload).find('span').text(text);


    new AjaxUpload(btnUpload, {

      action: 'basic_info/upload_pdf.php',

      name: 'uploadfile',

      onSubmit: function(file, ext){  



        if (! (ext && /^(pdf)$/.test(ext))){ 

         error_msg_alert('Only PDF files are allowed');

         return false;

        }

        $(btnUpload).find('span').text('Uploading...');

      },

      onComplete: function(file, response){

        if(response==="error"){          

          error_msg_alert("File is not uploaded.");           

          $(btnUpload).find('span').text('Upload PDF');

        }else

        { 

          if(response=="error1")

          {

            $(btnUpload).find('span').text('PDF');

            error_msg_alert('Maximum size exceeds');

            return false;

          }else

          {

            $(btnUpload).find('span').text('Uploaded');

            $("#pdf_upload_url").val(response);

             

          }

        }

      }

    });

}

 

$(function(){

	$('#frm_basic_info').validate({

		rules:{
				app_name : { required: true },

				state : { required : true },

		},

		submitHandler:function(form){

			var app_version = $('#app_version').val();

			var app_email_id = $('#app_email_id').val();

			var backoffice_email_id = $('#backoffice_email_id').val();

			var app_contact_no = $('#app_contact_no').val();

			var app_landline_no = $('#app_landline_no').val();

			var app_address = $('#app_address').val();

			var app_name = $('#app_name').val();

			var bank_acc_no = $('#bank_acc_no').val();

			var bank_name = $('#bank_name').val();
			var acc_name = $('#acc_name').val();

			var bank_branch_name = $('#bank_branch_name').val();

			var bank_swift_code =$('#bank_swift_code').val();

			var bank_ifsc_code = $('#bank_ifsc_code').val();

		 	var tax_name = $('#tax_name').val();

		 	var app_website = $('#app_website').val();

		 	var cin_no = $('#cin_no').val();

		 	var service_tax_no = $('#service_tax_no').val();

		 	var pdf_upload_url = $('#pdf_upload_url').val();
		 	var state = $('#state').val(); 
		 	var acc_email = $('#acc_email').val(); 
		 	var tax_type = $('#tax_type1').val();
		 	var tax_pay_date = $('#tax_pay_date').val();
		 	var credit_card =  $('#credit_card').val(); 
		 	
			var base_url = $('#base_url').val();

			$('#setting_save').button('loading');

			$('#vi_confirm_box').vi_confirm_box({

			    callback: function(data1){

			        if(data1=="yes"){

			          $.ajax({

			          	type:'post',

			          	url:base_url+'controller/app_settings/setting/app_basic_info_save.php',

			          	data:{ app_version : app_version, app_email_id : app_email_id, backoffice_email_id : backoffice_email_id, app_contact_no : app_contact_no, app_landline_no : app_landline_no, service_tax_no : service_tax_no,tax_name : tax_name, app_address : app_address, app_website : app_website, app_name : app_name, bank_acc_no : bank_acc_no, bank_name : bank_name, bank_branch_name : bank_branch_name, bank_ifsc_code : bank_ifsc_code, bank_swift_code : bank_swift_code, cin_no : cin_no, pdf_upload_url : pdf_upload_url, state : state,acc_name : acc_name,acc_email : acc_email, tax_type : tax_type, tax_pay_date : tax_pay_date, credit_card : credit_card},

			          	success:function(result){

			          		msg_popup_reload(result);

							$('#setting_save').button('reset');

			          	}

			          });

			        }

			      }

			});	



			return false;			

		}

	});

});


</script>