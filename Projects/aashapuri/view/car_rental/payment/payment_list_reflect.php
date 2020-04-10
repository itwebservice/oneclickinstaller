<?php
include "../../../model/model.php";

$booking_id = $_POST['booking_id'];
$payment_mode = $_POST['payment_mode'];
$financial_year_id = $_SESSION['financial_year_id'];
$payment_from_date = $_POST['payment_from_date'];
$payment_to_date = $_POST['payment_to_date'];
$emp_id = $_SESSION['emp_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status']; 
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];

$query = "select * from car_rental_payment where 1";
if($financial_year_id!=""){
	$query .=" and financial_year_id='$financial_year_id'";
}
if($booking_id!=""){
	$query .=" and booking_id='$booking_id'";
}
if($payment_mode!=""){
	$query .=" and payment_mode='$payment_mode'";
}
if($payment_from_date!='' && $payment_to_date!=''){
	$payment_from_date = get_date_db($payment_from_date);
	$payment_to_date = get_date_db($payment_to_date);
	$query .=" and payment_date between '$payment_from_date' and '$payment_to_date'";
}
include "../../../model/app_settings/branchwise_filteration.php";
$query .=" order by payment_id desc";
?>
<div class="row mg_tp_20"> <div class="col-xs-12 no-pad"> 
<div class="table-responsive">
<table class="table table-hover" id="tbl_vendor_list" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th></th>
			<th>Booking_ID</th>
			<th>Customer_Name</th>
			<th>Mode</th>
			<th>Receipt_Date</th>
			<th class="text-right">Amount</th>
			<th class="text-center">Receipt</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$count = 0;
		$sq_payment = mysql_query($query);
		$total_paid_amt=0;
		while($row_payment = mysql_fetch_assoc($sq_payment))
			if($row_payment['payment_amount']!=0){
			{
				$count++;
				$sq_booking = mysql_fetch_assoc(mysql_query("select * from car_rental_booking where booking_id='$row_payment[booking_id]'"));
				$total_sale = $sq_booking['total_fees'];
				$sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from car_rental_payment where clearance_status!='Cancelled' and booking_id='$row_payment[booking_id]'"));
				$total_pay_amt = $sq_pay['sum'];
				$outstanding =  $total_sale - $total_pay_amt;

				$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_booking[customer_id]'"));
				$date = $sq_booking['created_at'];
				$yr = explode("-", $date);
				$year =$yr[0];
				if($sq_customer['type']=='Corporate'){
					$customer_name = $sq_customer['company_name'];
				}else{
					$customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
				}
				
				$total_paid_amt = $total_paid_amt + $row_payment['payment_amount'];
				if($row_payment['clearance_status']=="Pending"){ $bg='warning';
				$sq_pending_amount = $sq_pending_amount + $row_payment['payment_amount'];
				}
				else if($row_payment['clearance_status']=="Cancelled"){ $bg='danger';
					$sq_cancel_amount = $sq_cancel_amount + $row_payment['payment_amount'];
				}
				else{
					$bg='';
				}

				$payment_id_name = "Car Rental Payment ID";
				$payment_id = get_car_rental_booking_payment_id($row_payment['payment_id'],$year);
				$receipt_date = date('d-m-Y');
				$booking_id = get_car_rental_booking_id($row_payment['booking_id'],$year);
				$customer_id = $sq_booking['customer_id'];
				$booking_name = "Car Rental Booking";
				$travel_date = date('d-m-Y',strtotime($sq_booking['traveling_date']));
				$payment_amount = $row_payment['payment_amount'];
				$payment_mode1 = $row_payment['payment_mode'];
				$transaction_id = $row_payment['transaction_id'];
				$payment_date = date('d-m-Y',strtotime($row_payment['payment_date']));
				$bank_name = $row_payment['bank_name'];
				$receipt_type = "Car Rental Receipt";				

				$url1 = BASE_URL."model/app_settings/print_html/receipt_html/receipt_body_html.php?payment_id_name=$payment_id_name&payment_id=$payment_id&receipt_date=$receipt_date&booking_id=$booking_id&customer_id=$customer_id&booking_name=$booking_name&travel_date=$travel_date&payment_amount=$payment_amount&transaction_id=$transaction_id&payment_date=$payment_date&bank_name=$bank_name&confirm_by=$confirm_by&receipt_type=$receipt_type&payment_mode=$payment_mode1&branch_status=$branch_status&outstanding=$outstanding";

				?>
				<tr class="<?= $bg ?>">
					<td><?= $count ?></td>
					<td>
						<?php 
						if($row_payment['payment_mode']=="Cash" || $row_payment['payment_mode']=="Cheque"){
							?>
							<input type="checkbox" id="chk_car_rental_payment_<?= $count ?>" name="chk_car_rental_payment" value="<?= $row_payment['payment_id'] ?>">
							<?php } ?>
					</td>
					<td><?= get_car_rental_booking_id($row_payment['booking_id'],$year) ?></td>
					<td><?= $customer_name ?></td>
					<td><?= $row_payment['payment_mode'] ?></td>
					<td><?= date('d/m/Y', strtotime($row_payment['payment_date'])) ?></td>
					<td class="text-right success"><?= $row_payment['payment_amount'] ?></td>
					<td>
						<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
					</td>
					<td>
						<button class="btn btn-info btn-sm" onclick="payment_update_modal(<?= $row_payment['payment_id'] ?>)" title="Edit Detail"><i class="fa fa-pencil-square-o"></i></button>
					</td>
				</tr>
			<?php
			}
		}
		?>
	</tbody>
	<tfoot>
		<tr class="active">
			<th colspan="3" class="text-right info">Total Amount: <?= number_format((($total_paid_amt=="") ? 0 : $total_paid_amt), 2); ?></th>
			<th colspan="2" class="text-right warning">Pending Clearance : <?= number_format((($sq_pending_amount=="") ? 0 : $sq_pending_amount), 2); ?></th>
			<th colspan="2" class="text-right danger">Cancelled : <?= number_format((($sq_cancel_amount=="") ? 0 : $sq_cancel_amount), 2); ?></th>
			<th colspan="2" class="text-right success">Paid : <?= number_format(($total_paid_amt - $sq_pending_amount - $sq_cancel_amount), 2); ?></th>
		</tr>
	</tfoot>
