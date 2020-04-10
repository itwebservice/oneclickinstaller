<?php
include_once("../../../model/model.php");
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$financial_year_id = $_SESSION['financial_year_id'];
$query = "select * from journal_entry_master where financial_year_id='$financial_year_id' ";
if($from_date != '' && $to_date != ''){
	$from_date = get_date_db($from_date);
	$to_date = get_date_db($to_date);
	$query .= " and entry_date between '$from_date' and '$to_date' ";
}
?>
<div class="row mg_tp_20"> <div class="col-md-12"> <div class="table-responsive">
	
<table class="table table-hover" id="tbl_list" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>JV_ID</th>
			<th>Date</th>
			<th>Particulars</th>
			<th>View</th>
			<th>dr_cr</th>
			<th>Narration</th>
			<th>Debit_Amount</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$count = 0;
		$total_dr = 0; $total_cr = 0;
		$sq_journal = mysql_query($query);
		while($row_journal = mysql_fetch_assoc($sq_journal)){
			$date = $row_journal['entry_date'];
			$yr = explode("-", $date);
			$year =$yr[0];
				$sq_journal_entry = mysql_fetch_assoc(mysql_query("select * from journal_entry_accounts where entry_id='$row_journal[entry_id]' limit 1"));		
				$sq_ledger = mysql_fetch_assoc(mysql_query("select * from ledger_master where ledger_id='$sq_journal_entry[ledger_id]'"));	
		        $sq_journal_debit = mysql_fetch_assoc(mysql_query("select sum(amount) as amount from journal_entry_accounts where type = 'Debit' and entry_id='$row_journal[entry_id]'"));
						$total_cr += $sq_journal_debit['amount'];	
			?>
			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= get_jv_entry_id($row_journal['entry_id'],$year) ?></td>
				<td><?= get_date_user($row_journal['entry_date']) ?></td>
				<td><?= ($sq_ledger['ledger_name']) ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="entry_display_modal(<?= $row_journal['entry_id'] ?>)" title="Journal entry"><i class="fa fa-eye"></i></button>
				</td>
				<td><?= ($sq_journal_entry['type']) ?></td>
				<td><?= ($row_journal['narration']) ?></td>
				<td class="success text-right"><?= number_format($sq_journal_debit['amount'],2) ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="update_modal(<?= $row_journal['entry_id'] ?>)" title="Edit journal"><i class="fa fa-pencil-square-o"></i></button>
				</td>
			</tr>
			<?php

		}
		?>
	</tbody>
	<tfoot>
		<tr class="success text-right table-heading-row">
			<td colspan="7"></td>
			<td>Total Debit : <?= number_format($total_cr,2) ?></td>
			<td></td>
		</tr>
	</tfoot>
</table>

</div> </div> </div>

<script>
$('#tbl_list').dataTable({
		"pagingType": "full_numbers"
	});
</script>