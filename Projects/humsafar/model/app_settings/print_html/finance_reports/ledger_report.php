<?php
include "../../../model.php";
include "../print_functions.php";

$from_date = $_GET['from_date'];
$to_date = $_GET['to_date'];
$ledger_id = $_GET['ledger_id'];
$financial_year_id = $_GET['financial_year_id'];
$branch_admin_id = $_GET['branch_admin_id'];

if($from_date!= '' && $to_date){
	$from_date1 = get_date_user($from_date);
	$to_date1 = get_date_user($to_date);
	$pnl_date = ' ('.$from_date1.' TO '.$to_date1.')';
}else{
	$pnl_date = ''; 
}

$sq_ledger = mysql_fetch_assoc(mysql_query("select * from ledger_master where ledger_id='$ledger_id'"));

$balance = 0;
$side_t = '(Cr)';
if($from_date != ''){
		//Calaculate previous year closing balance as opening balance of this year//
		$sq_ob = "select * from finance_transaction_master where gl_id='$ledger_id' and payment_amount != '0'";
			$from_date = date('Y-m-d', strtotime($from_date));
			$to_date = date('Y-m-d', strtotime($to_date));
			$sq_ob .= " and payment_date < '$from_date'";
		$sq_ledger_ob = mysql_query($sq_ob);
		$balance = 0;
		$total_debit = ($sq_lq['dr_cr']=='Dr') ? $sq_lq['balance'] : '0';
		$total_credit = ($sq_lq['dr_cr']=='Cr') ? $sq_lq['balance'] : '0';

		while($row_ledger_ob = mysql_fetch_assoc($sq_ledger_ob)){
			$debit_amount = ($row_ledger_ob['payment_side'] == 'Debit') ? $row_ledger_ob['payment_amount'] : '' ;
			$credit_amount = ($row_ledger_ob['payment_side'] == 'Credit') ? $row_ledger_ob['payment_amount'] : '' ;
			if($row_ledger_ob['payment_side'] == 'Debit'){
				$total_debit += $row_ledger_ob['payment_amount'];
			} 
			else{
				$total_credit += $row_ledger_ob['payment_amount'];
			}
		}
		if($total_debit>$total_credit){
			$balance =  $total_debit - $total_credit;
			$side_t='(Dr)';
		}
		else{
			$balance =  $total_credit - $total_debit;	
			$side_t='(Cr)';
		}
}

////////////////////////////////// END /////////////////////////////////////////////

$sq_q = "select * from finance_transaction_master where gl_id='$ledger_id' and payment_amount != '0' ";
if($from_date!= '' && $to_date){
	$from_date = date('Y-m-d', strtotime($from_date));
	$to_date = date('Y-m-d', strtotime($to_date));
	$sq_q .= " and  payment_date between '$from_date' and '$to_date' ";
}
if($branch_admin_id != '0'){
	$sq_q .= " and branch_admin_id = '$branch_admin_id'";
}
$sq_q .= ' order by payment_date';
?>

<section class="print_header main_block mg_bt_20">
  <div class="col-md-12 no-pad text-center">
    <div class="print_header_contact">
      <span class="title"><?php echo $app_name; ?></span><br>
      <p><?php echo ($branch_status=='yes' && $role!='Admin') ? $branch_details['address1'].','.$branch_details['address2'].','.$branch_details['city'] : $app_address ?></p>
      <p class="no-marg"><i class="fa fa-phone" style="margin-right: 5px;"></i> <?php echo ($branch_status=='yes' && $role!='Admin') ? 
       $branch_details['contact_no'] : $app_contact_no ?></p>
      <p><i class="fa fa-envelope" style="margin-right: 5px;"></i> <?php echo $app_email_id; ?></p>
    </div>
  	<span class="title"><?= $sq_ledger['ledger_name'] ?> Account</span>
  	<p><?= $pnl_date ?></p>
  </div>
</section>

<div class="row mg_tp_20">
<!-- START -->
<div class="col-md-12"><div class="table-responsive">
	<table class="table no-marg" id="tbl_list_ledger_sub" style="padding: 0px !important;">
		<thead>
			<tr class="table-heading-row">
				<th>SR.NO</th>
				<th style="width:100px;">Date</th>
				<th>Particulars</th>
				<th>Debit</th>
				<th>Credit</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td></td>
				<td></td>
				<td><?php echo "Opening Balance"; ?></td>
				<td><?php echo ($side_t=='(Dr)') ? number_format($balance,2) : ''; ?></td>
				<td><?php echo ($side_t=='(Cr)') ? number_format($balance,2) : ''; ?></td>
			</tr>
		<?php
			$count = 1;
			$total_debit1 = ($side_t == '(Dr)') ? $balance : '0';
			$total_credit1 = ($side_t == '(Cr)') ? $balance : '0';
			$sq_ledger_info = mysql_query($sq_q);
			while($row_ledger = mysql_fetch_assoc($sq_ledger_info)){
				$sq_le_name = mysql_fetch_assoc(mysql_query("select * from ledger_master where ledger_id='$row_ledger[gl_id]'"));
				$debit_amount = ($row_ledger['payment_side'] == 'Debit') ? $row_ledger['payment_amount'] : '' ;
				$credit_amount = ($row_ledger['payment_side'] == 'Credit') ? $row_ledger['payment_amount'] : '' ;
			?>

				<tr>
					<td><?= $count ?></td>
					<td><?= get_date_user($row_ledger['payment_date']) ?></td>
					<td><?= $row_ledger['payment_particular'] ?></td>
					<td><?= $debit_amount ?></td>
					<td><?= $credit_amount ?></td>
				</tr>
			<?php  
				$count++;

				if($row_ledger['payment_side'] == 'Debit'){
					$total_debit1 += $row_ledger['payment_amount'];
				} 
				else{
					$total_credit1 += $row_ledger['payment_amount'];
				}
			} //while close

				if($total_debit1>$total_credit1){
					$balance1 =  $total_debit1 - $total_credit1;
					$side_t1='(Dr)';
				}
				else{
					$balance1 =  $total_credit1 - $total_debit1;	
					$side_t1='(Cr)';
				}?>
				
			<tr class="table-heading-row">
				<td></td>
				<td></td>
				<td class="text-right">Current Total : </td>
				<td><?= number_format($total_debit1,2) ?></td>
				<td><?= number_format($total_credit1,2) ?></td>
			</tr>
			<tr class="table-heading-row">
				<td></td>
				<td></td>
				<td class="text-right" class="text-right">TOTAL BALANCE : </td>
				<td><?= number_format($balance1,2).$side_t1 ?> </td>
				<td></td>
			</tr>
		</tbody>
	</table>
	</div></div>
<!-- end -->
</div>

<script type="text/javascript">
	// App_accordion
 jQuery(document).ready(function() {
   jQuery(".panel-heading").click(function(){ 
    jQuery('#accordion .panel-heading').not(this).removeClass('isOpen');
    jQuery(this).toggleClass('isOpen');
    jQuery(this).next(".panel-collapse").addClass('thePanel');
    jQuery('#accordion .panel-collapse').not('.thePanel').slideUp("slow"); 
       jQuery(".thePanel").slideToggle("slow").removeClass('thePanel'); 
   });
   
  });
</script>