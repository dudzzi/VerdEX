<?php

header("Content-Type: application/json");

require_once "../backend/db.php";


// =====================================================
// GET CURRENT / LIVE SENSOR STATUS
// =====================================================

$currentQuery = "
    SELECT
        temperature,
        humidity,
        soil_moisture,
        updated_at
    FROM current_sensor_status
    WHERE id = 1
    LIMIT 1
";

$currentResult = $conn->query($currentQuery);


if (
    !$currentResult ||
    $currentResult->num_rows === 0
) {

    echo json_encode([
        "success" => false,
        "message" => "No current sensor status found"
    ]);

    $conn->close();
    exit;
}


$current = $currentResult->fetch_assoc();


if (
    $current["temperature"] === null ||
    $current["humidity"] === null ||
    $current["soil_moisture"] === null
) {

    echo json_encode([
        "success" => false,
        "message" => "Waiting for sensor data"
    ]);

    $conn->close();
    exit;
}


// =====================================================
// GET RECENT HISTORICAL READINGS
// =====================================================

$recentQuery = "
    SELECT
        temperature,
        humidity,
        soil_moisture,
        recorded_at
    FROM sensor_readings
    WHERE soil_moisture IS NOT NULL
    ORDER BY recorded_at DESC
    LIMIT 20
";

$recentResult = $conn->query($recentQuery);

$readings = [];


if ($recentResult) {

    while ($row = $recentResult->fetch_assoc()) {

        $readings[] = [

            "temperature" =>
                (float) $row["temperature"],

            "humidity" =>
                (float) $row["humidity"],

            "soil_moisture" =>
                (float) $row["soil_moisture"],

            "recorded_at" =>
                $row["recorded_at"]
        ];
    }
}


// Put oldest reading first
$readings = array_reverse($readings);


// =====================================================
// RETURN DATA
// =====================================================

echo json_encode([

    "success" => true,

    "temperature" =>
        (float) $current["temperature"],

    "humidity" =>
        (float) $current["humidity"],

    "soil_moisture" =>
        (float) $current["soil_moisture"],

    "updated_at" =>
        $current["updated_at"],

    "readings" =>
        $readings
]);


$conn->close();

?>