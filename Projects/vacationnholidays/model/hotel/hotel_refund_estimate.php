<?php 

$flag = true;

class hotel_refund_estimate{



public function refund_estimate_update()

{
  $row_spec='sales';
  $booking_id = $_POST['booking_id'];
  $cancel_amount = $_POST['cancel_amount'];
  $total_refund_amount = $_POST['total_refund_amount'];



  begin_t();



  $sq_refund = mysql_query("update hotel_booking_master set cancel_amount='$cancel_amount', refund_total_fee='$total_refund_amount'  where booking_id='$booking_id'");

  if($sq_refund){



  	//Finance save

    $this->finance_save($booking_id,$row_spec);



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

  	echo "Refund estimate has not been saved!";

  	exit;

  }



}



public function finance_save($booking_id,$row_spec)

{

	$booking_id = $_POST['booking_id'];
  $cancel_amount = $_POST['cancel_amount'];
  $total_refund_amount = $_POST['total_refund_amount'];

  $created_at = date("Y-m-d");
	$year2 = explode("-", $created_at);
  $yr1 =$year2[0];

  $sq_hotel_info = mysql_fetch_assoc(mysql_query("select * from hotel_booking_master where booking_id='$booking_id'"));
  $customer_id = $sq_hotel_info['customer_id'];
  $taxation_type = $sq_hotel_info['taxation_type'];
  $service_tax_subtotal = $sq_hotel_info['service_tax_subtotal'];

  //Getting customer Ledger
  $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
  $cust_gl = $sq_cust['ledger_id'];

  $hotel_amount = $sq_hotel_info['sub_total'] + $sq_hotel_info['service_charge'];
  global $transaction_master;

    //////////Sales/////////////

    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $hotel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_hotel_booking_id($booking_id,$yr1), $created_at, $hotel_amount, $customer_id);
    $ledger_particular = '';
    $gl_id = 64;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);

    /////////Tax Amount/////////
    tax_cancel_reflection_update('Hotel Booking',$service_tax_subtotal,$taxation_type,$booking_id,get_hotel_booking_id($booking_id,$yr1),$created_at, $customer_id, $row_spec);

    //////////Discount/////////////

    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $sq_hotel_info['discount'];
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_hotel_booking_id($booking_id,$yr1), $created_at, $sq_hotel_info['discount'], $customer_id);
    $ledger_particular = '';
    $gl_id = 36;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);

    //////////TDS/////////////

    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $sq_hotel_info['tds'];
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_hotel_booking_id($booking_id,$yr1), $created_at, $sq_hotel_info['tds'], $customer_id);
    $ledger_particular = '';
    $gl_id = 127;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);

    ////////Customer Sale Amount//////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $sq_hotel_info['total_fee'];
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_hotel_booking_id($booking_id,$yr1), $created_at, $sq_hotel_info['total_fee'], $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Cancel Amount//////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_hotel_booking_id($booking_id,$yr1), $customer_id);
    $ledger_particular = '';
    $gl_id = 161;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Customer Cancel Amount//////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_hotel_booking_id($booking_id,$yr1), $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular); 

}





}

?>