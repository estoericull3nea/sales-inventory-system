<?php
include_once("init.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>POSNIC - Update Supplier</title>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php include_once("tpl/common_js.php"); ?>
	<script src="js/script.js"></script>
	<script>
		$(document).ready(function() {
			$("#form1").validate({
				rules: {
					name: {
						required: true,
						minlength: 3,
						maxlength: 200
					},
					address: {
						minlength: 3,
						maxlength: 500
					},
					contact1: {
						minlength: 3,
						maxlength: 20
					},
					contact2: {
						minlength: 3,
						maxlength: 20
					}
				},
				messages: {
					name: {
						required: "Please enter a Supplier Name",
						minlength: "Supplier must consist of at least 3 characters"
					},
					address: {
						minlength: "Supplier Address must be at least 3 characters long",
						maxlength: "Supplier Address must be at least 3 characters long"
					}
				},
				submitHandler: function(form) {
					const formData = $(form).serialize();
					$.ajax({
						url: './ajax/supplier/updateSupplier.php',
						type: 'POST',
						data: formData,
						success: function(response) {
							alert('Supplier updated successfully.');
							console.log(response);
						},
						error: function(xhr, status, error) {
							alert('An error occurred while updating the supplier.');
							console.error(xhr, status, error);
						}
					});
					return false;
				}
			});
		});
	</script>
</head>

<body>
	<?php include_once("tpl/top_bar.php"); ?>
	<div id="header-with-tabs">
		<div class="page-full-width cf">
			<ul id="tabs" class="fl">
				<li><a href="dashboard.php" class="dashboard-tab">Dashboard</a></li>
				<li><a href="view_sales.php" class="sales-tab">Sales</a></li>
				<li><a href="view_customers.php" class="customers-tab">Customers</a></li>
				<li><a href="view_supplier.php" class="active-tab supplier-tab">Supplier</a></li>
				<li><a href="view_product.php" class="stock-tab">Stocks / Products</a></li>
				<li><a href="view_report.php" class="report-tab">Reports</a></li>
			</ul>
			<a href="#" id="company-branding-small" class="fr">
				<img src="<?php echo isset($_SESSION['logo']) ? "upload/" . $_SESSION['logo'] : "upload/posnic.png"; ?>" alt="Point of Sale" />
			</a>
		</div>
	</div>
	<div id="content">
		<div class="page-full-width cf">
			<div class="side-menu fl">
				<h3>Supplier Management</h3>
				<ul>
					<li><a href="add_supplier.php">Add Supplier</a></li>
					<li><a href="view_supplier.php">View Suppliers</a></li>
				</ul>
			</div>
			<div class="side-content fr">
				<div class="content-module">
					<div class="content-module-heading cf">
						<h3 class="fl">Update Supplier</h3>
						<span class="fr expand-collapse-text">Click to collapse</span>
						<span class="fr expand-collapse-text initial-expand">Click to expand</span>
					</div>
					<div class="content-module-main cf">
						<?php
						if (isset($_GET['sid'])) {
							$id = $_GET['sid'];
							$line = $db->queryUniqueObject("SELECT * FROM supplier_details WHERE id=$id");
						?>
							<form name="form1" method="post" id="form1" action="">
								<input name="id" type="hidden" value="<?php echo $_GET['sid']; ?>">
								<table class="form" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td>Name</td>
										<td><input name="name" type="text" id="name" maxlength="200" class="round default-width-input" value="<?php echo $line->supplier_name; ?>" /></td>
										<td>Contact</td>
										<td><input name="contact1" type="text" id="contact1" maxlength="20" class="round default-width-input" value="<?php echo $line->supplier_contact1; ?>" /></td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>Address</td>
										<td><textarea name="address" cols="15" class="round full-width-textarea"><?php echo $line->supplier_address; ?></textarea></td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>
											<input class="button round blue image-right ic-add text-upper" type="submit" name="Submit" value="Save">
										</td>
										<td align="right"><input class="button round red text-upper" type="reset" name="Reset" value="Reset"></td>
									</tr>
								</table>
							</form>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>