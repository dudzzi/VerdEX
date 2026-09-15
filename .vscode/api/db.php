<?php

$host = "sql207.infinityfree.com";
$dbname = "if0_42880574_Verdexdb1";
$username = "if0_42880574";
$password = "contactzanndro";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>