<?php 
include "../../../model/model.php";
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id']; 
$branch_status = $_POST['branch_status'];
$misc_id = $_POST['misc_id'];

$sq_visa_info = mysql_fetch_assoc(mysql_query("select * from miscellaneous_master where misc_id='$misc_id'"));
?>
<div class="modal fade" id="visa_update_modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="min-width: 90%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Miscellaneous</h4>
      </div>
      <div class="modal-body">

      	<form id="frm_visa_update" name="frm_visa_update">

      		<input type="hidden" id="misc_id_hidden" name="misc_id_hidden" value="<?= $misc_id ?>">
        
	        <div class="panel panel-default panel-body fieldset">
	        	<legend>Customer Details</legend>
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
	        			<select name="customer_id1" id="customer_id1" style="width:100%" onchange="customer_info_load('1')" disabled>
	        				<?php 
	        				$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_visa_info[customer_id]'"));
	        				if($sq_customer['type']=='Corporate'){
	        				?>
	        					<option value="<?= $sq_customer['customer_id'] ?>"><?= $sq_customer['company_name'] ?></option>
	        				<?php }  else{ ?>
	        					<option value="<?= $sq_customer['customer_id'] ?>"><?= $sq_customer['first_name'].' '.$sq_customer['last_name'] ?></option>
	        				<?php } ?>
	        				<?php get_customer_dropdown($role,$branch_admin_id,$branch_status); ?>
	        			</select>
	        		</div>
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
	                  <input type="text" id="email_id1" name="email_id1" placeholder="Email ID" title="Email ID" readonly>
	                </div>	
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
	                  <input type="text" id="mobile_no1" name="mobile_no1" placeholder="Mobile No" title="Mobile No" readonly>
	                </div>
	                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
	                  <input type="text" id="company_name1" class="hidden" name="company_name1" title="Company Name" placeholder="Company Name" title="Company Name" readonly>
	                </div>
				<script>
					customer_info_load('1');
				</script>

			</div>	  
			<div class="panel panel-default panel-body ">
	        	<legend>Service Details</legend>
		        	<div class="col-md-4 col-sm-4 col-xs-12 mg_bt_10_sm_xs">
			        		<select name="service1" id="service1" placeholder="Select Services" style="width: 100%" disabled>
										<option value="<?php echo $sq_visa_info['service']; ?>"><?php echo $sq_visa_info['service']; ?></option>
						    </select>
				    </div>
				
				    <div class="col-md-8 col-sm-6 col-xs-12 mg_bt_10_sm_xs">
				    	<input type="text" id="narration1" class="form-control" name="narration" placeholder="Narration" title="Narration" value="<?= $sq_visa_info['narration'] ?>">
				    </div>
	        	</div>

	        <div class="panel panel-default panel-body fieldset ">
	        	<legend>Passenger Details</legend>
				
				<div class="row mg_bt_10">
	                <div class="col-xs-12 text-right text_center_xs">
	                    <button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('tbl_dynamic_miscellaneous_update')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
	                </div>
	            </div>    
	            
	            <div class="row">
	                <div class="col-xs-12">
	                    <div class="table-responsive">
	                    <?php $offset = ""; ?>
	                    <table id="tbl_dynamic_miscellaneous_update" name="tbl_dynamic_miscellaneous_update" class="table table-bordered no-marg" style="min-width:1500px">
	                       <?php 
	                       $offset = "_u";
	                       $sq_entry_count = mysql_num_rows(mysql_query("select * from miscellaneous_master_entries where misc_id='$misc_id'"));
	                       if($sq_entry_count==0){
	                       		include_once('miscellaneous_member_tbl.php');	
	                       }
	                       else{
	                       		$count = 0;
	                       		$bg="";
	                       		$sq_entry = mysql_query("select * from miscellaneous_master_entries where misc_id='$misc_id'");
	                       		while($row_entry = mysql_fetch_assoc($sq_entry)){
	                       			 if($row_entry['status']=='Cancel'){
						            	$bg="danger";
						            }else
						            {
						            	$bg="FFF";
						            }
	                       			$count++;
	                       			?>
									 <tr class="<?= $bg ?>">
									    <td><input class="css-checkbox" id="chk_visa<?= $offset.$count ?>_d" type="checkbox" checked disabled><label class="css-label" for="chk_visa<?= $offset ?>"> <label></td>
									    <td><input maxlength="15" value="<?= $count?>" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
									    <td><input type="text" id="first_name<?= $offset.$count ?>_d" onchange="fname_validate(this.id)" name="first_name<?= $offset.$count ?>_d" placeholder="First Name" title="First Name" value="<?= $row_entry['first_name'] ?>" /></td>
									    <td><input type="text" id="middle_name<?= $offset.$count ?>_d" name="middle_name<?= $offset.$count ?>_d" onchange="fname_validate(this.id)" placeholder="Middle Name" title="Middle Name" value="<?= $row_entry['middle_name'] ?>" /></td>
									    <td><input type="text" id="last_name<?= $offset.$count ?>_d" name="last_name<?= $offset.$count ?>_d" onchange="fname_validate(this.id)" placeholder="Last Name" title="Last Name" value="<?= $row_entry['last_name'] ?>" /></td>
									    <td><input type="text" id="birth_date<?= $offset.$count ?>_d" name="birth_date<?= $offset.$count ?>_d" placeholder="Birth Date" title="Birth Date" class="app_datepicker" onchange="adolescence_reflect(this.id)" value="<?= get_date_user($row_entry['birth_date']) ?>" /></td>
    									<td style="width:80px"><input type="text" id="adolescence<?= $offset.$count ?>_d" name="adolescence<?= $offset.$count ?>_d" placeholder="Adolescence" title="Adolescence" readonly value="<?= $row_entry['adolescence'] ?>" /></td>
									    <td><input type="text" id="passport_id<?= $offset.$count ?>_d" name="passport_id<?= $offset.$count ?>_d" placeholder="Passport ID" title="Passport ID" value="<?= $row_entry['passport_id'] ?>" onchange="validate_passport(this.id);" style="text-transform: uppercase;"/></td>
									    <td><input type="text" id="issue_date<?= $offset ?>1" name="issue_date<?= $offset ?>1" class="app_datepicker"  placeholder="Issue Date" title="Issue Date" value="<?= get_date_user($row_entry['issue_date']) ?>" /></td>
									    <td><input type="text" id="expiry_date<?= $offset ?>1"  name="expiry_date<?= $offset ?>1" class="app_datepicker" placeholder="Expire Date" title="Expire Date" value="<?=  get_date_user($row_entry['expiry_date']) ?>"></td>
									    <td class="hidden"><input type="text" value="<?= $row_entry['entry_id'] ?>"></td>
									</tr>  
									<script>
										$("#birth_date<?= $offset.$count ?>_d, #issue_date<?= $offset ?>1, #expiry_date<?= $offset ?>1").datetimepicker({ timepicker:false, format:'d-m-Y' });

    									$('#visa_country_name<?= $offset ?>1').select2();
 
									</script>      
	                       			<?php

	                       		}

	                       }
	                       ?>
	                    </table>
	                    </div>
	                </div>
	            </div>        



	        <div class="panel panel-default panel-body fieldset mg_tp_30">
	        	<legend>Costing Details</legend>

	        	<div class="row">	        		
		        	<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
		        		<input type="text" id="visa_issue_amount1" name="visa_issue_amount1" placeholder="Amount" title="Amount" value="<?= $sq_visa_info['misc_issue_amount'] ?>" onchange="validate_balance(this.id);calculate_total_amount('1')">
		        	</div>	
		        	<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
		        		<input type="text" name="service_charge1" id="service_charge1" placeholder="Service Charge" title="Service Charge" value="<?= $sq_visa_info['service_charge'] ?>" onchange="validate_balance(this.id);calculate_total_amount('1')">
		        	</div>
		        	<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	                  <select name="taxation_type1" id="taxation_type1" title="Taxation Type">
	                    <option value="<?= $sq_visa_info['taxation_type'] ?>"><?= $sq_visa_info['taxation_type'] ?></option>
	                    <?php get_taxation_type_dropdown($setup_country_id) ?>
	                  </select>
	                </div>
		        	<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	        			<select name="taxation_id1" id="taxation_id1" title="Tax" onchange="generic_tax_reflect(this.id, 'service_tax1', 'calculate_total_amount', '1');">
	                       <?php 
	                       if($sq_visa_info['taxation_id']!="0"){

	                         $sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$sq_visa_info[taxation_id]'"));
	                         $sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));
	                         ?>
	                         <option value="<?= $sq_taxation['taxation_id'] ?>"><?= $sq_tax_type['tax_type'].'-'.$sq_taxation['tax_in_percentage'] ?></option>
	                         <?php 
	                       }
	                       ?>
	                       <?php get_taxation_dropdown(); ?>
	                    </select>
				        <input type="hidden" id="service_tax1" name="service_tax1" value="<?= $sq_visa_info['service_tax'] ?>">
	        		</div>			    
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
				        <input type="text" id="service_tax_subtotal1" name="service_tax_subtotal1" value="<?= $sq_visa_info['service_tax_subtotal'] ?>" placeholder="Tax Amount" title="Tax Amount" readonly>
	        		</div>
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
	        			<input type="text" name="visa_total_cost1" id="visa_total_cost1"  class="amount_feild_highlight text-right" placeholder="Net Total" title="Net Total" value="<?= $sq_visa_info['misc_total_cost'] ?>" readonly>
	        		</div>
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
	        			<input type="text" name="due_date1" id="due_date1"  placeholder="Due Date" title="Due Date" value="<?= get_date_user($sq_visa_info['due_date'])?>">
	        		</div>
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
	        			<input type="text" name="balance_date1" id="balance_date1" value="<?= date('d-m-Y',strtotime($sq_visa_info['created_at']))?>" placeholder="Booking Date" title="Booking Date" onchange="check_valid_date(this.id)">
	        		</div>
	        	</div>
			</div>

	        <div class="row text-center">
	        	<div class="col-md-12 mg_bt_10">
	        		<button class="btn btn-sm btn-success" id="visa_update"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>
	        	</div>
	        </div>

        </form>


      </div>  
    </div>
  </div>
