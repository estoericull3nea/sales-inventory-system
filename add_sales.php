<?php
session_start();
require './connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment'])) {
  // Handle form submission
  // Sanitize and validate inputs
  $customer = filter_input(INPUT_POST, 'supplier', FILTER_SANITIZE_STRING);
  $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
  $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);
  $payment = filter_input(INPUT_POST, 'payment', FILTER_VALIDATE_FLOAT);
  $mode = filter_input(INPUT_POST, 'mode', FILTER_SANITIZE_STRING);

  // Further processing of form data...
  var_dump($_POST); // Remove this line after debugging
  exit();
}

// Fetch products from the database
$query = "SELECT id, stock_name, stock_quantity, price FROM products";
$result = $conn->query($query);
$products = [];
while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="./bootstrap.min.css">
  <meta charset="utf-8">
  <title>Add Sales</title>
  <link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet'>
  <link rel="stylesheet" href="css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php include_once("tpl/common_js.php"); ?>
  <script src="js/script.js"></script>
  <script src="js/date_pic/jquery.date_input.js"></script>
  <script src="lib/auto/js/jquery.autocomplete.js"></script>
  <script type="text/javascript">
    function total_amount() {
      const sell = parseFloat(document.getElementById('sell').value);
      const quantity = parseFloat(document.getElementById('quty').value);
      const total = sell * quantity;
      document.getElementById('total').value = total.toFixed(2);
    }

    function quantity_change(event) {
      total_amount();
    }

    function numbersonly(event) {
      const key = event.which || event.keyCode;
      if ((key < 48 || key > 57) && key !== 8 && key !== 46 && key !== 37 && key !== 39) {
        return false;
      }
      return true;
    }

    function unique_check() {
      const item = document.getElementById('item').value;
      const items = document.getElementsByName('item[]');
      for (let i = 0; i < items.length; i++) {
        if (items[i].value === item) {
          alert("Item already added");
          document.getElementById('item').value = '';
          return;
        }
      }
    }

    function stock_size() {
      const item = document.getElementById('item').value;

      $.post('check_item_details.php', {
        item: item
      }, function(data) {
        $("#sell").val(data.sell);
        $("#stock").val(data.stock);
        $('#guid').val(data.guid);
        if (data.sell !== undefined) {
          $("#quty").focus();
        }
      }, 'json');
    }

    function add_values() {
      const item = document.getElementById('item').value;
      const quantity = document.getElementById('quty').value;
      const price = document.getElementById('sell').value;
      const stock = document.getElementById('stock').value;
      const total = document.getElementById('total').value;

      if (item === '' || quantity === '' || price === '' || total === '') {
        alert("Please fill in all the fields");
        return;
      }

      const table = document.getElementById('item_copy_final');
      const row = table.insertRow(-1);

      const cell1 = row.insertCell(0);
      const cell2 = row.insertCell(1);
      const cell3 = row.insertCell(2);
      const cell4 = row.insertCell(3);
      const cell5 = row.insertCell(4);
      const cell6 = row.insertCell(5);

      cell1.innerHTML = `<input type="hidden" name="item[]" value="${item}">` + item;
      cell2.innerHTML = `<input type="text" name="quty[]" readonly value="${quantity}" class="round default-width-input my_with">`;
      cell3.innerHTML = `<input type="text" name="sell[]" readonly value="${price}" class="round default-width-input my_with">`;
      cell4.innerHTML = `<input type="text" name="stock[]" readonly value="${stock}" class="round my_with">`;
      cell5.innerHTML = `<input type="text" name="total[]" readonly value="${total}" class="round default-width-input" style="width:120px; margin-left:20px;">`;
      cell6.innerHTML = `<input type="button" value="Remove" class="round" onclick="remove_row(this)" style="margin-left:30px; width:30px;height:30px;border:none;background:url(images/remove.png);">`;

      document.getElementById('item').value = '';
      document.getElementById('quty').value = '';
      document.getElementById('sell').value = '';
      document.getElementById('stock').value = '';
      document.getElementById('total').value = '';
      document.getElementById('item').focus();
    }

    function remove_row(button) {
      const row = button.parentNode.parentNode;
      row.parentNode.removeChild(row);
    }

    $(function() {
      $("#supplier").autocomplete("customer1.php", {
        width: 160,
        autoFill: true,
        selectFirst: true
      });
      $("#item").autocomplete("stock.php", {
        width: 160,
        autoFill: true,
        mustMatch: true,
        selectFirst: true
      });
      $("#item").blur(stock_size);
      $("#supplier").blur(function() {
        $.post('check_customer_details.php', {
          supplier: $("#supplier").val()
        }, function(data) {
          $("#address").val(data.address);
          $("#contact1").val(data.contact1);
        }, 'json');
      });
      $('#test1').jdPicker();
      $('#test2').jdPicker();
    });



    $(document).ready(function() {
      const products = <?php echo json_encode($products); ?>;

      $("#productSelect").change(function() {
        const selectedId = $(this).val();
        const product = products.find(p => p.id == selectedId);

        if (product) {
          $("#price").val(product.price);
          $("#stock").val(product.stock_quantity);
          $("#error-message").text('');
          calculateTotal();
        } else {
          $("#price").val('');
          $("#stock").val('');
          $("#total").val('');
          $("#error-message").text('');
        }
      });

      $("#quantity").keyup(function() {
        calculateTotal();
      });

      function calculateTotal() {
        const quantity = parseFloat($("#quantity").val());
        const price = parseFloat($("#price").val());
        const stock = parseFloat($("#stock").val());

        if (quantity > stock) {
          $("#error-message").text('Quantity exceeds available stock');
          $("#total").val('');
        } else {
          $("#error-message").text('');
          const total = (quantity * price).toFixed(2);
          $("#total").val(total);
        }
      }

      $("#formAddSales").submit(function(event) {
        event.preventDefault();
        const totalAmount = parseFloat($("#total").val());
        const paymentAmount = parseFloat($("#payment").val());

        if (isNaN(paymentAmount) || paymentAmount <= 0 || paymentAmount < totalAmount) {
          $("#payment-error-message").text('Payment amount must be greater than 0 and less than or equal to the total amount');
          return;
        } else {
          $("#payment-error-message").text('');
        }

        const formData = {
          supplier: $("#supplier").val(),
          address: $("#address").val(),
          contact: $("#contact1").val(),
          selectedProduct: $("#productSelect option:selected").text(),
          productId: $("#productSelect").val(),
          quantity: $("#quantity").val(),
          price: $("#price").val(),
          stock: $("#stock").val(),
          total: $("#total").val(),
          payment: $("#payment").val()
        };
        const jsonData = JSON.stringify(formData);
        console.log(jsonData);

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/sales/formAddSales.php", true); // Replace with your server endpoint
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onload = function() {
          if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status === 'error') {
              alert(response.message);
            } else {
              alert('Transaction Added');
              window.location.reload();
            }
          } else {
            alert('An error occurred while processing your request.');
          }
        };
        xhr.send(jsonData);
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
    </div>
  </div>
  <div id="content">
    <div class="page-full-width cf">
      <div class="side-menu fl">
        <h3>Sales Management</h3>
        <ul>
          <li><a href="add_sales.php">Add Sales</a></li>
          <li><a href="view_sales.php">View Sales</a></li>
        </ul>
      </div>
      <div class="side-content fr">
        <div class="content-module">
          <div class="content-module-heading cf">
            <h3 class="fl">Add Sales</h3>
            <span class="fr expand-collapse-text">Click to collapse</span>
            <span class="fr expand-collapse-text initial-expand">Click to expand</span>
          </div>
          <div class="content-module-main cf">
            <?php if (isset($_GET['msg'])) : ?>
              <script src="dist/js/jquery.ui.draggable.js"></script>
              <script src="dist/js/jquery.alerts.js"></script>
              <script src="dist/js/jquery.js"></script>
              <link rel="stylesheet" href="dist/js/jquery.alerts.css">
              <script type="text/javascript">
                jAlert('<?php echo htmlspecialchars($_GET['msg']); ?>', 'POSNIC');
              </script>
            <?php endif; ?>
            <form name="formAddSales" method="post" id="formAddSales" action="">
              <input type="hidden" id="posnic_total">
              <input type="hidden" id="roll_no" value="1">

          </div><br>
          <div align="center">
            <input type="hidden" id="guid">
            <input type="hidden" id="edit_guid">
            <table class="form">
              <form name="formAddSales" id="formAddSales">
                <input type="hidden" id="posnic_total">
                <input type="hidden" id="roll_no" value="1">
                <div class="mytable_row"><br>
                  <table class="form" border="0" cellspacing="0" cellpadding="0">
                    <!-- Existing form fields -->
                    <tr>
                      <td>&nbsp; </td>
                      <td>&nbsp; </td>
                      <?php
                      $result = $conn->query("SELECT MAX(id) AS max_id FROM stock_entries");
                      $row = $result->fetch_assoc();
                      $max = $row['max_id'] + 3;
                      $autoid = "PR" . $max;
                      ?>
                      <td>Stock ID:</td>
                      <td><input name="stockid" type="text" id="stockid" readonly maxlength="200" class="round default-width-input" style="width:130px;" value="<?php echo $autoid; ?>" /></td>
                      <td>Date:</td>
                      <td><input name="date" value="<?php echo date('d-m-Y'); ?>" type="text" id="date" maxlength="200" class="round default-width-input" /></td>
                      <td>&nbsp; </td>
                      <td>&nbsp; </td>
                    </tr>
                    <tr>
                      <td>&nbsp; </td>
                      <td>&nbsp; </td>
                      <td><span class="man">*</span>Customer:</td>
                      <td><input type="text" name="supplier" id="supplier" maxlength="200" class="round default-width-input" style="width:150px;" /></td>
                      <td>Address:</td>
                      <td><input name="address" placeholder="ENTER ADDRESS" type="text" id="address" maxlength="200" class="round default-width-input" /></td>
                      <td>&nbsp; </td>
                      <td>&nbsp; </td>
                      <td>Contact:</td>
                      <td><input name="contact" placeholder="ENTER CONTACT" type="text" id="contact1" maxlength="25" class="round default-width-input" onkeypress="return numbersonly(event)" style="width:120px;" /></td>
                    </tr>
                  </table>
                </div><br>
                <div align="center">
                  <input type="hidden" id="guid">
                  <input type="hidden" id="edit_guid">
                  <table class="form">
                    <label for="productSelect" class="text-start">Select Order</label>
                    <select id="productSelect" class="form-select">
                      <option selected>Select Order</option>
                      <?php foreach ($products as $product) : ?>
                        <option value="<?php echo $product['id']; ?>"><?php echo $product['stock_name']; ?></option>
                      <?php endforeach; ?>
                    </select>

                    <div class="mb-3">
                      <label for="quantity" class="form-label text-start">Quantity</label>
                      <input type="number" id="quantity" class="form-control">
                      <small id="error-message" style="color: red;"></small>
                    </div>

                    <div class="mb-3">
                      <label for="price" class="form-label text-start">Price</label>
                      <input type="number" id="price" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                      <label for="stock" class="form-label text-start">Available Stock</label>
                      <input type="text" id="stock" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                      <label for="total" class="form-label text-start">Total</label>
                      <input type="text" id="total" class="form-control" readonly>
                    </div>
                  </table>
                  <div style="overflow:auto;max-height:300px;">
                    <table class="form" id="item_copy_final" style="margin-left:45px;">
                    </table>
                  </div>
                </div>

                <div class="mb-3">
                  <label for="payment" class="form-label text-start">Amount to Pay</label>
                  <input type="number" id="payment" class="form-control" required>
                  <small id="payment-error-message" style="color: red;"></small>
                </div>


                <div class="mytable_row">
                  <table class="form">
                    <tr>
                      <td>&nbsp; </td>
                      <td><input class="button round blue image-right ic-add text-upper" type="submit" name="Submit" value="Add"></td>
                      <td>(Control + S)<input class="button round red text-upper" type="reset" name="Reset" value="Reset"></td>
                      <td><input class="button round red text-upper" type="button" name="print" value="Print" onclick='print1();'></td>
                      <td>&nbsp; </td>
                    </tr>
                  </table>
                </div>
              </form>



            </table>
            <div style="overflow:auto;max-height:300px;">
              <table class="form" id="item_copy_final" style="margin-left:45px;">
              </table>
            </div>
          </div>

          </form>
        </div>
      </div>
    </div>
  </div>
  </div>

</body>

</html>