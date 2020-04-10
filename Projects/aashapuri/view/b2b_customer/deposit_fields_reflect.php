<?php
include "../../model/model.php";
$reflect_details = $_POST['reflect_details'];
$status = $_POST['status'];

 if($status == 'true') {
?>	 
	<div class="col-sm-3 mg_bt_10_sm_xs">
		<input type="text" id="deposit" name="deposit" placeholder="Amount" title="Deposit Amount" onchange="validate_balance(this.id);">
	</div>
	<div class="col-md-3">
		<input type="text" id="payment_date" name="payment_date" placeholder="*Date" title="Date" value="<?= date('d-m-Y')?>" onchange="check_valid_date(this.id)">
	</div>
	<div class="col-md-3">
		<select name="payment_mode" id="payment_mode" title="Mode" onchange="payment_master_toggles(this.id, 'bank_name', 'transaction_id', 'bank_id')">
			<?php get_payment_mode_dropdown(); ?>
		</select>
	</div>
	<div class="col-md-3">
		<input type="text" id="bank_name" name="bank_name" class="form-control bank_suggest" placeholder="Bank Name" title="Bank Name" disabled>
	</div>
	<div class="col-md-3 mg_tp_10">
		<input type="text" id="transaction_id" name="transaction_id" onchange="validate_specialChar(this.id)" placeholder="Cheque No/ID" title="Cheque No/ID" disabled>
	</div>
	<div class="col-md-3 mg_tp_10">
		<select name="bank_id" id="bank_id" title="Select Bank" disabled>
		<?php get_bank_dropdown(); ?>
		</select>
	</div>
<?php
 }
else
{

}
 ?>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script>
	$('#payment_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
</script>