<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<div id="menu" align="middle">
  <a href="index.html">HOME</a> |
  <a href="products.php">PRODUCTS</a> |
  <a href="contact.html">CONTACT</a> |

  <?php if (isset($_SESSION['user_type'])): ?>
    <!-- If logged in -->
    <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span> |
    <a href="logout.php">LOGOUT</a>
  <?php else: ?>
    <!-- If not logged in -->
    <a href="farmer-register.html">FARMER REGISTER</a> |
    <a href="user-register.html">USER REGISTER</a> |
    <a href="farmer-login.html">FARMER LOGIN</a> |
    <a href="user-login.html">USER LOGIN</a>
  <?php endif; ?>
</div>
