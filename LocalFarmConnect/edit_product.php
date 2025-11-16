<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'farmer') {
  echo "<script>alert('Please log in as a farmer to edit products.'); window.location.href='farmer-login.html';</script>";
  exit();
}

$conn = new mysqli("localhost", "root", "", "localfarmconnect");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$farmer_email = $_SESSION['user_name'];

// Fetch existing product details
$result = $conn->query("SELECT * FROM products WHERE id='$id' AND farmer_email='$farmer_email'");
if (!$result || $result->num_rows == 0) {
  echo "<script>alert('Product not found or unauthorized.'); window.location.href='my-products.php';</script>";
  exit();
}

$row = $result->fetch_assoc();

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $description = $_POST['description'];

  // Optional image update
  if (!empty($_FILES['image']['name'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    $imageUpdate = ", image='$target_file'";
  } else {
    $imageUpdate = "";
  }

  $updateQuery = "UPDATE products 
                  SET name='$name', category='$category', price='$price', description='$description' $imageUpdate 
                  WHERE id='$id' AND farmer_email='$farmer_email'";

  if ($conn->query($updateQuery) === TRUE) {
    echo "<script>alert('Product updated successfully!'); window.location.href='my-products.php';</script>";
  } else {
    echo "<script>alert('Error updating product.'); window.location.href='my-products.php';</script>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
  <h1 align="center">Edit Product</h1>
  <form method="POST" enctype="multipart/form-data" style="width:50%;margin:auto;border:1px solid #ccc;padding:20px;border-radius:8px;">
    <label>Product Name:</label><br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required><br><br>

    <label>Category:</label><br>
    <select name="category">
      <option value="Fruits" <?php if ($row['category']=='Fruits') echo 'selected'; ?>>Fruits</option>
      <option value="Vegetables" <?php if ($row['category']=='Vegetables') echo 'selected'; ?>>Vegetables</option>
      <option value="Grains" <?php if ($row['category']=='Grains') echo 'selected'; ?>>Grains</option>
    </select><br><br>

    <label>Price (₹):</label><br>
    <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($row['price']); ?>" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="3"><?php echo htmlspecialchars($row['description']); ?></textarea><br><br>

    <label>Image:</label><br>
    <input type="file" name="image" accept="image/*"><br><br>

    <input type="submit" value="Update Product">
    <a href="my-products.php"><input type="button" value="Cancel"></a>
  </form>
</body>
</html>