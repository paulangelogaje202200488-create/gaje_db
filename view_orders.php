<?php
require_once 'header.php';
// List orders with customer name and total items
$sql = "SELECT o.id, o.created_at, o.status, o.total, c.name AS customer_name, COALESCE(SUM(oi.quantity),0) AS total_items
        FROM orders o
        JOIN customers c ON o.customer_id = c.id
        LEFT JOIN order_items oi ON oi.order_id = o.id
        GROUP BY o.id
        ORDER BY o.created_at DESC";
$res = $conn->query($sql);
if (!$res) {
    $res = new stdClass();
    $res->num_rows = 0;
}
?>

<h1>Orders</h1>
<div style="margin-bottom: 20px;"><a class="btn btn-success" href="add_order.php">+ Create Order</a></div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Created</th>
        <th>Items</th>
        <th>Total</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $res->fetch_assoc()): ?>
        <tr>
            <td><?php echo esc($row['id']); ?></td>
            <td><?php echo esc($row['customer_name']); ?></td>
            <td><?php echo esc(substr($row['created_at'], 0, 10)); ?></td>
            <td><?php echo esc($row['total_items']); ?></td>
            <td><?php echo number_format($row['total'], 2); ?></td>
            <td><?php echo esc($row['status']); ?></td>
            <td><a class="btn btn-sm btn-primary" href="view_order.php?id=<?php echo $row['id']; ?>">View</a></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'footer.php'; ?>
