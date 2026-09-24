<?php

$host = "sql201.infinityfree.com";
$username = "if0_42995495";
$password = "lzroQ72PGe0tq6";
$dbname = "if0_42995495_VerdexDatabase";

$conn = new mysqli($host, $username, $password, $dbname, 3306);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>