<?php 
include_once('../../model/model.php');
include_once('../../model/paid_service_master.php');

$paid_service_master = new paid_service_master;
$paid_service_master->service_save();
?>