<?php 

$flag = true;

class miscellaneous_master{
public function miscellaneous_master_save()
{
	$row_spec = 'sales';
	$service_tax_no = strtoupper($_POST['service_tax_no']);
	$landline_no = $_POST['landline_no'];
	$alt_email_id = $_POST['alt_email_id'];
	$company_name = $_POST['company_name'];
	$cust_type = $_POST['cust_type'];
    $state = $_POST['state'];
	$username = $contact_no;
	$password = $email_id;

	$customer_id = $_POST['customer_id'];
	$emp_id = $_POST['emp_id'];
	$misc_issue_amount = $_POST['misc_issue_amount'];
	$branch_admin_id = $_POST['branch_admin_id'];
	$service_charge = $_POST['service_charge'];
	$taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax = $_POST['service_tax'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$misc_total_cost = $_POST['misc_total_cost'];
	$narration = $_POST['narration'];
	$services = $_POST['service'];
	
	$due_date = $_POST['due_date'];
	$balance_date = $_POST['balance_date'];

	$payment_date = $_POST['payment_date'];
	$payment_amount = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id = $_POST['transaction_id'];	
	$bank_id = $_POST['bank_id'];

	$first_name_arr = $_POST['first_name_arr'];
	$middle_name_arr = $_POST['middle_name_arr'];
	$last_name_arr = $_POST['last_name_arr'];
	$birth_date_arr = $_POST['birth_date_arr'];
	$adolescence_arr = $_POST['adolescence_arr'];
	$visa_type_arr = $_POST['visa_type_arr'];
	$passport_id_arr = $_POST['passport_id_arr'];
	$issue_date_arr = $_POST['issue_date_arr'];
	$expiry_date_arr = $_POST['expiry_date_arr'];

	$payment_date = date('Y-m-d', strtotime($payment_date));
	$balance_date = date("Y-m-d", strtotime($balance_date));
	$due_date = date("Y-m-d", strtotime($due_date));

	if($payment_mode == 'Cheque'){ 
		$clearance_status = "Pending"; } 
	else {  $clearance_status = ""; }	

	$financial_year_id = $_SESSION['financial_year_id'];	
	begin_t();

    //Get Customer id
    if($customer_id == '0'){
    	$sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
	    $customer_id = $sq_max['max'];
    }
    
    //visa save
	$sq_max = mysql_fetch_assoc(mysql_query("select max(misc_id) as max from miscellaneous_master"));
	$misc_id = $sq_max['max'] + 1;

	$sq_visa = mysql_query("insert into miscellaneous_master (misc_id, customer_id,branch_admin_id,financial_year_id, misc_issue_amount, service_charge, taxation_type, taxation_id, service_tax, service_tax_subtotal, misc_total_cost, created_at, due_date,emp_id,narration,service) values ('$misc_id', '$customer_id', '$branch_admin_id','$financial_year_id', '$misc_issue_amount', '$service_charge', '$taxation_type', '$taxation_id', '$service_tax', '$service_tax_subtotal', '$misc_total_cost', '$balance_date', '$due_date', '$emp_id','$narration','$services')");
	
	
	if(!$sq_visa){
		rollback_t();
		echo "error--Sorry Miscellaneous information not saved successfully!";
		exit;
	}
	else{

		for($i=0; $i<sizeof($first_name_arr); $i++){

			$sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from miscellaneous_master_entries"));
			$entry_id = $sq_max['max'] + 1;

			$birth_date_arr[$i] = get_date_db($birth_date_arr[$i]);
			$issue_date_arr[$i] = get_date_db($issue_date_arr[$i]);
			$expiry_date_arr[$i] = get_date_db($expiry_date_arr[$i]);

			$sq_entry = mysql_query("insert into miscellaneous_master_entries(entry_id, misc_id, first_name, middle_name, last_name, birth_date, adolescence, passport_id, issue_date, expiry_date) values('$entry_id', '$misc_id', '$first_name_arr[$i]', '$middle_name_arr[$i]', '$last_name_arr[$i]', '$birth_date_arr[$i]', '$adolescence_arr[$i]',  '$passport_id_arr[$i]', '$issue_date_arr[$i]', '$expiry_date_arr[$i]')");

			if(!$sq_entry){

				$GLOBALS['flag'] = false;
				echo "error--Some Miscellaneous entries are not saved!";
				//exit;
			}

		}


		$sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from miscellaneous_payment_master"));
		$payment_id = $sq_max['max'] + 1;

		$sq_payment = mysql_query("insert into miscellaneous_payment_master (payment_id, misc_id, financial_year_id, branch_admin_id,  payment_date, payment_amount, payment_mode, bank_name, transaction_id, bank_id, clearance_status) values ('$payment_id', '$misc_id', '$financial_year_id', '$branch_admin_id', '$payment_date', '$payment_amount', '$payment_mode', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status') ");
		if(!$sq_payment){
			$GLOBALS['flag'] = false;
			echo "error--Sorry, Payment not saved!";
		}

		//Update customer credit note balance
		$payment_amount1 = $payment_amount;
		$sq_credit_note = mysql_query("select * from credit_note_master where customer_id='$customer_id'");
		$i=0;
		while($row_credit = mysql_fetch_assoc($sq_credit_note)) 
		{	
			if($row_credit['payment_amount'] <= $payment_amount1 && $payment_amount1 != '0'){		
				$payment_amount1 = $payment_amount1 - $row_credit['payment_amount'];
				$temp_amount = 0;
			}
			else{
				$temp_amount = $row_credit['payment_amount'] - $payment_amount1;
				$payment_amount1 = 0;
			}
			$sq_credit = mysql_query("update credit_note_master set payment_amount ='$temp_amount' where id='$row_credit[id]'");
			
		}
		

		//Finance save
    	$this->finance_save($misc_id, $payment_id,$row_spec, $branch_admin_id);

    	//Bank and Cash Book Save
    	$this->bank_cash_book_save($misc_id, $payment_id, $branch_admin_id);

    	



		if($GLOBALS['flag']){



			commit_t();

			

			//Visa Booking email send
			$sq_cms_count = mysql_num_rows(mysql_query("select * from cms_master_entries where id='11' and active_flag='Active'"));
          	if($sq_cms_count != '0'){
				$this->miscellaneous_booking_email_send($misc_id);
			}
			$this->booking_sms($misc_id, $customer_id, $balance_date);


		//Visa payment email send
		$visa_payment_master  = new miscellaneous_payment_master;

		$visa_payment_master->payment_email_notification_send($misc_id, $payment_amount, $payment_mode, $payment_date);			

		//Visa payment sms send
		if($payment_amount != 0){
			$visa_payment_master->payment_sms_notification_send($misc_id, $payment_amount, $payment_mode);
		}

			echo "Miscellaneous Booking has been successfully saved.";
			exit;	
		}

		else{

			rollback_t();

			exit;

		}
	}

}


public function finance_save($misc_id, $payment_id, $row_spec,$branch_admin_id)
{
  $customer_id = $_POST['customer_id'];
	$misc_issue_amount = $_POST['misc_issue_amount'];
	$service_charge = $_POST['service_charge'];
	$taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax = $_POST['service_tax'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$misc_total_cost = $_POST['misc_total_cost'];
	$payment_date = $_POST['payment_date'];
	$payment_amount1 = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id1 = $_POST['transaction_id'];	
	$bank_id1 = $_POST['bank_id'];	
	$booking_date = $_POST['balance_date'];

	$booking_date = date("Y-m-d", strtotime($booking_date));
	$payment_date1 = date('Y-m-d', strtotime($payment_date));	
	$year1 = explode("-", $booking_date);
	$yr1 =$year1[0];
	$year2 = explode("-", $payment_date1);
	$yr2 =$year2[0];

	$misc_sale_amount = $misc_issue_amount + $service_charge;
	$balance_amount = $misc_total_cost - $payment_amount1;
	
	//Get Customer id
    if($customer_id == '0'){
    	$sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
	    $customer_id = $sq_max['max'];
    }

    //Getting customer Ledger
	$sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
	$cust_gl = $sq_cust['ledger_id'];

    //Getting cash/Bank Ledger
    if($payment_mode == 'Cash') {  $pay_gl = 20; }
    else{ 
	    $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id1' and user_type='bank'"));
	    $pay_gl = $sq_bank['ledger_id'];
     } 

    global $transaction_master;

    ////////////Sales/////////////

    $module_name = "Miscellaneous Booking";
    $module_entry_id = $misc_id;
    $transaction_id = "";
    $payment_amount = $misc_sale_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_misc_booking_id($misc_id,$yr1), $booking_date, $misc_sale_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Miscellaneous Sales');
    $gl_id = 169;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    /////////Tax Amount/////////
    tax_reflection_update('Miscellaneous Booking',$service_tax_subtotal,$taxation_type,$misc_id,get_misc_booking_id($misc_id,$yr1),$booking_date, $customer_id, $row_spec,$branch_admin_id);

    //////Payment Amount///////
    $module_name = "Miscellaneous Booking";
    $module_entry_id = $misc_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_sales_particular(get_misc_booking_id($misc_id,$yr2), $payment_date1, $payment_amount1, $customer_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $pay_gl;
    $payment_side = "Debit";
    $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    ////////Balance Amount//////
    $module_name = "Miscellaneous Booking";
    $module_entry_id = $misc_id;
    $transaction_id = "";
    $payment_amount = $balance_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_misc_booking_id($misc_id,$yr1), $booking_date, $balance_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Miscellaneous Sales');
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

}



public function bank_cash_book_save($misc_id, $payment_id,$branch_admin_id)
{
	global $bank_cash_book_master;

	$customer_id = $_POST['customer_id'];
	$payment_date = $_POST['payment_date'];
	$payment_amount = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id = $_POST['transaction_id'];	
	$bank_id = $_POST['bank_id'];
	$payment_date = date('Y-m-d', strtotime($payment_date));	
	$year1 = explode("-", $payment_date);
	$yr1 =$year1[0];

	//Get Customer id
	if($customer_id == '0'){
		$sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
		$customer_id = $sq_max['max'];
	}

	$module_name = "Miscellaneous Booking";
	$module_entry_id = $payment_id;
	$payment_date = $payment_date;
	$payment_amount = $payment_amount;
	$payment_mode = $payment_mode;
	$bank_name = $bank_name;
	$transaction_id = $transaction_id;
	$bank_id = $bank_id;
	$particular = get_sales_paid_particular(get_misc_booking_payment_id($payment_id,$yr1), $payment_date, $payment_amount, $customer_id, $payment_mode, get_misc_booking_id($misc_id,$yr1));
	$clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
	$payment_side = "Credit";
	$payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";

	$bank_cash_book_master->bank_cash_book_master_save($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type,$branch_admin_id);
}


public function booking_sms($booking_id, $customer_id, $created_at){

    global $model, $app_name;
    $sq_customer_info = mysql_fetch_assoc(mysql_query("select contact_no from customer_master where customer_id='$customer_id'"));
    $mobile_no = $sq_customer_info['contact_no'];
    
    $date = $created_at;
    $yr = explode("-", $date);
	$yr1 =$yr[0];
	
    $message = 'Thank you for booking with '.$app_name.'. Booking No : '.get_misc_booking_id($booking_id,$yr1).'  Date :'.get_date_user($created_at);

    $model->send_message($mobile_no, $message);  
}
public function miscellaneous_master_update()
{
	$row_spec = "sales";
	$misc_id = $_POST['misc_id'];
	$customer_id = $_POST['customer_id'];
	$misc_issue_amount = $_POST['misc_issue_amount'];
	$service_charge = $_POST['service_charge'];
	$taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax = $_POST['service_tax'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$misc_total_cost = $_POST['misc_total_cost'];
	$due_date1 = $_POST['due_date1'];
	$balance_date1 = $_POST['balance_date1'];
	$first_name_arr = $_POST['first_name_arr'];
	$middle_name_arr = $_POST['middle_name_arr'];
	$last_name_arr = $_POST['last_name_arr'];
	$birth_date_arr = $_POST['birth_date_arr'];
	$adolescence_arr = $_POST['adolescence_arr'];
	$passport_id_arr = $_POST['passport_id_arr'];
	$issue_date_arr = $_POST['issue_date_arr'];
	$expiry_date_arr = $_POST['expiry_date_arr'];
	$entry_id_arr = $_POST['entry_id_arr'];
	$serv = $_POST['service'];
	//$services = implode(', ',$serv);
	$narration = $_POST['narration'];
	$sq_visa_info = mysql_fetch_assoc(mysql_query("select * from miscellaneous_master where misc_id='$misc_id'"));

	$issue_date = date('Y-m-d', strtotime($issue_date));
	$due_date1 = date('Y-m-d',strtotime($due_date1));
	$balance_date1 = date('Y-m-d',strtotime($balance_date1));

	begin_t();
	$sq_visa = mysql_query("update miscellaneous_master set customer_id='$customer_id', misc_issue_amount='$misc_issue_amount', service_charge='$service_charge', taxation_type='$taxation_type', taxation_id='$taxation_id', service_tax='$service_tax', service_tax_subtotal='$service_tax_subtotal', misc_total_cost='$misc_total_cost', due_date='$due_date1',created_at='$balance_date1',narration='$narration',service='$serv' where misc_id='$misc_id'");
	
	if(!$sq_visa){

		rollback_t();

		echo "error--Sorry, Miscellaneous information not update successfully!";

		exit;

	}

	else{		



		for($i=0; $i<sizeof($first_name_arr); $i++){


			$birth_date_arr[$i] = get_date_db($birth_date_arr[$i]);
			$issue_date_arr[$i] = get_date_db($issue_date_arr[$i]);
			$expiry_date_arr[$i] = get_date_db($expiry_date_arr[$i]);

			if($entry_id_arr[$i]==""){

				$sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from miscellaneous_master_entries"));

				$entry_id = $sq_max['max'] + 1;

				$sq_entry = mysql_query("insert into miscellaneous_master_entries(entry_id, misc_id, first_name, middle_name, last_name, birth_date, adolescence,passport_id, issue_date, expiry_date) values('$entry_id', '$misc_id', '$first_name_arr[$i]', '$middle_name_arr[$i]', '$last_name_arr[$i]', '$birth_date_arr[$i]', '$adolescence_arr[$i]', '$passport_id_arr[$i]', '$issue_date_arr[$i]', '$expiry_date_arr[$i]')");

				if(!$sq_entry){

					$GLOBALS['flag'] = false;

					echo "error--Some Miscellaneous entries are not saved!";

					//exit;

				}	

			}

			else{

				$sq_entry = mysql_query("update miscellaneous_master_entries set misc_id='$misc_id', first_name='$first_name_arr[$i]', middle_name='$middle_name_arr[$i]', last_name='$last_name_arr[$i]', birth_date='$birth_date_arr[$i]', adolescence='$adolescence_arr[$i]', passport_id='$passport_id_arr[$i]', issue_date='$issue_date_arr[$i]', expiry_date='$expiry_date_arr[$i]' where entry_id='$entry_id_arr[$i]'");
			
				if(!$sq_entry){

					$GLOBALS['flag'] = false;

					echo "error--Some Miscellaneous entries are not updated!";

					//exit;

				}	



			}



			



		}



		//Finance update

		$this->finance_update($sq_visa_info,$row_spec);

		if($GLOBALS['flag']){

			commit_t();

			echo "Miscellaneous Booking has been successfully updated.";

			exit;	

		}

		else{

			rollback_t();

			exit;

		}

		

	}

}


public function finance_update($sq_visa_info, $row_spec)
{
	$misc_id = $_POST['misc_id'];
	$customer_id = $_POST['customer_id'];
	$misc_issue_amount = $_POST['misc_issue_amount'];
	$service_charge = $_POST['service_charge'];
	$taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax = $_POST['service_tax'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$misc_total_cost = $_POST['misc_total_cost'];
	$balance_date1 = $_POST['balance_date1'];
	$created_at = date('Y-m-d',strtotime($balance_date1));

	global $transaction_master;

    $misc_sale_amount = $misc_issue_amount + $service_charge;
    //get total payment against visa id
    $sq_visa = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as payment_amount from miscellaneous_payment_master where misc_id='$misc_id'"));
	$balance_amount = $misc_total_cost - $sq_visa['payment_amount'];

    //Getting customer Ledger
	$sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
	$cust_gl = $sq_cust['ledger_id'];

	
    ////////////Sales/////////////
    $module_name = "Miscellaneous Booking";
    $module_entry_id = $misc_id;
    $transaction_id = "";
    $payment_amount = $misc_sale_amount;
    $payment_date = $created_at;
    $payment_particular = get_sales_particular(get_misc_booking_id($misc_id,$yr1), $created_at, $misc_sale_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Miscellaneous Sales');
    $old_gl_id = $gl_id = 169;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

    /////////Tax Amount/////////
    tax_reflection_update('Miscellaneous Booking',$service_tax_subtotal,$taxation_type,$misc_id,get_misc_booking_id($misc_id,$yr1),$created_at, $customer_id, $row_spec);

    ////////Balance Amount//////
    $module_name = "Miscellaneous Booking";
    $module_entry_id = $misc_id;
    $transaction_id = "";
    $payment_amount = $balance_amount;
    $payment_date = $created_at;
    $payment_particular = get_sales_particular(get_misc_booking_id($misc_id,$yr1), $created_at, $balance_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Miscellaneous Sales');
    $old_gl_id = $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);
}



public function miscellaneous_booking_email_send($misc_id)

{

	global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;

	global $app_name;

	$link = BASE_URL.'view/customer';



	$sq_visa = mysql_fetch_assoc(mysql_query("select * from miscellaneous_master where misc_id='$misc_id'"));
	 $booking_date = $sq_visa['created_at'];
	 $yr = explode("-", $booking_date);
	 $year =$yr[0];
	$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_visa[customer_id]'"));

	$password= $sq_customer['email_id'];

	$username =$sq_customer['contact_no'];
	$email_id = $sq_customer['email_id'];

	$customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];

	$subject = "Booking confirmation acknowledgement! ( ".get_misc_booking_id($misc_id,$year) . ' )';

	$content = '

		<tr>
			<td>
				<table style="width:100%">	

					<tr>

						<td>
							
							<p style="line-height:24px">

								<span style="font-weight: bold"> Booking ID : </span> '.get_misc_booking_id($misc_id,$year).'

							</p>

						</td>

					</tr>

					<tr>

						<td>

							<table style="width:100%">

								<tr>

									<td>							

										<p style="line-height:24px">

											Please Find below Login Details:-

										</p>

									</td>

								</tr>

								<tr>

									<td>'.mail_login_box($username, $password, $link).'</td>

								</tr>

							</table>

						</td>

					</tr>

				</table>
			</td>
		</tr>

	';



	global $model,$backoffice_email_id;

	$model->app_email_send('101',$email_id, $content,$subject);
	$model->app_email_send('101',$backoffice_email_id, $content,$subject);

}

public function employee_sign_up_mail($first_name, $last_name, $username, $password, $email_id)
{
  global $app_email_id, $app_name, $app_contact_no, $admin_logo_url, $app_website;
  global $mail_em_style, $mail_em_style1, $mail_font_family, $mail_strong_style, $mail_color;
   $link = BASE_URL.'view/customer';
  $content = '
  <tr>
    <td colspan="2">
      <table style="padding:0 30px">
        <tr>
          <td colspan="2">
            '.mail_login_box($username, $password, $link).'
          </td>
        </tr>
      </table>  
      </td>
    </tr>
  ';
  $subject ='Welcome aboard!';
  global $model;
   $model->app_email_send('2',$email_id, $content,$subject,'1');
}



}

?>