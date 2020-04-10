// jQuery.validator.addMethod("lettersonly", function(value, element) {

//   return this.optional(element) || /^[a-z]+$/i.test(value);

// }, "Letters only please"); 


$('#frm_tour_master_save').validate({

	rules:{

		txt_tour_cost : { required: true, number:true },

        txt_children_cost : { required: true, number:true },

        txt_infant_cost : { required: true, number:true },

        with_bed_cost : { required: true,  number:true   },

        inclusions : { required : true},

        exclusions : { required : true},

	},

	submitHandler:function(form){	

    var base_url = $('#base_url').val();

    var tour_type = $("#cmb_tour_type").val();

    var tour_name = $("#txt_tour_name").val();

    var adult_cost =  $("#txt_tour_cost").val();

    var children_cost = $("#txt_children_cost").val();

    var infant_cost = $("#txt_infant_cost").val();

    var with_bed_cost = $("#with_bed_cost").val();

    var visa_country_name = $("#visa_country_name1").val();

    var company_name = $("#company_name1").val();

    var active_flag = $("#active_flag").val();  

	var iframe = document.getElementById("inclusions-wysiwyg-iframe");
	var inclusions = iframe.contentWindow.document.body.innerHTML;
	var iframe1 = document.getElementById("exclusions-wysiwyg-iframe");
	var exclusions = iframe1.contentWindow.document.body.innerHTML;
	var pdf_url = $("#photo_upload_url_i").val();
	
    //Tour group table

	var from_date = new Array();

	var to_date = new Array();

	var capacity = new Array();



	var table = document.getElementById("tbl_dynamic_tour_group");

	var rowCount = table.rows.length;

	var latest_date="";



	for(var i=0; i<rowCount; i++)

	{

	  var row = table.rows[i];

	   

	  if(row.cells[0].childNodes[0].checked)

	  {

	     var from_date1 = row.cells[2].childNodes[0].value;         

	     var to_date1 = row.cells[3].childNodes[0].value;         

	     var capacity1 = row.cells[4].childNodes[0].value;   



	     if(from_date1=="" || to_date1=="" ){  

	         error_msg_alert('From date and To Date is required'+(i+1));

	         return false; 

	      }



	      if(capacity1=="" ){  

	         error_msg_alert('Seating Capacity is required'+(i+1));

	         return false; 

	      }



	     var get_from = from_date1.split('-');

	     var day=get_from[0];

	     var month=get_from[1];

	     var year=get_from[2];

	     var dateOne = new Date(year, month, day);      



	     var get_to = to_date1.split('-');

	     var day=get_to[0];

	     var month=get_to[1];

	     var year=get_to[2];

	     var dateTwo = new Date(year, month, day);





	     if(dateOne>=dateTwo)

	     {

	        error_msg_alert('From date is greater/equal to date in row'+(i+1));

	        return false;

	     }

	 

	     var latest_date = dateTwo;



	     from_date.push(from_date1);

	     to_date.push(to_date1);

	     capacity.push(capacity1);    

	  }      

	} 



    //Daywise program 

    var day_program_arr = new Array();

    var special_attaraction_arr = new Array();

    var overnight_stay_arr = new Array();
    var meal_plan_arr = new Array();

    var table = document.getElementById("dynamic_table_list_group");

    var rowCount = table.rows.length;

        for(var i=0; i<rowCount; i++)

        {

             var row = table.rows[i];

             var special_attaraction = row.cells[1].childNodes[0].value;

             var day_program = row.cells[2].childNodes[0].value;

             var overnight_stay = row.cells[3].childNodes[0].value;
						 var meal_plan = row.cells[4].childNodes[0].value;
						 
						 if(day_program=="") {error_msg_alert("Day-wise program important"); return false;}
             day_program_arr.push(day_program);

             special_attaraction_arr.push(special_attaraction);

             overnight_stay_arr.push(overnight_stay);                 
             meal_plan_arr.push(meal_plan);
        }




    //Train Information

	var train_from_location_arr = new Array();

	var train_to_location_arr = new Array();

	var train_class_arr = new Array();

	var train_arrival_date_arr = new Array();

	var train_departure_date_arr = new Array();





	var table = document.getElementById("tbl_package_tour_quotation_dynamic_train");

	  var rowCount = table.rows.length;

	  

	  for(var i=0; i<rowCount; i++)

	  {

	    var row = table.rows[i];

	     

	    if(row.cells[0].childNodes[0].checked)

	    {

	       var train_from_location1 = row.cells[2].childNodes[0].value; 
	       
	       var train_to_location1 = row.cells[3].childNodes[0].value;         

		   var train_class = row.cells[4].childNodes[0].value;  

		   var train_departure_date = row.cells[5].childNodes[0].value;         

		   var train_arrival_date = row.cells[6].childNodes[0].value;         

		          



	       

	       if(train_from_location1=="")

	       {

	          error_msg_alert('Enter train from location in row'+(i+1));

	          return false;

	       }



	       if(train_to_location1=="")

	       {

	          error_msg_alert('Enter train to location in row'+(i+1));

	          return false;

	       }

	      

	   

	       train_from_location_arr.push(train_from_location1);

	       train_to_location_arr.push(train_to_location1);

		   train_class_arr.push(train_class);

		   train_arrival_date_arr.push(train_arrival_date);

		   train_departure_date_arr.push(train_departure_date);



	    }      

	  }



	//Plane Information  
	var from_city_id_arr = new Array();
	var plane_from_location_arr = new Array();
	var to_city_id_arr = new Array();
	var plane_to_location_arr = new Array();

	var airline_name_arr = new Array();

	var plane_class_arr = new Array();

	var arraval_arr = new Array();

	var dapart_arr = new Array();



	var table = document.getElementById("tbl_package_tour_quotation_dynamic_plane");

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

	        if(from_city_id1=="")

		    {

		          error_msg_alert('Enter plane from city in row'+(i+1));

		          return false;

		    }

	       if(plane_from_location1=="")

	       {

	          error_msg_alert('Enter plane from location in row'+(i+1));

	          return false;

	       }

	       if(to_city_id1=="")

		    {

		          error_msg_alert('Enter plane To city in row'+(i+1));

		          return false;

		    }



	       if(plane_to_location1=="")

	       {

	          error_msg_alert('Enter plane to location in row'+(i+1));

	          return false;

	       }

	       if(airline_name=="")

			{ 

				error_msg_alert('Airline Name is required in row:'+(i+1)); 

				return false;

			}

	       if(plane_class=="")

	       	{ 

	       		error_msg_alert("Class is required in row:"+(i+1)); 

	       		 return false;

	   		}



			if(arraval1=="")

			{ 

				error_msg_alert('Arraval Date time is required in row:'+(i+1)); 

				return false;

			}

			if(dapart1=="")

			{ 

				error_msg_alert("Daparture Date time is required in row:"+(i+1)); 

				return false;

			}


		   from_city_id_arr.push(from_city_id1);
		   to_city_id_arr.push(to_city_id1);

	       plane_from_location_arr.push(plane_from_location1);

	       plane_to_location_arr.push(plane_to_location1);

	       airline_name_arr.push(airline_name);

	       plane_class_arr.push(plane_class);

	       arraval_arr.push(arraval1);

	       dapart_arr.push(dapart1);



	    }      

	  }

	//Cruise Information
	var cruise_departure_date_arr = new Array();
	var cruise_arrival_date_arr = new Array();
	var route_arr = new Array();
	var cabin_arr = new Array();

	var table = document.getElementById("tbl_dynamic_cruise");
	var rowCount = table.rows.length;

	  for(var i=0; i<rowCount; i++)
	  {
	    var row = table.rows[i];	 
	    if(row.cells[0].childNodes[0].checked)
	    {
	       var cruise_from_date = row.cells[2].childNodes[0].value;    
	       var cruise_to_date = row.cells[3].childNodes[0].value;    
	       var route = row.cells[4].childNodes[0].value;    
	       var cabin = row.cells[5].childNodes[0].value;        
		         	     
	       if(cruise_from_date=="")
	       {
	          error_msg_alert('Enter cruise departure datetime in row'+(i+1));
	          return false;
	       }

	       if(cruise_to_date=="")
	       {
	          error_msg_alert('Enter cruise departure datetime  in row'+(i+1));
	          return false;
	       }
	       if(route=="")
	       {
	          error_msg_alert('Enter route in row'+(i+1));
	          return false;
	       }
	       if(cabin=="")
	       {
	          error_msg_alert('Enter cabin in row'+(i+1));
	          return false;
	       }	      	 
	       cruise_departure_date_arr.push(cruise_from_date);
		   cruise_arrival_date_arr.push(cruise_to_date);
		   route_arr.push(route);
		   cabin_arr.push(cabin);

	    }      
	  }

    $('#btn_quotation_save').button('loading');
    $.ajax({
			type:'post',
			url:  base_url+'controller/group_tour/tours/tour_master_save.php',              
            data:   { tour_type : tour_type, tour_name : tour_name, adult_cost : adult_cost, children_cost : children_cost, infant_cost : infant_cost, with_bed_cost : with_bed_cost, 'from_date[]' : from_date, 'to_date[]' : to_date, 'capacity[]' : capacity,visa_country_name : visa_country_name,company_name : company_name ,active_flag : active_flag,day_program_arr : day_program_arr, special_attaraction_arr : special_attaraction_arr,overnight_stay_arr : overnight_stay_arr,meal_plan_arr : meal_plan_arr,train_from_location_arr : train_from_location_arr, train_to_location_arr : train_to_location_arr, train_class_arr : train_class_arr, train_arrival_date_arr : train_arrival_date_arr, train_departure_date_arr : train_departure_date_arr, from_city_id_arr : from_city_id_arr, to_city_id_arr : to_city_id_arr, plane_from_location_arr : plane_from_location_arr, plane_to_location_arr : plane_to_location_arr,airline_name_arr : airline_name_arr , plane_class_arr : plane_class_arr, arraval_arr : arraval_arr, dapart_arr : dapart_arr,cruise_departure_date_arr : cruise_departure_date_arr,cruise_arrival_date_arr : cruise_arrival_date_arr,route_arr : route_arr,cabin_arr : cabin_arr, inclusions : inclusions, exclusions : exclusions,pdf_url : pdf_url },
              success: function(data){

								var msg = data.split('--');
                if(msg[0]=="error"){
										error_msg_alert(msg[1]);
										$('#btn_quotation_save').button('reset');
										return false;
								}
                else
                {
                  msg_alert(data);
                  $('#save_modal1').modal('hide');
                  $('#save_modal1').on('hidden.bs.modal',
                  function(){
                   list_reflect();
                  });
                }
              }
	});

}  



});