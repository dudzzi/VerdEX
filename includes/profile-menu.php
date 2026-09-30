<?php

$profileFullName =
    $_SESSION["full_name"]
    ?? $_SESSION["username"]
    ?? "VerdEX User";

$profileRole =
    $_SESSION["role"]
    ?? "helper";

$profileInitial =
    strtoupper(
        substr(
            $profileFullName,
            0,
            1
        )
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
                    ucfirst($profileRole)
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


        <!-- ONLY OWNERS CAN CREATE HELPERS -->

        <?php if ($profileRole === "owner"): ?>

            <a
                href="#"
                id="createHelperProfileButton"
            >

                <span class="profile-popup-icon">
                    👥
                </span>

                <span>
                    Create Helper Profile
                </span>

            </a>

        <?php endif; ?>


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

<?php if ($profileRole === "owner"): ?>

<div
    class="helper-modal-overlay"
    id="helperModalOverlay"
>

    <div class="helper-modal">

        <div class="helper-modal-header">

            <div>
                <h2>Create Helper Profile</h2>
                <p>
                    Set up a profile for your helper.
                </p>
            </div>

            <button
                type="button"
                class="helper-modal-close"
                id="closeHelperModal"
            >
                ×
            </button>

        </div>


        <form
            id="createHelperForm"
            class="helper-modal-form"
        >

            <div class="helper-form-group">

                <label for="helperName">
                    Helper Name
                </label>

                <input
                    type="text"
                    id="helperName"
                    name="profile_name"
                    placeholder="Enter helper's name"
                    required
                >

            </div>


            <div class="helper-form-group">

                <label for="helperPin">
                    4-Digit PIN
                </label>

                <input
                    type="password"
                    id="helperPin"
                    name="pin"
                    placeholder="Enter 4-digit PIN"
                    maxlength="4"
                    inputmode="numeric"
                    required
                >

            </div>


            <div class="helper-form-group">

                <label for="confirmHelperPin">
                    Confirm PIN
                </label>

                <input
                    type="password"
                    id="confirmHelperPin"
                    name="confirm_pin"
                    placeholder="Enter PIN again"
                    maxlength="4"
                    inputmode="numeric"
                    required
                >

            </div>


            <div
                class="helper-modal-message"
                id="helperModalMessage"
            ></div>


            <div class="helper-modal-actions">

                <button
                    type="button"
                    class="helper-cancel-button"
                    id="cancelHelperModal"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="helper-create-button"
                >
                    Create Profile
                </button>

            </div>

        </form>

    </div>

</div>

<?php endif; ?>