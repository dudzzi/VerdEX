<?php

require_once "../backend/access-control.php";

requireLogin();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>VerdEX - Inventory</title>

    <link rel="stylesheet"
          href="../css/inventory.css">
    <link
    rel="stylesheet"
    href="../css/profile-menu.css"
    >

</head>

<body>


<!-- =====================================================
     SIDEBAR
     ===================================================== -->

<aside class="inventory-sidebar">

    <a href="home.php"
       class="inventory-logo">

        <img src="../images/verdexlogo.png"
             alt="VerdEX">

    </a>


    <nav class="inventory-nav">

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

<!-- =====================================================
     MAIN CONTENT
     ===================================================== -->

<main class="inventory-main">


    <!-- TOPBAR -->

    <header class="inventory-topbar">

        <div class="inventory-brand">

            <div class="inventory-brand-icon">
                🌿
            </div>

            <span>
                VerdEX
            </span>

        </div>


        <div class="inventory-top-actions">

            <div class="inventory-time">
                🕐
                <?php echo date('h:i A'); ?>
            </div>

            <div class="inventory-notification">
                🔔
                <span></span>
            </div>

        </div>

    </header>



    <!-- PAGE HEADER -->

    <section class="inventory-page-header">

        <div>

            <span class="inventory-eyebrow">
                FARM MANAGEMENT
            </span>

            <h1>
                Inventory
            </h1>

            <p>
                Manage your crops, fertilizers, and farming tools.
            </p>

        </div>


        <button class="add-button"
                onclick="openAddModal()">

            <span>+</span>
            Add Item

        </button>

    </section>



    <!-- =================================================
         INVENTORY CONTROLS
         ================================================= -->

    <section class="inventory-controls">


        <div class="category-buttons">

            <button
                class="category-button active"
                onclick="showCategory('plant', this)">

                Plants

            </button>


            <button
                class="category-button"
                onclick="showCategory('fertilizer', this)">

                Fertilizers

            </button>


            <button
                class="category-button"
                onclick="showCategory('tool', this)">

                Tools

            </button>

        </div>


        <select id="sortSelect"
                onchange="sortItems()">

            <option value="newest">
                Newest
            </option>

            <option value="oldest">
                Oldest
            </option>

            <option value="nameAsc">
                Name A-Z
            </option>

            <option value="nameDesc">
                Name Z-A
            </option>

            <option value="stockAsc">
                Stock Low-High
            </option>

            <option value="stockDesc">
                Stock High-Low
            </option>

        </select>

    </section>



    <!-- =================================================
         INVENTORY TABLE
         ================================================= -->

    <section class="inventory-table-card">

        <div class="table-header">

            <div>

                <h2>
                    Inventory Items
                </h2>

                <p>
                    View and manage your available items.
                </p>

            </div>

        </div>


        <div class="table-wrapper">

            <table class="inventory-table">

                <thead>

                    <tr>

                        <th>
                            Item
                        </th>

                        <th>
                            Type
                        </th>

                        <th>
                            Stock
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Date Added
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody id="inventoryTableBody">

                </tbody>

            </table>

        </div>


        <div id="emptyMessage"
             class="empty-message">

            No inventory items yet.

        </div>

    </section>


</main>



<!-- =====================================================
     ADD ITEM MODAL
     ===================================================== -->

<div id="addModal"
     class="modal">

    <div class="modal-content">


        <div class="modal-header">

            <div>

                <span class="modal-eyebrow">
                    INVENTORY
                </span>

                <h2>
                    Add Item
                </h2>

            </div>

            <button onclick="closeAddModal()">
                ×
            </button>

        </div>


        <form id="addForm">


            <label>
                Type
            </label>

            <select
                id="itemType"
                name="item_type"
                onchange="loadCatalogOptions()"
                required>

                <option value="plant">
                    Hydroponic Plant
                </option>

                <option value="fertilizer">
                    Fertilizer
                </option>

                <option value="tool">
                    Tool
                </option>

            </select>



            <label id="itemLabel">
                Plant
            </label>

            <select
                id="catalogId"
                name="catalog_id"
                onchange="showSelectedInformation()"
                required>

                <option value="">
                    Select an item
                </option>

            </select>



            <div id="autoInfo"
                 class="auto-info">

                <div class="auto-info-title">
                    Recommendation
                </div>

                <div id="autoInfoContent">

                    Select an item to view its recommendation.

                </div>

            </div>



            <label>
                Picture
            </label>

            <input
                type="file"
                name="image"
                accept="image/png,image/jpeg,image/webp">



            <label>
                Initial Stock
            </label>

            <input
                type="number"
                name="stock"
                min="0"
                value="0"
                required>



            <label>
                Unit
            </label>

            <input
                type="text"
                name="unit"
                value="pcs"
                maxlength="30"
                placeholder="pcs">



            <label>
                Notes
            </label>

            <textarea
                name="notes"
                placeholder="Add notes..."></textarea>



            <div class="modal-actions">

                <button
                    type="button"
                    class="cancel-button"
                    onclick="closeAddModal()">

                    Cancel

                </button>


                <button
                    type="submit"
                    class="save-button"
                    id="addSubmitButton">

                    Add Item

                </button>

            </div>


        </form>

    </div>

</div>



<!-- =====================================================
     ITEM DETAILS MODAL
     ===================================================== -->

<div id="infoModal"
     class="modal">

    <div class="modal-content details-modal">


        <div class="modal-header">

            <div>

                <span class="modal-eyebrow">
                    INVENTORY DETAILS
                </span>

                <h2 id="infoTitle">
                    Item Details
                </h2>

            </div>

            <button onclick="closeInfo()">
                ×
            </button>

        </div>


        <div id="infoContent">
        </div>


    </div>

</div>



<!-- =====================================================
     NOTES MODAL
     ===================================================== -->

<div id="notesModal"
     class="modal">

    <div class="modal-content">


        <div class="modal-header">

            <div>

                <span class="modal-eyebrow">
                    INVENTORY
                </span>

                <h2>
                    Notes
                </h2>

            </div>

            <button onclick="closeNotes()">
                ×
            </button>

        </div>


        <textarea
            id="notesInput"
            class="notes-input"
            placeholder="Write notes..."></textarea>


        <button
            class="save-button"
            onclick="saveNotes()">

            Save Notes

        </button>


    </div>

</div>



<script src="../js/inventory.js"></script>
<script src="../js/profile-menu.js"></script>
</body>

</html>