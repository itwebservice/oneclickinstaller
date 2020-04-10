<div class="app_panel_content Filter-panel">
	<div class="row">
		<div class="col-md-3 col-sm-6">
			<select style="width:100%" id="sale_type" name="sale_type" class="form-control" title="Select Sale" onchange="tour_expense_save_reflect();"> 
		    	<option value="Visa">Visa</option>     
		    	<option value="Flight Ticket">Flight Ticket</option>
		    	<option value="Train Ticket">Train Ticket</option>
		    	<option value="Hotel">Hotel</option>     
		    	<option value="Bus">Bus</option>
		    	<option value="Car Rental">Car Rental</option>
		    	<option value="Passport">Passport</option>
		    	<option value="Forex">Forex</option>
		    	<option value="Excursion">Excursion</option>
		    	<option value="Miscellaneous">Miscellaneous</option>		    	
		    </select>
		</div>
		<div class="col-md-9 col-sm-12 text-right">
			<button class="btn btn-excel btn-sm mg_bt_10_sm_xs" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>
		</div>
	</div>

</div>
<div id="div_other_tour_reflect" class="main_block mg_tp_10"></div>

<script>
$('#sale_type').select2();
	function excel_report(){
		var sale_type = $('#sale_type').val();
		var base_url = $('#base_url').val();
		if(sale_type==""){
			error_msg_alert("Select Sale Type");
			return false;
		}
		window.location = base_url+'view/reports/business_reports/report_reflect/revenue_expenses/other_sale/excel_report.php?sale_type='+sale_type;
	}

	function tour_expense_save_reflect(){
		var sale_type = $('#sale_type').val();
		var base_url = $('#base_url').val();

		if(sale_type==""){
			error_msg_alert("Select Sale");
			return false;
		}

		$.post(base_url+'view/reports/business_reports/report_reflect/revenue_expenses/other_sale/tour_expense_save_reflect.php', { sale_type : sale_type }, function(data){
			$('#div_other_tour_reflect').html(data);
		});
	}
	tour_expense_save_reflect('Visa');
</script>

