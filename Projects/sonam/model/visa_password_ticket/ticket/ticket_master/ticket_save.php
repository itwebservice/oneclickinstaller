<?php
$flag = true;
class ticket_save{

public function ticket_master_save()
{
	$row_spec = "sales";
	$customer_id = $_POST['customer_id'];	
	$emp_id = $_POST['emp_id'];	
	$tour_type = $_POST['tour_type'];
	$type_of_tour = $_POST['type_of_tour'];
	$branch_admin_id = $_POST['branch_admin_id'];
	$financial_year_id = $_POST['financial_year_id'];

	$adults = $_POST['adults'];
	$childrens = $_POST['childrens'];
	$infant = $_POST['infant'];
	$adult_fair = $_POST['adult_fair'];
	$children_fair = $_POST['children_fair'];
	$infant_fair = $_POST['infant_fair'];
	$basic_cost = $_POST['basic_cost'];
	$basic_cost_markup = $_POST['basic_cost_markup'];
	$basic_cost_discount = $_POST['basic_cost_discount'];
	$yq_tax = $_POST['yq_tax'];
	$yq_tax_markup = $_POST['yq_tax_markup'];
	$yq_tax_discount = $_POST['yq_tax_discount'];
	$g1_plus_f2_tax = $_POST['g1_plus_f2_tax'];
	$service_charge = $_POST['service_charge'];
	$taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax = $_POST['service_tax'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$tds = $_POST['tds'];
	$due_date = $_POST['due_date'];
	$booking_date = $_POST['booking_date'];
	$sup_id = $_POST['sup_id'];
	$ticket_total_cost = $_POST['ticket_total_cost'];

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
	$ticket_no_arr = $_POST['ticket_no_arr'];
	$gds_pnr_arr = $_POST['gds_pnr_arr'];
	$from_city_id_arr = $_POST['from_city_id_arr'];

	$departure_datetime_arr = $_POST['departure_datetime_arr'];
	$to_city_id_arr = $_POST['to_city_id_arr'];
	$arrival_datetime_arr = $_POST['arrival_datetime_arr'];
	$airlines_name_arr = $_POST['airlines_name_arr'];
	$class_arr = $_POST['class_arr'];
	$flight_no_arr = $_POST['flight_no_arr'];
	$airlin_pnr_arr = $_POST['airlin_pnr_arr'];
	$departure_city_arr = $_POST['departure_city_arr'];
	$arrival_city_arr = $_POST['arrival_city_arr'];
	$meal_plan_arr = $_POST['meal_plan_arr'];
	$luggage_arr = $_POST['luggage_arr'];
	$special_note_arr = $_POST['special_note_arr'];

	$due_date = get_date_db($due_date);
	$payment_date = get_date_db($payment_date);
	$booking_date = get_date_db($booking_date);	

	if($payment_mode == "Cheque"){ 
		$clearance_status = "Pending"; } 
	else {  $clearance_status = ""; }	
	$financial_year_id = $_SESSION['financial_year_id'];

	begin_t();
    //Get Customer id
    if($customer_id == '0'){
    	$sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
	    $customer_id = $sq_max['max'];
    }
	//***Booking information
	$sq_max = mysql_fetch_assoc(mysql_query("select max(ticket_id) as max from ticket_master"));
	$ticket_id = $sq_max['max'] + 1;

	$sq_ticket = mysql_query("insert into ticket_master (ticket_id, customer_id, branch_admin_id,financial_year_id, tour_type, type_of_tour, adults, childrens, infant, adult_fair, children_fair, infant_fair, basic_cost, basic_cost_markup, basic_cost_discount, yq_tax, yq_tax_markup, yq_tax_discount, g1_plus_f2_tax, service_charge, taxation_type, taxation_id, service_tax, service_tax_subtotal, tds, due_date, ticket_total_cost, created_at,emp_id,supplier_id) values ('$ticket_id', '$customer_id', '$branch_admin_id','$financial_year_id', '$tour_type', '$type_of_tour', '$adults', '$childrens', '$infant', '$adult_fair', '$children_fair', '$infant_fair', '$basic_cost', '$basic_cost_markup', '$basic_cost_discount', '$yq_tax', '$yq_tax_markup', '$yq_tax_discount', '$g1_plus_f2_tax', '$service_charge', '$taxation_type', '$taxation_id', '$service_tax', '$service_tax_subtotal', '$tds', '$due_date', '$ticket_total_cost', '$booking_date','$emp_id','$sup_id')");

	if(!$sq_ticket){
		$GLOBALS['flag'] = false;
		echo "error--Sorry, information not saved!";
	}

	//***Member information
	for($i=0; $i<sizeof($first_name_arr); $i++){
		$sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from ticket_master_entries"));
		$entry_id = $sq_max['max'] + 1;

		$birth_date_arr[$i] = get_date_db($birth_date_arr[$i]);
		$sq_entry = mysql_query("insert into ticket_master_entries(entry_id, ticket_id, first_name, middle_name, last_name, birth_date, adolescence, ticket_no, gds_pnr) values('$entry_id', '$ticket_id', '$first_name_arr[$i]','$middle_name_arr[$i]','$last_name_arr[$i]', '$birth_date_arr[$i]', '$adolescence_arr[$i]', '$ticket_no_arr[$i]', '$gds_pnr_arr[$i]')");

		if(!$sq_entry){
			$GLOBALS['flag'] = false;
			echo "error--Error in member information!";
		}
	}
	//***Trip information***
	for($i=0; $i<sizeof($departure_datetime_arr); $i++){

		$sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from ticket_trip_entries"));
		$entry_id = $sq_max['max'] + 1;

		$sq_count = mysql_num_rows(mysql_query("select * from ticket_trip_entries where airlin_pnr='$airlin_pnr_arr[$i]' and airlin_pnr!=''"));
		
		if($sq_count!= '0'){
			$GLOBALS['flag'] = false;
			echo "error--Repeated Airline PNR not allowed!";
		}

		$departure_datetime_arr[$i] = get_datetime_db($departure_datetime_arr[$i]);
		$arrival_datetime_arr[$i] = get_datetime_db($arrival_datetime_arr[$i]);

		$special_note1 = addslashes($special_note_arr[$i]);
		$sq_entry = mysql_query("insert into ticket_trip_entries(entry_id, ticket_id, departure_datetime, arrival_datetime, airlines_name, class, flight_no, airlin_pnr, from_city, to_city, departure_city, arrival_city,meal_plan,luggage, special_note) values('$entry_id', '$ticket_id', '$departure_datetime_arr[$i]', '$arrival_datetime_arr[$i]', '$airlines_name_arr[$i]', '$class_arr[$i]', '$flight_no_arr[$i]', '$airlin_pnr_arr[$i]', '$from_city_id_arr[$i]', '$to_city_id_arr[$i]', '$departure_city_arr[$i]', '$arrival_city_arr[$i]', '$meal_plan_arr[$i]', '$luggage_arr[$i]', '$special_note1')");

		if(!$sq_entry){
			$GLOBALS['flag'] = false;
			echo "error--Error in ticket information!";
		}
	}

	//***Payment Information
	$sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from ticket_payment_master"));
	$payment_id = $sq_max['max'] + 1;

	$sq_payment = mysql_query("insert into ticket_payment_master (payment_id, ticket_id, financial_year_id,branch_admin_id, payment_date, payment_amount, payment_mode, bank_name, transaction_id, bank_id, clearance_status) values ('$payment_id', '$ticket_id', '$financial_year_id', '$branch_admin_id', '$payment_date', '$payment_amount', '$payment_mode', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status') ");

	if(!$sq_payment){
		$GLOBALS['flag'] = false;
		echo "error--Sorry, Payment not saved!";
	}
	
	//Update customer credit note balance
	$payment_amount1 = $payment_amount;
	$sq_credit_note = mysql_query("select * from credit_note_master where customer_id='$customer_id'");
	$i=0;
	while($row_credit = mysql_fetch_assoc($sq_credit_note)) {

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
	$this->finance_save($ticket_id, $payment_id, $row_spec, $branch_admin_id);
	//Bank and Cash Book Save
	$this->bank_cash_book_save($ticket_id, $payment_id, $branch_admin_id);

	if($GLOBALS['flag']){

		commit_t();
		//Ticket Booking email send
		$this->ticket_booking_email_send($ticket_id,$payment_amount);
        $this->booking_sms($ticket_id, $customer_id, $booking_date);

		//Ticket payment email send
		$ticket_payment_master  = new ticket_payment_master;
		$ticket_payment_master->payment_email_notification_send($ticket_id, $payment_amount, $payment_mode, $payment_date);

		//Ticket payment sms send
		if($payment_amount != 0){
			$ticket_payment_master->payment_sms_notification_send($ticket_id, $payment_amount, $payment_mode);
		}

		echo "Flight Ticket Booking has been successfully saved.";
		exit;			
	}
	else{
		rollback_t();
		exit;
	}
}



public function finance_save($ticket_id, $payment_id, $row_spec, $branch_admin_id)
{
	$customer_id = $_POST['customer_id'];
	$tour_type = $_POST['tour_type'];
	$basic_cost = $_POST['basic_cost'];
	$basic_cost_markup = $_POST['basic_cost_markup'];
	$basic_cost_discount = $_POST['basic_cost_discount'];
	$yq_tax = $_POST['yq_tax'];
	$yq_tax_markup = $_POST['yq_tax_markup'];
	$yq_tax_discount = $_POST['yq_tax_discount'];
	$g1_plus_f2_tax = $_POST['g1_plus_f2_tax'];
	$service_charge = $_POST['service_charge'];
	$taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax = $_POST['service_tax'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$tds = $_POST['tds'];
	$due_date = $_POST['due_date'];
	$ticket_total_cost = $_POST['ticket_total_cost'];
    $booking_date = $_POST['booking_date'];
	$payment_date = $_POST['payment_date'];
	$payment_amount1 = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id1 = $_POST['transaction_id'];
	$bank_id = $_POST['bank_id'];
	

	$booking_date = date('Y-m-d', strtotime($booking_date));
	$payment_date1 = date('Y-m-d', strtotime($payment_date));
	$year1 = explode("-", $booking_date);
	$yr1 =$year1[0];
	$year2 = explode("-", $payment_date1);
	$yr2 =$year2[0];
		
	$total_sale = $basic_cost + $basic_cost_markup + $yq_tax + $yq_tax_markup + $g1_plus_f2_tax  + $service_charge - $yq_tax_discount;
	$balance_amount = $ticket_total_cost - $payment_amount1;

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
	    $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
	    $pay_gl = $sq_bank['ledger_id'];
     } 

    global $transaction_master;
	$sale_gl = ($tour_type == 'Domestic') ? 50 : 174;

    ////////////Sales/////////////
    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $total_sale;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $booking_date, $total_sale, $customer_id);
    $ledger_particular = get_ledger_particular('To','Flight Ticket Sales');
    $gl_id = $sale_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    /////////Tax Amount/////////
    tax_reflection_update('Air Ticket Booking',$service_tax_subtotal,$taxation_type,$ticket_id,get_ticket_booking_id($ticket_id,$yr1),$booking_date, $customer_id, $row_spec,$branch_admin_id);

    /////////TDS////////
    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $tds;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $booking_date, $tds, $customer_id);
    $ledger_particular = get_ledger_particular('To','Flight Ticket Sales');
    $gl_id = 127;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);


