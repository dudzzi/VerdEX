<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: pages/home.html");
} else {
    header("Location: pages/login.html");
}

exit;
?>