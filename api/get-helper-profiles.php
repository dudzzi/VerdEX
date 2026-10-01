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
            "Only owners can manage helper profiles."
    ]);

    exit;
}


/* =========================================
   GET OWNER ID
   ========================================= */

$ownerId =
    (int) $_SESSION["user_id"];


/* =========================================
   GET HELPER PROFILES
   ========================================= */

$stmt =
    $conn->prepare("
        SELECT
            id,
            profile_name,
            avatar,
            is_active,
            created_at
        FROM helper_profiles
        WHERE owner_id = ?
        ORDER BY profile_name ASC
    ");


$stmt->bind_param(
    "i",
    $ownerId
);


$stmt->execute();

$result =
    $stmt->get_result();


$profiles = [];


while (
    $row =
        $result->fetch_assoc()
) {

    $profiles[] = [

        "id" =>
            (int) $row["id"],

        "profile_name" =>
            $row["profile_name"],

        "avatar" =>
            $row["avatar"],

        "is_active" =>
            (bool) $row["is_active"],

        "created_at" =>
            $row["created_at"]

    ];

}


/* =========================================
   SUCCESS
   ========================================= */

echo json_encode([
    "success" => true,
    "profiles" => $profiles
]);


$stmt->close();

$conn->close();

?>