<?php include_once('app_functions.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?= ($app_name == '') ? 'iTours App' : $app_name ?></title>

	<?php fullwidth_header_scripts(); ?>
  
</head>
<body>
<input type="hidden" id="base_url" name="base_url" value="<?= BASE_URL ?>">

<div class="app_container">
<div class="app_wrap main_block">


<div class="fullwidth_header main_block">
	<div class="col-sm-4 col-xs-6">
     <a class="btn btn-info btn-sm ico_left mg_tp_10" data-toggle="tooltip" data-placement="bottom" title="Dashboard" href="<?php echo BASE_URL ?>view/dashboard/dashboard_main.php"><i class="fa fa-tachometer"></i><span class="">&nbsp;&nbsp;Dashboard</span></a>
	</div>
	<div class="col-sm-8 col-xs-6 mg_tp_10_sm_xs">
		<div class="app_ico_wrap main_block text-right">
			<ul class="hidden-xs">
				<?php topbar_icon_list() ?>
			</ul>

			<div class="dropdown pull-right visible-xs">
			  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			    <i class="fa fa-cog" aria-hidden="true"></i>
			    <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			    <?php topbar_icon_list() ?>
			  </ul>
			</div>
		</div>
	</div>
</div>

<link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,500" rel="stylesheet">


