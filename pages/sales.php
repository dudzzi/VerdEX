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

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>VerdEX - Sales</title>

    <link rel="stylesheet"
          href="../css/sales.css">

</head>

<body>


<!-- =====================================================
     SIDEBAR
     ===================================================== -->

<aside class="sales-sidebar">

    <a href="home.php" class="sales-logo">

        <img
            src="../images/verdexlogo.png"
            alt="VerdEX">

    </a>


    <nav class="sales-nav">

        <a href="home.php" title="Dashboard">
            🏠
        </a>

        <a href="inventory.php" title="Inventory">
            📦
        </a>

        <a href="calendar.php" title="Calendar">
            📅
        </a>

        <a href="sales.php"
           class="active"
           title="Sales">
            💰
        </a>

        <a href="status.php" title="Farm Status">
            💧
        </a>

        <a href="forum.php" title="Forum">
            💬
        </a>

        <a href="reports.php" title="Reports">
            📊
        </a>

    </nav>


    <div class="sales-nav-bottom">

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



<!-- =====================================================
     MAIN
     ===================================================== -->

<main class="sales-main">


    <!-- TOPBAR -->

    <header class="sales-topbar">

        <div class="sales-brand">

            <div class="sales-brand-icon">
                🌿
            </div>

            <span>
                VerdEX
            </span>

        </div>


        <div class="sales-top-actions">

            <div class="sales-time">
                🕐
                <?php echo date('h:i A'); ?>
            </div>

            <div class="sales-notification">
                🔔
                <span></span>
            </div>

        </div>

    </header>



    <!-- PAGE HEADER -->

    <section class="sales-page-header">

        <div>

            <span class="sales-eyebrow">
                FARM MANAGEMENT
            </span>

            <h1>
                Sales
            </h1>

            <p>
                Track your crop sales and monitor your farm revenue.
            </p>

        </div>


        <button
            class="add-sale-button"
            onclick="openSaleModal()">

            <span>+</span>

            Add Sale

        </button>

    </section>



    <!-- =====================================================
         SUMMARY CARDS
         ===================================================== -->

    <section class="sales-summary">


        <div class="sales-stat-card">

            <div class="sales-stat-top">

                <span>
                    Total Sales
                </span>

                <div class="sales-stat-icon">
                    ₱
                </div>

            </div>

            <h2 id="totalSales">
                ₱84,300
            </h2>

            <p class="positive">
                ↑ 12.5% from last month
            </p>

        </div>



        <div class="sales-stat-card">

            <div class="sales-stat-top">

                <span>
                    This Month
                </span>

                <div class="sales-stat-icon">
                    📈
                </div>

            </div>

            <h2 id="monthlySales">
                ₱24,650
            </h2>

            <p class="positive">
                ↑ 8.2% from last month
            </p>

        </div>



        <div class="sales-stat-card">

            <div class="sales-stat-top">

                <span>
                    Items Sold
                </span>

                <div class="sales-stat-icon">
                    🌱
                </div>

            </div>

            <h2 id="itemsSold">
                326
            </h2>

            <p>
                Crop items sold
            </p>

        </div>



        <div class="sales-stat-card">

            <div class="sales-stat-top">

                <span>
                    Pending Sales
                </span>

                <div class="sales-stat-icon pending">
                    ⏱
                </div>

            </div>

            <h2 id="pendingSales">
                5
            </h2>

            <p>
                Awaiting confirmation
            </p>

        </div>


    </section>



    <!-- =====================================================
         SALES CONTENT
         ===================================================== -->

    <section class="sales-content">


        <!-- SALES OVERVIEW -->

        <div class="sales-overview-card">

            <div class="sales-card-header">

                <div>

                    <span class="sales-eyebrow">
                        PERFORMANCE
                    </span>

                    <h2>
                        Sales Overview
                    </h2>

                </div>


                <select
                    id="salesPeriod"
                    onchange="changeSalesPeriod()">

                    <option value="7">
                        Last 7 Days
                    </option>

                    <option value="30">
                        Last 30 Days
                    </option>

                    <option value="year">
                        This Year
                    </option>

                </select>

            </div>


            <div class="chart-area">

                <div class="chart-y-axis">

                    <span>₱20k</span>
                    <span>₱15k</span>
                    <span>₱10k</span>
                    <span>₱5k</span>
                    <span>₱0</span>

                </div>


                <div class="chart">

                    <div class="chart-grid"></div>

                    <div class="chart-bars">

                        <div class="chart-column">
                            <div
                                class="chart-bar"
                                style="height: 45%;">
                            </div>
                            <span>Mon</span>
                        </div>

                        <div class="chart-column">
                            <div
                                class="chart-bar"
                                style="height: 65%;">
                            </div>
                            <span>Tue</span>
                        </div>

                        <div class="chart-column">
                            <div
                                class="chart-bar"
                                style="height: 52%;">
                            </div>
                            <span>Wed</span>
                        </div>

                        <div class="chart-column">
                            <div
                                class="chart-bar"
                                style="height: 78%;">
                            </div>
                            <span>Thu</span>
                        </div>

                        <div class="chart-column">
                            <div
                                class="chart-bar"
                                style="height: 62%;">
                            </div>
                            <span>Fri</span>
                        </div>

                        <div class="chart-column">
                            <div
                                class="chart-bar"
                                style="height: 88%;">
                            </div>
                            <span>Sat</span>
                        </div>

                        <div class="chart-column">
                            <div
                                class="chart-bar"
                                style="height: 72%;">
                            </div>
                            <span>Sun</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- TOP CROPS -->

        <div class="top-crops-card">

            <div class="sales-card-header">

                <div>

                    <span class="sales-eyebrow">
                        PRODUCTS
                    </span>

                    <h2>
                        Top Crops
                    </h2>

                </div>

            </div>


            <div class="crop-ranking">

                <div class="crop-item">

                    <div class="crop-number">
                        01
                    </div>

                    <div class="crop-info">

                        <strong>
                            Lettuce
                        </strong>

                        <span>
                            128 items sold
                        </span>

                    </div>

                    <strong>
                        ₱18,500
                    </strong>

                </div>


                <div class="crop-item">

                    <div class="crop-number">
                        02
                    </div>

                    <div class="crop-info">

                        <strong>
                            Tomato
                        </strong>

                        <span>
                            96 items sold
                        </span>

                    </div>

                    <strong>
                        ₱14,200
                    </strong>

                </div>


                <div class="crop-item">

                    <div class="crop-number">
                        03
                    </div>

                    <div class="crop-info">

                        <strong>
                            Bell Pepper
                        </strong>

                        <span>
                            74 items sold
                        </span>

                    </div>

                    <strong>
                        ₱10,800
                    </strong>

                </div>


                <div class="crop-item">

                    <div class="crop-number">
                        04
                    </div>

                    <div class="crop-info">

                        <strong>
                            Cucumber
                        </strong>

                        <span>
                            51 items sold
                        </span>

                    </div>

                    <strong>
                        ₱7,400
                    </strong>

                </div>

            </div>

        </div>


    </section>



    <!-- =====================================================
         RECENT SALES
         ===================================================== -->

    <section class="recent-sales-card">


        <div class="sales-card-header">

            <div>

                <span class="sales-eyebrow">
                    TRANSACTIONS
                </span>

                <h2>
                    Recent Sales
                </h2>

            </div>


            <button
                class="view-all-button"
                onclick="showAllSales()">

                View All

            </button>

        </div>



        <div class="sales-table-wrapper">

            <table class="sales-table">

                <thead>

                    <tr>

                        <th>
                            Customer
                        </th>

                        <th>
                            Product
                        </th>

                        <th>
                            Quantity
                        </th>

                        <th>
                            Amount
                        </th>

                        <th>
                            Date
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                        </th>

                    </tr>

                </thead>


                <tbody id="salesTableBody">

                </tbody>

            </table>

        </div>


    </section>


