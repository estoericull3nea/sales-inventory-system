<?php
include_once("init.php");

//Gump is libarary for Validatoin

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Get the data from the form
	$customer_name = $_POST['name'];
	$customer_address = $_POST['contact1'];
	$customer_contact1 = $_POST['address'];

	// Prepare an SQL statement for insertion
	$stmt = $conn->prepare("INSERT INTO customer_details (customer_name, customer_address, customer_contact1) VALUES (?, ?, ?)");

	// Bind the parameters to the SQL query
	$stmt->bind_param("sss", $customer_name, $customer_address, $customer_contact1);

	// Execute the statement
	if ($stmt->execute()) {
		$message = "New record created successfully";
	} else {
		$message = "Error: " . $stmt->error;
	}


	// Close the statement
	$stmt->close();
}
?>
<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<title> Add Customer</title>

	<!-- Stylesheets -->
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">

	<!-- Optimize for mobile devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<script type="text/javascript">
		function showAlert(message) {
			if (message) {
				alert(message);
			}
		}
	</script>
	<style type="text/css">
		body {
			margin-left: 0px;
			margin-top: 0px;
			margin-right: 0px;
			margin-bottom: 0px;
			background-color: #FFFFFF;
		}

		* {
			padding: 0px;
			margin: 0px;
		}

		#vertmenu {
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 100%;
			width: 160px;
			padding: 0px;
			margin: 0px;
		}

		#vertmenu h1 {
			display: block;
			background-color: #FF9900;
			font-size: 90%;
			padding: 3px 0 5px 3px;
			border: 1px solid #000000;
			color: #333333;
			margin: 0px;
			width: 159px;
		}

		#vertmenu ul {
			list-style: none;
			margin: 0px;
			padding: 0px;
			border: none;
		}

		#vertmenu ul li {
			margin: 0px;
			padding: 0px;
		}

		#vertmenu ul li a {
			font-size: 80%;
			display: block;
			border-bottom: 1px dashed #C39C4E;
			padding: 5px 0px 2px 4px;
			text-decoration: none;
			color: #666666;
			width: 160px;
		}

		#vertmenu ul li a:hover,
		#vertmenu ul li a:focus {
			color: #000000;
			background-color: #eeeeee;
		}

		.style1 {
			color: #000000
		}

		div.pagination {

			padding: 3px;

			margin: 3px;

		}



		div.pagination a {

			padding: 2px 5px 2px 5px;

			margin: 2px;

			border: 1px solid #AAAADD;



			text-decoration: none;
			/* no underline */

			color: #000099;

		}

		div.pagination a:hover,
		div.pagination a:active {

			border: 1px solid #000099;



			color: #000;

		}

		div.pagination span.current {

			padding: 2px 5px 2px 5px;

			margin: 2px;

			border: 1px solid #000099;



			font-weight: bold;

			background-color: #000099;

			color: #FFF;

		}

		div.pagination span.disabled {

			padding: 2px 5px 2px 5px;

			margin: 2px;

			border: 1px solid #EEE;



			color: #DDD;

		}
	</style>
	<!-- jQuery & JS files -->
	<?php include_once("tpl/common_js.php"); ?>
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
						required: "Please enter a Customer Name",
						minlength: "Customer must consist of at least 3 characters"
					},
					address: {
						minlength: "Customer Address must be at least 3 characters long",
						maxlength: "Customer Address must be at least 3 characters long"
					}
				}
			});

		});
	</script>

</head>

<body onload="showAlert('<?php echo $message; ?>')">

	<!-- TOP BAR -->
	<?php include_once("tpl/top_bar.php"); ?>
	<!-- end top-bar -->

	<!-- HEADER -->
	<div id="header-with-tabs">

		<div class="page-full-width cf">

			<ul id="tabs" class="fl">
				<li><a href="dashboard.php" class="dashboard-tab">Dashboard</a></li>
				<li><a href="view_sales.php" class=" sales-tab">Sales</a></li>
				<li><a href="view_customers.php" class="active-tab customers-tab">Customers</a></li>
				<li><a href="view_supplier.php" class="  supplier-tab">Supplier</a></li>
				<li><a href="view_product.php" class="stock-tab">Stocks / Products</a></li>
				<li><a href="view_report.php" class="report-tab">Reports</a></li>
			</ul> <!-- end tabs -->



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
				<div style="background: #ffffff">
					<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- posnic 120x90 vertical small -->
					<ins class="adsbygoogle" style="display:inline-block;width:120px;height:90px" data-ad-client="ca-pub-5212135413309920" data-ad-slot="3677012951"></ins>
					<script>
						(adsbygoogle = window.adsbygoogle || []).push({});
					</script>

				</div>
			</div> <!-- end side-menu -->

			<div class="side-content fr">

				<div class="content-module">

					<div class="content-module-heading cf">

						<h3 class="fl">Add Customer</h3>
						<span class="fr expand-collapse-text">Click to collapse</span>
						<span class="fr expand-collapse-text initial-expand">Click to expand</span>

					</div> <!-- end content-module-heading -->

					<div class="content-module-main cf">


						<?php


						//Gump is libarary for Validatoin
						if (isset($_GET['msg'])) {
							$data = $_GET['msg'];
							$msg = '<p style=color:#153450;font-family:gfont-family:Georgia, Times New Roman, Times, serif>' . $data . '</p>'; //
						?>

							<script src="dist/js/jquery.ui.draggable.js"></script>
							<script src="dist/js/jquery.alerts.js"></script>
							<script src="dist/js/jquery.js"></script>
							<link rel="stylesheet" href="dist/js/jquery.alerts.css">

							<script type="text/javascript">
								jAlert('<?php echo  $msg; ?>', 'POSNIC');
							</script>
						<?php
						}
						?>
						<form name="form1" method="post" id="form1" action="">

							<p><strong>Add Customer Details </strong></p>
							<table class="form" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td><span class="man">*</span>Name:</td>
									<td><input name="name" placeholder="Enter full name..." type="text" id="name" maxlength="200" class="round default-width-input" value="<?php echo isset($name) ? $name : ''; ?>" /></td>
									<td>Contact</td>
									<td><input name="contact1" placeholder="Enter contact..." type="text" id="buyingrate" maxlength="20" class="round default-width-input" value="<?php echo isset($contact1) ? $contact1 : ''; ?>" /></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>Address</td>
									<td><textarea name="address" placeholder="Enter address..." cols="15" class="round full-width-textarea"><?php echo isset($address) ? $address : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>
										<input class="button round blue image-right ic-add text-upper" type="submit" name="Submit" value="Add">
									</td>
									<td>&nbsp;</td>
									<td align="right"><input class="button round red text-upper" type="reset" name="Reset" value="Reset"> </td>
								</tr>
							</table>
						</form>



					</div> <!-- end content-module-main -->


				</div> <!-- end content-module -->



			</div> <!-- end full-width -->

		</div> <!-- end content -->





</body>

</html>