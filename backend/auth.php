<?php

session_start();

require_once "db.php";

$username =
    trim($_POST["username"] ?? "");

$password =
    $_POST["password"] ?? "";


/* =========================
   VALIDATION
   ========================= */

if (
    $username === "" ||
    $password === ""
) {

    header(
        "Location: ../pages/login.php?error=empty"
    );

    exit;
}


/* =========================
   FIND USER
   ========================= */

$stmt = $conn->prepare("
    SELECT
        id,
        username,
        password,
        full_name,
        role
    FROM users
    WHERE username = ?
    AND role = 'owner'
    LIMIT 1
");

$stmt->bind_param(
    "s",
    $username
);

$stmt->execute();

$result =
    $stmt->get_result();

$user =
    $result->fetch_assoc();


/* =========================
   VERIFY ACCOUNT
   ========================= */

if (
    !$user ||
    !password_verify(
        $password,
        $user["password"]
    )
) {

    $stmt->close();
    $conn->close();

    header(
        "Location: ../pages/login.php?error=invalid"
    );

    exit;
}


/* =========================
   LOGIN SUCCESS
   ========================= */

session_regenerate_id(true);

$_SESSION["loggedin"] = true;

$_SESSION["user_id"] =
    (int) $user["id"];

$_SESSION["username"] =
    $user["username"];

$_SESSION["full_name"] =
    $user["full_name"];

$_SESSION["role"] =
    $user["role"];


$stmt->close();
$conn->close();

header(
    "Location: ../pages/home.php"
);

exit;
?>