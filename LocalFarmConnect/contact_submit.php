<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the form data and sanitize it
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Database connection
    $conn = new mysqli("localhost", "root", "", "localfarmconnect");

    // Check for database connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind the SQL statement
    // Excluding the 'contact_id' and 'submitted_at' columns as they are auto-generated
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        die('Error preparing statement: ' . $conn->error);
    }

    // Bind parameters (using 'sssss' because we are passing 5 string values)
    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // If successful, redirect to the index page with a success message
        echo "<script>alert('Thank you! Your message has been received.'); window.location.href='index.html';</script>";
    } else {
        // If there was an error, show an alert and stay on the contact page
        echo "<script>alert('Error: Could not submit your message. Please try again.'); window.location.href='contact.html';</script>";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
}
?>
