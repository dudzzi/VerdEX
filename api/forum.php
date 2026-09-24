<?php

session_start();

header("Content-Type: application/json");

require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

$action = $_POST["action"] ?? "";

if ($action !== "create_post") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid action."
    ]);
    exit;
}

$title = trim($_POST["title"] ?? "");
$category_id = intval($_POST["category_id"] ?? 0);
$content = trim($_POST["content"] ?? "");

if ($title === "" || $category_id <= 0 || $content === "") {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields."
    ]);
    exit;
}

$author_name = $_SESSION["username"] ?? "User";
$author_role = $_SESSION["role"] ?? "Farm Owner";

$stmt = $conn->prepare("
    INSERT INTO forum_posts
    (
        category_id,
        author_name,
        author_role,
        title,
        content
    )
    VALUES (?, ?, ?, ?, ?)
");

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed.",
        "error" => $conn->error
    ]);
    exit;
}

$stmt->bind_param(
    "issss",
    $category_id,
    $author_name,
    $author_role,
    $title,
    $content
);

if (!$stmt->execute()) {
    echo json_encode([
        "success" => false,
        "message" => "Database insert failed.",
        "error" => $stmt->error
    ]);
    exit;
}

$post_id = $stmt->insert_id;

$stmt->close();

$check = $conn->prepare("
    SELECT post_id, title, author_name
    FROM forum_posts
    WHERE post_id = ?
");

if (!$check) {
    echo json_encode([
        "success" => false,
        "message" => "Post was inserted but verification failed.",
        "post_id" => $post_id
    ]);
    exit;
}

$check->bind_param("i", $post_id);
$check->execute();

$result = $check->get_result();

if ($result && $result->num_rows === 1) {

    $post = $result->fetch_assoc();

    echo json_encode([
        "success" => true,
        "message" => "Post created successfully.",
        "post_id" => (int) $post["post_id"],
        "title" => $post["title"],
        "author" => $post["author_name"]
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "INSERT reported success, but the post could not be found afterward.",
        "post_id" => $post_id
    ]);
}

$check->close();

exit;
?>