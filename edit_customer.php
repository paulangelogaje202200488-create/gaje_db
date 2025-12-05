<?php
require_once 'header.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { header('Location: customers.php'); exit; }

$stmt = $conn->prepare('SELECT id, name, email, phone FROM customers WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$customer = $res->fetch_assoc();
$stmt->close();
if (!$customer) { header('Location: customers.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    if ($name === '') { $error = 'Name is required.'; }
    else {
        $ust = $conn->prepare('UPDATE customers SET name = ?, email = ?, phone = ? WHERE id = ?');
        $ust->bind_param('sssi', $name, $email, $phone, $id);
        $ust->execute();
        $ust->close();
        header('Location: customers.php?success=1');
        exit;
    }
}
?>

<h1>Edit Customer</h1>
<div style="margin-bottom: 20px;"><a class="btn btn-outline" href="customers.php">← Back</a></div>
<?php if ($error): ?><div class="error"><?php echo esc($error); ?></div><?php endif; ?>
<form method="post" style="max-width: 500px;">
    <div class="form-row">
        <label>Full Name *</label>
        <input name="name" value="<?php echo esc($customer['name']); ?>" required>
    </div>
    <div class="form-row">
        <label>Email</label>
        <input name="email" type="email" value="<?php echo esc($customer['email'] ?? ''); ?>">
    </div>
    <div class="form-row">
        <label>Phone</label>
        <input name="phone" value="<?php echo esc($customer['phone'] ?? ''); ?>">
    </div>
    <div><button class="btn btn-primary" type="submit">Save Changes</button></div>
</form>

<?php require_once 'footer.php'; ?>
