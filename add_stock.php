<?php
include_once("init.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Add Stock</title>
	<link rel="stylesheet" href="./bootstrap.min.css">

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
	<script src="lib/auto/js/jquery.autocomplete.js"></script>

	<script>
		$(document).ready(function() {
			$("#supplier").autocomplete("supplier1.php", {
				width: 160,
				autoFill: true,
				selectFirst: true
			});
			$("#category").autocomplete("category.php", {
				width: 160,
				autoFill: true,
				selectFirst: true
			});

			$("#form1").validate({
				rules: {
					name: {
						required: true,
						minlength: 3,
						maxlength: 200
					},
					stockid: {
						required: true,
						minlength: 3,
						maxlength: 200
					},
					cost: {
						required: true
					},
					sell: {
						required: true
					}
				},
				messages: {
					name: {
						required: "Please Enter Stock Name",
						minlength: "Category Name must consist of at least 3 characters"
					},
					stockid: {
						required: "Please Enter Stock ID",
						minlength: "Category Name must consist of at least 3 characters"
					},
					sell: {
						required: "Please Enter Selling Price",
						minlength: "Category Name must consist of at least 3 characters"
					},
					cost: {
						required: "Please Enter Cost Price",
						minlength: "Category Name must consist of at least 3 characters"
					}
				}
			});
		});

		function numbersonly(e) {
			var unicode = e.charCode ? e.charCode : e.keyCode;
			if (unicode != 8 && unicode != 46 && unicode != 37 && unicode != 38 && unicode != 39 && unicode != 40 && unicode != 9) {
				if (unicode < 48 || unicode > 57)
					return false;
			}
		}
	</script>

	<script defer src="./bootstrap.bundle.min.js"></script>
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
				<li><a href="view_customers.php" class="customers-tab">Customers</a></li>
				<li><a href="view_purchase.php" class="purchase-tab">Purchase</a></li>
				<li><a href="view_supplier.php" class="supplier-tab">Supplier</a></li>
				<li><a href="view_product.php" class="active-tab stock-tab">Stocks / Products</a></li>
				<li><a href="view_payments.php" class="payment-tab">Payments / Outstandings</a></li>
				<li><a href="view_report.php" class="report-tab">Reports</a></li>
			</ul>
			<!-- end tabs -->

			<a href="#" id="company-branding-small" class="fr"><img src="<?php if (isset($_SESSION['logo'])) {
																				echo "upload/" . $_SESSION['logo'];
																			} else {
																				echo "upload/posnic.png";
																			} ?>" alt="Point of Sale" /></a>
		</div>
		<!-- end full-width -->
	</div>
	<!-- end header -->

	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="page-full-width cf">
			<div class="side-menu fl">
				<h3>Stock Management</h3>
				<ul>
					<li><a href="add_stock.php">Add Stock/Product</a></li>
					<li><a href="view_product.php">View Stock/Product</a></li>
				</ul>
			</div>
			<!-- end side-menu -->

			<div class="side-content fr">
				<div class="content-module">
					<div class="content-module-heading cf">
						<h3 class="fl">Add Stock</h3>
						<span class="fr expand-collapse-text">Click to collapse</span>
						<div style="margin-top: 15px;margin-left: 150px"></div>
						<span class="fr expand-collapse-text initial-expand">Click to expand</span>
					</div>
					<!-- end content-module-heading -->

					<div class="content-module-main cf">
						<?php
						// Gump is library for Validation

						if (isset($_POST['name'])) {
							$_POST = $gump->sanitize($_POST);
							$gump->validation_rules(array(
								'name'      => 'required|max_len,100|min_len,3',
								'stockid'   => 'required|max_len,200',
								'sell'      => 'required|max_len,200',
								'cost'      => 'required|max_len,200',
								'supplier'  => 'max_len,200',
								'category'  => 'max_len,200'
							));

							$gump->filter_rules(array(
								'name'      => 'trim|sanitize_string|mysqli_escape',
								'stockid'   => 'trim|sanitize_string|mysqli_escape',
								'sell'      => 'trim|sanitize_string|mysqli_escape',
								'cost'      => 'trim|sanitize_string|mysqli_escape',
								'category'  => 'trim|sanitize_string|mysqli_escape',
								'supplier'  => 'trim|sanitize_string|mysqli_escape'
							));

							$validated_data = $gump->run($_POST);

							if ($validated_data === false) {
								echo $gump->get_readable_errors(true);
							} else {
								$name = $validated_data['name'];
								$stockid = $validated_data['stockid'];
								$sell = $validated_data['sell'];
								$cost = $validated_data['cost'];
								$supplier = $validated_data['supplier'];
								$category = $validated_data['category'];

								$count = $db->countOf("products", "stock_name ='$name'");
								if ($count > 1) {
									$data = 'Duplicate Entry. Please Verify';
									$msg = '<p style=color:red;font-family:Georgia, Times New Roman, Times, serif>' . $data . '</p>';
						?>
									<script src="dist/js/jquery.ui.draggable.js"></script>
									<script src="dist/js/jquery.alerts.js"></script>
									<script src="dist/js/jquery.js"></script>
									<link rel="stylesheet" href="dist/js/jquery.alerts.css">
									<script type="text/javascript">
										jAlert('<?php echo $msg; ?>', 'POSNIC');
									</script>
							<?php
								} else {
									if ($db->query("INSERT INTO products(stock_id,stock_name,stock_quatity,supplier_id,company_price,selling_price,category) VALUES('$stockid','$name',0,'$supplier',$cost,$sell,'$category')")) {
										$db->query("INSERT INTO stock_avail(name,quantity) VALUES('$name',0)");
										$msg = "$name Stock Details Added";
										header("Location: add_stock.php?msg=$msg");
									} else {
										echo "<br><font color=red size=+1 >Problem in Adding!</font>";
									}
								}
							}
						}

						if (isset($_GET['msg'])) {
							$data = $_GET['msg'];
							$msg = '<p style=color:#153450;font-family:Georgia, Times New Roman, Times, serif>' . $data . '</p>';
							?>
							<script src="dist/js/jquery.ui.draggable.js"></script>
							<script src="dist/js/jquery.alerts.js"></script>
							<script src="dist/js/jquery.js"></script>
							<link rel="stylesheet" href="dist/js/jquery.alerts.css">
							<script type="text/javascript">
								jAlert('<?php echo $msg; ?>', 'POSNIC');
							</script>
						<?php
						}
						?>

						<form class="w-100" id="formAddProduct">
							<table class="form" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td><span class="man">*</span> Stock Name:</td>
									<td><input type="text" maxlength="200" class="form-control w-100" id="stock_id" /></td>
									<td><span class="man">*</span> Stock Quantity:</td>
									<td><input type="number" maxlength="200" class="form-control w-100" id="stock_quantity" /></td>
								</tr>
								<tr>
									<td><span class="man">*</span> Supplier Name:</td>
									<td><input type="text" maxlength="200" class="form-control w-100" id="supplier_name_id" /></td>
								</tr>
								<tr>
									<td>Price:</td>
									<td><input type="number" maxlength="200" class="form-control w-100" id="price_id" /></td>
									<td>Category:</td>
									<td><input type="text" maxlength="200" class="form-control w-100" id="category_id" /></td>
								</tr>
							</table>
							<button type="submit" class="btn btn-dark">Add</button>
						</form>


					</div>
					<!-- end content-module-main -->
				</div>
				<!-- end content-module -->
			</div>
			<!-- end full-width -->
		</div>
		<!-- end content -->

		<script>
			const formAddProduct = document.getElementById('formAddProduct');
			formAddProduct.addEventListener('submit', e => {
				e.preventDefault();

				// Retrieve form values
				const stock_name = document.getElementById('stock_id').value;
				const stock_quantity = document.getElementById('stock_quantity').value;
				const supplier_name = document.getElementById('supplier_name_id').value;
				const price = document.getElementById('price_id').value;
				const category = document.getElementById('category_id').value;

				// Create a JSON object with these values
				const data = JSON.stringify({
					stock_name: stock_name,
					stock_quantity: stock_quantity,
					supplier_name: supplier_name,
					price: price,
					category: category
				});

				// Create and send the XMLHttpRequest
				const xhr = new XMLHttpRequest();
				xhr.open('POST', './ajax/products/formAddProduct.php', true);
				xhr.setRequestHeader('Content-Type', 'application/json');
				xhr.onload = () => {
					console.log(xhr.responseText);
					if (xhr.responseText === '1') {
						alert('Product added successfully')
						window.location.reload()
					}
				};
				xhr.send(data);
			});
		</script>

</body>

</html>