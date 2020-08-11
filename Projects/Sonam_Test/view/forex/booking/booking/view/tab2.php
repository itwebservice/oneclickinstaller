<?php 
$sale_total_amount=$row_booking['net_total'];
if($sale_total_amount==""){  $sale_total_amount = 0 ;  }

$cancel_amount=0;
$query = mysql_fetch_assoc(mysql_query("SELECT sum(payment_amount) as sum from forex_booking_payment_master where booking_id='$booking_id' and clearance_status != 'Pending' and clearance_status != 'Cancelled'"));
$paid_amount = $query['sum'];
$paid_amount = ($paid_amount == '')?'0':$paid_amount;

if($paid_amount >= $cancel_amount && $cancel_amount == '0'){
	$balance_amount = $sale_total_amount - $paid_amount;
}
else if($paid_amount > $cancel_amount && $cancel_amount != '0'){
	$balance_amount = 0;
}
else{
	$balance_amount = $cancel_amount - $paid_amount;
}
include "../../../../../model/app_settings/generic_sale_widget.php";
?>
<div class="row">    
  	<div class="col-xs-12">
  		<div class="profile_box main_block" style="margin-top: 25px">
           	<h3 class="editor_title">Summary</h3>
                <div class="table-responsive">
                    <table class="table table-bordered no-marg" id="tbl_list">
						<thead>
							<tr class="table-heading-row">
								<th>S_No.</th>
								<th>Date</th>
								<th>Mode</th>
								<th>Bank_Name</th>
								<th>Cheque_No/ID</th>
								<th>Amount</th>
							</tr>
							
						</thead>
						<tbody>
							<?php 
							$query = "SELECT * from forex_booking_payment_master where 1";		
							
							if($booking_id!=""){
								$query .= " and booking_id='$booking_id'";
							}
							
							$count = 0;
							$sq_pending_amount=0;
							$sq_cancel_amount=0;
							$sq_paid_amount=0;
						 	$Total_payment=0;
							$sq_payment = mysql_query($query);		

							while($row_payment = mysql_fetch_assoc($sq_payment)){
								if($row_payment['payment_amount'] != '0'){
										$count++;
										//$total_paid_amt= $total_paid_amt +  $row_payment['payment_amount'];
										//$Total_payment=$total_paid_amt-$sq_pend_pay['sum']-$sq_cancel_pay['sum'];

										$sq_bus_info = mysql_fetch_assoc(mysql_query("select * from forex_booking_master where booking_id='$row_payment[booking_id]'"));
										$sq_customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_bus_info[customer_id]'"));
										
										$bg='';
										$sq_paid_amount = $sq_paid_amount + $row_payment['payment_amount'];
										if($row_payment['clearance_status']=="Pending"){ $bg="warning";}
						                else if($row_payment['clearance_status']=="Cancelled"){ $bg="danger";}
										?>
										<tr class="<?= $bg?>">				
											<td><?= $count ?></td>
											<td><?= get_date_user($row_payment['payment_date']) ?></td>
											<td><?= $row_payment['payment_mode'] ?></td>
											<td><?= $row_payment['bank_name'] ?></td>
											<td><?= $row_payment['transaction_id'] ?></td>
											<td class="text-right"><?= $row_payment['payment_amount'] ?></td>
										</tr>
										<?php
									}
								}
							?>
						</tbody>
					</table>
                </div>
                
        </div>  
    </div>
</div>  
           