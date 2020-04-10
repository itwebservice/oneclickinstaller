<?php
include "../../../model/model.php";

$customer_id = $_POST['customer_id'];
$booking_id = $_POST['booking_id'];
$payment_for = $_POST['payment_for'];
$payment_mode = $_POST['payment_mode'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$financial_year_id = $_SESSION['financial_year_id'];
$cust_type = $_POST['cust_type'];
$company_name = $_POST['company_name'];
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];

?>
<div class="row mg_tp_20"> <div class="col-xs-12 no-pad"> <div class="table-responsive">
	
<table class="table table-hover" id="tbl_list" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th></th>
			<th>Booking_ID</th>
			<th>Customer_Name</th>
			<th>Receipt_For</th>
			<th>Mode</th>
			<th>Receipt_Date</th>
			<?php if($payment_mode=="Cheque"){ ?> <th>Branch_Name</th>  <?php }  ?> 
			<th class="text-right success">Amount</th>
			<th>Receipt</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = $total = $pending = $cancelled = 0;

		$query = "select * from package_payment_master where 1 ";
		if($customer_id!=""){
			$query .=" and booking_id in (select booking_id from package_tour_booking_master where customer_id='$customer_id')";
		}
		if($booking_id!=""){
			$query .=" and booking_id='$booking_id' ";
		}
		if($payment_for!=""){
			$query .= " and payment_for='$payment_for'";
		}
		if($payment_mode!=""){
			$query .= " and payment_mode='$payment_mode'";
		}
		if($from_date!="" && $to_date!=""){
			$from_date = get_date_db($from_date);
			$to_date = get_date_db($to_date);
			$query .= " and date between '$from_date' and '$to_date'";
		}
		if($financial_year_id!=""){
			$query .= " and financial_year_id='$financial_year_id'";
		}
		if($cust_type != ""){
		    $query .= " and booking_id in (select booking_id from package_tour_booking_master where customer_id in ( select customer_id from customer_master where type='$cust_type' ))";
		}
		if($company_name != ""){
		    $query .= " and booking_id in (select booking_id from package_tour_booking_master where customer_id in ( select customer_id from customer_master where company_name='$company_name' ))";
		}
		if($role == "B2b"){
			$query .= " and booking_id in (select booking_id from package_tour_booking_master where emp_id ='$emp_id')";
		}
		include "../../../model/app_settings/branchwise_filteration.php";
		$query .=" order by payment_id desc";
		$sq_payment = mysql_query($query);
		while($row_payment = mysql_fetch_assoc($sq_payment)){
			if($row_payment['amount']!=0){

				$sq_booking = mysql_fetch_assoc(mysql_query("select * from package_tour_booking_master where booking_id='$row_payment[booking_id]'"));
				$total_sale = $sq_booking['total_travel_expense'] + $sq_booking['actual_tour_expense'];
				$sq_pay = mysql_fetch_assoc(mysql_query("select sum(amount) as sum from package_payment_master where clearance_status!='Cancelled' and booking_id='$row_payment[booking_id]'"));
				$total_pay_amt = $sq_pay['sum'];
				$outstanding =  $total_sale - $total_pay_amt;

				$date = $sq_booking['booking_date'];
				$yr = explode("-", $date);
				$year =$yr[0];

				$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_booking[customer_id]'"));
				if($sq_customer['type'] == 'Corporate'){
					$customer_name = $sq_customer['company_name'];
				}else{
					$customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
				}
				
				$bg = "";
				if($row_payment['clearance_status']=="Pending"){
					$bg = "warning";
					$pending = $pending + $row_payment['amount'];
				}
				else if($row_payment['clearance_status']=="Cancelled"){
					$bg = "danger";
					$cancelled = $cancelled + $row_payment['amount'];
				}

				$total = $total + $row_payment['amount'];

				$payment_id_name = "Package Payment ID";
				$payment_id = get_package_booking_payment_id($row_payment['payment_id'],$year);
				$receipt_date = date('d-m-Y');
				$booking_id = get_package_booking_id($row_payment['booking_id'],$year);
				$customer_id = $sq_booking['customer_id'];
				$booking_name = "Package Booking";
				$travel_date = date('d-m-Y',strtotime($sq_booking['tour_from_date']));
				$tour = $sq_booking['tour_name'];
				$payment_amount = $row_payment['amount'];
				$payment_mode1 = $row_payment['payment_mode'];
				$transaction_id = $row_payment['transaction_id'];
				$payment_date = $row_payment['date'];
				$bank_name = $row_payment['bank_name'];
				$receipt_type = ($row_payment['payment_for']=='Travelling') ? "Travel Receipt" : "Tour Receipt";

				$url1 = BASE_URL."model/app_settings/print_html/receipt_html/receipt_body_html.php?payment_id_name=$payment_id_name&payment_id=$payment_id&receipt_date=$receipt_date&booking_id=$booking_id&customer_id=$customer_id&booking_name=$booking_name&travel_date=$travel_date&payment_amount=$payment_amount&transaction_id=$transaction_id&payment_date=$payment_date&bank_name=$bank_name&confirm_by=$confirm_by&receipt_type=$receipt_type&payment_mode=$payment_mode1&branch_status=$branch_status&outstanding=$outstanding&tour=$tour";

				?>
				<tr class="<?= $bg ?>">
					<td><?= ++$count ?></td>
					<td><input type="checkbox" id="chk_receipt_<?= $count ?>" name="chk_receipt" data-amount="<?= $row_payment['amount'] ?>" data-payment-id="<?= $row_payment['payment_id'] ?>" data-offset="<?= $count ?>"></td>
					<td><?= get_package_booking_id($row_payment['booking_id'],$year) ?></td>
					<td><?= $customer_name ?></td>
					<td><?= $row_payment['payment_for'] ?></td>
					<td><?= $row_payment['payment_mode'] ?></td>
					<td><?= get_date_user($row_payment['date']) ?></td>
					<?php 
					    if($payment_mode=="Cheque"){
					      ?>
					      <th>
					        <input type="text" id="branch_name_<?= $count ?>" name="branch_name" class="form-control" placeholder="Branch Name">
					      </th>
					      <?php
					    }
					?>		
					<td class="success text-right"><?= $row_payment['amount'] ?></td>
					<td>
						<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
					</td>
					<td><a class="btn btn-info btn-sm" onclick="update_modal(<?= $row_payment['payment_id'] ?>)" title="Edit Detail"><i class="fa fa-pencil-square-o"></i></a></td>
				</tr>
				<?php
			}
		}
		?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="4" class="info text-right">Total Amount : <?= number_format($total, 2); ?></th>
			<th colspan="2" class="warning text-right">Pending Clearance : <?= number_format($pending, 2); ?></th>
			<th colspan="2" class="danger text-right">Cancelled : <?= number_format($cancelled, 2);?></th>
			<th colspan="2" class="success text-right">Total Paid : <?= number_format(($total - $pending - $cancelled), 2); ?></th>
		</tr>
	</tfoot>
