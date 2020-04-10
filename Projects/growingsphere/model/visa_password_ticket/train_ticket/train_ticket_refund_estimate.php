<?php 

$flag = true;

class train_ticket_refund_estimate{



public function refund_estimate_update()

{
  $row_spec ='sales';
  $train_ticket_id = $_POST['train_ticket_id'];
  $cancel_amount = $_POST['cancel_amount'];
  $total_refund_amount = $_POST['total_refund_amount'];

  begin_t();

  $sq_refund = mysql_query("update train_ticket_master set cancel_amount='$cancel_amount', refund_net_total='$total_refund_amount' where train_ticket_id='$train_ticket_id'");

  if($sq_refund){
  	//Finance save

    $this->finance_save($train_ticket_id,$row_spec);



  	if($GLOBALS['flag']){

  		commit_t();

  		echo "Refund estimate has been successfully saved.";

  		exit;

  	}

  	else{

  		rollback_t();

  		exit;

  	}



  }

  else{

  	rollback_t();

  	echo "Cancellation not saved!";

  	exit;

  }



}



public function finance_save($train_ticket_id,$row_spec){

	$train_ticket_id = $_POST['train_ticket_id'];
  $cancel_amount = $_POST['cancel_amount'];
  $total_refund_amount = $_POST['total_refund_amount'];

  $created_at = date("Y-m-d");
	$year2 = explode("-", $created_at);
	$yr2 =$year2[0];

  $sq_train_info = mysql_fetch_assoc(mysql_query("select * from train_ticket_master where train_ticket_id='$train_ticket_id'"));
  $customer_id = $sq_train_info['customer_id'];
  $taxation_type = $sq_train_info['taxation_type'];
  $service_tax_subtotal = $sq_train_info['service_tax_subtotal'];

  //Getting customer Ledger
  $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
  $cust_gl = $sq_cust['ledger_id'];

  $sale_amount = $sq_train_info['basic_fair'] + $sq_train_info['service_charge'];
  global $transaction_master;

    //////////Sales/////////////

    $module_name = "Train Ticket Booking";
    $module_entry_id = $train_ticket_id;
    $transaction_id = "";
    $payment_amount = $sale_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_train_ticket_booking_id($train_ticket_id,$yr2), $created_at, $sale_amount, $customer_id);
    $ledger_particular = '';
    $gl_id = 134;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);

    ///////// Delivery charges //////////
      $module_name = "Train Ticket Booking";
      $module_entry_id = $train_ticket_id;
      $transaction_id = "";
      $payment_amount = $sq_train_info['delivery_charges'];
      $payment_date = $created_at;
      $payment_particular = get_sales_particular(get_train_ticket_booking_id($train_ticket_id,$yr2), $created_at, $sq_train_info['delivery_charges'], $customer_id);
      $gl_id = 33;
      $payment_side = "Debit";
      $clearance_status = "";
      $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id);

    /////////Tax Amount/////////
    tax_cancel_reflection_update('Train Ticket Booking',$service_tax_subtotal,$taxation_type,$train_ticket_id,get_train_ticket_booking_id($train_ticket_id,$yr2),$created_at, $customer_id, $row_spec);

    ////////Customer Sale Amount//////
    $module_name = "Train Ticket Booking";
    $module_entry_id = $train_ticket_id;
    $transaction_id = "";
    $payment_amount = $sq_train_info['net_total'];
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_train_ticket_booking_id($train_ticket_id,$yr2), $created_at, $sq_train_info['net_total'], $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Cancel Amount//////
    $module_name = "Train Ticket Booking";
    $module_entry_id = $train_ticket_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_train_ticket_booking_id($train_ticket_id,$yr2), $customer_id);
    $ledger_particular = '';
    $gl_id = 161;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Customer Cancel Amount//////
    $module_name = "Train Ticket Booking";
    $module_entry_id = $train_ticket_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_train_ticket_booking_id($train_ticket_id,$yr2), $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular); 
}





}

?>