    /////////Discount////////
    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $basic_cost_discount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $booking_date, $basic_cost_discount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Flight Ticket Sales');
    $gl_id = 36;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    //////Payment Amount///////
    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_sales_particular(get_ticket_booking_id($ticket_id,$yr2), $payment_date1, $payment_amount1, $customer_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $pay_gl;
    $payment_side = "Debit";
    $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    ////////Balance Amount//////
    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $balance_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $booking_date, $balance_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Flight Ticket Sales');
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);   
}



public function bank_cash_book_save($ticket_id, $payment_id, $branch_admin_id)

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
	$year2 = explode("-", $payment_date);
	$yr2 =$year2[0];


	//Get Customer id
    if($customer_id == '0'){
    	$sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
	    $customer_id = $sq_max['max'];
    }
    
	$module_name = "Air Ticket Booking";

	$module_entry_id = $payment_id;

	$payment_date = $payment_date;

	$payment_amount = $payment_amount;

	$payment_mode = $payment_mode;

	$bank_name = $bank_name;

	$transaction_id = $transaction_id;

	$bank_id = $bank_id;

	$particular = get_sales_paid_particular(get_ticket_booking_payment_id($payment_id,$yr2), $payment_date, $payment_amount, $customer_id, $payment_mode, get_ticket_booking_id($ticket_id,$yr2));

	$clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";

	$payment_side = "Debit";

	$payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";



	$bank_cash_book_master->bank_cash_book_master_save($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type, $branch_admin_id);



}





