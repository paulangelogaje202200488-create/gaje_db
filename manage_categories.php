<?php
require_once 'header.php';
// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    $name = trim($_POST['name']);
    if (!empty($_POST['edit_id'])) {
        $edit_id = (int)$_POST['edit_id'];
        $ust = $conn->prepare('UPDATE categories SET name = ? WHERE id = ?');
        $ust->bind_param('si', $name, $edit_id);
        $ust->execute();
        $ust->close();
    } else {
        $stmt = $conn->prepare('INSERT INTO categories (name) VALUES (?)');
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: manage_categories.php');
    exit;
}
// Handle delete
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $stmt = $conn->prepare('DELETE FROM categories WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: manage_categories.php');
    exit;
}
$res = $conn->query('SELECT * FROM categories ORDER BY id DESC');
if (!$res) {
    $res = new stdClass();
    $res->num_rows = 0;
}
$editing = null;
if (isset($_GET['edit_id'])) {
    $eid = (int)$_GET['edit_id'];
    $er = $conn->query("SELECT * FROM categories WHERE id = $eid LIMIT 1");
    $editing = $er->fetch_assoc();
}
?>

<h1>Categories</h1>
<div style="margin-bottom: 20px;"><a class="btn btn-outline" href="index.php">← Products</a></div>

<form method="post" style="margin-bottom: 20px;">
    <div class="form-row">
        <label>Category name</label>
        <input type="text" name="name" required value="<?php echo esc($editing['name'] ?? ''); ?>">
    </div>
    <div>
        <?php if ($editing): ?>
            <input type="hidden" name="edit_id" value="<?php echo $editing['id']; ?>">
            <button class="btn btn-primary">Save</button>
            <a class="btn btn-outline" href="manage_categories.php">Cancel</a>
        <?php else: ?>
            <button class="btn btn-success">Add Category</button>
        <?php endif; ?>
    </div>
</form>

<table>
    <thead>
    <tr><th>ID</th><th>Name</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php if ($res && $res->num_rows > 0): ?>
        <?php while ($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?php echo esc($row['id']); ?></td>
                <td><?php echo esc($row['name']); ?></td>
                <td>
                    <a class="btn btn-sm btn-primary" href="manage_categories.php?edit_id=<?php echo $row['id']; ?>">Edit</a>
                    <a class="btn btn-sm btn-danger" href="manage_categories.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Delete category?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
    </tbody>
</table>

<?php require_once 'footer.php'; ?>
