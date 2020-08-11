<?php 

$flag = true;

class refund_estimate{



public function refund_estimate_save()

{
      $row_spec ='sales';
	  $booking_id = $_POST['booking_id'];
      $cancel_amount = $_POST['cancel_amount'];
      $total_refund_amount = $_POST['total_refund_amount'];



	//**Starting transaction

	begin_t();



	$sq_est = mysql_query("update bus_booking_master set cancel_amount='$cancel_amount', refund_net_total='$total_refund_amount' where booking_id='$booking_id'");

	if(!$sq_est){

		$GLOBALS['flag'] = false;

		echo "error--Refund estimate has not been saved.";

	}



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




public function finance_save($booking_id,$row_spec)
{
  $cancel_amount = $_POST['cancel_amount'];
  $total_refund_amount = $_POST['total_refund_amount'];

  $created_at = date("Y-m-d");
	$year1 = explode("-", $created_at);
	$yr1 =$year1[0];

  $sq_bus_booking = mysql_fetch_assoc(mysql_query("select * from bus_booking_master where booking_id='$booking_id'"));
  $customer_id = $sq_bus_booking['customer_id'];
  $taxation_type = $sq_bus_booking['taxation_type'];
  $service_tax_subtotal = $sq_bus_booking['service_tax_subtotal'];

  //Getting customer Ledger
  $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
  $cust_gl = $sq_cust['ledger_id'];

  $bus_sale_amount = $sq_bus_booking['basic_cost'] + $sq_bus_booking['service_charge'];
  global $transaction_master;

    //////////Sales/////////////

    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $bus_sale_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_bus_booking_id($booking_id,$yr1), $created_at, $bus_sale_amount, $customer_id);
    $ledger_particular = '';
    $gl_id = 11;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);

    /////////Tax Amount/////////
    tax_cancel_reflection_update('Bus Booking',$service_tax_subtotal,$taxation_type,$booking_id,get_bus_booking_id($booking_id,$yr1),$created_at, $customer_id, $row_spec);

    ////////Customer Sale Amount//////
    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $sq_bus_booking['net_total'];
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_bus_booking_id($booking_id,$yr1), $created_at, $sq_bus_booking['net_total'], $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Cancel Amount//////
    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_bus_booking_id($booking_id,$yr1), $customer_id);
    $ledger_particular = '';
    $gl_id = 161;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Customer Cancel Amount//////
    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_bus_booking_id($booking_id,$yr1), $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular); 

}


}

?>