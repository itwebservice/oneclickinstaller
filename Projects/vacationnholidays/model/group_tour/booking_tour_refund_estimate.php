<?php 

$flag = true;

class booking_tour_refund_estimate{



public function refund_estimate_update()

{
    $row_spec='sales';
    $tourwise_id = $_POST['tourwise_id'];
    $cancel_amount = $_POST['cancel_amount'];
    $total_refund_amount = $_POST['total_refund_amount'];

    $sq_booking = mysql_fetch_assoc(mysql_query("select customer_id, taxation_type from tourwise_traveler_details where id='$tourwise_id'"));
    $customer_id = $sq_booking['customer_id'];
    $taxation_type = $sq_booking['taxation_type'];

    begin_t();

    $created_at = date('Y-m-d H:i:s');
    $sq_max = mysql_fetch_assoc(mysql_query("select max(estimate_id) as max from refund_tour_estimate"));
    $estimate_id = $sq_max['max'] + 1;

    $sq_est = mysql_query("insert into refund_tour_estimate(estimate_id, tourwise_traveler_id, cancel_amount, total_refund_amount, created_at) values ('$estimate_id', '$tourwise_id', '$cancel_amount', '$total_refund_amount', '$created_at')");       

    if($sq_est){

        $this->finance_save($row_spec);

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
        echo "error--Sorry, Cancellation not done!";
        exit;
    }
}


public function finance_save($row_spec)
{
    $tourwise_id = $_POST['tourwise_id'];
    $cancel_amount = $_POST['cancel_amount'];
    $total_refund_amount = $_POST['total_refund_amount'];

    $created_at = date("Y-m-d");
    $year = date('Y');

    $sq_booking = mysql_fetch_assoc(mysql_query("select * from tourwise_traveler_details where id='$tourwise_id'"));
    $customer_id = $sq_booking['customer_id'];
    $taxation_type = $sq_booking['taxation_type'];

    $total_sale_amount = $sq_booking['train_expense'] + $sq_booking['train_service_charge'] + $sq_booking['plane_expense'] + $sq_booking['plane_service_charge'] + $sq_booking['cruise_expense'] + $sq_booking['cruise_service_charge'] + $sq_booking['visa_amount'] + $sq_booking['visa_service_charge'] + $sq_booking['insuarance_amount'] + $sq_booking['insuarance_service_charge'] + $sq_booking['tour_fee'];
    $tax_amount = $sq_booking['train_service_tax_subtotal'] + $sq_booking['plane_service_tax_subtotal'] + $sq_booking['cruise_service_tax_subtotal'] + $sq_booking['visa_service_tax_subtotal'] + $sq_booking['insuarance_service_tax_subtotal'] + $sq_booking['service_tax'];

   //Getting customer Ledger
   $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
   $cust_gl = $sq_cust['ledger_id'];

   global $transaction_master;

    //////////Sales/////////////

    $module_name = "Group Booking";
    $module_entry_id = $tourwise_id;
    $transaction_id = "";
    $payment_amount = $total_sale_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_group_booking_id($tourwise_id,$year), $created_at, $total_sale_amount, $customer_id);
    $ledger_particular = '';
    $gl_id = 60;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);

    /////////Tax Amount/////////
    tax_cancel_reflection_update('Group Booking',$tax_amount,$taxation_type,$tourwise_id,get_group_booking_id($tourwise_id,$year),$created_at, $customer_id, $row_spec);

    // Discount 
    $total_discount = $sq_booking['repeater_discount'] + $sq_booking['adjustment_discount'];
    $module_name = "Group Booking";
    $module_entry_id = $tourwise_id;
    $transaction_id = "";
    $payment_amount = $total_discount;
    $payment_date = $created_at;
    $payment_particular = get_sales_particular(get_group_booking_id($tourwise_id,$year), $booking_date, $total_discount, $customer_id);
    $ledger_particular = '';
    $gl_id = 36;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    ////////Customer Sale Amount//////
    $module_name = "Group Booking";
    $module_entry_id = $tourwise_id;
    $transaction_id = "";
    $payment_amount = $sq_booking['total_tour_fee']+$sq_booking['total_travel_expense'];
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_group_booking_id($tourwise_id,$year), $created_at, $sq_booking['total_tour_fee']+$sq_booking['total_travel_expense'], $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Cancel Amount//////
    $module_name = "Group Booking";
    $module_entry_id = $tourwise_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_group_booking_id($tourwise_id,$year), $customer_id);
    $ledger_particular = '';
    $gl_id = 161;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);    

    ////////Customer Cancel Amount//////
    $module_name = "Group Booking";
    $module_entry_id = $tourwise_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = get_cancel_sales_particular(get_group_booking_id($tourwise_id,$year), $customer_id);
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular); 

}

}

?>