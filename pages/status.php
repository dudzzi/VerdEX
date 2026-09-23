<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| SAMPLE FARM STATUS DATA
|--------------------------------------------------------------------------
| These are temporary values for the UI.
| Later, these can be replaced with data from your database / Arduino API.
|--------------------------------------------------------------------------
*/

$currentStatus = [
    'temperature' => 28.5,
    'humidity' => 72,
    'soil_moisture' => 64,
    'light_level' => 78
];

/*
|--------------------------------------------------------------------------
| SAMPLE HISTORICAL DATA
|--------------------------------------------------------------------------
| Example: August 2026
*/

$historyData = [
    [
        'date' => 'August 1, 2026',
        'temperature' => 27.8,
        'humidity' => 74,
        'soil_moisture' => 62,
        'light_level' => 76,
        'status' => 'Good'
    ],
    [
        'date' => 'August 5, 2026',
        'temperature' => 28.4,
        'humidity' => 71,
        'soil_moisture' => 65,
        'light_level' => 79,
        'status' => 'Good'
    ],
    [
        'date' => 'August 10, 2026',
        'temperature' => 29.1,
        'humidity' => 69,
        'soil_moisture' => 61,
        'light_level' => 82,
        'status' => 'Good'
    ],
    [
        'date' => 'August 15, 2026',
        'temperature' => 30.2,
        'humidity' => 67,
        'soil_moisture' => 55,
        'light_level' => 86,
        'status' => 'Warning'
    ],
    [
        'date' => 'August 20, 2026',
        'temperature' => 28.7,
        'humidity' => 73,
        'soil_moisture' => 68,
        'light_level' => 74,
        'status' => 'Good'
    ],
    [
        'date' => 'August 25, 2026',
        'temperature' => 27.9,
        'humidity' => 76,
        'soil_moisture' => 70,
        'light_level' => 71,
        'status' => 'Good'
    ],
    [
        'date' => 'August 31, 2026',
        'temperature' => 28.3,
        'humidity' => 72,
        'soil_moisture' => 66,
        'light_level' => 77,
        'status' => 'Good'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Farm Status | VerdEX</title>

    <link rel="stylesheet" href="../css/status.css">
    <link rel="stylesheet" href="../css/status.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <!-- ================================
         SIDEBAR
    ================================= -->

    <aside class="status-sidebar">

        <a href="home.php" class="status-logo">
            <img src="../images/verdexlogo.png" alt="VerdEX">
        </a>

        <nav class="status-nav">

            <a href="home.php" title="Dashboard">
                🏠
            </a>

            <a href="inventory.php" title="Inventory">
                📦
            </a>

            <a href="calendar.php" title="Calendar">
                📅
            </a>

            <a href="sales.php" title="Sales">
                💰
            </a>

            <a href="status.php" class="active" title="Farm Status">
                💧
            </a>

            <a href="forum.php" title="Forum">
                💬
            </a>

            <a href="reports.php" title="Reports">
                📊
            </a>

        </nav>

        <div class="status-nav-bottom">

            <a href="profile.php" title="Profile">
                👤
            </a>

            <a href="settings.php" title="Settings">
                ⚙️
            </a>

            <a href="../backend/logout.php" title="Logout">
                ↪
            </a>

        </div>

    </aside>


    <!-- ================================
         MAIN CONTENT
    ================================= -->

    <main class="status-main">

        <!-- PAGE HEADER -->

        <header class="status-header">

            <div>
                <h1>Farm Status</h1>

                <p>
                    Monitor your farm's current condition and view historical data.
                </p>
            </div>

        </header>


        <!-- ================================
             CURRENT FARM STATUS
        ================================= -->

        <section class="status-section">

            <div class="status-section-header">

                <div>
                    <h2 class="status-section-title">
                        Current Farm Status
                    </h2>

                    <p class="status-section-subtitle">
                        Latest readings from your farm sensors
                    </p>
                </div>

            </div>


            <div class="status-cards">

                <!-- FARM HEALTH -->

                <div class="farm-health-card">

                    <div class="status-card-top">

                        <span class="status-card-label">
                            Overall Farm Health
                        </span>

                        <div class="status-card-icon">
                            🌱
                        </div>

                    </div>

                    <div class="status-card-value">
                        Healthy
                    </div>

                    <div class="health-status">

                        <span class="health-dot"></span>

                        All systems are operating normally

                    </div>

                </div>


                <!-- TEMPERATURE -->

                <div class="status-card">

                    <div class="status-card-top">

                        <span class="status-card-label">
                            Temperature
                        </span>

                        <div class="status-card-icon">
                            🌡️
                        </div>

                    </div>

                    <div class="status-card-value">
                        <?= $currentStatus['temperature']; ?>°C
                    </div>

                    <div class="status-card-status">
                        Normal range
                    </div>

                </div>


                <!-- HUMIDITY -->

                <div class="status-card">

                    <div class="status-card-top">

                        <span class="status-card-label">
                            Humidity
                        </span>

                        <div class="status-card-icon">
                            💧
                        </div>

                    </div>

                    <div class="status-card-value">
                        <?= $currentStatus['humidity']; ?>%
                    </div>

                    <div class="status-card-status">
                        Good level
                    </div>

                </div>


                <!-- SOIL MOISTURE -->

                <div class="status-card">

                    <div class="status-card-top">

                        <span class="status-card-label">
                            Soil Moisture
                        </span>

                        <div class="status-card-icon">
                            🌱
                        </div>

                    </div>

                    <div class="status-card-value">
                        <?= $currentStatus['soil_moisture']; ?>%
                    </div>

                    <div class="status-card-status">
                        Good moisture level
                    </div>

                </div>

            </div>


            <!-- SECOND ROW -->

            <div class="status-cards" style="margin-top: 16px;">

                <!-- LIGHT LEVEL -->

                <div class="status-card">

                    <div class="status-card-top">

                        <span class="status-card-label">
                            Light Level
                        </span>

                        <div class="status-card-icon">
                            ☀️
                        </div>

                    </div>

                    <div class="status-card-value">
                        <?= $currentStatus['light_level']; ?>%
                    </div>

                    <div class="status-card-status">
                        Good sunlight
                    </div>

                </div>

            </div>

        </section>


        <!-- ================================
             ENVIRONMENTAL OVERVIEW
        ================================= -->

        <section class="status-section">

            <div class="status-section-header">

                <div>

                    <h2 class="status-section-title">
                        Environmental Overview
                    </h2>

                    <p class="status-section-subtitle">
                        Recent environmental readings
                    </p>

                </div>

            </div>


            <div class="status-chart-card">

                <div class="chart-container" id="realtimeChart">

                    Real-time chart will appear here.

                </div>

            </div>

        </section>


        <!-- ================================
             FARM STATUS HISTORY
        ================================= -->

        <section class="status-section">

            <div class="status-section-header">

                <div>

                    <h2 class="status-section-title">
                        Farm Status History
                    </h2>

                    <p class="status-section-subtitle">
                        Review previous farm conditions by month.
                    </p>

                </div>


                <div class="status-month-selector">

                    <label for="historyMonth">
                        Month:
                    </label>

                    <select id="historyMonth">

                        <option value="2026-08" selected>
                            August 2026
                        </option>

                        <option value="2026-07">
                            July 2026
                        </option>

                        <option value="2026-06">
                            June 2026
                        </option>

                    </select>

                </div>

            </div>


            <!-- HISTORY SUMMARY -->

            <div class="history-summary">

                <div class="history-summary-card">

                    <div class="history-summary-label">
                        Average Temperature
                    </div>

                    <div class="history-summary-value">
                        28.9°C
                    </div>

                </div>


                <div class="history-summary-card">

                    <div class="history-summary-label">
                        Average Humidity
                    </div>

                    <div class="history-summary-value">
                        71.7%
                    </div>

                </div>


                <div class="history-summary-card">

                    <div class="history-summary-label">
                        Average Soil Moisture
                    </div>

                    <div class="history-summary-value">
                        63.9%
                    </div>

                </div>


                <div class="history-summary-card">

                    <div class="history-summary-label">
                        Overall Status
                    </div>

                    <div class="history-summary-value">
                        Good
                    </div>

                </div>

            </div>


            <!-- HISTORY CHART -->

            <div class="status-chart-card">

                <div class="chart-container" id="historyChart">

                    Historical chart will appear here.

                </div>

            </div>

        </section>


        <!-- ================================
             DAILY HISTORY
        ================================= -->

        <section class="status-section">

            <div class="status-section-header">

                <div>

                    <h2 class="status-section-title">
                        Daily History
                    </h2>

                    <p class="status-section-subtitle">
                        Recorded farm conditions for August 2026
                    </p>

                </div>

            </div>


            <div class="status-table-card">

                <table class="status-table">

                    <thead>

                        <tr>

                            <th>Date</th>

                            <th>Temperature</th>

                            <th>Humidity</th>

                            <th>Soil Moisture</th>

                            <th>Light Level</th>

                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($historyData as $record): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($record['date']); ?>
                                </td>

                                <td>
                                    <?= $record['temperature']; ?>°C
                                </td>

                                <td>
                                    <?= $record['humidity']; ?>%
                                </td>

                                <td>
                                    <?= $record['soil_moisture']; ?>%
                                </td>

                                <td>
                                    <?= $record['light_level']; ?>%
                                </td>

                                <td>

                                    <?php if ($record['status'] === 'Good'): ?>

                                        <span class="status-badge good">
                                            Good
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge warning">
                                            Warning
                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </section>


        <!-- ================================
             CONNECTED SENSORS
        ================================= -->

        <section class="status-section">

            <div class="status-section-header">

                <div>

                    <h2 class="status-section-title">
                        Connected Sensors
                    </h2>

                    <p class="status-section-subtitle">
                        Sensors currently connected to VerdEX
                    </p>

                </div>

            </div>


            <div class="sensor-list">

                <div class="sensor-card">

                    <div class="sensor-top">

                        <span class="sensor-name">
                            Temperature Sensor
                        </span>

                        <span class="sensor-status">

                            <span class="sensor-status-dot"></span>

                            Online

                        </span>

                    </div>

                    <div class="sensor-info">
                        Last reading: <?= $currentStatus['temperature']; ?>°C
                    </div>

                </div>


                <div class="sensor-card">

                    <div class="sensor-top">

                        <span class="sensor-name">
                            Humidity Sensor
                        </span>

                        <span class="sensor-status">

                            <span class="sensor-status-dot"></span>

                            Online

                        </span>

                    </div>

                    <div class="sensor-info">
                        Last reading: <?= $currentStatus['humidity']; ?>%
                    </div>

                </div>


                <div class="sensor-card">

                    <div class="sensor-top">

                        <span class="sensor-name">
                            Soil Moisture Sensor
                        </span>

                        <span class="sensor-status">

                            <span class="sensor-status-dot"></span>

                            Online

                        </span>

                    </div>

                    <div class="sensor-info">
                        Last reading: <?= $currentStatus['soil_moisture']; ?>%
                    </div>

                </div>

            </div>

        </section>


        <!-- ================================
             RECENT ALERTS
        ================================= -->

        <section class="status-section">

            <div class="status-section-header">

                <div>

                    <h2 class="status-section-title">
                        Recent Alerts
                    </h2>

                    <p class="status-section-subtitle">
                        Recent events that may need your attention
                    </p>

                </div>

            </div>


            <div class="alert-list">

                <div class="alert-item">

                    <div class="alert-icon">
                        ⚠️
                    </div>

                    <div class="alert-content">

                        <strong>
                            Soil moisture was low
                        </strong>

                        <span>
                            August 15, 2026 — Soil moisture reached 55%.
                        </span>

                    </div>

                </div>


                <div class="alert-item">

                    <div class="alert-icon">
                        ☀️
                    </div>

                    <div class="alert-content">

                        <strong>
                            High light level detected
                        </strong>

                        <span>
                            August 15, 2026 — Light level reached 86%.
                        </span>

                    </div>

                </div>

            </div>

        </section>

    </main>


    <!-- STATUS JAVASCRIPT -->
    <script src="../js/status.js"></script>

</body>

</html>