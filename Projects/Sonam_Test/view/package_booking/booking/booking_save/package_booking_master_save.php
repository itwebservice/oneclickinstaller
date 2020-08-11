<?php 
include "../../../../model/model.php";
include_once('../../../layouts/fullwidth_app_header.php'); 
$branch_admin_id = $_SESSION['branch_admin_id']; 
$financial_year_id = $_SESSION['financial_year_id']; 
$emp_id=$_SESSION['emp_id']; 
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_status = $_POST['branch_status'];
$sq=mysql_query("select emp_id,first_name, last_name from emp_master where emp_id='$emp_id'");

if($row=mysql_fetch_assoc($sq)){
    $first_name=$row['first_name'];
    $last_name=$row['last_name'];
}
$booker_name = $first_name." ".$last_name; 

$unique_timestapmp = md5(uniqid(rand(), true));
$unique_timestapmp = $emp_name."".$unique_timestapmp;
 
?>
<input type="hidden" id="emp_id" name="emp_id" value="<?php echo $emp_id; ?>">
<input type="hidden" id="txt_unique_timestamp" name="txt_unique_timestamp" value="<?php echo $unique_timestapmp; ?>">
<input type="hidden" id="branch_admin_id1" name="branch_admin_id1" value="<?= $branch_admin_id ?>" >
<input type="hidden" id="financial_year_id" name="financial_year_id" value="<?= $financial_year_id ?>" >
<input type="hidden" id="whatsapp_switch" value="<?= $whatsapp_switch ?>" >
<div class="bk_tab_head bg_light">
    <ul> 
        <li>
            <a href="javascript:void(0)" id="tab_1_head" class="active">
                <span class="num" title="Tour Details">1<i class="fa fa-check"></i></span><br>
                <span class="text">Tour Details</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" id="tab_2_head">
                <span class="num" title="Traveling">2<i class="fa fa-check"></i></span><br>
                <span class="text">Travelling</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" id="tab_3_head">
                <span class="num" title="Hotel & Transport">3<i class="fa fa-check"></i></span><br>
                <span class="text">Hotel & Transport</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" id="tab_4_head">
                <span class="num" title="Receipt">4<i class="fa fa-check"></i></span><br>
                <span class="text">Receipt</span>
            </a>
        </li>
    </ul>
</div>

<div class="bk_tabs">
    <div id="tab_1" class="bk_tab active">
        <?php include_once("tab_1/package_booking_master_save_tab1.php"); ?>  
    </div>

    <div id="tab_2" class="bk_tab">
            <?php include_once("tab_2/package_booking_master_save_tab2.php"); ?>
    </div>

    <div id="tab_3" class="bk_tab">
            <?php include_once("tab_3/package_booking_master_save_tab3.php"); ?>   
    </div>
    <div id="tab_4" class="bk_tab">
            <?php include_once("tab_4/package_booking_master_save_tab4.php"); ?>   
    </div>
</div>

<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>
<script>

function copy_details(){
	if(document.getElementById("copy_details1").checked){
		var customer_id = $('#customer_id_p').val();
		var base_url = $('#base_url').val();
		
		if(customer_id != '' || customer_id != 0){
			$.ajax({
			type:'post',
			url:base_url+'view/load_data/customer_info_load.php',
			data:{customer_id : customer_id},
			success:function(result){
				result = JSON.parse(result);
				var table = document.getElementById("tbl_package_tour_member");
				var rowCount = table.rows.length;
				var row = table.rows[0];
				
				row.cells[3].childNodes[0].value = result.first_name;
				row.cells[4].childNodes[0].value = result.middle_name;
				row.cells[5].childNodes[0].value = result.last_name;
				row.cells[7].childNodes[0].value = result.birth_date;
				adolescence_reflect('m_birthdate1');
				calculate_age_member('m_birthdate1');
			}
			});	
		}
	}
	else{
		var table = document.getElementById("tbl_package_tour_member");
		var rowCount = table.rows.length;
		for(var i=0; i<rowCount; i++)
		{
			var row = table.rows[i];
			if(row.cells[0].childNodes[0].checked)
			{
				row.cells[3].childNodes[0].value = '';
				row.cells[4].childNodes[0].value = '';
				row.cells[5].childNodes[0].value = '';
				row.cells[7].childNodes[0].value = '';
				row.cells[8].childNodes[0].value = '';
			}
		}
	}
}
</script>
<script src='../js/calculations.js'></script>
<?php
include_once('../../../layouts/fullwidth_app_footer.php');
?>