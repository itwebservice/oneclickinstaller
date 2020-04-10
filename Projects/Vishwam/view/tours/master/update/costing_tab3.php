<form id="frm_tour_update"> 

      <h3 class="editor_title">Costing Details</h3>            

      <div class="panel panel-default panel-body app_panel_style">
        <div class="row text-center">   

          <div class="col-md-3 col-sm-6 mg_bt_10_xs"> 

              <input type="text" id="txt_tour_cost" name="txt_tour_cost" onchange="validate_balance(this.id)" class="form-control" placeholder="Adult Cost" title="Adult Cost" value="<?php echo $tour_info['adult_cost']; ?>" maxlength="10"/>

          </div>

          <div class="col-md-3 col-sm-6 mg_bt_10_xs"> 

              <input type="text" id="txt_children_cost" name="txt_children_cost" onchange="validate_balance(this.id)" class="form-control"  placeholder="Children  Cost" value="<?php echo $tour_info['children_cost']; ?>" title="Children  Cost" maxlength="10" />

          </div>                        

          <div class="col-md-3 col-sm-6 mg_bt_10_xs"> 

              <input type="text" id="txt_infant_cost" name="txt_infant_cost" onchange="validate_balance(this.id)" class="form-control"  placeholder="Infant Cost"  value="<?php echo $tour_info['infant_cost']; ?>" title="Infant Cost" maxlength="10" />

          </div>  

          <div class="col-md-3 col-sm-6 mg_bt_10_xs"> 

              <input type="text" id="with_bed_cost" onchange="validate_balance(this.id)" value="<?php echo $tour_info['with_bed_cost']; ?>" name="with_bed_cost" placeholder="Extra bed cost" title="Extra bed cost">

          </div>      

      </div>      

  

      <div class="row mg_tp_10 text-center"> 

          <div class="col-md-3 col-sm-6 mg_bt_10_xs"> 

              <input type="text" id="visa_country_name" pattern="[A-Za-z]" value="<?php echo $tour_info['visa_country_name']; ?>" name="visa_country_name" placeholder="Visa Country Name" title="Visa Country Name">

          </div> 

          <div class="col-md-3 col-sm-6 mg_bt_10_xs"> 

              <input type="text" id="company_name" value="<?php echo $tour_info['company_name']; ?>" name="company_name" placeholder="Insurance Company Name" title="Insurance Company" >

          </div>

     </div>
      </div>



        <div class="row mg_tp_20">                         

            <div class="col-md-6 col-sm-6 mg_bt_10_sm_xs">
                <h3 class="editor_title">Inclusions</h3>
                <textarea class="feature_editor" id="inclusions" name="inclusions" placeholder="Inclusions" title="Inclusions" rows="4"><?php echo $tour_info['inclusions']; ?></textarea>

            </div>      

            <div class="col-md-6 col-sm-6"> 
                <h3 class="editor_title">Exclusions</h3>
                <textarea class="feature_editor" id="exclusions" name="exclusions" class="form-control"  placeholder="Exclusions" title="Exclusions" rows="4"><?php echo $tour_info['exclusions']; ?></textarea>

            </div>   

        </div>



        <div class="row mg_bt_10 mg_tp_20 text-center">

          <button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab2()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>

      &nbsp;&nbsp;

                <button class="btn btn-sm btn-success ico_left" id="btn_update" >Update<i class="fa fa-floppy-o"></i>&nbsp;&nbsp;</button>

        </div>

</form>



<script>

 

function switch_to_tab2(){ $('a[href="#tab2"]').tab('show'); }



