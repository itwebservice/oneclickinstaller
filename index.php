<?php 
$conn = new mysqli("localhost", "root", "",'v7');
if($conn->connect_error){
	echo "Connection Failed:".$conn->connect_error;
	exit;
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Itweb Tours App Installers</title>
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">

	<script src="js/jquery-3.1.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/installer.js"></script>

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
			
			<div class="row text-center">
				<di class="col-md-4">
					<label for="product_name">Product Name</label>
					<input type="text" id="product_name" name="product_name" class="form-control" placeholder="Product Name">
				</di>
				<di class="col-md-4">
					<label for="database_name">Database Name</label>
					<input type="text" id="database_name" name="database_name" class="form-control" placeholder="Database Name">
				</di>
				<div class="col-md-4">
					<label for="country">Country</label>
					<select name="country" id="country" class="form-control" onchange="get_taxes(this.id)">
					<?php
					$query1 = $conn->query("select * from tax_country_master where 1");
					while($row_query = $query1 -> fetch_assoc()){
						?>
						<option value="<?= $row_query['country_id'] ?>"><?= $row_query['country_name'] ?></option>
					<?php } ?>
												
					</select>
				</div>
				<div class="col-md-4" style="margin-top: 10px;">
					<label for="tax_name">Tax</label>
					<input type="text" id="tax_name" name="tax_name" class="form-control" placeholder="Tax Name" readonly>
				</div>
				<div class="col-md-4" style="margin-top: 10px;">
					<label for="empty_setup">Setup Type</label>
					<select name="setup_type" id="setup_type" class="form-control">
						<option value="1">IToursPro</option>
						<option value="2">IToursEnterprise</option>
						<option value="3">IToursSmart</option>
						<option value="4">IToursGlobal</option>
					</select>
				</div>
				<div class="col-md-4" style="margin-top: 10px;">
					<label for="empty_setup">Empty Setup</label>
					<select name="empty_setup" id="empty_setup" class="form-control">
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
				</div>
				<di class="col-md-4" style="margin-top: 10px;">
					<label for="creator_name">Created By</label>
					<input type="text" id="creator_name" name="creator_name" class="form-control" placeholder="Creator Name">
				</di>				
			</div> <br>
			<div class="row text-center">
				<div class="col-md-12">
					<button onclick="installer_init()" class="btn btn-success">Install Application</button>
				</div>
			</div>
		
		</div>
	</div>

</div>


	
</body>
</html>
<script type="text/javascript">
	get_taxes('country');
</script>