<?php 

include "../../../../model/model.php";
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];
echo "hi".$branch_status;
?>
<input type="hidden" id="branch_admin_id1" name="branch_admin_id1" value="<?= $branch_admin_id ?>" >
<div class="modal fade" id="save_modal" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">New Receipt</h4>

      </div>

      <div class="modal-body">



        <form id="frm_save">

         <div class="row">

            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

              <select name="customer_id" id="customer_id" title="Customer Name" style="width:100%" onchange="booking_dropdown_load('customer_id', 'booking_id');">
 
                   <?php get_customer_dropdown($role,$branch_admin_id,$branch_status); ?>
              </select>

            </div>

            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

              <select name="booking_id" id="booking_id" style="width:100%" title="Booking ID" onchange="get_outstanding('forex',this.id);">

                <option value="">*Select Booking</option>

              </select>

            </div>           

          <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

            <input type="text" id="payment_date" name="payment_date" placeholder="Date" title="Date" value="<?= date('d-m-Y')?>" onchange="check_valid_date(this.id)">

          </div>  

          <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

            <input type="text" id="payment_amount" name="payment_amount" placeholder="*Amount" title="Amount" onchange="validate_balance(this.id);payment_amount_validate(this.id,'payment_mode','transaction_id','bank_name','bank_id')">

          </div>             

          <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

            <select name="payment_mode" id="payment_mode" title="Mode" onchange="payment_master_toggles(this.id, 'bank_name', 'transaction_id', 'bank_id')">

                <?php get_payment_mode_dropdown(); ?>

            </select>

          </div>

          <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

            <input type="text" id="bank_name" name="bank_name" class="form-control bank_suggest" placeholder="Bank Name" title="Bank Name" readonly>

          </div>

          <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

            <input type="text" id="transaction_id" name="transaction_id" onchange="validate_specialChar(this.id)" placeholder="Cheque No/ID" title="Cheque No/ID" readonly>

          </div>

          <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

            <select name="bank_id" id="bank_id" title="Creditor Bank" disabled>

              <?php get_bank_dropdown(); ?>

            </select>

          </div>

        </div>
        <div class="row">
          <div class="col-md-3 col-sm-3">
            <input type="text" id="outstanding" name="outstanding" class="form-control" placeholder="Outstanding" title="Outstanding" readonly/>
          </div>
          <div class="col-md-9 col-sm-9">
           <span style="color: red;line-height: 35px;" data-original-title="" title="" class="note"><?= $txn_feild_note ?></span>
         </div>
        </div>



        <div class="row text-center">

            <div class="col-xs-12">

              <button id="btn_save" class="btn btn-sm btn-success"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>

            </div>

        </div>



        </form>

        

      </div>     

    </div>

  </div>

</div>



<script>

$('#save_modal').modal('show');

$('#customer_id, #booking_id').select2();

$('#payment_date').datetimepicker({ timepicker:false, format:'d-m-Y' });



$(function(){



$('#frm_save').validate({

  rules:{

    booking_id : { required : true },

    payment_date : { required : true },

    payment_amount : { required : true, number: true },

    payment_mode : { required : true },

    bank_name : { required : function(){  if($('#payment_mode').val()!="Cash"){ return true; }else{ return false; }  }  },

    transaction_id : { required : function(){  if($('#payment_mode').val()!="Cash"){ return true; }else{ return false; }  }  },     

    bank_id : { required : function(){  if($('#payment_mode').val()!="Cash"){ return true; }else{ return false; }  }  },     

  },

  submitHandler:function(form){



    var booking_id = $('#booking_id').val();

    var payment_date = $('#payment_date').val();

    var payment_amount = $('#payment_amount').val();

    var payment_mode = $('#payment_mode').val();

    var bank_name = $('#bank_name').val();

    var transaction_id = $('#transaction_id').val();

    var bank_id = $('#bank_id').val();
    var branch_admin_id = $('#branch_admin_id1').val();

    //Validation for booking and payment date in login financial year
    var check_date1 = $('#payment_date').val();
    var base_url = $('#base_url').val();
    $.post(base_url+'view/load_data/finance_date_validation.php', { check_date: check_date1 }, function(data){
      if(data !== 'valid'){
        error_msg_alert("The Payment date does not match between selected Financial year.");
        return false;
      }else{
        $('#btn_save').button('loading');
        $.ajax({

          type: 'post',

          url: base_url+'controller/forex/payment/payment_save.php',

          data:{ booking_id : booking_id, payment_date : payment_date, payment_amount : payment_amount, payment_mode : payment_mode, bank_name : bank_name, transaction_id : transaction_id, bank_id : bank_id , branch_admin_id : branch_admin_id },

          success: function(result){

            $('#btn_save').button('reset');

            var msg = result.split('--');

            if(msg[0]=='error'){

              msg_alert(result);

            }
            else{

              msg_alert(result);

              reset_form('frm_save');

              $('#save_modal').modal('hide');  

              list_reflect();
            }
          }
        });
      }
    });

  }

});



});

</script>

<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>