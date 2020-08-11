<?php 
class booking_save_transaction
{
function finance_save($tourwise_traveler_id, $row_spec, $branch_admin_id)
{
  global $transaction_master;
  $row_spec = 'sales';
  $customer_id = $_POST['customer_id'];
  $form_date = $_POST['form_date'];
  $taxation_type = $_POST['taxation_type'];

  //** Traveling information overall
  $train_expense = $_POST['train_expense'];
  $train_service_charge = $_POST['train_service_charge'];
  $train_taxation_id = $_POST['train_taxation_id'];
  $train_service_tax_subtotal = $_POST['train_service_tax_subtotal'];
  $total_train_expense = $_POST['total_train_expense'];
  
  $plane_expense = $_POST['plane_expense'];
  $plane_service_charge = $_POST['plane_service_charge'];
  $plane_taxation_id = $_POST['plane_taxation_id'];
  $plane_service_tax_subtotal = $_POST['plane_service_tax_subtotal'];
  $total_plane_expense = $_POST['total_plane_expense'];

  $cruise_expense = $_POST['cruise_expense'];
  $cruise_service_charge = $_POST['cruise_service_charge'];
  $cruise_taxation_id = $_POST['cruise_taxation_id'];
  $cruise_service_tax_subtotal = $_POST['cruise_service_tax_subtotal'];
  $total_cruise_expense = $_POST['total_cruise_expense'];
  
  $visa_amount = $_POST['visa_amount'];
  $visa_service_charge = $_POST['visa_service_charge'];
  $visa_taxation_id = $_POST['visa_taxation_id'];
  $visa_service_tax_subtotal = $_POST['visa_service_tax_subtotal'];
  $visa_total_amount = $_POST['visa_total_amount'];
  
  $insuarance_amount = $_POST['insuarance_amount'];
  $insuarance_service_charge = $_POST['insuarance_service_charge'];
  $insuarance_taxation_id = $_POST['insuarance_taxation_id'];
  $insuarance_service_tax_subtotal = $_POST['insuarance_service_tax_subtotal'];
  $insuarance_total_amount = $_POST['insuarance_total_amount'];


  //**tour details
  $adult_expense = $_POST['adult_expense'];
  $child_b_expense = $_POST['child_b_expense'];
  $child_wb_expense = $_POST['child_wb_expense'];
  $infant_expense = $_POST['infant_expense'];
  $tour_fee = $_POST['tour_fee'];
  $repeater_discount = $_POST['repeater_discount'];
  $adjustment_discount = $_POST['adjustment_discount'];
  $tour_fee_subtotal_1 = $_POST['tour_fee_subtotal_1'];
  $service_tax_per = $_POST['service_tax_per'];
  $tour_taxation_id = $_POST['tour_taxation_id'];
  $service_tax = $_POST['service_tax'];
  $tour_fee_subtotal_2 = $_POST['tour_fee_subtotal_2'];
  $total_tour_fee = $_POST['total_tour_fee'];  
  $total_travel_expense = $_POST['total_travel_expense'];  

  //**Payment details
  $payment_date = $_POST['payment_date'];
  $payment_mode = $_POST['payment_mode'];
  $payment_amount = $_POST['payment_amount'];
  $bank_name = $_POST['bank_name'];
  $transaction_id = $_POST['transaction_id'];
  $payment_for = $_POST['payment_for'];
  $p_travel_type = $_POST['p_travel_type'];
  $bank_id_arr = $_POST['bank_id_arr'];

  $booking_date = get_date_db($form_date);
	$year1 = explode("-", $booking_date);
	$yr1 =$year1[0];
  
  $total_sale_amount = $train_expense + $train_service_charge + $plane_expense + $plane_service_charge + $cruise_expense + $cruise_service_charge + $visa_amount + $visa_service_charge + $insuarance_amount + $insuarance_service_charge + $tour_fee;

  $tax_amount = $train_service_tax_subtotal + $plane_service_tax_subtotal + $cruise_service_tax_subtotal + $visa_service_tax_subtotal + $insuarance_service_tax_subtotal + $service_tax;
  ////////////Sales/////////////

    $module_name = "Group Booking";
    $module_entry_id = $tourwise_traveler_id;
    $transaction_id = "";
    $payment_amount = $total_sale_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_group_booking_id($tourwise_traveler_id,$yr1), $booking_date, $total_sale_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Group Tour Sales');
    $gl_id = 59;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    /////////Tax Amount/////////
    tax_reflection_update('Group Booking',$tax_amount,$taxation_type,$tourwise_traveler_id,get_group_booking_id($tourwise_traveler_id,$yr1),$booking_date, $customer_id, $row_spec,$branch_admin_id);

    // Discount 
    $total_discount = $repeater_discount + $adjustment_discount;
    $module_name = "Group Booking";
    $module_entry_id = $tourwise_traveler_id;
    $transaction_id = "";
    $payment_amount = $total_discount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_group_booking_id($tourwise_traveler_id,$yr1), $booking_date, $total_discount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Group Tour Sales');
    $gl_id = 36;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

       
}

public function payment_finance_save($booking_id, $payment_id, $payment_date, $payment_mode, $payment_amount, $transaction_id1, $bank_id, $branch_admin_id)
{
  global $transaction_master;

  $customer_id = $_POST['customer_id'];
  $row_spec = 'sales';

  $payment_date = get_date_db($payment_date);
	$year1 = explode("-", $payment_date);
	$yr1 =$year1[0];

  //Getting cash/Bank Ledger
  if($payment_mode == 'Cash') {  $pay_gl = 20; }
  else{ 
    $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
    $pay_gl = $sq_bank['ledger_id'];
   } 


  //////Payment Amount///////
    $module_name = "Group Booking";
    $module_entry_id = $booking_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount;
    $payment_date = $payment_date;
    $payment_particular = get_sales_particular(get_group_booking_id($booking_id,$yr1), $payment_date, $payment_amount, $customer_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $pay_gl;
    $payment_side = "Debit";
    $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);


}

public function bank_cash_book_save($booking_id, $payment_id, $payment_date, $payment_mode, $payment_amount, $transaction_id, $bank_name, $bank_id,$branch_admin_id)
{
  global $transaction_master, $bank_cash_book_master;
  $payment_date = get_date_db($payment_date);
	$year1 = explode("-", $payment_date);
	$yr1 =$year1[0];

  $customer_id = $_POST['customer_id'];

  $module_name = "Group Booking";
  $module_entry_id = $payment_id;
  $payment_date = $payment_date;
  $payment_amount = $payment_amount;
  $payment_mode = $payment_mode;
  $bank_name = $bank_name;
  $transaction_id = $transaction_id;
  $bank_id = $bank_id;
  $particular = get_sales_paid_particular(get_group_booking_payment_id($payment_id,$yr1), $payment_date, $payment_amount, $customer_id, $payment_mode, get_group_booking_id($booking_id,$yr1));
  $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
  $payment_side = "Debit";
  $payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";

  $bank_cash_book_master->bank_cash_book_master_save($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type, $branch_admin_id);
  

}
}
?>