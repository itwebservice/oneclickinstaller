<?php
include "../../../../model/model.php";

$customer_id = $_POST['customer_id'];
$passport_id = $_POST['passport_id'];
$payment_mode = $_POST['payment_mode'];
$financial_year_id = $_SESSION['financial_year_id'];
$payment_from_date = $_POST['payment_from_date'];
$payment_to_date = $_POST['payment_to_date'];
$cust_type = $_POST['cust_type'];
$company_name = $_POST['company_name'];
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];

?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_passport_payment" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th></th>
			<th>Booking_ID</th>
			<th>Customer_Name</th>
			<th>Receipt_Date</th>
			<th>Mode</th>
			<th>Branch_Name</th>
			<th class="text-right">Amount</th>
			<th>Receipt</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$query = "select * from passport_payment_master where 1";
		if($financial_year_id!=""){
			$query .= " and financial_year_id='$financial_year_id'";
		}
		if($passport_id!=""){
			$query .= " and passport_id='$passport_id'";
		}
		if($payment_mode!=""){
			$query .= " and payment_mode='$payment_mode'";
		}
		if($customer_id!=""){
			$query .= " and passport_id in (select passport_id from passport_master where customer_id='$customer_id')";
		}
		if($payment_from_date!='' && $payment_to_date!=''){
			$payment_from_date = get_date_db($payment_from_date);
			$payment_to_date = get_date_db($payment_to_date);
			$query .=" and payment_date between '$payment_from_date' and '$payment_to_date'";
		}
		if($cust_type != ""){
			$query .= " and passport_id in (select passport_id from passport_master where customer_id in ( select customer_id from customer_master where type='$cust_type' ))";
		}
		if($company_name != ""){
			$query .= " and passport_id in (select passport_id from passport_master where customer_id in ( select customer_id from customer_master where company_name='$company_name' ))";
		}
		if($branch_status=='yes'){
			if($role=='Branch Admin' || $role=='Accountant' || $role_id>'7'){
				$query .= " and branch_admin_id = '$branch_admin_id'";
			}
			elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
				$query .= " and passport_id in (select passport_id from passport_master where emp_id ='$emp_id') and branch_admin_id = '$branch_admin_id'";
			}
		}
		elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
			$query .= " and passport_id in (select passport_id from passport_master where emp_id ='$emp_id')";
		}
	 
		$query .= " order by passport_id desc ";
		$count = 0;
		$bg;

		$sq_passport_payment = mysql_query($query);
		$sq_pending_amount = 0;
		$sq_cancel_amount = 0;
		$sq_paid_amount = 0;

		while($row_passport_payment = mysql_fetch_assoc($sq_passport_payment)){
			if($row_passport_payment['payment_amount'] != '0'){
				$count++;

				$sq_passport_info = mysql_fetch_assoc(mysql_query("select * from passport_master where passport_id='$row_passport_payment[passport_id]'"));
				$total_sale = $sq_passport_info['passport_total_cost'];
				$sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from passport_payment_master where clearance_status!='Cancelled' and passport_id='$row_passport_payment[passport_id]'"));
				$total_pay_amt = $sq_pay['sum'];
				$outstanding =  $total_sale - $total_pay_amt;
				$date = $sq_passport_info['created_at'];
				$yr = explode("-", $date);
				$year =$yr[0];

				$sq_customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_passport_info[customer_id]'"));
				if($sq_customer_info['type']=='Corporate'){
					$customer_name = $sq_customer_info['company_name'];
				}else{
					$customer_name = $sq_customer_info['first_name'].' '.$sq_customer_info['last_name'];
				}
				if($row_passport_payment['clearance_status']=="Pending"){ 
					$bg='warning';
					$sq_pending_amount = $sq_pending_amount + $row_passport_payment['payment_amount'];
				}
				else if($row_passport_payment['clearance_status']=="Cancelled"){ 
					$bg='danger';
					$sq_cancel_amount = $sq_cancel_amount + $row_passport_payment['payment_amount'];
				}			
				else{  
					$bg='';				
				}
				$sq_paid_amount = $sq_paid_amount + $row_passport_payment['payment_amount'];

				$payment_id_name = "Passport Payment ID";
				$payment_id = get_passport_booking_payment_id($row_passport_payment['payment_id'],$year);
				$receipt_date = date('d-m-Y');
				$booking_id = get_passport_booking_id($row_passport_payment['passport_id'],$year);
				$customer_id = $sq_passport_info['customer_id'];
				$booking_name = "Passport Booking";
				$travel_date = 'NA';
				$payment_amount = $row_passport_payment['payment_amount'];
				$payment_mode1 = $row_passport_payment['payment_mode'];
				$transaction_id = $row_passport_payment['transaction_id'];
				$payment_date = date('d-m-Y',strtotime($row_passport_payment['payment_date']));
				$bank_name = $row_passport_payment['bank_name'];

				$receipt_type = "Passport Receipt";

				$url1 = BASE_URL."model/app_settings/print_html/receipt_html/receipt_body_html.php?payment_id_name=$payment_id_name&payment_id=$payment_id&receipt_date=$receipt_date&booking_id=$booking_id&customer_id=$customer_id&booking_name=$booking_name&travel_date=$travel_date&payment_amount=$payment_amount&transaction_id=$transaction_id&payment_date=$payment_date&bank_name=$bank_name&confirm_by=$confirm_by&receipt_type=$receipt_type&payment_mode=$payment_mode1&branch_status=$branch_status&outstanding=$outstanding";
				?>
				<tr class="<?= $bg;?>">				
					<td><?= $count ?></td>
					<td>
						<?php 
						if($row_passport_payment['payment_mode']=="Cash" || $row_passport_payment['payment_mode']=="Cheque"){
							?>
							<input type="checkbox" id="chk_passport_payment_<?= $count ?>" name="chk_passport_payment" value="<?= $row_passport_payment['payment_id'] ?>">
							<?php	
						}
						?>					
					</td>
					<td><?= get_passport_booking_id($row_passport_payment['passport_id'],$year) ?></td>
					<td><?= $customer_name ?></td>
					<td><?= date('d/m/Y', strtotime($row_passport_payment['payment_date'])) ?></td>
					<td><?= $row_passport_payment['payment_mode'] ?></td>
					<td>
						<?php 
						if($payment_mode=="Cheque"){
							?>
							<input type="text" id="branch_name_<?= $count ?>" name="branch_name_d" class="form-control" placeholder="Branch Name" style="width:120px">
							<?php
						}
						?>
					</td>
					<td class="text-right success"><?= $row_passport_payment['payment_amount'] ?></td>
					<td>
						<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
					</td>
					<td>
						<button class="btn btn-info btn-sm" onclick="passport_payment_update_modal(<?= $row_passport_payment['payment_id'] ?>)" title="Edit Detail"><i class="fa fa-pencil-square-o"></i></button>
					</td>
				</tr>
				<?php
			}
		}
		?>
	</tbody>
	<tfoot>
		<tr class="active">
			<th class="text-right" colspan="3">Paid Amount: <?= number_format($sq_paid_amount, 2); ?></th>
			<th colspan="3" class="text-right warning">Pending Clearance : <?= number_format($sq_pending_amount, 2); ?></th>
			<th colspan="2" class="text-right danger">Cancel Amount: <?= number_format($sq_cancel_amount, 2); ?></th>
			<?php $payment_amount1 = $sq_paid_amount - $sq_pending_amount - $sq_cancel_amount ?>
			<th colspan="2" class="text-right success">Payment Amount : <?= number_format($payment_amount1, 2); ?></th>
		</tr>
	</tfoot>	
</table>

</div> </div> </div>


<?php if($payment_mode=="Cheque" || $payment_mode=="Cash"): ?>
<div class="row mg_tp_20 pd_bt_51">
	<div class="col-md-4">
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
	<div class="col-md-4">
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
<?php endif; ?>

<script>
$('#tbl_passport_payment').dataTable({
		"pagingType": "full_numbers",
		createdRow: function(row, data, dataIndex){
	       // Initialize custom control
	       $("input[type='radio'], input[type='checkbox']").labelauty({ label: false, maximum_width: "20px" });
	          // ... skipped ...
	       }
	});
</script>
<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>