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

    <link rel="stylesheet" href="../css/login.css">
</head>

<body class="login-page">

    <main class="login-container">

        <!-- LEFT SIDE -->
        <section class="login-form-section">

            <div class="login-content">

                <!-- Logo -->
                <div class="login-logo">
                    <img src="../images/verdexlogo.png" alt="VerdEX">
                </div>

                <!-- Heading -->
                <div class="login-heading">
                    <h1>Welcome back!</h1>
                    <p>Sign in to continue to your VerdEX dashboard.</p>
                </div>

                <!-- Error Message -->
                <?php if ($message !== ''): ?>
                    <div class="login-error">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form action="../backend/auth.php" method="POST" class="login-form">

                    <div class="form-group">
                        <label for="username">Username</label>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Enter your username"
                            autocomplete="username"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox">
                            <span>Remember me</span>
                        </label>

                        <a href="#" class="forgot-password">
                            Forgot password?
                        </a>
                    </div>

                    <button type="submit" class="login-button">
                        Log In
                    </button>

                </form>

                <!-- Demo Accounts -->
                <div class="demo-account">

                    <p class="demo-title">Demo Accounts</p>

                    <p>
                        <strong>Username:</strong> zandro
                        <span>•</span>
                        <strong>Password:</strong> zandro123
                    </p>

                </div>

            </div>

        </section>


        <!-- RIGHT SIDE -->
        <section class="login-image-section">

            <div class="image-overlay"></div>

            <div class="image-content">

                <div class="image-badge">
                    SMART HYDROPONIC FARMING
                </div>

                <h2>
                    Grow smarter.<br>
                    Manage better.
                </h2>

                <p>
                    Monitor your hydroponic farm and manage your
                    daily operations with VerdEX.
                </p>

            </div>

        </section>

    </main>

</body>
</html>