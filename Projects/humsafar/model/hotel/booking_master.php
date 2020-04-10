<?php
$flag = true;
class booking_master{
public function booking_save(){
  $row_spec ='sales';
	$unique_timestamp = $_POST['unique_timestamp'];
	$customer_id = $_POST['customer_id'];
  $emp_id = $_POST['emp_id'];
  $pass_name = $_POST['pass_name'];
	$adults = $_POST['adults'];
	$childrens = $_POST['childrens'];
	$infants = $_POST['infants'];
	$sub_total = $_POST['sub_total'];
	$service_charge = $_POST['service_charge'];
	$taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax = $_POST['service_tax'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$discount = $_POST['discount'];
	$tds = $_POST['tds'];
	$total_fee = $_POST['total_fee'];
	$due_date = $_POST['due_date'];
  $booking_date = $_POST['booking_date'];
  $branch_admin_id = $_POST['branch_admin_id'];

	$payment_date = $_POST['payment_date'];
	$payment_amount = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name =$_POST['bank_name'];
	$transaction_id = $_POST['transaction_id'];
	$bank_id = $_POST['bank_id'];

	$city_id_arr = $_POST['city_id_arr'];
	$hotel_id_arr = $_POST['hotel_id_arr'];
	$check_in_arr = $_POST['check_in_arr'];
	$check_out_arr = $_POST['check_out_arr'];
	$no_of_nights_arr = $_POST['no_of_nights_arr'];
	$rooms_arr = $_POST['rooms_arr'];
	$room_type_arr = $_POST['room_type_arr'];
	$category_arr = $_POST['category_arr'];
	$accomodation_type_arr = $_POST['accomodation_type_arr'];
	$extra_beds_arr = $_POST['extra_beds_arr'];
	$meal_plan_arr = $_POST['meal_plan_arr'];
	$conf_no_arr = $_POST['conf_no_arr'];

	$timestamp_count = mysql_num_rows(mysql_query("select * from hotel_booking_master where unique_timestamp='$unique_timestamp'"));
	if($timestamp_count>0){
		echo "error--Booking already saved!";
		exit;
	}

  //Get Customer id
  if($customer_id == '0'){
    $sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
    $customer_id = $sq_max['max'];
  }

	  $due_date = date('Y-m-d',strtotime($due_date));
    $booking_date = date('Y-m-d',strtotime($booking_date));
    $payment_date = date('Y-m-d', strtotime($payment_date));
	  $created_at = date('Y-m-d H:i:s');

    if($payment_mode=="Cheque"){ 
      $clearance_status = "Pending"; } 
    else {  $clearance_status = ""; } 

    $financial_year_id = $_SESSION['financial_year_id'];


	$sq_max = mysql_fetch_assoc(mysql_query("select max(booking_id) as max from hotel_booking_master"));
	$booking_id = $sq_max['max'] + 1;

	begin_t();

	$sq_booking = mysql_query("INSERT INTO hotel_booking_master(booking_id, customer_id, branch_admin_id,financial_year_id,pass_name, adults, childrens, infants, sub_total, service_charge, taxation_type, taxation_id, service_tax, service_tax_subtotal, discount, tds, total_fee, unique_timestamp, due_date , created_at, emp_id) VALUES ('$booking_id', '$customer_id', '$branch_admin_id','$financial_year_id','$pass_name', '$adults', '$childrens', '$infants', '$sub_total', '$service_charge', '$taxation_type', '$taxation_id', '$service_tax', '$service_tax_subtotal', '$discount', '$tds', '$total_fee', '$unique_timestamp', '$due_date', '$booking_date', '$emp_id')");
	if(!$sq_booking){

        rollback_t();

        echo "error--Sorry Hotel information not saved successfully!";

        exit;

    }
    else{
		for($i=0; $i<sizeof($city_id_arr); $i++){

			$check_in_arr[$i] = date('Y-m-d H:i:s', strtotime($check_in_arr[$i]));
			$check_out_arr[$i] = date('Y-m-d H:i:s', strtotime($check_out_arr[$i]));

			$sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from hotel_booking_entries"));
			$entry_id = $sq_max['max'] + 1;

			$sq_entry = mysql_query("insert into hotel_booking_entries (entry_id, booking_id, city_id, hotel_id, check_in, check_out, no_of_nights, rooms, room_type, category, accomodation_type, extra_beds, meal_plan, conf_no) values('$entry_id', $booking_id, '$city_id_arr[$i]', '$hotel_id_arr[$i]', '$check_in_arr[$i]', '$check_out_arr[$i]', '$no_of_nights_arr[$i]', '$rooms_arr[$i]', '$room_type_arr[$i]', '$category_arr[$i]', '$accomodation_type_arr[$i]', '$extra_beds_arr[$i]', '$meal_plan_arr[$i]', '$conf_no_arr[$i]')");
			if(!$sq_entry){
				$GLOBALS['flag'] = false;
				echo "error--Sorry, Some hotels are not saved!";
			}
		}
	    $sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from hotel_booking_payment"));
	    $payment_id = $sq_max['max']+1;
  	  
	  	$sq_payment = mysql_query("insert into hotel_booking_payment(payment_id, booking_id, financial_year_id, branch_admin_id, payment_date, payment_mode, payment_amount, bank_name, transaction_id, bank_id, clearance_status, created_at) values ('$payment_id', '$booking_id', '$financial_year_id', '$branch_admin_id', '$payment_date', '$payment_mode', '$payment_amount', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status', '$created_at')");
	    if(!$sq_payment){
	  	  $GLOBALS['flag'] = false;
		    echo "error--Sorry, Payment not done!".$booking_id;
		    exit;
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

    	//Cash/bank book 
    	$this->bank_cash_book_save($booking_id, $payment_id, $branch_admin_id);

		if($GLOBALS['flag']){
			commit_t();
			//Hotel Booking email send
			$this->hotel_booking_email_send($booking_id);
      $this->booking_sms($booking_id, $customer_id, $booking_date);
            
      //payment email send
      $payment_master  = new payment_master;            
      $payment_master->payment_email_notification_send($booking_id, $payment_amount, $payment_mode, $payment_date);

      // payment sms send
      if($payment_amount != 0){
        $payment_master->payment_sms_notification_send($booking_id, $payment_amount, $payment_mode);
      }
      echo "Hotel Booking has been successfully saved.";
      exit;   
           
		}
		else{
			rollback_t();
			exit;
		}
	}
	
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
public function finance_save($booking_id, $payment_id, $row_spec, $branch_admin_id)
{
	$customer_id = $_POST['customer_id'];
	$sub_total = $_POST['sub_total'];
	$service_charge = $_POST['service_charge'];
	$taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$discount = $_POST['discount'];
	$tds = $_POST['tds'];
	$total_fee = $_POST['total_fee'];
	$payment_date = $_POST['payment_date'];
	$payment_amount1 = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name =$_POST['bank_name'];
	$transaction_id1 = $_POST['transaction_id'];
	$bank_id = $_POST['bank_id'];

	$booking_date = get_date_db($_POST['booking_date']);
	$payment_date1 = get_date_db($payment_date);
	$year1 = explode("-", $booking_date);
	$yr1 =$year1[0];
	$year2 = explode("-", $payment_date1);
  $yr2 =$year2[0];
  
  $hotel_sale_amount = $sub_total + $service_charge;
  $balance_amount = $total_fee - $payment_amount1;

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
    ////////////Sales/////////////

    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $hotel_sale_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_hotel_booking_id($booking_id,$yr1), $booking_date, $hotel_sale_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $gl_id = 63;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    /////////Tax Amount/////////
    tax_reflection_update('Hotel Booking',$service_tax_subtotal,$taxation_type,$booking_id,get_hotel_booking_id($booking_id,$yr1),$booking_date, $customer_id, $row_spec,$branch_admin_id);

    /////////Discount////////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $discount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_hotel_booking_id($booking_id,$yr1), $booking_date, $discount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $gl_id = 36;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    /////////TDS////////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $tds;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_hotel_booking_id($booking_id,$yr1), $booking_date, $tds, $customer_id);
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $gl_id = 127;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    //////Payment Amount///////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_sales_particular(get_hotel_booking_id($booking_id,$yr2), $payment_amount1, $payment_amount1, $customer_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $pay_gl;
    $payment_side = "Debit";
    $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    ////////Balance Amount//////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $balance_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_hotel_booking_id($booking_id,$yr1), $booking_date, $balance_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
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
  $bank_name =$_POST['bank_name'];
  $transaction_id = $_POST['transaction_id'];
  $bank_id = $_POST['bank_id'];
	$payment_date = get_date_db($payment_date);
	$year2 = explode("-", $payment_date);
  $yr1 =$year2[0];

  //Get Customer id
  if($customer_id == '0'){
    $sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
    $customer_id = $sq_max['max'];
  }
  
  $module_name = "Hotel Booking";
  $module_entry_id = $payment_id;
  $payment_date = $payment_date;
  $payment_amount = $payment_amount;
  $payment_mode = $payment_mode;
  $bank_name = $bank_name;
  $transaction_id = $transaction_id;
  $bank_id = $bank_id; 
  $particular = get_sales_paid_particular(get_hotel_booking_payment_id($payment_id,$yr1), $payment_date, $payment_amount, $customer_id, $payment_mode, get_hotel_booking_id($booking_id,$yr1));
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
  $pass_name = $_POST['pass_name'];
	$adults = $_POST['adults'];
	$childrens = $_POST['childrens'];
	$infants = $_POST['infants'];
	$sub_total = $_POST['sub_total'];
	$service_charge = $_POST['service_charge'];
	$taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax = $_POST['service_tax'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$discount = $_POST['discount'];
	$tds = $_POST['tds'];
	$total_fee = $_POST['total_fee'];
	$due_date1 = $_POST['due_date1'];
  $booking_date1 = $_POST['booking_date1'];

	$city_id_arr = $_POST['city_id_arr'];
	$hotel_id_arr = $_POST['hotel_id_arr'];
	$check_in_arr = $_POST['check_in_arr'];
	$check_out_arr = $_POST['check_out_arr'];
	$no_of_nights_arr = $_POST['no_of_nights_arr'];
	$rooms_arr = $_POST['rooms_arr'];
	$room_type_arr = $_POST['room_type_arr'];
	$category_arr = $_POST['category_arr'];
	$accomodation_type_arr = $_POST['accomodation_type_arr'];
	$extra_beds_arr = $_POST['extra_beds_arr'];
	$meal_plan_arr = $_POST['meal_plan_arr'];
	$conf_no_arr = $_POST['conf_no_arr'];
	$entry_id_arr = $_POST['entry_id_arr'];

	$booking_date1 = date('Y-m-d', strtotime($booking_date1));
	$due_date1 = date('Y-m-d',strtotime($due_date1));

	$sq_booking_info = mysql_fetch_assoc(mysql_query("select * from hotel_booking_master where booking_id='$booking_id'"));

	begin_t();


	$sq_booking = mysql_query("UPDATE hotel_booking_master SET customer_id='$customer_id',pass_name='$pass_name', adults='$adults', childrens='$childrens', infants='$infants', sub_total='$sub_total', service_charge='$service_charge', taxation_type='$taxation_type', taxation_id='$taxation_id', service_tax='$service_tax', service_tax_subtotal='$service_tax_subtotal', discount='$discount', tds='$tds', total_fee='$total_fee', due_date='$due_date1',created_at='$booking_date1' WHERE booking_id='$booking_id'");
	if($sq_booking){

		for($i=0; $i<sizeof($city_id_arr); $i++){

			$check_in_arr[$i] = get_datetime_db($check_in_arr[$i]);
			$check_out_arr[$i] = get_datetime_db($check_out_arr[$i]);

			if($entry_id_arr[$i]==""){

				$sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from hotel_booking_entries"));
				$entry_id = $sq_max['max'] + 1;

				$sq_entry = mysql_query("insert into hotel_booking_entries (entry_id, booking_id, city_id, hotel_id, check_in, check_out, no_of_nights, rooms, room_type, category, accomodation_type, extra_beds, meal_plan, conf_no) values('$entry_id', $booking_id, '$city_id_arr[$i]', '$hotel_id_arr[$i]', '$check_in_arr[$i]', '$check_out_arr[$i]', '$no_of_nights_arr[$i]', '$rooms_arr[$i]', '$room_type_arr[$i]', '$category_arr[$i]', '$accomodation_type_arr[$i]', '$extra_beds_arr[$i]', '$meal_plan_arr[$i]', '$conf_no_arr[$i]')");
				if(!$sq_entry){
					$GLOBALS['flag'] = false;
					echo "error--Sorry, Some hotels are not saved!";
					//exit;
				}

			}
			else{

				$sq_entry = mysql_query("UPDATE hotel_booking_entries set city_id='$city_id_arr[$i]', hotel_id='$hotel_id_arr[$i]', check_in='$check_in_arr[$i]', check_out='$check_out_arr[$i]', no_of_nights='$no_of_nights_arr[$i]', rooms='$rooms_arr[$i]', room_type='$room_type_arr[$i]', category='$category_arr[$i]', accomodation_type='$accomodation_type_arr[$i]', extra_beds='$extra_beds_arr[$i]', meal_plan='$meal_plan_arr[$i]', conf_no='$conf_no_arr[$i]'  where entry_id='$entry_id_arr[$i]'");
				if(!$sq_entry){
					$GLOBALS['flag'] = false;
					echo "error--Sorry, Some hotels are not updated!";
					//exit;
				}

			}

			

		}

		//Finance update
		$this->finance_update($sq_booking_info, $row_spec);

		if($GLOBALS['flag']){
			commit_t();
			echo "Hotel Booking has been successfully updated.";
			exit;	
		}
		else{
			rollback_t();
			exit;
		}
		

	}
	else{
		rollback_t();
		echo "error--Sorry, Booking not updated!";
		exit;
	}
	
}

public function finance_update($sq_booking_info, $row_spec)
{
  $row_spec = 'sales';
	$booking_id = $_POST['booking_id'];
	$customer_id = $_POST['customer_id'];
	$sub_total = $_POST['sub_total'];
	$service_charge = $_POST['service_charge'];
	$taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax = $_POST['service_tax'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$discount = $_POST['discount'];
	$tds = $_POST['tds'];
	$total_fee = $_POST['total_fee'];

	$booking_date1 = $_POST['booking_date1'];

  $booking_date = get_date_db($booking_date1);
	$year1 = explode("-", $booking_date);
	$yr1 =$year1[0];

    $hotel_sale_amount = $sub_total + $service_charge;
    //get total payment against visa id
    $sq_hotel = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as payment_amount from hotel_booking_payment where booking_id='$booking_id'"));
    $balance_amount = $total_fee - $sq_hotel['payment_amount'];

    //Getting customer Ledger
    $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
    $cust_gl = $sq_cust['ledger_id'];


    global $transaction_master;

    ////////////Sales/////////////

    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $hotel_sale_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_hotel_booking_id($booking_id,$yr1), $booking_date, $hotel_sale_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $old_gl_id = $gl_id = 63;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

    /////////Tax Amount/////////
    tax_reflection_update('Hotel Booking',$service_tax_subtotal,$taxation_type,$booking_id,get_hotel_booking_id($booking_id,$yr1),$booking_date, $customer_id, $row_spec);

    /////////Discount////////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $discount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_hotel_booking_id($booking_id,$yr1), $booking_date, $discount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $old_gl_id = $gl_id = 36;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

    /////////TDS////////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $tds;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_hotel_booking_id($booking_id,$yr1), $booking_date, $tds, $customer_id);
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $old_gl_id = $gl_id = 127;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

    ////////Balance Amount//////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $balance_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_hotel_booking_id($booking_id,$yr1), $booking_date, $balance_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $old_gl_id = $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);
}


public function hotel_booking_email_send($booking_id)
{
	global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;
	global $app_name;
	$link = BASE_URL.'view/customer';

	$sq_hotel_booking = mysql_fetch_assoc(mysql_query("select * from hotel_booking_master where booking_id='$booking_id'"));
  $date = $sq_hotel_booking['created_at'];
  $yr = explode("-", $date);
  $year =$yr[0];

	$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_hotel_booking[customer_id]'"));

	$email_id = $sq_customer['email_id'];
	$customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
	$password= $email_id;
	$username =$sq_customer['contact_no'];
  $subject = 'Booking confirmation acknowledgement! ( '.get_hotel_booking_id($booking_id,$year). ' )';
	$content = '
    <tr>
      <td>
      	<table style="width:100%">	
      		<tr>
      			<td>
      				<p style="line-height:24px">Please Find below Login Details:-</p>
      			</td>
      		</tr>
      		<tr>
      			<td>
      				<table style="width:100%">
      					<tr>
      						<td>
      							<p>Booking ID :'.get_hotel_booking_id($booking_id,$year).'</p>
      						</td>
      					</tr>
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
	
  $model->app_email_send('18',$email_id, $content, $subject);
  $model->app_email_send('18',$backoffice_email_id, $content, $subject);
}

public function booking_sms($booking_id, $customer_id, $created_at){

  $sq_customer_info = mysql_fetch_assoc(mysql_query("select contact_no from customer_master where customer_id='$customer_id'"));
  $mobile_no = $sq_customer_info['contact_no'];
  $date = $created_at;
  $created_at1 = get_date_user($created_at);
  $yr = explode("-", $date);
  $year =$yr[0];

  global $model, $app_name;
  $message = 'Thank you for booking with '.$app_name.'. Booking ID : '.get_hotel_booking_id($booking_id,$year).'  Date :'.$created_at1;

  $model->send_message($mobile_no, $message);  
}

}
?>