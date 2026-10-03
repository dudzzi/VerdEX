<?php

require_once "../backend/access-control.php";

requireLogin();

require_once "../backend/db.php";


// =====================================================
// CURRENT SENSOR STATUS
// =====================================================

$currentStatus = [
    'temperature' => null,
    'humidity' => null,
    'soil_moisture' => null,
    'recorded_at' => null
];

$historyData = [];
$availableMonths = [];
$alerts = [];

$selectedMonth = $_GET['month'] ?? date('Y-m');


// =====================================================
// GET LATEST SENSOR READING
// =====================================================

$latestQuery = "
    SELECT
        temperature,
        humidity,
        soil_moisture,
        recorded_at
    FROM sensor_readings
    WHERE soil_moisture IS NOT NULL
    ORDER BY recorded_at DESC
    LIMIT 1
";

$latestResult = $conn->query($latestQuery);

if ($latestResult && $latestResult->num_rows > 0) {
    $currentStatus = $latestResult->fetch_assoc();
}


// =====================================================
// GET AVAILABLE MONTHS
// =====================================================

$monthQuery = "
    SELECT DISTINCT
        DATE_FORMAT(recorded_at, '%Y-%m') AS month_value
    FROM sensor_readings
    WHERE soil_moisture IS NOT NULL
    ORDER BY month_value DESC
";

$monthResult = $conn->query($monthQuery);

if ($monthResult) {

    while ($month = $monthResult->fetch_assoc()) {
        $availableMonths[] = $month['month_value'];
    }
}

if (empty($availableMonths)) {
    $availableMonths[] = date('Y-m');
}

if (!in_array($selectedMonth, $availableMonths, true)) {
    $selectedMonth = $availableMonths[0];
}


// =====================================================
// SELECTED MONTH
// =====================================================

$selectedMonthDate =
    DateTime::createFromFormat('Y-m', $selectedMonth);

if ($selectedMonthDate) {
    $monthLabel = $selectedMonthDate->format('F Y');
} else {
    $monthLabel = date('F Y');
}

$startDate = $selectedMonth . '-01';

$endDate = date(
    'Y-m-t',
    strtotime($startDate)
);


// =====================================================
// DAILY HISTORY
// =====================================================

$historyQuery = "
    SELECT
        DATE(recorded_at) AS reading_date,
        AVG(temperature) AS temperature,
        AVG(humidity) AS humidity,
        AVG(soil_moisture) AS soil_moisture,
        MAX(recorded_at) AS last_recorded
    FROM sensor_readings
    WHERE recorded_at >= ?
      AND recorded_at < DATE_ADD(?, INTERVAL 1 DAY)
      AND soil_moisture IS NOT NULL
    GROUP BY DATE(recorded_at)
    ORDER BY reading_date ASC
";

$historyStmt = $conn->prepare($historyQuery);

if ($historyStmt) {

    $historyStmt->bind_param(
        "ss",
        $startDate,
        $endDate
    );

    $historyStmt->execute();

    $historyResult = $historyStmt->get_result();

    while ($record = $historyResult->fetch_assoc()) {

        $temperature =
            (float) $record['temperature'];

        $humidity =
            (float) $record['humidity'];

        $soilMoisture =
            (float) $record['soil_moisture'];


        $recordStatus = 'Good';

        if (
            $temperature < 18 ||
            $temperature > 32 ||
            $humidity < 50 ||
            $humidity > 80 ||
            $soilMoisture < 40 ||
            $soilMoisture > 80
        ) {
            $recordStatus = 'Warning';
        }


        $historyData[] = [

            'date' => date(
                'F j, Y',
                strtotime($record['reading_date'])
            ),

            'temperature' =>
                round($temperature, 1),

            'humidity' =>
                round($humidity, 1),

            'soil_moisture' =>
                round($soilMoisture, 1),

            'status' =>
                $recordStatus
        ];
    }

    $historyStmt->close();
}


// =====================================================
// MONTHLY SUMMARY
// =====================================================

$averageTemperature = 0;
$averageHumidity = 0;
$averageSoilMoisture = 0;

$overallHistoryStatus = 'No Data';


$summaryQuery = "
    SELECT
        AVG(temperature) AS average_temperature,
        AVG(humidity) AS average_humidity,
        AVG(soil_moisture) AS average_soil_moisture
    FROM sensor_readings
    WHERE recorded_at >= ?
      AND recorded_at < DATE_ADD(?, INTERVAL 1 DAY)
      AND soil_moisture IS NOT NULL
";

$summaryStmt = $conn->prepare($summaryQuery);

