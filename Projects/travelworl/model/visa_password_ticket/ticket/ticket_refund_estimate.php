<?php 

$flag = true;

class ticket_refund_estimate{



public function refund_estimate_update()

{
  $row_spec ='sales';

  $ticket_id = $_POST['ticket_id'];
  $cancel_amount = $_POST['cancel_amount'];
  $total_refund_amount = $_POST['total_refund_amount'];

  begin_t();

  $sq_refund = mysql_query("update ticket_master set cancel_amount='$cancel_amount', total_refund_amount='$total_refund_amount' where ticket_id='$ticket_id'");

  if($sq_refund){



  	//Finance save

    $this->finance_save($ticket_id,$row_spec);



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



public function finance_save($ticket_id,$row_spec)
{

	$ticket_id = $_POST['ticket_id'];
  $cancel_amount = $_POST['cancel_amount'];
  $total_refund_amount = $_POST['total_refund_amount'];

  $created_at = date("Y-m-d");
	$year1 = explode("-", $created_at);
	$yr1 =$year1[0];

  $sq_ticket = mysql_fetch_assoc(mysql_query("select * from ticket_master where ticket_id='$ticket_id'"));
  $customer_id = $sq_ticket['customer_id'];
  $taxation_type = $sq_ticket['taxation_type'];
  $service_tax_subtotal = $sq_ticket['service_tax_subtotal'];

  //Getting customer Ledger
  $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
  $cust_gl = $sq_cust['ledger_id'];

  $total_sale = $sq_ticket['basic_cost'] + $sq_ticket['basic_cost_markup'] + $sq_ticket['yq_tax'] + $sq_ticket['yq_tax_markup'] + $sq_ticket['g1_plus_f2_tax']  + $sq_ticket['service_charge'] - $sq_ticket['yq_tax_discount'];
  global $transaction_master;

    //////////Sales/////////////

    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $total_sale;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $created_at, $total_sale, $customer_id);
    $ledger_particular = '';
    $gl_id = 51;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);

    /////////TDS////////
    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $sq_ticket['tds'];
    $payment_date = $booking_date;
    $payment_particular = get_cancel_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $created_at, $total_sale, $customer_id);
    $ledger_particular = get_ledger_particular('To','Flight Ticket Sales');
    $gl_id = 127;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);


    /////////Discount////////
    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $sq_ticket['basic_cost_discount'];
    $payment_date = $booking_date;
    $payment_particular = get_cancel_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $created_at, $total_sale, $customer_id);
    $ledger_particular = get_ledger_particular('To','Flight Ticket Sales');
    $gl_id = 36;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    /////////Tax Amount/////////
    tax_cancel_reflection_update('Air Ticket Booking',$service_tax_subtotal,$taxation_type,$ticket_id,get_ticket_booking_id($ticket_id,$yr1),$created_at, $customer_id, $row_spec);

    ////////Customer Sale Amount//////
    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $sq_ticket['ticket_total_cost'];
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $created_at, $sq_ticket['ticket_total_cost'], $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Cancel Amount//////
    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $customer_id);
    $ledger_particular = '';
    $gl_id = 161;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Customer Cancel Amount//////
    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular); 

}






}

?>