<?php 

$flag = true;

class booking_master{



public function booking_save()

{
    $row_spec = 'sales';
	$customer_id = $_POST['customer_id'];
    $emp_id = $_POST['emp_id'];
	$branch_admin_id = $_POST['branch_admin_id'];	

	$basic_cost = $_POST['basic_cost'];

	$service_charge = $_POST['service_charge'];

    $taxation_type = $_POST['taxation_type'];

	$taxation_id = $_POST['taxation_id'];

	$service_tax = $_POST['service_tax'];

	$service_tax_subtotal = $_POST['service_tax_subtotal'];

	$net_total = $_POST['net_total'];
    $balance_date1 = $_POST['balance_date'];


	$payment_date = $_POST['payment_date'];

	$payment_amount = $_POST['payment_amount'];

	$payment_mode = $_POST['payment_mode'];

	$bank_name = $_POST['bank_name'];

	$transaction_id = $_POST['transaction_id'];

	$bank_id = $_POST['bank_id'];



    $company_name_arr = $_POST['company_name_arr'];

    $bus_type_arr = $_POST['bus_type_arr'];

    $bus_type_new_arr = $_POST['bus_type_new_arr'];

    $pnr_no_arr = $_POST['pnr_no_arr'];

    $origin_arr = $_POST['origin_arr'];

    $destination_arr = $_POST['destination_arr'];

    $date_of_journey_arr = $_POST['date_of_journey_arr'];

    $reporting_time_arr = $_POST['reporting_time_arr'];

    $boarding_point_access_arr = $_POST['boarding_point_access_arr'];



	$journey_date = get_datetime_db($journey_date);

	$payment_date = get_date_db($payment_date);

	$balance_date = get_date_db($balance_date1);


    if($payment_mode=="Cheque"){ 
        $clearance_status = "Pending"; } 
    else {  $clearance_status = ""; }   



    $financial_year_id = $_SESSION['financial_year_id'];



	//**Starting Transaction 

	begin_t();

    //Get Customer id
    if($customer_id == '0'){
        $sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
        $customer_id = $sq_max['max'];
    }
    
	//**Saving Booking

	$sq_max = mysql_fetch_assoc(mysql_query("select max(booking_id) as max from bus_booking_master"));

	$booking_id = $sq_max['max'] + 1;



	$sq_booking = mysql_query("insert into bus_booking_master (booking_id, customer_id, branch_admin_id,financial_year_id, basic_cost, service_charge, taxation_type, taxation_id, service_tax, service_tax_subtotal, net_total, created_at, emp_id) values ('$booking_id', '$customer_id', '$branch_admin_id','$financial_year_id', '$basic_cost', '$service_charge', '$taxation_type', '$taxation_id', '$service_tax', '$service_tax_subtotal', '$net_total', '$balance_date', '$emp_id')");

	if(!$sq_booking){

		$GLOBALS['flag'] = false;

		echo "error--Booking not saved!";

	}



    //**Booking Entries

    for($i=0; $i<sizeof($company_name_arr); $i++){



        $date_of_journey_arr[$i] = get_datetime_db($date_of_journey_arr[$i]);



        $sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from bus_booking_entries"));

        $entry_id = $sq_max['max'] + 1;    



        $sq_entry = mysql_query("insert into bus_booking_entries(entry_id, booking_id, company_name, seat_type,bus_type, pnr_no, origin, destination, date_of_journey, reporting_time, boarding_point_access) values ('$entry_id', '$booking_id', '$company_name_arr[$i]', '$bus_type_arr[$i]','$bus_type_new_arr[$i]', '$pnr_no_arr[$i]', '$origin_arr[$i]', '$destination_arr[$i]', '$date_of_journey_arr[$i]', '$reporting_time_arr[$i]', '$boarding_point_access_arr[$i]')");

        if(!$sq_entry){

            $GLOBALS['flag'] = false;

            echo "error--Some entries not saved!";

        }



    }



	//**Saving Payment

	$sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from bus_booking_payment_master"));

    $payment_id = $sq_max['max'] + 1;



    $sq_payment = mysql_query("insert into bus_booking_payment_master (payment_id, booking_id, financial_year_id, branch_admin_id, payment_date, payment_amount, payment_mode, bank_name, transaction_id, bank_id, clearance_status) values ('$payment_id', '$booking_id', '$financial_year_id', '$branch_admin_id', '$payment_date', '$payment_amount', '$payment_mode', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status') ");

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

    $this->finance_save($booking_id, $payment_id, $row_spec, $branch_admin_id);



    //Bank and Cash Book Save

    $this->bank_cash_book_save($booking_id, $payment_id, $branch_admin_id);



    //**Ending Transaction

    if($GLOBALS['flag']){

        commit_t();
        $this->booking_mail($booking_id, $customer_id);
        $this->booking_sms($booking_id, $customer_id, $balance_date);

        // payment email send
        $payment_master  = new payment_master;
        $payment_master->payment_email_notification_send($booking_id, $payment_amount, $payment_mode, $payment_date);

        // payment sms send
        if($payment_amount != 0){
            $payment_master->payment_sms_notification_send($booking_id, $payment_amount, $payment_mode);
        }

        echo "Bus Booking has been successfully saved.";
        exit;
    }
    else{
        rollback_t();
        exit;
    }
}

public function booking_sms($booking_id, $customer_id, $created_at){

    global $model, $app_name;
    $sq_customer_info = mysql_fetch_assoc(mysql_query("select contact_no from customer_master where customer_id='$customer_id'"));
    $mobile_no = $sq_customer_info['contact_no'];
    
    $date = $created_at;
    $yr = explode("-", $date);
	$yr1 =$yr[0];
	
    $message = 'Thank you for booking with '.$app_name.'. Booking No : '.get_bus_booking_id($booking_id,$yr1).'  Date :'.get_date_user($created_at);

    $model->send_message($mobile_no, $message);  
}


public function booking_mail($booking_id, $customer_id)

{

    global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;

    global $app_name;

    $sq_visa = mysql_fetch_assoc(mysql_query("select * from bus_booking_master where booking_id='$booking_id'"));
    $date = $sq_visa['created_at'];
    $yr = explode("-", $date);
    $year =$yr[0];

    $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));

    $email_id = $sq_customer['email_id'];

    $customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];

    $password= $email_id;

    $username =$sq_customer['contact_no'];

    $link = BASE_URL.'view/customer';

    $subject = 'Booking confirmation acknowledgement! ( '.get_bus_booking_id($booking_id,$year). ' )';

    $content = '

        <tr>

            <td>
                <table style="width:100%">  

                    <tr>

                      <td style="padding-top:15px"><em style="'.$mail_em_style.'"><strong style="'.$mail_strong_style.'">Booking ID:</strong><span style="color:#fff;"> '.get_bus_booking_id($booking_id,$year).'</span></em></td>

                    </tr>

                    <tr>

                        <td>

                            <table style="width:100%">

                                <tr>

                                    <td>

                                        '.mail_login_box($username, $password, $link).'

                                    </td> 

                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    ';

    global $model,$backoffice_email_id;
    $model->app_email_send('19',$email_id, $content, $subject);
    $model->app_email_send('19',$backoffice_email_id, $content, $subject);
}