public function ticket_booking_email_send($ticket_id,$payment_amount)
{
	global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color,$currency_logo;
	global $app_name,$encrypt_decrypt,$secret_key;

	$link = BASE_URL.'view/customer';

	$sq_ticket = mysql_fetch_assoc(mysql_query("select * from ticket_master where ticket_id='$ticket_id'"));

	$date = $sq_ticket['created_at'];
	$yr = explode("-", $date);
	$year =$yr[0];

	$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_ticket[customer_id]'"));

	$subject = 'Booking confirmation acknowledgement! ( '.get_ticket_booking_id($ticket_id,$year). ' )';
	$customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];

	$username = $encrypt_decrypt->fnDecrypt($sq_customer['contact_no'], $secret_key);
	$password = $encrypt_decrypt->fnDecrypt($sq_customer['email_id'], $secret_key);

	$balance_amount = $sq_ticket[basic_cost] - $payment_amount;

	$flDetails = mysql_query('SELECT * FROM `ticket_trip_entries` where ticket_id = '.$ticket_id);
	$content = '
	<tr>
		<table width="85%" cellspacing="0" cellpadding="5" style="color: #888888;border: 1px solid #888888;margin: 0px auto;margin-top:20px; min-width: 100%;" role="presentation">
			<tr><td style="text-align:left;border: 1px solid #888888;width:50%">Concern Person</td>   <td style="text-align:left;border: 1px solid #888888;">'.$customer_name.'</td></tr>
			<tr><td style="text-align:left;border: 1px solid #888888;width:50%">Total Amount</td>   <td style="text-align:left;border: 1px solid #888888;">'.$currency_logo.' '.number_format($sq_ticket[basic_cost],2).'</td></tr>
		  	<tr><td style="text-align:left;border: 1px solid #888888;width:50%">Paid Amount</td>   <td style="text-align:left;border: 1px solid #888888;">'.$currency_logo.' '.number_format($payment_amount,2).'</td></tr>
		  	<tr><td style="text-align:left;border: 1px solid #888888;width:50%">Balance Amount</td>   <td style="text-align:left;border: 1px solid #888888;">'.$currency_logo.' '.number_format($balance_amount,2).'</td></tr> 
		</table>
	</tr>
	';
	while($rows = mysql_fetch_assoc($flDetails)){
		$city_from = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id = ".$rows[from_city]));
		$city_to = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id = ".$rows[to_city]));
		$content .= '<tr>
		<table width="85%" cellspacing="0" cellpadding="5" style="color: #888888;border: 1px solid #888888;margin: 0px auto;margin-top:20px; min-width: 100%;" role="presentation">
		  <tr><th colspan=2>Flight Details</th></tr>
		  <tr><td style="text-align:left;border: 1px solid #888888;width:50%">Sector From</td>   <td style="text-align:left;border: 1px solid #888888;" >'.$city_from[city_name].'</td></tr>
		  <tr><td style="text-align:left;border: 1px solid #888888;width:50%">Sector To</td>   <td style="text-align:left;border: 1px solid #888888;">'.$city_to[city_name].'</td></tr> 
		  <tr><td style="text-align:left;border: 1px solid #888888;width:50%">Take Off</td>   <td style="text-align:left;border: 1px solid #888888;">'.$rows[departure_city].'</td></tr>
		  <tr><td style="text-align:left;border: 1px solid #888888;width:50%">Landing</td>   <td style="text-align:left;border: 1px solid #888888;">'.$rows[arrival_city].'</td></tr>
		  
		</table>
	  </tr>';
	}
	
	$content .= mail_login_box($username, $password, $link);

	global $model,$backoffice_email_id;
	$model->app_email_send('16',$sq_customer['first_name'],$password, $content,$subject);
	if(!empty($backoffice_email_id))
	$model->app_email_send('16',"Admin",$backoffice_email_id, $content,$subject);
	 

}
public function employee_sign_up_mail($first_name, $last_name, $username, $password, $email_id)
{
  global $app_email_id, $app_name, $app_contact_no, $admin_logo_url, $app_website;
  global $mail_em_style, $mail_em_style1, $mail_font_family, $mail_strong_style, $mail_color;
  $link = BASE_URL.'view/customer';
  $content = mail_login_box($username, $password, $link);
   $subject ='Welcome aboard!';
   global $model;
  
   $model->app_email_send('2',$first_name,$email_id, $content,$subject,'1');
}

