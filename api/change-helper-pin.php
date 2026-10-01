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
            "Only owners can change helper PINs."
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
   GET DATA
   ========================================= */

$helperId =
    (int) (
        $_POST["helper_id"]
        ?? 0
    );

$newPin =
    trim(
        $_POST["new_pin"]
        ?? ""
    );

$confirmPin =
    trim(
        $_POST["confirm_pin"]
        ?? ""
    );

$ownerId =
    (int) $_SESSION["user_id"];


/* =========================================
   VALIDATION
   ========================================= */

if ($helperId <= 0) {

    echo json_encode([
        "success" => false,
        "message" =>
            "Invalid helper profile."
    ]);

    exit;
}


if (
    !preg_match(
        "/^[0-9]{4}$/",
        $newPin
    )
) {

    echo json_encode([
        "success" => false,
        "message" =>
            "PIN must contain exactly 4 numbers."
    ]);

    exit;
}


if ($newPin !== $confirmPin) {

    echo json_encode([
        "success" => false,
        "message" =>
            "PINs do not match."
    ]);

    exit;
}


/* =========================================
   CHECK OWNERSHIP
   ========================================= */

$checkStmt =
    $conn->prepare("
        SELECT id
        FROM helper_profiles
        WHERE id = ?
        AND owner_id = ?
        LIMIT 1
    ");

$checkStmt->bind_param(
    "ii",
    $helperId,
    $ownerId
);

$checkStmt->execute();

$checkResult =
    $checkStmt->get_result();


if ($checkResult->num_rows === 0) {

    $checkStmt->close();
    $conn->close();

    echo json_encode([
        "success" => false,
        "message" =>
            "Helper profile not found."
    ]);

    exit;
}


$checkStmt->close();


/* =========================================
   HASH NEW PIN
   ========================================= */

$newPinHash =
    password_hash(
        $newPin,
        PASSWORD_DEFAULT
    );


/* =========================================
   UPDATE PIN
   ========================================= */

$stmt =
    $conn->prepare("
        UPDATE helper_profiles
        SET pin_hash = ?
        WHERE id = ?
        AND owner_id = ?
    ");

$stmt->bind_param(
    "sii",
    $newPinHash,
    $helperId,
    $ownerId
);


if (!$stmt->execute()) {

    $stmt->close();
    $conn->close();

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" =>
            "Unable to change helper PIN."
    ]);

    exit;
}


/* =========================================
   SUCCESS
   ========================================= */

echo json_encode([
    "success" => true,
    "message" =>
        "Helper PIN changed successfully."
]);


$stmt->close();

$conn->close();

?>