<?php 
class ledger_master{

public function ledger_master_save()
{
	$ledger_name = $_POST['ledger_name'];
	$alias_name = $_POST['alias_name'];
	$group_id = $_POST['group_id'];
	$ledger_balance = $_POST['ledger_balance'];
	$side = $_POST['side'];

	$sq_count = mysql_num_rows(mysql_query("select ledger_name from ledger_master where ledger_name='$ledger_name'"));
	if($sq_count>0){
		echo "error--Ledger name already exists!";
		exit;
	}

	$sq_max = mysql_fetch_assoc(mysql_query("select max(ledger_id) as max from ledger_master"));
	$ledger_id = $sq_max['max'] + 1;

	begin_t();

	$sq_bank = mysql_query("insert into ledger_master (ledger_id, ledger_name, alias, group_sub_id, balance, dr_cr) values ('$ledger_id', '$ledger_name', '$alias_name', '$group_id', '$ledger_balance','$side')");
	if($sq_bank){
		commit_t();
		echo "Ledger has been successfully saved.";
		exit;
	}
	else{
		rollback_t();
		echo "error--Sorry, Ledger not saved!";
		exit;
	}

}

public function ledger_master_update()
{
	$ledger_id = $_POST['ledger_id'];
	$ledger_name = $_POST['ledger_name'];
	$alias_name = $_POST['alias_name'];
	$group_id = $_POST['group_id'];
	$ledger_balance = $_POST['ledger_balance'];
	$side = $_POST['side'];

	$sq_count = mysql_num_rows(mysql_query("select ledger_name from ledger_master where ledger_name='$ledger_name' and ledger_id!='$ledger_id'"));
	if($sq_count>0){
		echo "error--Ledger name already exists!";
		exit;
	}

	begin_t();

	$sq_bank = mysql_query("update ledger_master set ledger_name='$ledger_name', alias='$alias_name', group_sub_id='$group_id',balance='$ledger_balance',dr_cr='$side' where ledger_id='$ledger_id'");
	
	$sq_ledger = mysql_fetch_assoc(mysql_query("select * from ledger_master where ledger_id='$ledger_id'"));
	//bank update 
	if($sq_ledger['user_type']=='bank'){
		$sq_bank = mysql_query("update bank_master set opening_balance='$ledger_balance' where bank_id='$sq_ledger[customer_id]'");
	}

	//supplier update
	if($sq_ledger['user_type']=='DMC Vendor'){
		$sq_bank = mysql_query("update dmc_master set opening_balance='$ledger_balance' where dmc_id='$sq_ledger[customer_id]'");
	}
	else if($sq_ledger['user_type']=='Hotel Vendor'){
		$sq_bank = mysql_query("update hotel_master set opening_balance='$ledger_balance' where hotel_id='$sq_ledger[customer_id]'");
	}
	else if($sq_ledger['user_type']=='Transport Vendor'){
		$sq_bank = mysql_query("update transport_agency_master set opening_balance='$ledger_balance' where transport_agency_id='$sq_ledger[customer_id]'");
	}
	else if($sq_ledger['user_type']=='Visa Vendor'){
		$sq_bank = mysql_query("update visa_vendor set opening_balance='$ledger_balance' where vendor_id='$sq_ledger[customer_id]'");
	}
	else if($sq_ledger['user_type']=='Passport Vendor'){
		$sq_bank = mysql_query("update passport_vendor set opening_balance='$ledger_balance' where vendor_id='$sq_ledger[customer_id]'");
	}
	else if($sq_ledger['user_type']=='Train Ticket Vendor'){
		$sq_bank = mysql_query("update train_ticket_vendor set opening_balance='$ledger_balance' where vendor_id='$sq_ledger[customer_id]'");
	}
	else if($sq_ledger['user_type']=='Car Rental Vendor'){
		$sq_bank = mysql_query("update car_rental_vendor set opening_balance='$ledger_balance' where vendor_id='$sq_ledger[customer_id]'");
	}
	else if($sq_ledger['user_type']=='Ticket Vendor'){
		$sq_bank = mysql_query("update ticket_vendor set opening_balance='$ledger_balance' where vendor_id='$sq_ledger[customer_id]'");
	}
	else if($sq_ledger['user_type']=='Excursion Vendor'){
		$sq_bank = mysql_query("update site_seeing_vendor set opening_balance='$ledger_balance' where vendor_id='$sq_ledger[customer_id]'");
	}
	else if($sq_ledger['user_type']=='Insuarance Vendor'){
		$sq_bank = mysql_query("update insuarance_vendor set opening_balance='$ledger_balance' where vendor_id='$sq_ledger[customer_id]'");
	}
	else if($sq_ledger['user_type']=='Cruise Vendor'){
		$sq_bank = mysql_query("update cruise_master set opening_balance='$ledger_balance' where cruise_id='$sq_ledger[customer_id]'");
	}
	else if($sq_ledger['user_type']=='Other Vendor'){
		$sq_bank = mysql_query("update other_vendors set opening_balance='$ledger_balance' where vendor_id='$sq_ledger[customer_id]'");
	}
	else{

	}
	if($sq_bank){
		commit_t();
		echo "Ledger has been successfully updated.";
		exit;
	}
	else{
		rollback_t();
		echo "error--Sorry, Ledger not updated!";
		exit;
	}

}

}
?>