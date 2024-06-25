<?php
include_once("init.php");

?>
<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<title>POSNIC - Add Customer</title>

	<link rel="stylesheet" href="./bootstrap.min.css">

	<!-- Stylesheets -->
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">

	<!-- Optimize for mobile devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- jQuery & JS files -->
	<?php include_once("tpl/common_js.php"); ?>
	<script src="js/script.js"></script>
	<script>
		/*$.validator.setDefaults({
		submitHandler: function() { alert("submitted!"); }
	});*/
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
				<li><a href="view_sales.php" class="sales-tab">Sales</a></li>
				<li><a href="view_customers.php" class="active-tab customers-tab">Customers</a></li>
				<li><a href="view_purchase.php" class="purchase-tab">Purchase</a></li>
				<li><a href="view_supplier.php" class=" supplier-tab">Supplier</a></li>
				<li><a href="view_product.php" class=" stock-tab">Stocks / Products</a></li>
				<li><a href="view_payments.php" class="payment-tab">Payments / Outstandings</a></li>
				<li><a href="view_report.php" class="report-tab">Reports</a></li>
			</ul> <!-- end tabs -->

			<!-- Change this image to your own company's logo -->
			<!-- The logo will automatically be resized to 30px height. -->
			<a href="#" id="company-branding-small" class="fr"><img src="<?php if (isset($_SESSION['logo'])) {
																				echo "upload/" . $_SESSION['logo'];
																			} else {
																				echo "upload/posnic.png";
																			} ?>" alt="Point of Sale" /></a>

		</div> <!-- end full-width -->

	</div> <!-- end header -->



	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="page-full-width cf">

			<div class="side-menu fl">

				<h3>Customers Management</h3>
				<ul>
					<li><a href="add_customer.php">Add Customer</a></li>
					<li><a href="view_customers.php">View Customers</a></li>
				</ul>

			</div> <!-- end side-menu -->

			<div class="side-content fr">

				<div class="content-module">

					<div class="content-module-heading cf">

						<h3 class="fl">Add Customer</h3>

					</div> <!-- end content-module-heading -->

					<div class="content-module-main cf">
						<p><strong>Add Customer Details </strong> - Add New ( Control +A)</p>

						<form id="formUpdateCustomer">

							Name
							<input type="text" id="name" maxlength="200" class="form-control" />
							Contact
							<input type="text" id="buyingrate" maxlength="20" class="form-control" />
							Address
							<textarea name="address" cols="15" class="form-control"></textarea>
							<button type="submit" class="btn btn-dark">Update</button>
						</form>



					</div> <!-- end content-module-main -->


				</div> <!-- end content-module -->



			</div> <!-- end full-width -->

		</div> <!-- end content -->


		<script>
			const formUpdateCustomer = document.getElementById('formUpdateCustomer')
			formUpdateCustomer.addEventListener('submit', e => {
				e.preventDefault()
				const xhr = new XMLHttpRequest()
				xhr.open('POST', '', true)
				xhr.onload = () => {
					console.log(xhr.responseText);
				}
				xhr.send()
			})
		</script>


</body>

</html>