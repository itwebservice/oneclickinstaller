<?php 
include "../../../model/model.php";
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id']; 
$branch_status = $_POST['branch_status'];
$exc_id = $_POST['exc_id'];

$sq_exc_info = mysql_fetch_assoc(mysql_query("select * from excursion_master where exc_id='$exc_id'"));
?>
<div class="modal fade" id="exc_update_modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="min-width: 90%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Excursion</h4>
      </div>
      <div class="modal-body">

      	<form id="frm_exc_update" name="frm_exc_save">

      		<input type="hidden" id="exc_id_hidden" name="exc_id_hidden" value="<?= $exc_id ?>">
        
	        <div class="panel panel-default panel-body app_panel_style feildset-panel">
	        	<legend>Customer Details</legend>

	        	<div class="row">
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
	        			<select name="customer_id1" id="customer_id1" style="width:100%" onchange="customer_info_load('1')" disabled>
	        				<?php 
	        				$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_exc_info[customer_id]'"));
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
		        </div>
				<script>
					customer_info_load('1');
				</script>

			</div>	  
				

	        <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_30">
	        	<legend>Excursion Details</legend>
				
				 <div class="row mg_bt_10">
	                <div class="col-xs-12 text-right text_center_xs">
	                    <button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('tbl_dynamic_exc_booking_update')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
	                </div>
	            </div>    
	            
	            <div class="row">
	                <div class="col-xs-12">
	                    <div class="table-responsive">
	                    <?php $offset = ""; ?>
	                    <table id="tbl_dynamic_exc_booking_update" name="tbl_dynamic_exc_booking_update" class="table table-bordered no-marg pd_bt_51">
	                       <?php 
	                       $offset = "_u";
	                       $sq_entry_count = mysql_num_rows(mysql_query("select * from excursion_master_entries where exc_id='$exc_id'"));
	                       if($sq_entry_count==0){
	                       		include_once('exc_member_tbl.php');	
	                       }
	                       else{
	                       		$count = 0;
	                       		$bg="";
	                       		$sq_entry = mysql_query("select * from excursion_master_entries where exc_id='$exc_id'");
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
									    <td><input class="css-checkbox" id="chk_exc<?= $offset.$count?>" onchange="calculate_exc_expense('tbl_dynamic_exc_booking_update','1')" type="checkbox" checked disabled><label class="css-label" for="chk_exc<?=  $count ?>"> <label></td>
									    <td><input maxlength="15" value="<?=  $count ?>" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
									    <td><input type="text" id="exc_date-<?= $offset.$count ?>" name="exc_date<?= $offset.$count ?>" placeholder="Excursion Date & Time" title="Excursion Date & Time" class="app_datepicker" value="<?php echo get_datetime_user($row_entry['exc_date']); ?>"></td>
									    <td><select id="city_name-<?= $offset.$count ?>" class="app_select2 form-control" name="city_name-<?= $offset.$count ?>" title="City Name" onchange="get_excursion_list(this.id);">
									    <?php
                       			   			$sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_entry[city_id]'")); ?>
                       			   			<option value="<?php echo $sq_city['city_id'] ?>"><?php echo $sq_city['city_name'] ?></option>
									            <option value="">*City</option>
									            <?php 
									                $sq_city = mysql_query("select * from city_master order by city_name asc");
									                while($row_city = mysql_fetch_assoc($sq_city))
									                {
									                 ?>
									                    <option value="<?php echo $row_city['city_id'] ?>"><?php echo $row_city['city_name'] ?></option>
									                 <?php   
									                }    
									            ?>
									        </select>
									    </td>
									    <td><select id="excursion-<?= $offset.$count ?>" class="app_select2 form-control" title="Excursion Name" name="excursion-<?= $offset.$count ?>" onchange="get_excursion_amount(this.id);">
									    <?php
                       			   			$sq_exc = mysql_fetch_assoc(mysql_query("select * from itinerary_paid_services where service_id='$row_entry[exc_name]'")); ?>
                       			   			<option value="<?php echo $sq_exc['service_id'] ?>"><?php echo $sq_exc['service_name'] ?></option>
									        <option value="">*Excursion Name</option>                                      
									    </select></td>
									    <td><input type="text" id="total_adult-<?= $offset.$count ?>" name="total_adult-<?= $offset.$count ?>" placeholder="Total Adult" title="Total Adult" value="<?php echo $row_entry['total_adult'] ?>" onchange="excursion_amount_calculate(this.id);calculate_exc_expense('tbl_dynamic_exc_booking_update','1'); validate_balance(this.id);"></td>
									    <td><input type="text" id="total_children-<?= $offset.$count ?>" name="total_children-<?= $offset.$count ?>" placeholder="Total Child" title="Total Child" value="<?php echo $row_entry['total_child'] ?>" onchange="excursion_amount_calculate(this.id);calculate_exc_expense('tbl_dynamic_exc_booking_update','1'); validate_balance(this.id);"></td>
									    <td><input type="text" id="adult_cost-<?= $offset.$count ?>" name="adult_cost-<?= $offset.$count ?>" placeholder="Adult Cost" title="Adult Cost" value="<?php echo $row_entry['adult_cost'] ?>" onchange="excursion_amount_calculate(this.id);calculate_exc_expense('tbl_dynamic_exc_booking_update','1'); validate_balance(this.id);"></td>
									    <td><input type="text" id="child_cost-<?= $offset.$count ?>" name="child_cost-<?= $offset.$count ?>" placeholder="Child Cost" title="Child Cost" value="<?php echo $row_entry['child_cost'] ?>" onchange="excursion_amount_calculate(this.id);calculate_exc_expense('tbl_dynamic_exc_booking_update','1'); validate_balance(this.id);"> </td>
									    <td><input type="text" id="total_amount-<?= $offset.$count ?>" name="total_amount-<?= $offset.$count ?>" placeholder="Total Amount" title="Excursion Amount" value="<?php echo $row_entry['total_cost'] ?>" onchange="validate_balance(this.id)"></td>
									    <td><input type="hidden" value="<?= $row_entry['entry_id'] ?>"></td>
									</tr>  
									<script>
										$("#exc_date-<?= $offset.$count?>").datetimepicker({ format:'d-m-Y H:i' });
										$("#city_name-<?= $offset.$count?>").select2();
									</script>      
	                       			<?php

	                       		}

	                       }
	                       ?>
	                    </table>
	                    </div>
	                </div>
	            </div>        

	        </div>

	        <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_30">
	        	<legend>Costing Details</legend>

	        	<div class="row">	        		
		        	<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
		        		<input type="text" id="exc_issue_amount1" name="exc_issue_amount1" placeholder="Amount" title="Amount" value="<?= $sq_exc_info['exc_issue_amount'] ?>" onchange="calculate_total_amount('1');validate_balance(this.id)">
		        	</div>	
		        	<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
		        		<input type="text" name="service_charge1" id="service_charge1" placeholder="Service Charge" title="Service Charge" value="<?= $sq_exc_info['service_charge'] ?>" onchange="calculate_total_amount('1');validate_balance(this.id)">
		        	</div>
		        	<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	                  <select name="taxation_type1" id="taxation_type1" title="Taxation Type">
	                    <option value="<?= $sq_exc_info['taxation_type'] ?>"><?= $sq_exc_info['taxation_type'] ?></option>
	                    <?php get_taxation_type_dropdown($setup_country_id) ?>
	                  </select>
	                </div>
		        	<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	        			<select name="taxation_id1" id="taxation_id1" title="Tax" onchange="generic_tax_reflect(this.id, 'service_tax1', 'calculate_total_amount', '1');">
	                       <?php 
	                       if($sq_exc_info['taxation_id']!='0'){
	                        
	                         $sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$sq_exc_info[taxation_id]'"));
	                         $sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));
	                         ?>
	                     <option value="<?= $sq_taxation['taxation_id'] ?>"><?= $sq_tax_type['tax_type'].'-'.$sq_taxation['tax_in_percentage'] ?></option> 
	                       <?php } ?>
	                       <?php get_taxation_dropdown(); ?>
	                    </select>
				        <input type="hidden" id="service_tax1" name="service_tax1" value="<?= $sq_exc_info['service_tax'] ?>">
	        		</div>			    
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
				        <input type="text" id="service_tax_subtotal1" name="service_tax_subtotal1" value="<?= $sq_exc_info['service_tax_subtotal'] ?>" placeholder="Tax Amount" title="Tax Amount" readonly>
	        		</div>
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
	        			<input type="text" name="exc_total_cost1" id="exc_total_cost1"  class="amount_feild_highlight text-right" placeholder="Net Total" title="Net Total" value="<?= $sq_exc_info['exc_total_cost'] ?>" readonly>
	        		</div>
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
	        			<input type="text" name="due_date1" id="due_date1" id="due_date" placeholder="Due Date" title="Due Date" value="<?= get_date_user($sq_exc_info['due_date'])?>">
	        		</div>
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
	        			<input type="text" name="balance_date1" id="balance_date1" value="<?= get_date_user($sq_exc_info['created_at'])?>" placeholder="Booking Date" title="Booking Date" onchange="check_valid_date(this.id)">
	        		</div>
	        	</div>
			</div>	  
				




	        <div class="row text-center">
	        	<div class="col-md-12">
	        		<button class="btn btn-sm btn-success" id="exc_update"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>
	        	</div>
	        </div>

        </form>


      </div>  
    </div>
  </div>
