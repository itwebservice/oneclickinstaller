<?php
$financial_year_id = $_SESSION['financial_year_id'];
$sq_finance = mysql_fetch_assoc(mysql_query("select from_date,to_date from financial_year where financial_year_id='$financial_year_id'"));
$financial_from_date = $sq_finance['from_date'];
$financial_to_date = $sq_finance['to_date'];
?>
<form id="frm_vendor_estimate_save">
<div class="modal fade" id="estimate_save_modal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document" style="width:95%; margin-top:20px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">New Purchase Costing</h4>
      </div>
      <div class="modal-body">
      	<input type="hidden" id="branch_admin_id1" name="branch_admin_id1" value="<?= $branch_admin_id ?>" >
		<input type="hidden" id="financial_from_date" name="financial_from_date" value="<?= $financial_from_date ?>" >
		<input type="hidden" id="financial_to_date" name="financial_to_date" value="<?= $financial_to_date ?>" >
      	<input type="hidden" id="emp_id" name="emp_id" value="<?= $emp_id ?>" >
			<div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_20 mg_bt_10">
			<legend>*Payment For</legend>
				<div class="row">
					<div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10">
						<select name="estimate_type" id="estimate_type" title="Purchase Type" onchange="payment_for_data_load(this.value, 'div_payment_for_content')">
							<option value="">*Purchase Type</option>
							<?php 
							$sq_estimate_type = mysql_query("select * from estimate_type_master order by estimate_type");
							while($row_estimate = mysql_fetch_assoc($sq_estimate_type)){
								?>
								<option value="<?= $row_estimate['estimate_type'] ?>"><?= $row_estimate['estimate_type'] ?></option>
								<?php
							}
							?>
						</select>
					</div>
					<div id="div_payment_for_content"></div>
				</div>

			</div>
			<div class="row text-right">  
				<div class="col-xs-12">
					<button type="button" class="btn btn-info btn-sm ico_left" onclick="estimate_section_add()"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Section</button>
				</div>
			</div>
			<input type="hidden" id="dynamic_estimate_count" name="dynamic_estimate_count" value="0">
			<input type="hidden" id="estimate_count" name="estimate_count">
			<div id="div_dynamic_estimate">
			</div>	
			<div class="row">
				<div class="col-xs-12 text-center">
					<button class="btn btn-sm btn-success" id="btn_save_estimate"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
				</div>
			</div>
	    </div>      
    </div>
  </div>
</div>
</form>
<script src="<?= BASE_URL ?>js/ajaxupload.3.5.js"></script>
<script>

$('#request_id').select2();
function estimate_section_add()
{
	var dynamic_estimate_count = $('#dynamic_estimate_count').val();
	dynamic_estimate_count = parseFloat(dynamic_estimate_count) + 1;
	$.post('estimate/dynamic_estimate_section.php', { dynamic_estimate_count : dynamic_estimate_count }, function(data){
		$('#div_dynamic_estimate').append(data);
		$('#dynamic_estimate_count').val(dynamic_estimate_count);
	});
}

function upload_invoice_pic_attch()
{	
    var dynamic_estimate_count = $('#dynamic_estimate_count').val();
	dynamic_estimate_count = parseFloat(dynamic_estimate_count) + 1
    var btnUpload=$('#id_upload_btn'+dynamic_estimate_count);

    $(btnUpload).find('span').text('Upload Invoice');

    $('#id_upload_url'+dynamic_estimate_count).val('');
    
    new AjaxUpload(btnUpload, {
      action: 'estimate/upload_invoice_proof.php',
      name: 'uploadfile',
      onSubmit: function(file, ext)
      {  
        if (! (ext && /^(jpg|png|jpeg|pdf)$/.test(ext))){ 
         error_msg_alert('Only JPG, PNG or GIF or pdf files are allowed');
         return false;
        }
        $(btnUpload).find('span').text('Uploading...');
      },
      onComplete: function(file, response){
        if(response==="error"){          
          error_msg_alert("File is not uploaded.");           
          $(btnUpload).find('span').text('Upload Again');
        }else
        { 
          $(btnUpload).find('span').text('Uploaded');
          $('#id_upload_url'+dynamic_estimate_count).val(response);
        }
      }
    });
}
estimate_section_add();

function close_estimate(id){
	$('#'+id).remove();
}

