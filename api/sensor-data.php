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

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database connection failed."
    ]);

    exit;
}

$conn->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "POST request required."
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

$soilMoisture = isset($_POST["soil_moisture"])
    ? (float) $_POST["soil_moisture"]
    : null;

if (
    $temperature === null ||
    $humidity === null ||
    $soilMoisture === null
) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Missing sensor values."
    ]);

    $conn->close();

    exit;
}

$currentStmt = $conn->prepare("
    UPDATE current_sensor_status
    SET
        temperature = ?,
        humidity = ?,
        soil_moisture = ?,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = 1
");

if (!$currentStmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to prepare current status update."
    ]);

    $conn->close();

    exit;
}

$currentStmt->bind_param(
    "ddd",
    $temperature,
    $humidity,
    $soilMoisture
);

if (!$currentStmt->execute()) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to update current sensor status."
    ]);

    $currentStmt->close();
    $conn->close();

    exit;
}

$currentStmt->close();

$shouldSaveHistory = false;

$lastQuery = "
    SELECT recorded_at
    FROM sensor_readings
    ORDER BY recorded_at DESC
    LIMIT 1
";

$lastResult = $conn->query($lastQuery);

if (!$lastResult) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to check sensor history."
    ]);

    $conn->close();

    exit;
}

if ($lastResult->num_rows === 0) {

    $shouldSaveHistory = true;

} else {

    $lastRow = $lastResult->fetch_assoc();

    $lastSaved = strtotime($lastRow["recorded_at"]);
    $fiveMinutesAgo = time() - 300;

    if ($lastSaved <= $fiveMinutesAgo) {
        $shouldSaveHistory = true;
    }
}

$historySaved = false;
$readingId = null;

if ($shouldSaveHistory) {

    $historyStmt = $conn->prepare("
        INSERT INTO sensor_readings
        (
            temperature,
            humidity,
            soil_moisture
        )
        VALUES (?, ?, ?)
    ");

    if (!$historyStmt) {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Failed to prepare history insert."
        ]);

        $conn->close();

        exit;
    }

    $historyStmt->bind_param(
        "ddd",
        $temperature,
        $humidity,
        $soilMoisture
    );

    if ($historyStmt->execute()) {

        $historySaved = true;
        $readingId = $historyStmt->insert_id;

    }

    $historyStmt->close();
}

echo json_encode([
    "success" => true,
    "message" => "Sensor data received successfully.",
    "temperature" => $temperature,
    "humidity" => $humidity,
    "soil_moisture" => $soilMoisture,
    "history_saved" => $historySaved,
    "reading_id" => $readingId
]);

$conn->close();

?>
