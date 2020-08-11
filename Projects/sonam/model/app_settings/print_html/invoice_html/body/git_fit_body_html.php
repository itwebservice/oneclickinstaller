<?php  
//Generic Files
include "../../../../model.php"; 
include "../../print_functions.php";
require("../../../../../classes/convert_amount_to_word.php"); 

//Parameters
$invoice_no = $_GET['invoice_no'];
$invoice_date = $_GET['invoice_date'];
$customer_id = $_GET['customer_id'];
$service_name = $_GET['service_name'];
$taxation_type = $_GET['taxation_type'];
$bank_name = $_GET['bank_name'];
$tour_name = $_GET['tour_name'];
$train_expense = $_GET['train_expense'];
$plane_expense = $_GET['plane_expense'];
$cruise_expense = $_GET['cruise_expense'];
$visa_amount = $_GET['visa_amount'];
$insuarance_amount = $_GET['insuarance_amount'];
$tour_subtotal = $_GET['tour_subtotal'];

$train_service_charge = $_GET['train_service_charge'];
$plane_service_charge = $_GET['plane_service_charge'];
$cruise_service_charge = $_GET['cruise_service_charge'];
$visa_service_charge = $_GET['visa_service_charge'];
$insuarance_service_charge = $_GET['insuarance_service_charge'];

$train_service_tax = $_GET['train_service_tax'];
$plane_service_tax = $_GET['plane_service_tax'];
$cruise_service_tax = $_GET['cruise_service_tax'];
$visa_service_tax = $_GET['visa_service_tax'];
$insuarance_service_tax = $_GET['insuarance_service_tax'];
$tour_service_tax = $_GET['tour_service_tax'];

$train_service_tax_subtotal = $_GET['train_service_tax_subtotal'];
$plane_service_tax_subtotal = $_GET['plane_service_tax_subtotal'];
$cruise_service_tax_subtotal = $_GET['cruise_service_tax_subtotal'];
$visa_service_tax_subtotal = $_GET['visa_service_tax_subtotal'];
$insuarance_service_tax_subtotal = $_GET['insuarance_service_tax_subtotal'];
$tour_service_tax_subtotal = $_GET['tour_service_tax_subtotal'];
$sac_code = $_GET['sac_code'];

$net_amount = $_GET['net_amount'];
($_GET['total_paid']=="")?$total_paid = 0:$total_paid = $_GET['total_paid'];
$total_balance = $net_amount - $total_paid;
$amount_in_word = $amount_to_word->convert_number_to_words($net_amount);

