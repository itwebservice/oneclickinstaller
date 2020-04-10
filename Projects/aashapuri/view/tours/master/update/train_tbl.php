<div class="row mg_bt_10">
    <div class="col-md-12 text-right text_center_xs">        
        <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_package_tour_quotation_dynamic_train')"><i class="fa fa-plus"></i></button>
    </div>
</div>

<div class="row mg_bt_10">
    <div class="col-md-12">
        <div class="table-responsive">
        <table id="tbl_package_tour_quotation_dynamic_train" name="tbl_package_tour_quotation_dynamic_train" class="table table-bordered no-marg pd_bt_51">

        	<?php 
        	$sq_train_count = mysql_num_rows(mysql_query("select * from group_train_entries where tour_id='$tour_id'"));
        	if($sq_train_count==0){
        		?>
				<tr>
	                <td><input class="css-checkbox" id="chk_train1" type="checkbox"><label class="css-label" for="chk_train1"> <label></td>
	                <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
	                <td class="col-md-3"><select id="train_from_location1" onchange="validate_location('train_from_location1','train_to_location1')" name="train_from_location1" class="app_select2 form-control"  style="width: 100% !important;">
			                <option value="">From</option>
			                <?php 
			                    $sq_city = mysql_query("select * from city_master");
			                    while($row_city = mysql_fetch_assoc($sq_city))
			                    {
			                     ?>
			                        <option value="<?php echo $row_city['city_name'] ?>"><?php echo $row_city['city_name'] ?></option>
			                     <?php   
			                    }    
			                ?>
			            </select>
	                </td>
	                 <td class="col-md-3"><select id="train_to_location1" onchange="validate_location('train_to_location1','train_from_location1')" name="train_to_location1" class="app_select2 form-control"  style="width: 100% !important;">
		                <option value="">To</option>
		                <?php 
		                    $sq_city = mysql_query("select * from city_master");
		                    while($row_city = mysql_fetch_assoc($sq_city))
		                    {
		                     ?>
		                        <option value="<?php echo $row_city['city_name'] ?>"><?php echo $row_city['city_name'] ?></option>
		                     <?php   
		                    }    
		                ?>
		            </select></td>
		            <td class="col-md-2"><select name="train_class" id="train_class1" title="Class">
				            	<option value="">Class</option>
				            	<?php get_train_class_dropdown(); ?>
		            </select></td>
		            <td class="col-md-2"><input type="text" id="train_departure_date1" name="train_departure_date" placeholder="Departure Date Time" title="Departure Date Time" class="app_datetimepicker" value="<?= date('d-m-Y H:i:s') ?>" onchange="get_to_datetime(this.id,'train_arrival_date1')"></td>
		            <td class="col-md-2"><input type="text" id="train_arrival_date1" name="train_arrival_date" placeholder="Arrival Date Time" title="Arrival Date Time" class="app_datetimepicker" value="<?= date('d-m-Y H:i:s') ?>"></td>
	            </tr>  
	            <script>
		            	$('#train_arrival_date1, #train_departure_date1').datetimepicker({ format:'d-m-Y H:i:s' });
		            </script>        
        		<?php
        	}
        	else{
        		$count = 0;
        		$sq_q_train = mysql_query("select * from group_train_entries where tour_id='$tour_id'");
        		while($row_q_train = mysql_fetch_assoc($sq_q_train)){
        			$count++;
        			?>
					<tr>
		                <td><input class="css-checkbox" id="chk_train<?= $count ?>_1" type="checkbox" checked disabled><label class="css-label" for="chk_train<?= $count ?>_1"> <label></td>
		                <td><input maxlength="15" value="<?= $count ?>" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
		                <td class="col-md-3"><select id="train_from_location<?= $count ?>_1" onchange="validate_location('train_from_location<?= $count ?>_1','train_to_location<?= $count ?>_1')"  class="app_select2 form-control" name="train_from_location" style="width: 100% !important;">
									<option value="<?= $row_q_train['from_location'] ?>"><?= $row_q_train['from_location'] ?></option>
				                	<?php
				                    $sq_city = mysql_query("select * from city_master");
				                    while($row_city = mysql_fetch_assoc($sq_city))
				                    {
				                     ?>
				                        <option value="<?php echo $row_city['city_name'] ?>"><?php echo $row_city['city_name'] ?></option>
				                     <?php   
				                    }    
				                ?>
				            </select>
		                </td>
		                 <td class="col-md-3"><select id="train_to_location<?= $count ?>_1" onchange="validate_location('train_to_location<?= $count ?>_1' , 'train_from_location<?= $count ?>_1')" class="app_select2 form-control" name="train_to_location" style="width: 100% !important;">
		                 	    <option value="<?= $row_q_train['to_location'] ?>"><?= $row_q_train['to_location'] ?></option>	
			                <?php 
			                    $sq_city = mysql_query("select * from city_master");
			                    while($row_city = mysql_fetch_assoc($sq_city))
			                    {
			                     ?>
			                        <option value="<?php echo $row_city['city_name'] ?>"><?php echo $row_city['city_name'] ?></option>
			                     <?php   
			                    }    
			                ?>
			            </select></td>
			            <td class="col-md-2"><select name="train_class" id="train_class1" title="Class">
			            	<option value="<?= $row_q_train['class'] ?>"><?= $row_q_train['class'] ?></option>
			            	<?php get_train_class_dropdown(); ?>
			            </select></td>
			            <td class="col-md-2"><input type="text" id="train_departure_date<?= $count ?>_u" name="train_departure_date" placeholder="Departure Date" title="Departure Date" class="app_datetimepicker" onchange="get_to_datetime(this.id,'train_arrival_date<?= $count ?>_u')" value="<?= get_datetime_user($row_q_train['departure_date']) ?>"></td>
			            <td class="col-md-2"><input type="text" id="train_arrival_date<?= $count ?>_u" name="train_arrival_date" placeholder="Arrival Date" title="Arrival Date" class="app_datetimepicker" value="<?= get_datetime_user($row_q_train['arrival_date']) ?>"></td>
			            <td class="hidden"><input type="text" value="<?= $row_q_train['id'] ?>"></td>
		            </tr>          
		            <script>
		            	$('#train_arrival_date<?= $count ?>_u, #train_departure_date<?= $count ?>_u').datetimepicker({ format:'d-m-Y H:i:s' });
		            	$('#train_from_location<?= $count ?>_1, #train_to_location<?= $count ?>_1').select2({minimumInputLength: 1});
		            </script>
        			<?php
        		}
        	}
        	?>
                                  
        </table>
        </div>
    </div>
</div> 