public function finance_save($booking_id, $payment_id, $row_spec, $branch_admin_id){
    $customer_id = $_POST['customer_id'];
	$basic_cost = $_POST['basic_cost'];
	$service_charge = $_POST['service_charge'];
    $taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax = $_POST['service_tax'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$net_total = $_POST['net_total'];
	$payment_date = $_POST['payment_date'];
	$payment_amount1 = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id1 = $_POST['transaction_id'];	
	$bank_id1 = $_POST['bank_id'];	
    $booking_date = $_POST['balance_date'];

    $booking_date = date('Y-m-d', strtotime($booking_date));
	$payment_date1 = date('Y-m-d', strtotime($payment_date));
	$year1 = explode("-", $booking_date);
	$yr1 =$year1[0];
	$year2 = explode("-", $payment_date1);
	$yr2 =$year2[0];

    $bus_sale_amount = $basic_cost + $service_charge;
    $balance_amount = $net_total - $payment_amount1;

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
    global $fiance_vars;

    ////////////Sales/////////////

    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $bus_sale_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_bus_booking_id($booking_id,$yr1), $booking_date, $bus_sale_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Bus Sales');
    $gl_id = 10;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    /////////Tax Amount/////////
    tax_reflection_update('Bus Booking',$service_tax_subtotal,$taxation_type,$booking_id,get_bus_booking_id($booking_id,$yr1),$booking_date, $customer_id, $row_spec,$branch_admin_id);

    //////Payment Amount///////
    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_sales_particular(get_bus_booking_id($booking_id,$yr2), $payment_date1, $payment_amount1, $customer_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $pay_gl;
    $payment_side = "Debit";
    $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    ////////Balance Amount//////
    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $balance_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_bus_booking_id($booking_id,$yr1), $booking_date, $balance_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Bus Sales');
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);


}



public function bank_cash_book_save($booking_id, $payment_id, $branch_admin_id)

{

	global $bank_cash_book_master;



	$customer_id = $_POST['customer_id'];

	$payment_date = $_POST['payment_date'];

	$payment_amount = $_POST['payment_amount'];

	$payment_mode = $_POST['payment_mode'];

	$bank_name = $_POST['bank_name'];

	$transaction_id = $_POST['transaction_id'];	

    $bank_id = $_POST['bank_id'];

	$payment_date1 = date('Y-m-d', strtotime($payment_date));
	$year1 = explode("-", $payment_date1);
	$yr1 =$year1[0];

    //Get Customer id
    if($customer_id == '0'){
      $sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
      $customer_id = $sq_max['max'];
    }

	$module_name = "Bus Booking";

	$module_entry_id = $payment_id;

	$payment_date = $payment_date;

	$payment_amount = $payment_amount;

	$payment_mode = $payment_mode;

	$bank_name = $bank_name;

	$transaction_id = $transaction_id;

	$bank_id = $bank_id;

	$particular = get_sales_paid_particular(get_bus_booking_payment_id($payment_id,$yr1), $payment_date, $payment_amount, $customer_id, $payment_mode, get_bus_booking_id($booking_id,$yr1));

	$clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";

	$payment_side = "Debit";

	$payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";



	$bank_cash_book_master->bank_cash_book_master_save($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type, $branch_admin_id);

}



public function booking_update()

{

    $row_spec = 'sales';

	$booking_id = $_POST['booking_id'];

	$customer_id = $_POST['customer_id'];

	

	$basic_cost = $_POST['basic_cost'];

	$service_charge = $_POST['service_charge'];

    $taxation_type = $_POST['taxation_type'];

	$taxation_id = $_POST['taxation_id'];

	$service_tax = $_POST['service_tax'];

	$service_tax_subtotal = $_POST['service_tax_subtotal'];

	$net_total = $_POST['net_total'];
    $balance_date1 = $_POST['balance_date1'];
    $balance_date1 = get_date_db($balance_date1);

    $company_name_arr = $_POST['company_name_arr'];

    $bus_type_arr = $_POST['bus_type_arr'];

    $bus_type_new_arr = $_POST['bus_type_new_arr'];

    $pnr_no_arr = $_POST['pnr_no_arr'];

    $origin_arr = $_POST['origin_arr'];

    $destination_arr = $_POST['destination_arr'];

    $date_of_journey_arr = $_POST['date_of_journey_arr'];

    $reporting_time_arr = $_POST['reporting_time_arr'];

    $boarding_point_access_arr = $_POST['boarding_point_access_arr'];

    $entry_id_arr = $_POST['entry_id_arr'];



	//**Starting Transaction 

	begin_t();



	$sq_booking_info = mysql_fetch_assoc(mysql_query("select * from bus_booking_master where booking_id='$booking_id'"));



	//**Saving Booking

	$sq_booking = mysql_query("update bus_booking_master set customer_id='$customer_id', basic_cost='$basic_cost', service_charge='$service_charge', taxation_type='$taxation_type', taxation_id='$taxation_id', service_tax='$service_tax', service_tax_subtotal='$service_tax_subtotal', net_total='$net_total',created_at='$balance_date1' where booking_id='$booking_id'");

	if(!$sq_booking){

		$GLOBALS['flag'] = false;

		echo "error--Booking not updated!";

	}



    //**Booking Entries

    for($i=0; $i<sizeof($company_name_arr); $i++){



        $date_of_journey_arr[$i] = get_datetime_db($date_of_journey_arr[$i]);

        if($entry_id_arr[$i] == ""){



            $sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from bus_booking_entries"));

            $entry_id = $sq_max['max'] + 1;    



            $sq_entry = mysql_query("insert into bus_booking_entries(entry_id, booking_id, company_name,seat_type, bus_type, pnr_no, origin, destination, date_of_journey, reporting_time, boarding_point_access) values ('$entry_id', '$booking_id', '$company_name_arr[$i]', '$bus_type_arr[$i]','$bus_type_new_arr[$i]', '$pnr_no_arr[$i]', '$origin_arr[$i]', '$destination_arr[$i]', '$date_of_journey_arr[$i]', '$reporting_time_arr[$i]', '$boarding_point_access_arr[$i]')");

            if(!$sq_entry){

                $GLOBALS['flag'] = false;

                echo "error--Some entries not saved!";

            }    



        }

        else{



            $sq_entry = mysql_query("update bus_booking_entries set company_name='$company_name_arr[$i]', seat_type='$bus_type_arr[$i]',bus_type ='$bus_type_new_arr[$i]', pnr_no='$pnr_no_arr[$i]', origin='$origin_arr[$i]', destination='$destination_arr[$i]', date_of_journey='$date_of_journey_arr[$i]', reporting_time='$reporting_time_arr[$i]', boarding_point_access='$boarding_point_access_arr[$i]' where entry_id='$entry_id_arr[$i]'");

            if(!$sq_entry){

                $GLOBALS['flag'] = false;

                echo "error--Some entries not updated!";

            }



        }



        

    }



	//Finance update

	$this->finance_update($sq_booking_info, $row_spec);



    //**Ending Transaction

    if($GLOBALS['flag']){

        commit_t();

        echo "Bus Booking has been successfully updated.";

        exit;

    }

    else{

        rollback_t();

        exit;

    }





}



public function finance_update($sq_booking_info, $row_spec)
{

	$booking_id = $_POST['booking_id'];
	$customer_id = $_POST['customer_id'];
	$basic_cost = $_POST['basic_cost'];
	$service_charge = $_POST['service_charge'];
    $taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax = $_POST['service_tax'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$net_total = $_POST['net_total'];

    $created_at = $_POST['balance_date1'];
    $booking_date = get_date_db($created_at);
	$year1 = explode("-", $booking_date);
	$yr1 =$year1[0];

	global $transaction_master;

    $bus_sale_amount = $basic_cost + $service_charge;
    //get total payment against bus id
    $sq_bus = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as payment_amount from bus_booking_payment_master where booking_id='$booking_id'"));
    $balance_amount = $net_total - $sq_bus['payment_amount'];

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

    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $bus_sale_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_bus_booking_id($booking_id,$yr1), $booking_date, $bus_sale_amount, $customer_id);
    $old_gl_id = $gl_id = 10;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec);

    /////////Tax Amount/////////
    tax_reflection_update('Bus Booking',$service_tax_subtotal,$taxation_type,$booking_id,get_bus_booking_id($booking_id,$yr1),$booking_date, $customer_id, $row_spec);

    ////////Balance Amount//////
    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $balance_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_bus_booking_id($booking_id,$yr1), $booking_date, $balance_amount, $customer_id);
    $old_gl_id = $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec);  

}
function employee_sign_up_mail($cust_first_name, $cust_last_name, $username, $password, $email_id)
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