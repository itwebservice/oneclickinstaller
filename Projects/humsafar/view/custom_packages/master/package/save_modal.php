<form id="frm_package_master_save">

<div class="app_panel">  
 <!--=======Header panel======-->

<div class="app_panel_head mg_bt_20">
  <div class="container">
    <h2 class="pull-left"></h2>
    <div class="pull-right header_btn"><button><a></a></button></div>
     <div class="pull-right header_btn">
        <button>
            <a data-original-title="" title="">
                <i class="fa fa-arrow-right"></i>
            </a>
        </button>
    </div>
  </div>
</div>

<div class="container">


 <div class="app_panel_content no-pad">

        <div class="panel panel-default panel-body main_block bg_light">
          <legend>Tour Information</legend>
          <div class="bg_white main_block panel-default-inner">
            <div class="col-xs-12 no-pad mg_bt_20 mg_tp_20">
              <div class="col-md-3 col-sm-3"> 
                <select id="dest_name_s"  name="dest_name_s" title="Select Destination" class="form-control"  style="width:100%"> 
                  <option value="">*Destination</option>
                   <?php 
                   $sq_query = mysql_query("select * from destination_master where status != 'Inactive'"); 
                   while($row_dest = mysql_fetch_assoc($sq_query)){ ?>
                      <option value="<?php echo $row_dest['dest_id']; ?>"><?php echo $row_dest['dest_name']; ?></option>
                      <?php } ?>
                </select>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <select name="tour_type" id="tour_type" title="Tour Type" onchange="incl_reflect(this.id,'')">
                      <option value="">Tour Type</option>
                      <option value="Domestic">Domestic</option>
                      <option value="International">International</option>
                  </select>
              </div>
            </div> 
            <div class="col-xs-12 no-pad mg_bt_20">        
                <div class="col-md-3 col-sm-3 mg_bt_10_xs"> 
                    <input type="text" id="package_name" name="package_name"  onchange="package_name_check(this.id);fname_validate(this.id); " class="form-control"  placeholder="*Package Name" title="Package Name" />
                    <small>Note : Package Name : eg. Kerala amazing</small>
                </div>      
                <div class="col-md-3 col-sm-3 mg_bt_10_xs"> 
                    <input type="text" id="package_code" name="package_code"  class="form-control" placeholder="Package Code" title="Package Code" />
                    <small>Note : Package Code : eg. Ker001</small>
                </div>     
                <div class="col-md-3 col-sm-3 mg_bt_10_xs"> 
                    <input type="number" id="total_nights" onchange="validate_balance(this.id); calculate_days()" name="total_nights" placeholder="*Nights" title="Total Nights">
                </div>      
                <div class="col-md-3 col-sm-3 mg_bt_10_xs"> 
                    <input type="number" id="total_days"  onchange=" validate_days('total_nights' , 'total_days');" name="total_days" class="form-control"  placeholder="*Days" title="Total Days" readonly />
                </div>     
                <div class="col-md-3 col-sm-3"> 
                    <select id="status"  name="status" title="Status" class="form-control hidden">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                    </select>
               </div>    
            </div>
            <div class="col-xs-12 no-pad mg_bt_20">
                <div class="col-md-2 col-sm-3 mg_bt_10_xs">
                    <input type="text" id="adult_cost" name="adult_cost" onchange="validate_balance(this.id);" class="form-control"  placeholder="Adult Cost" title="Adult Cost" />
                </div>   
                <div class="col-md-2 col-sm-3 mg_bt_10_xs">
                    <input type="text" id="child_cost" name="child_cost" onchange="validate_balance(this.id);" class="form-control"  placeholder="Child Cost" title="Child Cost" />
                </div>
                <div class="col-md-2 col-sm-3 mg_bt_10_xs">
                    <input type="text" id="infant_cost" name="infant_cost" onchange="validate_balance(this.id);" class="form-control"  placeholder="Infant Cost" title="Infant Cost" />
                </div>
                <div class="col-md-2 col-sm-3 mg_bt_10_xs">
                    <input type="text" id="child_with" name="child_with" onchange="validate_balance(this.id);" class="form-control"  placeholder="Child with Bed Cost" title="Child with Bed Cost" />
                </div>
                <div class="col-md-2 col-sm-3 mg_bt_10_xs">
                    <input type="text" id="child_without" name="child_without" onchange="validate_balance(this.id);" class="form-control"  placeholder="Child w/o Bed Cost" title="Child w/o Bed Cost" />
                </div>
                <div class="col-md-2 col-sm-3 mg_bt_10_xs">
                    <input type="text" id="extra_bed" name="extra_bed" onchange="validate_balance(this.id);" class="form-control"  placeholder="Extra Bed Cost" title="Extra Bed Cost" />
                </div>
            </div>
          </div>
        </div>


        <div class="row">
            <div class="col-md-12" id="div_list1">
            </div>    
        </div>


        <div class="panel panel-default panel-body main_block bg_light">
          <legend>Hotel Information</legend>
           <small class="note">Note -  Pls ensure you added city wise hotel & tariff using Supplier Master</small>
          <div class="bg_white main_block panel-default-inner">
            <div class="col-xs-12 text-right mg_tp_10">
              <button class="btn btn-info btn-sm ico_left mg_bt_10" onclick="hotel_save_modal()"><i class="fa fa-plus"></i>&nbsp;&nbsp;Hotel</button>
              <button type="button" class="btn btn-info btn-sm ico_left mg_bt_10" onClick="addRow('tbl_package_hotel_master')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
              <button type="button" class="btn btn-danger btn-sm ico_left mg_bt_10" onClick="deleteRow('tbl_package_hotel_master')"><i class="fa fa-times"></i>&nbsp;&nbsp;Delete</button>
            </div> 
            <div class="col-xs-12"> 
              <div class="table-responsive">
                <table id="tbl_package_hotel_master" name="tbl_package_hotel_master" class="table table-hover pd_bt_51" style="border-bottom: 0 !important;">
                  <tr>
                      <td><input id="chk_dest1" type="checkbox" checked></td>
                      <td><input maxlength="15" value="1" type="text" name="no" placeholder="Sr. No." class="form-control" disabled /></td>
                      <td><select id="city_name" name="city_name1" onchange="hotel_name_list_load(this.id);" class="city_master_dropdown app_select2" style="width:100%" title="Select City Name">
                            <?php get_cities_dropdown(); ?>
                          </select></td>
                      <td><select id="hotel_name" name="hotel_name1" onchange="hotel_type_load(this.id);" style="width:100%" title="Select Hotel Name">
                            <option value="">*Hotel Name</option>
                          </select></td>
                      <td><input type="text" id="hotel_type" name="hotel_type1" placeholder="*Hotel Type" title="Hotel Type" readonly></td>
                      <td><input type="text" id="hotel_tota_days1" onchange="validate_balance(this.id)" name="hotel_tota_days1" placeholder="*Total Night" title="Total Night"></td></td>
                  </tr>
                </table>  
              </div>
            </div>
          </div>
        </div>
        <div class="row mg_bt_20">
        </div>
        <div class="panel panel-default panel-body main_block bg_light">
          <legend>Transport Information</legend>
          <div class="row mg_bt_20">
            <div class="col-xs-12 text-right mg_tp_10">
              <button type="button" class="btn btn-info btn-sm ico_left mg_bt_10" onClick="addRow('tbl_package_tour_transport')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
              <button type="button" class="btn btn-danger btn-sm ico_left mg_bt_10" onClick="deleteRow('tbl_package_tour_transport')"><i class="fa fa-times"></i>&nbsp;&nbsp;Delete</button>
            </div> 
              <div class="col-xs-12">
                <div class="table-responsive">
                <table id="tbl_package_tour_transport" name="tbl_package_tour_transport" class="table mg_bt_0 table-bordered mg_bt_10 pd_bt_51">             
                    <tbody>
                      <tr>
                          <td class="col-md-1"><input class="css-checkbox labelauty" id="chk_transport1" type="checkbox" checked="" autocomplete="off" data-original-title="" title="" aria-hidden="true" style="display: none;"><label for="chk_transport1"><span class="labelauty-unchecked-image"></span><span class="labelauty-checked-image"></span></label><label class="css-label" for="chk_transport1"> </label></td>
                          <td class="col-md-1"><input maxlength="15" value="1" type="text" name="username" placeholder="Sr No." class="form-control" disabled="" autocomplete="off" data-original-title="" title=""></td>
                          <td class="col-md-3"><select name="vehicle_name1" id="vehicle_name1" style="width:100%" class="form-control app_select2" onchange="get_transport_cost(this.id)">
                          <option value="">Select Vehicle</option>
                          <?php
                          $sq_query = mysql_query("select * from transport_agency_bus_master where active_flag != 'Inactive'"); 
                          while($row_dest = mysql_fetch_assoc($sq_query)){ ?>
                              <option value="<?php echo $row_dest['bus_id']; ?>"><?php echo $row_dest['bus_name']; ?></option>
                          <?php } ?></select></td>
                          <td><input type="text" name="cost1" placeholder="Cost" id="cost1" class="form-control"></td>
                      </tr>                  
                    </tbody>
                </table>
                </div>
              </div>
            </div>
        </div>
        <div class="row mg_bt_20">
            <div class="col-md-6 col-sm-6 mg_bt_10_sm_xs">
                <h3 class="editor_title">Inclusions</h3>
                <textarea class="feature_editor" id="inclusions" name="inclusions" placeholder="Inclusions" title="Inclusions" rows="4"></textarea>
            </div>
            <div class="col-md-6 col-sm-6"> 
                <h3 class="editor_title">Exclusions</h3>
                <textarea class="feature_editor" id="exclusions" name="exclusions" class="form-control"  placeholder="Exclusions" title="Exclusions" rows="4"></textarea>
            </div>
        </div>
      </div>
   </div>
    <div class="panel panel-default main_block bg_light pad_8 text-center mg_bt_0">
        <button class="btn btn-sm btn-info ico_right" id="btn_save">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
    </div>
