<?php

require_once "../backend/access-control.php";

requireLogin();

require_once "../backend/db.php";


$categories = [];

$categoryQuery = $conn->query("
    SELECT category_id, category_name
    FROM forum_categories
    ORDER BY category_id ASC
");

if ($categoryQuery) {

    while ($categoryRow = $categoryQuery->fetch_assoc()) {

        $categories[] = [
            'id' => (int) $categoryRow['category_id'],
            'name' => $categoryRow['category_name']
        ];

    }

}


$posts = [];

$postQuery = $conn->query("
    SELECT
        fp.post_id,
        fp.author_name,
        fp.author_role,
        fp.title,
        fp.content,
        fp.likes,
        fp.created_at,
        fc.category_name
    FROM forum_posts fp
    INNER JOIN forum_categories fc
        ON fp.category_id = fc.category_id
    ORDER BY fp.created_at DESC
");


if ($postQuery) {

    while ($row = $postQuery->fetch_assoc()) {

        $createdTime = strtotime($row['created_at']);


        if (date('Y-m-d', $createdTime) === date('Y-m-d')) {

            $postDate = 'Today, ' . date('g:i A', $createdTime);

        } elseif (
            date('Y-m-d', $createdTime)
            === date('Y-m-d', strtotime('-1 day'))
        ) {

            $postDate = 'Yesterday, ' . date('g:i A', $createdTime);

        } else {

            $postDate = date('F j, Y', $createdTime);

        }


        $commentCount = 0;


        $commentStatement = $conn->prepare("
            SELECT COUNT(*) AS total
            FROM forum_comments
            WHERE post_id = ?
        ");


        if ($commentStatement) {

            $commentStatement->bind_param(
                "i",
                $row['post_id']
            );

            $commentStatement->execute();

            $commentResult = $commentStatement->get_result();


            if ($commentResult) {

                $commentData = $commentResult->fetch_assoc();

                if ($commentData) {

                    $commentCount = (int) $commentData['total'];

                }

            }


            $commentStatement->close();

        }


        $posts[] = [
            'author' => $row['author_name'],
            'role' => $row['author_role'],
            'date' => $postDate,
            'category' => $row['category_name'],
            'title' => $row['title'],
            'content' => $row['content'],
            'likes' => (int) $row['likes'],
            'comments' => $commentCount
        ];

    }

}


$memberCount = 0;

$memberQuery = $conn->query("
    SELECT COUNT(DISTINCT author_name) AS total
    FROM forum_posts
");


if ($memberQuery) {

    $memberData = $memberQuery->fetch_assoc();

    if ($memberData) {

        $memberCount = (int) $memberData['total'];

    }

}


$postCount = 0;

$postCountQuery = $conn->query("
    SELECT COUNT(*) AS total
    FROM forum_posts
");


if ($postCountQuery) {

    $postCountData = $postCountQuery->fetch_assoc();

    if ($postCountData) {

        $postCount = (int) $postCountData['total'];

    }

}


$categoryCounts = [];

$categoryCountQuery = $conn->query("
    SELECT
        fc.category_name,
        COUNT(fp.post_id) AS total
    FROM forum_categories fc
    LEFT JOIN forum_posts fp
        ON fc.category_id = fp.category_id
    GROUP BY
        fc.category_id,
        fc.category_name
    ORDER BY fc.category_id ASC
");


if ($categoryCountQuery) {

    while ($categoryRow = $categoryCountQuery->fetch_assoc()) {

        $categoryCounts[$categoryRow['category_name']] =
            (int) $categoryRow['total'];

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Forum | VerdEX</title>

    <link
        rel="stylesheet"
        href="../css/forum.css"
    >
    <link
    rel="stylesheet"
    href="../css/profile-menu.css"
    >

</head>

<body>


    <aside class="forum-sidebar">

        <a
            href="home.php"
            class="forum-logo"
        >

            <img
                src="../images/verdexlogo.png"
                alt="VerdEX"
            >

        </a>


        <nav class="forum-nav">

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

    <main class="forum-main">


        <header class="forum-header">

            <div>

                <h1>
                    Community Forum
                </h1>

                <p>
                    Share ideas, ask questions, and learn from other farmers.
                </p>

            </div>


            <button
                class="create-post-btn"
                id="openPostModal"
            >
                + Create Post
            </button>

        </header>



        <section class="forum-tools">


            <div class="forum-search">

                <span>
                    🔍
                </span>


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


                    <?php foreach ($categories as $category): ?>

                        <option
                            value="<?= htmlspecialchars($category['name']); ?>"
                        >
                            <?= htmlspecialchars($category['name']); ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </section>



        <section class="forum-layout">


            <div class="forum-posts">


                <?php if (empty($posts)): ?>

                    <div class="forum-empty">

                        <h2>
                            No posts yet
                        </h2>

                        <p>
                            Be the first to create a post in the VerdEX community.
                        </p>

                    </div>

                <?php else: ?>


                    <?php foreach ($posts as $post): ?>

                        <article
                            class="forum-post"
                            data-category="<?= htmlspecialchars($post['category']); ?>"
                        >


                            <div class="post-header">


                                <div class="post-user">


                                    <div class="post-avatar">

                                        <?= strtoupper(
                                            substr(
                                                $post['author'],
                                                0,
                                                1
                                            )
                                        ); ?>

                                    </div>


                                    <div>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $post['author']
                                            ); ?>

                                        </strong>


                                        <span>

                                            <?= htmlspecialchars(
                                                $post['role']
                                            ); ?>

                                            ·

                                            <?= htmlspecialchars(
                                                $post['date']
                                            ); ?>

                                        </span>

                                    </div>

                                </div>


                                <button class="post-menu">
                                    ⋮
                                </button>

                            </div>



                            <span class="post-category">

                                <?= htmlspecialchars(
                                    $post['category']
                                ); ?>

                            </span>



                            <h2>

                                <?= htmlspecialchars(
                                    $post['title']
                                ); ?>

                            </h2>


                            <p class="post-content">

                                <?= htmlspecialchars(
                                    $post['content']
                                ); ?>

                            </p>



                            <div class="post-actions">


                                <button
                                    class="post-action like-btn"
                                >

                                    ♡

                                    <span>

                                        <?= $post['likes']; ?>

                                    </span>

                                </button>


                                <button
                                    class="post-action comment-btn"
                                >

                                    💬

                                    <span>

                                        <?= $post['comments']; ?>

                                    </span>

                                </button>


                                <button
                                    class="post-action share-btn"
                                >

                                    ↗

                                    <span>
                                        Share
                                    </span>

                                </button>

                            </div>



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

                <?php endif; ?>


            </div>



            <aside class="forum-right">


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
                                <?= $memberCount; ?>
                            </strong>

                            <span>
                                Members
                            </span>

                        </div>


                        <div>

                            <strong>
                                <?= $postCount; ?>
                            </strong>

                            <span>
                                Posts
                            </span>

                        </div>

                    </div>

                </div>



                <div class="forum-side-card">


                    <h3>
                        Categories
                    </h3>


                    <a
                        href="#"
                        data-category="Crop Care"
                    >

                        🌱 Crop Care

                        <span>
                            <?= $categoryCounts['Crop Care'] ?? 0; ?>
                        </span>

                    </a>


                    <a
                        href="#"
                        data-category="Farming Tips"
                    >

                        💡 Farming Tips

                        <span>
                            <?= $categoryCounts['Farming Tips'] ?? 0; ?>
                        </span>

                    </a>


                    <a
                        href="#"
                        data-category="Pest Control"
                    >

                        🐛 Pest Control

                        <span>
                            <?= $categoryCounts['Pest Control'] ?? 0; ?>
                        </span>

                    </a>


                    <a
                        href="#"
                        data-category="General"
                    >

                        💬 General

                        <span>
                            <?= $categoryCounts['General'] ?? 0; ?>
                        </span>

                    </a>

                </div>



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



    <div
        class="post-modal"
        id="postModal"
    >

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


                <select
                    id="postCategory"
                    required
                >

                    <option value="">
                        Select a category
                    </option>


                    <?php foreach ($categories as $category): ?>

                        <option
                            value="<?= $category['id']; ?>"
                        >
                            <?= htmlspecialchars($category['name']); ?>
                        </option>

                    <?php endforeach; ?>

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
    <script src="../js/profile-menu.js"></script>                    
</body>

</html>