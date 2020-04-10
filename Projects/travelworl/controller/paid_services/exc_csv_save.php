<?php 
include "../../model/model.php"; 
include "../../model/paid_service_master.php";

$paid_service_master = new paid_service_master();
$paid_service_master->exc_csv_save();
?>