if ($summaryStmt) {

    $summaryStmt->bind_param(
        "ss",
        $startDate,
        $endDate
    );

    $summaryStmt->execute();

    $summaryResult =
        $summaryStmt->get_result();


    if (
        $summaryResult &&
        $summaryResult->num_rows > 0
    ) {

        $summary =
            $summaryResult->fetch_assoc();


        if (
            $summary['average_temperature']
            !== null
        ) {

            $averageTemperature = round(
                (float)
                $summary['average_temperature'],
                1
            );

            $averageHumidity = round(
                (float)
                $summary['average_humidity'],
                1
            );

            $averageSoilMoisture = round(
                (float)
                $summary['average_soil_moisture'],
                1
            );


            $overallHistoryStatus = 'Good';

            if (
                $averageTemperature < 18 ||
                $averageTemperature > 32 ||
                $averageHumidity < 50 ||
                $averageHumidity > 80 ||
                $averageSoilMoisture < 40 ||
                $averageSoilMoisture > 80
            ) {
                $overallHistoryStatus =
                    'Warning';
            }
        }
    }

    $summaryStmt->close();
}


// =====================================================
// CURRENT VALUES
// =====================================================

$currentTemperature =
    $currentStatus['temperature'];

$currentHumidity =
    $currentStatus['humidity'];

$currentSoilMoisture =
    $currentStatus['soil_moisture'];


// =====================================================
// OVERALL FARM HEALTH
// =====================================================

$overallHealth = 'No Data';

$overallHealthMessage =
    'Waiting for sensor readings.';


if (
    $currentTemperature !== null &&
    $currentHumidity !== null &&
    $currentSoilMoisture !== null
) {

    $overallHealth = 'Healthy';

    $overallHealthMessage =
        'All systems are operating normally';


    if (
        $currentTemperature < 18 ||
        $currentTemperature > 32 ||
        $currentHumidity < 50 ||
        $currentHumidity > 80 ||
        $currentSoilMoisture < 40 ||
        $currentSoilMoisture > 80
    ) {

        $overallHealth = 'Warning';

        $overallHealthMessage =
            'One or more readings need attention';
    }
}


// =====================================================
// SENSOR ONLINE STATUS
// =====================================================

$sensorOnline = false;

if (!empty($currentStatus['recorded_at'])) {

    $lastReadingTime =
        strtotime($currentStatus['recorded_at']);

    if ($lastReadingTime !== false) {

        $sensorOnline =
            (time() - $lastReadingTime)
            <= 300;
    }
}


// =====================================================
// ALERTS
// =====================================================

$alertsQuery = "
    SELECT
        temperature,
        humidity,
        soil_moisture,
        recorded_at
    FROM sensor_readings
    WHERE soil_moisture IS NOT NULL
      AND (
            temperature < 18
            OR temperature > 32
            OR humidity < 50
            OR humidity > 80
            OR soil_moisture < 40
            OR soil_moisture > 80
      )
    ORDER BY recorded_at DESC
    LIMIT 5
";

$alertsResult =
    $conn->query($alertsQuery);


if ($alertsResult) {

    while (
        $alert =
        $alertsResult->fetch_assoc()
    ) {

        $alertMessages = [];


        if (
            (float) $alert['temperature'] < 18 ||
            (float) $alert['temperature'] > 32
        ) {

            $alertMessages[] =
                'Temperature reached ' .
                $alert['temperature'] .
                '°C.';
        }


        if (
            (float) $alert['humidity'] < 50 ||
            (float) $alert['humidity'] > 80
        ) {

            $alertMessages[] =
                'Humidity reached ' .
                $alert['humidity'] .
                '%.';
        }


        if (
            (float) $alert['soil_moisture'] < 40 ||
            (float) $alert['soil_moisture'] > 80
        ) {

            $alertMessages[] =
                'Soil moisture reached ' .
                $alert['soil_moisture'] .
                '%.';
        }


        if (!empty($alertMessages)) {

            $alerts[] = [

                'date' => date(
                    'F j, Y g:i A',
                    strtotime(
                        $alert['recorded_at']
                    )
                ),

                'message' =>
                    implode(
                        ' ',
                        $alertMessages
                    )
            ];
        }
    }
}


// =====================================================
// HISTORY CHART DATA
// =====================================================

$chartLabels = [];
$chartTemperature = [];
$chartHumidity = [];
$chartSoilMoisture = [];

