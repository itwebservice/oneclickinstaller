<?php
$flag = true;
class vendor_estimate_master{
public function vendor_estimate_save(){
    $row_spec = 'purchase';
    $estimate_type = $_POST['estimate_type'];
    $estimate_type_id = $_POST['estimate_type_id'];
    $vendor_type_arr = $_POST['vendor_type_arr'];
    $vendor_type_id_arr = $_POST['vendor_type_id_arr'];
    $basic_cost_arr = $_POST['basic_cost_arr'];
    $non_recoverable_taxes_arr = $_POST['non_recoverable_taxes_arr'];
    $service_charge_arr = $_POST['service_charge_arr'];
    $other_charges_arr = $_POST['other_charges_arr'];
    $taxation_type_arr = $_POST['taxation_type_arr'];
    $taxation_id_arr = $_POST['taxation_id_arr'];
    $service_tax_arr = $_POST['service_tax_arr'];
    $service_tax_subtotal_arr = $_POST['service_tax_subtotal_arr'];
    $discount_arr = $_POST['discount_arr'];
    $our_commission_arr = $_POST['our_commission_arr'];
    $tds_arr = $_POST['tds_arr'];
    $net_total_arr = $_POST['net_total_arr'];
    $remark_arr = $_POST['remark_arr'];
    $invoice_url_arr = $_POST['invoice_url_arr'];
    $invoice_id_arr = $_POST['invoice_id_arr'];
    $payment_due_date_arr = $_POST['payment_due_date_arr'];
    $purchase_date_arr = $_POST['purchase_date_arr'];
    $branch_admin_id = $_POST['branch_admin_id'];
    $emp_id = $_POST['emp_id'];

    $financial_year_id = $_SESSION['financial_year_id'];
    $created_at = date('Y-m-d H:i:s');
   
    begin_t();

    for($i=0; $i<sizeof($basic_cost_arr); $i++){

            $sq_max = mysql_fetch_assoc(mysql_query("select max(estimate_id) as max from vendor_estimate"));
            $estimate_id = $sq_max['max'] + 1;

            $payment_due_date_arr1[$i] = get_date_db($payment_due_date_arr[$i]);
            $purchase_date_arr1[$i] = get_date_db($purchase_date_arr[$i]);

            $remark_arr1 = addslashes($remark_arr[$i]);
            $sq_est = mysql_query("insert into vendor_estimate(estimate_id, estimate_type, estimate_type_id, branch_admin_id,financial_year_id, emp_id, vendor_type, vendor_type_id, basic_cost, non_recoverable_taxes, service_charge, other_charges, taxation_type, taxation_id, service_tax, service_tax_subtotal, discount, our_commission, tds, net_total, remark, created_at, invoice_proof_url, invoice_id, due_date, purchase_date) values('$estimate_id', '$estimate_type', '$estimate_type_id', '$branch_admin_id','$financial_year_id', '$emp_id', '$vendor_type_arr[$i]', '$vendor_type_id_arr[$i]', '$basic_cost_arr[$i]', '$non_recoverable_taxes_arr[$i]', '$service_charge_arr[$i]', '$other_charges_arr[$i]', '$taxation_type_arr[$i]', '$taxation_id_arr[$i]', '$service_tax_arr[$i]', '$service_tax_subtotal_arr[$i]', '$discount_arr[$i]', '$our_commission_arr[$i]', '$tds_arr[$i]', '$net_total_arr[$i]', '$remark_arr1', '$created_at','$invoice_url_arr[$i]','$invoice_id_arr[$i]','$payment_due_date_arr1[$i]','$purchase_date_arr1[$i]')");
            if(!$sq_est){
                $GLOBALS['flag'] = false;
                echo "error--Supplier Cost not saved!";     
            }
            else{
                //Send Mail
                $booking_id = get_estimate_type_name($estimate_type, $estimate_type_id);
                $supplier_name = get_vendor_name($vendor_type_arr[$i], $vendor_type_id_arr[$i]);
                $supplier_email = get_vendor_email($vendor_type_arr[$i], $vendor_type_id_arr[$i]);
                $date = $purchase_date_arr1[$i];
                $yr = explode("-", $date);
                $year =$yr[0];
                $estimate_id1 = get_vendor_estimate_id($estimate_id,$year);
                $this->purchase_mail_send($estimate_id1,$booking_id,$supplier_name,$supplier_email,$estimate_type);

                //Finance Save
                $this->finance_save($estimate_id, $vendor_type_arr[$i], $vendor_type_id_arr[$i], $basic_cost_arr[$i], $non_recoverable_taxes_arr[$i], $service_charge_arr[$i], $other_charges_arr[$i], $taxation_type_arr[$i], $taxation_id_arr[$i], $service_tax_arr[$i], $service_tax_subtotal_arr[$i], $discount_arr[$i], $our_commission_arr[$i], $tds_arr[$i], $net_total_arr[$i], $row_spec,$branch_admin_id,$purchase_date_arr1[$i]);
            }
    }
    if($GLOBALS['flag']){
        commit_t();
        echo "Purchase has been successfully saved." ;
        exit;
    }
    else{
        rollback_t();
        exit;
    }

}
public function purchase_mail_send($estimate_id,$booking_id,$supplier_name,$supplier_email,$estimate_type){
  global $app_email_id, $app_name, $app_contact_no, $admin_logo_url, $app_website;

  $content = '
    <table style="color:#22262e;font-size:13px;width:90%;margin-bottom:20px">
           
      <tr>
         <td colspan="2"><strong>Purchase Type : '.$estimate_type.'</strong></td>
      </tr>
      <tr>
         <td colspan="2"><strong>Purchase ID : '.$booking_id.'</strong></td>
      </tr>    
    </table>
  ';
  $subject = 'Purchase Confirmation! (Purchase ID : '.$booking_id.' )';
  global $model;
  $model->app_email_send('25',$supplier_email,$content, $subject);

}
 
public function finance_save($estimate_id, $vendor_type, $vendor_type_id, $basic_cost, $non_recoverable_taxes, $service_charge, $other_charges, $taxation_type, $taxation_id, $service_tax, $service_tax_subtotal, $discount, $our_commission, $tds, $net_total,$row_spec,$branch_admin_id,$purchase_date_arr1){
    
    global $transaction_master;

    $purchase_gl = get_vendor_purchase_gl_id($vendor_type, $vendor_type_id);
    $purchase_amount =  $basic_cost + $non_recoverable_taxes + $service_charge + $other_charges;
    $created_at = get_date_db($purchase_date_arr1);
	$year1 = explode("-", $created_at);
	$yr1 =$year1[0];

    //Getting supplier Ledger
    $q = "select * from ledger_master where group_sub_id='105' and customer_id='$vendor_type_id' and user_type='$vendor_type'";
    $sq_sup = mysql_fetch_assoc(mysql_query($q));
    $supplier_gl = $sq_sup['ledger_id'];
    ////////////purchase/////////////
    $module_name = $vendor_type;
    $module_entry_id = $estimate_id;
    $transaction_id = "";
    $payment_amount = $purchase_amount;
    $payment_date = $created_at;
    $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $created_at, $purchase_amount, $vendor_type, $vendor_type_id);
    $ledger_particular = get_ledger_particular('For',$vendor_type.' Purchase');
    $gl_id = $purchase_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    /////////Tax Amount/////////
    purchase_tax_reflection_update($vendor_type,$service_tax_subtotal,$taxation_type,$estimate_id,get_vendor_estimate_id($estimate_id,$yr1),$created_at,'', $row_spec);

