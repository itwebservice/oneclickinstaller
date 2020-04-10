<?php
$flag = true;
class refund_estimate{
public function refund_estimate_update(){
  $row_spec ='sales';
  $booking_id = $_POST['booking_id'];
  $cancel_amount = $_POST['cancel_amount'];
  $total_refund_amount = $_POST['total_refund_amount'];

  begin_t();
  $sq_refund = mysql_query("update car_rental_booking set cancel_amount='$cancel_amount', total_refund_amount='$total_refund_amount' where booking_id='$booking_id'");
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
  	echo "Refund not saved!";
  	exit;
  }
}

public function finance_save($booking_id,$row_spec){
	$booking_id = $_POST['booking_id'];
  $cancel_amount = $_POST['cancel_amount'];
  $total_refund_amount = $_POST['total_refund_amount'];

  $created_at = date("Y-m-d");
	$year1 = explode("-", $created_at);
	$yr1 =$year1[0];

  $sq_car_info = mysql_fetch_assoc(mysql_query("select * from car_rental_booking where booking_id='$booking_id'"));
  $customer_id = $sq_car_info['customer_id'];
  $taxation_type = $sq_car_info['taxation_type'];
  $service_tax_subtotal = $sq_car_info['service_tax_subtotal'];

  //Getting customer Ledger
  $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
  $cust_gl = $sq_cust['ledger_id'];

  $car_sale_amount = $sq_car_info['km_total_fee'] + $sq_car_info['actual_cost'] + $sq_car_info['driver_allowance'] + $sq_car_info['permit_charges'] + $sq_car_info['toll_and_parking'] + $sq_car_info['state_entry_tax'];
  
  global $transaction_master;

    //////////Sales/////////////

    $module_name = "Car Rental Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $car_sale_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_car_rental_booking_id($booking_id,$yr1), $created_at, $car_sale_amount, $customer_id);
    $ledger_particular = '';
    $gl_id = 19;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);

    /////////Tax Amount/////////
    tax_cancel_reflection_update('Car Rental Booking',$service_tax_subtotal,$taxation_type,$booking_id,get_car_rental_booking_id($booking_id,$yr1),$created_at, $customer_id, $row_spec);

    ////////Customer Sale Amount//////
    $module_name = "Car Rental Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $sq_car_info['total_fees'];
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_car_rental_booking_id($booking_id,$yr1), $created_at, $sq_car_info['total_fees'], $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Cancel Amount//////
    $module_name = "Car Rental Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_car_rental_booking_id($booking_id,$yr1), $customer_id);
    $ledger_particular = '';
    $gl_id = 161;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Customer Cancel Amount//////
    $module_name = "Car Rental Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_car_rental_booking_id($booking_id,$yr1), $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular); 
  
}





}

?>