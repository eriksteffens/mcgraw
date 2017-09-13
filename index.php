<html>
    <head></head>
    <body>
<?php
require_once "includes/common.php";
require_once "includes/header.php";
require_once "includes/nav.php";
require_once('includes/database.php');
redirectRequest("main.php");



$assets = count(getDBItems("Assets"));
$tenants = count(getDBItems("Tenants"));
$workOrders = count(getDBItems("WorkOrders"));

?>

<div id="main" class="container">
	<div class="row">
		<div class="col s12">
			<h3 class="center-align"> </h3>
		</div>
	</div>
	<div id="asset-list" class="col s12">

		<div class="opening-summary">

			<div class="row">
				<div class="col s12">
					<h1 class="center-text">Welcome to the McGraw Group Asset Management System</h1>
				</div>
			</div>
			<div class="row">

				<div class="col s5 center-text">
				        <a class="nav-item" href="main.php">ASSET<div class="center-text summary-count"><?php echo $assets; ?></div></a>
				</div>
				<div class="col s2"></div>
					<div class="col s5 center-text">
						<a class="nav-item" href="tenant.php">TENANT<div class="center-text summary-count"><?php echo $tenants; ?></div></a>
					</div>
			</div>
			<div class="row">

				<div class="col s5 center-text">
				        <a class="nav-item" href="workorder.php">WORK ORDER<div class="center-text summary-count"><?php echo $workOrders; ?></div></a>
				</div>
				<div class="col s2"></div>
					<div class="col s5 center-text">
					</div>
			</div>
		</div>

	</div>	
</div>
	</body>
</html>