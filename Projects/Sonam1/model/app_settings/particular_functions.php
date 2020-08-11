<?php 
//===================== NEW ========================
function get_cash_deposit_particular($bank_id)
{
  $sq_bank_info = mysql_fetch_assoc(mysql_query("select * from bank_master where bank_id='$bank_id'"));
  $particular = "Being Cash deposited in bank ".$sq_bank_info['bank_name'].'('.$sq_bank_info['branch_name'].')';

  return $particular;
}
function get_cash_withdraw_particular($bank_id)
{
  $sq_bank_info = mysql_fetch_assoc(mysql_query("select * from bank_master where bank_id='$bank_id'"));
  $particular = "Being Cash Withdrawn from bank ".$sq_bank_info['bank_name'].'('.$sq_bank_info['branch_name'].')';

  return $particular;
}
function get_bank_transfer_particular($f_bank_id,$t_bank_id)
{
  $sq_bank_info1 = mysql_fetch_assoc(mysql_query("select * from bank_master where bank_id='$f_bank_id'"));
  $sq_bank_info2 = mysql_fetch_assoc(mysql_query("select * from bank_master where bank_id='$t_bank_id'"));

  $particular = "Being amount transferred from ".$sq_bank_info1['bank_name'].'('.$sq_bank_info1['branch_name'].")"."to ".$sq_bank_info2['bank_name'].'('.$sq_bank_info2['branch_name'].')';

  return $particular;
}

function get_cancel_sales_particular($invoice_id, $customer_id)

{


  $sq_customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));

  if($sq_customer_info['type']== 'Corporate'){
    $customer_name = $sq_customer_info['company_name'];
  }else{
    $customer_name = $sq_customer_info['first_name'].' '.$sq_customer_info['last_name'];
  }



  $particular = "Being Sales against Inv. No ".$invoice_id." for ".$customer_name." cancelled";

  return $particular;

}
function get_cancel_purchase_particular($invoice_id)

{

  $particular = "Being purchase against Inv. No ".$invoice_id." cancelled";

  return $particular;

}
//===================================================
function get_sales_particular($invoice_id, $date, $amount, $customer_id)

{

  $date = get_date_user($date);

  $sq_customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));

  if($sq_customer_info['type']== 'Corporate'){
    $customer_name = $sq_customer_info['company_name'];
  }else{
    $customer_name = $sq_customer_info['first_name'].' '.$sq_customer_info['last_name'];
  }



  $particular = "Being Sales booked against Inv. No ".$invoice_id." from ".$customer_name;

  return $particular;

}


function get_purchase_partucular($invoice_id, $date, $amount, $vendor_type, $vendor_type_id)

{

  $date = get_date_user($date);



  $vendor_name = get_vendor_name($vendor_type, $vendor_type_id);



  $particular = "Being Purchases made against Inv. No ".$invoice_id." from ".$vendor_name;

  return $particular;

}

function get_sales_paid_particular($payment_id, $date, $amount, $customer_id, $payment_mode, $invoice)

{

  $date = get_date_user($date);

  $sq_customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));

  
  if($sq_customer_info['type']== 'Corporate'){
    $customer_name = $sq_customer_info['company_name'];
  }else{
    $customer_name = $sq_customer_info['first_name'].' '.$sq_customer_info['last_name'];
  }



  $particular = "Being Payment received against Inv. No ".$invoice." by ".$payment_mode." from ".$customer_name." for ".$payment_id;

  return $particular;

}



function get_purchase_paid_partucular($invoice_id, $date, $amount, $vendor_type, $vendor_type_id, $payment_mode='')

{

  $date = get_date_user($date);



  $vendor_name = get_vendor_name($vendor_type, $vendor_type_id);



  $particular = "Being Payment made against Inv. No ".$invoice_id." by ".$payment_mode." from ".$vendor_name;

  return $particular;

}



function get_expense_paid_particular($invoice_id, $expense_type_id, $date, $amount, $payment_mode)

{

  $date = get_date_user($date);



  $sq_expense_type = mysql_fetch_assoc(mysql_query("select * from ledger_master where ledger_id='$expense_type_id'"));

  $expense_type = $sq_expense_type['ledger_name'];


  if($payment_mode != ''){
    $particular = "Being Payment made against Expense Type ".$expense_type." by ".$payment_mode;
  }
  else{
    $particular = "Being Payment made against Expense Type ".$expense_type;
  }

  return $particular;

}


