<?php 

$flag = true;

class booking_refund_estimate{



public function refund_estimate_update()

{
    $row_spec= 'sales';
	$booking_id = $_POST['booking_id'];  
    $cancel_amount = $_POST['cancel_amount'];
    $total_refund_amount = $_POST['total_refund_amount'];

	$sq_booking = mysql_fetch_assoc(mysql_query("select customer_id, taxation_type from package_tour_booking_master where booking_id='$booking_id'"));

    $customer_id = $sq_booking['customer_id'];

	$taxation_type = $sq_booking['taxation_type'];



	begin_t();



		$created_at = date('Y-m-d H:i:s');



		$sq_max = mysql_fetch_assoc(mysql_query("select max(estimate_id) as max from package_refund_traveler_estimate"));

		$estimate_id = $sq_max['max'] + 1;
        $q = "insert into package_refund_traveler_estimate(estimate_id, booking_id, cancel_amount, total_refund_amount, created_at) values ('$estimate_id', '$booking_id', '$cancel_amount', '$total_refund_amount', '$created_at')";
		$sq_est = mysql_query($q);		

	if($sq_est){

		if($GLOBALS['flag']){

            $this->finance_save($booking_id,$row_spec);
			commit_t();

			echo "Refund Estimate has been successfully saved.";

			exit;

		}

		else{

			rollback_t();

			exit;	

		}



	}

	else{

		rollback_t();

		echo "error--Sorry, Cancellation not done!";

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

  $sq_pck_info = mysql_fetch_assoc(mysql_query("select * from package_tour_booking_master where booking_id='$booking_id'"));
  $customer_id = $sq_pck_info['customer_id'];
  $taxation_type = $sq_pck_info['taxation_type'];

  $total_sale_amount = $sq_pck_info['train_expense'] + $sq_pck_info['train_service_charge'] + $sq_pck_info['plane_expense'] + $sq_pck_info['plane_service_charge'] + $sq_pck_info['cruise_expense'] + $sq_pck_info['cruise_service_charge'] + $sq_pck_info['visa_amount'] + $sq_pck_info['visa_service_charge'] + $sq_pck_info['insuarance_amount'] + $sq_pck_info['insuarance_service_charge'] + $sq_pck_info['subtotal'];
  $tax_amount = $sq_pck_info['train_service_tax_subtotal'] + $sq_pck_info['plane_service_tax_subtotal'] + $sq_pck_info['cruise_service_tax_subtotal'] + $sq_pck_info['visa_service_tax_subtotal'] + $sq_pck_info['insuarance_service_tax_subtotal'] + $sq_pck_info['tour_service_tax_subtotal'];

  //Getting customer Ledger
  $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
  $cust_gl = $sq_cust['ledger_id'];

  global $transaction_master;

    //////////Sales/////////////

    $module_name = "Package Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $total_sale_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_package_booking_id($booking_id,$yr1), $created_at, $total_sale_amount, $customer_id);
    $ledger_particular = '';
    $gl_id = 92;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);

    /////////Tax Amount/////////
    tax_cancel_reflection_update('Package Booking',$tax_amount,$taxation_type,$booking_id,get_package_booking_id($booking_id,$yr1),$created_at, $customer_id, $row_spec);

    ////////Customer Sale Amount//////
    $module_name = "Package Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $sq_pck_info['actual_tour_expense'];
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_package_booking_id($booking_id,$yr1), $created_at, $sq_pck_info['actual_tour_expense'], $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Cancel Amount//////
    $module_name = "Package Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_package_booking_id($booking_id,$yr1), $customer_id);
    $ledger_particular = '';
    $gl_id = 161;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Customer Cancel Amount//////
    $module_name = "Package Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_package_booking_id($booking_id,$yr1), $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular); 

}
}

?>