</table>

</div> </div> </div>

<?php if($payment_mode=="Cheque" || $payment_mode=="Cash"): ?>
<div class="panel panel-default panel-body pad_8 text-center mg_tp_20 pd_bt_51">
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
$('#tbl_list').dataTable({
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

  var payment_amount = 0;
  var payment_type = 'cash';
  var payment_id = '';

  if($('input[name="chk_receipt"]:checked').length==0){
    error_msg_alert('Please select at least one payment to generate receipt!');
    return false;
  }

  $('input[name="chk_receipt"]:checked').each(function(){

    var amount = $(this).attr('data-amount');
    payment_amount = parseFloat(payment_amount) + parseFloat(amount);

  });

  url = base_url()+'view/bank_receipts/group_tour_payment/cash_payment_receipt.php?payment_amount='+payment_amount+'&payment_type='+payment_type+'&payment_id='+payment_id+'&bank_name_reciept='+bank_name_reciept;
	window.open(url, '_blank');  
}


function cheque_bank_receipt_generate()
{
  var bank_name_reciept = $('#bank_name_reciept').val();

  var payment_amount = 0;
  var payment_id_arr = new Array();
  var branch_name_arr = new Array();

  $('input[name="chk_receipt"]:checked').each(function(){

    var amount = $(this).attr('data-amount');
    var payment_id = $(this).attr('data-payment-id');
    var offset = $(this).attr('data-offset');
    var branch_name = $('#branch_name_'+offset).val();

    payment_amount = parseFloat(payment_amount) + parseFloat(amount);

    payment_id_arr.push(payment_id);
    branch_name_arr.push(branch_name);

  });
	  if(payment_id_arr.length==0){

			error_msg_alert('Please select at least one payment to generate receipt!');

			return false;

	  } 

	$('input[name="chk_receipt"]:checked').each(function(){

		//var id = $(this).attr('id');
		 var offset = $(this).attr('data-offset');
		var branch_name = $('#branch_name_'+offset).val();

		if(branch_name==""){
			error_msg_alert("Please enter branch name for selected payments!");				
			exit(0);
		}
	});
    
  url = base_url()+'view/bank_receipts/package_tour_payment/cheque_payment_receipt.php?payment_id='+payment_id_arr+'&branch_name='+branch_name_arr+'&total_amount='+payment_amount+'&bank_name_reciept='+bank_name_reciept;
                window.open(url, '_blank');  
}
</script>
<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>