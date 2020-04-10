<?php
include "../../../model/model.php";

$group_id = $_POST['group_id'];
$financial_year_id = $_POST['financial_year_id'];
$branch_admin_id = $_POST['branch_admin_id'];
?>
<div class="row mg_tp_20"> <div class="col-md-12"> <div class="table-responsive">
	
<table class="table table-hover" id="tbl_list_ledger" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Ledger_name</th>
			<th>View</th>
			<th>Alias_Name</th>
			<th>Group_name</th>
			<th>Amount</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$query = "select * from ledger_master where 1 ";
		if($group_id!=""){
			$query .= " and group_sub_id='$group_id'";	
		}
		$count = 0;
		$sq_gl = mysql_query($query);
		while($row_gl = mysql_fetch_assoc($sq_gl)){
			$credit = 0;
			$debit = 0;
			
			$sq_sl = mysql_fetch_assoc(mysql_query("select * from subgroup_master where subgroup_id='$row_gl[group_sub_id]'"));

			$q1 = "select sum(payment_amount) as sum from finance_transaction_master where payment_side='Credit' and gl_id='$row_gl[ledger_id]'";
			if($branch_admin_id != '0'){
				$q1 .= " and branch_admin_id='$branch_admin_id'";
			}	

			$sq_trans_credit = mysql_fetch_assoc(mysql_query($q1));
			$credit += ($sq_trans_credit['sum']=="") ? 0 : $sq_trans_credit['sum'];
			
			$q2 = "select sum(payment_amount) as sum from finance_transaction_master where payment_side='Debit' and gl_id='$row_gl[ledger_id]'";
			if($branch_admin_id != '0'){
				$q2 .= " and branch_admin_id='$branch_admin_id'";
			}	

			$sq_trans_debit = mysql_fetch_assoc(mysql_query($q2));
			$debit += ($sq_trans_debit['sum']=="") ? 0 : $sq_trans_debit['sum'];			


			if($debit>$credit){
				$balance =  $debit - $credit;
				$side_t1='(Dr)';
			}
			else{
				$balance =  $credit - $debit;
				$side_t1='(Cr)';
			}
			?>
			<tr>
				<td><?= $row_gl['ledger_id'] ?></td>
				<td><?= $row_gl['ledger_name'] ?></td>
				<td>
					<a href="view/index.php?ledger_id=<?= $row_gl['ledger_id']  ?>" target="_BLANK" class="btn btn-info btn-sm" title="View Ledger History"><i class="fa fa-eye"></i></a>
				</td>
				<td><?= $row_gl['alias'] ?></td>
				<td><?= $sq_sl['subgroup_name'] ?></td>
				<td><?= number_format($balance,2).$side_t1 ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="update_modal(<?= $row_gl['ledger_id'] ?>)" title="Update GL"><i class="fa fa-pencil-square-o"></i></button>
				</td>

			</tr>
			<?php
		}
		?>
	</tbody>
</table>

</div> </div> </div>

<script>
$('#tbl_list_ledger').dataTable({
		"pagingType": "full_numbers"
	});
</script>