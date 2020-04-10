<div class="row">
    <div class="col-xs-12 text-right mg_bt_20_sm_xs">
        <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_package_tour_quotation_dynamic_hotel_update')"><i class="fa fa-plus"></i></button>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
        <table id="tbl_package_tour_quotation_dynamic_hotel_update" name="tbl_package_tour_quotation_dynamic_hotel_update" class="table table-bordered pd_bt_51">
            <?php 
            $sq_hotel_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_hotel_entries where quotation_id='$quotation_id'"));
            if($sq_hotel_count==0){
                $sq_package = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id = '$package_id'"));
                $package_name = $sq_package['package_name'];
                ?>
                <tr>
                    <td><input class="css-checkbox" id="chk_hotel1" type="checkbox" checked><label class="css-label" for="chk_hotel1"> <label></td>
                    <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
                    <td><select id="city_name1" name="city_name1" onchange="hotel_name_list_load(this.id);" class="city_master_dropdown app_select2" style="width:100%" title="Select City Name">
                            <?php get_cities_dropdown(); ?>
                          </select></td>
                    <td><select id="hotel_name1" name="hotel_name1" onchange="hotel_type_load(this.id);get_hotel_cost(this.id);" style="width:160px" title="Select Hotel Name">
                        <option value="">Hotel Name</option>
                      </select></td>
                    <td><select name="room_cat1" id="room_cat1" style="width:145px;" onchange="get_hotel_cost(this.id);" title="Room Category" class="form-control app_select2"><?php get_room_category_dropdown(); ?></select></td>
                    <td><input type="text" id="hotel_type1" name="hotel_type1" placeholder="Hotel Type" title="Hotel Type" readonly></td>
                    <td><input type="text" id="hotel_stay_days1" name="hotel_stay_days1" onchange="validate_balance(this.id);" placeholder="Total Nights" title="Total Nights"></td>
                    <td><input type="text" id="no_of_rooms1" title="Total Rooms" onchange="validate_balance(this.id);" name="no_of_rooms1" placeholder="Total Rooms"></td>
                    <td><input type="text" id="extra_bed1" name="extra_bed1" onchange="validate_balance(this.id);" title="Extra Bed"  ></td>
                    <td class="hidden"><input type="text" id="package_name1" name="package_name1" placeholder="Package Name" value="<?= $package_name ?>" title="Package Name" style="display: none" readonly></td>                       
                    <td class="hidden"><input type="text" id="hotel_cost1" name="hotel_cost1" placeholder="Hotel Cost" title="Hotel Cost" style="display: none"></td> 
                    <td class="hidden"><input type="text" id="package_id1" name="package_id1" placeholder="Package ID" title="Package ID" style="display:none;"></td> 
                    <td class="hidden"><input type="text" id="extra_bed_cost1" name="extra_bed_cost1" placeholder="Extra bed cost" title="Extra bed cost"  style="display: none"></td> 
                    <td class="hidden"><input type="text"/></td>    
                </tr>
                <?php
            }
            else{
                $count = 0;             
                $sq_q_hotel = mysql_query("select * from package_tour_quotation_hotel_entries where quotation_id='$quotation_id' and package_id = '$sq_quotation[package_id]'");
                while($row_q_hotel = mysql_fetch_assoc($sq_q_hotel)){
                    $count++;
                    $sq_package = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id = '$row_q_hotel[package_id]'"));
                    $package_id = $sq_package['package_name'];
                    $sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_q_hotel[city_name]'"));
                    $sq_hotel = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$row_q_hotel[hotel_name]'"));
                    
                    ?>
                        <tr>
                            <td><input class="css-checkbox" id="chk_hotel<?= $count ?>_1" type="checkbox" checked><label class="css-label" for="chk_hotel1"> <label></td>
                            <td><input maxlength="15" value="<?= $count ?>" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
                            <td><select id="city_name1<?= $count ?>" name="city_name1<?= $count ?>" onchange="hotel_name_list_load(this.id);" class="
                                city_master_dropdown" style="width:100%" title="Select City Name">

                                <option value="<?php echo $sq_city['city_id']; ?>"><?php echo $sq_city['city_name']; ?></option>
                                <?php get_cities_dropdown(); ?>
                                </select></td>
                            <td><select id="hotel_name1<?= $count ?>" name="hotel_name1<?= $count ?>" onchange="hotel_type_load(this.id);get_hotel_cost(this.id);" style="width:160px" title="Select Hotel Name">
                                <option value="<?php echo $sq_hotel['hotel_id']; ?>"><?php echo $sq_hotel['hotel_name']; ?></option>
                                <option value="">Hotel Name</option>
                              </select></td>
                            <td><select name="room_cat1<?= $count ?>" id="room_cat1<?= $count ?>" style="width:145px;" onchange="get_hotel_cost(this.id);" title="Room Category" class="form-control app_select2">
                                <option value="<?php echo $row_q_hotel['room_category']; ?>"><?php echo $row_q_hotel['room_category']; ?></option>
                                <?php get_room_category_dropdown(); ?></select></td>
                            <td><input type="text" id="hotel_type1<?= $count ?>" name="hotel_type1<?= $count ?>" placeholder="Hotel Type" value="<?= $row_q_hotel['hotel_type'] ?>" title="Hotel Type" readonly></td>
                            <td><input type="text" id="hotel_stay_days1<?= $count ?>" name="hotel_stay_days1<?= $count ?>" placeholder="*Total Nights"  value="<?= $row_q_hotel['total_days'] ?>" onchange="validate_balance(this.id);" title="Total Nights"></td>
                            <td><input type="text" id="no_of_rooms1<?= $count ?>" value="<?= $row_q_hotel['total_rooms'] ?>" title="No.Of Rooms" name="no_of_rooms1<?= $count ?>" onchange="validate_balance(this.id);" placeholder="No.Of Rooms"></td>
                            <td><input type="text" id="extra_bed1<?= $count ?>" onchange="validate_balance(this.id);" name="extra_bed1<?= $count ?>" title="Extra Bed"  value="<?= $row_q_hotel['extra_bed'] ?>"></td>
                            <td class="hidden"><input type="text" id="package_name1<?= $count ?>" name="package_name1<?= $count ?>" placeholder="Package Name" title="Package Name" value="<?= $package_id ?>" style="display: none" readonly></td>   
                            <td class="hidden"><input type="text" id="hotel_cost1<?= $count ?>" name="hotel_cost1<?= $count ?>"  value="<?= $row_q_hotel['hotel_cost'] ?>" onchange="validate_balance(this.id);" placeholder="Hotel Cost" style="display: none" title="Hotel Cost"></td> 
                            <td class="hidden"><input type="text" id="package_id1<?= $count ?>" name="package_id1<?= $count ?>" placeholder="Package ID" title="Package ID"  value="<?= $row_q_hotel['package_id'] ?>" style="display: none"></td> 
                            <td class="hidden"><input type="text" id="extra_bed_cost1<?= $count ?>" onchange="validate_balance(this.id);" style="display: none" name="extra_bed_cost1<?= $count ?>" placeholder="Extra bed cost" title="Extra bed cost"  value="<?= $row_q_hotel['extra_bed_cost'] ?>"></td> 
                            <td class="hidden"><input type="text" value="<?= $row_q_hotel['id'] ?>"></td>    
                        </tr>
                    <?php
                }
            }
            ?>
            
        </table>
        </div>
    </div>
</div> 