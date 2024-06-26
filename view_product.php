<?php
include_once("init.php");
require './connection.php';
?>
<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Stock</title>

	<link rel="stylesheet" href="./bootstrap.min.css">


	<!-- Stylesheets -->
	<!--<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet'>-->
	<link rel="stylesheet" href="css/style.css">

	<!-- Optimize for mobile devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- jQuery & JS files -->
	<?php include_once("tpl/common_js.php"); ?>
	<script src="js/script.js"></script>
	<script src="dist/js/jquery.ui.draggable.js"></script>
	<script src="dist/js/jquery.alerts.js"></script>
	<link rel="stylesheet" href="dist/js/jquery.alerts.css">



	<script>
		function confirmSubmit(id, table, dreturn) {
			jConfirm('You Want Delete Product', 'Confirmation Dialog', function(r) {
				if (r) {
					console.log();
					$.ajax({
						url: "delete.php",
						data: {
							id: id,
							table: table,
							return: dreturn
						},
						success: function(data) {
							window.location = 'view_product.php';
							jAlert('Product Is Deleted', 'POSNIC');
						}
					});
				}
				return r;
			});
		}

		function confirmDeleteSubmit() {
			var flag = 0;
			var field = document.forms.deletefiles;
			for (i = 0; i < field.length; i++) {
				if (field[i].checked == true) {
					flag = flag + 1;
				}
			}
			if (flag < 1) {
				jAlert('You must check one and only one checkbox', 'POSNIC');
				return false;
			} else {
				jConfirm('You Want Delete Product', 'Confirmation Dialog', function(r) {
					if (r) {
						document.deletefiles.submit();
					} else {
						return false;
					}
				});
			}
		}

		function confirmLimitSubmit() {
			if (document.getElementById('search_limit').value != "") {
				document.limit_go.submit();
			} else {
				return false;
			}
		}

		function checkAll() {
			var field = document.forms.deletefiles;
			for (i = 0; i < field.length; i++)
				field[i].checked = true;
		}

		function uncheckAll() {
			var field = document.forms.deletefiles;
			for (i = 0; i < field.length; i++)
				field[i].checked = false;
		}
	</script>
	<script>
		$(document).ready(function() {
			$("#form1").valicreated_at({
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
						minlength: "supplier must consist of at least 3 characters"
					},
					address: {
						minlength: "supplier Address must be at least 3 characters long",
						maxlength: "supplier Address must be at least 3 characters long"
					}
				}
			});
		});
	</script>

	<script defer src="./bootstrap.bundle.min.js"></script>

</head>