    ////// Discount ////////////
    $module_name = $vendor_type;
    $module_entry_id = $estimate_id;
    $transaction_id = "";
    $payment_amount = $discount;
    $payment_date = $created_at;
    $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $created_at, $discount, $vendor_type, $vendor_type_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = 37;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    ////// Commision ////////////
    $module_name = $vendor_type;
    $module_entry_id = $estimate_id;
    $transaction_id = "";
    $payment_amount = $our_commission;
    $payment_date = $created_at;
    $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $created_at, $our_commission, $vendor_type, $vendor_type_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = 25;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    ////// Tds receivable ////////////
    $module_name = $vendor_type;
    $module_entry_id = $estimate_id;
    $transaction_id = "";
    $payment_amount = $tds;
    $payment_date = $created_at;
    $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $created_at, $tds, $vendor_type, $vendor_type_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = 127;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    //Supplier
    $module_name = $vendor_type;
    $module_entry_id = $estimate_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $net_total;
    $payment_date = $created_at;
    $payment_particular = get_purchase_partucular(get_vendor_payment_id($estimate_id,$yr1), $created_at, $net_total, $vendor_type, $vendor_type_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $supplier_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
}

public function vendor_estimate_update(){
    $row_spec ='purchase';
    $estimate_id = $_POST['estimate_id'];
    $estimate_type = $_POST['estimate_type'];
    $vendor_type = $_POST['vendor_type'];   
    $estimate_type_id = $_POST['estimate_type_id'];
    $vendor_type_id = $_POST['vendor_type_id'];

    $basic_cost = $_POST['basic_cost'];
    $non_recoverable_taxes = $_POST['non_recoverable_taxes'];
    $service_charge = $_POST['service_charge'];
    $other_charges = $_POST['other_charges'];
    $taxation_id = $_POST['taxation_id'];
    $taxation_type = $_POST['taxation_type'];
    $service_tax = $_POST['service_tax'];
    $service_tax_subtotal = $_POST['service_tax_subtotal'];
    $discount = $_POST['discount'];
    $our_commission = $_POST['our_commission'];
    $tds = $_POST['tds'];
    $net_total = $_POST['net_total'];
    $remark = $_POST['remark'];
    $invoice_url = $_POST['invoice_url'];
    $invoice_id = $_POST['invoice_id'];
    $payment_due_date = $_POST['payment_due_date'];
    $purchase_date = $_POST['purchase_date'];

    $payment_due_date = get_date_db($payment_due_date);
    $purchase_date = get_date_db($purchase_date);

    $sq_estimate_info  = mysql_fetch_assoc(mysql_query("select * from vendor_estimate where estimate_id='$estimate_id'"));

    begin_t();
    $remark1 = addslashes($remark);
    $sq_est = mysql_query("update vendor_estimate set estimate_type='$estimate_type', estimate_type_id='$estimate_type_id', vendor_type='$vendor_type', vendor_type_id='$vendor_type_id', basic_cost='$basic_cost', non_recoverable_taxes='$non_recoverable_taxes', service_charge='$service_charge',taxation_type='$taxation_type', other_charges='$other_charges', taxation_id='$taxation_id', service_tax='$service_tax', service_tax_subtotal='$service_tax_subtotal', discount='$discount', our_commission='$our_commission', tds='$tds', net_total='$net_total', remark='$remark1',invoice_proof_url = '$invoice_url',invoice_id='$invoice_id', due_date='$payment_due_date',purchase_date ='$purchase_date' where estimate_id='$estimate_id'");
    if($sq_est){

        //Finance Update
        //$this->finance_update($sq_estimate_info,$row_spec);
        $this->finance_update($sq_estimate_info,$estimate_id, $vendor_type, $vendor_type_id, $basic_cost, $non_recoverable_taxes, $service_charge, $other_charges, $taxation_type, $taxation_id, $service_tax, $service_tax_subtotal, $discount, $our_commission, $tds, $net_total,$row_spec,$purchase_date);

        if($GLOBALS['flag']){
            commit_t();
            echo "Purchase has been successfully updated.";
            exit;
        }
    }
    else{
        rollback_t();
        echo "error--Supplier Cost not updated!";
        exit;
    }

}

public function finance_update($sq_estimate_info,$estimate_id, $vendor_type, $vendor_type_id, $basic_cost, $non_recoverable_taxes, $service_charge, $other_charges, $taxation_type, $taxation_id, $service_tax, $service_tax_subtotal, $discount, $our_commission, $tds, $net_total,$row_spec,$purchase_date)
{


    $old_purchase_gl = get_vendor_purchase_gl_id($sq_estimate_info['vendor_type'], $sq_estimate_info['vendor_type_id']);    
    $purchase_gl = get_vendor_purchase_gl_id($vendor_type, $vendor_type_id);  
    $purchase_amount =  $basic_cost + $non_recoverable_taxes + $service_charge + $other_charges;

	$year1 = explode("-", $purchase_date);
    $yr1 =$year1[0];
    
    global $transaction_master;
    //Getting supplier Ledger
    $q = "select * from ledger_master where group_sub_id='105' and customer_id='$vendor_type_id' and user_type='$vendor_type'";
    $sq_sup = mysql_fetch_assoc(mysql_query($q));
    $supplier_gl = $sq_sup['ledger_id'];

    ////////////Sales/////////////

    $module_name = $vendor_type;
    $module_entry_id = $estimate_id;
    $transaction_id = "";
    $payment_amount = $purchase_amount;
    $payment_date = $purchase_date;
    $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $purchase_date, $purchase_amount, $vendor_type, $vendor_type_id);
    $ledger_particular = get_ledger_particular('For',$vendor_type.' Purchase');
    $old_gl_id = $old_purchase_gl;
    $gl_id = $purchase_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

