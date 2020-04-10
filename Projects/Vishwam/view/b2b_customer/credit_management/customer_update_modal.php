<?php
include "../../../model/model.php";
$register_id = $_POST['register_id'];
$sq_credit = mysql_fetch_assoc(mysql_query("select * from b2b_creditlimit_master where entry_id='$register_id'"));
$sq_appcredit = mysql_fetch_assoc(mysql_query("select * from b2b_approved_credit where entry_id='$sq_credit[entry_id]'"));
$descr = ($sq_appcredit['approval_status'] == 'Approved') ? $sq_appcredit['description'] : $sq_credit['description']; 
?>
<div class="modal fade" id="credit_update_modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Status</h4>
      </div>
      <div class="modal-body">
        <form id="frm_customer_update">
        <input type="hidden" id="register_id" name="register_id" value="<?= $sq_credit['register_id'] ?>">
        <input type="hidden" id="entry_id" name="entry_id" value="<?= $sq_credit['entry_id'] ?>">
        <input type="hidden" id="approval_id" name="approval_id" value="<?= $sq_appcredit['approval_id'] ?>">
          <div class="row mg_bt_10">
            <div class="col-sm-3 mg_bt_10_sm_xs">
              <select name="credit_approve" id="credit_approve" title="Approval Status" onchange="change_fields_status(this.id)">
              <?php if($sq_credit['approval_status'] != ''){?>
                  <option value="<?= $sq_credit['approval_status'] ?>"><?= $sq_credit['approval_status'] ?></option>
              <?php } ?>
                  <option value="">Approval Status</option>
                  <option value="Approved">Approved</option>
                  <option value="Rejected">Rejected</option>
              </select>
            </div>
            <div class="col-sm-3 mg_bt_10_sm_xs">
              <input type="text" id="app_credit" name="app_credit" placeholder="Approved Credit" title="Approved Credit" value="<?= $sq_credit['credit_amount'] ?>" onchange="validate_balance(this.id);">
            </div>
            <div class="col-sm-6 mg_bt_10_sm_xs">
              <textarea id="description" name="description" placeholder="Description"><?= $descr ?></textarea>
            </div>
          </div>
          <div class="row text-center">
            <div class="col-md-12 mg_tp_10">
              <button class="btn btn-sm btn-success" id="btn_cupdate"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>
            </div>
          </div>
        </form> 
    </div>
  </div>
</div>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script src="<?= BASE_URL ?>js/ajaxupload.3.5.js"></script>
<script>
	$('#payment_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
  $('#credit_update_modal').modal('show');
function reflect_details(reflect_details){

	  var base_url = $('#base_url').val();
    var reflect_details1 = $('#'+reflect_details).val();
    var status = document.getElementById(reflect_details).checked;
    $.post('deposit_fields_reflect.php',{ reflect_details : reflect_details1,status:status}, function(data){
      $('#deposit_fields').html(data);
    });
}
  $(function(){
	$('#frm_customer_update').validate({
	  rules:{
      credit_approve : {required : true },
	  },
	  submitHandler:function(form){

	  	  var register_id = $('#register_id').val();
        var entry_id = $('#entry_id').val();
        var approval_id = $('#approval_id').val();
	      var credit_limit = $('#app_credit').val();
        var description = $('#description').val();
        var approve_status = $('#credit_approve').val();
        var base_url = $('#base_url').val();
        
        $("#vi_confirm_box").vi_confirm_box({
        callback: function(result){
          if(result=="yes")
          {
            $('#btn_cupdate').button('loading');
            
            $.ajax({
              type: 'post',
              url: base_url+'controller/b2b_customer/credit_limit/customer_update.php',
              data:{ entry_id:entry_id,register_id : register_id, credit_limit : credit_limit,description : description,approve_status : approve_status,approval_id:approval_id},
              success: function(result){
                msg_alert(result);
                $('#credit_update_modal').modal('hide');
                $('#btn_cupdate').button('reset');
                $('#credit_update_modal').on('hidden.bs.modal', function(){
                  customer_list_reflect();
                });
              }
            });
          }
        }
      });
	  }
	});
  });
</script>