//Header
if($app_invoice_format == "Standard"){include "../headers/standard_header_html.php"; }
if($app_invoice_format == "Regular"){include "../headers/regular_header_html.php"; }
if($app_invoice_format == "Advance"){include "../headers/advance_header_html.php"; }
?>
<section class="no-pad main_block">
<div class="col-md-12 mg_tp_20"><p class="border_lt"><span class="font_5">Tour Name : <?= $tour_name?> </span></p></div>
  <!-- invoice_receipt_body_table-->
  <div class="main_block inv_rece_table">
    <div class="row">
      <div class="col-md-12">
       <div class="table-responsive">
        <table class="table table-bordered no-marg" id="tbl_emp_list" style="padding: 0 !important;">
          <thead>
            <tr class="table-heading-row">
              <th>Services</th>
              <th>Basic_Amount</th>
              <th>S. Charge</th>
              <th>Tax(%)</th>
              <?php if($taxation_type == 'SGST CGST'){ ?>
              <th>CGST</th>
              <th>SGST</th>
              <?php }
              else{ ?>
              <th><?= $taxation_type ?></th> <?php } ?>
              <th>Total_Amount</th>
            </tr>
          </thead>
          <tbody> 
          <?php 
          if($train_expense != '0'){ 
            $total_train = $train_expense+$train_service_charge+$train_service_tax_subtotal;?>  
            <tr>
              <td><strong class="font_5">Train</strong></td>
              <td class="text-right"><?php echo number_format($train_expense,2); ?></td>
              <td class="text-right"><?php echo number_format($train_service_charge,2); ?></td>
              <td class="text-right"><?php echo number_format($train_service_tax,2); ?></td>
              <?php if($taxation_type == 'SGST CGST'){ ?>
                <td><?php echo number_format($train_service_tax_subtotal/2,2); ?></td>
                <td><?php echo number_format($train_service_tax_subtotal/2,2); ?></td>
              <?php }
              else{ ?>
              <td class="text-right"><?php echo number_format($train_service_tax_subtotal,2); ?></td>
              <?php } ?>
              <td class="text-right"><?php echo number_format($total_train,2); ?></td>
            </tr>
            <?php } 
            if($plane_expense != '0'){
              $total_plane = $plane_expense+$plane_service_charge+$plane_service_tax_subtotal;?>
            <tr>
              <td><strong class="font_5">Flight</strong></td>
              <td class="text-right"><?php echo number_format($plane_expense,2); ?></td>
              <td class="text-right"><?php echo number_format($plane_service_charge,2); ?></td>
              <td class="text-right"><?php echo number_format($plane_service_tax,2); ?></td>
              <?php if($taxation_type == 'SGST CGST'){ ?>
                <td><?php echo number_format($plane_service_tax_subtotal/2,2); ?></td>
                <td><?php echo number_format($plane_service_tax_subtotal/2,2); ?></td>
              <?php }
              else{ ?>
              <td class="text-right"><?php echo number_format($plane_service_tax_subtotal,2); ?></td>
              <?php } ?>
              <td class="text-right"><?php echo number_format($total_plane,2); ?></td>
            </tr>
            <?php }
            if($cruise_expense != '0'){
              $total_cruise = $cruise_expense+$cruise_service_charge+$cruise_service_tax_subtotal;
            ?>
            <tr>
              <td><strong class="font_5">Cruise</strong></td>
              <td class="text-right"><?php echo number_format($cruise_expense,2); ?></td>
              <td class="text-right"><?php echo number_format($cruise_service_charge,2); ?></td>
              <td class="text-right"><?php echo number_format($cruise_service_tax,2); ?></td>
              <?php if($taxation_type == 'SGST CGST'){ ?>
                <td><?php echo number_format($cruise_service_tax_subtotal/2,2); ?></td>
                <td><?php echo number_format($cruise_service_tax_subtotal/2,2); ?></td>
              <?php }
              else{ ?>
              <td class="text-right"><?php echo number_format($cruise_service_tax_subtotal,2); ?></td>
              <?php } ?>
              <td class="text-right"><?php echo number_format($total_cruise,2); ?></td>
            </tr>
            <?php } 
            if($visa_amount != '0'){
            $total_visa = $visa_amount+$visa_service_charge+$visa_service_tax_subtotal; ?>
            <tr>
              <td><strong class="font_5">Visa</strong></td>
              <td class="text-right"><?php echo number_format($visa_amount,2); ?></td>
              <td class="text-right"><?php echo number_format($visa_service_charge,2); ?></td>
              <td class="text-right"><?php echo number_format($visa_service_tax,2); ?></td>
              <?php if($taxation_type == 'SGST CGST'){ ?>
                <td><?php echo number_format($visa_service_tax_subtotal/2,2); ?></td>
                <td><?php echo number_format($visa_service_tax_subtotal/2,2); ?></td>
              <?php }
              else{ ?>
              <td class="text-right"><?php echo number_format($visa_service_tax_subtotal,2); ?></td>
              <?php } ?>
              <td class="text-right"><?php echo number_format($total_visa,2); ?></td>
            </tr>
            <?php } 
            if($insuarance_amount != '0'){
            $total_ins = $insuarance_amount+$insuarance_service_charge+$insuarance_service_tax_subtotal;
            ?>
            <tr>
              <td><strong class="font_5">Insurance</strong></td>
              <td class="text-right"><?php echo number_format($insuarance_amount,2); ?></td>
              <td class="text-right"><?php echo number_format($insuarance_service_charge,2); ?></td>
              <td class="text-right"><?php echo number_format($insuarance_service_tax,2); ?></td>
              <?php if($taxation_type == 'SGST CGST'){ ?>
                <td><?php echo number_format($insuarance_service_tax_subtotal/2,2); ?></td>
                <td><?php echo number_format($insuarance_service_tax_subtotal/2,2); ?></td>
              <?php }
              else{ ?>
              <td class="text-right"><?php echo number_format($insuarance_service_tax_subtotal,2); ?></td>
              <?php } ?>
              <td class="text-right"><?php echo number_format($total_ins,2); ?></td>
            </tr>
            <?php } 
            if($tour_subtotal != '0'){
            $total_tour = $tour_subtotal+$tour_service_tax_subtotal; ?>
            <tr>
              <td><strong class="font_5">Tour</strong></td>
              <td class="text-right"><?php echo number_format($tour_subtotal,2); ?></td>
              <td class="text-right"><?php echo number_format(0,2); ?></td>
              <td class="text-right"><?php echo number_format($tour_service_tax,2); ?></td>
              <?php if($taxation_type == 'SGST CGST'){ ?>
                <td><?php echo number_format($tour_service_tax_subtotal/2,2); ?></td>
                <td><?php echo number_format($tour_service_tax_subtotal/2,2); ?></td>
              <?php }
              else{ ?>
              <td class="text-right"><?php echo number_format($tour_service_tax_subtotal,2); ?></td>
              <?php } ?>
              <td class="text-right"><?php echo number_format($total_tour,2); ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
       </div>
     </div>
    </div>
  </div>
</section>


<!-- invoice_receipt_body_calculation -->
<section class="print_sec main_block">
  <div class="row">
    <div class="col-md-12">
      <div class="main_block inv_rece_calculation border_block">
        <div class="col-md-4"><p class="border_lt"><span class="font_5">NET AMOUNT </span><span class="font_5 float_r"><?php echo $currency_logo." ".number_format($net_amount,2); ?></span></p></div>
        <div class="col-md-4"><p class="border_lt"><span class="font_5">PAID AMOUNT </span><span class="float_r"><?php echo $currency_logo." ".number_format($total_paid,2); ?></span></p></div>
        <div class="col-md-4"><p class="border_lt no-marg"><span class="font_5">BALANCE AMOUNT </span><span class="font_5 float_r"><?php echo $currency_logo." ".number_format($total_balance,2); ?></span></p></div>
      </div>
    </div>
  </div>
</section>

<?php 
//Footer
include "../generic_footer_html.php"; ?>