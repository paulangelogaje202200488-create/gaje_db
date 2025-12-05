<?php
require_once 'database.php';

$error = '';
$success = '';

$cats = $conn->query('SELECT id, name FROM categories ORDER BY name ASC');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $category_id = $_POST['category_id'] !== '' ? (int)$_POST['category_id'] : null;

    if ($name === '') {
        $error = 'Product name is required.';
    } elseif ($price <= 0) {
        $error = 'Price must be greater than 0.';
    } else {
        $stock = (int)($_POST['stock'] ?? 0);
        $stmt = $conn->prepare('INSERT INTO products (name, price, category_id, stock) VALUES (?, ?, ?, ?)');
        if (!$stmt) {
            $error = 'Prepare failed: ' . $conn->error;
        } else {
            $stmt->bind_param('sdii', $name, $price, $category_id, $stock);
            if ($stmt->execute()) {
                $success = 'Product added successfully.';
                // reload categories result for form
                $cats = $conn->query('SELECT id, name FROM categories ORDER BY name ASC');
            } else {
                $error = 'Execute failed: ' . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>
<?php require_once 'header.php'; ?>

    <h1>Add Product</h1>
    <div style="margin-bottom: 20px;"><a class="btn btn-outline" href="index.php">← Back to Products</a></div>

    <?php if ($error): ?><div class="error"><?php echo esc($error); ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?php echo esc($success); ?></div><?php endif; ?>

    <form method="post" style="max-width: 500px;">
        <div class="form-row">
            <label for="name">Product Name *</label>
            <input id="name" name="name" type="text" required value="<?php echo esc($_POST['name'] ?? ''); ?>">
        </div>

        <div class="form-row">
            <label for="price">Price *</label>
            <input id="price" name="price" type="number" step="0.01" min="0.01" required value="<?php echo esc($_POST['price'] ?? ''); ?>">
        </div>

        <div class="form-row">
            <label for="stock">Stock Quantity</label>
            <input id="stock" name="stock" type="number" min="0" value="<?php echo esc($_POST['stock'] ?? '0'); ?>">
        </div>

        <div class="form-row">
            <label for="category">Category</label>
            <select id="category" name="category_id">
                <option value="">-- none --</option>
                <?php if ($cats && $cats->num_rows > 0): ?>
                    <?php while ($c = $cats->fetch_assoc()): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo (isset($_POST['category_id']) && $_POST['category_id']==$c['id'])? 'selected':''; ?>><?php echo esc($c['name']); ?></option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </div>

        <div><button class="btn btn-primary" type="submit">Add Product</button></div>
    </form>

<?php require_once 'footer.php'; ?>