</div>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script>
$('#customer_id1,#service1').select2();
$('#birth_date1, #issue_date1, #due_date1,#balance_date1').datetimepicker({ timepicker:false, format:'d-m-Y' });
$('#visa_update_modal').modal('show');

$(function(){

$('#frm_visa_update').validate({
	rules:{
			customer_id1: { required: true},
			visa_issue_amount1: { required: true, number: true },
			service_charge1 :{ required : true, number:true },
			taxation_id1 :{ required : true, number:true },
			taxation_type1 : { required : true },
			service_tax1 :{ required : true, number:true },
			service_tax_subtotal1 :{ required : true, number:true },
			visa_total_cost1 :{ required : true, number:true },
			balance_date1 : { required : true },
			 
	},
	submitHandler:function(form){

		    var misc_id = $('#misc_id_hidden').val();
		    var customer_id = $('#customer_id1').val();
			var misc_issue_amount = $('#visa_issue_amount1').val();	
			var service_charge = $('#service_charge1').val();
			var taxation_type = $('#taxation_type1').val();
			var taxation_id = $('#taxation_id1').val();
			var service_tax = $('#service_tax1').val();
			var service_tax_subtotal = $('#service_tax_subtotal1').val();
			var misc_total_cost = $('#visa_total_cost1').val();	
			var due_date1 = $('#due_date1').val();
			var balance_date1 = $('#balance_date1').val();
			var narration = $('#narration1').val();
			var service = $('#service1').val();

			var first_name_arr = new Array();
			var middle_name_arr = new Array();
			var last_name_arr = new Array();
			var birth_date_arr = new Array();
			var adolescence_arr = new Array();
			var passport_id_arr = new Array();
			var issue_date_arr = new Array();
			var expiry_date_arr = new Array();
			var entry_id_arr = new Array();

	        var table = document.getElementById("tbl_dynamic_miscellaneous_update");
	        var rowCount = table.rows.length;
	        
	        if(parseFloat(taxation_id) == "0"){ error_msg_alert("Please select Tax Percentage"); return false; }
			if(service == ''){ error_msg_alert("Enter Services in Service details!"); return false; }

	        for(var i=0; i<rowCount; i++){
	          var row = table.rows[i];
	           
	          if(row.cells[0].childNodes[0].checked)
	          {

				  var first_name = row.cells[2].childNodes[0].value;
				  var middle_name = row.cells[3].childNodes[0].value;
				  var last_name = row.cells[4].childNodes[0].value;
				  var birth_date = row.cells[5].childNodes[0].value;
				  var adolescence = row.cells[6].childNodes[0].value;
				  var passport_id = row.cells[7].childNodes[0].value;
				  var issue_date = row.cells[8].childNodes[0].value;
				  var expiry_date = row.cells[9].childNodes[0].value;
				  
				  if(row.cells[10]){
				  	var entry_id = row.cells[10].childNodes[0].value;	
				  }
				  else{
				  	var entry_id = "";
				  }

	              var msg = "";
	             
				  if(first_name==""){ msg +="First name is required in row:"+(i+1)+"<br>"; }
				
				 

	              if(msg!=""){
	                error_msg_alert(msg);
	                return false;
	              }

				  first_name_arr.push(first_name);
				  middle_name_arr.push(middle_name);
				  last_name_arr.push(last_name);
				  birth_date_arr.push(birth_date);
				  adolescence_arr.push(adolescence);
				  passport_id_arr.push(passport_id);
				  issue_date_arr.push(issue_date);
				  expiry_date_arr.push(expiry_date);
				  entry_id_arr.push(entry_id);	              

	          }      
	        }


			var base_url = $('#base_url').val();
			//Validation for booking and payment date in login financial year
			var check_date1 = $('#balance_date1').val();
			$.post(base_url+'view/load_data/finance_date_validation.php', { check_date: check_date1 }, function(data){
				if(data !== 'valid'){
					error_msg_alert("The Booking date does not match between selected Financial year.");
					return false;
				}else{
					$('#visa_update').button('loading');
					$.ajax({
						type: 'post',
						url: base_url+'controller/miscellaneous/miscellaneous_master_update.php',
						data:{ misc_id : misc_id, customer_id : customer_id, misc_issue_amount : misc_issue_amount, service_charge : service_charge, taxation_type : taxation_type, taxation_id : taxation_id, service_tax : service_tax, service_tax_subtotal : service_tax_subtotal, misc_total_cost : misc_total_cost, first_name_arr : first_name_arr, middle_name_arr : middle_name_arr, last_name_arr : last_name_arr, birth_date_arr : birth_date_arr, adolescence_arr : adolescence_arr, passport_id_arr : passport_id_arr, issue_date_arr : issue_date_arr, expiry_date_arr : expiry_date_arr, entry_id_arr : entry_id_arr, due_date1 : due_date1,balance_date1 : balance_date1,service : service, narration : narration },
						success: function(result){
							var msg = result.split('-');
							if(msg[0]=='error'){
								msg_alert(result);
							}
							else{
								msg_alert(result);
								$('#visa_update').button('reset');
								reset_form('frm_visa_update');
								$('#visa_update_modal').modal('hide');	
								visa_customer_list_reflect();
							}
							
						}
					});
				}
			});
	}
});

});
</script>