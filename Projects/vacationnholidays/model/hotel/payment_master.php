<?php 
$flag = true;
class payment_master{

public function payment_save(){

  $booking_id = $_POST['booking_id'];
  $payment_amount = $_POST['payment_amount'];
  $payment_date = $_POST['payment_date'];
  $payment_mode = $_POST['payment_mode'];
  $bank_name = $_POST['bank_name'];
  $transaction_id = $_POST['transaction_id'];
  $bank_id = $_POST['bank_id'];
  $branch_admin_id = $_POST['branch_admin_id'];

  $created_at = date('Y-m-d H:i:s');
  $payment_date = get_date_db($payment_date);

  if($payment_mode=="Cheque"){
    $clearance_status = "Pending";}
  else {  $clearance_status = "";}

  $financial_year_id = $_SESSION['financial_year_id'];


  begin_t();
  $sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from hotel_booking_payment"));
  $payment_id = $sq_max['max']+1;
  $sq_payment = mysql_query("insert into hotel_booking_payment(payment_id, booking_id, branch_admin_id, financial_year_id, payment_date, payment_mode, payment_amount, bank_name, transaction_id, bank_id, clearance_status, created_at) values ('$payment_id', '$booking_id', '$branch_admin_id', '$financial_year_id', '$payment_date', '$payment_mode', '$payment_amount', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status', '$created_at')");
  if(!$sq_payment){
    rollback_t();
	  echo "error--Sorry, Payment not done!";
	  exit;
  }
  else{

    //Finance save
    $this->finance_save($payment_id, $branch_admin_id);

    //Bank and Cash Book Save
    $this->bank_cash_book_save($payment_id, $branch_admin_id);

    if($GLOBALS['flag']){
      commit_t();
      //Payment email notification
        $this->payment_email_notification_send($booking_id, $payment_amount, $payment_mode, $payment_date);

        //Payment sms notification
        $this->payment_sms_notification_send($booking_id, $payment_amount, $payment_mode);

      echo "Hotel Receipt has been successfully saved.";
      exit;  
    }
    else{
      rollback_t();
      exit;
    }
    
  }

}

public function finance_save($payment_id, $branch_admin_id)
{
  $row_spec = 'sales';
  $booking_id = $_POST['booking_id'];
  $payment_amount1 = $_POST['payment_amount'];
  $payment_date = $_POST['payment_date'];
  $payment_mode = $_POST['payment_mode'];
  $bank_name = $_POST['bank_name'];
  $transaction_id1 = $_POST['transaction_id'];
  $bank_id = $_POST['bank_id'];

  $payment_date1 = get_date_db($payment_date);
	$year2 = explode("-", $payment_date1);
  $yr1 =$year2[0];

  $sq_hotel_booking_info = mysql_fetch_assoc(mysql_query("select * from hotel_booking_master where booking_id='$booking_id'"));
  $customer_id = $sq_hotel_booking_info['customer_id'];  
  global $transaction_master;


    //Getting cash/Bank Ledger
    if($payment_mode == 'Cash') {  $pay_gl = 20; }
    else{ 
      $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
      $pay_gl = $sq_bank['ledger_id'];
     } 

     //Getting customer Ledger
    $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
    $cust_gl = $sq_cust['ledger_id'];


    //////Payment Amount///////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_sales_paid_particular(get_hotel_booking_payment_id($booking_id,$yr1), $payment_date1, $payment_amount1, $customer_id, $payment_mode, get_hotel_booking_id($booking_id,$yr1));
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $pay_gl;
    $payment_side = "Debit";
    $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    ////////Customer Amount//////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_sales_particular(get_hotel_booking_payment_id($booking_id,$yr1), $payment_date1, $payment_amount1, $customer_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);  
}

public function bank_cash_book_save($payment_id, $branch_admin_id)
{
  global $bank_cash_book_master;

  $booking_id = $_POST['booking_id'];
  $payment_amount = $_POST['payment_amount'];
  $payment_date = $_POST['payment_date'];
  $payment_mode = $_POST['payment_mode'];
  $bank_name = $_POST['bank_name'];
  $transaction_id = $_POST['transaction_id'];
  $bank_id = $_POST['bank_id'];

  $payment_date1 = get_date_db($payment_date);
	$year2 = explode("-", $payment_date1);
  $yr1 =$year2[0];
  $sq_hotel_booking_info = mysql_fetch_assoc(mysql_query("select customer_id from hotel_booking_master where booking_id='$booking_id'"));  
  
  $module_name = "Hotel Booking";
  $module_entry_id = $payment_id;
  $payment_date = $payment_date1;
  $payment_amount = $payment_amount;
  $payment_mode = $payment_mode;
  $bank_name = $bank_name;
  $transaction_id = $transaction_id;
  $bank_id = $bank_id; 
  $particular = get_sales_paid_particular(get_hotel_booking_payment_id($payment_id,$yr1), $payment_date1, $payment_amount, $sq_hotel_booking_info['customer_id'], $payment_mode, get_hotel_booking_id($booking_id,$yr1));
  $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
  $payment_side = "Debit";
  $payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";

  $bank_cash_book_master->bank_cash_book_master_save($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type, $branch_admin_id);
  
}

public function payment_update()
{
  $payment_id = $_POST['payment_id'];
  $booking_id = $_POST['booking_id'];
  $payment_amount = $_POST['payment_amount'];
  $payment_date = $_POST['payment_date'];
  $payment_mode = $_POST['payment_mode'];
  $bank_name = $_POST['bank_name'];
  $transaction_id = $_POST['transaction_id'];
  $bank_id = $_POST['bank_id'];

  $payment_date = date('Y-m-d', strtotime($payment_date));

  $financial_year_id = $_SESSION['financial_year_id'];

  $sq_payment_info = mysql_fetch_assoc(mysql_query("select * from hotel_booking_payment where payment_id='$payment_id'"));
  $clearance_status = ($sq_payment_info['payment_mode']=='Cash' && $payment_mode!="Cash") ? "Pending" : $sq_payment_info['clearance_status'];
  if($payment_mode=="Cash"){ $clearance_status = ""; }

  begin_t();

  
  $sq_payment = mysql_query("update hotel_booking_payment set financial_year_id='$financial_year_id', payment_date='$payment_date', payment_mode='$payment_mode', payment_amount='$payment_amount', bank_name='$bank_name', transaction_id='$transaction_id', bank_id='$bank_id', clearance_status='$clearance_status' where payment_id='$payment_id' ");
  if(!$sq_payment){
    rollback_t();
	  echo "error--Sorry, Payment not updated!";
	  exit;
  }
  else{

    //Finance update
    $this->finance_update($sq_payment_info, $clearance_status);

    //Bank and Cash Book Save
    $this->bank_cash_book_update($clearance_status);

    if($GLOBALS['flag']){
      commit_t();
      //Payment email notification
      $this->payment_update_email_notification_send($payment_id);

      echo "Hotel Receipt has been successfully updated.";
      exit;  
    }
    else{
      rollback_t();
      exit;
    }
    
  }

}


public function finance_update($sq_payment_info, $clearance_status1)
{
  $row_spec  ='sales';
  $payment_id = $_POST['payment_id'];
  $booking_id = $_POST['booking_id'];
  $payment_amount1 = $_POST['payment_amount'];
  $payment_date = $_POST['payment_date'];
  $payment_mode = $_POST['payment_mode'];
  $bank_name = $_POST['bank_name'];
  $transaction_id1 = $_POST['transaction_id'];
  $bank_id = $_POST['bank_id']; 
  $payment_old_value = $_POST['payment_old_value']; 

  $payment_date1 = date('Y-m-d', strtotime($payment_date));
	$year2 = explode("-", $payment_date1);
  $yr1 =$year2[0];

  $sq_hotel_info = mysql_fetch_assoc(mysql_query("select customer_id from hotel_booking_master where booking_id='$booking_id'"));
  $customer_id = $sq_hotel_info['customer_id'];  
  global $transaction_master;


  //Getting cash/Bank Ledger
  if($payment_mode == 'Cash') {  $pay_gl = 20; }
  else{ 
    $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
    $pay_gl = $sq_bank['ledger_id'];
   } 

  //Getting customer Ledger
  $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
  $cust_gl = $sq_cust['ledger_id'];

  if($payment_amount1 > $payment_old_value)
  {
    $balance_amount = $payment_amount1 - $payment_old_value;
    //////Payment Amount///////
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = $transaction_id1;
      $payment_amount = $payment_amount1;
      $payment_date = $payment_date1;
      $payment_particular = get_sales_paid_particular(get_hotel_booking_payment_id($booking_id,$yr1), $payment_date1, $payment_amount1, $customer_id, $payment_mode, get_hotel_booking_id($booking_id,$yr1));
      $ledger_particular = get_ledger_particular('By','Cash/Bank');
      $gl_id = $pay_gl;
      $payment_side = "Debit";
      $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
      $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

      ////////Balance Amount//////
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = $transaction_id1;
      $payment_amount = $balance_amount;
      $payment_date = $payment_date1;
      $payment_particular = get_sales_particular(get_hotel_booking_id($booking_id,$yr1), $payment_date1, $balance_amount, $customer_id);
      $ledger_particular = get_ledger_particular('By','Cash/Bank');
      $gl_id = $cust_gl;
      $payment_side = "Credit";
      $clearance_status = "";
      $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

      //Reverse first payment amount
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = $transaction_id1;
      $payment_amount = $payment_old_value;
      $payment_date = $payment_date1;
      $payment_particular = get_sales_paid_particular(get_hotel_booking_payment_id($booking_id,$yr1), $payment_date1, $payment_old_value, $customer_id, $payment_mode,get_hotel_booking_id($booking_id,$yr1));
      $ledger_particular = get_ledger_particular('By','Cash/Bank');
      $gl_id = $pay_gl;
      $payment_side = "Credit";
      $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
      $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
  }
  else if($payment_amount1 < $payment_old_value){
    $balance_amount = $payment_old_value - $payment_amount1;
    //////Payment Amount///////
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = $transaction_id1;
      $payment_amount = $payment_amount1;
      $payment_date = $payment_date1;
      $payment_particular = get_sales_paid_particular(get_hotel_booking_payment_id($booking_id,$yr1), $payment_date1, $payment_amount1, $customer_id, $payment_mode, get_hotel_booking_id($booking_id,$yr1));
      $ledger_particular = get_ledger_particular('By','Cash/Bank');
      $gl_id = $pay_gl;
      $payment_side = "Debit";
      $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
      $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

      ////////Balance Amount//////
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = $transaction_id1;
      $payment_amount = $balance_amount;
      $payment_date = $payment_date1;
      $payment_particular = get_sales_particular(get_hotel_booking_id($booking_id,$yr1), $payment_date1, $balance_amount, $customer_id);
      $ledger_particular = get_ledger_particular('By','Cash/Bank');
      $gl_id = $cust_gl;
      $payment_side = "Debit";
      $clearance_status = "";
      $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
      
      //Reverse first payment amount
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = $transaction_id1;
      $payment_amount = $payment_old_value;
      $payment_date = $payment_date1;
      $payment_particular = get_sales_paid_particular(get_hotel_booking_payment_id($booking_id,$yr1), $payment_date1, $payment_old_value, $customer_id, $payment_mode, get_hotel_booking_id($booking_id,$yr1));
      $ledger_particular = get_ledger_particular('By','Cash/Bank');
      $gl_id = $pay_gl;
      $payment_side = "Credit";
      $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
      $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
  }
  else{
    //Do nothing
  }
}


public function bank_cash_book_update($clearance_status)
{
  global $bank_cash_book_master;

  $payment_id = $_POST['payment_id'];
  $booking_id = $_POST['booking_id'];
  $payment_amount = $_POST['payment_amount'];
  $payment_date = $_POST['payment_date'];
  $payment_mode = $_POST['payment_mode'];
  $bank_name = $_POST['bank_name'];
  $transaction_id = $_POST['transaction_id'];
  $bank_id = $_POST['bank_id'];
  $payment_date = date('Y-m-d', strtotime($payment_date));
	$year2 = explode("-", $payment_date);
  $yr1 =$year2[0];

  $sq_hotel_booking_info = mysql_fetch_assoc(mysql_query("select customer_id from hotel_booking_master where booking_id='$booking_id'"));  
  
  $module_name = "Hotel Booking";
  $module_entry_id = $payment_id;
  $payment_date = $payment_date;
  $payment_amount = $payment_amount;
  $payment_mode = $payment_mode;
  $bank_name = $bank_name;
  $transaction_id = $transaction_id;
  $bank_id = $bank_id;
  $particular = get_sales_paid_particular(get_hotel_booking_payment_id($payment_id,$yr1), $payment_date, $payment_amount, $sq_hotel_booking_info['customer_id'], $payment_mode, get_hotel_booking_id($booking_id,$yr1));
  $clearance_status = $clearance_status;
  $payment_side = "Debit";
  $payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";

  $bank_cash_book_master->bank_cash_book_master_update($module_name, $payment_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type);
}

//////////////////////////////////**Payment email notification send start**/////////////////////////////////////
public function payment_email_notification_send($booking_id, $payment_amount, $payment_mode, $payment_date)
{
   $sq_hotel_info = mysql_fetch_assoc(mysql_query("select * from hotel_booking_master where booking_id='$booking_id'"));

   $total_amount = $sq_hotel_info['total_fee'];
   $date = $sq_hotel_info['created_at'];
   $yr = explode("-", $date);
   $year =$yr[0];

   $sq_customer_info = mysql_fetch_assoc(mysql_query("select email_id from customer_master where customer_id='$sq_hotel_info[customer_id]'"));
   $email_id = $sq_customer_info['email_id'];

   $sq_total_amount = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from hotel_booking_payment where booking_id='$booking_id' and clearance_status!='Cancelled'"));
   $paid_amount = $sq_total_amount['sum'];

   $payment_id = get_hotel_booking_payment_id($payment_id,$year);

   $subject = 'Payment Acknowledgement (Booking ID : '.get_hotel_booking_id($booking_id,$year).' )';
   global $model;
   $model->generic_payment_mail('50',$payment_amount, $payment_mode, $total_amount, $paid_amount, $payment_date, $email_id, $subject);
}
//////////////////////////////////**Payment email notification send end**/////////////////////////////////////


//////////////////////////////////**Payment update email notification send start**/////////////////////////////////////
public function payment_update_email_notification_send($payment_id)
{

  $sq_payment_info = mysql_fetch_assoc(mysql_query("select * from hotel_booking_payment where payment_id='$payment_id' and clearance_status!='Cancelled'"));
  $booking_id = $sq_payment_info['booking_id'];
  $payment_amount = $sq_payment_info['payment_amount'];
    $payment_mode = $sq_payment_info['payment_mode'];
    $payment_date = $sq_payment_info['payment_date'];
  $update_payment = true;

  $sq_hotel_info = mysql_fetch_assoc(mysql_query("select * from hotel_booking_master where booking_id='$booking_id'"));
  $total_amount = $sq_hotel_info['total_fee'];

  $date = $sq_hotel_info['created_at'];
  $yr = explode("-", $date);
  $year =$yr[0];

  $sq_total_amount = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from hotel_booking_payment where booking_id='$booking_id' and clearance_status!='Cancelled'"));
    $paid_amount = $sq_total_amount['sum'];

  $sq_customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_hotel_info[customer_id]'"));
  $email_id = $sq_customer_info['email_id'];

  $payment_id = get_hotel_booking_payment_id($payment_id,$year);
  
  $subject = 'Hotel Booking Payment Correction (Booking ID : '.get_hotel_booking_id($booking_id,$year).' )';

  global $model;
    $model->generic_payment_mail('60',$payment_amount, $payment_mode, $total_amount, $paid_amount, $payment_date, $email_id,$subject, $update_payment);

}
//////////////////////////////////**Payment update email notification send end**/////////////////////////////////////

//////////////////////////////////**Payment sms notification send start**/////////////////////////////////////
public function payment_sms_notification_send($booking_id, $payment_amount, $payment_mode)
{
  $sq_hotel_info = mysql_fetch_assoc(mysql_query("select customer_id from hotel_booking_master where booking_id='$booking_id'"));
  $customer_id = $sq_hotel_info['customer_id'];

  $sq_customer_info = mysql_fetch_assoc(mysql_query("select contact_no from customer_master where customer_id='$customer_id'"));
  $mobile_no = $sq_customer_info['contact_no'];

  $message = "Acknowledge your payment of Rs. ".$payment_amount.", ".$payment_mode." which we received for Hotel installment.";
    global $model;
    $model->send_message($mobile_no, $message);
}
//////////////////////////////////**Payment sms notification send end**/////////////////////////////////////

}
?>