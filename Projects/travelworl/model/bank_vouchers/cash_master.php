<?php 

$flag = true;

class cash_master{
////////////////////////////// CASH DEPOSIT START ///////////////////////////
public function cash_deposit_save()
{
	$bank_id = $_POST['bank_id'];
	$payment_amount = $_POST['payment_amount'];
	$payment_date = $_POST['payment_date'];
	$payment_evidence_url = $_POST['payment_evidence_url'];
	$branch_admin_id = $_POST['branch_admin_id'];
    $emp_id = $_POST['emp_id']; 
    $financial_year_id = $_SESSION['financial_year_id'];

	$created_at = date('Y-m-d H:i:s');
    $payment_date = date('Y-m-d', strtotime($payment_date));

	begin_t();

	$sq_max = mysql_fetch_assoc(mysql_query("select max(deposit_id) as max from cash_deposit_master"));
	$deposit_id = $sq_max['max'] + 1;
	$sq_deposit = mysql_query("insert into cash_deposit_master (deposit_id, bank_id, amount, evidence_url, transaction_date, branch_admin_id, emp_id, financial_year_id, created_at) values ('$deposit_id', '$bank_id', '$payment_amount', '$payment_evidence_url', '$payment_date', '$branch_admin_id','$emp_id' ,'$financial_year_id','$created_at') ");

	if($sq_deposit){
		//Finance save
    	$this->finance_save($deposit_id,$branch_admin_id);

	    if($GLOBALS['flag']){
	      commit_t();
	      echo "Payment has been successfully saved.";
	      exit;
	    }
	    else{
	      rollback_t();
	      exit;
	    }
	}
	else{
		rollback_t();
		echo "error--Cash Deposit Not Done!";
		exit;
	}
}


public function finance_save($deposit_id,$branch_admin_id)
{
	$row_spec = 'bank';
	$bank_id = $_POST['bank_id'];
	$payment_amount1 = $_POST['payment_amount'];
	$payment_date = $_POST['payment_date'];
	$payment_evidence_url = $_POST['payment_evidence_url'];
	$branch_admin_id = $_POST['branch_admin_id'];
    $emp_id = $_POST['emp_id']; 
    $financial_year_id = $_SESSION['financial_year_id'];

    $payment_date1 = date('Y-m-d', strtotime($payment_date));

    //BANK Ledger
    $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
    $pay_gl = $sq_bank['ledger_id'];

    global $transaction_master;

    ////////////Basic deposit Amount/////////////

    $module_name = "Cash Deposit";
    $module_entry_id = $deposit_id;
    $transaction_id = "";
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_cash_deposit_particular($bank_id);    
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = '20';
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    /////////bank////////
    $module_name = "Cash Deposit";
    $module_entry_id = $deposit_id;
    $transaction_id = "";
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_cash_deposit_particular($bank_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $pay_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

}

public function cash_deposit_update()
{
	$bank_id = $_POST['bank_id'];
	$deposit_id = $_POST['deposit_id'];
	$payment_amount = $_POST['payment_amount'];
	$payment_date = $_POST['payment_date'];
	$payment_evidence_url = $_POST['payment_evidence_url'];

	$due_date = get_date_db($due_date);
    $booking_date = get_date_db($booking_date);

	begin_t();
	$sq_deposit_u = mysql_query("update cash_deposit_master set amount='$payment_amount',evidence_url='$payment_evidence_url' where deposit_id='$deposit_id'");

	if($sq_deposit_u){
		//Finance save
    	$this->finance_update($deposit_id);

	    if($GLOBALS['flag']){
	      commit_t();
	      echo "Payment has been successfully updated.";
	      exit;
	    }
	    else{
	      rollback_t();
	      exit;
	    }
	}
	else{
		rollback_t();
		echo "error--Deposit not updated!";
		exit;
	}

}



public function finance_update($deposit_id)
{
	$row_spec = 'bank';
	$bank_id = $_POST['bank_id'];
	$payment_amount1 = $_POST['payment_amount'];
	$payment_date = $_POST['payment_date'];
	$payment_evidence_url = $_POST['payment_evidence_url'];
	$payment_old_amount = $_POST['payment_old_amount'];

    $payment_date1 = date('Y-m-d', strtotime($payment_date));

    //BANK Ledger
    $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
    $pay_gl = $sq_bank['ledger_id'];

    global $transaction_master;

    if($payment_amount1 == '0' && ($payment_old_amount != $payment_amount1))
    {
	    ////////////Basic deposit /////////////
	    $module_name = "Cash Deposit";
	    $module_entry_id = $deposit_id;
	    $transaction_id = "";
	    $payment_amount = $payment_old_amount;
	    $payment_date = $payment_date1;
	    $payment_particular = get_cash_deposit_particular($bank_id);
	    $ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = '20';
	    $payment_side = "Debit";
	    $clearance_status = "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

	    /////////bank////////
	    $module_name = "Cash Deposit";
	    $module_entry_id = $deposit_id;
	    $transaction_id = "";
	    $payment_amount = $payment_old_amount;
	    $payment_date = $payment_date1;
	    $payment_particular = get_cash_deposit_particular($bank_id);
	    $ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $pay_gl;
	    $payment_side = "Credit";
	    $clearance_status = "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    }
    else{

    }
}

///////////////////////////// CASH DEPOSIT END/////////////////////////

///////////////////////////// CASH WITHDRAW START/////////////////////////
public function cash_withdraw_save()
{
	$bank_id = $_POST['bank_id'];
	$payment_amount = $_POST['payment_amount'];
	$payment_date = $_POST['payment_date'];
	$payment_evidence_url = $_POST['payment_evidence_url'];
	$branch_admin_id = $_POST['branch_admin_id'];
    $emp_id = $_POST['emp_id']; 
    $financial_year_id = $_SESSION['financial_year_id'];

	$created_at = date('Y-m-d H:i:s');
    $payment_date = date('Y-m-d', strtotime($payment_date));

	begin_t();

	$sq_max = mysql_fetch_assoc(mysql_query("select max(withdraw_id) as max from cash_withdraw_master"));
	$withdraw_id = $sq_max['max'] + 1;
	$sq_deposit = mysql_query("insert into cash_withdraw_master (withdraw_id, bank_id, amount, evidence_url, transaction_date, branch_admin_id, emp_id, financial_year_id, created_at) values ('$withdraw_id', '$bank_id', '$payment_amount', '$payment_evidence_url', '$payment_date', '$branch_admin_id','$emp_id' ,'$financial_year_id','$created_at') ");

	if($sq_deposit){
		//Finance save
    	$this->finance_save_withdraw($withdraw_id,$branch_admin_id);

	    if($GLOBALS['flag']){
	      commit_t();
	      echo "Payment has been successfully saved.";
	      exit;
	    }
	    else{
	      rollback_t();
	      exit;
	    }
	}
	else{
		rollback_t();
		echo "error--Cash Withdrawal Not Done!";
		exit;
	}
}


public function finance_save_withdraw($withdraw_id,$branch_admin_id)
{
	$row_spec = 'bank';
	$bank_id = $_POST['bank_id'];
	$payment_amount1 = $_POST['payment_amount'];
	$payment_date = $_POST['payment_date'];
	$payment_evidence_url = $_POST['payment_evidence_url'];
	$branch_admin_id = $_POST['branch_admin_id'];
    $emp_id = $_POST['emp_id']; 
    $financial_year_id = $_SESSION['financial_year_id'];

    $payment_date1 = date('Y-m-d', strtotime($payment_date));

    //BANK Ledger
    $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
    $pay_gl = $sq_bank['ledger_id'];

    global $transaction_master;

    ////////////Basic deposit Amount/////////////

    $module_name = "Cash Withdraw";
    $module_entry_id = $withdraw_id;
    $transaction_id = "";
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_cash_withdraw_particular($bank_id);    
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = '20';
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    /////////bank////////
    $module_name = "Cash Withdraw";
    $module_entry_id = $withdraw_id;
    $transaction_id = "";
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_cash_withdraw_particular($bank_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $pay_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

}

public function cash_withdraw_update()
{
	$bank_id = $_POST['bank_id'];
	$withdraw_id = $_POST['withdraw_id'];
	$payment_amount = $_POST['payment_amount'];
	$payment_date = $_POST['payment_date'];
	$payment_evidence_url = $_POST['payment_evidence_url'];

	$due_date = get_date_db($due_date);
    $booking_date = get_date_db($booking_date);

	begin_t();
	$sq_deposit_u = mysql_query("update cash_withdraw_master set amount='$payment_amount',evidence_url='$payment_evidence_url' where withdraw_id='$withdraw_id'");

	if($sq_deposit_u){
		//Finance save
    	$this->finance_update_withdraw($withdraw_id);

	    if($GLOBALS['flag']){
	      commit_t();
	      echo "Payment has been successfully updated.";
	      exit;
	    }
	    else{
	      rollback_t();
	      exit;
	    }
	}
	else{
		rollback_t();
		echo "error--Withdrawal not updated!";
		exit;
	}

}



public function finance_update_withdraw($withdraw_id)
{
	$row_spec = 'bank';
	$bank_id = $_POST['bank_id'];
	$payment_amount1 = $_POST['payment_amount'];
	$payment_date = $_POST['payment_date'];
	$payment_evidence_url = $_POST['payment_evidence_url'];
	$payment_old_amount = $_POST['payment_old_amount'];

    $payment_date1 = date('Y-m-d', strtotime($payment_date));

    //BANK Ledger
    $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
    $pay_gl = $sq_bank['ledger_id'];

    global $transaction_master;

    if($payment_amount1 == '0' && ($payment_old_amount != $payment_amount1))
    {
	    ////////////Basic deposit /////////////
	    $module_name = "Cash Withdraw";
	    $module_entry_id = $withdraw_id;
	    $transaction_id = "";
	    $payment_amount = $payment_old_amount;
	    $payment_date = $payment_date1;
	    $payment_particular = get_cash_withdraw_particular($bank_id);    
	    $ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = '20';
	    $payment_side = "Credit";
	    $clearance_status = "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

	    /////////bank////////
	    $module_name = "Cash Deposit";
	    $module_entry_id = $withdraw_id;
	    $transaction_id = "";
	    $payment_amount = $payment_old_amount;
	    $payment_date = $payment_date1;
	    $payment_particular = get_cash_deposit_particular($bank_id);
	    $ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $pay_gl;
	    $payment_side = "Debit";
	    $clearance_status = "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    }
    else{

    }
}
///////////////////////////// CASH WITHDRAW END/////////////////////////
}

?>