<?php

header("Content-Type: application/json");

require_once "../backend/db.php";


/* =========================================
   ONLY ALLOW POST
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
   GET OWNER USERNAME
   ========================================= */

$ownerUsername =
    trim(
        $_POST["owner_username"]
        ?? ""
    );


if ($ownerUsername === "") {

    echo json_encode([
        "success" => false,
        "message" =>
            "Please enter the owner's username."
    ]);

    exit;
}


/* =========================================
   FIND OWNER
   ========================================= */

$ownerStmt =
    $conn->prepare("
        SELECT
            id,
            username,
            full_name
        FROM users
        WHERE username = ?
        AND role = 'owner'
        LIMIT 1
    ");

$ownerStmt->bind_param(
    "s",
    $ownerUsername
);

$ownerStmt->execute();

$ownerResult =
    $ownerStmt->get_result();

$owner =
    $ownerResult->fetch_assoc();


if (!$owner) {

    $ownerStmt->close();
    $conn->close();

    echo json_encode([
        "success" => false,
        "message" =>
            "Owner account not found."
    ]);

    exit;
}


$ownerStmt->close();


/* =========================================
   FIND HELPER PROFILES
   ========================================= */

$profileStmt =
    $conn->prepare("
        SELECT
            id,
            profile_name,
            avatar
        FROM helper_profiles
        WHERE owner_id = ?
        AND is_active = 1
        ORDER BY profile_name ASC
    ");

$profileStmt->bind_param(
    "i",
    $owner["id"]
);

$profileStmt->execute();

$profileResult =
    $profileStmt->get_result();


$profiles = [];


while (
    $profile =
        $profileResult->fetch_assoc()
) {

    $profiles[] = [
        "id" =>
            (int) $profile["id"],

        "profile_name" =>
            $profile["profile_name"],

        "avatar" =>
            $profile["avatar"]
    ];
}


$profileStmt->close();
$conn->close();


/* =========================================
   RETURN RESULT
   ========================================= */

echo json_encode([
    "success" => true,

    "owner" => [
        "username" =>
            $owner["username"],

        "full_name" =>
            $owner["full_name"]
    ],

    "profiles" =>
        $profiles
]);

?>