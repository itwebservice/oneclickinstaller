<?php
$flag = true; 
class bank_master{

public function bank_master_save()
{
	$bank_name = $_POST['bank_name'];
	$branch_name = $_POST['branch_name'];
	$address = $_POST['address'];
	$account_no = $_POST['account_no'];
	$ifsc_code = $_POST['ifsc_code'];
	$swift_code = $_POST['swift_code'];
	$account_type = $_POST['account_type'];
	$mobile_no = $_POST['mobile_no'];
	$opening_balance = $_POST['opening_balance'];
	$active_flag = $_POST['active_flag'];
	$as_of_date = $_POST['as_of_date'];

	$created_at = date('Y-m-d H:i:s');
	$as_of_date = get_date_db($as_of_date);

	//**Starting transaction
	begin_t();

	$sq_count = mysql_num_rows(mysql_query("select bank_name from bank_master where bank_name='$bank_name'"));
	if($sq_count>0){
		echo "error--Bank name already exists!";
		exit;
	}

	$sq_max = mysql_fetch_assoc(mysql_query("select max(bank_id) as max from bank_master"));
	$bank_id = $sq_max['max'] + 1;

	$sq_bank = mysql_query("insert into bank_master (bank_id, bank_name, branch_name, address, account_no, ifsc_code, swift_code, account_type, mobile_no, opening_balance,active_flag, created_at,as_of_date) values ('$bank_id', '$bank_name', '$branch_name', '$address', '$account_no', '$ifsc_code', '$swift_code', '$account_type', '$mobile_no', '$opening_balance', '$active_flag', '$created_at','$as_of_date')");

	if($bank_id == 1){
		$sq_app_settings_bank = mysql_query("UPDATE app_settings SET bank_name='$bank_name', acc_name='$account_type', bank_acc_no='$account_no', bank_branch_name='$branch_name', bank_ifsc_code='$ifsc_code', bank_swift_code='$swift_code'");
	}

	get_bank_balance_update();

	//Creating ledger
	$sq_max = mysql_fetch_assoc(mysql_query("select max(ledger_id) as max from ledger_master"));
	$ledger_id = $sq_max['max'] + 1;
	$ledger_name = $bank_name.'('.$branch_name.')';

	$sq_ledger = mysql_query("insert into ledger_master (ledger_id, ledger_name, alias, group_sub_id, balance, dr_cr,customer_id,user_type) values ('$ledger_id', '$ledger_name', '', '24', '0','Dr','$bank_id','bank')");
		
	//Finance save
	$this->finance_save($bank_id,$ledger_id);

	if(!$sq_bank){
		$GLOBALS['flag'] = false;
		echo "error--Sorry, Bank not saved!";
	}

	if($GLOBALS['flag']){
		commit_t();
		echo "Bank has been successfully saved.";
		exit;
	}
	else{
		rollback_t();
		exit;
	}

}

public function finance_save($bank_id, $ledger_id)
{
	$row_spec = 'opening balance';
	$bank_name = $_POST['bank_name'];
	$branch_name = $_POST['branch_name'];
	$opening_balance = $_POST['opening_balance'];
	$as_of_date = $_POST['as_of_date'];
	
	$as_of_date = date('Y-m-d', strtotime($as_of_date));

	global $transaction_master;

	//////Opening balance to bank ledger///////
	$module_name = "Bank";
	$module_entry_id = $bank_id;
	$transaction_id = '';
	$payment_amount = $opening_balance;
	$payment_date = $as_of_date;
	$payment_particular = get_bank_opening_balance_particular($bank_name,$branch_name,$opening_balance,$as_of_date);
	$ledger_particular = 'By Cash/Bank';
	$gl_id = $ledger_id;
	$payment_side = "Debit";
	$clearance_status = "";
	$transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

  ////////Opening balance to Profit ledger//////
  $module_name = "Bank";
	$module_entry_id = $bank_id;
	$transaction_id = '';
	$payment_amount = $opening_balance;
	$payment_date = $as_of_date;
	$payment_particular = get_bank_opening_balance_particular($bank_name,$branch_name,$opening_balance,$as_of_date);
	$ledger_particular = 'By Cash/Bank';
	$gl_id = 173;
	$payment_side = "Credit";
	$clearance_status = "";
	$transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);  
}

