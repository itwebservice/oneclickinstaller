<?php
$conn = new mysqli("localhost", "root", "",'v7');
if($conn->connect_error){
	echo "Connection Failed:".$conn->connect_error;
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Itweb Tours App Installer</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/select2.min.css">
	<link rel="stylesheet" href="css/jquery.datetimepicker.css">
	<script src="js/jquery-3.1.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/installer.js"></script>
	<script src="js/select2.min.js"></script>
	<script src="js/jquery.datetimepicker.full.js"></script>
</head>
<body>
<div class="container">
	<br>
	<div class="alert alert-danger" role="alert">
	This is highly sensitive information. Belongs to itwebservices. Do not use this feature unless you are authorized and well aware of this feature. This can impact other projects data as well.
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>iTours Installer</h4>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-4">
					<input type="text" id="product_name" name="product_name" class="form-control" placeholder="*Setup Name" title="Enter Setup Name" required>
				</div>
				<div class="col-md-4">
					<input type="text" id="database_name" name="database_name" class="form-control" placeholder="*Database Name" title="Enter Database Name" required>
				</div>
				<div class="col-md-4">
					<select name="setup_type" id="setup_type" class="form-control" title="Select Setup type" required>
						<option value="1">IToursPro</option>
						<option value="2">IToursEnterprise</option>
						<option value="3">IToursSmart</option>
						<option value="4">IToursGlobal(B2B)</option>
					</select>
				</div>
			</div>
			<div class="row" style="margin-top:10px">
				<div class="col-md-4">
					<select name="empty_setup" id="empty_setup" class="form-control" title="Empty setup(Yes/No)" required>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
				</div>
				<div class="col-md-4">
					<input type="text" id="creator_name" name="creator_name" class="form-control" placeholder="*Creator Name" title="Creator Name" required>
				</div>
				<div class="col-md-4">
					<input id="b2c" name="b2c" type="checkbox" style="margin-top: 10px;" required/><label for="b2c">&nbsp;&nbsp;B2C</label>
				</div>
			</div><hr/>
			<div class="row">
			</div>
			<div class="row">
				<div class="col-md-3">
					<input type="text" id="company_name" name="company_name" class="form-control" placeholder="*Company Name" title="Enter Company Name" required>
				</div>
				<div class="col-md-3">
					<input type="text" id="website" name="website" class="form-control" placeholder="*Website Name" title="Enter Website Name" required>
				</div>
				<div class="col-md-3">
					<input type="text" id="contact_no" name="contact_no" class="form-control" placeholder="*Contact No" title="Enter Contact No" required>
				</div>
				<div class="col-md-3">
					<textarea id="address" name="address" class="form-control" placeholder="*Address" rows="1" required></textarea>
				</div>
			</div>
			<div class="row" style="margin-top:10px">
				<div class="col-md-3">
					<input type="text" id="tax_name" name="tax_name" class="form-control" placeholder="*Tax name" title="Enter Tax name" required>
				</div>
				<div class="col-md-3">
					<select name="country" id="country" class="form-control" required>
						<option value="">*Select Country</option>
						<?php
						$query1 = $conn->query("select country_id,country_name,country_code from country_list_master where 1");
						while($row_query = $query1 -> fetch_assoc()){
							?>
							<option value="<?= $row_query['country_id'] ?>"><?= $row_query['country_name'].'('.$row_query['country_code'].')' ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-sm-3">
					<select name="state" id="state" title="Select State" class='form-control' style='width:100%' required>
						<option value="">*Select State</option>
						<?php
						$query1 = $conn->query("select id,state_name from state_master where 1");
						while($row_query = $query1 -> fetch_assoc()){
							?>
							<option value="<?= $row_query['id'] ?>"><?= $row_query['state_name'] ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-sm-3">
					<select name="currency" id="currency" title="Select currency" class='form-control' style='width:100%' required>
						<option value="">*Select Currency</option>
						<?php
						$query1 = $conn->query("select id,currency_code from currency_name_master where 1");
						while($row_query = $query1 -> fetch_assoc()){
							?>
							<option value="<?= $row_query['id'] ?>"><?= $row_query['currency_code'] ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="row" style="margin-top:10px">
				<div class="col-md-3">
					<input type="number" id="currency_rate" name="currency_rate" class="form-control" placeholder="*Currency Rate" title="Enter Currency Rate" required>
				</div>
				<div class="col-md-3">
					<input type="text" id="ffrom_date" name="ffrom_date" class="form-control" placeholder="*FY From date" title="Enter Financial Year From date" required>
				</div>
				<div class="col-md-3">
					<input type="text" id="fto_date" name="fto_date" class="form-control" placeholder="*FY To date" title="Enter Financial Year To date" required>
				</div>
				<div class="col-md-3">
					<input type="text" id="location" name="location" class="form-control" placeholder="*Location Name" title="Enter Location name" required>
				</div>
			</div>
			<div class="row" style="margin-top:10px">
				<div class="col-md-3">
					<input type="text" id="branch" name="branch" class="form-control" placeholder="*Branch Name" title="Enter Branch name" required>
				</div>
			</div>
			<div class="row text-center" style="margin-top:10px">
				<div class="col-md-12">
					<button onclick="installer_init()" class="btn btn-success">Install Application</button>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<script>
$('#country,#currency,#state').select2();
$('#fto_date,#ffrom_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
</script>