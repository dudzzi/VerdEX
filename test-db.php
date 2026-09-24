<?php

require_once "config.php";

$result = $conn->query("SELECT DATABASE() AS db_name");

$row = $result->fetch_assoc();

echo "Connected database: " . $row["db_name"];
?>