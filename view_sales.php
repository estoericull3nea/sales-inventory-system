<?php
include_once("init.php");

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 10;

// Function to handle SQL queries safely
function safe_query($conn, $query)
{
	$result = mysqli_query($conn, $query);
	if (!$result) {
		die("Database query failed: " . mysqli_error($conn));
	}
	return $result;
}

// Fetch total number of records
$searchtxt = isset($_POST['searchtxt']) ? trim($_POST['searchtxt']) : '';
$searchQuery = $searchtxt ? "WHERE stock_name LIKE '%" . mysqli_real_escape_string($conn, $searchtxt) . "%'" : '';
$countQuery = "SELECT COUNT(DISTINCT id) as num FROM stock_entries $searchQuery";
$total_pages = mysqli_fetch_assoc(safe_query($conn, $countQuery))['num'];

// Calculate pagination variables
$start = ($page - 1) * $limit;
$lastpage = ceil($total_pages / $limit);
$prev = $page - 1;
$next = $page + 1;

// Fetch sales records
$sql = "SELECT * FROM stock_entries $searchQuery ORDER BY created_at DESC LIMIT $start, $limit";
$result = safe_query($conn, $sql);

// Pagination logic
$adjacents = 3;
$pagination = "";

if ($lastpage > 1) {
	$pagination .= "<div>";
	if ($page > 1) $pagination .= "<a href=\"view_sales.php?page=$prev&limit=$limit\" class='my_pagination'>Previous</a>";
	else $pagination .= "<span class='my_pagination'>Previous</span>";

	if ($lastpage < 7 + ($adjacents * 2)) {
		for ($counter = 1; $counter <= $lastpage; $counter++) {
			if ($counter == $page) $pagination .= "<span class='my_pagination'>$counter</span>";
			else $pagination .= "<a href=\"view_sales.php?page=$counter&limit=$limit\" class='my_pagination'>$counter</a>";
		}
	} elseif ($lastpage > 5 + ($adjacents * 2)) {
		if ($page < 1 + ($adjacents * 2)) {
			for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
				if ($counter == $page) $pagination .= "<span class='my_pagination'>$counter</span>";
				else $pagination .= "<a href=\"view_sales.php?page=$counter&limit=$limit\" class='my_pagination'>$counter</a>";
			}
			$pagination .= "...";
			$pagination .= "<a href=\"view_sales.php?page=$lpm1&limit=$limit\" class='my_pagination'>$lpm1</a>";
			$pagination .= "<a href=\"view_sales.php?page=$lastpage&limit=$limit\" class='my_pagination'>$lastpage</a>";
		} elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
			$pagination .= "<a href=\"view_sales.php?page=1&limit=$limit\" class='my_pagination'>1</a>";
			$pagination .= "<a href=\"view_sales.php?page=2&limit=$limit\" class='my_pagination'>2</a>";
			$pagination .= "...";
			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
				if ($counter == $page) $pagination .= "<span class='my_pagination'>$counter</span>";
				else $pagination .= "<a href=\"view_sales.php?page=$counter&limit=$limit\" class='my_pagination'>$counter</a>";
			}
			$pagination .= "...";
			$pagination .= "<a href=\"view_sales.php?page=$lpm1&limit=$limit\" class='my_pagination'>$lpm1</a>";
			$pagination .= "<a href=\"view_sales.php?page=$lastpage&limit=$limit\" class='my_pagination'>$lastpage</a>";
		} else {
			$pagination .= "<a href=\"view_sales.php?page=1&limit=$limit\" class='my_pagination'>1</a>";
			$pagination .= "<a href=\"view_sales.php?page=2&limit=$limit\" class='my_pagination'>2</a>";
			$pagination .= "...";
			for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
				if ($counter == $page) $pagination .= "<span class='my_pagination'>$counter</span>";
				else $pagination .= "<a href=\"view_sales.php?page=$counter&limit=$limit\" class='my_pagination'>$counter</a>";
			}
		}
	}
	if ($page < $counter - 1) $pagination .= "<a href=\"view_sales.php?page=$next&limit=$limit\" class='my_pagination'>Next</a>";
	else $pagination .= "<span class='my_pagination'>Next</span>";
	$pagination .= "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Stock</title>
	<link rel="stylesheet" href="css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php include_once("tpl/common_js.php"); ?>
	<script src="js/script.js"></script>
	<script src="dist/js/jquery.ui.draggable.js"></script>
	<script src="dist/js/jquery.alerts.js"></script>
	<link rel="stylesheet" href="dist/js/jquery.alerts.css">
	<script>
		function confirmSubmit(id, table, dreturn) {
			jConfirm('You Want Delete This Sales Details', 'Confirmation Dialog', function(r) {
				if (r) {
					$.ajax({
						url: "delete.php",
						data: {
							id: id,
							table: table,
							return: dreturn
						},
						success: function(data) {
							window.location = 'view_sales.php';
							jAlert('Sales Is Deleted', 'POSNIC');
						}
					});
				}
			});
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
				jAlert('You must check one and only one checkbox', 'POSNIC');
				return false;
			} else {
				jConfirm('You Want Delete Sales', 'Confirmation Dialog', function(r) {
					if (r) {
						document.deletefiles.submit();
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
			for (i = 0; i < field.length; i++) field[i].checked = true;
		}

		function uncheckAll() {
			var field = document.forms.deletefiles;
			for (i = 0; i < field.length; i++) field[i].checked = false;
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
</head>

<body>
	<?php include_once("tpl/top_bar.php"); ?>
	<div id="header-with-tabs">
		<div class="page-full-width cf">
			<ul id="tabs" class="fl">
				<li><a href="dashboard.php" class="dashboard-tab">Dashboard</a></li>
				<li><a href="view_sales.php" class="active-tab sales-tab">Sales</a></li>
				<li><a href="view_customers.php" class="customers-tab">Customers</a></li>
				<li><a href="view_supplier.php" class="supplier-tab">Supplier</a></li>
				<li><a href="view_product.php" class="stock-tab">Stocks / Products</a></li>
				<li><a href="view_report.php" class="report-tab">Reports</a></li>
			</ul>
			<a href="#" id="company-branding-small" class="fr"><img src="<?php echo isset($_SESSION['logo']) ? "upload/" . $_SESSION['logo'] : "upload/posnic.png"; ?>" alt="Point of Sale" /></a>
		</div>
	</div>

	<div id="content">
		<div class="page-full-width cf">
			<div class="side-menu fl">
				<h3>Sales</h3>
				<ul>
					<li><a href="add_sales.php">Add Sales</a></li>
					<li><a href="view_sales.php">View Sales</a></li>
				</ul>
				<div style="background: #ffffff">
					<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- posnic 120x90 vertical small -->
					<ins class="adsbygoogle" style="display:inline-block;width:120px;height:90px" data-ad-client="ca-pub-5212135413309920" data-ad-slot="3677012951"></ins>
					<script>
						(adsbygoogle = window.adsbygoogle || []).push({});
					</script>
				</div>
			</div>

			<div class="side-content fr">
				<div class="content-module">
					<div class="content-module-heading cf">
						<h3 class="fl">Sales</h3>
						<span class="fr expand-collapse-text">Click to collapse</span>
						<span class="fr expand-collapse-text initial-expand">Click to expand</span>
					</div>
					<div class="content-module-main cf">
						<form action="" method="get" name="limit_go">
							Page per Record<input name="limit" type="text" class="round my_text_box" id="search_limit" style="margin-left:5px;" value="<?php echo isset($_GET['limit']) ? $_GET['limit'] : '10'; ?>" size="3" maxlength="3">
							<input name="go" type="button" value="Go" class="round blue my_button text-upper" onclick="return confirmLimitSubmit()">
						</form>
						<form name="deletefiles" action="delete.php" method="post">
							<input type="hidden" name="table" value="stock_entries">
							<input type="hidden" name="return" value="view_sales.php">
							<input type="button" name="selectall" value="SelectAll" class="my_button round blue text-upper" onclick="checkAll()" style="margin-left:5px;">
							<input type="button" name="unselectall" value="DeSelectAll" class="my_button round blue text-upper" onclick="uncheckAll()" style="margin-left:5px;">
							<input name="dsubmit" type="button" value="Delete Selected" class="my_button round blue text-upper" style="margin-left:5px;" onclick="return confirmDeleteSubmit()">
							<table>
								<colgroup>
									<col width="5%">
									<col width="15%">
									<col width="10%">
									<col width="10%">
									<col width="15%">
									<col width="10%">
									<col width="10%">
									<col width="5%">
									<col width="5%">
									<col width="5%">
								</colgroup>
								<thead>
									<tr>
										<th>ID</th>
										<th>Stock Name</th>
										<th>Customer</th>
										<th>Quantity</th>
										<th>Amount</th>
										<th>Address</th>
										<th>Contact</th>
										<th>Action</th>
										<th>Select</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									$no = ($page - 1) * $limit;
									while ($row = mysqli_fetch_assoc($result)) {
									?>
										<tr>
											<td><?php echo $row['id']; ?></td>
											<td><?php echo $row['stock_name']; ?></td>
											<td><?php echo $row['username']; ?></td>
											<td><?php echo $row['quantity']; ?></td>
											<td><?php echo $row['total']; ?></td>
											<td><?php echo $row['address']; ?></td>
											<td><?php echo $row['contact']; ?></td>
											<td>
												<a href="javascript:confirmSubmit(<?php echo $row['id']; ?>,'stock_entries','view_sales.php')" class="table-actions-button ic-table-delete"></a>
											</td>
											<td><input type="checkbox" value="<?php echo $row['id']; ?>" name="checklist[]" id="check_box"></td>
										</tr>
									<?php
										$i++;
									}
									?>
									<tr>
										<td align="center" colspan="2">
											<div style="margin-left:20px;"><?php echo $pagination; ?></div>
										</td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>