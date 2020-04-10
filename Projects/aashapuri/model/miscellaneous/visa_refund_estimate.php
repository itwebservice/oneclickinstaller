<?php 
$flag = true;
class miscellaneous_refund_estimate{

public function refund_estimate_update()
{
  $row_spec="sales";
  $misc_id = $_POST['misc_id'];
  $cancel_amount = $_POST['cancel_amount'];
  $total_refund_amount = $_POST['total_refund_amount'];

  begin_t();

  $sq_refund = mysql_query("update miscellaneous_master set cancel_amount='$cancel_amount', total_refund_amount='$total_refund_amount' where misc_id='$misc_id'");
  if($sq_refund)
  {
  	//Finance save
    $this->finance_save($misc_id,$row_spec);

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

public function finance_save($misc_id,$row_spec)
{
	$misc_id = $_POST['misc_id'];
	$cancel_amount = $_POST['cancel_amount'];
	$total_refund_amount = $_POST['total_refund_amount'];

	$created_at = date("Y-m-d");
	$year1 = explode("-", $created_at);
	$yr1 =$year1[0];

	$sq_sq_visa_info = mysql_fetch_assoc(mysql_query("select * from miscellaneous_master where misc_id='$misc_id'"));
  $customer_id = $sq_sq_visa_info['customer_id'];
	$taxation_type = $sq_sq_visa_info['taxation_type'];
  $service_tax_subtotal = $sq_sq_visa_info['service_tax_subtotal'];

  //Getting customer Ledger
  $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
  $cust_gl = $sq_cust['ledger_id'];

  $visa_sale_amount = $sq_sq_visa_info['misc_issue_amount'] + $sq_sq_visa_info['service_charge'];
  global $transaction_master;

    //////////Sales/////////////

    $module_name = "Miscellaneous Booking";
    $module_entry_id = $misc_id;
    $transaction_id = "";
    $payment_amount = $visa_sale_amount;
    $payment_date = $created_at;
    $payment_particular = get_sales_particular(get_misc_booking_id($misc_id,$yr1), $created_at, $visa_sale_amount, $customer_id);
    $ledger_particular = '';
    $gl_id = 170;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);

    /////////Tax Amount/////////
    tax_cancel_reflection_update('Miscellaneous Booking',$service_tax_subtotal,$taxation_type,$misc_id,get_misc_booking_id($misc_id,$yr1),$created_at, $customer_id, $row_spec);

    ////////Customer Sale Amount//////
    $module_name = "Miscellaneous Booking";
    $module_entry_id = $misc_id;
    $transaction_id = "";
    $payment_amount = $sq_sq_visa_info['misc_total_cost'];
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_misc_booking_id($misc_id,$yr1), $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Cancel Amount//////
    $module_name = "Miscellaneous Booking";
    $module_entry_id = $misc_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_misc_booking_id($misc_id,$yr1), $customer_id);
    $ledger_particular = '';
    $gl_id = 161;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Customer Cancel Amount//////
    $module_name = "Miscellaneous Booking";
    $module_entry_id = $misc_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_misc_booking_id($misc_id,$yr1), $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular); 

}


}

?>