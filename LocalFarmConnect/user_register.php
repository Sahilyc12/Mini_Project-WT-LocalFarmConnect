<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "localfarmconnect";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$pass = $_POST['password'];
$address = $_POST['address'];

// Check for duplicate email
$check_sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
  echo "<h2 align='center'>This email is already registered.</h2>";
  echo "<p align='center'><a href='user-register.html'>Go Back</a></p>";
} else {
  $sql = "INSERT INTO users (name, email, password, address)
          VALUES ('$name', '$email', '$pass', '$address')";
  if ($conn->query($sql) === TRUE) {
    echo "<h2 align='center'>User Registration Successful!</h2>";
    echo "<p align='center'><a href='user-register.html'>Go Back</a></p>";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

$conn->close();
?>