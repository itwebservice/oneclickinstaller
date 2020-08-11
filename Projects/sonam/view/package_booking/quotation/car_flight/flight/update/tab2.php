<form id="frm_tab21">
	
	<div class="row">
		<div class="col-md-12 app_accordion">
			<input type="hidden" value="" id="tour_group_id"/>
  			<div class="panel-group main_block" id="accordion" role="tablist" aria-multiselectable="true">

  				<!-- Flight Information -->
				<div class="accordion_content main_block mg_bt_10">
					<div class="panel panel-default main_block">
						<div class="panel-heading main_block" role="tab" id="heading_<?= $count ?>">
					        <div class="Normal main_block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1" id="collapsed1">       
					        	<div class="col-md-12"><span>Flight Information</span></div>
					        </div>
					    </div>
					    <div id="collapse1" class="panel-collapse collapse in main_block" role="tabpanel" aria-labelledby="heading1">
					        <div class="panel-body">
					        	<?php include_once('plane_tbl.php') ?>
					        </div>
					    </div>
					</div>
				</div>
			</div>
		</div>
	</div>	



	<div class="row text-center mg_tp_20">

		<div class="col-xs-12">

			<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab1()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>

			&nbsp;&nbsp;

			<button class="btn btn-info btn-sm ico_right">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>

		</div>

	</div>



</form>



<script>

$('#airline_name1_1').select2();


$(function(){

	$('#frm_tab21').validate({

		rules:{

		},

		submitHandler:function(form){
 

			var table = document.getElementById("tbl_flight_quotation_dynamic_plane_update");

			  var rowCount = table.rows.length;

			  

			  for(var i=0; i<rowCount; i++)

			  {

			    var row = table.rows[i];

			     

			    if(row.cells[0].childNodes[0].checked)

			    {

			       var from_city_id1 = row.cells[2].childNodes[0].value;
			       var plane_from_location1 = row.cells[3].childNodes[0].value;   
			       var to_city_id1 = row.cells[4].childNodes[0].value; 
			       var plane_to_location1 = row.cells[5].childNodes[0].value;

			       var airline_name = row.cells[6].childNodes[0].value;  

			       var plane_class = row.cells[7].childNodes[0].value;         

			       var dapart1 = row.cells[8].childNodes[0].value;

			       var arraval1 = row.cells[9].childNodes[0].value;


			       if(row.cells[10] && row.cells[10].childNodes[0]){

			       	var plane_id = row.cells[10].childNodes[0].value;

			       }

			       else{

			       	var plane_id = "";

			       }     

					if(from_city_id1=="")

				    {

				          error_msg_alert('Enter plane from city in row'+(i+1));
						  $('.accordion_content').removeClass("indicator");
	          	  		  $('#tbl_flight_quotation_dynamic_plane_update').parent('div').closest('.accordion_content').addClass("indicator");

				          return false;

				    }

			       

			       if(plane_from_location1=="")

			       {

			          error_msg_alert('Enter plane from location in row'+(i+1));
						  $('.accordion_content').removeClass("indicator");
	          	  	  $('#tbl_flight_quotation_dynamic_plane_update').parent('div').closest('.accordion_content').addClass("indicator");

			          return false;

			       }

			       if(to_city_id1=="")

				    {

				          error_msg_alert('Enter plane To city in row'+(i+1));
						  $('.accordion_content').removeClass("indicator");
	          	  		  $('#tbl_flight_quotation_dynamic_plane_update').parent('div').closest('.accordion_content').addClass("indicator");

				          return false;

				    }




			       if(plane_to_location1=="")

			       {

			          error_msg_alert('Enter plane to location in row'+(i+1));
						  $('.accordion_content').removeClass("indicator");
	          	  		  $('#tbl_flight_quotation_dynamic_plane_update').parent('div').closest('.accordion_content').addClass("indicator");

			          return false;

			       } 

					if(dapart1=="")

					{ 

						error_msg_alert("Departure Date time is required in row:"+(i+1)); 
						  $('.accordion_content').removeClass("indicator");
	          	  		  $('#tbl_flight_quotation_dynamic_plane_update').parent('div').closest('.accordion_content').addClass("indicator");

						 return false;

					}

			       

					if(arraval1=="")

					{ 

						error_msg_alert('Arrival Date time is required in row:'+(i+1));
						  $('.accordion_content').removeClass("indicator");
	          	  		  $('#tbl_flight_quotation_dynamic_plane_update').parent('div').closest('.accordion_content').addClass("indicator"); 

						 return false;

					}



 

			    }      

			  }







		$('.accordion_content').removeClass("indicator");
		$('a[href="#tab_3"]').tab('show');		

		}

	});

});

function switch_to_tab1(){ $('a[href="#tab_1"]').tab('show'); }

</script>