function get_gst_paid_particular($invoice_id, $date, $amount, $payment_mode)
{

  $date = get_date_user($date);
  if($payment_mode != ''){
    $particular = "Being TAX paid against Invoice No. ".$invoice_id." by payment mode ".$payment_mode;
  }
  else{
    $particular = "Being TAX paid against Invoice No. ".$invoice_id;
  }

  return $particular;

}



function get_salary_paid_particular($login_id, $month, $year, $date)

{

  $date = get_date_user($date);



  $month_year = $month.'/'.$year;



  $sq_login = mysql_fetch_assoc(mysql_query("select emp_id from roles where id='$login_id'"));

  $emp_id = $sq_login['emp_id'];



  $sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$emp_id'"));

  $employee_name = $sq_emp_info['first_name'].' '.$sq_emp_info['last_name'];



  $particular = "Being Salary paid to ".$employee_name." For ".$month_year;

  return $particular;

}


function get_advance_particular($customer_id)
{
  $sq_customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));
  $customer_name = $sq_customer_info['company_name'];

  $particular = "Being Advances received from ".$customer_name;
  return $particular;
}

function get_advance_purchase_particular($supplier_name)
{
  $particular = "Being Advances made to ".$supplier_name;
  return $particular;
}

function get_other_income_particular($payment_mode, $date, $description, $amount)

{

  $date = get_date_user($date);



  $particular = "Being Payment received for ".$description." by ".$payment_mode;

  return $particular;

}

function get_flight_supplier_particular($payment_mode, $date, $amount)

{

  $date = get_date_user($date);



  $particular = "Being Payment made by ".$payment_mode;

  return $particular;

}


function get_visa_supplier_particular($payment_mode, $date, $amount)

{

  $date = get_date_user($date);



  $particular = "Being Payment made ".$payment_mode;

  return $particular;

}


function get_incentive_paid_particular($emp_id)

{

  $sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$emp_id'"));

  $employee_name = $sq_emp_info['first_name'].' '.$sq_emp_info['last_name'];



  $particular = "Being Incentive paid to ".$employee_name;

  return $particular;

}



function get_refund_paid_particular($invoice_id, $date, $amount, $payment_mode, $refund_id)

{

  $date = get_date_user($date);



  if($invoice_id!=''){

    $invoice_str = " against Invoice no ".$invoice_id;  

  }

  else{

    $invoice_str = "";

  }



  $particular = "Refund paid".$invoice_str." by ".$payment_mode." for refund id ".$refund_id;

  return $particular;

}



function get_refund_charges_particular($invoice_id, $refund_id, $payment_mode='')

{


  $particular = "Refund received".$invoice_str." by ".$payment_mode." for refund id ".$refund_id;

  return $particular;

}



function get_ledger_particular($side,$service)
{
  $particular = $side.' '.$service;  
  return $particular;
}

//Opening balance particular
function get_bank_opening_balance_particular($bank_name,$branch_name,$opening_balance,$as_of_date)
{
  $as_of_date = get_date_user($as_of_date);
  $particular = "Being Opening balance added for bank ".$bank_name.'('.$branch_name.') As of date '.$as_of_date.' of amount '.$opening_balance.' Rs.';

  return $particular;
}
function get_sup_opening_balance_particular($vendor_type,$opening_balance,$as_of_date,$username)
{
  $as_of_date = get_date_user($as_of_date);
  $particular = "Being Opening balance added for ".$vendor_type.'('.$username.')'.' As of date '.$as_of_date.' of amount '.$opening_balance.' Rs.';

  return $particular;
}
function get_b2b_deposit_particular($bank_id,$deposit)
{
  $sq_bank_info = mysql_fetch_assoc(mysql_query("select * from bank_master where bank_id='$bank_id'"));
  if($sq_bank_info != ''){ $particular = 'Being Deposit of '.$deposit.' received in bank '.$sq_bank_info['bank_name'].'('.$sq_bank_info['branch_name'].')'; }
  else{
    $particular = 'Being Deposit of '.$deposit.' received by Cash';
  }

  return $particular;
}
?>