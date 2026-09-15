<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: home.php');
    exit;
}

$error = $_GET['error'] ?? '';
$message = '';

if ($error === 'empty') {
    $message = 'Please enter your username and password.';
} elseif ($error === 'invalid') {
    $message = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VerdEX - Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-body">
    <div class="login-panel glass-panel">
        <div class="logo-wrapper">
            <img src="../images/verdexlogo.png" alt="VerdEX Logo" class="logo-img">
        </div>

        <h2>Welcome Back</h2>
        <p class="subtitle">Sign in to access your VerdEX dashboard.</p>

        <?php if ($message !== ''): ?>
            <div class="login-error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="../backend/auth.php" method="POST" class="login-form">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" autocomplete="username" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
            </div>

            <button type="submit" class="btn-primary btn-full">Log In</button>
        </form>

        <div class="demo-login-box">
            <span class="panel-label">Temporary Demo Accounts</span>
            <p>Zandro: <strong>zandro / zandro123</strong></p>
            <p>Sam: <strong>sam / sam123</strong></p>
            <p>Jaypee: <strong>jaypee / jaypee123</strong></p>
            <p>Lorenzo: <strong>lorenzo / lorenzo123</strong></p>
        </div>
    </div>
</body>
</html>
