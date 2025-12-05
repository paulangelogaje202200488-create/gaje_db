<?php
require_once 'header.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { header('Location: view_orders.php'); exit; }

// Get order and customer
$ost = $conn->prepare('SELECT o.id, o.created_at, o.total, o.status, c.name AS customer_name FROM orders o JOIN customers c ON o.customer_id = c.id WHERE o.id = ?');
$ost->bind_param('i', $id);
$ost->execute();
$ores = $ost->get_result();
$order = $ores->fetch_assoc();
$ost->close();
if (!$order) { header('Location: view_orders.php'); exit; }

// Get items
$itst = $conn->prepare('SELECT oi.product_id, oi.quantity, oi.price, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?');
$itst->bind_param('i', $id);
$itst->execute();
$items = $itst->get_result();
$itst->close();
?>

<h1>Order #<?php echo esc($order['id']); ?></h1>
<div style="margin-bottom: 20px;"><a class="btn btn-outline" href="view_orders.php">← Back to Orders</a></div>

<div style="margin-bottom: 20px;">
    <p><strong>Customer:</strong> <?php echo esc($order['customer_name']); ?></p>
    <p><strong>Created:</strong> <?php echo esc(substr($order['created_at'], 0, 10)); ?></p>
    <p><strong>Status:</strong> <?php echo esc($order['status']); ?></p>
</div>

<table>
    <thead>
    <tr>
        <th>Product</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Subtotal</th>
    </tr>
    </thead>
    <tbody>
    <?php $total = 0; while ($it = $items->fetch_assoc()): $sub = $it['quantity'] * $it['price']; $total += $sub; ?>
        <tr>
            <td><?php echo esc($it['name']); ?></td>
            <td><?php echo esc($it['quantity']); ?></td>
            <td><?php echo number_format($it['price'], 2); ?></td>
            <td><?php echo number_format($sub, 2); ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-end">Total</th>
            <th><?php echo number_format($order['total'], 2); ?></th>
        </tr>
    </tfoot>
</table>

<?php require_once 'footer.php'; ?>
