<?php 
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$role_id= $_SESSION['role_id'];
?>
<form id="frm_tab1">
<div class="app_panel"> 
<!--=======Header panel======-->
    <div class="app_panel_head mg_bt_20">
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
            <button data-target="#myModalHint" data-toggle="modal">
              <a title="Help">
                <i class="fa fa-question" aria-hidden="true"></i>
              </a>
            </button>
          </div>
        </div>
    </div> 
<!--=======Header panel end======-->

    <div class="container">
        <h5 class="booking-section-heading main_block text-center">Hotel Basic Details</h5>
        <div class="app_panel_content Filter-panel">
            <div class="row mg_bt_20">
                <div class="col-md-3 mg_bt_10">
                    <select id="cmb_city_id1" name="cmb_city_id1" onchange="hotel_name_list_load(this.id)" class="city_master_dropdown" style="width:100%" title="Select City Name">
                        <?php get_cities_dropdown(); ?>
                    </select>
                </div>
                <div class="col-md-3 mg_bt_10">
                    <select id="hotel_id1" name="hotel_id1" style="width:100%" title="Select Hotel Name">
                        <option value="">*Select Hotel</option>
                    </select>
                </div>
                <div class="col-md-2 mg_bt_10">
                    <select name="currency_code" id="currency_code1" title="Currency" style="width:100%">
                    <?php
                        $sq_currency = mysql_query("select * from currency_name_master order by default_currency desc");
                        while($row_currency = mysql_fetch_assoc($sq_currency)){
                        ?>
                        <option value="<?= $row_currency['id'] ?>"><?= $row_currency['currency_code'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 mg_bt_10">
                    <input type="text" id="check_in" name="check_in" placeholder="Check In Time" title="Check In Time" />
                </div>
                <div class="col-md-2 mg_bt_10">
                    <input type="text" id="check_out" name="check_out" placeholder="Check Out Time" title="Check Out Time" />
                </div>
            </div>
        </div>

        <h5 class="booking-section-heading main_block"></h5>
        <div class="row mg_tp_20">
            <div class="col-md-6">
                <h3 class="editor_title">Inclusions</h3>
                <TEXTAREA  id="inclusions" class="feature_editor" style="width: 100%" name="inclusions" placeholder="Inclusions" title="Inclusions"></textarea>
            </div>
            <div class="col-md-6">
                <h3 class="editor_title">Exclusions</h3>	
                <TEXTAREA id="exclusions" class="feature_editor" style="width: 100%;" name="exclusions" placeholder="Exclusions" title="Exclusions"></textarea>
            </div>
            <div class="col-md-12 mg_tp_20">
                <h3 class="editor_title">Terms & Conditions</h3>
                <TEXTAREA id="terms_conditions" class="feature_editor" style="width: 100%;" name="terms_conditions" placeholder="Terms & Conditions" title="Terms & Conditions"></textarea>
            </div>
        </div>
        <br><br>
        <div class="row text-center">
            <div class="col-xs-12">
                <button class="btn btn-info btn-sm ico_right">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
            </div>
        </div>
</form>
<?= end_panel() ?>

<script>
$('#currency_code1,#cmb_hotel_id').select2();
$('#check_in,#check_out').datetimepicker({ datepicker:false, format:'H:i A',showMeridian: true });
$('#cmb_city_id1').select2({minimumInputLength: 1});
//**Hotel Name load start**//
function hotel_name_list_load(id){
  var city_id = $("#"+id).val();
  var base_url = $('#base_url').val();
  $.get( base_url+"view/hotels/master/b2b_tarrif/hotel_name_load.php" , { city_id : city_id } , function ( data ) {
        $ ("#hotel_id1").html( data );
  });
}
$('#frm_tab1').validate({

	rules:{
		cmb_city_id1 : { required : true },
		hotel_id1 : { required : true },
	},
	submitHandler:function(form){
	$('#tab1_head').addClass('done');
	$('#tab2_head').addClass('active');
	$('.bk_tab').removeClass('active');
	$('#tab2').addClass('active');
	$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);
	}

});



</script>

