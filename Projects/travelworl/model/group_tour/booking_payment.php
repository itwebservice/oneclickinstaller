<?php 
$flag = true;
class booking_payment{

///////////////////////////////////***** Saving Payment Information *********/////////////////////////////////////////////////////////////
 public function save_payment_details()
 {
    $tourwise_traveler_id = $_POST['tourwise_id'];
    $payment_date = $_POST['payment_date'];
    $payment_mode = $_POST['payment_mode'];
    $payment_amount = $_POST['payment_amount'];
    $bank_name = $_POST['bank_name'];
    $transaction_id = $_POST['transaction_id'];
    $payment_for = $_POST['payment_for'];
    $p_travel_type = $_POST['p_travel_type'];
    $bank_id = $_POST['bank_id'];
    $branch_admin_id = $_POST['branch_admin_id'];
    $emp_id = $_POST['emp_id'];
    $payment_date = date("Y-m-d", strtotime($payment_date));
    $financial_year_id = $_SESSION['financial_year_id']; 

    $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";

    begin_t();

     $sq = mysql_query("select max(payment_id) as max from payment_master");
     $value = mysql_fetch_assoc($sq);
     $max_payment_id = $value['max'] + 1;
  
     $sq= mysql_query(" insert into payment_master (payment_id, financial_year_id, branch_admin_id, emp_id, tourwise_traveler_id, date, payment_mode, amount, bank_name, transaction_id, payment_for, travel_type, bank_id, clearance_status ) values ('$max_payment_id', '$financial_year_id', '$branch_admin_id', '$emp_id', '$tourwise_traveler_id', '$payment_date', '$payment_mode', '$payment_amount', '$bank_name', '$transaction_id', '$payment_for', '$p_travel_type', '$bank_id', '$clearance_status') ");
  
     $this->booking_registration_reciept_save( $tourwise_traveler_id, $max_payment_id, $payment_for);
  
     if(!$sq)
      {
        rollback_t();
        echo "error--Error in payment information save.";
        exit;
      } 
      else{

        //Finance Save
        $this->finance_save($max_payment_id, $branch_admin_id);

        //Bank and Cash Book Save
        $this->bank_cash_book_save($max_payment_id,$branch_admin_id);

        if($GLOBALS['flag']){
          commit_t();
    
          //Payment email notification
          $this->payment_email_notification_send($tourwise_traveler_id, $payment_amount, $payment_mode, $payment_date);
    
          //Payment notification message after booking
          if($payment_amount!=0){
            $this->payment_notification_sms_send($payment_amount, $payment_mode,$tourwise_traveler_id);
          }
    
          echo "Payment has been successfully saved.";  
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
    $tourwise_traveler_id = $_POST['tourwise_id'];
    $payment_date = $_POST['payment_date'];
    $payment_mode = $_POST['payment_mode'];
    $payment_amount1 = $_POST['payment_amount'];
    $bank_name = $_POST['bank_name'];
    $transaction_id1 = $_POST['transaction_id'];
    $bank_id1 = $_POST['bank_id'];
  
    $payment_date = date('Y-m-d', strtotime($payment_date));
    $year1 = explode("-", $payment_date);
    $yr1 =$year1[0];

    $sq_group_info = mysql_fetch_assoc(mysql_query("select * from tourwise_traveler_details where id='$tourwise_traveler_id'"));
    $customer_id = $sq_group_info['customer_id'];  
    global $transaction_master;


    //Getting cash/Bank Ledger
    if($payment_mode == 'Cash') {  $pay_gl = 20; }
    else{ 
      $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id1' and user_type='bank'"));
      $pay_gl = $sq_bank['ledger_id'];
     } 

     //Getting customer Ledger
    $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
    $cust_gl = $sq_cust['ledger_id'];


    //////Payment Amount///////
    $module_name = "Group Booking";
    $module_entry_id = $tourwise_traveler_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date;
    $payment_particular = get_sales_paid_particular(get_group_booking_payment_id($tourwise_traveler_id,$yr1), $payment_date, $payment_amount1, $customer_id, $payment_mode, get_group_booking_id($tourwise_traveler_id,$yr1));
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $pay_gl;
    $payment_side = "Debit";
    $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    ////////Customer Amount//////
    $module_name = "Group Booking";
    $module_entry_id = $tourwise_traveler_id;
    $transaction_id = "";
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date;
    $payment_particular = get_sales_particular(get_group_booking_id($tourwise_traveler_id,$yr1), $payment_date, $payment_amount1, $customer_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

 }

 public function bank_cash_book_save($payment_id,$branch_admin_id)
{
  global $bank_cash_book_master;

  $tourwise_traveler_id = $_POST['tourwise_id'];
  $payment_date = $_POST['payment_date'];
  $payment_mode = $_POST['payment_mode'];
  $payment_amount = $_POST['payment_amount'];
  $bank_name = $_POST['bank_name'];
  $transaction_id = $_POST['transaction_id'];
  $payment_for = $_POST['payment_for'];
  $p_travel_type = $_POST['p_travel_type'];
  $bank_id = $_POST['bank_id'];
  $payment_date = date('Y-m-d', strtotime($payment_date));
  $year1 = explode("-", $payment_date);
  $yr1 =$year1[0];

  $sq_booking = mysql_fetch_assoc(mysql_query("select customer_id from tourwise_traveler_details where id='$tourwise_traveler_id'"));
  
  $module_name = "Group Booking";
  $module_entry_id = $payment_id;
  $payment_date = $payment_date;
  $payment_amount = $payment_amount;
  $payment_mode = $payment_mode;
  $bank_name = $bank_name;
  $transaction_id = $transaction_id;
  $bank_id = $bank_id;
  $particular = get_sales_paid_particular(get_group_booking_payment_id($payment_id,$yr1), $payment_date, $payment_amount, $sq_booking['customer_id'], $payment_mode, get_group_booking_id($tourwise_traveler_id,$yr1));
  $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
  $payment_side = "Debit";
  $payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";

  $bank_cash_book_master->bank_cash_book_master_save($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type,$branch_admin_id);
  
}




///////////////////////////////////***** Payment Master Update start *********/////////////////////////////////////////////////////////////
function payment_master_update()
{
  $payment_id = $_POST['payment_id'];
  $tourwise_traveler_id = $_POST['tourwise_traveler_id'];
  $payment_date = $_POST['payment_date'];
  $payment_mode = $_POST['payment_mode'];
  $payment_amount = $_POST['payment_amount'];
  $bank_name = $_POST['bank_name'];
  $transaction_id = $_POST['transaction_id'];
  $payment_for = $_POST['payment_for'];
  $p_travel_type = $_POST['p_travel_type'];
  $bank_id = $_POST['bank_id'];
  $payment_old_value = $_POST['payment_old_value']; 

  $financial_year_id = $_SESSION['financial_year_id'];  

  $sq_payment_info = mysql_fetch_assoc(mysql_query("select * from payment_master where payment_id='$payment_id'"));

  $clearance_status = ($sq_payment_info['payment_mode']=='Cheque') ? "Pending" : '';

  begin_t();
   
  $sq = mysql_query("update payment_master set tourwise_traveler_id = '$tourwise_traveler_id',financial_year_id='$financial_year_id', date='$payment_date', payment_mode='$payment_mode', amount='$payment_amount', bank_name='$bank_name', transaction_id='$transaction_id', payment_for='$payment_for', travel_type='$p_travel_type', bank_id='$bank_id', clearance_status='$clearance_status' where payment_id='$payment_id'"); 
  if(!$sq)
  {
    rollback_t();
    echo "error--Payment not updated!";
    exit;
  }  
  else
  {

    //Finance Update
    $this->finance_update($sq_payment_info, $clearance_status);

    //Bank and Cash Book update
    $this->bank_cash_book_update($sq_payment_info, $clearance_status);

    if($GLOBALS['flag']){
      commit_t();
      //Payment email notification
      $this->payment_update_email_notification_send($payment_id);

      echo "Payment updated!";
      return true;  
    }
    else{
      rollback_t();
      exit;
    }
    
  }  

}

function finance_update($sq_payment_info, $clearance_status1)
{
    $row_spec = 'sales';
    $payment_id = $_POST['payment_id'];
    $tourwise_traveler_id = $_POST['tourwise_traveler_id'];
    $payment_date = $_POST['payment_date'];
    $payment_amount1 = $_POST['payment_amount'];
    $payment_mode = $_POST['payment_mode'];
    $bank_name = $_POST['bank_name'];
    $transaction_id1 = $_POST['transaction_id']; 
    $bank_id = $_POST['bank_id']; 
    $payment_old_value = $_POST['payment_old_value']; 

    $payment_date = date('Y-m-d', strtotime($payment_date));
    $year1 = explode("-", $payment_date);
    $yr1 =$year1[0];

    $sq_group_info = mysql_fetch_assoc(mysql_query("select customer_id from tourwise_traveler_details where id='$tourwise_traveler_id'"));
    $customer_id = $sq_group_info['customer_id'];  
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
        $module_name = "Group Booking";
        $module_entry_id = $tourwise_traveler_id;
        $transaction_id = $transaction_id1;
        $payment_amount = $payment_amount1;
        $payment_date = $payment_date;
        $payment_particular = get_sales_paid_particular(get_group_booking_payment_id($tourwise_traveler_id,$yr1), $payment_date, $payment_amount1, $customer_id, $payment_mode, get_group_booking_id($tourwise_traveler_id,$yr1));
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $pay_gl;
        $payment_side = "Debit";
        $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

        ////////Balance Amount//////
        $module_name = "Group Booking";
        $module_entry_id = $tourwise_traveler_id;
        $transaction_id = "";
        $payment_amount = $balance_amount;
        $payment_date = $payment_date;
        $payment_particular = get_sales_particular(get_group_booking_id($tourwise_traveler_id,$yr1), $payment_date, $balance_amount, $customer_id);
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $cust_gl;
        $payment_side = "Credit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

        //Reverse first payment amount
        $module_name = "Group Booking";
        $module_entry_id = $tourwise_traveler_id;
        $transaction_id = $transaction_id1;
        $payment_amount = $payment_old_value;
        $payment_date = $payment_date;
        $payment_particular = get_sales_paid_particular(get_group_booking_payment_id($tourwise_traveler_id,$yr1), $payment_date, $payment_old_value, $customer_id, $payment_mode, get_group_booking_id($tourwise_traveler_id,$yr1));
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $pay_gl;
        $payment_side = "Credit";
        $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
    }
    else if($payment_amount1 < $payment_old_value){
      $balance_amount = $payment_old_value - $payment_amount1;
      //////Payment Amount///////
        $module_name = "Group Booking";
        $module_entry_id = $tourwise_traveler_id;
        $transaction_id = $transaction_id1;
        $payment_amount = $payment_amount1;
        $payment_date = $payment_date;
        $payment_particular = get_sales_paid_particular(get_group_booking_payment_id($tourwise_traveler_id,$yr1), $payment_date, $payment_amount1, $customer_id, $payment_mode, get_group_booking_id($tourwise_traveler_id,$yr1));
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $pay_gl;
        $payment_side = "Debit";
        $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

        ////////Balance Amount//////
        $module_name = "Group Booking";
        $module_entry_id = $tourwise_traveler_id;
        $transaction_id = "";
        $payment_amount = $balance_amount;
        $payment_date = $payment_date;
        $payment_particular = get_sales_particular(get_group_booking_id($tourwise_traveler_id,$yr1), $payment_date, $balance_amount, $customer_id);
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $cust_gl;
        $payment_side = "Debit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);  
        
        //Reverse first payment amount
        $module_name = "Group Booking";
        $module_entry_id = $tourwise_traveler_id;
        $transaction_id = $transaction_id1;
        $payment_amount = $payment_old_value;
        $payment_date = $payment_date;
        $payment_particular = get_sales_paid_particular(get_group_booking_payment_id($tourwise_traveler_id,$yr1), $payment_date, $payment_old_value, $customer_id, $payment_mode, get_group_booking_id($tourwise_traveler_id,$yr1));
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

public function bank_cash_book_update($sq_payment_info, $clearance_status)
{
  global $bank_cash_book_master;

  $payment_id = $_POST['payment_id'];
  $payment_mode = $_POST['payment_mode'];
  $payment_amount = $_POST['payment_amount'];
  $bank_name = $_POST['bank_name'];
  $transaction_id = $_POST['transaction_id'];
  $bank_id = $_POST['bank_id'];
  $payment_date = get_date_db($sq_payment_info['date']);
	$year1 = explode("-", $payment_date);
	$yr1 =$year1[0];

  $sq_booking = mysql_fetch_assoc(mysql_query("select customer_id from tourwise_traveler_details where id='$sq_payment_info[tourwise_traveler_id]'"));
  
  $module_name = "Group Booking";
  $module_entry_id = $payment_id;
  $payment_date = $sq_payment_info['date'];
  $payment_amount = $payment_amount;
  $payment_mode = $payment_mode;
  $bank_name = $bank_name;
  $transaction_id = $transaction_id;
  $bank_id = $bank_id;
  $particular = get_sales_paid_particular(get_group_booking_payment_id($payment_id,$yr1), $sq_payment_info['date'], $payment_amount, $sq_booking['customer_id'], $payment_mode, get_group_booking_id($sq_payment_info['tourwise_traveler_id'],$yr1));
  $clearance_status = $clearance_status;
  $payment_side = "Debit";
  $payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";

  $bank_cash_book_master->bank_cash_book_master_update($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type);
}
///////////////////////////////////***** Payment Master Update end *********/////////////////////////////////////////////////////////////
  //////////////////////////////////**Reciept for booking is saved and update here..

 function booking_registration_reciept_save( $tourwise_id, $max_payment_id, $payment_of)
 {

  $sq_payment_amt = mysql_fetch_assoc(mysql_query("select amount from payment_master where payment_id='$max_payment_id'"));

  if($sq_payment_amt['amount']!=0)
  {

    $max_receipt = mysql_fetch_assoc(mysql_query("select max(receipt_id) as max from receipt_master"));
    $max_receipt_id = $max_receipt['max'] + 1;

    //$cur_date = date('Y-m-d');

    $sq_receipt_date = mysql_fetch_assoc(mysql_query("select date from payment_master where payment_id='$max_payment_id'"));

    //$cur_date = date("Y-m-d", strtotime($cur_date));
   
    $sq = mysql_query(" insert into receipt_master (receipt_id, tourwise_traveler_id, payment_id, receipt_for, receipt_of, receipt_date) values ('$max_receipt_id','$tourwise_id', '$max_payment_id', '', '$payment_of', '$sq_receipt_date[date]')");
    if(!$sq)
    {
      echo "Error for receipt save.";
      exit; 
    }
    
  }    

 }


 //////////////////////////////////**Reciept for booking is saved here end..

//////////////////////////////////**Payment notification message send start**/////////////////////////////////////
 function payment_notification_sms_send($payment_amount, $payment_mode, $tourwise_traveler_id)
 {
    $sq_personal_info = mysql_fetch_assoc(mysql_query("select * from traveler_personal_info where tourwise_traveler_id='$tourwise_traveler_id'"));
    $mobile_no = $sq_personal_info['mobile_no'];
    
    $message = "Dear customer, Thanks for Payment of Rs.".$payment_amount.". We will sent a reciept on your mail id shortly.";
    global $model;
    $model->send_message($mobile_no, $message);

    // Dear ( Customer Name), Thanks for Payment of Rs.".$payment_amount.". We will sent  a reciept on your mail id shortly. 

 }
 //////////////////////////////////**Payment notification message send end**/////////////////////////////////////

 //////////////////////////////////**Payment email notification send start**/////////////////////////////////////
 public function payment_email_notification_send($tourwise_traveler_id, $payment_amount, $payment_mode, $payment_date)
 {
   $sq_total_paid = mysql_fetch_assoc(mysql_query("select sum(amount) as sum from payment_master where tourwise_traveler_id='$tourwise_traveler_id' and clearance_status!='Cancelled'"));
   $paid_amount = $sq_total_paid['sum'];

   $sq_tourwise = mysql_fetch_assoc(mysql_query("select * from tourwise_traveler_details where id='$tourwise_traveler_id'"));
   $date = $sq_tourwise['form_date'];
   $yr = explode("-", $date);
   $year =$yr[0];
   $total_amount = $sq_tourwise['total_travel_expense'] + $sq_tourwise['total_tour_fee'];

   $sq_personal = mysql_fetch_assoc(mysql_query("select email_id from traveler_personal_info where tourwise_traveler_id='$tourwise_traveler_id'"));
   $email_id = $sq_personal['email_id'];
   $subject = 'Payment Acknowledgement (Booking ID : '.get_group_booking_id($tourwise_traveler_id,$year).' )';
   $payment_id = get_group_booking_payment_id($payment_id,$year);
   
   global $model;
   $model->generic_payment_mail('44',$payment_amount, $payment_mode, $total_amount, $paid_amount, $payment_date, $email_id,$subject, $payment_id);
 }
 //////////////////////////////////**Payment email notification send end**/////////////////////////////////////
//////////////////////////////////** Advance Payment email notification send start**/////////////////////////////////////
 public function payment_email_notification_send_adv($tourwise_traveler_id, $payment_amount1, $payment_date)
 {
   $sq_total_paid = mysql_fetch_assoc(mysql_query("select sum(amount) as sum from payment_master where tourwise_traveler_id='$tourwise_traveler_id' and clearance_status!='Cancelled'"));
   $paid_amount = $sq_total_paid['sum'];

   $sq_tourwise = mysql_fetch_assoc(mysql_query("select * from tourwise_traveler_details where id='$tourwise_traveler_id'"));
   $total_amount = $sq_tourwise['total_travel_expense'] + $sq_tourwise['total_tour_fee'];

   $date = $sq_tourwise['form_date'];
   $yr = explode("-", $date);
   $year =$yr[0];
   $subject = 'Group Booking Payment Correction (Booking ID : '.get_group_booking_id($tourwise_traveler_id,$year).' )';
   $sq_personal = mysql_fetch_assoc(mysql_query("select email_id from traveler_personal_info where tourwise_traveler_id='$tourwise_traveler_id'"));
   $email_id = $sq_personal['email_id'];

   //$payment_id = get_group_booking_payment_id($payment_id,$yr1);
   
   global $model;
   $model->generic_adv_payment_mail($payment_amount1, $total_amount, $paid_amount, $payment_date, $email_id, $subject);
 }
 //////////////////////////////////** Advance Payment email notification send end**/////////////////////////////////////

 //////////////////////////////////**Payment update email notification send start**/////////////////////////////////////
 public function payment_update_email_notification_send($payment_id){

   $sq_payment_info = mysql_fetch_assoc(mysql_query("select * from payment_master where payment_id='$payment_id' and clearance_status!='Cancelled'"));
   $payment_amount = $sq_payment_info['amount'];
   $payment_mode = $sq_payment_info['payment_mode'];
   $payment_date = $sq_payment_info['date'];
   $tourwise_traveler_id = $sq_payment_info['tourwise_traveler_id'];
   $update_payment = true;

   $sq_total_paid = mysql_fetch_assoc(mysql_query("select sum(amount) as sum from payment_master where tourwise_traveler_id='$tourwise_traveler_id' and clearance_status!='Cancelled'"));
   $paid_amount = $sq_total_paid['sum'];

   $sq_tourwise = mysql_fetch_assoc(mysql_query("select total_travel_expense, total_tour_fee from tourwise_traveler_details where id='$tourwise_traveler_id'"));
   $total_amount = $sq_tourwise['total_travel_expense'] + $sq_tourwise['total_tour_fee'];

   $date = $sq_tourwise['form_date'];
   $yr = explode("-", $date);
   $year =$yr[0];

   $sq_personal = mysql_fetch_assoc(mysql_query("select email_id from traveler_personal_info where tourwise_traveler_id='$tourwise_traveler_id'"));
   $email_id = $sq_personal['email_id'];
   $subject = 'Group Tour Payment Correction (Booking ID : '.get_group_booking_id($tourwise_traveler_id,$year).' )';
   //$payment_id = get_group_booking_payment_id($payment_id,$yr1);

   global $model;
   $model->generic_payment_mail('55',$payment_amount, $payment_mode, $total_amount, $paid_amount, $payment_date, $email_id,$subject,$update_payment);
 }
 //////////////////////////////////**Payment update email notification send end**/////////////////////////////////////


}
?>