/* =================================
   VERDEX - FORUM JAVASCRIPT
================================= */


/* =================================
   CREATE POST MODAL
================================= */

const postModal = document.getElementById("postModal");
const openPostModal = document.getElementById("openPostModal");
const closePostModal = document.getElementById("closePostModal");
const cancelPost = document.getElementById("cancelPost");


/* Open modal */

if (openPostModal) {

    openPostModal.addEventListener("click", function () {

        postModal.classList.add("show");

    });

}


/* Close modal */

function closeModal() {

    if (postModal) {
        postModal.classList.remove("show");
    }

}


if (closePostModal) {

    closePostModal.addEventListener("click", closeModal);

}


if (cancelPost) {

    cancelPost.addEventListener("click", closeModal);

}


/* Close when clicking outside */

if (postModal) {

    postModal.addEventListener("click", function (event) {

        if (event.target === postModal) {

            closeModal();

        }

    });

}


/* =================================
   CREATE POST
================================= */

const createPostForm = document.getElementById("createPostForm");

if (createPostForm) {

    createPostForm.addEventListener("submit", function (event) {

        event.preventDefault();

        const title =
            document.getElementById("postTitle").value.trim();

        const category =
            document.getElementById("postCategory").value;

        const content =
            document.getElementById("postContent").value.trim();


        if (!title || !category || !content) {

            alert("Please complete all fields.");

            return;

        }


        /*
        | Temporary behavior
        |
        | Later this will send the post
        | to the PHP/MySQL backend.
        */

        alert("Post created successfully!");

        createPostForm.reset();

        closeModal();

    });

}


/* =================================
   LIKE BUTTON
================================= */

const likeButtons =
    document.querySelectorAll(".like-btn");


likeButtons.forEach(function (button) {

    button.addEventListener("click", function () {

        const countElement =
            button.querySelector("span");

        let count =
            parseInt(countElement.textContent);

        if (button.classList.contains("liked")) {

            count--;

            button.classList.remove("liked");

            button.querySelector("span:first-child");

            button.innerHTML =
                `♡ <span>${count}</span>`;

        } else {

            count++;

            button.classList.add("liked");

            button.innerHTML =
                `♥ <span>${count}</span>`;

        }

    });

});


/* =================================
   SEARCH POSTS
================================= */

const searchInput =
    document.getElementById("forumSearch");

const posts =
    document.querySelectorAll(".forum-post");


if (searchInput) {

    searchInput.addEventListener("input", function () {

        const searchText =
            searchInput.value.toLowerCase().trim();


        posts.forEach(function (post) {

            const postText =
                post.textContent.toLowerCase();


            if (postText.includes(searchText)) {

                post.style.display = "";

            } else {

                post.style.display = "none";

            }

        });

    });

}


/* =================================
   CATEGORY FILTER
================================= */

const categoryFilter =
    document.getElementById("categoryFilter");


if (categoryFilter) {

    categoryFilter.addEventListener("change", function () {

        const selectedCategory =
            categoryFilter.value;


        posts.forEach(function (post) {

            const postCategory =
                post.getAttribute("data-category");


            if (
                selectedCategory === "all" ||
                postCategory === selectedCategory
            ) {

                post.style.display = "";

            } else {

                post.style.display = "none";

            }

        });

    });

}


/* =================================
   CATEGORY SIDEBAR LINKS
================================= */

const categoryLinks =
    document.querySelectorAll(
        ".forum-side-card a[data-category]"
    );


categoryLinks.forEach(function (link) {

    link.addEventListener("click", function (event) {

        event.preventDefault();

        const category =
            link.getAttribute("data-category");


        if (categoryFilter) {

            categoryFilter.value = category;

            categoryFilter.dispatchEvent(
                new Event("change")
            );

        }

    });

});


/* =================================
   COMMENT BUTTON
================================= */

const commentButtons =
    document.querySelectorAll(".comment-btn");


commentButtons.forEach(function (button) {

    button.addEventListener("click", function () {

        const post =
            button.closest(".forum-post");

        if (!post) {
            return;
        }


        const commentInput =
            post.querySelector(
                ".post-comment-box input"
            );


        if (commentInput) {

            commentInput.focus();

        }

    });

});


/* =================================
   SEND COMMENT
================================= */

const commentBoxes =
    document.querySelectorAll(".post-comment-box");


commentBoxes.forEach(function (box) {

    const input =
        box.querySelector("input");

    const button =
        box.querySelector("button");


    button.addEventListener("click", function () {

        const comment =
            input.value.trim();


        if (!comment) {

            return;

        }


        /*
        | Temporary behavior.
        | Later comments will be saved
        | into MySQL.
        */

        alert("Comment added!");

        input.value = "";

    });


    input.addEventListener("keydown", function (event) {

        if (event.key === "Enter") {

            event.preventDefault();

            button.click();

        }

    });

});


/* =================================
   SHARE BUTTON
================================= */

const shareButtons =
    document.querySelectorAll(".share-btn");


shareButtons.forEach(function (button) {

    button.addEventListener("click", async function () {

        const post =
            button.closest(".forum-post");


        const title =
            post.querySelector("h2").textContent;


        /*
        | Use browser share feature
        | when available.
        */

        if (navigator.share) {

            try {

                await navigator.share({

                    title: "VerdEX Community",

                    text: title,

                    url: window.location.href

                });

            } catch (error) {

                // User cancelled sharing.

            }

        } else {

            /*
            | Fallback for browsers
            | without Web Share API.
            */

            try {

                await navigator.clipboard.writeText(
                    window.location.href
                );

                alert("Forum link copied!");

            } catch (error) {

                alert("Unable to copy the link.");

            }

        }

    });

});