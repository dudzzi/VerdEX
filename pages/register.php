<?php

session_start();

if (
    isset($_SESSION["loggedin"]) &&
    $_SESSION["loggedin"] === true
) {

    header(
        "Location: home.php"
    );

    exit;
}


$error =
    $_GET["error"] ?? "";

$message = "";


if ($error === "empty") {

    $message =
        "Please complete all fields.";

} elseif ($error === "username") {

    $message =
        "Username must contain 3–30 letters, numbers, or underscores.";

} elseif ($error === "password") {

    $message =
        "Password must be at least 8 characters.";

} elseif ($error === "match") {

    $message =
        "Passwords do not match.";

} elseif ($error === "exists") {

    $message =
        "That username is already taken.";

} elseif ($error === "database") {

    $message =
        "Unable to create the account. Please try again.";
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        VerdEX - Create Account
    </title>

    <link
        rel="stylesheet"
        href="../css/login.css"
    >

    <link
        rel="stylesheet"
        href="../css/register.css"
    >

</head>


<body class="login-page">

<main class="login-container register-container">


    <!-- LEFT SIDE -->

    <section class="login-form-section">

        <div class="login-content">

            <div class="login-logo">

                <img
                    src="../images/verdexlogo.png"
                    alt="VerdEX"
                >

            </div>


            <div class="login-heading">

                <h1>
                    Create account
                </h1>

                <p>
                    Create your VerdEX account
                    to access the farm management
                    system.
                </p>

            </div>


            <?php if ($message !== ""): ?>

                <div class="login-error">

                    <?php
                    echo htmlspecialchars(
                        $message
                    );
                    ?>

                </div>

            <?php endif; ?>


            <form
                action="../backend/register.php"
                method="POST"
                class="login-form"
            >


                <div class="form-group">

                    <label for="full_name">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        placeholder="Enter your full name"
                        autocomplete="name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Choose a username"
                        autocomplete="username"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="At least 8 characters"
                        autocomplete="new-password"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="confirm_password">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        placeholder="Enter your password again"
                        autocomplete="new-password"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="login-button"
                >
                    Create Account
                </button>

            </form>


            <div class="register-login-link">

                <span>
                    Already have an account?
                </span>

                <a href="login.php">
                    Log in
                </a>

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
                Monitor your hydroponic farm
                and manage your daily operations
                with VerdEX.
            </p>

        </div>

    </section>


</main>

</body>

</html>