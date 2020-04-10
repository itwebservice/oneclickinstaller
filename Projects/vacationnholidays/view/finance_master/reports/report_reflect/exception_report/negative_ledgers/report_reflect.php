<?php
include "../../../../../../model/model.php";
$ledger_id = $_POST['ledger_name'];
$financial_year_id = $_POST['financial_year_id'];
$role = $_POST['role'];
$branch_admin_id = $_POST['branch_admin_id'];
$branch_status = $_POST['branch_status'];

$query = "select * from ledger_master where 1 ";
if($ledger_id != ''){
	$query .= " and ledger_id='$ledger_id'";
}
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_report_neagtive" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Ledger</th>
			<th>Date</th>
			<th>Balance</th>
			<th>DR/CR</th>
		</tr>
	</thead>
	<tbody>		
		<?php
		$sq_ledger_info = mysql_query($query);
		$count = 1;
		while($row_ledger = mysql_fetch_assoc($sq_ledger_info))
		{
			$total_debit = ($row_ledger['dr_cr']=='Dr') ? $row_ledger['balance'] : '0';
			$total_credit = ($row_ledger['dr_cr']=='Cr') ? $row_ledger['balance'] : '0';
			$balance = 0;
			$payment_date = '';
			$query1 = "select * from finance_transaction_master where gl_id='$row_ledger[ledger_id]' and financial_year_id='$financial_year_id'";
			if($branch_status=='yes'){
				if($role=='Branch Admin'){
				$query1 .= " and branch_admin_id = '$branch_admin_id'";
				}	
				elseif($role!='Admin' && $role!='Branch Admin'){
			    $query1 .= " and emp_id='$emp_id'";
			    }
			} 	
			$sq_le_name = mysql_query($query1);	
			while($row_finance = mysql_fetch_assoc($sq_le_name))
			{
				$payment_date = get_date_user($row_finance['payment_date']);
			?>		
			<?php 
				if($row_finance['payment_side'] == 'Debit'){
					$total_debit += $row_finance['payment_amount'];
				} 
				else{
					$total_credit += $row_finance['payment_amount'];
				}
			}//while close
			if($total_debit>$total_credit && $row_ledger['dr_cr'] == 'Cr'){
				$balance =  $total_debit - $total_credit;
				?>				
			<tr>
				<td><?= $count++ ?></td>
				<td><?= $row_ledger['ledger_name'] ?></td>
				<td><?= $payment_date?></td>
				<td><?= number_format($balance,2) ?></td>
				<td><?= 'DR' ?></td>
			</tr>
			<?php
			}
			if($total_debit<$total_credit && $row_ledger['dr_cr'] == 'Dr'){
				$balance =  $total_credit - $total_debit;	 ?>					
			<tr>
				<td><?= $count++ ?></td>
				<td><?= $row_ledger['ledger_name'] ?></td>
				<td><?= $payment_date ?></td>
				<td><?= number_format($balance,2) ?></td>
				<td><?= 'CR' ?></td>
			</tr>
			<?php }
		} ?> 	
	</tbody>
</table>
</div> </div> </div>
<script>
$('#tbl_report_neagtive').dataTable({
		"pagingType": "full_numbers"
	});
</script>