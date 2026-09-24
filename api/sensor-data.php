<?php

header("Content-Type: application/json");

require_once "../backend/db.php";


// =====================================================
// ONLY ALLOW POST
// =====================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "POST request required"
    ]);

    exit;
}


// =====================================================
// GET SENSOR DATA
// =====================================================

$temperature =
    $_POST["temperature"] ?? null;

$humidity =
    $_POST["humidity"] ?? null;

$soilMoisture =
    $_POST["soil_moisture"] ?? null;


// =====================================================
// VALIDATE DATA
// =====================================================

if (
    $temperature === null ||
    $humidity === null ||
    $soilMoisture === null
) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Missing sensor data"
    ]);

    exit;
}


if (
    !is_numeric($temperature) ||
    !is_numeric($humidity) ||
    !is_numeric($soilMoisture)
) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Invalid sensor data"
    ]);

    exit;
}


$temperature = (float) $temperature;
$humidity = (float) $humidity;
$soilMoisture = (float) $soilMoisture;


// =====================================================
// 1. UPDATE CURRENT/LIVE READING
// =====================================================

$currentStmt = $conn->prepare("
    UPDATE current_sensor_status
    SET
        temperature = ?,
        humidity = ?,
        soil_moisture = ?,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = 1
");

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
        "message" =>
            "Failed to update current sensor status"
    ]);

    $currentStmt->close();
    $conn->close();

    exit;
}

$currentStmt->close();


// =====================================================
// 2. CHECK LAST HISTORICAL READING
// =====================================================

$shouldSaveHistory = false;

$lastQuery = "
    SELECT recorded_at
    FROM sensor_readings
    ORDER BY recorded_at DESC
    LIMIT 1
";

$lastResult = $conn->query($lastQuery);


if (
    !$lastResult ||
    $lastResult->num_rows === 0
) {

    // No history yet
    $shouldSaveHistory = true;

} else {

    $lastRow =
        $lastResult->fetch_assoc();

    $lastSaved =
        strtotime($lastRow["recorded_at"]);

    $fiveMinutesAgo =
        time() - 300;


    if ($lastSaved <= $fiveMinutesAgo) {
        $shouldSaveHistory = true;
    }
}


// =====================================================
// 3. SAVE HISTORY ONLY EVERY 5 MINUTES
// =====================================================

$historySaved = false;


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

    $historyStmt->bind_param(
        "ddd",
        $temperature,
        $humidity,
        $soilMoisture
    );


    if ($historyStmt->execute()) {
        $historySaved = true;
    }

    $historyStmt->close();
}


// =====================================================
// RESPONSE
// =====================================================

echo json_encode([
    "success" => true,

    "message" =>
        "Current sensor status updated",

    "history_saved" =>
        $historySaved
]);


$conn->close();

?>