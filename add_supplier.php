<?php
include_once("init.php");

?>
<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Add Supplier</title>

	<!-- Stylesheets -->
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="js/date_pic/date_input.css">
	<link rel="stylesheet" href="lib/auto/css/jquery.autocomplete.css">

	<!-- Optimize for mobile devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- jQuery & JS files -->
	<?php include_once("tpl/common_js.php"); ?>
	<script src="js/script.js"></script>
	<script src="js/date_pic/jquery.date_input.js"></script>
	<script src="lib/auto/js/jquery.autocomplete.js "></script>
	<script src="js/script.js"></script>
	<script>
		/*$.validator.setDefaults({
		submitHandler: function() { alert("submitted!"); }
	});*/
		$(document).ready(function() {

			// validate signup form on keyup and submit
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
						required: "Please enter a supplier Name",
						minlength: "Supplier must consist of at least 3 characters"
					},
					address: {
						minlength: "Supplier Address must be at least 3 characters long",
						maxlength: "Supplier Address must be at least 3 characters long"
					}
				}
			});

		});
	</script>

</head>

<body>

	<!-- TOP BAR -->
	<?php include_once("tpl/top_bar.php"); ?>
	<!-- end top-bar -->



	<!-- HEADER -->
	<div id="header-with-tabs">

		<div class="page-full-width cf">

			<ul id="tabs" class="fl">
				<li><a href="dashboard.php" class="dashboard-tab">Dashboard</a></li>
				<li><a href="view_sales.php" class=" sales-tab">Sales</a></li>
				<li><a href="view_customers.php" class="customers-tab">Customers</a></li>
				<li><a href="view_supplier.php" class="active-tab   supplier-tab">Supplier</a></li>
				<li><a href="view_product.php" class="stock-tab">Stocks / Products</a></li>
				<li><a href="view_report.php" class="report-tab">Reports</a></li>
			</ul> <!-- end tabs -->

			<!-- Change this image to your own company's logo -->
			<!-- The logo will automatically be resized to 30px height. -->


		</div> <!-- end full-width -->

	</div> <!-- end header -->



	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="page-full-width cf">

			<div class="side-menu fl">

				<h3>supplier Management</h3>
				<ul>
					<li><a href="add_supplier.php">Add Supplier</a></li>
					<li><a href="view_supplier.php">View Supplier</a></li>
				</ul>

			</div> <!-- end side-menu -->

			<div class="side-content fr">

				<div class="content-module">

					<div class="content-module-heading cf">

						<h3 class="fl">Add supplier</h3>
						<span class="fr expand-collapse-text">Click to collapse</span>
						<span class="fr expand-collapse-text initial-expand">Click to expand</span>

					</div> <!-- end content-module-heading -->

					<div class="content-module-main cf">


						<form id="form1">
							<p><strong>Add Supplier Details </strong></p>
							<table class="form" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td><span class="man">*</span>Name:</td>
									<td><input name="name" placeholder="ENTER YOUR FULL NAME" type="text" id="name" maxlength="200" class="round default-width-input" required /></td>
									<td>Contact</td>
									<td><input name="contact" placeholder="ENTER YOUR CONTACT" type="text" id="contact" maxlength="20" class="round default-width-input" required /></td>
								</tr>
								<tr>
									<td>Address</td>
									<td><textarea name="address" placeholder="ENTER YOUR ADDRESS" cols="8" class="round full-width-textarea" required></textarea></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>
										<input class="button round blue image-right ic-add text-upper" type="submit" name="Submit" value="Add">
									<td align="right"><input class="button round red text-upper" type="reset" name="Reset" value="Reset"> </td>
								</tr>
							</table>
						</form>


					</div> <!-- end content-module-main -->


				</div> <!-- end content-module -->



			</div> <!-- end full-width -->

		</div> <!-- end content -->



		<script>
			const form1 = document.getElementById('form1');
			form1.addEventListener('submit', e => {
				e.preventDefault();

				const formData = new FormData(form1);
				const json = {};

				formData.forEach((value, key) => {
					json[key] = value;
				});


				const xhr = new XMLHttpRequest();
				xhr.open('POST', './ajax/supplier/addSupplier.php', true);
				xhr.setRequestHeader('Content-Type', 'application/json');
				xhr.onload = () => {
					console.log(xhr.responseText);
					if (xhr.responseText === '1') {
						alert('Supplier added successfully')
						window.location.reload()
					}
				};
				xhr.send(JSON.stringify(json));
			});
		</script>


</body>

</html>