<?php include_once('app_functions.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?= ($app_name == '') ? 'iTours App' : $app_name ?></title>
	<?php admin_header_scripts(); ?>
</head>
<body>
<input type="hidden" id="base_url" name="base_url" value="<?= BASE_URL ?>">

<div class="app_header main_block">
   <!--========*****Topbar*****========-->
   <?php include "admin_topbar.php"; ?>
</div>

<script type="text/javascript">
  $('#calendar').datetimepicker({ timepicker:false, format:'d-m-Y' });
 
</script>
<div class="sidebar_wrap">
  <!--========*****sidebar*****========-->
  <?php include "admin_sidebar.php"; ?>
</div>

<div class="app_content_wrap">