$('#frm_tour_update').validate({

    rules:{

    },

    submitHandler:function(form){   

      var base_url = $('#base_url').val();

      var tour_id = $("#txt_tour_id").val();

      var tour_type = $("#cmb_tour_type").val();

      var tour_name = $("#txt_tour_name").val();

      var adult_cost = $("#txt_tour_cost").val();

      var children_cost = $("#txt_children_cost").val();

      var infant_cost = $("#txt_infant_cost").val();

      var with_bed_cost = $("#with_bed_cost").val();

      var visa_country_name = $("#visa_country_name").val();

      var company_name = $("#company_name").val();

      var active_flag = $('#active_flag1').val();

      var inclusions = $('#inclusions').val();

      var exclusions = $('#exclusions').val();



      //Tour group table

      var from_date = new Array();

      var to_date = new Array();

      var capacity = new Array();

      var tour_group_id = new Array();



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

           var tour_group_id1 = row.cells[5].childNodes[0].value;   



           if(from_date1=="" || to_date1=="" ){  

               error_msg_alert('From date and To Date is required'+(i+1));

               return false; 

           } 



           if(capacity1=="" ){  

                 error_msg_alert('Capacity is required'+(i+1));

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
           var latest_date = dateTwo;



           from_date.push(from_date1);

           to_date.push(to_date1);

           capacity.push(capacity1);    

           tour_group_id.push(tour_group_id1);    

        }      

      }



    //Daywise program 

        var day_program_arr = new Array();

        var special_attaraction_arr = new Array();

        var overnight_stay_arr = new Array();
        var meal_plan_arr = new Array();

        var entry_id_arr = new Array();

        var table = document.getElementById("dynamic_table_list1");

        var rowCount = table.rows.length;

        

            for(var i=0; i<rowCount; i++)

            {

                 var row = table.rows[i];

                 var special_attaraction = row.cells[1].childNodes[0].value;

                 var day_program = row.cells[2].childNodes[0].value;

                 var overnight_stay = row.cells[3].childNodes[0].value;
                 var meal_plan = row.cells[4].childNodes[0].value;

                 var entry_id = row.cells[5].childNodes[0].value;

                 if(day_program=="") {error_msg_alert("Day-wise program important"); return false;} 

                 
                 day_program_arr.push(day_program);

                 special_attaraction_arr.push(special_attaraction);

                 overnight_stay_arr.push(overnight_stay);  
                 meal_plan_arr.push(meal_plan);  

                 entry_id_arr.push(entry_id);   

            }





    //Train Information

    var train_from_location_arr = new Array();

        var train_to_location_arr = new Array();

        var train_class_arr = new Array();

        var train_arrival_date_arr = new Array();

        var train_departure_date_arr = new Array();

        var train_id_arr = new Array();



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

                   var train_arrival_date = row.cells[6].childNodes[0].value;         

                   var train_departure_date = row.cells[5].childNodes[0].value;  

     

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

                    if(train_arrival_date=="")

                    { 

                        error_msg_alert('Arraval Date time is required in row:'+(i+1)); 

                        return false;

                    }

                    if(train_departure_date=="")

                    { 

                        error_msg_alert("Daparture Date time is required in row:"+(i+1)); 

                        return false;

                    }

                   if(row.cells[7] && row.cells[7].childNodes[0]){

                    var train_id = row.cells[7].childNodes[0].value;

                   }

                   else{

                    var train_id = "";

                   }    

                   train_from_location_arr.push(train_from_location1);

                   train_to_location_arr.push(train_to_location1);

                   train_class_arr.push(train_class);

                   train_arrival_date_arr.push(train_arrival_date);

                   train_departure_date_arr.push(train_departure_date);

                   train_id_arr.push(train_id); 

                }      

              }

        //Plane Information     

    var from_city_id_arr = new Array();
    var to_city_id_arr = new Array();
    var plane_from_location_arr = new Array();
    var plane_to_location_arr = new Array();
    var airline_name_arr = new Array();
    var plane_class_arr = new Array();
    var arraval_arr = new Array();
    var dapart_arr = new Array();
    var plane_id_arr = new Array();

    var table = document.getElementById("tbl_package_tour_quotation_dynamic_plane_update");
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

           if(row.cells[10] && row.cells[10].childNodes[0]){
            var plane_id = row.cells[10].childNodes[0].value;
           }
           else{
            var plane_id = "";
           }
              
           from_city_id_arr.push(from_city_id1);
           to_city_id_arr.push(to_city_id1);
           plane_from_location_arr.push(plane_from_location1);
           plane_to_location_arr.push(plane_to_location1);
           airline_name_arr.push(airline_name);
           plane_class_arr.push(plane_class);
           arraval_arr.push(arraval1);
           dapart_arr.push(dapart1);
           plane_id_arr.push(plane_id);

            }      

          }


    //Cruise Information
    var cruise_departure_date_arr = new Array();
    var cruise_arrival_date_arr = new Array();
    var route_arr = new Array();
    var cabin_arr = new Array();
    var c_entry_id_arr = new Array();

    var table = document.getElementById("tbl_dynamic_cruise_update");
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
           if(row.cells[6]){
            var entry_id = row.cells[6].childNodes[0].value;        
           }
           else{ 
            var entry_id = '';
           }

           if(cruise_from_date=="")
           {
              error_msg_alert('Enter cruise departure datetime in row'+(i+1));
              return false;
           }

           if(cruise_to_date=="")
           {
              error_msg_alert('Enter cruise arrival datetime  in row'+(i+1));
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
           c_entry_id_arr.push(entry_id);

        }      
      }
          
    $('#btn_quotation_update').button('loading');

    

    $.post( 

             base_url+"controller/group_tour/tours/tour_master_update.php",

                 {  tour_id : tour_id,tour_type : tour_type, tour_name : tour_name, adult_cost : adult_cost, children_cost : children_cost, infant_cost : infant_cost, with_bed_cost : with_bed_cost, 'from_date[]' : from_date, 'to_date[]' : to_date, 'capacity[]' : capacity,tour_group_id : tour_group_id,visa_country_name : visa_country_name,company_name : company_name ,active_flag : active_flag,day_program_arr : day_program_arr, special_attaraction_arr : special_attaraction_arr,overnight_stay_arr : overnight_stay_arr,meal_plan_arr : meal_plan_arr, entry_id_arr : entry_id_arr,train_from_location_arr : train_from_location_arr, train_to_location_arr : train_to_location_arr, train_class_arr : train_class_arr, train_arrival_date_arr : train_arrival_date_arr, train_departure_date_arr : train_departure_date_arr,train_id_arr : train_id_arr, from_city_id_arr : from_city_id_arr, to_city_id_arr : to_city_id_arr, plane_from_location_arr : plane_from_location_arr, plane_to_location_arr : plane_to_location_arr,airline_name_arr : airline_name_arr , plane_class_arr : plane_class_arr, arraval_arr : arraval_arr, dapart_arr : dapart_arr,plane_id_arr : plane_id_arr, cruise_departure_date_arr : cruise_departure_date_arr, cruise_arrival_date_arr : cruise_arrival_date_arr, route_arr : route_arr, cabin_arr : cabin_arr, c_entry_id_arr : c_entry_id_arr, inclusions : inclusions, exclusions : exclusions  },

                 function(data) {

                  var msg = data.split('--');
                  if(msg[0]=="error"){
                      error_msg_alert(msg[1]);
                      $('#btn_update').button('reset');
                      return false;
                  }

                  else

                  {                                      

                    success_msg_alert(data);

                    $('#btn_update').button('reset');

                    $('#update_modal1').modal('hide');

                    $('#update_modal1').on('hidden.bs.modal', function(){

                      list_reflect();

                    });

                  }  

                 });

}  



});

</script>