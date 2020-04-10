<?php
include "../../../model/model.php";

$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$income_type_id = $_POST['income_type_id'];
$financial_year_id = $_SESSION['financial_year_id'];

$query = "select * from other_income_master where 1 ";
if($from_date!="" && $to_date!=""){
	$from_date = get_date_db($from_date);
	$to_date = get_date_db($to_date);

	$query .= " and receipt_date between '$from_date' and '$to_date'";
}
if($income_type_id!=""){
	$query .= " and income_type_id='$income_type_id' ";
}
if($financial_year_id!=""){
	$query .=" and financial_year_id='$financial_year_id'";
}
$query .=" order by income_id desc";
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
	
<table class="table table-hover" id="income_table" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Income_Type</th>
			<th>Receipt_from</th>
			<th>Receipt_Date</th>
			<th>Mode</th>
			<th>Narration</th>
			<th class="text-right">Paid_Amount</th>
			<th class="text-center">View</th>
			<th class="text-center">Edit</th>
			<th class="text-center">Receipt</th>
		</tr>	
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$bg;
		$paid_amount=0;
		$sq_income = mysql_query($query);
		while($row_income = mysql_fetch_assoc($sq_income)){

			$sq_income_type_info = mysql_fetch_assoc(mysql_query("select * from ledger_master where ledger_id='$row_income[income_type_id]'"));
			$sq_paid = mysql_fetch_assoc(mysql_query("select * from other_income_payment_master where income_type_id='$row_income[income_id]'"));
			$paid_amount+=$sq_paid['payment_amount'];

			$year1 = explode("-", $sq_paid['payment_date']);
			$yr1 =$year1[0];
			$bg='';

			if($sq_paid['clearance_status']=="Pending"){ $bg='warning';
				$sq_pending_amount = $sq_pending_amount + $sq_paid['payment_amount'];
			}
			else if($sq_paid['clearance_status']=="Cancelled"){ $bg='danger';
				$sq_cancel_amount = $sq_cancel_amount + $sq_paid['payment_amount'];
			}

			$payment_id_name = "Hotel Payment ID";
			$payment_id = get_other_income_payment_id($sq_paid['payment_id'],$yr1);
			$receipt_date = date('d-m-Y');
			$booking_id = $row_income['receipt_from'];
			$customer_id = $sq_booking['customer_id'];
			$booking_name = $sq_income_type_info['ledger_name'].'('.$row_income['particular'].')';
			$travel_date = 'NA';
			$payment_amount = $sq_paid['payment_amount'];
			$payment_mode1 = $sq_paid['payment_mode'];
			$transaction_id = $sq_paid['transaction_id'];
			$payment_date = date('d-m-Y',strtotime($sq_paid['payment_date']));
			$bank_name = $sq_paid['bank_name'];
			$receipt_type ="Other Income";
			
			$url1 = BASE_URL."model/app_settings/print_html/receipt_html/receipt_body_html.php?payment_id_name=$payment_id_name&payment_id=$payment_id&receipt_date=$receipt_date&booking_id=$booking_id&customer_id=$customer_id&booking_name=$booking_name&travel_date=$travel_date&payment_amount=$payment_amount&transaction_id=$transaction_id&payment_date=$payment_date&bank_name=$bank_name&confirm_by=$confirm_by&receipt_type=$receipt_type&payment_mode=$payment_mode1&branch_status=$branch_status";
			?>
			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= $sq_income_type_info['ledger_name'] ?></td>
				<td><?= $row_income['receipt_from'] ?></td>
				<td><?= get_date_user($row_income['receipt_date']) ?></td>
				<td><?= ($sq_paid['payment_mode']=='')?'NA':$sq_paid['payment_mode'] ?></td>
				<td><?= $row_income['particular'] ?></td>
				<td class="text-right success"><?= $sq_paid['payment_amount'] ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="entry_display_modal(<?= $row_income['income_id'] ?>)" title="Journal entry"><i class="fa fa-eye"></i></button>
				</td>
				<td class="text-center">
					<button class="btn btn-info btn-sm" onclick="update_income_modal(<?= $sq_paid['payment_id'] ?>)"><i class="fa fa-pencil-square-o"></i></button>
				</td>
				<td>
					<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>
			</tr>
			<?php
		}
		$paid_amount = $paid_amount - $sq_pending_amount - $sq_cancel_amount;
		?>
	</tbody>
	<tfoot>
		<tr class="active">
			<th colspan="6" class="success"></th>
			<th class="text-right success">Total Paid: <?= number_format($paid_amount, 2); ?></th>
			<th colspan="3" class="success"></th>
		</tr>
	</tfoot>
</table>
<script type="text/javascript">
	$('#income_table').dataTable({
		"pagingType": "full_numbers"
	});
</script>

</div> </div> </div>