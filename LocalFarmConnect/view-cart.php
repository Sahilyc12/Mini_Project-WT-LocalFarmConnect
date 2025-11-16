<?php
session_start();

// Make sure only logged-in users can access the cart
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'user') {
  echo "<script>
          alert('Please login as a user to view your cart.');
          window.location.href = 'user-login.html';
        </script>";
  exit();
}

$user_email = $_SESSION['user_name'];

// Connect to DB
$conn = new mysqli("localhost", "root", "", "localfarmconnect");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Remove item from cart
if (isset($_POST['remove'])) {
  $product_id = intval($_POST['product_id']);
  $conn->query("DELETE FROM cart WHERE user_email='$user_email' AND product_id=$product_id");
}

// Checkout (move to orders + clear cart)
if (isset($_POST['checkout'])) {
  // Fetch all cart items for the current user
  $cartItems = $conn->query("SELECT * FROM cart WHERE user_email='$user_email'");

  if ($cartItems && $cartItems->num_rows > 0) {
    while ($item = $cartItems->fetch_assoc()) {
      $product_id = $item['product_id'];

      // Get product details
      $p = $conn->query("SELECT name, price FROM products WHERE id=$product_id")->fetch_assoc();
      $pname = $conn->real_escape_string($p['name']);
      $pprice = $p['price'];

      // Insert into orders table
      $conn->query("INSERT INTO orders (user_email, product_id, product_name, price, order_date)
                    VALUES ('$user_email', $product_id, '$pname', $pprice, NOW())");
    }

    // Clear cart
    $conn->query("DELETE FROM cart WHERE user_email='$user_email'");
    echo "<script>alert('✅ Thank you for your purchase! Your order has been placed successfully.');</script>";
  } else {
    echo "<script>alert('Your cart is empty. Nothing to checkout.');</script>";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>My Cart - LocalFarmConnect</title>
  <link rel="stylesheet" type="text/css" href="mystyle.css">
  <style>
    .cart-container {
      width: 80%;
      margin: 30px auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #ccc;
    }

    th {
      background-color: #d0f0f6;
      color: #333;
    }

    .remove-btn {
      background-color: red;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 4px;
      cursor: pointer;
    }
    .remove-btn:hover { background-color: darkred; }

    .checkout-btn {
      background-color: green;
      color: white;
      border: none;
      padding: 10px 18px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 20px;
    }
    .checkout-btn:hover { background-color: darkgreen; }
  </style>
</head>
<body>
  <div id="menu" align="middle">
    <a href="index.html">HOME</a> |
    <a href="products.php">PRODUCTS</a> |
    <a href="logout.php">LOGOUT</a>
  </div>

  <h1 align="center">🛒 My Cart</h1>

  <div class="cart-container">
    <?php
    // Fetch cart items with product info
    $sql = "SELECT c.product_id, p.name, p.price, p.image 
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_email='$user_email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      echo "<table>
              <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Price (₹)</th>
                <th>Action</th>
              </tr>";
      $total = 0;
      while ($row = $result->fetch_assoc()) {
        $total += $row['price'];
        echo "<tr>
                <td><img src='" . $row['image'] . "' style='width:80px;height:80px;object-fit:cover;border-radius:6px;'></td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>₹" . htmlspecialchars($row['price']) . "</td>
                <td>
                  <form method='POST' style='display:inline;'>
                    <input type='hidden' name='product_id' value='" . $row['product_id'] . "'>
                    <button type='submit' name='remove' class='remove-btn'>Remove</button>
                  </form>
                </td>
              </tr>";
      }
      echo "<tr>
              <td colspan='2' align='right'><b>Total:</b></td>
              <td colspan='2'><b>₹$total</b></td>
            </tr>";
      echo "</table>";

      echo "<form method='POST' align='center'>
              <button type='submit' name='checkout' class='checkout-btn'>Proceed to Checkout</button>
            </form>";
    } else {
      echo "<p align='center'>Your cart is empty.</p>";
    }

    $conn->close();
    ?>
  </div>
</body>
</html>