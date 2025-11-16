<?php
// Step 1: Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$database = "localfarmconnect";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Step 2: Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$pass = $_POST['password'];
$location = $_POST['location'];
$produce = $_POST['produce_type'];

// Step 3: Check if email already exists
$check_sql = "SELECT * FROM farmers WHERE email='$email'";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
  echo "<h2 align='center'>This email is already registered.</h2>";
  echo "<p align='center'><a href='farmer-register.html'>Go Back</a></p>";
} else {
  // Step 4: Insert new record
  $sql = "INSERT INTO farmers (name, email, password, location, produce_type)
          VALUES ('$name', '$email', '$pass', '$location', '$produce')";
  
  if ($conn->query($sql) === TRUE) {
    echo "<h2 align='center'>Registration Successful!</h2>";
    echo "<p align='center'><a href='farmer-register.html'>Go Back</a></p>";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

$conn->close();
?>