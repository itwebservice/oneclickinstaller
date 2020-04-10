<?php  
//Generic Files
include "../../../../model.php"; 
include "../../print_functions.php";
require("../../../../../classes/convert_amount_to_word.php"); 

//Parameters
$invoice_no = $_GET['invoice_no'];
$ticket_id = $_GET['ticket_id'];
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

$sq_passenger = mysql_query("select * from ticket_master_entries where ticket_id = '$ticket_id'");
$sq_fields = mysql_fetch_assoc(mysql_query("select * from ticket_master where ticket_id = '$ticket_id'"));

$other_tax = ($sq_fields['yq_tax'] + $sq_fields['yq_tax_markup'] + $sq_fields['g1_plus_f2_tax']) - $sq_fields['yq_tax_discount'] ;

$amount_in_word = $amount_to_word->convert_number_to_words($net_amount);
//Header
if($app_invoice_format == "Standard"){include "../headers/standard_header_html.php"; }
if($app_invoice_format == "Regular"){include "../headers/regular_header_html.php"; }
if($app_invoice_format == "Advance"){include "../headers/advance_header_html.php"; }
?>


<div class="col-md-12 mg_tp_20"><p class="border_lt"><span class="font_5">PASSENGER :  </span></p></div>
<!-- invoice_receipt_body_table-->
   <div class="main_block inv_rece_table main_block">
    <div class="row">
      <div class="col-md-12">
       <div class="table-responsive">
        <table class="table table-bordered no-marg" id="tbl_emp_list" style="padding: 0 !important;">
          <thead>
            <tr class="table-heading-row">
              <th>SR.NO</th>
              <th>PASSENGER</th>
              <th>SECTOR_FROM</th>
              <th>SECTOR_TO</th>
              <th>Departure</th>
              <th>PNR_NO</th>
              <th>FLIGHT_NO</th>
            </tr>
          </thead>
          <tbody>   
          <?php 
          $count = 1;
          while($row_passenger = mysql_fetch_assoc($sq_passenger))
          {
            $sq_dest1 = mysql_query("select * from ticket_trip_entries where ticket_id = '$row_passenger[ticket_id]'");
            while($sq_dest = mysql_fetch_assoc($sq_dest1)){
            ?>
            <tr class="odd">
              <td><?php echo $count; ?></td>
              <td><?php echo $row_passenger['first_name'].' '.$row_passenger['last_name']; ?></td>
              <td><?php echo $sq_dest['departure_city']; ?></td>
              <td><?php echo $sq_dest['arrival_city']; ?></td>
              <td><?php echo date("d-m-Y H:i:s", strtotime($sq_dest['departure_datetime'])); ?></td>
              <td style="text-transform: uppercase;"><?php echo $sq_dest['airlin_pnr']; ?></td>
              <td><?php echo $sq_dest['flight_no']; ?></td>
            </tr>
            <?php   
               $count++;
              }
          } ?>
          </tbody>
        </table>
       </div>
     </div>
    </div>
  </div>

<?php $net_amount1 =  $basic_cost1 + $sq_fields['service_charge'] + $other_tax + $sq_fields['service_tax_subtotal'] - $sq_fields['tds'];  ?>

 <!-- invoice_receipt_body_calculation -->
<section class="print_sec main_block">
  <div class="row">
    <div class="col-md-12">
      <div class="main_block inv_rece_calculation border_block">
        <div class="col-md-6"><p class="border_lt"><span class="font_5">AMOUNT </span><span class="float_r"><?php echo number_format($basic_cost1,2); ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">TOTAL </span><span class="font_5 float_r"><?php echo number_format($net_amount1,2); ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">SERVICE CHARGE </span><span class="float_r"><?php echo number_format($sq_fields['service_charge'],2); ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">ADVANCED PAID </span><span class="font_5 float_r"><?php echo number_format($total_paid,2); ?></span></p></div>
        <?php if($taxation_type == 'SGST CGST'){ ?>
        <div class="row no-marg">
          <div class="col-md-6"><p class="border_lt"><span class="font_5">TAX<?= '('.($service_tax_per/2).'%)' ?>[<?php echo 'SGST'; ?>] </span><span class="float_r"><?= number_format($service_tax/2,2) ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">CURRENT DUE </span><span class="font_5 float_r"><?php echo number_format($balance_amount,2); ?></span></p></div>
        </div>
        <div class="row no-marg">
          <div class="col-md-6"><p class="border_lt"><span class="font_5">TAX<?= '('.($service_tax_per/2).'%)' ?>[<?php echo 'CGST'; ?>] </span><span class="float_r"><?= number_format($service_tax/2,2) ?></span></p></div>
        </div><?php } 
        else{ ?>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">TAX<?= '('.$service_tax_per.'%)' ?>[<?= $taxation_type ?>] </span><span class="float_r"><?= number_format($service_tax,2) ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">CURRENT DUE </span><span class="font_5 float_r"><?php echo number_format($balance_amount,2); ?></span></p></div> <?php } ?>
        <div class="row no-marg">
          <div class="col-md-6"><p class="border_lt"><span class="font_5">OTHER TAX </span><span class="float_r"><?php echo number_format($other_tax,2); ?></span></p></div>
        </div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">TDS </span><span class="float_r"><?php echo number_format($sq_fields['tds'],2); ?></span></p></div>
      </div>
    </div>
  </div>
</section>

<?php 
//Footer
include "../generic_footer_html.php"; ?>