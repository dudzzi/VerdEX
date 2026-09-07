function openPostModal() {
    document.getElementById("postModal").classList.add("show");
}

function closePostModal() {
    document.getElementById("postModal").classList.remove("show");
}


/* LIKE POST */

function likePost(button) {

    let count = button.querySelector("span");

    let currentLikes = parseInt(count.textContent);

    if (button.classList.contains("liked")) {

        currentLikes--;

        button.classList.remove("liked");

        button.innerHTML = "♡ <span>" + currentLikes + "</span>";

    } else {

        currentLikes++;

        button.classList.add("liked");

        button.innerHTML = "♥ <span>" + currentLikes + "</span>";
    }
}


/* SHOW COMMENTS */

function toggleComments(button) {

    let post = button.closest(".post");

    let comments = post.querySelector(".comments");

    comments.classList.toggle("show");
}


/* ADD COMMENT */

function addComment(button) {

    let container = button.closest(".comments");

    let input = container.querySelector("input");

    let text = input.value.trim();

    if (text === "") {
        return;
    }

    let comment = document.createElement("div");

    comment.classList.add("comment");

    comment.innerHTML = `
        <strong>You</strong>
        <p>${text}</p>
    `;

    container.insertBefore(
        comment,
        container.querySelector(".comment-input")
    );

    input.value = "";
}


/* FILTER POSTS */

function filterPosts() {

    let filter = document.getElementById("postFilter").value;

    let posts = document.querySelectorAll(".post");

    posts.forEach(function(post) {

        let category = post.getAttribute("data-category");

        if (filter === "all" || filter === category) {

            post.style.display = "block";

        } else {

            post.style.display = "none";

        }

    });
}


/* CREATE POST */

function createPost() {

    let title = document.getElementById("postTitle").value.trim();

    let text = document.getElementById("postText").value.trim();

    let category = document.getElementById("postCategory").value;

    if (title === "" || text === "") {

        alert("Please complete your post.");

        return;
    }


    let categoryName = category.toUpperCase();


    let post = document.createElement("div");

    post.classList.add("post");

    post.setAttribute("data-category", category);


    post.innerHTML = `

        <div class="post-header">

            <div class="profile-circle">
                Z
            </div>

            <div>

                <strong>You</strong>

                <span>Hydroponic Farmer</span>

                <small>Just now</small>

            </div>

        </div>


        <div class="post-content">

            <span class="post-category ${category}">
                ${categoryName}
            </span>

            <h3>${title}</h3>

            <p>${text}</p>

        </div>


        <div class="post-actions">

            <button class="like-btn" onclick="likePost(this)">
                ♡ <span>0</span>
            </button>

            <button onclick="toggleComments(this)">
                💬 <span>0</span>
            </button>

        </div>


        <div class="comments">

            <div class="comment-input">

                <input
                    type="text"
                    placeholder="Write a comment..."
                >

                <button onclick="addComment(this)">
                    Post
                </button>

            </div>

        </div>
    `;


    let content = document.querySelector(".content");

    let firstPost = document.querySelector(".post");

    content.insertBefore(post, firstPost);


    document.getElementById("postTitle").value = "";

    document.getElementById("postText").value = "";

    closePostModal();
}