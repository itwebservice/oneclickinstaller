<?php  
//Generic Files
include "../../../../model.php"; 
include "../../print_functions.php";
require("../../../../../classes/convert_amount_to_word.php"); 

//Parameters
$invoice_no = $_GET['invoice_no'];
$train_ticket_id = $_GET['train_ticket_id'];
$invoice_date = $_GET['invoice_date'];
$customer_id = $_GET['customer_id'];
$service_name = $_GET['service_name'];
$basic_cost1 = $_GET['basic_cost'];
$taxation_type = $_GET['taxation_type'];
$service_tax_per = $_GET['service_tax_per'];
$service_tax = $_GET['service_tax'];
$net_amount = $_GET['net_amount'];
$bank_name = $_GET['bank_name'];
$total_paid = $_GET['total_paid'];
$balance_amount = $_GET['balance_amount'];
$sac_code = $_GET['sac_code'];

$sq_passenger = mysql_query("select * from  train_ticket_master_entries where train_ticket_id = '$train_ticket_id'");
$sq_passenger_count = mysql_fetch_assoc(mysql_query("select count(*) as cnt from  train_ticket_master_entries where train_ticket_id = '$train_ticket_id'"));
$sq_fields = mysql_fetch_assoc(mysql_query("select * from train_ticket_master where train_ticket_id = '$train_ticket_id'"));

$net_amount1 =  $basic_cost1 + $sq_fields['service_charge'] + $sq_fields['delivery_charges'] + $k3 + $sq_fields['service_tax_subtotal'];

$amount_in_word = $amount_to_word->convert_number_to_words($net_amount);
//Header
if($app_invoice_format == "Standard"){include "../headers/standard_header_html.php"; }
if($app_invoice_format == "Regular"){include "../headers/regular_header_html.php"; }
if($app_invoice_format == "Advance"){include "../headers/advance_header_html.php"; }
?>



<div class="col-md-12 mg_tp_20"><p class="border_lt"><span class="font_5">PASSENGER (s):  <?= $sq_passenger_count['cnt'] ?></span><span><?= $sq_hotel['p_name'] ?></span></p></div>
<!-- invoice_receipt_body_table-->
   <div class="main_block inv_rece_table main_block">
    <div class="row">
      <div class="col-md-12">
       <div class="table-responsive">
        <table class="table table-bordered no-marg" id="tbl_emp_list" style="padding: 0 !important;">
          <thead>
            <tr class="table-heading-row">
              <th>SR.NO</th>
              <th>Name</th>
              <th>Travel_From</th>
              <th>Travel_To</th>
              <th>Departure</th>
              <th>Train_Name</th>
              <th>Train_No</th>
            </tr>
          </thead>
          <tbody>   
          <?php 
          $count = 1;
          while($row_passenger = mysql_fetch_assoc($sq_passenger)){
            $sq_dest1 = mysql_query("select * from train_ticket_master_trip_entries where train_ticket_id = '$row_passenger[train_ticket_id]'");
            while($sq_dest = mysql_fetch_assoc($sq_dest1)){
            ?>
            <tr class="odd">
              <td><?php echo $count; ?></td>
              <td><?php echo $row_passenger['first_name'].' '.$row_passenger['last_name']; ?></td>
              <td><?php echo $sq_dest['travel_from']; ?></td>
              <td><?php echo $sq_dest['travel_to']; ?></td>
              <td><?php echo date("d-m-Y H:i:s", strtotime($sq_dest['travel_datetime'])); ?></td>
              <td><?php echo $sq_dest['train_name']; ?></td>
              <td><?php echo $sq_dest['train_no']; ?></td>
            </tr>
            <?php $count++;
              }
             } ?>
          </tbody>
        </table>
       </div>
     </div>
    </div>
  </div>

 <!-- invoice_receipt_body_calculation -->

<section class="print_sec main_block">
  <div class="row">
    <div class="col-md-12">
      <div class="main_block inv_rece_calculation border_block">
        <div class="col-md-6"><p class="border_lt"><span class="font_5">AMOUNT </span><span class="font_5 float_r"><?= $currency_logo." ".$basic_cost1 ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">TOTAL </span><span class="font_5 float_r"><?= $currency_logo." ".number_format($net_amount1,2) ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">SERVICE CHARGE </span><span class="float_r"><?= $currency_logo." ".number_format($sq_fields['service_charge'],2) ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">ADVANCED PAID </span><span class="font_5 float_r"><?= $currency_logo." ".number_format($total_paid,2) ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">DELIVERY CHARGE </span><span class="float_r"><?php echo $currency_logo." ".number_format( $sq_fields['delivery_charges'],2) ; ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">CURRENT DUE </span><span class="font_5 float_r"><?= $currency_logo." ".number_format($balance_amount,2) ?></span></p></div>
        <?php if($taxation_type == 'SGST CGST'){ ?>
        <div class="row no-marg">
          <div class="col-md-6"><p class="border_lt"><span class="font_5">TAX<?= '('.($service_tax_per/2).'%)' ?>[<?php echo 'SGST'; ?>] </span><span class="float_r"><?= $currency_logo." ".number_format($service_tax/2,2) ?></span></p></div>
        </div>
        <div class="row no-marg">
          <div class="col-md-6"><p class="border_lt"><span class="font_5">TAX<?= '('.($service_tax_per/2).'%)' ?>[<?php echo 'CGST'; ?>] </span><span class="float_r"><?= $currency_logo." ".number_format($service_tax/2,2) ?></span></p></div>
        </div><?php }
        else{ ?>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">TAX<?= '('.$service_tax_per.'%)' ?>[<?= $taxation_type ?>] </span><span class="float_r"><?= $currency_logo." ".number_format($service_tax,2) ?></span></p></div> <?php } ?>
      </div>
    </div>
  </div>
</section>

<?php 
//Footer
include "../generic_footer_html.php"; ?>