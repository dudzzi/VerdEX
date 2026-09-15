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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VerdEX - Home</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="../images/verdexlogo.png" alt="VerdEX Logo">
        </div>

        <button class="sidebar-toggle" onclick="toggleSidebar()">‹</button>

        <a href="home.php" class="active">Home</a>
        <a href="inventory.html">Inventory</a>
        <a href="calendar.html">Calendar</a>
        <a href="sales.html">Sales</a>
        <a href="status.html">Status</a>
        <a href="trends.html">Trends</a>
        <a href="reports.html">Weekly Reports</a>

        <h4>About Profile</h4>
        <a href="profile.html">My Profile</a>
        <a href="settings.html">Settings</a>
        <a href="../backend/logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="home-top-nav">
            <a href="#dashboard" class="home-nav-bubble active">Dashboard</a>
            <a href="#description" class="home-nav-bubble">Project Description</a>
            <a href="#features" class="home-nav-bubble">Project Features</a>
            <a href="#members" class="home-nav-bubble">Group Members</a>
        </div>

        <section id="dashboard" class="home-section">
            <div class="page-heading">
                <span class="eyebrow">Overview</span>
                <h1>Welcome, <?php echo htmlspecialchars($fullName); ?></h1>
                <p class="page-heading-sub">VerdEX — Smart Hydroponic Farm Management System</p>
                <span class="role-chip">Your role: <?php echo htmlspecialchars($role); ?></span>
            </div>

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

            <div class="quick-links-grid">
                <a href="inventory.html" class="glass-panel quick-link-card">
                    <span class="panel-label">Inventory</span>
                    <p>Track hydroponic plants, fertilizers, and tools in stock.</p>
                </a>
                <a href="status.html" class="glass-panel quick-link-card">
                    <span class="panel-label">Status</span>
                    <p>View live temperature, humidity, and water level readings.</p>
                </a>
                <a href="sales.html" class="glass-panel quick-link-card">
                    <span class="panel-label">Sales</span>
                    <p>Review recent transactions and revenue.</p>
                </a>
                <a href="trends.html" class="glass-panel quick-link-card">
                    <span class="panel-label">Trends</span>
                    <p>See announcements and farm activity trends.</p>
                </a>
            </div>
        </section>

        <section id="description" class="home-section">
            <div class="page-heading compact-heading">
                <span class="eyebrow">Documentation</span>
                <h2>Project Description</h2>
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

        <section id="features" class="home-section">
            <div class="page-heading compact-heading">
                <span class="eyebrow">Capabilities</span>
                <h2>Project Features</h2>
                <p class="page-heading-sub">Major capabilities of the VerdEX system.</p>
            </div>

            <div class="feature-grid showcase-feature-grid">
                <a href="inventory.html" class="glass-panel feature-item showcase-feature-link">
                    <div class="feature-item-top"><span class="panel-label"><span class="feature-emoji">📦</span> Inventory Management</span><span class="demo-badge">Live</span></div>
                    <p>Organize and track hydroponic plant stock, fertilizers, and tools in one centralized module.</p>
                </a>

                <a href="status.html" class="glass-panel feature-item showcase-feature-link">
                    <div class="feature-item-top"><span class="panel-label"><span class="feature-emoji">💧</span> Water Level & Environmental Monitoring</span><span class="demo-badge">Live</span></div>
                    <p>Monitor water level, temperature, and humidity using connected sensors.</p>
                </a>

                <div class="glass-panel feature-item">
                    <div class="feature-item-top"><span class="panel-label"><span class="feature-emoji">🚨</span> Critical-Condition Notifications</span></div>
                    <p>Alert the farm owner and helpers when temperature, humidity, or water level reaches an unsafe range.</p>
                </div>

                <a href="calendar.html" class="glass-panel feature-item showcase-feature-link">
                    <div class="feature-item-top"><span class="panel-label"><span class="feature-emoji">📅</span> Plant Calendar</span><span class="demo-badge">Live</span></div>
                    <p>Schedule and track planting, nutrient replacement, maintenance, and harvesting activities.</p>
                </a>

                <a href="reports.html" class="glass-panel feature-item showcase-feature-link">
                    <div class="feature-item-top"><span class="panel-label"><span class="feature-emoji">📊</span> Monthly Reports</span><span class="demo-badge">Live</span></div>
                    <p>Review reports covering sales, inventory levels, and overall farm condition.</p>
                </a>

                <div class="glass-panel feature-item">
                    <div class="feature-item-top"><span class="panel-label"><span class="feature-emoji">🤖</span> AI-Based Recommendations</span></div>
                    <p>Provide suggestions on suitable fertilizers, growing media, and hydroponic plant care practices.</p>
                </div>
            </div>
        </section>

        <section id="members" class="home-section">
            <div class="page-heading compact-heading">
                <span class="eyebrow">Our Team</span>
                <h2>Meet the Team Behind VerdEX</h2>
            </div>

            <div class="team-showcase-grid">
                <div class="glass-panel team-showcase-card">
                    <div class="team-avatar">ZS</div>
                    <span class="panel-label">Project Manager / Data Analyst</span>
                    <h3>Animos, Zandro Sean D.</h3>
                    <p>Leads the project and handles data analysis and coordination.</p>
                </div>

                <div class="glass-panel team-showcase-card">
                    <div class="team-avatar">SA</div>
                    <span class="panel-label">Frontend Developer / Lead Documenter</span>
                    <h3>Austria, Sam Aisele A.</h3>
                    <p>Builds the user interface and leads project documentation.</p>
                </div>

                <div class="glass-panel team-showcase-card">
                    <div class="team-avatar">JC</div>
                    <span class="panel-label">UI/UX Designer / Database Manager</span>
                    <h3>Cervantes, Jaypee D.</h3>
                    <p>Designs the user experience and manages the project database.</p>
                </div>

                <div class="glass-panel team-showcase-card">
                    <div class="team-avatar">LM</div>
                    <span class="panel-label">Backend Developer / IoT Developer</span>
                    <h3>Chavez, Lorenzo M.</h3>
                    <p>Develops backend services and handles IoT and sensor integration.</p>
                </div>
            </div>
        </section>

        <div class="glass-panel info-panel home-account-panel">
            <span class="panel-label">Current Account</span>
            <p>Signed in as <strong><?php echo htmlspecialchars($fullName); ?></strong> — <?php echo htmlspecialchars($role); ?>.</p>
            <a href="../backend/logout.php" class="btn-secondary">Log Out</a>
        </div>
    </div>

    <script src="../js/sidebar.js"></script>
    <script>
        document.querySelectorAll('.home-nav-bubble').forEach(function (link) {
            link.addEventListener('click', function () {
                document.querySelectorAll('.home-nav-bubble').forEach(function (item) {
                    item.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