foreach ($historyData as $record) {

    $chartLabels[] =
        $record['date'];

    $chartTemperature[] =
        $record['temperature'];

    $chartHumidity[] =
        $record['humidity'];

    $chartSoilMoisture[] =
        $record['soil_moisture'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Farm Status | VerdEX</title>

    <link
        rel="stylesheet"
        href="../css/status.css"
    >

    <link
        rel="stylesheet"
        href="../css/profile-menu.css"
    >

    <link
        rel="stylesheet"
        href="../css/ai-assistant.css"
    >

    <script src="../js/vendor/chart.umd.min.js"></script>

</head>

<body>


<!-- ==================================================
SIDEBAR
================================================== -->

<aside class="status-sidebar">

    <a
        href="home.php"
        class="status-logo"
    >

        <img
            src="../images/verdexlogo.png"
            alt="VerdEX"
        >

    </a>


    <nav class="status-nav">

        <a href="home.php" title="Home">
            🏠
        </a>

        <a href="inventory.php" title="Inventory">
            📦
        </a>

        <a href="calendar.php" title="Calendar">
            📅
        </a>


        <?php if (($_SESSION["role"] ?? "") === "owner"): ?>

            <a href="sales.php" title="Sales">
                💰
            </a>

        <?php endif; ?>


        <a href="status.php" title="Farm Status">
            🌱
        </a>

        <a href="forum.php" title="Forum">
            💬
        </a>


        <?php if (($_SESSION["role"] ?? "") === "owner"): ?>

            <a href="reports.php" title="Reports">
                📊
            </a>

        <?php endif; ?>

    </nav>


    <div class="sidebar-bottom">

        <a
            href="#"
            class="profile-menu-toggle"
            title="Profile"
        >
            👤
        </a>

        <a
            href="../backend/logout.php"
            title="Logout"
        >
            ↪
        </a>

    </div>

</aside>

<?php
require "../includes/profile-menu.php";
?>
<!-- ==================================================
MAIN
================================================== -->

<main class="status-main">


<header class="status-header">

    <div>

        <h1>Farm Status</h1>

        <p>
            Monitor your farm's current condition
            and view historical data.
        </p>

    </div>

</header>


<!-- ==================================================
CURRENT FARM STATUS
================================================== -->

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


        <!-- Overall Health -->

        <div class="farm-health-card">

            <div class="status-card-top">

                <span class="status-card-label">
                    Overall Farm Health
                </span>

                <div class="status-card-icon">
                    🌱
                </div>

            </div>


            <div
                class="status-card-value"
                id="liveOverallHealth"
            >
                <?= htmlspecialchars(
                    $overallHealth
                ); ?>
            </div>


            <div
                class="health-status"
                id="liveOverallHealthMessage"
            >

                <span class="health-dot"></span>

                <?= htmlspecialchars(
                    $overallHealthMessage
                ); ?>

            </div>

        </div>


        <!-- Temperature -->

        <div class="status-card">

            <div class="status-card-top">

                <span class="status-card-label">
                    Temperature
                </span>

                <div class="status-card-icon">
                    🌡️
                </div>

            </div>


            <div
                class="status-card-value"
                id="liveTemperature"
            >

                <?= $currentTemperature !== null
                    ? number_format(
                        (float) $currentTemperature,
                        1
                    ) . '°C'
                    : 'No Data';
                ?>

            </div>


            <div
                class="status-card-status"
                id="liveTemperatureStatus"
            >

                <?php if (
                    $currentTemperature !== null
                ): ?>

                    <?php if (
                        $currentTemperature >= 18 &&
                        $currentTemperature <= 32
                    ): ?>

                        Normal range

                    <?php else: ?>

                        Warning level

                    <?php endif; ?>

                <?php else: ?>

                    Waiting for reading

                <?php endif; ?>

            </div>

        </div>


        <!-- Humidity -->

        <div class="status-card">

            <div class="status-card-top">

                <span class="status-card-label">
                    Humidity
                </span>

                <div class="status-card-icon">
                    💧
                </div>

            </div>


            <div
                class="status-card-value"
                id="liveHumidity"
            >

                <?= $currentHumidity !== null
                    ? number_format(
                        (float) $currentHumidity,
                        1
                    ) . '%'
                    : 'No Data';
                ?>

            </div>


            <div
                class="status-card-status"
                id="liveHumidityStatus"
            >

                <?php if (
                    $currentHumidity !== null
                ): ?>

                    <?php if (
                        $currentHumidity >= 50 &&
                        $currentHumidity <= 80
                    ): ?>

                        Good level

                    <?php else: ?>

                        Warning level

                    <?php endif; ?>

                <?php else: ?>

                    Waiting for reading

                <?php endif; ?>

            </div>

        </div>


        <!-- Soil Moisture -->

        <div class="status-card">

            <div class="status-card-top">

                <span class="status-card-label">
                    Soil Moisture
                </span>

                <div class="status-card-icon">
                    🌱
                </div>

            </div>


            <div
                class="status-card-value"
                id="liveSoilMoisture"
            >

                <?= $currentSoilMoisture !== null
                    ? number_format(
                        (float) $currentSoilMoisture,
                        1
                    ) . '%'
                    : 'No Data';
                ?>

            </div>


            <div
                class="status-card-status"
                id="liveSoilMoistureStatus"
            >

                <?php if (
                    $currentSoilMoisture !== null
                ): ?>

                    <?php if (
                        $currentSoilMoisture >= 40 &&
                        $currentSoilMoisture <= 80
                    ): ?>

                        Good moisture level

                    <?php else: ?>

                        Warning level

                    <?php endif; ?>

                <?php else: ?>

                    Waiting for reading

                <?php endif; ?>

            </div>

        </div>


    </div>

</section>


<!-- ==================================================
ENVIRONMENTAL OVERVIEW
================================================== -->

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

        <div class="chart-container">

            <canvas id="realtimeChart"></canvas>

        </div>

    </div>

</section>


<!-- ==================================================
HISTORY
================================================== -->

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

            <select
                id="historyMonth"
                onchange="changeHistoryMonth(this.value)"
            >

                <?php foreach (
                    $availableMonths as $month
                ): ?>

                    <?php

                    $monthDate =
                        DateTime::createFromFormat(
                            'Y-m',
                            $month
                        );

                    $monthText =
                        $monthDate
                        ? $monthDate->format('F Y')
                        : $month;

                    ?>

                    <option
                        value="<?= htmlspecialchars(
                            $month
                        ); ?>"
                        <?= $month === $selectedMonth
                            ? 'selected'
                            : '';
                        ?>
                    >

                        <?= htmlspecialchars(
                            $monthText
                        ); ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

    </div>


    <div class="history-summary">


        <div class="history-summary-card">

            <div class="history-summary-label">
                Average Temperature
            </div>

            <div class="history-summary-value">

                <?= $averageTemperature > 0
                    ? $averageTemperature . '°C'
                    : 'No Data';
                ?>

            </div>

        </div>


        <div class="history-summary-card">

            <div class="history-summary-label">
                Average Humidity
            </div>

            <div class="history-summary-value">

                <?= $averageHumidity > 0
                    ? $averageHumidity . '%'
                    : 'No Data';
                ?>

            </div>

        </div>


        <div class="history-summary-card">

            <div class="history-summary-label">
                Average Soil Moisture
            </div>

            <div class="history-summary-value">

                <?= $averageSoilMoisture > 0
                    ? $averageSoilMoisture . '%'
                    : 'No Data';
                ?>

            </div>

        </div>


        <div class="history-summary-card">

            <div class="history-summary-label">
                Overall Status
            </div>

            <div class="history-summary-value">

                <?= htmlspecialchars(
                    $overallHistoryStatus
                ); ?>

            </div>

        </div>


    </div>


    <div class="status-chart-card">

        <div class="chart-container">

            <canvas id="historyChart"></canvas>

        </div>

    </div>

</section>


<!-- ==================================================
DAILY HISTORY
================================================== -->

<section class="status-section">

    <div class="status-section-header">

        <div>

            <h2 class="status-section-title">
                Daily History
            </h2>

            <p class="status-section-subtitle">

                Recorded farm conditions for
                <?= htmlspecialchars(
                    $monthLabel
                ); ?>

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
                    <th>Status</th>

                </tr>

            </thead>


            <tbody>

            <?php if (
                !empty($historyData)
            ): ?>


                <?php foreach (
                    $historyData as $record
                ): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars(
                                $record['date']
                            ); ?>
                        </td>

                        <td>
                            <?= $record[
                                'temperature'
                            ]; ?>°C
                        </td>

                        <td>
                            <?= $record[
                                'humidity'
                            ]; ?>%
                        </td>

                        <td>
                            <?= $record[
                                'soil_moisture'
                            ]; ?>%
                        </td>

                        <td>

                            <?php if (
                                $record['status']
                                === 'Good'
                            ): ?>

                                <span
                                    class="status-badge good"
                                >
                                    Good
                                </span>

                            <?php else: ?>

                                <span
                                    class="status-badge warning"
                                >
                                    Warning
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>


            <?php else: ?>

                <tr>

                    <td colspan="5">

                        No sensor readings found
                        for this month.

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</section>


<!-- ==================================================
CONNECTED SENSORS
================================================== -->

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

                    <span
                        class="sensor-status-dot"
                    ></span>

                    <?= $sensorOnline
                        ? 'Online'
                        : 'Offline';
                    ?>

                </span>

            </div>

            <div class="sensor-info">

                Last reading:

                <?= $currentTemperature !== null
                    ? number_format(
                        (float)
                        $currentTemperature,
                        1
                    ) . '°C'
                    : 'No reading';
                ?>

            </div>

        </div>


        <div class="sensor-card">

            <div class="sensor-top">

                <span class="sensor-name">
                    Humidity Sensor
                </span>

                <span class="sensor-status">

                    <span
                        class="sensor-status-dot"
                    ></span>

                    <?= $sensorOnline
                        ? 'Online'
                        : 'Offline';
                    ?>

                </span>

            </div>

            <div class="sensor-info">

                Last reading:

                <?= $currentHumidity !== null
                    ? number_format(
                        (float)
                        $currentHumidity,
                        1
                    ) . '%'
                    : 'No reading';
                ?>

            </div>

        </div>


        <div class="sensor-card">

            <div class="sensor-top">

                <span class="sensor-name">
                    Soil Moisture Sensor
                </span>

                <span class="sensor-status">

                    <span
                        class="sensor-status-dot"
                    ></span>

                    <?= $sensorOnline
                        ? 'Online'
                        : 'Offline';
                    ?>

                </span>

            </div>

            <div class="sensor-info">

                Last reading:

                <?= $currentSoilMoisture !== null
                    ? number_format(
                        (float)
                        $currentSoilMoisture,
                        1
                    ) . '%'
                    : 'No reading';
                ?>

            </div>

        </div>


    </div>

</section>


<!-- ==================================================
ALERTS
================================================== -->

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


        <?php if (!empty($alerts)): ?>


            <?php foreach (
                $alerts as $alert
            ): ?>

                <div class="alert-item">

                    <div class="alert-icon">
                        ⚠️
                    </div>

                    <div class="alert-content">

                        <strong>
                            Sensor reading needs attention
                        </strong>

                        <span>

                            <?= htmlspecialchars(
                                $alert['date']
                            ); ?>

                            —

                            <?= htmlspecialchars(
                                $alert['message']
                            ); ?>

                        </span>

                    </div>

                </div>

            <?php endforeach; ?>


        <?php else: ?>


            <div class="alert-item">

                <div class="alert-icon">
                    ✅
                </div>

                <div class="alert-content">

                    <strong>
                        No recent alerts
                    </strong>

                    <span>
                        All recorded sensor readings
                        are within the normal range.
                    </span>

                </div>

            </div>


        <?php endif; ?>


    </div>

</section>


</main>

<?php
require "../includes/ai-assistant.php";
?>
<!-- ==================================================
JAVASCRIPT
================================================== -->

<script>

const historyLabels =
    <?= json_encode($chartLabels); ?>;

const historyTemperature =
    <?= json_encode($chartTemperature); ?>;

const historyHumidity =
    <?= json_encode($chartHumidity); ?>;

const historySoilMoisture =
    <?= json_encode($chartSoilMoisture); ?>;


function changeHistoryMonth(month) {

    window.location.href =
        "status.php?month=" +
        encodeURIComponent(month);
}


// =====================================================
// ENVIRONMENTAL OVERVIEW CHART
// =====================================================

const realtimeCanvas =
    document.getElementById(
        "realtimeChart"
    );

window.realtimeChart = null;

if (realtimeCanvas) {

    window.realtimeChart = new Chart(
        realtimeCanvas,
        {
            type: "bar",

            data: {
                labels: [],

                datasets: [
                    {
                        label: "Temperature °C",
                        data: []
                    },

                    {
                        label: "Humidity %",
                        data: []
                    },

                    {
                        label: "Soil Moisture %",
                        data: []
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: true
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 100
                    }
                }
            }
        }
    );
}


// =====================================================
// HISTORY CHART
// =====================================================

const historyCanvas =
    document.getElementById(
        "historyChart"
    );

if (historyCanvas) {

    new Chart(
        historyCanvas,
        {

            type: "bar",

            data: {

                labels:
                    historyLabels,

                datasets: [

                    {
                        label:
                            "Temperature °C",

                        data:
                            historyTemperature
                    },

                    {
                        label:
                            "Humidity %",

                        data:
                            historyHumidity
                    },

                    {
                        label:
                            "Soil Moisture %",

                        data:
                            historySoilMoisture
                    }

                ]
            },


            options: {

                responsive: true,

                maintainAspectRatio:
                    false,

                plugins: {

                    legend: {
                        display: true
                    }

                }

            }

        }
    );
}

</script>


<script src="../js/status.js"></script>
<script src="../js/profile-menu.js"></script>
<script src="../js/ai-assistant.js"></script>
</body>

</html>