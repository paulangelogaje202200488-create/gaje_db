<?php
require_once 'header.php';

// Handle delete
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $stmt = $conn->prepare('DELETE FROM customers WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: customers.php');
    exit;
}

$res = $conn->query("SELECT * FROM customers ORDER BY id DESC");
if (!$res) {
    $res = new stdClass();
    $res->num_rows = 0;
}
?>

<h1>Customers</h1>
<div style="margin-bottom: 20px;"><a class="btn btn-success" href="add_customer.php">+ Add Customer</a></div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($c = $res->fetch_assoc()): ?>
        <tr>
            <td><?php echo esc($c['id']); ?></td>
            <td><?php echo esc($c['name']); ?></td>
            <td><?php echo esc($c['email'] ?? '—'); ?></td>
            <td><?php echo esc($c['phone'] ?? '—'); ?></td>
            <td>
                <a class="btn btn-sm btn-primary" href="edit_customer.php?id=<?php echo $c['id']; ?>">Edit</a>
                <a class="btn btn-sm btn-danger" href="customers.php?delete_id=<?php echo $c['id']; ?>" onclick="return confirm('Delete this customer?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'footer.php'; ?>
