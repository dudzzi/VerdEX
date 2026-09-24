const openPostModal = document.getElementById("openPostModal");
const postModal = document.getElementById("postModal");
const closePostModal = document.getElementById("closePostModal");
const cancelPost = document.getElementById("cancelPost");
const createPostForm = document.getElementById("createPostForm");

if (openPostModal && postModal) {

    openPostModal.addEventListener("click", function () {
        postModal.classList.add("show");
    });

}

if (closePostModal && postModal) {

    closePostModal.addEventListener("click", function () {
        postModal.classList.remove("show");
    });

}

if (cancelPost && postModal) {

    cancelPost.addEventListener("click", function () {
        postModal.classList.remove("show");
    });

}

if (postModal) {

    postModal.addEventListener("click", function (event) {

        if (event.target === postModal) {
            postModal.classList.remove("show");
        }

    });

}

if (createPostForm) {

    createPostForm.addEventListener("submit", async function (event) {

        event.preventDefault();

        const title = document.getElementById("postTitle").value.trim();
        const category = document.getElementById("postCategory").value;
        const content = document.getElementById("postContent").value.trim();

        if (!title || !category || !content) {
            alert("Please complete all fields.");
            return;
        }

        const formData = new FormData();

        formData.append("action", "create_post");
        formData.append("title", title);
        formData.append("category_id", category);
        formData.append("content", content);

        try {

            const response = await fetch("../api/forum.php", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.success) {

                alert("Post created successfully!");

                createPostForm.reset();

                postModal.classList.remove("show");

                window.location.reload();

            } else {

                alert(
                    result.message ||
                    "Unable to create post."
                );

            }

        } catch (error) {

            console.error(error);

            alert("Unable to connect to the server.");

        }

    });

}


const categoryLinks = document.querySelectorAll(
    ".forum-side-card a[data-category]"
);

const forumPosts = document.querySelectorAll(
    ".forum-post"
);

const categoryFilter = document.getElementById("categoryFilter");

function filterPosts(category) {

    forumPosts.forEach(function (post) {

        const postCategory = post.getAttribute("data-category");

        if (
            category === "all" ||
            postCategory === category
        ) {

            post.style.display = "";

        } else {

            post.style.display = "none";

        }

    });

}

categoryLinks.forEach(function (link) {

    link.addEventListener("click", function (event) {

        event.preventDefault();

        const selectedCategory =
            link.getAttribute("data-category");

        filterPosts(selectedCategory);

        if (categoryFilter) {
            categoryFilter.value = selectedCategory;
        }

    });

});

if (categoryFilter) {

    categoryFilter.addEventListener("change", function () {

        filterPosts(this.value);

    });

}