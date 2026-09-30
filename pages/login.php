<?php

session_start();

if (
    isset($_SESSION["loggedin"]) &&
    $_SESSION["loggedin"] === true
) {
    header("Location: home.php");
    exit;
}

$error =
    $_GET["error"] ?? "";

$registered =
    $_GET["registered"] ?? "";

$message = "";
$successMessage = "";

if ($error === "empty") {

    $message =
        "Please enter your username and password.";

} elseif ($error === "invalid") {

    $message =
        "Invalid username or password.";
}

if ($registered === "1") {

    $successMessage =
        "Account created successfully. You can now log in.";
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
        VerdEX - Login
    </title>

    <link
        rel="stylesheet"
        href="../css/login.css"
    >

</head>


<body class="login-page">

<main class="login-container">


    <!-- LEFT SIDE -->

    <section class="login-form-section">

        <div class="login-content">


            <!-- LOGO -->

            <div class="login-logo">

                <img
                    src="../images/verdexlogo.png"
                    alt="VerdEX"
                >

            </div>


            <!-- HEADING -->

            <div class="login-heading">

                <h1>
                    Welcome back!
                </h1>

                <p>
                    Choose how you want to
                    access VerdEX.
                </p>

            </div>


            <!-- OWNER / HELPER TOGGLE -->

            <div class="login-role-toggle">

                <button
                    type="button"
                    class="login-role-button active"
                    id="ownerToggle"
                >
                    Owner
                </button>

                <button
                    type="button"
                    class="login-role-button"
                    id="helperToggle"
                >
                    Helper
                </button>

            </div>


            <!-- SUCCESS MESSAGE -->

            <?php if ($successMessage !== ""): ?>

                <div class="login-success">

                    <?php
                    echo htmlspecialchars(
                        $successMessage
                    );
                    ?>

                </div>

            <?php endif; ?>


            <!-- ERROR MESSAGE -->

            <?php if ($message !== ""): ?>

                <div class="login-error">

                    <?php
                    echo htmlspecialchars(
                        $message
                    );
                    ?>

                </div>

            <?php endif; ?>


            <!-- ============================
                 OWNER LOGIN
                 ============================ -->

            <div
                class="login-mode-section"
                id="ownerLoginSection"
            >

                <form
                    action="../backend/auth.php"
                    method="POST"
                    class="login-form"
                >

                    <div class="form-group">

                        <label for="username">
                            Username
                        </label>

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

                        <label for="password">
                            Password
                        </label>

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

                            <input
                                type="checkbox"
                                name="remember"
                            >

                            <span>
                                Remember me
                            </span>

                        </label>


                        <a
                            href="#"
                            class="forgot-password"
                        >
                            Forgot password?
                        </a>

                    </div>


                    <button
                        type="submit"
                        class="login-button"
                    >
                        Log In
                    </button>

                </form>


                <div class="create-account-link">

                    <span>
                        Don't have an account?
                    </span>

                    <a href="register.php">
                        Create account
                    </a>

                </div>

            </div>


            <!-- ============================
                 HELPER LOGIN
                 ============================ -->

            <div
                class="login-mode-section hidden"
                id="helperLoginSection"
            >

                <div class="helper-login-heading">

                    <h3>
                        Find your helper profile
                    </h3>

                    <p>
                        Enter the username of
                        your farm owner.
                    </p>

                </div>


                <div class="helper-search-form">

                    <div class="form-group">

                        <label for="ownerUsername">
                            Owner Username
                        </label>

                        <input
                            type="text"
                            id="ownerUsername"
                            placeholder="Enter owner's username"
                            autocomplete="off"
                        >

                    </div>


                    <button
                        type="button"
                        class="login-button"
                        id="searchOwnerButton"
                    >
                        Search
                    </button>

                </div>


                <!-- Profiles will appear here later -->

                <div
                    class="helper-profile-results"
                    id="helperProfileResults"
                >

                    <p class="helper-empty-message">
                        Helper profiles will appear
                        here after searching.
                    </p>

                </div>

            </div>


        </div>

    </section>



    <!-- RIGHT SIDE -->

    <section class="login-image-section">

        <div class="image-overlay">
        </div>


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
<!-- =========================================
     HELPER PIN MODAL
     ========================================= -->

<div
    class="helper-pin-overlay"
    id="helperPinOverlay"
>

    <div class="helper-pin-modal">

        <button
            type="button"
            class="helper-pin-close"
            id="closeHelperPin"
        >
            ×
        </button>


        <div class="helper-pin-avatar"
             id="helperPinAvatar">
            H
        </div>


        <h2 id="helperPinName">
            Helper
        </h2>


        <p>
            Enter your 4-digit PIN
            to continue.
        </p>


        <input
            type="hidden"
            id="selectedHelperId"
        >


        <input
            type="password"
            id="helperLoginPin"
            class="helper-pin-input"
            maxlength="4"
            inputmode="numeric"
            placeholder="••••"
        >


        <div
            class="helper-pin-message"
            id="helperPinMessage"
        ></div>


        <button
            type="button"
            class="login-button"
            id="helperPinContinue"
        >
            Continue
        </button>

    </div>

</div>

<script src="../js/login.js"></script>

</body>

</html>