</div>

<div id="div_modal_content"></div>

</form>

<script src="<?= BASE_URL ?>js/ajaxupload.3.5.js"></script>

<script>
$('#dest_name_s,#vehicle_name1').select2();
$('#city_name').select2({minimumInputLength: 1});
function generate_list(){

    var total_days = $("#total_days").val();
    $.post('generate_program_list.php', {total_days : total_days}, function(data){
        $('#div_list1').html(data);
    });
}

function calculate_days(){
   var total_nights = $("#total_nights").val();
   var days = parseInt(total_nights) + 1;
   $("#total_days").val(days);
   generate_list();
}

function package_name_check(package_name){
  var package_name1 = $('#'+package_name).val();

  $.post( "../package_name_check.php" , { package_name : package_name1 } , function ( data ) {
    if(data == 'This package name already exists.'){
      error_msg_alert(data);
      return false;
    }else{
      return true;
    }
  });
}

$(function(){
  $('#frm_package_master_save').validate({

    rules:{
        dest_name_s : { required: true },
        package_name : { required: true },
        total_days : { required: true, number:true },
        total_nights : { required: true, number:true },          
        day_program : {required : true },
    },
    submitHandler:function(form){

        var valid_state = table_info_validate();
        if(valid_state==false){ return false; }

        var table = document.getElementById("tbl_package_hotel_master");
        var hotel_name_arr = new Array();
        var rowCount = table.rows.length;
        
        for(var i=0; i<rowCount; i++){
            var row = table.rows[i];
            if(row.cells[0].childNodes[0].checked){
              var hotel_name = row.cells[3].childNodes[0].value;
              hotel_name_arr.push(hotel_name);     
            }
        }

        $.ajax({
          type:'post',
          url: 'package_hotel_info.php',
          data:{ hotel_name_arr : hotel_name_arr },
          success:function(result){
            var hotel_arr = JSON.parse(result);
              for(var i=0; i<hotel_arr.length; i++){
                var opt = new Option(hotel_arr[i].hotel_name1,hotel_arr[i].hotel_id);
                $("#hotel_names").append(opt);
              }
          }
        });

        $('#tab_1_head').addClass('done');
        $('#tab_2_head').addClass('active');
        $('.bk_tab').removeClass('active');
        $('#tab_2').addClass('active');
        $('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);

      return false;
    }
  });
});

function table_info_validate(){

  g_validate_status = true; 
  var validate_message = "";

  //Special attraction table
  var table = document.getElementById("dynamic_table_list");
  var rowCount = table.rows.length;

  for(var i=0; i<rowCount; i++){

      var row = table.rows[i];
      validate_dynamic_empty_fields(row.cells[0].childNodes[0]);
      validate_dynamic_empty_fields(row.cells[1].childNodes[0]);
      validate_dynamic_empty_fields(row.cells[2].childNodes[0]);

      var flag1 = validate_spattration(row.cells[0].childNodes[0].id);
      var flag2 = validate_dayprogram(row.cells[1].childNodes[0].id);
      var flag3 = validate_onstay(row.cells[2].childNodes[0].id);
      if(!flag1 || !flag2 || !flag3){
          return false;
      }
 }

  //Hotel info table
  var total_nights = $('#total_nights').val();
  var total_night = 0;
  var table = document.getElementById("tbl_package_hotel_master");
  var rowCount = table.rows.length;

  for(var i=0; i<rowCount; i++){

    var row = table.rows[i];       
    if(rowCount == 1){
      if(!row.cells[0].childNodes[0].checked){
        error_msg_alert("Atleast One Hotel is required!");
        g_validate_status = false; 
        return false;
      }
    }

    if(row.cells[0].childNodes[0].checked){

        validate_dynamic_empty_fields(row.cells[2].childNodes[0]);
        validate_dynamic_empty_fields(row.cells[3].childNodes[0]);
        validate_dynamic_empty_fields(row.cells[4].childNodes[0]);
        validate_dynamic_empty_fields(row.cells[5].childNodes[0]);

        if(row.cells[2].childNodes[0].value==""){
              validate_message += "Enter City Name in row-"+(i+1)+"<br>";
        }
        if(row.cells[3].childNodes[0].value==""){
              validate_message += "Enter Hotel Name in row-"+(i+1)+"<br>";
        }
        if(row.cells[4].childNodes[0].value==""){
              validate_message += "Enter Hotel Type in row-"+(i+1)+"<br>";
        }
        if(row.cells[5].childNodes[0].value==""){
              validate_message += "Enter Total Nights in row-"+(i+1)+"<br>";
        }
        total_night = parseFloat(total_night) + parseFloat(row.cells[5].childNodes[0].value);
      }
  }
  if(parseFloat(total_night) != parseFloat(total_nights)){
      error_msg_alert("Total Nights doesn't match!");
      g_validate_status = false; 
      return false
  }
  g_validate_status = true;

  //Transport info table
  var table = document.getElementById("tbl_package_tour_transport");
  var rowCount = table.rows.length;
  for(var i=0; i<rowCount; i++){
    var row = table.rows[i];
    if(row.cells[0].childNodes[0].checked){

        validate_dynamic_empty_fields(row.cells[2].childNodes[0]);
        validate_dynamic_empty_fields(row.cells[3].childNodes[0]);

        if(row.cells[2].childNodes[0].value==""){
            validate_message += "Enter Vehicle Name in row-"+(i+1)+"<br>";
        }
        if(row.cells[3].childNodes[0].value==""){
            validate_message += "Enter Vehicle Cost in row-"+(i+1)+"<br>";
        }
    }
  }
  if(validate_message!=""){
      $('#site_alert').vialert({ 
          type:"error",
          message:validate_message,
          delay:10000,
      });
  }
  if(g_validate_status==false){ return false; }
}

function load_images(hotel_names){
    $.ajax({
      type:'post',
      url: 'get_hotel_img.php',
      data:{hotel_name : hotel_names },
      success:function(result){

        $('#images_list').html(result);
      }
  });
}

function delete_image(image_id,hotel_name){

    var base_url = $("#base_url").val();
    $.ajax({
          type:'post',
          url: base_url+'controller/custom_packages/delete_hotel_image.php',
          data:{ image_id : image_id },
          success:function(result){
            msg_alert(result);
            load_images(hotel_name);
          }
  });
}

/**Hotel Name load start**/

function hotel_name_list_load(id)
{

  var city_id = $("#"+id).val();

  var count = id.substring(9);

  $.get( "hotel/hotel_name_load.php" , { city_id : city_id } , function ( data ) {
        $ ("#hotel_name"+count).html( data ) ;                            
  } ) ;   

}

function hotel_type_load(id)

{

  var hotel_id = $("#"+id).val();

  var count = id.substring(10);

  $.get( "hotel/hotel_type_load.php" , { hotel_id : hotel_id } , function ( data ) {

        $ ("#hotel_type"+count).val( data ) ;                            

  } ) ;

}


</script>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>

