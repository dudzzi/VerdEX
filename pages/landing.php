<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>VerdEX - Smart Hydroponic Farm Management</title>

    <link rel="stylesheet" href="../css/landing.css">
</head>

<body>

<!-- =========================================
     NAVIGATION
========================================= -->

<header class="landing-header">

    <nav class="landing-nav">

        <a href="#home" class="brand">

            <img src="../images/verdexlogo.png" alt="VerdEX Logo">

            <span>
                Verd<span>EX</span>
            </span>

        </a>


        <div class="nav-links">

            <a href="#home" class="active">
                Overview
            </a>

            <a href="#about">
                About
            </a>

            <a href="#features">
                Features
            </a>

            <a href="#team">
                Team
            </a>

        </div>


        <a href="login.php" class="nav-login">
            Log In
        </a>

    </nav>

</header>


<main>


<!-- =========================================
     HERO
========================================= -->

<section class="hero" id="home">

    <div class="hero-content">


        <!-- LEFT -->

        <div class="hero-text">

            <div class="hero-badge">

                <span></span>

                Smart Hydroponic Farm Management

            </div>


            <h1>
                Grow smarter.
                <br>

                Manage your farm with
                <span>confidence.</span>
            </h1>


            <p>
                VerdEX brings your hydroponic farm into one
                connected system. Monitor farm conditions,
                organize daily operations, and make better
                decisions using real farm data.
            </p>


            <div class="hero-actions">

                <a href="login.php" class="primary-button">

                    Get Started

                    <span>→</span>

                </a>


                <a href="#features" class="secondary-button">
                    Explore Features
                </a>

            </div>


            <div class="hero-points">

                <div>
                    <span class="check">✓</span>
                    Real-time monitoring
                </div>

                <div>
                    <span class="check">✓</span>
                    Farm management
                </div>

                <div>
                    <span class="check">✓</span>
                    Smart recommendations
                </div>

            </div>

        </div>


        <!-- RIGHT DASHBOARD PREVIEW -->

        <div class="hero-dashboard">

            <div class="dashboard-window">

                <div class="dashboard-top">

                    <div>

                        <span class="dashboard-label">
                            VERDEX FARM
                        </span>

                        <h3>
                            Good morning 👋
                        </h3>

                    </div>


                    <div class="dashboard-status">

                        <span></span>

                        Farm Online

                    </div>

                </div>


                <!-- FARM HEALTH -->

                <div class="health-card">

                    <div>

                        <span>
                            Overall Farm Health
                        </span>

                        <h2>
                            Healthy
                        </h2>

                        <p>
                            All systems are operating normally.
                        </p>

                    </div>


                    <div class="health-icon">
                        🌱
                    </div>

                </div>


                <!-- SENSOR CARDS -->

                <div class="dashboard-sensors">

                    <div class="sensor-preview">

                        <div class="sensor-icon">
                            🌡️
                        </div>

                        <span>
                            Temperature
                        </span>

                        <strong>
                            28.5°C
                        </strong>

                        <small>
                            Normal
                        </small>

                    </div>


                    <div class="sensor-preview">

                        <div class="sensor-icon">
                            💧
                        </div>

                        <span>
                            Humidity
                        </span>

                        <strong>
                            72%
                        </strong>

                        <small>
                            Good
                        </small>

                    </div>


                    <div class="sensor-preview">

                        <div class="sensor-icon">
                            🌱
                        </div>

                        <span>
                            Soil Moisture
                        </span>

                        <strong>
                            64%
                        </strong>

                        <small>
                            Good
                        </small>

                    </div>

                </div>


                <!-- MINI CHART -->

                <div class="dashboard-chart">

                    <div class="mini-chart-header">

                        <div>

                            <strong>
                                Farm Activity
                            </strong>

                            <span>
                                Today
                            </span>

                        </div>


                        <span class="chart-percent">
                            +12.5%
                        </span>

                    </div>


                    <div class="fake-chart">

                        <div class="chart-line line-1"></div>

                        <div class="chart-line line-2"></div>

                        <div class="chart-line line-3"></div>


                        <div class="chart-bars">

                            <span style="height: 35%;"></span>

                            <span style="height: 48%;"></span>

                            <span style="height: 42%;"></span>

                            <span style="height: 65%;"></span>

                            <span style="height: 57%;"></span>

                            <span style="height: 78%;"></span>

                            <span style="height: 68%;"></span>

                            <span style="height: 88%;"></span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FLOATING CARDS -->

            <div class="floating-card floating-left">

                <div class="floating-icon">
                    💧
                </div>

                <div>

                    <span>
                        Water System
                    </span>

                    <strong>
                        Normal
                    </strong>

                </div>

            </div>


            <div class="floating-card floating-right">

                <div class="floating-icon">
                    ✓
                </div>

                <div>

                    <span>
                        Farm Status
                    </span>

                    <strong>
                        All Good
                    </strong>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================
     PROBLEM / SOLUTION
