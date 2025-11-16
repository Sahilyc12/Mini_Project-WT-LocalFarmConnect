<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Ensure only logged-in farmers can delete
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'farmer') {
  echo "<script>alert('Unauthorized access. Please log in first.'); window.location.href='farmer-login.html';</script>";
  exit();
}

$conn = new mysqli("localhost", "root", "", "localfarmconnect");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$farmer_email = $_SESSION['user_name'];

// Get image path before deleting
$imgQuery = "SELECT image FROM products WHERE id='$id' AND farmer_email='$farmer_email'";
$imgResult = $conn->query($imgQuery);
if ($imgResult && $imgResult->num_rows > 0) {
  $row = $imgResult->fetch_assoc();
  $imagePath = $row['image'];

  // Delete record
  $deleteQuery = "DELETE FROM products WHERE id='$id' AND farmer_email='$farmer_email'";
  if ($conn->query($deleteQuery) === TRUE) {
    if (file_exists($imagePath)) {
      unlink($imagePath); // remove image file from uploads folder
    }
    echo "<script>alert('Product deleted successfully!'); window.location.href='my-products.php';</script>";
  } else {
    echo "<script>alert('Error deleting product.'); window.location.href='my-products.php';</script>";
  }
} else {
  echo "<script>alert('Product not found or unauthorized.'); window.location.href='my-products.php';</script>";
}

$conn->close();
?>