</div>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script>
$('#customer_id1').select2();
$('#birth_date1, #issue_date1, #due_date1,#balance_date1').datetimepicker({ timepicker:false, format:'d-m-Y' });
$('#exc_update_modal').modal('show');

$(function(){

$('#frm_exc_update').validate({
	rules:{
			customer_id1: { required: true},
			exc_issue_amount1: { required: true, number: true },
			service_charge1 :{ required : true, number:true },
			taxation_id1 :{ required : true, number:true },
			taxation_type1 : { required : true },
			service_tax1 :{ required : true, number:true },
			service_tax_subtotal1 :{ required : true, number:true },
			exc_total_cost1 :{ required : true, number:true },
			balance_date1 : { required : true },
			 
	},
	submitHandler:function(form){

		    var exc_id = $('#exc_id_hidden').val();
		    var customer_id = $('#customer_id1').val();
			var exc_issue_amount = $('#exc_issue_amount1').val();	
			var service_charge = $('#service_charge1').val();
			var taxation_type = $('#taxation_type1').val();
			var taxation_id = $('#taxation_id1').val();
			var service_tax = $('#service_tax1').val();
			var service_tax_subtotal = $('#service_tax_subtotal1').val();
			var exc_total_cost = $('#exc_total_cost1').val();	
			var due_date1 = $('#due_date1').val();
			var balance_date1 = $('#balance_date1').val();

			if(parseFloat(taxation_id) == "0"){ error_msg_alert("Please select Tax Percentage"); return false; }
			
			var exc_date_arr = new Array();
			var city_id_arr = new Array();
			var exc_name_arr = new Array();
			var total_adult_arr = new Array();
			var total_child_arr = new Array();
			var adult_cost_arr = new Array();
			var child_cost_arr = new Array();
			var total_amt_arr = new Array();
			var entry_id_arr = new Array();


	        var table = document.getElementById("tbl_dynamic_exc_booking_update");
	        var rowCount = table.rows.length;
	        
	        for(var i=0; i<rowCount; i++)
	        {
	          var row = table.rows[i]; 
	          if(row.cells[0].childNodes[0].checked)
	          {
				  var exc_date = row.cells[2].childNodes[0].value;
				  var city_id = row.cells[3].childNodes[0].value;
				  var exc_name = row.cells[4].childNodes[0].value;
				  var total_adult = row.cells[5].childNodes[0].value;
				  var total_child = row.cells[6].childNodes[0].value;
				  var adult_cost = row.cells[7].childNodes[0].value;
				  var child_cost = row.cells[8].childNodes[0].value;
				  var total_amt = row.cells[9].childNodes[0].value;
				  
				  if(row.cells[10]){
				  	var entry_id = row.cells[10].childNodes[0].value;	
				  }
				  else{
				  	var entry_id = "";
				  	alert(entry_id);
				  }
	              var msg = "";

				  if(exc_date==""){ msg +="Excursion Date is required in row:"+(i+1)+"<br>"; }
				  if(city_id==""){ msg +="City name is required in row:"+(i+1)+"<br>"; }
				  if(exc_name==""){ msg +="Excursion Name is required in row:"+(i+1)+"<br>"; }
				  if(total_adult==""){ msg +="Total Adult is required in row:"+(i+1)+"<br>"; }
				  if(total_child==""){ msg +="Total Children is required in row:"+(i+1)+"<br>"; }

	              if(msg!=""){
	                error_msg_alert(msg);
	                return false;
	              }
				  exc_date_arr.push(exc_date);
				  city_id_arr.push(city_id);
				  exc_name_arr.push(exc_name);
				  total_adult_arr.push(total_adult);
				  total_child_arr.push(total_child);
				  adult_cost_arr.push(adult_cost);
				  child_cost_arr.push(child_cost);
				  total_amt_arr.push(total_amt); 
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
						$('#exc_update').button('loading');
						$.ajax({
							type: 'post',
							url: base_url+'controller/excursion/exc_master_update.php',
							data:{ exc_id : exc_id, customer_id : customer_id, exc_issue_amount : exc_issue_amount, service_charge : service_charge, taxation_type : taxation_type, taxation_id : taxation_id, service_tax : service_tax, service_tax_subtotal : service_tax_subtotal, exc_total_cost : exc_total_cost,due_date1 : due_date1,balance_date : balance_date1,exc_date_arr : exc_date_arr,city_id_arr : city_id_arr,exc_name_arr : exc_name_arr, total_adult_arr : total_adult_arr,total_child_arr : total_child_arr,adult_cost_arr : adult_cost_arr,child_cost_arr : child_cost_arr,total_amt_arr : total_amt_arr, entry_id_arr : entry_id_arr},
							success: function(result){
								var msg = result.split('-');
								if(msg[0]=='error'){
									msg_alert(result);
								}
								else{
									msg_alert(result);
									$('#exc_update').button('reset');
									reset_form('frm_exc_update');
									$('#exc_update_modal').modal('hide');	
									exc_customer_list_reflect();
								}
								
							}
						});
					}
			});
	}
});

});
</script>