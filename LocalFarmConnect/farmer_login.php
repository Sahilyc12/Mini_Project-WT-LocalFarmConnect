<?php
session_start();

// 🔹 Prevent already logged-in users from accessing login again
if (isset($_SESSION['user_type'])) {
  echo "<script>
    alert('You are already logged in as " . $_SESSION['user_name'] . ". Please logout first.');
    window.location.href = 'welcome.php';
  </script>";
  exit();
}

// 🔹 Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "localfarmconnect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// 🔹 Read login form data
$email = $_POST['email'];
$password = $_POST['password'];

// 🔹 Validate credentials
$sql = "SELECT * FROM farmers WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Store session info
  $_SESSION['user_type'] = 'farmer';
  $_SESSION['user_name'] = $email;
  
  // Redirect to welcome page
  header("Location: welcome.php");
  exit();
} else {
  echo "Invalid email or password";
}

$conn->close();
?>