</table>
</div>
</div>
</div>

<?php if($payment_mode=="Cheque" || $payment_mode=="Cash"): ?>
<div class="panel panel-default panel-body pad_8 mg_tp_20 pd_bt_51">
<div class="row">
	<div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10_sm_xs">
    <select name="bank_name_reciept" id="bank_name_reciept" title="Bank Name" class="form-control">
      <?php 
      $sq_bank = mysql_query("select * from bank_name_master");
      while($row_bank = mysql_fetch_assoc($sq_bank)){
        ?>
        <option value="<?= $row_bank['label'] ?>"><?= $row_bank['bank_name'] ?></option>
        <?php
      }
      ?>
    </select>
  </div>
  <div class="col-md-4 col-sm-6 col-xs-12">
	<?php 
	if($payment_mode=="Cheque"){
		?>
		<button class="btn btn-danger ico_left" onclick="cheque_bank_receipt_generate()"><i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Bank Receipt</button>
		<?php
	}
	if($payment_mode=="Cash"){
		?>
		<button class="btn btn-danger ico_left" onclick="cash_bank_receipt_generate()"><i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Bank Receipt</button>
		<?php
	}	
	?>
	</div>
</div>
</div>
<?php endif; ?>

<script>
$('#tbl_vendor_list').dataTable({
		"pagingType": "full_numbers",
		createdRow: function(row, data, dataIndex){
	       // Initialize custom control
	       $("input[type='radio'], input[type='checkbox']").labelauty({ label: false, maximum_width: "20px" });
	          // ... skipped ...
	       }
	});

function cash_bank_receipt_generate()
{
	var bank_name_reciept = $('#bank_name_reciept').val();
	var payment_id_arr = new Array();

	$('input[name="chk_car_rental_payment"]:checked').each(function(){
		payment_id_arr.push($(this).val());
	});
	if(payment_id_arr.length==0){
		error_msg_alert('Please select at least one payment to generate receipt!');
		return false;
	}

	var base_url = $('#base_url').val();
	var url = base_url+"view/bank_receipts/car_rental_payment/cash_bank_receipt.php?payment_id_arr="+payment_id_arr+'&bank_name_reciept='+bank_name_reciept;
	window.open(url, '_blank');
}

function cheque_bank_receipt_generate()
{
	var bank_name_reciept = $('#bank_name_reciept').val();
	var payment_id_arr = new Array();
	var branch_name_arr = new Array();

	$('input[name="chk_car_rental_payment"]:checked').each(function(){
		var id = $(this).attr('id');
		var offset = id.substring(23);
		var branch_name = $('#branch_name_'+offset).val();

		payment_id_arr.push($(this).val());
		branch_name_arr.push(branch_name);
	});

	if(payment_id_arr.length==0){
		error_msg_alert('Please select at least one payment to generate receipt!');
		return false;
	}
	$('input[name="chk_car_rental_payment"]:checked').each(function(){

		var id = $(this).attr('id');
		var offset = id.substring(23);
		var branch_name = $('#branch_name_'+offset).val();

		if(branch_name==""){
			error_msg_alert("Please enter branch name for selected payments!");				
			exit(0);
		}
	});
	var base_url = $('#base_url').val();

	var url = base_url+"view/bank_receipts/car_rental_payment/cheque_bank_receipt.php?payment_id_arr="+payment_id_arr+'&branch_name_arr='+branch_name_arr+'&bank_name_reciept='+bank_name_reciept;
	window.open(url, '_blank');
}
</script>
<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>