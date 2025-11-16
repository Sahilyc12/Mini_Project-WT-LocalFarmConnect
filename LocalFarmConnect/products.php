<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "", "localfarmconnect");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'user') {
        echo "<script>alert('Please login as a user to add items to your cart.');</script>";
    } else {
        $product_id = intval($_POST['product_id']);
        $user_email = $_SESSION['user_name'];
        $check = $conn->query("SELECT * FROM cart WHERE user_email='$user_email' AND product_id=$product_id");

        if ($check->num_rows == 0) {
            $conn->query("INSERT INTO cart (user_email, product_id) VALUES ('$user_email', $product_id)");
            echo "<script>alert('Product added to cart!');</script>";
        } else {
            echo "<script>alert('This product is already in your cart.');</script>";
        }
    }
}

// Handle Buy Now
if (isset($_POST['buy_now'])) {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'user') {
        echo "<script>alert('Please login as a user to make a purchase.');</script>";
    } else {
        echo "<script>alert('Thank you for your purchase! Order confirmed.');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>LocalFarmConnect - Products</title>
    <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
    <!-- Navigation Menu -->
    <div id="menu" align="middle">
        <a href="index.html">HOME</a> |
        <a href="products.php">PRODUCTS</a> |
        <a href="farmer-register.html">FARMER REGISTER</a> |
        <a href="user-register.html">USER REGISTER</a> |
        <a href="farmer-login.html">FARMER LOGIN</a> |
        <a href="user-login.html">USER LOGIN</a> |
        <a href="contact.html">CONTACT</a>
        <?php
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'farmer') {
            echo ' | <a href="my-products.php">MY PRODUCTS</a> | <a href="logout.php">LOGOUT</a>';
        } elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'user') {
            echo ' | <a href="view-cart.php">VIEW CART</a> | <a href="logout.php">LOGOUT</a>';
        }
        ?>
    </div>

    <h1 align="center">Our Products</h1>

    <div class="box">
        <h2 style="text-align:center;">Fresh From Local Farmers</h2>
        <p style="text-align:center;">
            We offer a variety of fresh produce such as seasonal fruits, vegetables, and grains. 
            All are supplied directly by our registered local farmers.
        </p>
    </div>

    <hr style="margin:40px 0;">

    <!-- ================== Add Product Form for Farmers ================== -->
    <?php
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'farmer') {
    ?>
        <h2 align="center">Add New Product</h2>
        <form action="insert_product.php" method="POST" enctype="multipart/form-data" style="max-width:500px;margin:auto;">
            <label>Product Name:</label><br>
            <input type="text" name="name" required><br><br>

            <label>Category:</label><br>
            <select name="category" required>
                <option value="Fruits">Fruits</option>
                <option value="Vegetables">Vegetables</option>
                <option value="Grains">Grains</option>
                <option value="Other">Other</option>
            </select><br><br>

            <label>Price (₹):</label><br>
            <input type="number" name="price" step="0.01" required><br><br>

            <label>Description:</label><br>
            <textarea name="description" rows="3" required></textarea><br><br>

            <label>Image:</label><br>
            <input type="file" name="image" accept="image/*" required><br><br>

            <button type="submit" name="add_product">Add Product</button>
        </form>
        <hr>
    <?php
    }
    ?>

    <!-- ================== Display All Products ================== -->
    <h2 align="center">Available Products</h2>

    <div class="product-container" style="display:flex;flex-wrap:wrap;gap:20px;justify-content:center;">
    <?php
    $result = $conn->query("SELECT * FROM products ORDER BY date_added DESC");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product-card'>";
            echo "<img src='" . $row['image'] . "' alt='" . htmlspecialchars($row['name']) . "' style='width:100%;height:150px;object-fit:cover;border-radius:8px;'>";
            echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
            echo "<p>Category: " . htmlspecialchars($row['category']) . "</p>";
            echo "<p>Price: ₹" . htmlspecialchars($row['price']) . "</p>";
            echo "<p><b>" . htmlspecialchars($row['description']) . "</b></p>";
            
            // Only show Buy/Add to Cart for users
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'user') {
                echo "<form method='POST' style='display:inline;'>
                        <input type='hidden' name='product_id' value='" . $row['id'] . "'>
                        <button type='submit' name='buy_now' class='buy-btn'>Buy Now</button>
                      </form>";
                
                echo "<form method='POST' style='display:inline;'>
                        <input type='hidden' name='product_id' value='" . $row['id'] . "'>
                        <button type='submit' name='add_to_cart' class='cart-btn'>Add to Cart</button>
                      </form>";
            }

            echo "</div>";
        }
    } else {
        echo "<p align='center'>No products available yet.</p>";
    }
    $conn->close();
    ?>
    </div>
</body>
</html>
