<div class="row mg_bt_10">
    <div class="col-md-12 text-right text_center_xs">
        <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_group_tour_quotation_dynamic_plane_update')"><i class="fa fa-plus"></i></button>
    </div>
</div>

<div class="row mg_bt_10">
    <div class="col-md-12">
        <div class="table-responsive">
        <table id="tbl_group_tour_quotation_dynamic_plane_update" name="tbl_group_tour_quotation_dynamic_plane_update" class="table table-bordered no-marg pd_bt_51">
			<?php 
			$sq_plane_count = mysql_num_rows(mysql_query("select * from group_tour_plane_entries where tour_id='$tour_id'"));
			if($sq_plane_count==0){
				?>
				<tr>
	                <td><input class="css-checkbox" id="chk_plan-1" type="checkbox"><label class="css-label" for="chk_plan-1"> <label></td>
	                <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
	               	<td><select id="from_city-1" name="from_city-1" style="width: 150px;" class="app_select2 form-control" title="Select City Name" onchange="validate_location('from_city-1','to_city-1');airport_reflect(this.id)">
		                <?php get_cities_dropdown(); ?>
		            </select></td>
		            <td><select id="plane_from_location-1" class="app_select2 form-control" title="Sector From" name="plane_from_location-1" style="width: 200px;">
			            <option value="">*Sector From</option>
			        </select></td>
		        	<td><select id="to_city-1" name="to_city-1" style="width: 150px;" class="app_select2 form-control" title="Select City Name" onchange="validate_location('to_city-1','from_city-1');airport_reflect1(this.id)">
	                <?php get_cities_dropdown(); ?>
	                </select></td>
	                 <td><select id="plane_to_location-1" class="app_select2 form-control"  title="Sector To" name="plane_to_location-1" style="width: 200px;">
		                <option value="">*Sector To</option>
		            </select></td>

		            <td><select id="airline_name-1" class="app_select2 form-control" title="Airline Name" name="airline_name-1" style="width: 200px;">
			                <option value="">*Airline Name</option>
			                <?php get_airline_name_dropdown(); ?>
			            </select>
	                </td>

		            <td><select name="plane_class-1" id="plane_class-1" title="Class" style="width: 100px;">

		            	<option value="">*Class</option>

		            	<option value="Economy">Economy</option>

	                    <option value="Premium Economy">Premium Economy</option>

	                    <option value="Business">Business</option>

	                    <option value="First Class">First Class</option>

		            </select></td>	            
		        </tr>
				<?php
			}
			else{
				$offset = "_u";
				$count = 0;
				$sq_q_plane = mysql_query("select * from group_tour_plane_entries where tour_id='$tour_id'");
				while($row_q_plane = mysql_fetch_assoc($sq_q_plane)){
					$count++;
					
					?>
					<tr>
						<td><input class="css-checkbox" id="chk_plan-<?= $offset.$count ?>_d" type="checkbox" disabled checked><label class="css-label" for="chk_plan-<?= $offset ?>"> </label></td>
		                <td><input maxlength="15" value="<?= $count ?>" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
		                <td><select id="from_city-<?= $offset.$count ?>_d" name="from_city-<?= $offset.$count ?>_d" style="width: 150px;" class="app_select2 form-control" title="Select City Name" onchange="validate_location('from_city-<?= $offset.$count ?>_d' ,'to_city-<?= $offset.$count ?>_d' );airport_reflect(this.id)">
		                <?php $sq_city = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$row_q_plane[from_city]'")); ?>
		                <option value="<?php echo $row_q_plane['from_city'] ?>"><?php echo $sq_city['city_name'] ?></option>
		                <?php get_cities_dropdown(); ?>
		                </select></td>
		                <td><select id="plane_from_location-<?= $offset.$count ?>_d" class="app_select2 form-control" name="plane_from_location-<?= $offset.$count ?>_d" style="width: 200px !important;">
		                <option value="<?= $row_q_plane['from_location'] ?>"><?= $row_q_plane['from_location'] ?></option>
		                </select></td>
			            <td><select id="to_city-<?= $offset.$count ?>_d" name="to_city-<?= $offset.$count ?>_d" style="width: 150px;" class="app_select2 form-control" title="Select City Name" onchange="validate_location('to_city-<?= $offset.$count ?>_d' ,'from_city-<?= $offset.$count ?>_d' );airport_reflect1(this.id)">
			            <?php $sq_city = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$row_q_plane[to_city]'")); ?>
		                <option value="<?php echo $row_q_plane['to_city'] ?>"><?php echo $sq_city['city_name'] ?></option>
		                <?php get_cities_dropdown(); ?>
		                </select></td>
		                 <td><select id="plane_to_location-<?= $offset.$count ?>_d" class="app_select2 form-control" name="plane_to_location-<?= $offset.$count ?>_d" style="width: 200px !important;">
			                <option value="<?= $row_q_plane['to_location'] ?>"><?= $row_q_plane['to_location'] ?></option>
			            </select></td>	
		                 <td><select id="airline_name-<?= $offset.$count ?>_d" class="app_select2 form-control" name="airline_name-<?= $offset.$count ?>_d" style="width: 200px !important;">
		                 	<?php 
		                 	$sq_airline = mysql_fetch_assoc(mysql_query("select * from airline_master where airline_id='$row_q_plane[airline_name]'"));
		                 	?>
			                <option value="<?= $sq_airline['airline_id'] ?>"><?= $sq_airline['airline_name'].' ('.$sq_airline['airline_code'].')' ?></option>
			                      <?php get_airline_name_dropdown(); ?>
			            </select></td>
			            <td><select name="plane_class-<?= $offset.$count ?>_d" id="plane_class-<?= $offset.$count ?>_d" title="Class">
			            		<option value="<?= $row_q_plane['class'] ?>"><?= $row_q_plane['class'] ?></option>
				            	<option value="">Class</option>
				            	<option value="Economy">Economy</option>
			                    <option value="Premium Economy">Premium Economy</option>
			                    <option value="Business">Business</option>
			                    <option value="First Class">First Class</option>
				            </select></td>	
			            <td><input type="hidden" value="<?= $row_q_plane['id'] ?>"></td>
			        </tr>
					<?php
				}
			}
			?>                                            
        </table>
        </div>
    </div>
</div> 
