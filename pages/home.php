<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$fullName = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'User';
$role = $_SESSION['role'] ?? 'Team Member';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>VerdEX - Dashboard</title>

    <link rel="stylesheet"
          href="../css/home.css">

</head>

<body class="dashboard-page">


    <!-- =====================================================
         SIDEBAR
         ===================================================== -->

    <aside class="dashboard-sidebar">

        <a href="home.php"
           class="dashboard-logo">

            <img src="../images/verdexlogo.png"
                 alt="VerdEX">

        </a>


        <nav class="dashboard-nav">

            <a href="home.php"
               class="active"
               title="Dashboard">
                🏠
            </a>

            <a href="inventory.php"
               title="Inventory">
                📦
            </a>

            <a href="calendar.php"
               title="Calendar">
                📅
            </a>

            <a href="sales.php"
               title="Sales">
                💰
            </a>

            <a href="status.php"
               title="Farm Status">
                💧
            </a>

            <a href="forum.php"
               title="Trends">
                💬
            </a>

            <a href="reports.php"
               title="Reports">
                📊
            </a>

        </nav>


        <div class="dashboard-nav-bottom">

            <a href="profile.php"
               title="Profile">
                👤
            </a>

            <a href="settings.php"
               title="Settings">
                ⚙️
            </a>

            <a href="../backend/logout.php"
               title="Logout">
                ↪
            </a>

        </div>

    </aside>



    <!-- =====================================================
         MAIN CONTENT
         ===================================================== -->

    <main class="dashboard-main">


        <!-- TOPBAR -->

        <header class="dashboard-topbar">

            <div class="dashboard-brand">

                <div class="dashboard-brand-icon">
                    🌿
                </div>

                <span>
                    VerdEX
                </span>

            </div>


            <div class="dashboard-top-actions">



                <div class="dashboard-time">
                    🕐
                    <?php echo date('h:i A'); ?>
                </div>

                <div class="dashboard-notification">

                    🔔

                    <span class="notification-dot"></span>

                </div>

            </div>

        </header>



        <!-- PAGE HEADING -->

        <section class="dashboard-heading">

            <h1>
                Overview
            </h1>

            <p>
                Monitor your hydroponic farm and manage your daily activities.
            </p>

        </section>



        <!-- =================================================
             STAT CARDS
             ================================================= -->

        <section class="dashboard-stats">


            <div class="stat-card">

                <div class="stat-card-header">

                    <span class="stat-card-label">
                        Active Crops
                    </span>

                    <div class="stat-icon">
                        🌱
                    </div>

                </div>

                <div class="stat-card-value">
                    6
                </div>

                <div class="stat-card-description">
                    Crops currently being monitored
                </div>

            </div>



            <div class="stat-card">

                <div class="stat-card-header">

                    <span class="stat-card-label">
                        Inventory Items
                    </span>

                    <div class="stat-icon">
                        📦
                    </div>

                </div>

                <div class="stat-card-value">
                    12
                </div>

                <div class="stat-card-description">
                    Plants, fertilizers and tools
                </div>

            </div>



            <div class="stat-card">

                <div class="stat-card-header">

                    <span class="stat-card-label">
                        Pending Tasks
                    </span>

                    <div class="stat-icon">
                        ✓
                    </div>

                </div>

                <div class="stat-card-value">
                    3
                </div>

                <div class="stat-card-description">
                    Activities that need attention
                </div>

            </div>


        </section>



        <!-- =================================================
             FARM CONDITION + CROP PROGRESS
             ================================================= -->

        <section class="dashboard-grid">


            <!-- FARM CONDITION -->

            <div class="dashboard-card">

                <div class="card-header">

                    <div>

                        <div class="card-title">
                            Farm Condition
                        </div>

                        <div class="card-subtitle">
                            Current greenhouse readings
                        </div>

                    </div>

                    <button class="card-menu">
                        •••
                    </button>

                </div>


                <div class="condition-grid">


                    <div class="condition-item">

                        <div class="condition-label">
                            Temperature
                        </div>

                        <div class="condition-value">
                            28°C
                        </div>

                        <div class="condition-status">
                            ● Normal
                        </div>

                    </div>


                    <div class="condition-item">

                        <div class="condition-label">
                            Humidity
                        </div>

                        <div class="condition-value">
                            67%
                        </div>

                        <div class="condition-status">
                            ● Normal
                        </div>

                    </div>


                    <div class="condition-item">

                        <div class="condition-label">
                            Water Level
                        </div>

                        <div class="condition-value">
                            81%
                        </div>

                        <div class="condition-status">
                            ● Good
                        </div>

                    </div>


                </div>

            </div>



            <!-- CROP PROGRESS -->

            <div class="dashboard-card crop-progress-card">

                <div class="card-header">

                    <div>

                        <div class="card-title">
                            Crop Progress
                        </div>

                        <div class="card-subtitle">
                            Overall harvest completion
                        </div>

                    </div>

                    <button class="card-menu">
                        •••
                    </button>

                </div>


                <div class="progress-circle">

                    <div class="progress-circle-content">

                        <span class="progress-value">
                            81%
                        </span>

                        <span class="progress-label">
                            Harvest Completion
                        </span>

                    </div>

                </div>


                <div class="progress-legend">

                    <span>
                        <span class="legend-dot"></span>
                        Completed
                    </span>

                    <span>
                        <span class="legend-dot"></span>
                        In Progress
                    </span>

                </div>

            </div>


        </section>



        <!-- =================================================
             ACTIVITIES + SMART TASK
             ================================================= -->

        <section class="dashboard-lower-grid">


            <!-- RECENT ACTIVITIES -->

            <div class="dashboard-card">

                <div class="card-header">

                    <div>

                        <div class="card-title">
                            Recent Farm Activities
                        </div>

                        <div class="card-subtitle">
                            Latest updates from your farm
                        </div>

                    </div>

                    <button class="card-menu">
                        •••
                    </button>

                </div>


                <div class="activity-list">


                    <div class="activity-item">

                        <div class="activity-icon">
                            💧
                        </div>

                        <div class="activity-info">

                            <div class="activity-title">
                                Water level checked
                            </div>

                            <div class="activity-time">
                                Today • 09:00 AM
                            </div>

                        </div>

                    </div>


                    <div class="activity-item">

                        <div class="activity-icon">
                            🌱
                        </div>

                        <div class="activity-info">

                            <div class="activity-title">
                                Crop inventory updated
                            </div>

                            <div class="activity-time">
                                Today • 08:30 AM
                            </div>

                        </div>

                    </div>


                    <div class="activity-item">

                        <div class="activity-icon">
                            📅
                        </div>

                        <div class="activity-info">

                            <div class="activity-title">
                                Farm activity scheduled
                            </div>

                            <div class="activity-time">
                                Yesterday • 04:15 PM
                            </div>

                        </div>

                    </div>


                    <div class="activity-item">

                        <div class="activity-icon">
                            📊
                        </div>

                        <div class="activity-info">

                            <div class="activity-title">
                                Weekly report generated
                            </div>

                            <div class="activity-time">
                                Yesterday • 02:00 PM
                            </div>

                        </div>

                    </div>


                </div>

            </div>



            <!-- SMART TASK -->

            <div class="dashboard-card smart-task">

                <span class="smart-task-label">
                    SMART FARM UPDATE
                </span>

                <h3>
                    Keep your farm activities organized.
                </h3>

                <p>
                    Check your tasks, monitor farm conditions,
                    and keep your crop records updated.
                </p>

                <a href="calendar.php"
                   class="smart-task-button">

                    View Tasks

                </a>

            </div>


        </section>



    </main>


</body>

</html>