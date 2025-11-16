<?php
session_start();
$conn = new mysqli("localhost", "root", "", "localfarmconnect");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$cart_id = intval($_POST['cart_id']);
$sql = "DELETE FROM cart WHERE id=$cart_id";
if ($conn->query($sql)) {
  echo "<script>alert('Item removed from cart.'); window.location.href='view-cart.php';</script>";
} else {
  echo "<script>alert('Error removing item.'); window.location.href='view-cart.php';</script>";
}
$conn->close();
?>
