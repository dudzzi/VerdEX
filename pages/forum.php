<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| SAMPLE FORUM POSTS
|--------------------------------------------------------------------------
| Temporary data for the UI.
| Later, this will come from the MySQL database.
|--------------------------------------------------------------------------
*/

$posts = [
    [
        'author' => 'Juan Dela Cruz',
        'role' => 'Farm Owner',
        'date' => 'Today, 9:30 AM',
        'category' => 'Crop Care',
        'title' => 'What is the best way to handle yellowing leaves?',
        'content' => 'Some of my tomato plants have started developing yellow leaves. Has anyone experienced this before?',
        'likes' => 12,
        'comments' => 4
    ],

    [
        'author' => 'Maria Santos',
        'role' => 'Farm Helper',
        'date' => 'Yesterday, 4:15 PM',
        'category' => 'Farming Tips',
        'title' => 'Tips for keeping soil moisture stable',
        'content' => 'I noticed that keeping the soil moisture within a consistent range helped our crops grow better. Sharing some tips that worked for our farm.',
        'likes' => 8,
        'comments' => 3
    ],

    [
        'author' => 'Pedro Reyes',
        'role' => 'Farm Owner',
        'date' => 'September 21, 2026',
        'category' => 'Pest Control',
        'title' => 'How do you deal with common garden pests?',
        'content' => 'Looking for safe and practical ways to prevent pests from damaging vegetables without affecting the crops.',
        'likes' => 15,
        'comments' => 6
    ]
];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forum | VerdEX</title>

    <link rel="stylesheet" href="../css/forum.css">

</head>

