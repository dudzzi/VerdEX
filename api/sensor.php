<?php

header("Content-Type: application/json");

$host = getenv("MYSQLHOST");
$port = getenv("MYSQLPORT");
$username = getenv("MYSQLUSER");
$password = getenv("MYSQLPASSWORD");
$dbname = getenv("MYSQLDATABASE");

$conn = new mysqli(
    $host,
    $username,
    $password,
    $dbname,
    $port
);

if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed."
    ]);
    exit;
}

$conn->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    $conn->close();
    exit;
}

$temperature = isset($_POST["temperature"])
    ? (float) $_POST["temperature"]
    : null;

$humidity = isset($_POST["humidity"])
    ? (float) $_POST["humidity"]
    : null;

$soil_moisture = isset($_POST["soil_moisture"])
    ? (float) $_POST["soil_moisture"]
    : null;

if (
    $temperature === null ||
    $humidity === null ||
    $soil_moisture === null
) {
    echo json_encode([
        "success" => false,
        "message" => "Missing sensor values."
    ]);
    $conn->close();
    exit;
}

$sql = "
    INSERT INTO sensor_readings
    (
        temperature,
        humidity,
        soil_moisture
    )
    VALUES (?, ?, ?)
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Database prepare failed.",
        "error" => $conn->error
    ]);
    $conn->close();
    exit;
}

$stmt->bind_param(
    "ddd",
    $temperature,
    $humidity,
    $soil_moisture
);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Sensor reading saved.",
        "reading_id" => $stmt->insert_id
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Failed to save sensor reading.",
        "error" => $stmt->error
    ]);

}

$stmt->close();
$conn->close();

?>
?>
