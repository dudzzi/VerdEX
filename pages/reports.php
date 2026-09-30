<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reports | VerdEX</title>

    <link rel="stylesheet" href="../css/reports.css">
    <link
    rel="stylesheet"
    href="../css/profile-menu.css"
    >

</head>

<body>

    <!-- ================================
         SIDEBAR
    ================================= -->

    <aside class="reports-sidebar">

        <a href="home.php" class="reports-logo">
            <img src="../images/verdexlogo.png" alt="VerdEX">
        </a>

        <nav class="reports-nav">

            <a href="home.php" title="Dashboard">🏠</a>

            <a href="inventory.php" title="Inventory">📦</a>

            <a href="calendar.php" title="Calendar">📅</a>

            <a href="sales.php" title="Sales">💰</a>

            <a href="status.php" title="Farm Status">💧</a>

            <a href="forum.php" title="Forum">💬</a>

            <a href="reports.php" class="active" title="Reports">📊</a>

        </nav>

        <div class="dashboard-nav-bottom">

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
    <!-- ================================
         MAIN CONTENT
    ================================= -->

    <main class="reports-main">

        <!-- HEADER -->

        <header class="reports-header">

            <div>

                <h1>Reports</h1>

                <p>
                    View and analyze your farm's performance.
                </p>

            </div>

            <div class="report-actions">

                <select id="reportPeriod">

                    <option value="week">
                        This Week
                    </option>

                    <option value="month" selected>
                        This Month
                    </option>

                    <option value="year">
                        This Year
                    </option>

                </select>

                <button id="downloadReport">
                    ↓ Download Report
                </button>

            </div>

        </header>


        <!-- ================================
             SUMMARY CARDS
        ================================= -->

        <section class="report-summary">

            <div class="summary-card">

                <div class="summary-icon">
                    🌱
                </div>

                <div>

                    <span>
                        Crops Produced
                    </span>

                    <strong>
                        248 kg
                    </strong>

                    <small class="positive">
                        ↑ 12.5% from last month
                    </small>

                </div>

            </div>


            <div class="summary-card">

                <div class="summary-icon">
                    💰
                </div>

                <div>

                    <span>
                        Total Sales
                    </span>

                    <strong>
                        ₱42,850
                    </strong>

                    <small class="positive">
                        ↑ 8.2% from last month
                    </small>

                </div>

            </div>


            <div class="summary-card">

                <div class="summary-icon">
                    📦
                </div>

                <div>

                    <span>
                        Inventory Used
                    </span>

                    <strong>
                        67 items
                    </strong>

                    <small>
                        5 items low in stock
                    </small>

                </div>

            </div>


            <div class="summary-card">

                <div class="summary-icon">
                    💧
                </div>

                <div>

                    <span>
                        Average Soil Moisture
                    </span>

                    <strong>
                        64%
                    </strong>

                    <small class="positive">
                        Healthy range
                    </small>

                </div>

            </div>

        </section>


        <!-- ================================
             CHARTS
        ================================= -->

        <section class="reports-grid">

            <!-- CROP PRODUCTION -->

            <div class="report-card production-card">

                <div class="card-header">

                    <div>

                        <h2>Crop Production</h2>

                        <p>
                            Production throughout the month
                        </p>

                    </div>

                    <span class="card-badge">
                        August 2026
                    </span>

                </div>

                <div class="chart-container">

                    <canvas id="productionChart"></canvas>

                </div>

            </div>


            <!-- SALES -->

            <div class="report-card sales-card">

                <div class="card-header">

                    <div>

                        <h2>Sales Overview</h2>

                        <p>
                            Revenue generated from crops
                        </p>

                    </div>

                    <span class="card-badge">
                        August 2026
                    </span>

                </div>

                <div class="chart-container">

                    <canvas id="salesChart"></canvas>

                </div>

            </div>

        </section>


        <!-- ================================
             CROP PERFORMANCE
        ================================= -->

        <section class="report-card crop-performance">

            <div class="card-header">

                <div>

                    <h2>Crop Performance</h2>

                    <p>
                        Production and sales by crop
                    </p>

                </div>

            </div>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>Crop</th>

                            <th>Quantity Produced</th>

                            <th>Units Sold</th>

                            <th>Revenue</th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td>
                                <strong>Tomatoes</strong>
                            </td>

                            <td>
                                85 kg
                            </td>

                            <td>
                                72 kg
                            </td>

                            <td>
                                ₱12,600
                            </td>

                            <td>
                                <span class="status-good">
                                    Good
                                </span>
                            </td>

                        </tr>


                        <tr>

                            <td>
                                <strong>Lettuce</strong>
                            </td>

                            <td>
                                62 kg
                            </td>

                            <td>
                                55 kg
                            </td>

                            <td>
                                ₱9,350
                            </td>

                            <td>
                                <span class="status-good">
                                    Good
                                </span>
                            </td>

                        </tr>


                        <tr>

                            <td>
                                <strong>Bell Pepper</strong>
                            </td>

                            <td>
                                54 kg
                            </td>

                            <td>
                                48 kg
                            </td>

                            <td>
                                ₱11,520
                            </td>

                            <td>
                                <span class="status-average">
                                    Average
                                </span>
                            </td>

                        </tr>


                        <tr>

                            <td>
                                <strong>Eggplant</strong>
                            </td>

                            <td>
                                47 kg
                            </td>

                            <td>
                                39 kg
                            </td>

                            <td>
                                ₱9,380
                            </td>

                            <td>
                                <span class="status-good">
                                    Good
                                </span>
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>


        <!-- ================================
             FARM INSIGHTS
        ================================= -->

        <section class="insights-grid">

            <div class="report-card">

                <div class="card-header">

                    <div>

                        <h2>Farm Insights</h2>

                        <p>
                            Important observations from your data.
                        </p>

                    </div>

                </div>


                <div class="insight-list">

                    <div class="insight-item">

                        <div class="insight-icon">
                            🌱
                        </div>

                        <div>

                            <strong>
                                Production increased
                            </strong>

                            <p>
                                Crop production increased by 12.5%
                                compared to the previous month.
                            </p>

                        </div>

                    </div>


                    <div class="insight-item">

                        <div class="insight-icon">
                            💧
                        </div>

                        <div>

                            <strong>
                                Soil moisture is stable
                            </strong>

                            <p>
                                Average soil moisture remained
                                within the healthy range.
                            </p>

                        </div>

                    </div>


                    <div class="insight-item">

                        <div class="insight-icon">
                            📦
                        </div>

                        <div>

                            <strong>
                                Inventory needs attention
                            </strong>

                            <p>
                                5 inventory items are currently
                                running low.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- REPORT INFO -->

            <div class="report-card report-info">

                <div class="card-header">

                    <div>

                        <h2>Report Summary</h2>

                        <p>
                            August 2026
                        </p>

                    </div>

                </div>


                <div class="summary-row">

                    <span>Total Production</span>

                    <strong>248 kg</strong>

                </div>

                <div class="summary-row">

                    <span>Total Sales</span>

                    <strong>₱42,850</strong>

                </div>

                <div class="summary-row">

                    <span>Average Soil Moisture</span>

                    <strong>64%</strong>

                </div>

                <div class="summary-row">

                    <span>Low Stock Items</span>

                    <strong>5</strong>

                </div>

            </div>

        </section>

    </main>


    <!-- CHART.JS -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="../js/reports.js"></script>
    <script src="../js/profile-menu.js"></script>
</body>

</html>