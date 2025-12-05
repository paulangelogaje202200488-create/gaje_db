<?php
require_once 'header.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    $stmt = $conn->prepare('INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $name, $email, $phone);
    if ($stmt->execute()) {
        $success = 'Customer added successfully!';
        $_POST = [];
    } else {
        $error = 'Error: ' . $stmt->error;
    }
    $stmt->close();
}
?>

<h1>Add Customer</h1>
<div style="margin-bottom: 20px;"><a class="btn btn-outline" href="customers.php">← Back</a></div>

<?php if ($error): ?><div class="error"><?php echo esc($error); ?></div><?php endif; ?>
<?php if ($success): ?><div class="success"><?php echo esc($success); ?></div><?php endif; ?>

<form method="post" style="max-width: 500px;">
    <div class="form-row">
        <label>Full Name *</label>
        <input name="name" type="text" required value="<?php echo esc($_POST['name'] ?? ''); ?>">
    </div>
    <div class="form-row">
        <label>Email</label>
        <input name="email" type="email" value="<?php echo esc($_POST['email'] ?? ''); ?>">
    </div>
    <div class="form-row">
        <label>Phone</label>
        <input name="phone" type="tel" value="<?php echo esc($_POST['phone'] ?? ''); ?>">
    </div>
    <div><button class="btn btn-primary" type="submit">Add Customer</button></div>
</form>

<?php require_once 'footer.php'; ?>
