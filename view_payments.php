<?php
include_once("init.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Payment</title>

	<!-- Stylesheets -->
	<link rel="stylesheet" href="css/style.css">

	<!-- Optimize for mobile devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- jQuery & JS files -->
	<?php include_once("tpl/common_js.php"); ?>
	<script src="js/script.js"></script>

	<script>
		function confirmSubmit() {
			return confirm("Are you sure you wish to Delete this Entry?");
		}

		function confirmDeleteSubmit() {
			var flag = 0;
			var field = document.forms.deletefiles;
			for (i = 0; i < field.length; i++) {
				if (field[i].checked == true) {
					flag++;
				}
			}
			if (flag < 1) {
				alert("You must check at least one checkbox!");
				return false;
			} else {
				return confirm("Are you sure you wish to Delete Selected Record?");
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
				<li><a href="view_sales.php" class="sales-tab">Sales</a></li>
				<li><a href="view_customers.php" class="customers-tab">Customers</a></li>
				<li><a href="view_supplier.php" class="supplier-tab">Supplier</a></li>
				<li><a href="view_product.php" class="stock-tab">Stocks / Products</a></li>
				<li><a href="view_payments.php" class="active-tab payment-tab">Payments / Outstandings</a></li>
				<li><a href="view_report.php" class="report-tab">Reports</a></li>
			</ul>
			<!-- end tabs -->

			<!-- Change this image to your own company's logo -->
			<a href="#" id="company-branding-small" class="fr"><img src="<?php echo isset($_SESSION['logo']) ? "upload/" . $_SESSION['logo'] : "upload/posnic.png"; ?>" alt="Point of Sale" /></a>
		</div>
		<!-- end full-width -->
	</div>
	<!-- end header -->

	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="page-full-width cf">
			<div class="side-menu fl">
				<h3>Payment</h3>
				<ul>
					<li><a href="view_payments.php">Payments</a></li>
				</ul>
			</div>
			<!-- end side-menu -->

			<div class="side-content fr">
				<div class="content-module">
					<div class="content-module-heading cf">
						<h3 class="fl">Payment</h3>
						<span class="fr expand-collapse-text">Click to collapse</span>
						<span class="fr expand-collapse-text initial-expand">Click to expand</span>
					</div>
					<!-- end content-module-heading -->

					<div class="content-module-main cf">
						<form action="" method="post" name="search">
							<input name="searchtxt" type="text" class="round my_text_box" placeholder="Search">
							&nbsp;&nbsp;<input name="Search" type="submit" class="my_button round blue text-upper" value="Search">
						</form>
						<form action="" method="get" name="limit_go">
							Page per Record
							<input name="limit" type="text" class="round my_text_box" id="search_limit" style="margin-left:5px;" value="<?php echo isset($_GET['limit']) ? $_GET['limit'] : "10"; ?>" size="3" maxlength="3">
							<input name="go" type="button" value="Go" class="round blue my_button text-upper" onclick="return confirmLimitSubmit()">
						</form>

						<form name="deletefiles" action="delete.php" method="post">
							<table>
								<?php
								$SQL = "SELECT DISTINCT(transactionid) FROM stock_sales WHERE balance > 0";
								if (isset($_POST['Search']) && trim($_POST['searchtxt']) != "") {
									$SQL = "SELECT DISTINCT(transactionid) FROM stock_sales WHERE (stock_name LIKE '%" . $_POST['searchtxt'] . "%' OR supplier_name LIKE '%" . $_POST['searchtxt'] . "%' OR transactionid LIKE '%" . $_POST['searchtxt'] . "%' OR date LIKE '%" . $_POST['searchtxt'] . "%') AND balance > 0";
								}

								$tbl_name = "stock_sales";
								$adjacents = 3;

								$query = "SELECT COUNT(*) as num FROM $tbl_name WHERE balance > 0";
								if (isset($_POST['Search']) && trim($_POST['searchtxt']) != "") {
									$query = "SELECT COUNT(*) as num FROM stock_sales WHERE (stock_name LIKE '%" . $_POST['searchtxt'] . "%' OR supplier_name LIKE '%" . $_POST['searchtxt'] . "%' OR transactionid LIKE '%" . $_POST['searchtxt'] . "%' OR date LIKE '%" . $_POST['searchtxt'] . "%') AND balance > 0";
								}
								$total_pages = mysqli_fetch_array(mysqli_query($conn, $query));
								$total_pages = $total_pages['num'];

								$targetpage = "view_stock_sales_payments.php";
								$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
								$page = isset($_GET['page']) ? $_GET['page'] : 1;
								$start = ($page - 1) * $limit;

								$sql = "SELECT DISTINCT(transactionid) FROM stock_sales WHERE balance > 0 ORDER BY date DESC LIMIT $start, $limit";
								if (isset($_POST['Search']) && trim($_POST['searchtxt']) != "") {
									$sql = "SELECT DISTINCT(transactionid) FROM stock_sales WHERE (stock_name LIKE '%" . $_POST['searchtxt'] . "%' OR supplier_name LIKE '%" . $_POST['searchtxt'] . "%' OR transactionid LIKE '%" . $_POST['searchtxt'] . "%' OR date LIKE '%" . $_POST['searchtxt'] . "%') ORDER BY date DESC LIMIT $start, $limit";
								}
								$result = mysqli_query($conn, $sql);

								$lastpage = ceil($total_pages / $limit);

								$pagination = "";
								if ($lastpage > 1) {
									$pagination .= "<div>";
									if ($page > 1)
										$pagination .= "<a href=\"view_payments.php?page=" . ($page - 1) . "&limit=$limit\" class=my_pagination>Previous</a>";
									else
										$pagination .= "<span class=my_pagination>Previous</span>";
									if ($lastpage < 7 + ($adjacents * 2)) {
										for ($counter = 1; $counter <= $lastpage; $counter++) {
											if ($counter == $page)
												$pagination .= "<span class=my_pagination>$counter</span>";
											else
												$pagination .= "<a href=\"view_payments.php?page=$counter&limit=$limit\" class=my_pagination>$counter</a>";
										}
									} elseif ($lastpage > 5 + ($adjacents * 2)) {
										if ($page < 1 + ($adjacents * 2)) {
											for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
												if ($counter == $page)
													$pagination .= "<span class=my_pagination>$counter</span>";
												else
													$pagination .= "<a href=\"view_payments.php?page=$counter&limit=$limit\" class=my_pagination>$counter</a>";
											}
											$pagination .= "...";
											$pagination .= "<a href=\"view_payments.php?page=$lastpage&limit=$limit\" class=my_pagination>$lastpage</a>";
										} elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
											$pagination .= "<a href=\"view_payments.php?page=1&limit=$limit\" class=my_pagination>1</a>";
											$pagination .= "<a href=\"view_payments.php?page=2&limit=$limit\" class=my_pagination>2</a>";
											$pagination .= "...";
											for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
												if ($counter == $page)
													$pagination .= "<span class=my_pagination>$counter</span>";
												else
													$pagination .= "<a href=\"view_payments.php?page=$counter&limit=$limit\" class=my_pagination>$counter</a>";
											}
											$pagination .= "...";
											$pagination .= "<a href=\"view_payments.php?page=$lastpage&limit=$limit\" class=my_pagination>$lastpage</a>";
										} else {
											$pagination .= "<a href=\"view_payments.php?page=1&limit=$limit\" class=my_pagination>1</a>";
											$pagination .= "<a href=\"view_payments.php?page=2&limit=$limit\" class=my_pagination>2</a>";
											$pagination .= "...";
											for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
												if ($counter == $page)
													$pagination .= "<span class=my_pagination>$counter</span>";
												else
													$pagination .= "<a href=\"view_payments.php?page=$counter&limit=$limit\" class=my_pagination>$counter</a>";
											}
										}
									}
									if ($page < $counter - 1)
										$pagination .= "<a href=\"view_payments.php?page=" . ($page + 1) . "&limit=$limit\" class=my_pagination>Next</a>";
									else
										$pagination .= "<span class=my_pagination>Next</span>";
									$pagination .= "</div>\n";
								}
								?>
								<tr>
									<th>No</th>
									<th>Customer Name</th>
									<th>Due Date</th>
									<th>Subtotal</th>
									<th>Payment</th>
									<th>Balance</th>
									<th>Add Payment</th>
								</tr>
								<?php $i = 1;
								$no = $page - 1;
								$no = $no * $limit;
								while ($row = mysqli_fetch_array($result)) {
									$entryid = $row['transactionid'];
									$line = $db->queryUniqueObject("SELECT * FROM stock_sales WHERE transactionid='$entryid'");
									$mysqldate = $line->due;
									$phpdate = strtotime($mysqldate);
									$phpdate = date("d/m/Y", $phpdate);
								?>
									<tr>
										<td><?php echo $no + $i; ?></td>
										<td width="100"><?php echo $line->customer_id; ?></td>
										<td width="100"><?php echo $phpdate; ?></td>
										<td width="100"><?php echo $line->subtotal; ?></td>
										<td width="100"><?php echo $line->payment; ?></td>
										<td width="100"><?php echo $line->balance; ?></td>
										<td><a href="update_payment.php?sid=<?php echo $line->transactionid; ?>&table=stock_entries&return=view_payments.php">Pay now</a></td>
									</tr>
								<?php $i++;
								} ?>
								<tr>
									<td align="center">
										<div style="margin-left:20px;"><?php echo $pagination; ?></div>
									</td>
								</tr>
							</table>
						</form>
					</div>
					<!-- end content-module-main -->
				</div>
				<!-- end content-module -->
			</div>
			<!-- end full-width -->
		</div>
		<!-- end content -->

		<!-- FOOTER -->
		<div id="footer">
			<p>&copy;Copyright 2013</p>
		</div>
		<!-- end footer -->
</body>

</html>