<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Check if farmer is logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'farmer') {
  echo "<script>
          alert('Please log in as a farmer to view your products.');
          window.location.href = 'farmer-login.html';
        </script>";
  exit();
}

// Get logged-in farmer email
$farmer_email = $_SESSION['user_name'];

// Connect to database
$conn = new mysqli("localhost", "root", "", "localfarmconnect");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>My Products - LocalFarmConnect</title>
  <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
  <!-- Navigation Menu -->
  <div id="menu" align="middle">
    <a href="index.html">HOME</a> |
    <a href="products.php">PRODUCTS</a> |
    <a href="farmer-register.html">FARMER REGISTER</a> |
    <a href="user-register.html">USER REGISTER</a> |
    <a href="farmer-login.html">FARMER LOGIN</a> |
    <a href="user-login.html">USER LOGIN</a> |
    <a href="contact.html">CONTACT</a>

    <?php
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'farmer') {
      echo ' | <a href="my-products.php">MY PRODUCTS</a> | <a href="logout.php">LOGOUT</a>';
    }
    ?>
  </div>

  <h1 align="center">My Products</h1>

  <div class="product-container">
  <?php
  $result = $conn->query("SELECT * FROM products WHERE farmer_email='$farmer_email' ORDER BY date_added DESC");
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<div class='product-card'>";
      echo "<img src='" . $row['image'] . "' alt='" . htmlspecialchars($row['name']) . "' style='width:100%;height:150px;object-fit:cover;border-radius:8px;'><br>";
      echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
      echo "<p>Category: " . htmlspecialchars($row['category']) . "</p>";
      echo "<p>Price: ₹" . htmlspecialchars($row['price']) . "</p>";
      echo "<p><b>" . htmlspecialchars($row['description']) . "</b></p>";

      echo "<a href='edit_product.php?id=" . $row['id'] . "' style='text-decoration:none;'>
              <button class='edit-btn'>Edit</button>
            </a>";

      echo "<a href='delete_product.php?id=" . $row['id'] . "' 
                onclick=\"return confirm('Are you sure you want to delete this product?');\" 
                style='text-decoration:none;'>
              <button class='delete-btn'>Delete</button>
            </a>";

      echo "</div>";
    }
  } else {
    echo "<p align='center'>You haven’t added any products yet.</p>";
  }

  $conn->close();
  ?>
  </div>
</body>
</html>