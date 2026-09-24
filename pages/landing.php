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
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="landing-page">

        <nav class="landing-topbar">
            <div class="landing-brand">
                <img src="../images/verdexlogo.png" alt="VerdEX Logo" class="landing-brand-logo">
            </div>

            <div class="landing-nav-links">
                <a href="#hero" class="landing-nav-link active">Overview</a>
                <a href="#about" class="landing-nav-link">About</a>
                <a href="#features" class="landing-nav-link">Features</a>
                <a href="#team" class="landing-nav-link">Team</a>
            </div>

            <a href="login.php" class="btn-primary">Log In</a>
        </nav>

        <main>

            <!-- HERO -->
            <section id="hero" class="landing-hero">
                <span class="eyebrow">Smart Hydroponic Farm Management</span>
                <h1>Grow smarter, manage better with VerdEX</h1>
                <p class="landing-hero-sub">
                    Real-time water level, humidity, and temperature monitoring, paired with
                    AI-powered recommendations — so hydroponic farms run on data, not guesswork.
                </p>
                <div class="landing-hero-actions">
                    <a href="login.php" class="btn-primary">Log In</a>
                    <a href="#features" class="btn-secondary">See Features</a>
                </div>
            </section>

            <!-- PROBLEM / SOLUTION -->
            <section class="landing-section">
                <div class="panel-grid panel-grid-2">
                    <div class="glass-panel info-panel">
                        <span class="panel-label">Problem</span>
                        <p>Hydroponic farming requires constant, precise monitoring of water level, humidity, and temperature. Manual tracking is time-consuming, error-prone, and difficult to scale.</p>
                    </div>

                    <div class="glass-panel info-panel">
                        <span class="panel-label">Solution</span>
                        <p>VerdEX automates monitoring with connected sensors, giving growers real-time data, critical-condition alerts, and AI-based recommendations to reduce manual work and crop loss.</p>
                    </div>
                </div>
            </section>

            <!-- ABOUT / DESCRIPTION -->
            <section id="about" class="landing-section">
                <div class="page-heading compact-heading landing-section-heading">
                    <span class="eyebrow">About</span>
                    <h2>What is VerdEX?</h2>
                </div>

                <div class="glass-panel info-panel">
                    <span class="panel-label">About VerdEX</span>
                    <p>VerdEX is a web-based smart hydroponic farm management system that integrates water level monitoring, humidity and temperature sensors, and AI-powered assistance to help hydroponic growers manage their farms more efficiently.</p>
                </div>

                <div class="panel-grid panel-grid-2">
                    <div class="glass-panel info-panel">
                        <span class="panel-label">Proposed Users</span>
                        <ul class="showcase-list">
                            <li>Hydroponic farm owners</li>
                            <li>Farm helpers</li>
                            <li>Farm managers</li>
                        </ul>
                    </div>

                    <div class="glass-panel info-panel">
                        <span class="panel-label">General Capabilities</span>
                        <ul class="showcase-list">
                            <li>Monitor farm conditions</li>
                            <li>Manage inventory</li>
                            <li>Schedule farm activities</li>
                            <li>Review sales and reports</li>
                            <li>Receive critical-condition notifications</li>
                            <li>Get AI-based farm guidance</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- FEATURES -->
            <section id="features" class="landing-section">
                <div class="page-heading compact-heading landing-section-heading">
                    <span class="eyebrow">Capabilities</span>
                    <h2>Everything your farm needs, in one place</h2>
                    <p class="page-heading-sub">Major capabilities of the VerdEX system.</p>
                </div>

                <div class="feature-grid showcase-feature-grid">
                    <div class="glass-panel feature-item">
                        <div class="feature-item-top"><span class="panel-label"><span class="feature-emoji">📦</span> Inventory Management</span><span class="demo-badge">Live</span></div>
                        <p>Organize and track hydroponic plant stock, fertilizers, and tools in one centralized module.</p>
                    </div>

                    <div class="glass-panel feature-item">
                        <div class="feature-item-top"><span class="panel-label"><span class="feature-emoji">💧</span> Water Level & Environmental Monitoring</span><span class="demo-badge">Live</span></div>
                        <p>Monitor water level, temperature, and humidity using connected sensors.</p>
                    </div>

                    <div class="glass-panel feature-item">
                        <div class="feature-item-top"><span class="panel-label"><span class="feature-emoji">🚨</span> Critical-Condition Notifications</span></div>
                        <p>Alert the farm owner and helpers when temperature, humidity, or water level reaches an unsafe range.</p>
                    </div>

                    <div class="glass-panel feature-item">
                        <div class="feature-item-top"><span class="panel-label"><span class="feature-emoji">📅</span> Plant Calendar</span><span class="demo-badge">Live</span></div>
                        <p>Schedule and track planting, nutrient replacement, maintenance, and harvesting activities.</p>
                    </div>

                    <div class="glass-panel feature-item">
                        <div class="feature-item-top"><span class="panel-label"><span class="feature-emoji">📊</span> Monthly Reports</span><span class="demo-badge">Live</span></div>
                        <p>Review reports covering sales, inventory levels, and overall farm condition.</p>
                    </div>

                    <div class="glass-panel feature-item">
                        <div class="feature-item-top"><span class="panel-label"><span class="feature-emoji">🤖</span> AI-Based Recommendations</span></div>
                        <p>Provide suggestions on suitable fertilizers, growing media, and hydroponic plant care practices.</p>
                    </div>
                </div>
            </section>

            <!-- TEAM -->
            <section id="team" class="landing-section">
                <div class="page-heading compact-heading landing-section-heading">
                    <span class="eyebrow">Our Team</span>
                    <h2>Meet the team behind VerdEX</h2>
                </div>

                <div class="landing-team-grid">

                        <div class="glass-panel landing-team-card">
                        <div class="landing-team-header">
                            <!-- MEMBER 1 BACKGROUND VIDEO — replace videos/member1-bg.mp4 with the real file -->
                            <div class="landing-team-media-wrap">
                                <video class="landing-team-video" autoplay muted loop playsinline>
                                    <source src="../videos/member1-bg.mp4" type="video/mp4">
                                </video>
                            </div>
                            <div class="landing-team-overlay"></div>
                        </div>
                        <div class="landing-team-avatar-ring">
                            <img src="../images/team/zandro.jpg" alt="Zandro Sean Animos"
                                onerror="this.remove(); this.parentElement.classList.add('no-photo');">
                            <span class="landing-team-initials">ZS</span>
                        </div>
                        <div class="landing-team-body">
                            <h3>Animos, Zandro Sean D.</h3>
                            <span class="landing-team-role">Project Manager / Data Analyst</span>
                            <p>Leads the project and handles data analysis and coordination.</p>
                        </div>
                    </div>

                        <div class="glass-panel landing-team-card">
                        <div class="landing-team-header">
                            <!-- MEMBER 2 BACKGROUND VIDEO — replace videos/member2-bg.mp4 with the real file -->
                            <div class="landing-team-media-wrap">
                                <video class="landing-team-video" autoplay muted loop playsinline>
                                    <source src="../videos/member2-bg.mp4" type="video/mp4">
                                </video>
                            </div>
                            <div class="landing-team-overlay"></div>
                        </div>
                        <div class="landing-team-avatar-ring">
                            <img src="../images/team/sam.jpg" alt="Sam Aisele Austria"
                                onerror="this.remove(); this.parentElement.classList.add('no-photo');">
                            <span class="landing-team-initials">SA</span>
                        </div>
                        <div class="landing-team-body">
                            <h3>Austria, Sam Aisele A.</h3>
                            <span class="landing-team-role">Frontend Developer / Lead Documenter</span>
                            <p>Builds the user interface and leads project documentation.</p>
                        </div>
                    </div>

                        <div class="glass-panel landing-team-card">
                        <div class="landing-team-header">
                            <!-- MEMBER 3 BACKGROUND VIDEO — replace videos/member3-bg.mp4 with the real file -->
                            <div class="landing-team-media-wrap">
                                <video class="landing-team-video" autoplay muted loop playsinline>
                                    <source src="../videos/member3-bg.mp4" type="video/mp4">
                                </video>
                            </div>
                            <div class="landing-team-overlay"></div>
                        </div>
                        <div class="landing-team-avatar-ring">
                            <img src="../images/team/jaypee.jpg" alt="Jaypee Cervantes"
                                onerror="this.remove(); this.parentElement.classList.add('no-photo');">
                            <span class="landing-team-initials">JC</span>
                        </div>
                        <div class="landing-team-body">
                            <h3>Cervantes, Jaypee D.</h3>
                            <span class="landing-team-role">UI/UX Designer / Database Manager</span>
                            <p>Designs the user experience and manages the project database.</p>
                        </div>
                    </div>

                        <div class="glass-panel landing-team-card">
                        <div class="landing-team-header">
                            <!-- MEMBER 4 BACKGROUND VIDEO — replace videos/member4-bg.mp4 with the real file -->
                            <div class="landing-team-media-wrap">
                                <video class="landing-team-video" autoplay muted loop playsinline>
                                    <source src="../videos/member4-bg.mp4" type="video/mp4">
                                </video>
                            </div>
                            <div class="landing-team-overlay"></div>
                        </div>
                        <div class="landing-team-avatar-ring">
                            <img src="../images/team/lorenzo.png" alt="Lorenzo Chavez"
                                onerror="this.remove(); this.parentElement.classList.add('no-photo');">
                            <span class="landing-team-initials">LM</span>
                        </div>
                        <div class="landing-team-body">
                            <h3>Chavez, Lorenzo M.</h3>
                            <span class="landing-team-role">Backend Developer / IoT Developer</span>
                            <p>Develops backend services and handles IoT and sensor integration.</p>
                        </div>
                    </div>

                </div>
            </section>

            <!-- FINAL CTA -->
            <section class="landing-section">
                <div class="glass-panel landing-cta">
                    <div>
                        <span class="panel-label">Get Started</span>
                        <p>Ready to manage your farm? Log in to access your VerdEX dashboard.</p>
                    </div>
                    <a href="login.php" class="btn-primary">Log In</a>
                </div>
            </section>

        </main>

        <footer class="landing-footer">
            <img src="../images/verdexlogo.png" alt="VerdEX Logo" class="landing-footer-logo">
            <p>VerdEX — Grow Smart. Manage Better.</p>
        </footer>

    </div>

    <script>
        const sections = document.querySelectorAll('#hero, #about, #features, #team');
        const navLinks = document.querySelectorAll('.landing-nav-link');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    navLinks.forEach((link) => link.classList.remove('active'));
                    const activeLink = document.querySelector('.landing-nav-link[href="#' + entry.target.id + '"]');
                    if (activeLink) {
                        activeLink.classList.add('active');
                    }
                }
            });
        }, { rootMargin: '-40% 0px -55% 0px' });

        sections.forEach((section) => observer.observe(section));
    </script>

</body>
</html>