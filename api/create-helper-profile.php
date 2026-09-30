<?php

session_start();

header("Content-Type: application/json");

require_once "../backend/db.php";


/* =========================================
   CHECK LOGIN
   ========================================= */

if (
    !isset($_SESSION["loggedin"]) ||
    $_SESSION["loggedin"] !== true
) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "You must be logged in."
    ]);

    exit;
}


/* =========================================
   OWNER ONLY
   ========================================= */

if (
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] !== "owner"
) {

    http_response_code(403);

    echo json_encode([
        "success" => false,
        "message" =>
            "Only owners can create helper profiles."
    ]);

    exit;
}


/* =========================================
   POST ONLY
   ========================================= */

if (
    $_SERVER["REQUEST_METHOD"] !== "POST"
) {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "POST request required."
    ]);

    exit;
}


/* =========================================
   GET FORM DATA
   ========================================= */

$profileName =
    trim(
        $_POST["profile_name"]
        ?? ""
    );

$pin =
    $_POST["pin"]
    ?? "";

$confirmPin =
    $_POST["confirm_pin"]
    ?? "";

$ownerId =
    (int) $_SESSION["user_id"];


/* =========================================
   VALIDATION
   ========================================= */

if (
    $profileName === "" ||
    $pin === "" ||
    $confirmPin === ""
) {

    echo json_encode([
        "success" => false,
        "message" =>
            "Please complete all fields."
    ]);

    exit;
}


if (
    strlen($profileName) < 2 ||
    strlen($profileName) > 100
) {

    echo json_encode([
        "success" => false,
        "message" =>
            "Helper name must be between 2 and 100 characters."
    ]);

    exit;
}


/* PIN must be exactly 4 numbers */

if (
    !preg_match(
        "/^[0-9]{4}$/",
        $pin
    )
) {

    echo json_encode([
        "success" => false,
        "message" =>
            "PIN must contain exactly 4 numbers."
    ]);

    exit;
}


if ($pin !== $confirmPin) {

    echo json_encode([
        "success" => false,
        "message" =>
            "PINs do not match."
    ]);

    exit;
}


/* =========================================
   CHECK DUPLICATE PROFILE NAME
   ========================================= */

$checkStmt =
    $conn->prepare("
        SELECT id
        FROM helper_profiles
        WHERE owner_id = ?
        AND profile_name = ?
        LIMIT 1
    ");

$checkStmt->bind_param(
    "is",
    $ownerId,
    $profileName
);

$checkStmt->execute();

$checkResult =
    $checkStmt->get_result();


if ($checkResult->num_rows > 0) {

    $checkStmt->close();
    $conn->close();

    echo json_encode([
        "success" => false,
        "message" =>
            "A helper profile with that name already exists."
    ]);

    exit;
}


$checkStmt->close();


/* =========================================
   HASH PIN
   ========================================= */

$pinHash =
    password_hash(
        $pin,
        PASSWORD_DEFAULT
    );


/* =========================================
   SAVE PROFILE
   ========================================= */

$stmt =
    $conn->prepare("
        INSERT INTO helper_profiles
        (
            owner_id,
            profile_name,
            pin_hash
        )
        VALUES (?, ?, ?)
    ");

$stmt->bind_param(
    "iss",
    $ownerId,
    $profileName,
    $pinHash
);


if (!$stmt->execute()) {

    $stmt->close();
    $conn->close();

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" =>
            "Unable to create helper profile."
    ]);

    exit;
}


/* =========================================
   SUCCESS
   ========================================= */

echo json_encode([
    "success" => true,
    "message" =>
        "Helper profile created successfully."
]);


$stmt->close();
$conn->close();

?>