========================================= -->

<section class="problem-section">

    <div class="section-container">

        <div class="problem-grid">


            <article class="problem-card">

                <div class="problem-number">
                    01
                </div>

                <span class="section-label">
                    THE PROBLEM
                </span>

                <h2>
                    Farming shouldn't depend on guesswork.
                </h2>

                <p>
                    Hydroponic farms require constant monitoring
                    of environmental conditions. Manual tracking
                    takes time and makes important changes easier
                    to miss.
                </p>

            </article>


            <article class="solution-card">

                <div class="problem-number">
                    02
                </div>

                <span class="section-label">
                    THE SOLUTION
                </span>

                <h2>
                    One system to keep your farm connected.
                </h2>

                <p>
                    VerdEX combines farm monitoring, inventory,
                    scheduling, sales, reports, and smart
                    recommendations into one simple platform.
                </p>

            </article>

        </div>

    </div>

</section>


<!-- =========================================
     ABOUT
========================================= -->

<section class="about-section" id="about">

    <div class="section-container">

        <div class="section-heading">

            <span class="section-label">
                ABOUT VERDEX
            </span>

            <h2>
                Smart farming made simpler.
            </h2>

            <p>
                VerdEX helps hydroponic growers manage everyday
                farm activities while keeping important farm
                information easy to understand.
            </p>

        </div>


        <div class="about-grid">


            <article class="about-card">

                <div class="about-icon">
                    ◉
                </div>

                <h3>
                    Monitor
                </h3>

                <p>
                    Keep track of important farm and
                    environmental conditions.
                </p>

            </article>


            <article class="about-card">

                <div class="about-icon">
                    □
                </div>

                <h3>
                    Manage
                </h3>

                <p>
                    Organize crops, fertilizers, tools,
                    inventory, and farm resources.
                </p>

            </article>


            <article class="about-card">

                <div class="about-icon">
                    ◷
                </div>

                <h3>
                    Plan
                </h3>

                <p>
                    Schedule planting, maintenance,
                    harvesting, and other activities.
                </p>

            </article>


            <article class="about-card">

                <div class="about-icon">
                    ↗
                </div>

                <h3>
                    Analyze
                </h3>

                <p>
                    Review sales, reports, and historical
                    farm information.
                </p>

            </article>

        </div>

    </div>

</section>


<!-- =========================================
     FEATURES
========================================= -->

<section class="features-section" id="features">

    <div class="section-container">

        <div class="section-heading">

            <span class="section-label">
                FEATURES
            </span>

            <h2>
                Everything your farm needs.
            </h2>

            <p>
                Essential tools for managing your hydroponic
                farm in one connected workspace.
            </p>

        </div>


        <div class="features-grid">


            <article class="feature-card">

                <div class="feature-icon">
                    📦
                </div>

                <h3>
                    Inventory Management
                </h3>

                <p>
                    Manage crops, fertilizers, and tools
                    from one organized inventory.
                </p>

            </article>


            <article class="feature-card">

                <div class="feature-icon">
                    💧
                </div>

                <h3>
                    Farm Monitoring
                </h3>

                <p>
                    View important farm readings and monitor
                    environmental conditions.
                </p>

            </article>


            <article class="feature-card">

                <div class="feature-icon">
                    📅
                </div>

                <h3>
                    Farm Calendar
                </h3>

                <p>
                    Schedule planting, maintenance,
                    nutrient replacement, and harvesting.
                </p>

            </article>


            <article class="feature-card">

                <div class="feature-icon">
                    ₱
                </div>

                <h3>
                    Sales Tracking
                </h3>

                <p>
                    Record farm sales and keep track of
                    revenue from your crops.
                </p>

            </article>


            <article class="feature-card">

                <div class="feature-icon">
                    📊
                </div>

                <h3>
                    Farm Reports
                </h3>

                <p>
                    Review production, sales, inventory,
                    and farm performance.
                </p>

            </article>


            <article class="feature-card">

                <div class="feature-icon">
                    ✦
                </div>

                <h3>
                    AI Recommendations
                </h3>

                <p>
                    Receive useful recommendations for
                    farm management and plant care.
                </p>

            </article>

        </div>

    </div>