public function bank_master_update()
{
	$bank_id = $_POST['bank_id'];
	$bank_name = $_POST['bank_name'];
	$branch_name = $_POST['branch_name'];
	$address = $_POST['address'];
	$account_no = $_POST['account_no'];
	$ifsc_code = $_POST['ifsc_code'];
	$swift_code = $_POST['swift_code'];
	$account_type = $_POST['account_type'];
	$mobile_no = $_POST['mobile_no'];
	$opening_balance = $_POST['opening_balance'];
	$active_flag = $_POST['active_flag'];
	$as_of_date = $_POST['as_of_date'];
	$as_of_date = get_date_db($as_of_date);

	$sq_count = mysql_num_rows(mysql_query("select bank_name from bank_master where bank_name='$bank_name' and bank_id!='$bank_id'"));
	if($sq_count>0){
		echo "error--Bank name already exists!";
		exit;
	}

	//**Starting transaction
	begin_t();

	$sq_bank = mysql_query("update bank_master set bank_name='$bank_name', branch_name='$branch_name', address='$address', account_no='$account_no', ifsc_code='$ifsc_code', swift_code='$swift_code', account_type='$account_type', mobile_no='$mobile_no', opening_balance='$opening_balance', active_flag='$active_flag',as_of_date='$as_of_date' where bank_id='$bank_id'");

	if($bank_id == 1){
		$sq_app_settings_bank = mysql_query("UPDATE app_settings SET bank_name='$bank_name', acc_name='$account_type', bank_acc_no='$account_no', bank_branch_name='$branch_name', bank_ifsc_code='$ifsc_code', bank_swift_code='$swift_code'");
	}


	$ledger_name = $bank_name.'('.$branch_name.')';
	$sq_bank = mysql_query("update ledger_master set ledger_name='$ledger_name', balance='0' where user_type='bank' and customer_id='$bank_id'");
	
	//Finance save
	$this->finance_update($bank_id,$ledger_id);
	
	if(!$sq_bank){
		$GLOBALS['flag'] = false;
		echo "error--Sorry, Bank not updated!";
	}	

	if($GLOBALS['flag']){
		commit_t();
		echo "Bank has been successfully updated.";
		exit;
	}
	else{
		rollback_t();
		exit;
	}

}
public function finance_update($bank_id, $ledger_id)
{
	$row_spec = 'opening balance';
	$bank_id = $_POST['bank_id'];
	$bank_name = $_POST['bank_name'];
	$branch_name = $_POST['branch_name'];
	$address = $_POST['address'];
	$account_no = $_POST['account_no'];
	$ifsc_code = $_POST['ifsc_code'];
	$swift_code = $_POST['swift_code'];
	$account_type = $_POST['account_type'];
	$mobile_no = $_POST['mobile_no'];
	$opening_balance = $_POST['opening_balance'];
	$active_flag = $_POST['active_flag'];
	$as_of_date = $_POST['as_of_date'];
	$as_of_date = get_date_db($as_of_date);
	
	$as_of_date = date('Y-m-d', strtotime($as_of_date));
	$ledger_name = $bank_name.'('.$branch_name.')';
	$sq_bank_b = mysql_fetch_assoc(mysql_query("select * from ledger_master where ledger_name='$ledger_name' and user_type='bank' and customer_id='$bank_id'"));

	global $transaction_master;

	//////Opening balance to bank ledger///////
	$module_name = "Bank";
	$module_entry_id = $bank_id;
	$transaction_id = '';
	$payment_amount = $opening_balance;
	$payment_date = $as_of_date;
	$payment_particular = get_bank_opening_balance_particular($bank_name,$branch_name,$opening_balance,$as_of_date);
	$ledger_particular = 'By Cash/Bank';
	$old_gl_id = $gl_id = $sq_bank_b['ledger_id'];
	$payment_side = "Debit";
	$clearance_status = "";
	$transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

  ////////Opening balance to Profit ledger//////
  $module_name = "Bank";
	$module_entry_id = $bank_id;
	$transaction_id = '';
	$payment_amount = $opening_balance;
	$payment_date = $as_of_date;
	$payment_particular = get_bank_opening_balance_particular($bank_name,$branch_name,$opening_balance,$as_of_date);
	$ledger_particular = 'By Cash/Bank';
	$old_gl_id = $gl_id = 173;
	$payment_side = "Credit";
	$clearance_status = "";
	$transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);
}
}
?>