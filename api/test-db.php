<?php

echo "TEST START<br>";

require_once "../config.php";

echo "CONFIG LOADED<br>";

$result = $conn->query("SELECT DATABASE() AS current_database");

if ($result) {
    $row = $result->fetch_assoc();

    echo "CONNECTED TO: " .
        htmlspecialchars($row["current_database"]);
} else {
    echo "QUERY ERROR: " .
        htmlspecialchars($conn->error);
}

?>