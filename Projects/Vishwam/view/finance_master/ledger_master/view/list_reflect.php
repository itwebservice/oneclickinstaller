<?php 
include "../../../../model/model.php";
$from_date_filter = $_POST['from_date_filter'];
$to_date_filter = $_POST['to_date_filter'];
$ledger_id = $_POST['ledger_id'];
$financial_year_id = $_POST['financial_year_id'];
$branch_admin_id = $_POST['branch_admin_id'];

$sq_lq = mysql_fetch_assoc(mysql_query("select * from ledger_master where ledger_id='$ledger_id' "));
$balance = 0;
$side_t = '(Cr)';
if($from_date_filter != ''){
		//Calaculate previous year closing balance as opening balance of this year
		$sq_ob = "select * from finance_transaction_master where gl_id='$ledger_id' and payment_amount != '0'";
		$from_date_filter = date('Y-m-d', strtotime($from_date_filter));
		$to_date_filter = date('Y-m-d', strtotime($to_date_filter));
		$sq_ob .= " and payment_date < '$from_date_filter'";
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
if($from_date_filter != '' && $to_date_filter != ''){
	$from_date_filter = date('Y-m-d', strtotime($from_date_filter));
	$to_date_filter = date('Y-m-d', strtotime($to_date_filter));
	$sq_q .= " and  payment_date between '$from_date_filter' and '$to_date_filter'";
}
if($branch_admin_id != '0'){
	$sq_q .= " and branch_admin_id = '$branch_admin_id'";
}
$sq_q .= ' order by finance_transaction_id ';
?>
<div class="row"> <div class="col-md-12 mg_tp_20"><div class="table-responsive">
	<table class="table table-hover table-bordered" id="tbl_list_ledger_sub" style="margin: 20px 0 !important;  padding: 0px !important;">
		<thead>
			<tr class="table-heading-row">
				<th>SR.NO</th>
				<th>Date</th>
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
				$particular = addslashes($row_ledger['payment_particular']);
			?>
				<tr>
					<td><?= $count ?></td>
					<td><?= get_date_user($row_ledger['payment_date']) ?></td>
					<td style="cursor:pointer;text-decoration: underline;" onclick="show_history('<?= $row_ledger[module_entry_id] ?>','<?= $row_ledger[module_name] ?>','<?= $row_ledger[finance_transaction_id] ?>','<?= $particular ?>','<?= $sq_le_name[ledger_name] ?>')"><?= $sq_le_name['ledger_name'].' ('.$row_ledger['module_entry_id'].'_'.$row_ledger['module_name'].')' ?></td>
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
				} ?>
		</tbody>
		<tfoot>		
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
		</tfoot>
	</table>
	</div></div></div>
<script>

$('#tbl_list_ledger_sub').dataTable({
		"pagingType": "full_numbers"
});

</script>