public function booking_sms($booking_id, $customer_id, $created_at){

    global $encrypt_decrypt, $secret_key;
    $sq_customer_info = mysql_fetch_assoc(mysql_query("select contact_no from customer_master where customer_id='$customer_id'"));
	$mobile_no = $encrypt_decrypt->fnDecrypt($sq_customer_info['contact_no'], $secret_key);
    $date = $created_at;
	$created_at1 = get_date_user($created_at);
    $yr = explode("-", $date);
    $year =$yr[0];

    global $model, $app_name;
    $message = 'Thank you for booking with '.$app_name.'. Booking ID : '.get_ticket_booking_id($booking_id,$year).'  Date :'.$created_at1;

    $model->send_message($mobile_no, $message);
}
public function whatsapp_send(){
	global $app_contact_no, $encrypt_decrypt, $secret_key;
  
	$emp_id = $_POST['emp_id '];
	$booking_date = $_POST['booking_date'];
	$customer_id = $_POST['customer_id'];
	
	$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));
	$mobile_no = $encrypt_decrypt->fnDecrypt($sq_customer['contact_no'], $secret_key);
	
	$sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id= '$emp_id"));
	if($emp_id == 0){
		$contact = $app_contact_no;
	}
	else{
		$contact = $sq_emp_info['mobile_no'];
	}
   
   $whatsapp_msg = rawurlencode('Hello Dear '.$sq_customer[first_name].',
Hope you are doing great. This is to inform you that your booking is confirmed with us. We look forward to provide you a great experience.
*Booking Date* : '.get_date_user($booking_date).'
  
Please contact for more details : '.$contact.'
Thank you.');
   $link = 'https://web.whatsapp.com/send?phone='.$mobile_no.'&text='.$whatsapp_msg;
   echo $link;
}

}
?>