    /////////Tax Amount/////////
    purchase_tax_reflection_update($vendor_type,$service_tax_subtotal,$taxation_type,$estimate_id,get_vendor_estimate_id($estimate_id,$yr1),$purchase_date,0, $row_spec);

    ////// Discount ////////////
    $module_name = $vendor_type;
    $module_entry_id = $estimate_id;
    $transaction_id = "";
    $payment_amount = $discount;
    $payment_date = $purchase_date;
    $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $purchase_date, $discount, $vendor_type, $vendor_type_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $old_gl_id = $gl_id = 37;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

    ////// Commision ////////////
    $module_name = $vendor_type;
    $module_entry_id = $estimate_id;
    $transaction_id = "";
    $payment_amount = $our_commission;
    $payment_date = $purchase_date;
    $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $purchase_date, $our_commission, $vendor_type, $vendor_type_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $old_gl_id = $gl_id = 25;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

    ////// Tds receivable ////////////
    $module_name = $vendor_type;
    $module_entry_id = $estimate_id;
    $transaction_id = "";
    $payment_amount = $tds;
    $payment_date = $purchase_date;
    $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $tds, $our_commission, $vendor_type, $vendor_type_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $old_gl_id  = $gl_id = 127;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

    //Supplier
    $module_name = $vendor_type;
    $module_entry_id = $estimate_id;
    $transaction_id ='';
    $payment_amount = $net_total;
    $payment_date = $purchase_date;
    $payment_particular = get_purchase_partucular(get_vendor_payment_id($estimate_id,$yr1), $purchase_date, $net_total, $vendor_type, $vendor_type_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $old_gl_id = $gl_id = $supplier_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);
}
}
?>