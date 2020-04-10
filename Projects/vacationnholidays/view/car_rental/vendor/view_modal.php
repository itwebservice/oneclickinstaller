<?php 

include "../../../model/model.php";

 

$vendor_id = $_POST['vendor_id'];



$sq_vendor = mysql_fetch_assoc(mysql_query("select * from car_rental_vendor where vendor_id='$vendor_id'"));

 

?>

<div class="modal fade profile_box_modal" id="car_rental_view_modal" role="dialog" aria-labelledby="myModalLabel">

  	<div class="modal-dialog modal-lg" role="document">

    	<div class="modal-content">

      		<div class="modal-body profile_box_padding">

      	

	      		<div>

				  <!-- Nav tabs -->

				  	<ul class="nav nav-tabs" role="tablist">

				    	<li role="presentation" class="active"><a href="#basic_information" aria-controls="home" role="tab" data-toggle="tab" class="tab_name">Car Supplier Information</a></li>

				    	<li class="pull-right"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></li>

				  	</ul>




		            <div class="panel panel-default panel-body fieldset profile_background no-pad-sm">

						<!-- Tab panes1 -->

						<div class="tab-content">

						    <!-- *****TAb1 start -->

						    <div role="tabpanel" class="tab-pane active" id="basic_information">
								
								<h3 class="editor_title">Supplier Information</h3>
						     	<div class="panel panel-default panel-body app_panel_style">
						     		<div class="row">

										<div class="col-md-12">
		
											<div class="profile_box main_block">

								        	<?php $sq_city = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$sq_vendor[city_id]'")); ?>

								        		<div class="row">

	           										<div class="col-md-6 right_border_none_sm" style="border-right: 1px solid #ddd">

								        				<span class="main_block"> 

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>City Name <em>:</em></label> " .$sq_city['city_name']; ?>

								        				</span>

								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Company Name <em>:</em></label> " .$sq_vendor['vendor_name']; ?> 

								        				</span>

								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Contact No <em>:</em></label> " .$sq_vendor['mobile_no']; ?>

								        				</span>

								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Landline No <em>:</em></label> " .$sq_vendor['landline_no']; ?>

								        				</span>

								        				<span class="main_block"> 

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Email ID <em>:</em></label> " .$sq_vendor['email']; ?>

								        				</span>

								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Contact Person <em>:</em></label> " .$sq_vendor['contact_person_name']; ?> 

								        				</span>

								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Emergency Contact <em>:</em></label> " .$sq_vendor['immergency_contact_no']; ?>

								        				</span>

								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Address <em>:</em></label> " .$sq_vendor['address']; ?>

								        				</span>

								        				<?php $sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_vendor[state_id]'"));
	                   									 ?>  
								        				 <span class="main_block">

											                  <i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

											                  <?php echo "<label>State <em>:</em></label>".$sq_state['state_name'] ?>

											            </span>	

										        		<span class="main_block">

								        				<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i> 

								        				    <?php echo "<label>Country <em>:</em></label> " .$sq_vendor['country']; ?>

								        				</span>
								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Website <em>:</em></label> " .$sq_vendor['website']; ?> 

								        				</span>

								        			</div>

								        			<div class="col-md-6">

								        				

								        			

								        				<span class="main_block"> 

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Bank Name <em>:</em></label> " .$sq_vendor['bank_name']; ?>

								        				</span>
								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Account Name <em>:</em></label> " .$sq_vendor['account_name']; ?> 

								        				</span>
								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Account No <em>:</em></label> " .$sq_vendor['account_no']; ?> 

								        				</span>

								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Branch <em>:</em></label> " .$sq_vendor['branch']; ?>

								        				</span>

								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>IFSC/Swift Code <em>:</em></label> " .$sq_vendor['ifsc_code']; ?>

								        				</span>
								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>PAN/TAN No <em>:</em></label> " .$sq_vendor['pan_no']; ?>

								        				</span>
								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Opening Balance <em>:</em></label> " .$sq_vendor['opening_balance']; ?>

								        				</span>
								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>As Of Date <em>:</em></label> " .get_date_user($sq_vendor['as_of_date']); ?>

								        				</span>

								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Side <em>:</em></label> " .$sq_vendor['side']; ?>

								        				</span>

								        				
								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Tax No <em>:</em></label> " .strtoupper($sq_vendor['service_tax_no']); ?>

								        				</span>
								        				<span class="main_block">

								        					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

								        				    <?php echo "<label>Status <em>:</em></label> " .$sq_vendor['active_flag']; ?>

								        				</span>

									    			</div> 

									    		</div>

									    	</div>

									    </div>



									</div> 
						     	</div> 



								<div class="row">    

								  	<div class="col-md-12">

								           	<h3 class="editor_title">Vehicle Details</h3>

								                <div class="table-responsive">

								                    <table class="table no-marg table-bordered">

								                     <thead>

								                       <tr class="table-heading-row">

								                       	<th>S_NO.</th>

								                       	<th>Vehicle_Name</th>

								                       	<th>Vehicle_No.</th>
								                       	<th>Vehicle_Type</th>

								                       	<th>Driver_Name</th>

								                       	<th>Mobile_No.</th>

								                       	<th>Purchase_Year</th>

								                       	<th>Rate</th>


								                       </tr>

								                    </thead>

								                    <tbody>

								                       <?php 

								                       		$count = 0;

								                       		$sq_vehivle_entry = mysql_query("select * from car_rental_vendor_vehicle_entries where vendor_id='$vendor_id'");

											            	while($row_vehicle_entry = mysql_fetch_assoc($sq_vehivle_entry)){

											            		$count++;

								                       	?>

																 <tr>

																    <td><?php echo $count; ?></td>

																    <td><?php echo $row_vehicle_entry['vehicle_name']; ?></td>

																    <td><?php echo strtoupper($row_vehicle_entry['vehicle_no']); ?></td>

																	<td><?php echo $row_vehicle_entry['vehicle_type']; ?></td>

																    <td><?php echo $row_vehicle_entry['vehicle_driver_name']; ?></td>

																    <td><?php echo $row_vehicle_entry['vehicle_mobile_no']; ?></td>

																    <td><?php echo $row_vehicle_entry['vehicle_year_of_purchase']; ?> </td>

																    <td><?php echo $row_vehicle_entry['vehicle_rate']; ?></td>

																</tr>  

								                       			<?php



								                       				}

								                       			

								                      			 ?>

								                    </tbody>

								                </table>

								            </div> 

								    </div>

								</div>  

						    </div>

						    <!-- ********Tab1 End******** --> 



						</div>

					</div>

		        </div>

    		</div>

    	</div>

	</div>

</div>



<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>

<script>

$('#car_rental_view_modal').modal('show');

</script>  

 

