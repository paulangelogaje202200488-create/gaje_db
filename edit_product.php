<?php
require_once 'header.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { header('Location: index.php'); exit; }

// load categories and product
$cats = $conn->query('SELECT id, name FROM categories ORDER BY name');
if (!$cats) {
    $cats = new stdClass();
    $cats->num_rows = 0;
}
$stmt = $conn->prepare('SELECT id, name, price, category_id, stock FROM products WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$product = $res->fetch_assoc();
$stmt->close();
if (!$product) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $category_id = $_POST['category_id'] !== '' ? (int)$_POST['category_id'] : null;
    if ($name === '' || $price <= 0) {
        $error = 'Name and valid price are required.';
    } else {
        $ust = $conn->prepare('UPDATE products SET name = ?, price = ?, category_id = ?, stock = ? WHERE id = ?');
        $ust->bind_param('sdiii', $name, $price, $category_id, $stock, $id);
        $ust->execute();
        $ust->close();
        header('Location: index.php');
        exit;
    }
}
?>

<h1>Edit Product</h1>
<div style="margin-bottom: 20px;"><a class="btn btn-outline" href="index.php">← Back to Products</a></div>

<?php if ($error): ?><div class="error"><?php echo esc($error); ?></div><?php endif; ?>

<form method="post" style="max-width: 500px;">
    <div class="form-row">
        <label>Name *</label>
        <input name="name" required value="<?php echo esc($product['name']); ?>">
    </div>
    <div class="form-row">
        <label>Price *</label>
        <input name="price" type="number" step="0.01" required value="<?php echo esc($product['price']); ?>">
    </div>
    <div class="form-row">
        <label>Stock</label>
        <input name="stock" type="number" min="0" value="<?php echo esc($product['stock'] ?? 0); ?>">
    </div>
    <div class="form-row">
        <label>Category</label>
        <select name="category_id">
            <option value="">-- none --</option>
            <?php if ($cats && $cats->num_rows > 0): ?>
                <?php while ($c = $cats->fetch_assoc()): $sel = ($c['id']==$product['category_id'])? 'selected': ''; ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo $sel; ?>><?php echo esc($c['name']); ?></option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>
    </div>
    <div><button class="btn btn-primary" type="submit">Save</button></div>
</form>

<?php require_once 'footer.php'; ?>