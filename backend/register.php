<?php

session_start();

require_once "db.php";


if (
    $_SERVER["REQUEST_METHOD"]
    !== "POST"
) {

    header(
        "Location: ../pages/register.php"
    );

    exit;
}


/* =========================
   FORM DATA
   ========================= */

$fullName =
    trim(
        $_POST["full_name"]
        ?? ""
    );

$username =
    trim(
        $_POST["username"]
        ?? ""
    );

$password =
    $_POST["password"]
    ?? "";

$confirmPassword =
    $_POST["confirm_password"]
    ?? "";


/* =========================
   EMPTY FIELDS
   ========================= */

if (
    $fullName === "" ||
    $username === "" ||
    $password === "" ||
    $confirmPassword === ""
) {

    header(
        "Location: ../pages/register.php?error=empty"
    );

    exit;
}


/* =========================
   USERNAME
   ========================= */

if (
    !preg_match(
        "/^[A-Za-z0-9_]{3,30}$/",
        $username
    )
) {

    header(
        "Location: ../pages/register.php?error=username"
    );

    exit;
}


/* =========================
   PASSWORD
   ========================= */

if (
    strlen($password) < 8
) {

    header(
        "Location: ../pages/register.php?error=password"
    );

    exit;
}


if (
    $password !==
    $confirmPassword
) {

    header(
        "Location: ../pages/register.php?error=match"
    );

    exit;
}


/* =========================
   CHECK USERNAME
   ========================= */

$checkStmt =
    $conn->prepare("
        SELECT id
        FROM users
        WHERE username = ?
        LIMIT 1
    ");

$checkStmt->bind_param(
    "s",
    $username
);

$checkStmt->execute();

$result =
    $checkStmt->get_result();


if (
    $result->num_rows > 0
) {

    $checkStmt->close();
    $conn->close();

    header(
        "Location: ../pages/register.php?error=exists"
    );

    exit;
}


$checkStmt->close();


/* =========================
   CREATE ACCOUNT
   ========================= */

$hashedPassword =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );

$role = "owner";


$stmt =
    $conn->prepare("
        INSERT INTO users
        (
            username,
            password,
            full_name,
            role
        )
        VALUES (?, ?, ?, ?)
    ");

$stmt->bind_param(
    "ssss",
    $username,
    $hashedPassword,
    $fullName,
    $role
);


if (
    !$stmt->execute()
) {

    $stmt->close();
    $conn->close();

    header(
        "Location: ../pages/register.php?error=database"
    );

    exit;
}


$stmt->close();
$conn->close();


header(
    "Location: ../pages/login.php?registered=1"
);

exit;
?>