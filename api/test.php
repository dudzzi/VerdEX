<?php

header("Content-Type: application/json");

echo json_encode([
    "success" => true,
    "method" => $_SERVER["REQUEST_METHOD"],
    "temperature" => $_POST["temperature"] ?? null,
    "humidity" => $_POST["humidity"] ?? null,
    "soil_moisture" => $_POST["soil_moisture"] ?? null
]);

?>