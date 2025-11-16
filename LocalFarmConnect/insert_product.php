<?php
session_start();

//Only allow logged-in farmers
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'farmer') {
  echo "<script>
    alert('You must be logged in as a farmer to add products.');
    window.location.href = 'farmer-login.html';
  </script>";
  exit();
}

//Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "localfarmconnect";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//Get form data
$farmer_email = $_SESSION['user_name'];
$name = $_POST['name'];
$category = $_POST['category'];
$price = $_POST['price'];
$description = $_POST['description'];

//Handle image upload
$target_dir = "uploads/";
$image_name = basename($_FILES["image"]["name"]);
$target_file = $target_dir . time() . "_" . $image_name; // prevent duplicate names
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$allowed_types = array("jpg", "jpeg", "png", "gif");

// Validate image file type
if (!in_array($imageFileType, $allowed_types)) {
  echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.'); window.history.back();</script>";
  exit();
}

// Move file to uploads folder
if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
  echo "<script>alert('Error uploading image.'); window.history.back();</script>";
  exit();
}

//Insert product data into DB
$sql = "INSERT INTO products (farmer_email, name, category, price, description, image)
        VALUES ('$farmer_email', '$name', '$category', '$price', '$description', '$target_file')";

if ($conn->query($sql) === TRUE) {
  echo "<script>
    alert('Product added successfully!');
    window.location.href = 'products.php';
  </script>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>