<body>

    <!-- ================================
         SIDEBAR
    ================================= -->

    <aside class="forum-sidebar">

        <a href="home.php" class="forum-logo">
            <img src="../images/verdexlogo.png" alt="VerdEX">
        </a>

        <nav class="forum-nav">

            <a href="home.php" title="Dashboard">
                🏠
            </a>

            <a href="inventory.php" title="Inventory">
                📦
            </a>

            <a href="calendar.php" title="Calendar">
                📅
            </a>

            <a href="sales.php" title="Sales">
                💰
            </a>

            <a href="status.php" title="Farm Status">
                💧
            </a>

            <a href="forum.php" class="active" title="Forum">
                💬
            </a>

            <a href="reports.php" title="Reports">
                📊
            </a>

        </nav>

        <div class="forum-nav-bottom">

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


    <!-- ================================
         MAIN CONTENT
    ================================= -->

    <main class="forum-main">

        <!-- HEADER -->

        <header class="forum-header">

            <div>

                <h1>Community Forum</h1>

                <p>
                    Share ideas, ask questions, and learn from other farmers.
                </p>

            </div>

            <button class="create-post-btn" id="openPostModal">
                + Create Post
            </button>

        </header>


        <!-- ================================
             SEARCH & FILTER
        ================================= -->

        <section class="forum-tools">

            <div class="forum-search">

                <span>🔍</span>

                <input
                    type="text"
                    id="forumSearch"
                    placeholder="Search posts..."
                >

            </div>


            <div class="forum-filter">

                <select id="categoryFilter">

                    <option value="all">
                        All Categories
                    </option>

                    <option value="Crop Care">
                        Crop Care
                    </option>

                    <option value="Farming Tips">
                        Farming Tips
                    </option>

                    <option value="Pest Control">
                        Pest Control
                    </option>

                    <option value="General">
                        General
                    </option>

                </select>

            </div>

        </section>


        <!-- ================================
             FORUM CONTENT
        ================================= -->

        <section class="forum-layout">


            <!-- POSTS -->

            <div class="forum-posts">

                <?php foreach ($posts as $post): ?>

                    <article
                        class="forum-post"
                        data-category="<?= htmlspecialchars($post['category']); ?>"
                    >

                        <!-- POST HEADER -->

                        <div class="post-header">

                            <div class="post-user">

                                <div class="post-avatar">
                                    <?= strtoupper(substr($post['author'], 0, 1)); ?>
                                </div>

                                <div>

                                    <strong>
                                        <?= htmlspecialchars($post['author']); ?>
                                    </strong>

                                    <span>
                                        <?= htmlspecialchars($post['role']); ?>
                                        ·
                                        <?= htmlspecialchars($post['date']); ?>
                                    </span>

                                </div>

                            </div>

                            <button class="post-menu">
                                ⋮
                            </button>

                        </div>


                        <!-- CATEGORY -->

                        <span class="post-category">
                            <?= htmlspecialchars($post['category']); ?>
                        </span>


                        <!-- POST CONTENT -->

                        <h2>
                            <?= htmlspecialchars($post['title']); ?>
                        </h2>

                        <p class="post-content">
                            <?= htmlspecialchars($post['content']); ?>
                        </p>


                        <!-- POST ACTIONS -->

                        <div class="post-actions">

                            <button class="post-action like-btn">

                                ♡

                                <span>
                                    <?= $post['likes']; ?>
                                </span>

                            </button>


                            <button class="post-action comment-btn">

                                💬

                                <span>
                                    <?= $post['comments']; ?>
                                </span>

                            </button>


                            <button class="post-action share-btn">

                                ↗

                                <span>
                                    Share
                                </span>

                            </button>

                        </div>


                        <!-- COMMENTS -->

                        <div class="post-comment-box">

                            <input
                                type="text"
                                placeholder="Write a comment..."
                            >

                            <button>
                                Send
                            </button>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>


            <!-- ================================
                 RIGHT SIDEBAR
            ================================= -->

            <aside class="forum-right">


                <!-- COMMUNITY CARD -->

                <div class="forum-info-card">

                    <div class="forum-info-icon">
                        🌱
                    </div>

                    <h3>
                        VerdEX Community
                    </h3>

                    <p>
                        Connect with other farmers and share
                        knowledge about farming.
                    </p>

                    <div class="community-stats">

                        <div>

                            <strong>
                                128
                            </strong>

                            <span>
                                Members
                            </span>

                        </div>

                        <div>

                            <strong>
                                56
                            </strong>

                            <span>
                                Posts
                            </span>

                        </div>

                    </div>

                </div>


                <!-- CATEGORIES -->

                <div class="forum-side-card">

                    <h3>
                        Categories
                    </h3>

                    <a href="#" data-category="Crop Care">
                        🌱 Crop Care
                        <span>18</span>
                    </a>

                    <a href="#" data-category="Farming Tips">
                        💡 Farming Tips
                        <span>14</span>
                    </a>

                    <a href="#" data-category="Pest Control">
                        🐛 Pest Control
                        <span>9</span>
                    </a>

                    <a href="#" data-category="General">
                        💬 General
                        <span>15</span>
                    </a>

                </div>


                <!-- COMMUNITY GUIDELINES -->

                <div class="forum-side-card">

                    <h3>
                        Community Guidelines
                    </h3>

                    <p>
                        Keep discussions respectful and helpful.
                    </p>

                    <p>
                        Share useful farming knowledge and experiences.
                    </p>

                    <p>
                        Avoid spam and unrelated content.
                    </p>

                </div>

            </aside>

        </section>

    </main>


    <!-- ================================
         CREATE POST MODAL
    ================================= -->

    <div class="post-modal" id="postModal">

        <div class="post-modal-content">

            <div class="modal-header">

                <div>

                    <h2>
                        Create a Post
                    </h2>

                    <p>
                        Share something with the VerdEX community.
                    </p>

                </div>

                <button id="closePostModal">
                    ×
                </button>

            </div>


            <form id="createPostForm">

                <label for="postTitle">
                    Title
                </label>

                <input
                    type="text"
                    id="postTitle"
                    placeholder="What do you want to talk about?"
                    required
                >


                <label for="postCategory">
                    Category
                </label>

                <select id="postCategory" required>

                    <option value="">
                        Select a category
                    </option>

                    <option value="Crop Care">
                        Crop Care
                    </option>

                    <option value="Farming Tips">
                        Farming Tips
                    </option>

                    <option value="Pest Control">
                        Pest Control
                    </option>

                    <option value="General">
                        General
                    </option>

                </select>


                <label for="postContent">
                    Description
                </label>

                <textarea
                    id="postContent"
                    rows="6"
                    placeholder="Write your post..."
                    required
                ></textarea>


                <div class="modal-actions">

                    <button
                        type="button"
                        class="cancel-btn"
                        id="cancelPost"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="submit-post-btn"
                    >
                        Publish Post
                    </button>

                </div>

            </form>

        </div>

    </div>


    <script src="../js/forum.js"></script>

</body>

</html>