<body>


	<!-- Modal -->
	<div class="modal fade" id="showSingleModal" tabindex="-1" aria-labelledby="showSingleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="showSingleModalLabel">Modal title</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="formEditData">
						<div class="mb-3">
							<label class="form-label">Stock Name</label>
							<input type="text" class="form-control" required id="stock_name_id">
						</div>
						<div class="mb-3">
							<label class="form-label">Stock Quantity</label>
							<input type="number" min="1" max="100" class="form-control" required id="stock_q_id">
						</div>
						<div class="mb-3">
							<label class="form-label">Supplier Name</label>
							<input type="text" class="form-control" required id="supp_name_id">
						</div>
						<div class="mb-3">
							<label class="form-label">Price</label>
							<input type="number" class="form-control" required id="price_id">
						</div>
						<div class="mb-3">
							<label class="form-label">Category</label>
							<input type="text" class="form-control" required id="category_id">
						</div>
						<input type="hidden" id="id_id">
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-dark">Update</button>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>


	<!-- TOP BAR -->
	<?php include_once("tpl/top_bar.php"); ?>
	<!-- end top-bar -->

	<!-- HEADER -->
	<div id="header-with-tabs">
		<div class="page-full-width cf">
			<ul id="tabs" class="fl">
				<li><a href="dashboard.php" class="dashboard-tab">Dashboard</a></li>
				<li><a href="view_sales.php" class="sales-tab">Sales</a></li>
				<li><a href="view_customers.php" class=" customers-tab">Customers</a></li>
				<li><a href="view_supplier.php" class=" supplier-tab">Supplier</a></li>
				<li><a href="view_product.php" class="active-tab stock-tab">Stocks / Products</a></li>
				<li><a href="view_report.php" class="report-tab">Reports</a></li>
			</ul>

		</div>
	</div>

	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="page-full-width cf">
			<div class="side-menu fl">
				<h3>Stock Management</h3>
				<ul>
					<li><a href="add_stock.php">Add Stock/Product</a></li>
					<li><a href="view_product.php">View Stock/Product</a></li>
				</ul>
				<div style="background: #ffffff">
					<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<ins class="adsbygoogle" style="display:inline-block;width:120px;height:90px" data-ad-client="ca-pub-5212135413309920" data-ad-slot="3677012951"></ins>
					<script>
						(adsbygoogle = window.adsbygoogle || []).push({});
					</script>
				</div>
			</div>

			<div class="side-content fr">
				<div class="content-module">
					<div class="content-module-heading cf">
						<h3 class="fl">Stock/Product</h3>
						<span class="fr expand-collapse-text">Click to collapse</span>
						<span class="fr expand-collapse-text initial-expand">Click to expand</span>
					</div>

					<div class="content-module-main cf">
						<table>
							<form action="" method="post" name="search">
								<input name="searchtxt" type="text" class="round my_text_box" placeholder="Search">
								&nbsp;&nbsp;<input name="Search" type="submit" class="my_button round blue text-upper" value="Search">
							</form>
							<form action="" method="get" name="limit_go">
								Page per Record<input name="limit" type="text" class="round my_text_box" id="search_limit" style="margin-left:5px;" value="<?php if (isset($_GET['limit'])) echo $_GET['limit'];
																																							else echo "10"; ?>" size="3" maxlength="3">
								<input name="go" type="button" value="Go" class=" round blue my_button text-upper" onclick="return confirmLimitSubmit()">
							</form>

							<form name="deletefiles" action="delete.php" method="post">
								<input type="hidden" name="table" value="products">
								<input type="hidden" name="return" value="view_product.php">
								<input type="button" name="selectall" value="SelectAll" class="my_button round blue text-upper" onClick="checkAll()" style="margin-left:5px;" />
								<input type="button" name="unselectall" value="DeSelectAll" class="my_button round blue text-upper" onClick="uncheckAll()" style="margin-left:5px;" />
								<input name="dsubmit" type="button" value="Delete Selected" class="my_button round blue text-upper" style="margin-left:5px;" onclick="return confirmDeleteSubmit()" />

								<table>
									<?php
									// Debugging: Check if connection is successful
									if ($conn->connect_error) {
										die("Connection failed: " . $conn->connect_error);
									}

									$SQL = "SELECT * FROM products";
									if (isset($_POST['Search']) && trim($_POST['searchtxt']) != "") {
										$SQL = "SELECT * FROM products WHERE stock_name LIKE '%" . $_POST['searchtxt'] . "%' OR supplier_address LIKE '%" . $_POST['searchtxt'] . "%' OR supplier_name LIKE '%" . $_POST['searchtxt'] . "%' OR created_at LIKE '%" . $_POST['searchtxt'] . "%'";
									}

									$tbl_name = "products";

									$adjacents = 3;

									$query = "SELECT COUNT(*) as num FROM $tbl_name";
									if (isset($_POST['Search']) && trim($_POST['searchtxt']) != "") {
										$query = "SELECT COUNT(*) as num FROM products WHERE stock_name LIKE '%" . $_POST['searchtxt'] . "%' OR id LIKE '%" . $_POST['searchtxt'] . "%' OR supplier_name LIKE '%" . $_POST['searchtxt'] . "%' OR created_at LIKE '%" . $_POST['searchtxt'] . "%'";
									}

									$result = mysqli_query($conn, $query);
									if (!$result) {
										die("Query failed: " . mysqli_error($conn)); // Debugging: Display SQL error
									}

									$total_pages = mysqli_fetch_array($result);
									$total_pages = $total_pages['num'];

									$targetpage = "view_product.php";
									$limit = 10;
									if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
										$limit = $_GET['limit'];
									}

									$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
									$start = ($page - 1) * $limit;

									$sql = "SELECT * FROM products LIMIT $start, $limit";
									if (isset($_POST['Search']) && trim($_POST['searchtxt']) != "") {
										$sql = "SELECT * FROM products WHERE stock_name LIKE '%" . $_POST['searchtxt'] . "%' OR id LIKE '%" . $_POST['searchtxt'] . "%' OR supplier_name LIKE '%" . $_POST['searchtxt'] . "%' OR created_at LIKE '%" . $_POST['searchtxt'] . "%' LIMIT $start, $limit";
									}

									$result = mysqli_query($conn, $sql);
									if (!$result) {
										die("Query failed: " . mysqli_error($conn)); // Debugging: Display SQL error
									}

									if ($page == 0) $page = 1;
									$prev = $page - 1;
									$next = $page + 1;
									$lastpage = ceil($total_pages / $limit);
									$lpm1 = $lastpage - 1;

									$pagination = "";
									if ($lastpage > 1) {
										$pagination .= "<div>";
										if ($page > 1)
											$pagination .= "<a href=\"view_product.php?page=$prev&limit=$limit\" class=my_pagination>Previous</a>";
										else
											$pagination .= "<span class=my_pagination>Previous</span>";

										if ($lastpage < 7 + ($adjacents * 2)) {
											for ($counter = 1; $counter <= $lastpage; $counter++) {
												if ($counter == $page)
													$pagination .= "<span class=my_pagination>$counter</span>";
												else
													$pagination .= "<a href=\"view_product.php?page=$counter&limit=$limit\" class=my_pagination>$counter</a>";
											}
										} elseif ($lastpage > 5 + ($adjacents * 2)) {
											if ($page < 1 + ($adjacents * 2)) {
												for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
													if ($counter == $page)
														$pagination .= "<span class=my_pagination>$counter</span>";
													else
														$pagination .= "<a href=\"view_product.php?page=$counter&limit=$limit\" class=my_pagination>$counter</a>";
												}
												$pagination .= "...";
												$pagination .= "<a href=\"view_product.php?page=$lpm1&limit=$limit\" class=my_pagination>$lpm1</a>";
												$pagination .= "<a href=\"view_product.php?page=$lastpage&limit=$limit\" class=my_pagination>$lastpage</a>";
											} elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
												$pagination .= "<a href=\"view_product.php?page=1&limit=$limit\" class=my_pagination>1</a>";
												$pagination .= "<a href=\"view_product.php?page=2&limit=$limit\" class=my_pagination>2</a>";
												$pagination .= "...";
												for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
													if ($counter == $page)
														$pagination .= "<span class=my_pagination>$counter</span>";
													else
														$pagination .= "<a href=\"view_product.php?page=$counter&limit=$limit\" class=my_pagination>$counter</a>";
												}
												$pagination .= "...";
												$pagination .= "<a href=\"view_product.php?page=$lpm1&limit=$limit\" class=my_pagination>$lpm1</a>";
												$pagination .= "<a href=\"view_product.php?page=$lastpage&limit=$limit\" class=my_pagination>$lastpage</a>";
											} else {
												$pagination .= "<a href=\"$view_product.php?page=1&limit=$limit\" class=my_pagination>1</a>";
												$pagination .= "<a href=\"$view_product.php?page=2&limit=$limit\" class=my_pagination>2</a>";
												$pagination .= "...";
												for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
													if ($counter == $page)
														$pagination .= "<span class=my_pagination>$counter</span>";
													else
														$pagination .= "<a href=\"$targetpage?page=$counter&limit=$limit\" class=my_pagination>$counter</a>";
												}
											}
										}
										if ($page < $counter - 1)
											$pagination .= "<a href=\"view_product.php?page=$next&limit=$limit\" class=my_pagination>Next</a>";
										else
											$pagination .= "<span class=my_pagination>Next</span>";
										$pagination .= "</div>\n";
									}
									?>
									<tr>
										<th>Stock Name</th>
										<th>Stock Id</th>
										<th>created_at</th>
										<th>Supplier Name</th>
										<th>Price</th>
										<th>Stock</th>
										<th>Edit /Delete</th>
										<th>Select</th>
									</tr>

									<?php
									$i = 1;
									$no = $page - 1;
									$no = $no * $limit;
									while ($row = mysqli_fetch_array($result)) {
									?>
										<tr>
											<td><?php echo !empty($row['stock_name']) ? $row['stock_name'] : 'N/A'; ?></td>
											<td><?php echo !empty($row['id']) ? $row['id'] : 'N/A'; ?></td>
											<td><?php echo !empty($row['created_at']) ? $row['created_at'] : 'N/A'; ?></td>
											<td><?php echo !empty($row['supplier_name']) ? $row['supplier_name'] : 'N/A'; ?></td>
											<td><?php echo !empty($row['price']) ? $row['price'] : 'N/A'; ?></td>
											<td><?php echo !empty($row['stock_quantity']) ? $row['stock_quantity'] : 'N/A'; ?></td>
											<td>
												<button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#showSingleModal" onclick="getSingleProduct(<?php echo $row['id']; ?>)">Edit</button>
												<button type="button" class="btn btn-sm btn-dark" onclick="deleteSingleProduct(<?php echo $row['id']; ?>)">Delete</button>
											</td>
											<td><input type="checkbox" value="<?php echo $row['id']; ?>" name="checklist[]" id="check_box" /></td>
										</tr>


									<?php
										$i++;
									}
									?>
									<tr>
										<td align="center" colspan="9">
											<div style="margin-left:20px;"><?php echo $pagination; ?></div>
										</td>
									</tr>
								</table>
							</form>
					</div>
				</div>
				<div id="footer">
					<p> &copy;Copyright 2013</p>
				</div>
			</div>
			<script>
				const getSingleProduct = (id) => {
					const xhr = new XMLHttpRequest();
					xhr.open('POST', './ajax/products/get_single_using_id.php', true);
					xhr.setRequestHeader('Content-Type', 'application/json');
					xhr.onload = () => {
						const data = JSON.parse(xhr.responseText)
						document.getElementById('stock_name_id').value = `${data.stock_name}`
						document.getElementById('stock_q_id').value = `${data.stock_quatity}`
						document.getElementById('supp_name_id').value = `${data.supplier_name}`
						document.getElementById('price_id').value = `${data.price}`
						document.getElementById('category_id').value = `${data.category}`
						document.getElementById('id_id').value = `${data.id}`
					};
					const data = JSON.stringify({
						id: id
					});
					xhr.send(data);
				};
				const deleteSingleProduct = (id) => {
					const xhr = new XMLHttpRequest();
					xhr.open('POST', './ajax/products/deleteSingleProduct.php', true);
					xhr.setRequestHeader('Content-Type', 'application/json');
					xhr.onload = () => {
						console.log(xhr.responseText);
						if (xhr.responseText === '1') {
							alert('Product deleted successfully')
							window.location.reload()
						}
					};
					const data = JSON.stringify({
						id: id
					});
					xhr.send(data);
				}

				const formEditData = document.getElementById('formEditData');
				formEditData.addEventListener('submit', e => {
					e.preventDefault();

					// Retrieve form values
					const stock_name = document.getElementById('stock_name_id').value;
					const stock_quantity = document.getElementById('stock_q_id').value;
					const supplier_name = document.getElementById('supp_name_id').value;
					const price = document.getElementById('price_id').value;
					const category = document.getElementById('category_id').value;
					const id = document.getElementById('id_id').value;

					// Create a JSON object with these values
					const data = JSON.stringify({
						id: id,
						stock_name: stock_name,
						stock_quantity: stock_quantity,
						supplier_name: supplier_name,
						price: price,
						category: category
					});

					// Create and send the XMLHttpRequest
					const xhr = new XMLHttpRequest();
					xhr.open('POST', './ajax/products/updateSingleProduct.php', true);
					xhr.setRequestHeader('Content-Type', 'application/json');
					xhr.onload = () => {
						if (xhr.responseText === '1') {
							alert('Product updated successfully')
							window.location.reload()
						}
					};
					xhr.send(data);
				});
			</script>

</body>

</html>