<?php 
$flag = true;
class group_tour_booker_incentive{

public function group_tour_booker_incentive_save()
{
	$tourwise_traveler_id = $_POST['tourwise_traveler_id'];
	$emp_id = $_POST['emp_id'];
	$basic_amount = $_POST['basic_amount'];
	$tds = $_POST['tds'];
	$incentive_amount = $_POST['incentive_amount'];
	$financial_year_id = $_POST['financial_year_id'];

	$incentive_count = mysql_num_rows(mysql_query("select * from booker_incentive_group_tour tourwise_traveler_id='$tourwise_traveler_id' and emp_id='$emp_id'"));
	if($incentive_count>0){
		echo "error--Sorry, You already entered this incentive.";
		exit;
	}

	$incentive_id = mysql_fetch_assoc(mysql_query( "select max(incentive_id) as max from booker_incentive_group_tour" ));
	$incentive_id = $incentive_id['max']+1;
	$sq = mysql_query("insert into booker_incentive_group_tour ( incentive_id, emp_id, tourwise_traveler_id, basic_amount, tds, incentive_amount,financial_year_id) values( '$incentive_id', '$emp_id', '$tourwise_traveler_id', '$basic_amount', '$tds', '$incentive_amount','$financial_year_id' )");

	$this->finance_save($tourwise_traveler_id, $emp_id);

	if($sq){
		echo "Incentive has been successfully saved.";
		exit;
	}
	else{
		echo "error--Sorry, Incentive not added.";
		exit;
	}

}

public function finance_save($tourwise_traveler_id,$emp_id)
{
	$incentive_amount = $_POST['incentive_amount'];
	$tds = $_POST['tds'];
	$basic_amount = $_POST['basic_amount'];
    $tds1 = ($basic_amount * ( $tds / 100));
    global $transaction_master;
    global $cash_in_hand, $bank_account, $booker_incentives ,$fiance_vars;

    //***========================Incentive entries start=============================***//
     //incentive amount
    $module_name = 'Booker Incentive Estimate';
    $module_entry_id = $tourwise_traveler_id;
    $transaction_type = '';
    $payment_amount = $incentive_amount;
    $payment_date = '';
    $payment_particular = get_incentive_paid_particular($emp_id);
    $gl_id = '146';
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_type, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status);

     //**TDS**//
    $module_name="Booker Incentive Estimate";
    $module_entry_id = $tourwise_traveler_id;
    $transaction_type = "";
    $payment_amount = $tds1;
    $payment_date = "";
    $payment_particular = get_incentive_paid_particular($emp_id);
    $gl_id = $fiance_vars['tds_paid'];
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_type, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status);

    //basic amount
    $module_name = 'Booker Incentive Estimate';
    $module_entry_id = $tourwise_traveler_id;
    $transaction_type = '';
    $payment_amount = $incentive_amount;
    $payment_date = '';
    $payment_particular = get_incentive_paid_particular($emp_id);
    $gl_id = $booker_incentives;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_type, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status);
}

public function group_tour_booker_incentive_update()
{
	$tourwise_traveler_id = $_POST['tourwise_traveler_id'];
	$emp_id = $_POST['emp_id'];
	$basic_amount = $_POST['basic_amount'];
	$tds = $_POST['tds'];
	$incentive_amount = $_POST['incentive_amount'];

	$sq = mysql_query("update booker_incentive_group_tour set basic_amount='$basic_amount', tds='$tds', incentive_amount='$incentive_amount' where tourwise_traveler_id='$tourwise_traveler_id' and emp_id='$emp_id'");
	if($sq){
		echo "Incentive has been successfully updated.";
		exit;
	}
	else{
		echo "error--Sorry, Incentive not updated.";
		exit;
	}
}
}
?>