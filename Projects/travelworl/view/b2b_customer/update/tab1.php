<form id="frm_updatetab1">
<input type='hidden' value='<?=$register_id?>' id='register_id' name='register_id'/>
	<div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_20">

	     <legend>Basic Details</legend>
          <div class="row mg_tp_10">
              <div class="col-md-3 col-sm-6 mg_bt_10">
                  <input class="form-control" type="hidden" id="company_old" name="company_old" value="<?= $sq_query['company_name'] ?>" required />
                  <input class="form-control" type="text" id="company_name" name="company_name" placeholder="*Company Name" title="Company Name"  value="<?= $sq_query['company_name'] ?>" required /> 
              </div>
              <div class="col-md-3 col-sm-6 mg_bt_10">
                  <input class="form-control" type="text" id="acc_name" name="acc_name" placeholder="Accounting Name" title="Accounting Name"  value="<?= $sq_query['accounting_name'] ?>"/>
              </div>
              <div class="col-md-3 col-sm-6 mg_bt_10">
                  <select class="form-control" id='iata_status' title='iata_status' name='iata_status'>
                  <?php if($sq_query['iata_status']!=''){?>
                    <option value="<?=$sq_query['iata_status'] ?>"><?=$sq_query['iata_status'] ?></option><?php } ?>
                    <option value=''>IATA Status</option>
                    <option value='Approved'>Approved</option>
                    <option value='Not Approved'>Not Approved</option>
                  </select>
              </div>
              <div class="col-md-3 col-sm-6 mg_bt_10">
                  <input class="form-control" type="text" id="iata_reg" name="txt_mobile_no1" placeholder="IATA Reg.No" title="IATA Reg.No" value="<?=$sq_query['iata_reg_no'] ?>">
              </div>
          </div>

          <div class="row">
          		<div class="col-md-3 col-sm-6 mg_bt_10_xs">
                  <input class="form-control" type="text" id="nature" name="nature" placeholder="Nature Of Business" title="Nature Of Business" value="<?=$sq_query['nature_of_business'] ?>"/>
              </div>
	            <div class="col-md-3 col-sm-6 mg_bt_10_xs">
                <select class="form-control" id='currency' title='Currency' name='currency' style='width:100%;'>
                <?php if($sq_query['currency']!=0){ $sq_cur = mysql_fetch_assoc(mysql_query("select id,currency_code from currency_name_master where id='$sq_query[currency]'"));?>
                <option value="<?= $sq_cur['id'] ?>"><?= $sq_cur['currency_code'] ?></option>
                <?php } ?>
                  <option value=''>Preferred Currency</option>
                  <?php $sq_currency = mysql_query("select id,currency_code from currency_name_master where 1");
                  while($row_currency = mysql_fetch_assoc($sq_currency)){ ?>
                    <option value="<?= $row_currency['id'] ?>"><?= $row_currency['currency_code'] ?></option>
                  <?php } ?>
                </select>
	            </div>
              <div class="col-md-3 col-sm-6 mg_bt_10_xs">
                  <input type="text" id="telephone" name="telephone" placeholder="Telephone" title="Telephone" value="<?= $sq_query['telephone'] ?>"/>
              </div>
              <div class="col-md-3 col-sm-6 mg_bt_10_xs">
                  <input type="text" id="latitude" name="latitude" placeholder="Latitude" title="Latitude" value="<?= $sq_query['latitude'] ?>"/>
              </div>
          </div>

          <div class="row mg_tp_10">
                <div class="col-md-3 col-sm-6 mg_bt_10_xs">
                  <input type="text" id="turnover_slab" name="turnover_slab" placeholder="Turnover Slab" title="Turnover Slab" value="<?= $sq_query['turnover'] ?>"/>
                </div>
                <div class="col-md-3 col-sm-6 mg_bt_10_xs">
                  <input type="text" id="skype_id" name="skype_id" placeholder="Skype ID" title="Skype ID" value="<?= $sq_query['skype_id'] ?>"/>
                </div>
                <div class="col-md-3 col-sm-6 mg_bt_10_xs">
                  <input type="text" id="website" name="website" placeholder="Website" title="Website" value="<?= $sq_query['website'] ?>"/>
                </div>
                <div class="col-md-3 col-sm-6 mg_bt_10">
                    <select class="form-control" id='active_flag' title='active_flag' name='active_flag'>
                    <?php if($sq_query['active_flag']!=''){?>
                      <option value="<?=$sq_query['active_flag'] ?>"><?=$sq_query['active_flag'] ?></option><?php } ?>
                      <option value=''>Active Flag</option>
                      <option value='Active'>Active</option>
                      <option value='Inactive'>Inactive</option>
                    </select>
                </div>
          </div>
        </div>

        <div class="panel panel-default panel-body app_panel_style mg_tp_30 feildset-panel">
  	    <legend>Address Details</legend>

        	<div class="row mg_tp_10">
            <div class="col-md-3 col-sm-6 mg_bt_10_xs">
              <select id='city' name='city' class='form-control' style='width:100%;' required>
              <?php $sq_city = mysql_fetch_assoc(mysql_query("select city_id,city_name from city_master where city_id='$sq_query[city]'"));?>
                <option value="<?= $sq_city['city_id'] ?>"><?= $sq_city['city_name'] ?></option>
                <?php get_cities_dropdown();?>
              </select>
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10_xs">
              <input type="text" id="address1" name="address1" placeholder="Address1" title="Address1" value="<?= $sq_query['address1'] ?>"/>
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10_xs">
              <input type="text" id="address2" name="address2" placeholder="Address2" title="Address2" value="<?= $sq_query['address2'] ?>"/>
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10_xs">
              <input type="text" id="pincode" name="pincode" placeholder="Pincode" title="Pincode" value="<?= $sq_query['pincode'] ?>"/>
            </div>
          </div>
          <div class="row mg_tp_10">
              <div class="col-md-3 col-sm-6 mg_bt_10_xs">
                <select class="form-control" id='country' title='Country' name='country' style='width:100%;'>
                <?php if($sq_query['country']!=0){ $sq_coun = mysql_fetch_assoc(mysql_query("select * from country_list_master where country_id='$sq_query[country]'"));?>
                    <option value="<?= $sq_coun['country_id'] ?>"><?= $sq_coun['country_name'].'('.$sq_coun['country_code'].')' ?></option>
                <?php } ?>
                  <option value=''>Country</option>
                  <?php
                  $sq_country = mysql_query("select * from country_list_master where 1");
                  while($row_country = mysql_fetch_assoc($sq_country)){ ?>
                    <option value="<?= $row_country['country_id'] ?>"><?= $row_country['country_name'].'('.$row_country['country_code'].')' ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-md-3 col-sm-6 mg_bt_10_xs">
                <input type="text" id="timezone" name="timezone" placeholder="Timezone" title="Timezone" value="<?= $sq_query['timezone'] ?>"/>
              </div>
	            <div class="col-md-3 col-sm-6 mg_bt_10_xs">
	              <div class="div-upload">
	                <div id="address_upload_btn1" class="upload-button1"><span>Upload</span></div>
	                <span id="id_proof_status" ></span>
	                <ul id="files" ></ul>
	                <input type="hidden" id="address_upload_url" name="address_upload_url" value='<?=$sq_query['address_proof_url'] ?>'>
	              </div>
	            </div> 
	        </div>
	    </div>
      <div class="row text-center">
        <div class="col-xs-12">
          <button class="btn btn-info btn-sm ico_right">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
        </div>
      </div>
</form>
<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>
<script src="<?= BASE_URL ?>js/ajaxupload.3.5.js"></script>

<script>
$('#currency').select2();
$('#city,#country').select2({minimumInputLength:1});

upload_address_proof();

function upload_address_proof(){

    var btnUpload=$('#address_upload_btn1');
    $(btnUpload).find('span').text('Address Proof');    

    new AjaxUpload(btnUpload, {

      action: '../b2b_customer/inc/upload_address_proof.php',

      name: 'uploadfile',

      onSubmit: function(file, ext){  

        if (! (ext && /^(jpg|png|jpeg|pdf)$/.test(ext))){ 

         error_msg_alert('Only PDF,JPG, PNG or GIF files are allowed');

         return false;

        }

        $(btnUpload).find('span').text('Uploading...');

      },

      onComplete: function(file, response){

        if(response==="error"){          

          error_msg_alert("File is not uploaded.");           

          $(btnUpload).find('span').text('Upload');

        }else

        { 

          $(btnUpload).find('span').text('Uploaded');

          $("#address_upload_url").val(response);

        }

      }

    });

}


$(function(){
$('#frm_updatetab1').validate({

	rules:{
          
	},

	submitHandler:function(form){

		$('a[href="#tab2"]').tab('show');
     return false;
	}

});

});

</script>