</main>



<!-- =====================================================
     ADD SALE MODAL
     ===================================================== -->

<div
    id="saleModal"
    class="sales-modal">


    <div class="sales-modal-content">


        <div class="sales-modal-header">

            <div>

                <span class="sales-eyebrow">
                    FARM SALES
                </span>

                <h2>
                    Add Sale
                </h2>

            </div>


            <button
                onclick="closeSaleModal()">

                ×

            </button>

        </div>



        <form id="saleForm">


            <label>
                Customer Name
            </label>

            <input
                type="text"
                id="customerName"
                placeholder="e.g. Juan Dela Cruz"
                required>



            <label>
                Product
            </label>

            <select
                id="saleProduct"
                required>

                <option value="">
                    Select crop
                </option>

                <option value="Lettuce">
                    Lettuce
                </option>

                <option value="Tomato">
                    Tomato
                </option>

                <option value="Bell Pepper">
                    Bell Pepper
                </option>

                <option value="Cucumber">
                    Cucumber
                </option>

                <option value="Other">
                    Other
                </option>

            </select>



            <div class="sales-form-row">

                <div>

                    <label>
                        Quantity
                    </label>

                    <input
                        type="number"
                        id="saleQuantity"
                        min="1"
                        placeholder="0"
                        required>

                </div>


                <div>

                    <label>
                        Price
                    </label>

                    <input
                        type="number"
                        id="salePrice"
                        min="0"
                        step="0.01"
                        placeholder="₱0.00"
                        required>

                </div>

            </div>



            <label>
                Sale Date
            </label>

            <input
                type="date"
                id="saleDate"
                required>



            <label>
                Status
            </label>

            <select id="saleStatus">

                <option value="Completed">
                    Completed
                </option>

                <option value="Pending">
                    Pending
                </option>

            </select>



            <div class="sales-modal-actions">

                <button
                    type="button"
                    class="sales-cancel-button"
                    onclick="closeSaleModal()">

                    Cancel

                </button>


                <button
                    type="submit"
                    class="sales-save-button">

                    Add Sale

                </button>

            </div>


        </form>

    </div>

</div>



<!-- =====================================================
     SALE DETAILS MODAL
     ===================================================== -->

<div
    id="saleDetailsModal"
    class="sales-modal">


    <div class="sales-modal-content">


        <div class="sales-modal-header">

            <div>

                <span class="sales-eyebrow">
                    SALE DETAILS
                </span>

                <h2 id="saleDetailsCustomer">
                    Customer
                </h2>

            </div>


            <button
                onclick="closeSaleDetails()">

                ×

            </button>

        </div>


        <div id="saleDetailsContent">

        </div>


    </div>

</div>



<script src="../js/sales.js"></script>

</body>

</html>