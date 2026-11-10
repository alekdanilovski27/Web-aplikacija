// Function to toggle like on a post
function toggleLike(postId) {
    const heartIcon = document.getElementById(`like-icon-${postId}`);
    const notification = document.getElementById(`notification-${postId}`);
    const likeCount = document.getElementById(`like-count-${postId}`);

    heartIcon.classList.toggle('liked');
    if (heartIcon.classList.contains('liked')) {
        heartIcon.src = 'hand-heart.png';
        notification.textContent = 'Liked!';
        likeCount.textContent = parseInt(likeCount.textContent) + 1 + ' Likes';
    } else {
        heartIcon.src = 'heart-outline.png';
        notification.textContent = 'Unliked!';
        likeCount.textContent = parseInt(likeCount.textContent) - 1 + ' Likes';
    }
    notification.classList.add('show');
    setTimeout(() => {
        notification.classList.remove('show');
    }, 1500);

    // Send AJAX request to update like status in the database
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'toggle_like.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`post_id=${postId}&user_id=${userId}`);
}

// Function to toggle the options menu for a post
function toggleOptionsMenu(postId) {
    const optionsMenu = document.getElementById(`options-menu-${postId}`);
    optionsMenu.style.display = optionsMenu.style.display === 'block' ? 'none' : 'block';
}

// Function to edit a post
function editPost(postId) {
    const newContent = prompt("Edit your post:");
    if (newContent) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'edit_post.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    loadPosts();
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('An error occurred while editing the post.');
            }
        };
        xhr.send(`post_id=${postId}&content=${encodeURIComponent(newContent)}`);
    }
}

// Function to delete a post
function deletePost(postId) {
    if (confirm("Are you sure you want to delete this post?")) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_post.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    loadPosts();
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('An error occurred while deleting the post.');
            }
        };
        xhr.send(`post_id=${postId}`);
    }
}