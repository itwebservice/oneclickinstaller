<?php
include "../../../model/model.php";

$entity_for=$_POST['entity_for'];
$emp_id=$_POST['emp_id'];
$branch_status=$_POST['branch_status'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
if($entity_for=="Group Tour"){
?>
<div class="col-sm-4 mg_bt_30">
    <select name="tour_id" id="tour_id" title="Tour Name" style="width:100%" onchange="tour_group_load()" title="Tour Name">
        <option value="">Tour Name</option>
           <?php 
              $sq_tour = mysql_query("select tour_id, tour_name from tour_master order by tour_name");
                   while($row_tour = mysql_fetch_assoc($sq_tour)){
            ?>
        <option value="<?= $row_tour['tour_id'] ?>"><?= $row_tour['tour_name'] ?></option>
               <?php
                }
               ?>
    </select>
</div>
<div class="col-sm-4 mg_bt_10">
  	  <select name="tour_group_id" id="tour_group_id" title="Tour Group" style="width:100%" title="Tour Group">
        <option value="">Tour Group</option>
    </select>
</div>

<?php
}else{
?>
<div class="col-sm-4 mg_bt_30">
        <input type="hidden" id="branch_admin_id1" name="branch_admin_id1" value="<?= $branch_admin_id ?>" >
	<select id="booking_id" name="booking_id" style="width:100%"> 
            <option value="">Select Booking</option>
            <?php 
                $query ="select * from package_tour_booking_master where 1";
                if($role != 'Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
                    $query .= " and emp_id='$emp_id'";
                }
                if($branch_status=='yes' && $role!='Admin'){
                    $query .= " and branch_admin_id = '$branch_admin_id'";
                } 
                $query .= " order by booking_id desc";
                $sq_booking = mysql_query($query);
                while($row_booking = mysql_fetch_assoc($sq_booking))
                {
                  $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_booking[customer_id]'"));   
                ?>
                 <option value="<?php echo $row_booking['booking_id'] ?>"><?php echo "Booking ID-".$row_booking['booking_id']."-".$sq_customer['first_name'].' '.$sq_customer['last_name']; ?></option>
                 <?php    
                    
                }    
             ?>
    </select>
</div>	
<?php
	}
?>
<script>
		$('#booking_id,#tour_id,tour_group_id').select2();
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>