$(function(){
	$('#frm_vendor_estimate_save').validate({
		rules:{

				estimate_type: { required: function(){ if($('#vendor_type').val()!='Other Vendor'){ return true }else{ return false; } } },
				basic_cost_s : { number : true},
				non_recoverable_taxes_s : { number : true},
				service_charge_s : { number : true},
				other_charges_s : { number : true},
				taxation_type_s : { required : true},
				discount_s : { number : true},
				our_commission_s : { number : true},
				tds_s : { number : true},

		},
		submitHandler:function(form){
				var base_url = $('#base_url').val();
				var status = validate_estimate_vendor('estimate_type', 'null');
				var financial_from_date = $('#financial_from_date').val();
				var financial_to_date = $('#financial_to_date').val();
 				if(!status){ return false; }

				var estimate_type = $('#estimate_type').val();
 				var estimate_type_id = get_estimate_type_id('estimate_type');
 			
				var vendor_type_arr = new Array();
				var vendor_type_id_arr = new Array();
				var basic_cost_arr = new Array();
				var non_recoverable_taxes_arr = new Array();
				var service_charge_arr = new Array();
				var other_charges_arr = new Array();
				var taxation_id_arr = new Array();
				var taxation_type_arr = new Array();
				var service_tax_arr = new Array();
				var service_tax_subtotal_arr = new Array();
				var discount_arr = new Array();
				var our_commission_arr = new Array();
				var tds_arr = new Array();
				var net_total_arr = new Array();
				var remark_arr = new Array();
				var invoice_id_arr = new Array();
				var payment_due_date_arr = new Array();
				var invoice_url_arr = new Array();
				var purchase_date_arr = new Array();
				var branch_admin_id = $('#branch_admin_id1').val();
			  var emp_id = $('#emp_id').val();

				var msg = "";
				var counter = 0;
				$('[name="vendor_type"]').each(function(){
					counter++;
					var id = $(this).attr('id');
					var offset = id.substring(11);
					var offset1 = id.substring(14);

					var vendor_type = $('#vendor_type'+offset).val();
					var vendor_type_id = get_vendor_type_id(id, offset);
					var basic_cost = $('#basic_cost'+offset).val();
					var non_recoverable_taxes = $('#non_recoverable_taxes'+offset).val();
					var service_charge = $('#service_charge'+offset).val();
					var other_charges = $('#other_charges'+offset).val();
					var taxation_id = $('#taxation_id'+offset).val();
					var taxation_type = $('#taxation_type'+offset).val();
					var service_tax = $('#service_tax'+offset).val();
					var service_tax_subtotal = $('#service_tax_subtotal'+offset).val();
					var discount = $('#discount'+offset).val();
					var our_commission = $('#our_commission'+offset).val();
					var tds = $('#tds'+offset).val();
					var net_total = $('#net_total'+offset).val();
					var remark = $('#remark'+offset).val();
					var invoice_id = $('#invoice_id'+offset).val();
					var payment_due_date = $('#payment_due_date'+offset).val();
					var invoice_url = $('#id_upload_url'+offset1).val();
					var purchase_date = $('#purchase_date'+offset).val();
					
					if(vendor_type==""){ msg +=">Supplier type is required in vendor estimate-"+counter+"<br>"; }
					if(vendor_type_id==""){ msg +=">"+vendor_type+" is required in vendor estimate-"+counter+"<br>"; }
					if(estimate_type_id==""){ msg += ">"+estimate_type+" is required"+"<br>"; }

					if(basic_cost==""){ msg +=">Basic cost is required in vendor estimate-"+counter+"<br>"; }
					if(net_total==""){ msg +=">Net total is required in vendor estimate-"+counter+"<br>"; }
					if(parseFloat(taxation_id)=="0"){ msg +=">Tax Percentage is required in vendor estimate-"+counter+"<br>"; }
					
					//Purchase date validation
					var dateFrom = financial_from_date;
					var dateTo = financial_to_date;
					var dateCheck = purchase_date;

					var d1 = dateFrom.split("-");
					var d2 = dateTo.split("-");
					var c = dateCheck.split("-");

					var from = new Date(d1[0], parseInt(d1[1])-1, d1[2]); // -1 because months are from 0 to 11
					var to   = new Date(d2[0], parseInt(d2[1])-1, d2[2]);
					var check = new Date(c[2], parseInt(c[1])-1, c[0]);

					vendor_type_arr.push(vendor_type);
					vendor_type_id_arr.push(vendor_type_id);
					basic_cost_arr.push(basic_cost);
					non_recoverable_taxes_arr.push(non_recoverable_taxes);
					service_charge_arr.push(service_charge);
					other_charges_arr.push(other_charges);
					taxation_type_arr.push(taxation_type);
					taxation_id_arr.push(taxation_id);
					service_tax_arr.push(service_tax);
					service_tax_subtotal_arr.push(service_tax_subtotal);
					discount_arr.push(discount);
					our_commission_arr.push(our_commission);
					tds_arr.push(tds);
					net_total_arr.push(net_total);
					remark_arr.push(remark);
					invoice_id_arr.push(invoice_id);
					payment_due_date_arr.push(payment_due_date);
					invoice_url_arr.push(invoice_url);

					if(check > from && check < to){
						purchase_date_arr.push(purchase_date);
				  }
					else{
						msg += "The Purchase date does not match between selected Financial year in vendor estimate-"+counter+"<br>";
					}
				});

				if(msg!=""){
					error_msg_alert(msg);
					return false;
				}

				$('#btn_save_estimate').button('loading');
	            $.ajax({
	              type:'post',
	              url: base_url+'controller/vendor/dashboard/estimate/vendor_estimate_save.php',
	              data:{ estimate_type : estimate_type, estimate_type_id : estimate_type_id, vendor_type_arr : vendor_type_arr, vendor_type_id_arr : vendor_type_id_arr, basic_cost_arr : basic_cost_arr, non_recoverable_taxes_arr : non_recoverable_taxes_arr, service_charge_arr : service_charge_arr, other_charges_arr : other_charges_arr, taxation_type_arr : taxation_type_arr, taxation_id_arr : taxation_id_arr, service_tax_arr : service_tax_arr, service_tax_subtotal_arr : service_tax_subtotal_arr, discount_arr : discount_arr, our_commission_arr : our_commission_arr, tds_arr : tds_arr, net_total_arr : net_total_arr, remark_arr : remark_arr, invoice_id_arr : invoice_id_arr, payment_due_date_arr : payment_due_date_arr , invoice_url_arr : invoice_url_arr,purchase_date_arr : purchase_date_arr, branch_admin_id : branch_admin_id , emp_id : emp_id},
	              success:function(result){
	              	$('#btn_save_estimate').button('reset');
	                msg_alert(result);
	                $('#estimate_save_modal').modal('hide');
	                $('#estimate_save_modal').on('hidden.bs.modal', function(){
	                	vendor_dashboard_content_reflect();
	                });
	              }
	            });
 
		}
	});
});
</script>