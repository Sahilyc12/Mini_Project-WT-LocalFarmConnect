<?php
session_start();
$conn = new mysqli("localhost", "root", "", "localfarmconnect");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'user') {
  echo "<script>alert('Please log in as a user to continue.'); window.location.href='user-login.html';</script>";
  exit();
}

$user_email = $_SESSION['user_name'];
$product_id = intval($_POST['product_id']);
$action = $_POST['action'];

// ✅ Handle Add to Cart
if ($action == "add") {
  $sql = "INSERT INTO cart (user_email, product_id) VALUES ('$user_email', '$product_id')";
  if ($conn->query($sql)) {
    echo "<script>alert('Item added to cart!'); window.location.href='products.php';</script>";
  } else {
    echo "<script>alert('Error adding to cart!'); window.location.href='products.php';</script>";
  }
}

// ✅ Handle Buy Now (direct order)
elseif ($action == "buy") {
  $sql = "INSERT INTO orders (user_email, product_id) VALUES ('$user_email', '$product_id')";
  if ($conn->query($sql)) {
    echo "<script>alert('Purchase successful!'); window.location.href='products.php';</script>";
  } else {
    echo "<script>alert('Error processing order!'); window.location.href='products.php';</script>";
  }
}

$conn->close();
?>
