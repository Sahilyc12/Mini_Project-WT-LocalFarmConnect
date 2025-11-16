<?php
session_start();

// If user is not logged in, redirect to home
if(!isset($_SESSION['user_type']) || !isset($_SESSION['user_name'])) {
  header("Location: index.html");
  exit();
}

$userType = $_SESSION['user_type'];
$userName = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Welcome - LocalFarmConnect</title>
  <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
  <div id="menu" align="middle">
    <a href="index.html">HOME</a> |
    <a href="products.php">PRODUCTS</a> |
    <a href="contact.html">CONTACT</a> |
    <a href="logout.php">LOGOUT</a>
  </div>

  <h1 style="text-align:center;">Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
  <p style="text-align:center;">You are logged in as a <strong><?php echo ucfirst($userType); ?></strong>.</p>

  <?php if($userType == 'farmer'): ?>
    <div style="text-align:center;">
      <p><a href="products.php">Manage Products</a></p>
    </div>
  <?php else: ?>
    <div style="text-align:center;">
      <p><a href="products.php">View Available Produce</a></p>
    </div>
  <?php endif; ?>
</body>
</html>
