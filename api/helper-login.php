<?php

session_start();

header("Content-Type: application/json");

require_once "../backend/db.php";


/* =========================================
   POST REQUEST ONLY
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

$profileId =
    (int) (
        $_POST["profile_id"]
        ?? 0
    );

$pin =
    trim(
        $_POST["pin"]
        ?? ""
    );


/* =========================================
   VALIDATION
   ========================================= */

if ($profileId <= 0) {

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
        $pin
    )
) {

    echo json_encode([
        "success" => false,
        "message" =>
            "Please enter your 4-digit PIN."
    ]);

    exit;
}


/* =========================================
   FIND HELPER PROFILE
   ========================================= */

$stmt =
    $conn->prepare("
        SELECT
            hp.id,
            hp.owner_id,
            hp.profile_name,
            hp.pin_hash,
            hp.avatar,

            u.username AS owner_username,
            u.full_name AS owner_name

        FROM helper_profiles hp

        INNER JOIN users u
            ON hp.owner_id = u.id

        WHERE hp.id = ?
        AND hp.is_active = 1
        AND u.role = 'owner'

        LIMIT 1
    ");


$stmt->bind_param(
    "i",
    $profileId
);


$stmt->execute();

$result =
    $stmt->get_result();

$helper =
    $result->fetch_assoc();


/* =========================================
   PROFILE NOT FOUND
   ========================================= */

if (!$helper) {

    $stmt->close();
    $conn->close();

    echo json_encode([
        "success" => false,
        "message" =>
            "Helper profile not found."
    ]);

    exit;
}


/* =========================================
   VERIFY PIN
   ========================================= */

if (
    !password_verify(
        $pin,
        $helper["pin_hash"]
    )
) {

    $stmt->close();
    $conn->close();

    echo json_encode([
        "success" => false,
        "message" =>
            "Incorrect PIN."
    ]);

    exit;
}


/* =========================================
   HELPER LOGIN SUCCESS
   ========================================= */

session_regenerate_id(true);

$_SESSION["loggedin"] = true;

$_SESSION["role"] =
    "helper";

$_SESSION["helper_id"] =
    (int) $helper["id"];

$_SESSION["owner_id"] =
    (int) $helper["owner_id"];

$_SESSION["full_name"] =
    $helper["profile_name"];

$_SESSION["owner_username"] =
    $helper["owner_username"];


/* =========================================
   RETURN SUCCESS
   ========================================= */

echo json_encode([
    "success" => true,

    "message" =>
        "Login successful.",

    "redirect" =>
        "../pages/home.php"
]);


$stmt->close();
$conn->close();

?>