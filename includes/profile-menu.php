<?php

$profileFullName =
    $_SESSION['full_name']
    ?? $_SESSION['username']
    ?? 'VerdEX User';

$profileRole =
    $_SESSION['role']
    ?? 'Team Member';

$profileInitial =
    strtoupper(
        substr($profileFullName, 0, 1)
    );

?>

<div
    class="profile-popup"
    id="profilePopup"
>

    <!-- ACCOUNT HEADER -->
    <div class="profile-popup-header">

        <div class="profile-popup-avatar">
            <?php
            echo htmlspecialchars(
                $profileInitial
            );
            ?>
        </div>

        <div class="profile-popup-user">

            <strong>
                <?php
                echo htmlspecialchars(
                    $profileFullName
                );
                ?>
            </strong>

            <span>
                <?php
                echo htmlspecialchars(
                    $profileRole
                );
                ?>
            </span>

            <a href="profile.php">
                View profile
            </a>

        </div>

    </div>


    <!-- PROFILE OPTIONS -->
    <div class="profile-popup-section">

        <a href="profile.php">
            <span class="profile-popup-icon">
                👤
            </span>

            <span>
                My Profile
            </span>
        </a>


        <a href="profile.php#security">
            <span class="profile-popup-icon">
                🔐
            </span>

            <span>
                Account & Security
            </span>
        </a>


        <a href="profile.php#settings">
            <span class="profile-popup-icon">
                ⚙️
            </span>

            <span>
                Settings
            </span>
        </a>

    </div>


    <!-- OTHER OPTIONS -->
    <div class="profile-popup-section">

        <a href="profile.php#help">
            <span class="profile-popup-icon">
                ❓
            </span>

            <span>
                Help & Support
            </span>
        </a>


        <a href="profile.php#about">
            <span class="profile-popup-icon">
                🌱
            </span>

            <span>
                About VerdEX
            </span>
        </a>


        <a
            href="../backend/logout.php"
            class="profile-popup-logout"
        >
            <span class="profile-popup-icon">
                ↪
            </span>

            <span>
                Sign Out
            </span>
        </a>

    </div>

</div>