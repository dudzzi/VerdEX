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
        "message" => "Waiting for sensor data."
    ]);

    $conn->close();

    exit;
}

$current = $currentResult->fetch_assoc();

$recentQuery = "
    SELECT
        temperature,
        humidity,
        soil_moisture,
        recorded_at
    FROM sensor_readings
    ORDER BY recorded_at DESC
    LIMIT 20
";

$recentResult = $conn->query($recentQuery);

$readings = [];

if ($recentResult) {

    while ($row = $recentResult->fetch_assoc()) {

        $readings[] = [
            "temperature" => (float) $row["temperature"],
            "humidity" => (float) $row["humidity"],
            "soil_moisture" => (float) $row["soil_moisture"],
            "recorded_at" => $row["recorded_at"]
        ];
    }
}

$readings = array_reverse($readings);

echo json_encode([
    "success" => true,

    "temperature" => (float) $current["temperature"],

    "humidity" => (float) $current["humidity"],

    "soil_moisture" => (float) $current["soil_moisture"],

    "updated_at" => $current["updated_at"],

    "readings" => $readings
]);

$conn->close();

?>
