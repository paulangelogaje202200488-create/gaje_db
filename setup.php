<?php
// setup.php - Initialize database tables
$mysqli = new mysqli('localhost', 'root', '', '');

if ($mysqli->connect_errno) {
    die('Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Create database
$mysqli->query('CREATE DATABASE IF NOT EXISTS gaje_db');
$mysqli->select_db('gaje_db');

// Create categories table
$mysqli->query("CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL
)");

// Create products table
$mysqli->query("CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  stock INT DEFAULT 0,
  category_id INT,
  FOREIGN KEY (category_id) REFERENCES categories(id)
)");

// Create customers table
$mysqli->query("CREATE TABLE IF NOT EXISTS customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255),
  phone VARCHAR(20)
)");

// Create orders table
$mysqli->query("CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(12,2),
  status VARCHAR(50) DEFAULT 'pending',
  FOREIGN KEY (customer_id) REFERENCES customers(id)
)");

// Create order_items table
$mysqli->query("CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
)");

// Insert sample data
$mysqli->query("INSERT IGNORE INTO categories (id, name) VALUES 
(1, 'Electronics'),
(2, 'Clothing'),
(3, 'Food')");

$mysqli->query("INSERT IGNORE INTO products (id, name, price, stock, category_id) VALUES 
(1, 'Laptop', 999.99, 5, 1),
(2, 'Mouse', 25.99, 50, 1),
(3, 'T-Shirt', 19.99, 100, 2),
(4, 'Coffee', 12.99, 200, 3)");

$mysqli->query("INSERT IGNORE INTO customers (id, name, email, phone) VALUES 
(1, 'John Doe', 'john@example.com', '555-1234'),
(2, 'Jane Smith', 'jane@example.com', '555-5678')");

$mysqli->close();

echo "<h1>✓ Database Setup Complete!</h1>";
echo "<p>Tables created and sample data inserted.</p>";
echo "<p><a href='index.php'>Go to Products</a></p>";
?>
