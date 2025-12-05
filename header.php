<?php
// header.php - common header and navigation
require_once 'database.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gaje DB</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <div class="top-links">
    <a href="index.php">Products</a>
    <a href="manage_categories.php">Categories</a>
    <a href="customers.php">Customers</a>
    <a href="add_order.php">Create Order</a>
    <a href="view_orders.php">Orders</a>
    <form method="get" action="index.php" style="margin-left: auto; display: flex; gap: 5px; align-items: center;">
      <input type="text" name="q" placeholder="Search products" value="<?php echo esc($_GET['q'] ?? ''); ?>" style="padding: 8px 12px; border-radius: 4px; border: 1px solid #ddd; font-size: 0.95em;">
      <button type="submit" class="btn btn-primary" style="margin: 0; padding: 8px 20px;">Search</button>
    </form>
  </div>

