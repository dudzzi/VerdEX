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
            "Only owners can edit helper profiles."
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
   FORM DATA
   ========================================= */

$helperId =
    (int) (
        $_POST["helper_id"]
        ?? 0
    );

$profileName =
    trim(
        $_POST["profile_name"]
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
   CHECK DUPLICATE NAME
   ========================================= */

$duplicateStmt =
    $conn->prepare("
        SELECT id
        FROM helper_profiles
        WHERE owner_id = ?
        AND profile_name = ?
        AND id != ?
        LIMIT 1
    ");

$duplicateStmt->bind_param(
    "isi",
    $ownerId,
    $profileName,
    $helperId
);

$duplicateStmt->execute();

$duplicateResult =
    $duplicateStmt->get_result();


if ($duplicateResult->num_rows > 0) {

    $duplicateStmt->close();
    $conn->close();

    echo json_encode([
        "success" => false,
        "message" =>
            "A helper profile with that name already exists."
    ]);

    exit;
}


$duplicateStmt->close();


/* =========================================
   UPDATE NAME
   ========================================= */

$stmt =
    $conn->prepare("
        UPDATE helper_profiles
        SET profile_name = ?
        WHERE id = ?
        AND owner_id = ?
    ");

$stmt->bind_param(
    "sii",
    $profileName,
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
            "Unable to update helper profile."
    ]);

    exit;
}


/* =========================================
   SUCCESS
   ========================================= */

echo json_encode([
    "success" => true,
    "message" =>
        "Helper profile updated successfully."
]);


$stmt->close();

$conn->close();

?>