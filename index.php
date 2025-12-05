<?php
require_once 'header.php';

// Handle delete
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $stmt = $conn->prepare('DELETE FROM products WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: index.php');
    exit;
}

// List products with category name using JOIN
$q = '';
if (!empty($_GET['q'])) {
    $q = $conn->real_escape_string($_GET['q']);
}
$sql = "SELECT p.id, p.name, p.price, p.stock, p.category_id, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id";
if ($q !== '') {
    $sql .= " WHERE p.name LIKE '%$q%' OR c.name LIKE '%$q%'";
}
$sql .= " ORDER BY p.id DESC";
$res = $conn->query($sql);
?>
<h1>Products</h1>
<div style="margin-bottom: 20px;"><a class="btn btn-success" href="add_product.php">+ Add Product</a></div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Category</th>
        <th>Stock</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($res): ?>
        <?php while ($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?php echo esc($row['id']); ?></td>
                <td><?php echo esc($row['name']); ?></td>
                <td><?php echo esc($row['category_name'] ?? '—'); ?></td>
                <td><?php echo esc($row['stock'] ?? '0'); ?></td>
                <td><?php echo esc(number_format($row['price'],2)); ?></td>
                <td>
                    <a class="btn btn-sm btn-primary" href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a class="btn btn-sm btn-danger" href="index.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
    </tbody>
    </tbody>
</table>

<?php require_once 'footer.php'; ?>
