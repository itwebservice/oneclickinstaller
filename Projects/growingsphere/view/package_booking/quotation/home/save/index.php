<?php
include "../../../../../model/model.php";
include_once('../../../../layouts/fullwidth_app_header.php');
$login_id = $_SESSION['login_id'];
$role = $_SESSION['role'];
$emp_id = $_SESSION['emp_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];
?>
<!-- Tab panes -->
<div class="bk_tab_head bg_light">
    <ul> 
        <li>
            <a href="javascript:void(0)" id="tab1_head" class="active">
                <span class="num" title="Enquiry">1<i class="fa fa-check"></i></span><br>
                <span class="text">Enquiry</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" id="tab2_head">
                <span class="num" title="Package">2<i class="fa fa-check"></i></span><br>
                <span class="text">Package</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" id="tab_daywise_head">
                <span class="num" title="Daywise Gallery">3<i class="fa fa-check"></i></span><br>
                <span class="text">Daywise Gallery</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" id="tab3_head">
                <span class="num" title="Travel And Stay">4<i class="fa fa-check"></i></span><br>
                <span class="text">Travel And Stay</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" id="tab4_head">
                <span class="num" title="Costing">5<i class="fa fa-check"></i></span><br>
                <span class="text">Costing</span>
            </a>
        </li>
    </ul>
</div>

<div class="bk_tabs">
    <div id="tab1" class="bk_tab active">
        <?php include_once("tab1.php"); ?>
    </div>
    <div id="tab2" class="bk_tab">
        <?php include_once("tab2.php"); ?>
    </div>
    <div id="tab_daywise" class="bk_tab">
        <?php include_once("daywise_images.php"); ?>
    </div>
    <div id="tab3" class="bk_tab">
        <?php include_once("tab3.php"); ?>
    </div>
    <div id="tab4" class="bk_tab">
        <?php include_once("tab4.php"); ?>
    </div>
</div>  
<script>
$('#enquiry_id, #currency_code').select2();

$('#from_date, #to_date, #quotation_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
$('#txt_arrval1,#txt_dapart1,#train_arrival_date,#train_departure_date').datetimepicker({ format:'d-m-Y H:i:s' });
//$('#quotation_save_modal').modal('show');

/**Hotel Name load start**/
function hotel_name_list_load(id)
{
  var city_id = $("#"+id).val();
  var count = id.substring(9);
  $.get( "../hotel/hotel_name_load.php" , { city_id : city_id } , function ( data ) {
        $ ("#hotel_name"+count).html( data ) ;                            
  } ) ;   
}
function hotel_type_load(id)
{
  var hotel_id = $("#"+id).val();
  var count = id.substring(10);
  $.get( "../hotel/hotel_type_load.php" , { hotel_id : hotel_id } , function ( data ) {
        $ ("#hotel_type"+count).val( data ) ;  
  } ) ;   
}
/**Excursion Name load**/
function get_excursion_list(id)
{
  var city_id = $("#"+id).val();
  var base_url = $('#base_url').val();
  
  var count = id.substring(10);  
  $.post(base_url+"view/package_booking/quotation/home/excursion_name_load.php" , { city_id : city_id } , function ( data ) {
        $ ("#excursion-"+count).html( data ) ;                            
  } ) ;   
}
/**Excursion Amount load**/
function get_excursion_amount(id)
{
  var service_id = $("#"+id).val();
  var base_url = $('#base_url').val();
  var total_adult = $('#total_adult').val();
  var total_children = $('#total_children').val();
  
  var count = id.substring(10);
  $.post(base_url+"view/package_booking/quotation/home/excursion_amount_load.php" , { service_id : service_id,total_adult : total_adult, total_children : total_children } , function ( data ) { 
        $ ("#excursion_amount-"+count).val(data) ;                            
  } ) ;   
}
</script>
<script src="<?php echo BASE_URL ?>view/package_booking/quotation/js/quotation.js"></script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script src="<?php echo BASE_URL ?>js/app/field_validation.js"></script>
<?php
include_once('../../../../layouts/fullwidth_app_footer.php');
?>