</section>


<!-- =========================================
     TEAM
========================================= -->

<section class="team-section" id="team">

    <div class="section-container">

        <div class="section-heading">

            <span class="section-label">
                OUR TEAM
            </span>

            <h2>
                Meet the team behind VerdEX.
            </h2>

            <p>
                The people working together to design,
                develop, and improve the VerdEX system.
            </p>

        </div>


        <div class="team-grid">


            <!-- ZANDRO -->

            <article class="team-card">

                <div class="team-video">

                    <video autoplay muted loop playsinline>

                        <source
                            src="../videos/member1-bg.mp4"
                            type="video/mp4"
                        >

                    </video>

                    <div class="team-video-overlay"></div>

                </div>


                <div class="team-content">

                    <div class="team-avatar">

                        <img
                            src="../images/team/zandro.jpg"
                            alt="Zandro Sean D. Animos"
                        >

                    </div>

                    <h3>
                        Zandro Sean D. Animos
                    </h3>

                    <span>
                        Project Manager / Data Analyst
                    </span>

                    <p>
                        Leads project coordination and
                        handles data analysis.
                    </p>

                </div>

            </article>


            <!-- SAM -->

            <article class="team-card">

                <div class="team-video">

                    <video autoplay muted loop playsinline>

                        <source
                            src="../videos/member2-bg.mp4"
                            type="video/mp4"
                        >

                    </video>

                    <div class="team-video-overlay"></div>

                </div>


                <div class="team-content">

                    <div class="team-avatar">

                        <img
                            src="../images/team/sam.jpg"
                            alt="Sam Aisele A. Austria"
                        >

                    </div>

                    <h3>
                        Sam Aisele A. Austria
                    </h3>

                    <span>
                        Front-End Developer / Lead Documenter
                    </span>

                    <p>
                        Develops the interface and leads
                        project documentation.
                    </p>

                </div>

            </article>


            <!-- JAYPEE -->

            <article class="team-card">

                <div class="team-video">

                    <video autoplay muted loop playsinline>

                        <source
                            src="../videos/member3-bg.mp4"
                            type="video/mp4"
                        >

                    </video>

                    <div class="team-video-overlay"></div>

                </div>


                <div class="team-content">

                    <div class="team-avatar">

                        <img
                            src="../images/team/jaypee.jpg"
                            alt="Jaypee D. Cervantes"
                        >

                    </div>

                    <h3>
                        Jaypee D. Cervantes
                    </h3>

                    <span>
                        UI/UX Designer / Database Manager
                    </span>

                    <p>
                        Designs the user experience and
                        manages the project database.
                    </p>

                </div>

            </article>


            <!-- LORENZO -->

            <article class="team-card">

                <div class="team-video">

                    <video autoplay muted loop playsinline>

                        <source
                            src="../videos/member4-bg.mp4"
                            type="video/mp4"
                        >

                    </video>

                    <div class="team-video-overlay"></div>

                </div>


                <div class="team-content">

                    <div class="team-avatar">

                        <img
                            src="../images/team/lorenzo.png"
                            alt="Lorenzo M. Chavez"
                        >

                    </div>

                    <h3>
                        Lorenzo M. Chavez
                    </h3>

                    <span>
                        Backend Developer / IoT Developer
                    </span>

                    <p>
                        Develops backend functionality and
                        works on sensor integration.
                    </p>

                </div>

            </article>

        </div>

    </div>

</section>


<!-- =========================================
     CTA
========================================= -->

<section class="cta-section">

    <div class="section-container">

        <div class="cta-card">

            <div>

                <span class="section-label">
                    START WITH VERDEX
                </span>

                <h2>
                    Ready to manage your farm smarter?
                </h2>

                <p>
                    Access your VerdEX dashboard and keep
                    your farm information in one place.
                </p>

            </div>


            <a href="login.php" class="cta-button">

                Log In to VerdEX

                <span>→</span>

            </a>

        </div>

    </div>

</section>


</main>


<!-- =========================================
     FOOTER
========================================= -->

<footer class="landing-footer">

    <div class="footer-inner">

        <div class="footer-brand">

            <img
                src="../images/verdexlogo.png"
                alt="VerdEX"
            >

            <strong>
                VerdEX
            </strong>

        </div>


        <p>
            Smart Hydroponic Farm Management
        </p>


        <span>
            © 2026 VerdEX
        </span>

    </div>

</footer>


</body>

</html>