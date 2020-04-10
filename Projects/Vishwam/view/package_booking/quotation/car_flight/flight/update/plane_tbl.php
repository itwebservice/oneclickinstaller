 <div class="row mg_bt_10">
    <div class="col-md-12 text-right text_center_xs">
		<button type="button" class="btn btn-excel btn-sm" onclick="addRow('tbl_flight_quotation_dynamic_plane_update')"><i class="fa fa-plus"></i></button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
        <table id="tbl_flight_quotation_dynamic_plane_update" name="tbl_flight_quotation_dynamic_plane_update" class="table table-bordered pd_bt_51 no-marg">
			<?php 
			$sq_plane_count = mysql_num_rows(mysql_query("select * from flight_quotation_plane_entries where quotation_id='$quotation_id'"));
			if($sq_plane_count==0){
				?>
				<tr>
	                <td><input class="css-checkbox" id="chk_plan-1" type="checkbox" checked><label class="css-label" for="chk_plan-"> </label></td>
	                <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
	                <td><select id="from_city-1" name="from_city-1" style="width: 150px;" class="app_select2 form-control" title="Select City Name" onchange="airport_reflect(this.id)">
		                <?php get_cities_dropdown(); ?>
		            </select></td>
		            <td><select id="plane_from_location-1" class="app_select2 form-control" title="Sector From" name="plane_from_location-1" style="width: 200px;">
			            <option value="">*Sector From</option>
			        </select></td>
		        	<td><select id="to_city-1" name="to_city-1" style="width: 150px;" class="app_select2 form-control" title="Select City Name" onchange="airport_reflect1(this.id)">
	                <?php get_cities_dropdown(); ?>
	                </select></td>
	                 <td><select id="plane_to_location-1" class="app_select2 form-control" name="plane_to_location-1" style="width: 200px;">
		                <option value="">Sector To</option>
		            </select></td>	            
					<td><select id="airline_name-1" class="app_select2 form-control"  title="Airline Name" name="airline_name-1" style="width: 200px;">
		                    <option value="">Airline Name</option>
		                    <?php get_airline_name_dropdown(); ?>
						    </select></td>
		            <td><select name="plane_class-1" id="plane_class-1" title="Class" style="width: 150px;">
				            	<option value="">Class</option>
				            	<option value="Economy">Economy</option>
			                    <option value="Premium Economy">Premium Economy</option>
			                    <option value="Business">Business</option>
			                    <option value="First Class">First Class</option>
				        </select></td>
		            <td><input type="text" id="txt_dapart-1" name="txt_dapart-1" class="app_datetimepicker" placeholder="Departure Date & Time" title="Departure Date & Time" onchange="get_to_datetime(this.id,'txt_arrval-1')" style="width: 139px;" /></td>	           
		            <td><input type="text" id="txt_arrval-1" name="txt_arrval-1" class="app_datetimepicker" placeholder="Arrival Date Time" title="Arrival Date Time" style="width: 139px;" /></td>
		            <td><input type="hidden" id="txt_count-1" name="txt_count-1" value=""></td></tr>
		        </tr>
		        <script>
	            	$('#txt_arrval-1, #txt_dapart-1').datetimepicker({format:'d-m-Y H:i:s' });
	            </script>
				<?php
			}
			else{
				$offset = "_u";
				$count = 0;
				$sq_q_plane = mysql_query("select * from flight_quotation_plane_entries where quotation_id='$quotation_id'");
				while($row_q_plane = mysql_fetch_assoc($sq_q_plane)){
					$sq_airline = mysql_fetch_assoc(mysql_query("select * from airline_master where airline_id='$row_q_plane[airline_name]'"));
					$count++;
					?>
					<tr>
						<td><input class="css-checkbox" id="chk_plan-<?= $offset.$count ?>_d" type="checkbox" disabled checked><label class="css-label" for="chk_plan-<?= $offset.$count ?>_d"> </label></td>
		                <td><input maxlength="15" value="<?= $count ?>" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
		                <td><select id="from_city-<?= $offset.$count ?>_d" name="from_city-<?= $offset.$count ?>_d" style="width: 150px;" class="app_select2 form-control" title="Select City Name" onchange="airport_reflect(this.id)">
		                <?php $sq_city = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$row_q_plane[from_city]'")); ?>
		                <option value="<?php echo $row_q_plane['from_city'] ?>"><?php echo $sq_city['city_name'] ?></option>
		                <?php get_cities_dropdown(); ?>
		                </select></td>
		                <td><select id="plane_from_location-<?= $offset.$count ?>_d" class="app_select2 form-control" name="plane_from_location-<?= $offset.$count ?>_d" style="width: 200px !important;">
		                <option value="<?= $row_q_plane['from_location'] ?>"><?= $row_q_plane['from_location'] ?></option>
		                </select></td>
			            <td><select id="to_city-<?= $offset.$count ?>_d" name="to_city-<?= $offset.$count ?>_d" style="width: 150px;" class="app_select2 form-control" title="Select City Name" onchange="airport_reflect1(this.id)">
			            <?php $sq_city = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$row_q_plane[to_city]'")); ?>
		                <option value="<?php echo $row_q_plane['to_city'] ?>"><?php echo $sq_city['city_name'] ?></option>
		                <?php get_cities_dropdown(); ?>
		                </select></td>
		                 <td><select id="plane_to_location-<?= $offset.$count ?>_d" class="app_select2 form-control" name="plane_to_location-<?= $offset.$count ?>_d">
			                <option value="<?= $row_q_plane['to_location'] ?>"><?= $row_q_plane['to_location'] ?></option>
			                      <?php get_airport_name_dropdown(); ?>
			            </select></td>			            	            
						<td><select id="airline_name-<?= $offset.$count ?>_d" class="app_select2 form-control"  title="Airline Name" name="airline_name-<?= $offset.$count ?>_d">
							<?php if($row_q_plane['airline_name']!=''){ ?>
							<option value="<?= $row_q_plane['airline_name'] ?>"><?= $sq_airline['airline_name'].' ('.$sq_airline['airline_code'].')' ?></option>
		                    <option value="">Airline Name</option>
		                    <?php get_airline_name_dropdown(); 
		              		  }else{ ?>
		              		  	 <option value="">Airline Name</option>
		                    <?php get_airline_name_dropdown(); } ?>
						    </select></td>
			            <td><select name="plane_class-<?= $offset.$count ?>_d" id="plane_class-<?= $offset.$count ?>_d" title="Class">
			            	<?php if($row_q_plane['class']!=''){ ?>
			            		<option value="<?= $row_q_plane['class'] ?>"><?= $row_q_plane['class'] ?></option>
				            	<option value="">Class</option>
				            	<option value="Economy">Economy</option>
			                    <option value="Premium Economy">Premium Economy</option>
			                    <option value="Business">Business</option>
			                    <option value="First Class">First Class</option>
			                     <?php }else{ ?>
			                      <option value="">Class</option>
				            	<option value="Economy">Economy</option>
			                    <option value="Premium Economy">Premium Economy</option>
			                    <option value="Business">Business</option>
			                    <option value="First Class">First Class</option>
			                <?php } ?>
				            </select></td>	
			            <td><input type="text" id="txt_dapart-<?= $offset.$count ?>_d" name="txt_dapart-<?= $offset.$count ?>_d" class="app_datetimepicker" placeholder="Departure Date & Time" title="Departure Date & Time" onchange="get_to_datetime(this.id,'txt_arrval-<?= $offset.$count ?>_d')" value="<?= date('d-m-Y H:i:s', strtotime($row_q_plane['dapart_time'])) ?>" /></td>
			            <td><input type="text" id="txt_arrval-<?= $offset.$count ?>_d" name="txt_arrval-<?= $offset.$count ?>_d" class="app_datetimepicker" placeholder="Arrival Date Time" title="Arrival Date Time" value="<?= date('d-m-Y H:i:s', strtotime($row_q_plane['arraval_time'])) ?>" /></td>
			            <td><input type="hidden" value="<?= $row_q_plane['id'] ?>"></td></tr>
			        </tr>

		            <script>
		            	$('#txt_arrval-<?= $offset.$count ?>_d, #txt_dapart-<?= $offset.$count ?>_d').datetimepicker({ format:'d-m-Y H:i:s' });
		            	$('#plane_from_location-<?= $offset.$count ?>_d, #plane_to_location-<?= $offset.$count ?>_d').select2();
		            </script>
					<?php
				}
			}
			?>                                            
        </table>
        </div>
    </div>
</div> 
