<?php 

class app_settings_master{



public function app_basic_info_save()

{

	$app_version = $_POST['app_version'];

	$app_email_id = $_POST['app_email_id'];

	$backoffice_email_id = $_POST['backoffice_email_id'];

	$app_contact_no = $_POST['app_contact_no'];

	$app_landline_no = $_POST['app_landline_no'];

	$tax_name = strtoupper($_POST['tax_name']);

	$service_tax_no = strtoupper($_POST['service_tax_no']);

	$app_address = $_POST['app_address'];

	$app_website = $_POST['app_website'];

	$app_name = $_POST['app_name'];

	$acc_name = $_POST['acc_name'];

	$app_cin = $_POST['cin_no'];

	$bank_acc_no = $_POST['bank_acc_no'];

	$bank_name = $_POST['bank_name'];

	$bank_branch_name = $_POST['bank_branch_name'];

	$bank_ifsc_code = $_POST['bank_ifsc_code'];

	$bank_swift_code = $_POST['bank_swift_code'];

	$cancel_pdf_url = $_POST['pdf_upload_url'];
	$credit_card = $_POST['credit_card'];

	

	$state = $_POST['state'];
	$acc_email = $_POST['acc_email'];
	$tax_type = $_POST['tax_type'];
	$tax_pay_date = $_POST['tax_pay_date'];
	$tax_pay_date1 = get_date_db($tax_pay_date);

 	$sq_app_setting_count = mysql_num_rows(mysql_query("select * from app_settings"));
 	$app_address = addslashes($app_address);	
 	$app_name = addslashes($app_name);

	if($sq_app_setting_count == '0'){
		$sq_max = mysql_fetch_assoc(mysql_query("select max(setting_id) as max from app_settings"));

		$setting_id = $sq_max['max'] + 1;

		$query = "insert into app_settings ( setting_id, app_version, app_email_id,backoffice_email_id, app_contact_no, app_landline_no,  tax_name, service_tax_no, app_address, app_website, app_name,app_cin, bank_acc_no,acc_name, bank_name, bank_branch_name, bank_ifsc_code, bank_swift_code, policy_url , state_id, accountant_email, tax_type, tax_pay_date, credit_card_charges) values ( '$setting_id', '$app_version', '$app_email_id','$backoffice_email_id', '$app_contact_no', '$app_landline_no', '$tax_name', '$service_tax_no', '$app_address', '$app_website', '$app_name','$app_cin', '$bank_acc_no','$acc_name', '$bank_name', '$bank_branch_name', '$bank_ifsc_code', '$bank_swift_code', '$cancel_pdf_url','$state','$acc_email','$tax_type','$tax_pay_date1','$credit_card')";

		$sq_setting = mysql_query($query);

		if($sq_setting){


			echo "App setting has been successfully saved.";

			exit;

		}

		else{

			echo "error--Sorry, Settings are not saved!";

		}

	}

	else{
		
		$query = "update app_settings set app_version = '$app_version', app_email_id = '$app_email_id', app_contact_no = '$app_contact_no', app_landline_no='$app_landline_no', tax_name='$tax_name', service_tax_no = '$service_tax_no', app_address = '$app_address', app_website = '$app_website', app_name = '$app_name',app_cin='$app_cin', bank_acc_no = '$bank_acc_no',acc_name='$acc_name', bank_name='$bank_name', bank_branch_name='$bank_branch_name', bank_ifsc_code='$bank_ifsc_code', bank_swift_code='$bank_swift_code', policy_url='$cancel_pdf_url', backoffice_email_id='$backoffice_email_id', state_id='$state', accountant_email='$acc_email', tax_type='$tax_type', tax_pay_date='$tax_pay_date1',credit_card_charges='$credit_card' where setting_id='1'";
		$sq_setting = mysql_query($query);
	
		if($sq_setting){
		   
			echo "App setting has been successfully updated.";

			exit;

		}

		else{

			echo "error--Sorry, Settings are not saved!";

		}

	}

}

function app_cred_info_save()

{

	$sms_username = $_POST['sms_username'];

	$sms_password = $_POST['sms_password'];

	$app_smtp_status = $_POST['app_smtp_status'];

	$app_smtp_host = $_POST['app_smtp_host'];

	$app_smtp_port = $_POST['app_smtp_port'];

	$app_smtp_password = $_POST['app_smtp_password'];

	$app_smtp_method = $_POST['app_smtp_method'];

	$current_password = $_POST['current_password'];

	$new_password = $_POST['new_password'];

	$re_password = $_POST['re_password'];
	$ip_address = $_POST['ip_address'];



 	$sq_app_setting_count = mysql_num_rows(mysql_query("select * from app_settings"));

	if($sq_app_setting_count==0){



		$sq_max = mysql_fetch_assoc(mysql_query("select max(setting_id) as max from app_settings"));

		$setting_id = $sq_max['max'] + 1;



		$sq_setting = mysql_query("insert into app_settings ( setting_id, sms_username, sms_password,  app_smtp_status, app_smtp_host, app_smtp_port, app_smtp_password, app_smtp_method,ip_addresses ) values ( '$setting_id', '$sms_username', '$sms_password', '$app_smtp_status', '$app_smtp_host', '$app_smtp_port', '$app_smtp_password', '$app_smtp_method','$ip_address')");

				

		if($sq_setting){

			echo "Settings are saved successfully!";

			exit;

		}

		else{

			echo "error--Sorry, Settings are not saved!";

		}



	}

	else{

		

		$sq_setting = mysql_query("update app_settings set sms_username = '$sms_username', sms_password = '$sms_password', app_smtp_status='$app_smtp_status', app_smtp_host='$app_smtp_host', app_smtp_port='$app_smtp_port', app_smtp_password='$app_smtp_password', app_smtp_method='$app_smtp_method',ip_addresses='$ip_address' where setting_id='1'");

		if($new_password !='' and $re_password !=''){

			$sq_password_count = mysql_num_rows(mysql_query("select * from roles where emp_id='0' and password='$current_password'"));

			if($sq_password_count==1){

				if(strcasecmp($new_password, $re_password) == 0){

					$sq_password = mysql_query("update roles set password = '$new_password' where emp_id='0'");
				}

				

			}

			else

			{

				echo "error--Sorry, Current password is incorrect!";

				exit;

			}

	    }

		if($sq_setting){

			echo "Settings are saved successfully!";

			exit;

		}

		else{

 			echo "Settings are not saved!";

		}

	}
}

function app_format_save(){

	$invoice_format_list = $_POST['invoice_format_list'];
	$quot_format = $_POST['quot_format'];
	$image = $_POST['image'];

	$sq_invoice = mysql_query("update generic_count_master set invoice_format='$invoice_format_list' where id='1'");

	if($sq_app_setting_count == '0'){
		$sq_max = mysql_fetch_assoc(mysql_query("select max(setting_id) as max from app_settings"));

		$setting_id = $sq_max['max'] + 1;

		$query = "insert into app_settings ( setting_id, quot_format, quot_img_url) values ( '$setting_id', '$quot_format', '$image')";

		$sq_setting = mysql_query($query);
	}
	else{
		$sq_invoice = mysql_query("update app_settings set quot_format='$quot_format',quot_img_url='$image' where setting_id='1'");
	}


	if($sq_invoice){
		echo "Format Settings are saved successfully!";
		exit;
	}
	else{
		echo "error--Sorry, Format Settings are not saved!";
	}

}




}

