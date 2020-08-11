<?php include "../../model/model.php"; ?>
 <div class="row mg_bt_20">          
            <div class="col-md-4">
              <select name="cust_type" id="cust_type" onchange="corporate_fields_reflect();" title="Customer Type" required>
              <?php get_customer_type_dropdown(); ?>
              </select>
            </div>
        </div>
        <div class="panel panel-default panel-body app_panel_style mg_tp_30 feildset-panel">
        <legend>Personal Information</legend>
        <div class="row mg_bt_10">
            <div class="col-sm-4 col-xs-12">
              <input type="text" id="cust_first_name" name="cust_first_name" placeholder="*First Name" onchange="fname_validate(this.id)" title="First Name" required>
            </div>
            <div class="col-sm-4 col-xs-12">
              <input type="text" id="cust_middle_name" onchange="fname_validate(this.id)" name="cust_middle_name" placeholder="Middle Name" title="Middle Name">
            </div>
            <div class="col-sm-4 col-xs-12">
              <input type="text" id="cust_last_name" name="cust_last_name" onchange="fname_validate(this.id)" placeholder="Last Name" title="Last Name">
            </div>  
        </div>                      
        <div class="row mg_bt_10">
            <div class="col-sm-4 col-xs-12">
              <select name="cust_gender" id="cust_gender" title="Select Gender">
                <option value="">Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>           
            <div class="col-sm-4 col-xs-12">
              <input type="text" id="cust_birth_date" name="cust_birth_date" placeholder="Birth Date" title="Birth Date" onchange="validate_futuredate(this.id);calculate_age_generic('cust_birth_date', 'cust_age')" value="<?= date('d-m-Y',  strtotime(' -1 day'))?>">
            </div>
            <div class="col-sm-4 col-xs-12">
              <input type="text" id="cust_age" name="cust_age" onchange="validate_balance(this.id);" placeholder="Age" title="Age">
            </div>
        </div>
        <div class="row mg_bt_10">
            <div class="col-sm-4 col-xs-12">
              <input type="text" id="cust_contact_no" name="cust_contact_no" onchange="mobile_validate(this.id)" placeholder="*Mobile No" title="Mobile No" required>
            </div>
            <div class="col-sm-4 col-xs-12">
              <input type="text" id="cust_email_id" name="cust_email_id" placeholder="Email ID" title="Email ID">
            </div>     
            <div class="col-sm-4 col-xs-12">
              <input type="text" id="cust_service_tax_no" name="cust_service_tax_no" onchange="validate_alphanumeric(this.id)" placeholder="Tax No" title="Tax No">
            </div>
          </div>
          </div>
          <div class="panel panel-default panel-body app_panel_style mg_tp_30 feildset-panel">
           <legend>Address Information</legend>
           <div class="row mg_bt_10">
            <div class="col-sm-4 col-xs-12">
              <input type="text" name="cust_address1"  onchange="validate_address(this.id)" id="cust_address1" placeholder="Address-1" title="Address 1"/>
            </div>
             <div class="col-sm-4 col-xs-12">
              <input type="text" name="cust_address2"  onchange="validate_address(this.id)" id="cust_address2" placeholder="Address-2" title="Address 2"/>
            </div>
             <div class="col-sm-4 col-xs-12">
              <input type="text" name="city" id="city"  onchange="validate_city(this.id)" placeholder="City" title="City"/>
            </div>
          </div>
          <div class="row mg_bt_10">
            <div class="col-sm-4 col-xs-12">
              <select name="cust_state" id="cust_state" title="Select State" style="width : 100%">
                <?php get_states_dropdown() ?>
              </select>
            </div>
          </div>
          <div class="row mg_bt_10">
            <div id="corporate_fields"></div>
        </div>
  <script>
  $('#cust_state').select2();
  $('#cust_birth_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
  </script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>