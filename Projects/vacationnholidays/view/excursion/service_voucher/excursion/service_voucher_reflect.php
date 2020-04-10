<?php
include "../../../../model/model.php";

$booking_id = $_POST['booking_id'];

$sq = mysql_fetch_assoc( mysql_query("select * from excursion_service_voucher where booking_id='$booking_id'") );
?>
<form id="frm_service_voucher">
  <div class="panel panel-default panel-body">
	<div class="row">
		<div class="col-md-4 col-sm-6 mg_bt_10 col-md-offset-4">
			<textarea id="note" name="note" placeholder="Note" onchange="validate_address(this.id)" title="Note"><?= $sq['note'] ?></textarea>
		</div>
	</div>

	<div class="row text-center mg_tp_20">
		<div class="col-md-12">
			<button class="btn btn-sm btn-info ico_left" title="Print"><i class="fa fa-print"></i>&nbsp;&nbsp;Print Voucher</button>
		</div>
	</div>
  </div>
</form>

<script>
	$(function(){
		$('#frm_service_voucher').validate({
			rules:{
			},
			submitHandler:function(form){

					var base_url = $('#base_url').val();
					var booking_id = $('#cmb_booking_id').val(); 
					var booking_type = 'excursion';
					var note = $('#note').val();

								$.ajax({
									type:'post',
									url:base_url+'controller/excursion/exc_service_voucher_save.php',
									data:{ booking_id : booking_id, note : note,booking_type:booking_type},
									success: function(message){
									var msg = message.split('--');
									if(msg[0]=="error"){
										error_msg_alert(msg[1]);
									}
									else
									{
										$('#vi_confirm_box').vi_confirm_box({
							            false_btn: false,
							            message: 'Information Saved Successfully',
							            true_btn_text:'Ok',
							            callback: function(data1){
							        	if(data1=="yes"){
										var url1 = base_url+'model/app_settings/print_html/voucher_html/excursion_voucher.php?booking_id='+booking_id+'&booking_type='+booking_type;
										loadOtherPage(url1);
									    }
									}
								});
							}
						}
					});

			}
		});
	});
</script>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>