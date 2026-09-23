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

    <title>VerdEX - Calendar</title>

    <link rel="stylesheet"
          href="../css/calendar.css">

</head>

<body>


<!-- =====================================================
     SIDEBAR
     ===================================================== -->

<aside class="calendar-sidebar">

    <a href="home.php" class="calendar-logo">

        <img
            src="../images/verdexlogo.png"
            alt="VerdEX">

    </a>


    <nav class="calendar-nav">

        <a href="home.php" title="Dashboard">
            🏠
        </a>

        <a href="inventory.php" title="Inventory">
            📦
        </a>

        <a href="calendar.php"
           class="active"
           title="Calendar">
            📅
        </a>

        <a href="sales.php" title="Sales">
            💰
        </a>

        <a href="status.php" title="Farm Status">
            💧
        </a>

        <a href="forum.php" title="Forum">
            💬
        </a>

        <a href="reports.php" title="Weekly Reports">
            📊
        </a>

    </nav>


    <div class="calendar-nav-bottom">

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

<main class="calendar-main">


    <!-- TOPBAR -->

    <header class="calendar-topbar">

        <div class="calendar-brand">

            <div class="calendar-brand-icon">
                🌿
            </div>

            <span>
                VerdEX
            </span>

        </div>


        <div class="calendar-top-actions">

            <div class="calendar-search">
                🔍
                <span>Search</span>
            </div>

            <div class="calendar-time">
                🕐
                <?php echo date('h:i A'); ?>
            </div>

            <div class="calendar-notification">
                🔔
                <span></span>
            </div>

        </div>

    </header>



    <!-- PAGE HEADER -->

    <section class="calendar-page-header">

        <div>

            <span class="calendar-eyebrow">
                FARM MANAGEMENT
            </span>

            <h1>
                Calendar
            </h1>

            <p>
                Schedule and keep track of your farm activities.
            </p>

        </div>


        <button
            class="add-event-button"
            onclick="openEventModal()">

            <span>+</span>

            Add Task

        </button>

    </section>



    <!-- =================================================
         CALENDAR LAYOUT
         ================================================= -->

    <section class="calendar-layout">


        <!-- MAIN CALENDAR -->

        <div class="calendar-card">


            <!-- CALENDAR HEADER -->

            <div class="calendar-card-header">

                <button
                    class="month-arrow"
                    onclick="previousMonth()">

                    ‹

                </button>


                <div class="month-title">

                    <h2 id="monthTitle">
                        September 2026
                    </h2>

                </div>


                <button
                    class="month-arrow"
                    onclick="nextMonth()">

                    ›

                </button>


                <button
                    class="today-button"
                    onclick="goToToday()">

                    Today

                </button>

            </div>



            <!-- WEEKDAYS -->

            <div class="calendar-weekdays">

                <div>Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>

            </div>



            <!-- DAYS -->

            <div
                id="calendarDays"
                class="calendar-days">

            </div>


        </div>



        <!-- =================================================
             UPCOMING TASKS
             ================================================= -->

        <aside class="upcoming-card">


            <div class="upcoming-header">

                <div>

                    <span class="calendar-eyebrow">
                        SCHEDULE
                    </span>

                    <h2>
                        Upcoming Tasks
                    </h2>

                </div>


                <button
                    class="more-button"
                    onclick="openEventModal()">

                    +

                </button>

            </div>



            <div
                id="upcomingTasks"
                class="upcoming-tasks">

            </div>


        </aside>


    </section>



</main>



<!-- =====================================================
     ADD TASK MODAL
     ===================================================== -->

<div
    id="eventModal"
    class="calendar-modal">


    <div class="calendar-modal-content">


        <div class="calendar-modal-header">

            <div>

                <span class="calendar-eyebrow">
                    FARM SCHEDULE
                </span>

                <h2>
                    Add Task
                </h2>

            </div>


            <button
                onclick="closeEventModal()">

                ×

            </button>

        </div>



        <form id="eventForm">


            <label>
                Task Name
            </label>

            <input
                type="text"
                id="eventTitle"
                placeholder="e.g. Soil Aeration"
                required>



            <label>
                Date
            </label>

            <input
                type="date"
                id="eventDate"
                required>



            <div class="form-row">

                <div>

                    <label>
                        Start Time
                    </label>

                    <input
                        type="time"
                        id="eventStart"
                        required>

                </div>


                <div>

                    <label>
                        End Time
                    </label>

                    <input
                        type="time"
                        id="eventEnd">

                </div>

            </div>



            <label>
                Task Type
            </label>

            <select id="eventType">

                <option value="plant">
                    Plant Care
                </option>

                <option value="irrigation">
                    Irrigation
                </option>

                <option value="fertilizer">
                    Fertilizer
                </option>

                <option value="inspection">
                    Inspection
                </option>

                <option value="maintenance">
                    Maintenance
                </option>

                <option value="other">
                    Other
                </option>

            </select>



            <label>
                Description
            </label>

            <textarea
                id="eventDescription"
                placeholder="Add task details..."></textarea>



            <div class="modal-actions">

                <button
                    type="button"
                    class="cancel-button"
                    onclick="closeEventModal()">

                    Cancel

                </button>


                <button
                    type="submit"
                    class="save-button">

                    Add Task

                </button>

            </div>


        </form>

    </div>

</div>



<!-- =====================================================
     TASK DETAILS MODAL
     ===================================================== -->

<div
    id="taskDetailsModal"
    class="calendar-modal">


    <div class="calendar-modal-content">


        <div class="calendar-modal-header">

            <div>

                <span class="calendar-eyebrow">
                    TASK DETAILS
                </span>

                <h2 id="taskDetailsTitle">
                    Task
                </h2>

            </div>


            <button onclick="closeTaskDetails()">
                ×
            </button>

        </div>



        <div id="taskDetailsContent">

        </div>


    </div>

</div>



<script src="../js/calendar.js"></script>

</body>

</html>