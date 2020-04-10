<form id="frm_tab_3">

<div class="app_panel"> 


 <!--=======Header panel======-->
    <div class="app_panel_head">
      <div class="container">
        <h2 class="pull-left"></h2>
        <div class="pull-right header_btn">
          <button>
              <a>
                  <i class="fa fa-arrow-right"></i>
              </a>
          </button>
        </div>
        <div class="pull-right header_btn">
          <button type="button" onclick="back_to_tab_2()">
              <a>
                  <i class="fa fa-arrow-left"></i>
              </a>
          </button>
        </div>
      </div>          
    </div> 

  <!--=======Header panel end======-->

    <div class="">
        <div class="container">
            <h5 class="booking-section-heading main_block">Accommodation details</h5>
                    <?php 
                    $count_ht = 0;
                    $sq_hotel_entries = mysql_num_rows(mysql_query("select * from package_hotel_accomodation_master where booking_id='$booking_id'"));
                    if($sq_hotel_entries==0){
                        include_once("../booking_save/tab_3/hotel_table_row.php");
                    }
                    else{ ?>
                <div class="row" style="margin-top: 5px"> <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_package_hotel_infomration')" title="Add row"><i class="fa fa-plus"></i></button>
                </div> </div>
                <div class="row mg_tp_10"> <div class="col-xs-12"> <div class="table-responsive">
                <table id="tbl_package_hotel_infomration" class="table table-bordered table-hover pd_bt_51 table-striped no-marg" style="width: 1475px;">
                <?php
                        $sq_hotel_acc = mysql_query("select * from package_hotel_accomodation_master where booking_id='$booking_id'");
                        while($row_hotel_acc=mysql_fetch_assoc($sq_hotel_acc)){
                            $count_ht++;
                         ?>
                        <tr>
                            <td><input id="check-btn-hotel-acm-1" type="checkbox" onchange="calculate_hotel_expense()" checked disabled ></td>
                            <td><input maxlength="15" type="text" name="username"  value="<?= $count_ht ?>" placeholder="Sr. No." disabled/></td>
                            <td><select id="city_name1<?php echo $count_ht."_h" ?>" name="city_name1<?php echo $count_ht."_h" ?>" style="width:100%" title="Select City Name" onchange="hotel_name_list_load1(this.id)">
                                <?php
                                    $sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_hotel_acc[city_id]'"));
                                ?>
                                <option value="<?php echo $sq_city['city_id'] ?>"><?= $sq_city['city_name'] ?></option>
                                    <?php get_cities_dropdown(); ?>
                                  </select></td>
                            <td><select id="hotel_name1<?php echo $count_ht."_h" ?>" name="hotel_name1<?php echo $count_ht."_h" ?>" style="width:100%" title="Select Hotel Name">
                                <?php 
                                    $sq_hotel = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$row_hotel_acc[hotel_id]'"));
                                ?>
                                <option value="<?php echo $sq_hotel['hotel_id'] ?>"><?= $sq_hotel['hotel_name'] ?></option>
                                <option value="">Hotel Name</option>
                                  </select></td>
                            <td><input type="text" id="txt_hotel_from_date<?php echo $count_ht."_h" ?>" placeholder="Check-In DateTime" onchange="validate_transportDate('txt_hotel_from_date<?php echo $count_ht.'_h' ?>' ,'txt_hotel_to_date<?php echo $count_ht.'_h' ?>');" value="<?php echo date("d-m-Y H:i:s", strtotime($row_hotel_acc['from_date'])) ?>" title="Check-In DateTime"></td>
                            <td><input type="text" id="txt_hotel_to_date<?php echo $count_ht."_h" ?>" placeholder="Check-Out DateTime" onchange="validate_arrivalDate('txt_hotel_from_date<?php echo $count_ht.'_h' ?>' ,'txt_hotel_to_date<?php echo $count_ht.'_h' ?>')" value="<?php echo date("d-m-Y H:i:s", strtotime($row_hotel_acc['to_date'])) ?>" title="Check-Out DateTime"></td>
                            <td><input type="text" id="txt_room1" name="txt_room1" placeholder="Room" value="<?php echo $row_hotel_acc['rooms'] ?>" title="Room"></td>
                            <td><select name="txt_catagory1" id="txt_catagory1" title="Category" class="form-control app_select2">
                                <option value="<?= $row_hotel_acc['catagory'] ?>"><?= $row_hotel_acc['catagory'] ?></option>
                                <option value="">Category</option>
                                <option value="Deluxe">Deluxe</option>
                                <option value="Semi Deluxe">Semi Deluxe</option>
                                <option value="Super Deluxe">Super Deluxe</option>
                                <option value="Standard">Standard</option>
                                <option value="Suit">Suit</option>
                                <option value="Superior">Superior</option>
                                <option value="Premium">Premium</option>
                                <option value="Luxury">Luxury</option>
                                <option value="Super luxury">Super luxury</option>
                                <option value="Villa">Villa</option>
                                <option value="Home">Home</option>
                                <option value="PG">PG</option>
                                <option value="Hall">Hall</option>
                                <option value="Economy">Economy</option>
                                <option value="Royal suite">Royal suite</option>
                                <option value="Executive Suite">Executive Suite</option>
                                <option value="Single room">Single room</option>
                                <option value="Double room">Double room</option>
                                <option value="Triple sharing room">Triple sharing room</option>
                                <option value="King">King</option>
                                <option value="Queen">Queen</option>
                                <option value="Studio">Studio</option>
                                <option value="Apartment">Apartment</option>
                                <option value="Connecting Rooms">Connecting Rooms</option>
                                <option value="Cabana Room">Cabana Room</option>                                
                            </select></td>
                            <td><select title="Meal Plan" id="cmb_meal_plan<?= $count_ht ?>_t" name="cmb_meal_plan" title="Meal Plan">
                                    <option value="<?= $row_hotel_acc['meal_plan'] ?>"><?= $row_hotel_acc['meal_plan'] ?></option>
                                    <?php get_mealplan_dropdown(); ?>
                            </select></td>
                            <td><select name="room_type<?= $count_ht ?>_t" id="room_type" title="Room Type">
                                    <option value="<?= $row_hotel_acc['room_type'] ?>"><?= $row_hotel_acc['room_type'] ?></option>
                                    <option value="AC">AC</option>
                                    <option value="Non AC">Non AC</option>
                            </select></td>
                            <td><input type="text" id="txt_hotel_acm_confirmation_no<?= $count_ht ?>_t" name="txt_hotel_acm_confirmation_no" placeholder="Confirmation no" onchange=" validate_specialChar(this.id)" value="<?= $row_hotel_acc['confirmation_no'] ?>" title="Confirmation no" ></td>
                            <td style="display:none"><input type="text" value="<?php echo $row_hotel_acc['id'] ?>"></td>
                        </tr>
                       <?php
                       }
                   ?>
                </table>
                <input type="hidden" id="txt_generate_hotel_acc_date" name="txt_generate_hotel_acc_date" value="<?php echo $count_ht ?>"> 
                    </div>  </div> </div><?php } ?>

        <h5 class="booking-section-heading main_block">Transport details</h5>
        <?php 
        $count_tt = 0;
        $sq_trans_entries = mysql_num_rows(mysql_query("select * from package_tour_transport_master where booking_id='$booking_id'"));
        if($sq_trans_entries==0){
            include_once("../booking_save/tab_3/transport_table_row.php");
        }
        else{ ?>
        <div class="row" style="margin-top: 5px"> <div class="col-md-12 text-right">
            <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_package_transport_infomration')" title="Add Row"><i class="fa fa-plus"></i></button>
        </div> </div>
        <div class="row main_block">
            <div class="col-xs-12"> 
                <div class="table-responsive">
                    <table id="tbl_package_transport_infomration" class="table table-bordered table-hover table-striped" style="width: 100%;">
                    <?php
                        $sq_trans_acc = mysql_query("select * from package_tour_transport_master where booking_id='$booking_id'");
                        while($row_trans_acc=mysql_fetch_assoc($sq_trans_acc)){
                            $count_tt++;
                         ?>
                        <tr>
                            <td><input id="check-btn-tr-acm-1" type="checkbox" checked disabled></td>
                            <td><input maxlength="15" type="text" name="username"  value="<?= $count_tt ?>" placeholder="Sr. No." disabled/></td>
                            <td><select name="vehicle_name1<?= $count_tt ?>" id="vehicle_name1" title="Vehicle Name" style="width:100%">
                                    <?php
                                    $sq_transport = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id='$row_trans_acc[transport_bus_id]'"));
                                    ?>
                                    <option value="<?= $sq_transport['bus_id'] ?>"><?= $sq_transport['bus_name'] ?></option>
                                    <option value="">Select Vehicle</option>
                                    <?php
                                    $sq_transport_buses = mysql_query("select * from transport_agency_bus_master order by bus_name asc");
                                    while($row_transport_bus = mysql_fetch_assoc($sq_transport_buses)){
                                    ?>
                                    <option value="<?= $row_transport_bus['bus_id'] ?>"><?= $row_transport_bus['bus_name'] ?></option>
                                    <?php } ?>
                                </select></td>
                            <td><input type="text" id="txt_tsp_from_date" name="txt_tsp_from_date<?= $count_tt ?>"  onchange="validate_validDate('txt_tsp_from_date' ,'txt_tsp_to_date')" placeholder="Start Date" title="Start Date" value="<?= get_date_user($row_trans_acc['transport_from_date']) ?>" class="form-control app_datepicker"></td>
                            <td><input type="text" id="txt_tsp_to_date" onchange="validate_issueDate('txt_tsp_from_date' ,'txt_tsp_to_date')" name="txt_tsp_to_date<?= $count_tt ?>" placeholder="End Date" title="End Date" value="<?= get_date_user($row_trans_acc['transport_to_date']) ?>" class="form-control app_datepicker"></td>
                            <td style="display:none"><input type="text" value="<?php echo $row_trans_acc['entry_id'] ?>"></td>
                        </tr>
                        <script>
                         $( "#txt_tsp_from_date").datetimepicker({ timepicker:false,format: "d-m-Y"  });
                         $( "#txt_tsp_to_date").datetimepicker({ timepicker:false,format: "d-m-Y"  });
                        </script>
                        <?php } ?>
                </table>
                </div>
            </div>
        </div>
        <?php } ?>

        <h5 class="booking-section-heading main_block">Excursion details</h5>
        <?php 
        $count_et = 0;
        $sq_exc_entries = mysql_num_rows(mysql_query("select * from package_tour_excursion_master where booking_id='$booking_id'"));
        if($sq_exc_entries==0){
            include_once("../booking_save/tab_3/excursion_table_row.php");
        }
        else{ ?>
        <div class="row" style="margin-top: 5px"> <div class="col-md-12 text-right">
            <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_package_exc_infomration')" title="Add Row"><i class="fa fa-plus"></i></button>
        </div> </div>
        <div class="row main_block">
            <div class="col-xs-12"> 
                <div class="table-responsive">
                    <table id="tbl_package_exc_infomration" class="table table-bordered table-hover table-striped" style="width: 100%;">
                    <?php
                        $sq_exc_acc = mysql_query("select * from package_tour_excursion_master where booking_id='$booking_id'");
                        while($row_exc_acc=mysql_fetch_assoc($sq_exc_acc)){
                            $count_et++;
                            $sq_ex = mysql_fetch_assoc(mysql_query("select * from itinerary_paid_services where service_id='$row_exc_acc[exc_id]'"));
                         ?>
                        <tr>
                            <td><input id="check-btn-exc" type="checkbox" checked disabled></td>
                            <td><input maxlength="15" type="text" name="username"  value="<?= $count_et ?>" placeholder="Sr. No." disabled/></td>
                            <td><select id="city_name-1<?= $count_et ?>" class="form-control" name="city_name-1<?= $count_et ?>" title="City Name" style="width:100%" onchange="get_excursion_list(this.id);">
                                    <?php
                                    $sq_transport = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_exc_acc[city_id]'"));
                                    ?>
                                    <option value="<?= $sq_transport['city_id'] ?>"><?= $sq_transport['city_name'] ?></option>
                                    <option value="">*City</option>
                                    <?php
                                        $sq_city = mysql_query("select * from city_master order by city_name asc");
                                        while($row_city = mysql_fetch_assoc($sq_city)){
                                        ?>
                                        <option value="<?php echo $row_city['city_id'] ?>"><?php echo $row_city['city_name'] ?></option>
                                        <?php } ?>
                                </select>
                            </td>
                            <td><select id="excursion-1<?= $count_et ?>" class="form-control" title="Excursion Name" name="excursion-1<?= $count_et ?>" style="width:100%">
                                            <option value="<?php echo $sq_ex['service_id'] ?>"><?php echo $sq_ex['service_name'] ?></option>
                                            <option value="">*Excursion Name</option>
                                </select></td>
                            <td style="display:none"><input type="text" value="<?php echo $row_exc_acc['entry_id'] ?>"></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
        </div>
    <?php } ?>

<div class="panel panel-default main_block bg_light pad_8 text-center mg_bt_0">
    <div class="text-center">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-info ico_left" type="button" onclick="back_to_tab_2()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-sm btn-info ico_right" >Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
        </div>
    </div>
</div>
</form>

<?= end_panel() ?>
<script>
$('#transport_bus_id').select2();
function generating_hotel_acc_date(){
    var count = $("#txt_generate_hotel_acc_date").val();
    for(var i=0; i<=count; i++){
        $( "#txt_hotel_from_date"+i+"_h").datetimepicker({  format: "d-m-Y h:i:s"  });
        $( "#txt_hotel_to_date"+i+"_h").datetimepicker({  format: "d-m-Y h:i:s"  });
    }
}
generating_hotel_acc_date();

function disabled_transport_details(id){
    var id = $('#transport_agency_id').val();
    if(id!='N/A'){
       $("#transport_bus_id").prop({disabled:'', value:''});
        $('#txt_tsp_from_date').prop({disabled:'', value:''});
        $("#txt_tsp_to_date").prop({disabled:'', value:''});
        $('#txt_tsp_total_amount').prop({disabled:'', value:''});
    }
    else{
        $("#transport_bus_id").prop({disabled:'disabled',value:''});
        $('#txt_tsp_from_date').prop({disabled:'disabled', value:''});
        $("#txt_tsp_to_date").prop({disabled:'disabled', value:''});
        $('#txt_tsp_total_amount').prop({disabled:'disabled', value:''});
    }
}

/**Hotel Name load start**/
function hotel_name_list_load1(id){
  var city_id = $("#"+id).val();
  var count = id.substring(10);
  $.get( "../../booking/inc/hotel_name_load.php" , { city_id : city_id } , function ( data ) {
     $("#hotel_name1"+count).html(data);
  });
}
</script>

<script src="../js/tab_3.js"></script>