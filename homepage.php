<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "usbw";
$dbname = "korisnici";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT firstname, lastname, pp FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname, $profilePicture);
$stmt->fetch();
$stmt->close();

$user_name = $firstname . ' ' . $lastname;

// Fetch all users for the "Friends" section
$users_sql = "SELECT id, firstname, lastname FROM users WHERE id != $user_id"; // Exclude the current user
$users_result = $conn->query($users_sql);

// Fetch friend relationships
$friends_sql = "SELECT friend_id, status FROM friends WHERE user_id = $user_id";
$friends_result = $conn->query($friends_sql);
$friends = [];
while ($row = $friends_result->fetch_assoc()) {
    $friends[$row['friend_id']] = $row['status'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="homepagee.css">
</head>
<body data-user-id="<?php echo $user_id; ?>">
<?php
// Include navbar.php
include('navbar.php');
?>
<div class="layout-container">
    <div class="sidebar">
        <h1 class="brand">MySpace</h1>
        <nav>
            <ul>
                <li><a href="friends.php">Friends</a></li>
                <li><a href="groups.php">Groups</a></li>
            </ul>
        </nav>

        <!-- Display list of users to add as friends -->
        <div class="friends-list">
            <h3>Users</h3>
            <ul>
                <?php while ($user_row = $users_result->fetch_assoc()): ?>
                    <li>
                        <?php echo $user_row['firstname'] . ' ' . $user_row['lastname']; ?>
                        <div style="display:inline;">
                            <input type="hidden" name="friend_id" value="<?php echo $user_row['id']; ?>">
                            <?php if (isset($friends[$user_row['id']]) && $friends[$user_row['id']] === 'accepted'): ?>
                                <button onclick="removeFriend(<?php echo $user_row['id']; ?>)">Remove Friend</button>
                            <?php elseif (isset($friends[$user_row['id']]) && $friends[$user_row['id']] === 'pending'): ?>
                                <button onclick="acceptFriend(<?php echo $user_row['id']; ?>)">Accept Request</button>
                                <button onclick="removeFriend(<?php echo $user_row['id']; ?>)">Cancel Request</button>
                            <?php else: ?>
                                <button onclick="addFriend(<?php echo $user_row['id']; ?>)">Add Friend</button>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="profile">
            <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture">
            <span><?php echo htmlspecialchars($user_name); ?></span>
        </div>

        <div class="hnavbar">
            <input type="text" id="post-content" placeholder="What's on your mind?">
            <button onclick="document.getElementById('file-upload').click();">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Photo
            </button>
        </div>

        <input type="file" id="file-upload" style="display: none;" accept="image/*">

        <div class="post">
            <button onclick="createPost()">Post</button>
        </div>

        <div class="posts" id="posts"></div>
    </div>

    <div class="right-sidebar">
        <h3>Trending Now</h3>
        <ul>
            <li><a href="bitcoin/home.html" target="_blank">#Bitcoin</a></li>
            <li><a href="tehnology/home.html" target="_blank">#Tehnology Today</a></li>
            <li><a href="esports/home html.html" target="_blank">#TravelGoals</a></li>
        </ul>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const userId = document.body.getAttribute('data-user-id');

    function loadPosts() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_posts.php', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    const postsContainer = document.getElementById('posts');
                    postsContainer.innerHTML = '';
                    response.posts.forEach(post => {
                        const postId = post.post_id;
                        const postElement = document.createElement('div');
                        postElement.classList.add('post-container');
                        postElement.style.width = '100%';  // Зголемете ја ширината за секој поединечен пост
                        postElement.innerHTML = `
                            <div class="notification" id="notification-${postId}">Liked!</div>
                            <div class="post-header">
                                <div class="profile">
                                    <img src="${post.profile_picture}" alt="Profile Picture" class="post-profile-img">
                                    <div class="profile-info">
                                        <span class="username">${post.firstname} ${post.lastname}</span>
                                        <span class="post-date">${post.created_at}</span>
                                    </div>
                                </div>
                                <div class="options" onclick="toggleOptionsMenu(${postId})">
                                    <img src="three-dots.png" alt="Options" width="20" height="20">
                                    <div class="options-menu" id="options-menu-${postId}">
                                        <div class="menu-item" onclick="editPost(${postId})">Edit Post</div>
                                        <div class="menu-item" onclick="deletePost(${postId})">Delete Post</div>
                                    </div>
                                </div>
                            </div>
                            <div class="post-text">${post.content}</div>
                            ${post.image ? `<img src="${post.image}" alt="Post Image" class="post-image">` : ''}
                            <div class="post-actions">
                                <div class="action" onclick="toggleLike(${postId})">
                                    <img src="heart-outline.png" alt="Like" id="like-icon-${postId}" class="heart">
                                    <span id="like-count-${postId}">${post.like_count} Likes</span>
                                </div>
                                <div class="action">
                                    <input type="text" id="comment-content-${postId}" placeholder="Add a comment...">
                                    <button onclick="addComment(${postId})">Comment</button>
                                </div>
                            </div>
                            <div class="comments" id="comments-${postId}">
                                ${post.comments.map(comment => `
                                    <div class="comment">
                                        <div class="profile">
                                            <img src="${comment.profile_picture}" alt="Profile Picture" class="comment-profile-img">
                                            <div class="profile-info">
                                                <span class="username">${comment.firstname} ${comment.lastname}</span>
                                                <span class="comment-date">${comment.created_at}</span>
                                            </div>
                                        </div>
                                        <div class="comment-text">${comment.content}</div>
                                        <div class="comment-actions">
                                            <div class="action" onclick="toggleCommentLike(${comment.comment_id})">
                                                <img src="heart-outline.png" alt="Like" id="comment-like-icon-${comment.comment_id}" class="heart">
                                                <span id="comment-like-count-${comment.comment_id}">${comment.like_count} Likes</span>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                        postsContainer.appendChild(postElement);
                    });
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('An error occurred while fetching the posts.');
            }
        };

        xhr.onerror = function () {
            alert('A network error occurred.');
        };

        xhr.send();
    }

    function createPost() {
        const content = document.getElementById('post-content').value;
        const fileInput = document.getElementById('file-upload');
        const formData = new FormData();
        formData.append('main_text', content);
        if (fileInput.files.length > 0) {
            formData.append('media_file', fileInput.files[0]);
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'save_post.php', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    loadPosts();
                    document.getElementById('post-content').value = '';
                    fileInput.value = '';
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('An error occurred while submitting the post.');
            }
        };

        xhr.onerror = function () {
            alert('A network error occurred.');
        };

        xhr.send(formData);
    }

    window.toggleLike = function(postId) {
        const heartIcon = document.getElementById(`like-icon-${postId}`);
        const notification = document.getElementById(`notification-${postId}`);
        const likeCount = document.getElementById(`like-count-${postId}`);

        const action = heartIcon.classList.contains('liked') ? 'unlike' : 'like';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'like_posts.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    heartIcon.classList.toggle('liked');
                    if (heartIcon.classList.contains('liked')) {
                        heartIcon.style.color = 'red';
                        notification.textContent = 'Liked!';
                        likeCount.textContent = parseInt(likeCount.textContent) + 1 + ' Likes';
                    } else {
                        heartIcon.style.color = 'black';
                        notification.textContent = 'Unliked!';
                        likeCount.textContent = parseInt(likeCount.textContent) - 1 + ' Likes';
                    }
                    notification.classList.add('show');
                    setTimeout(() => {
                        notification.classList.remove('show');
                    }, 1500);
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('An error occurred while updating the like status.');
            }
        };
        xhr.send(`action=${action}&user_id=${userId}&post_id=${postId}`);
    };

    window.toggleCommentLike = function(commentId) {
        const heartIcon = document.getElementById(`comment-like-icon-${commentId}`);
        const likeCount = document.getElementById(`comment-like-count-${commentId}`);

        const action = heartIcon.classList.contains('liked') ? 'unlike' : 'like';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'like_posts.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    heartIcon.classList.toggle('liked');
                    if (heartIcon.classList.contains('liked')) {
                        heartIcon.style.color = 'red';
                        likeCount.textContent = parseInt(likeCount.textContent) + 1 + ' Likes';
                    } else {
                        heartIcon.style.color = 'black';
                        likeCount.textContent = parseInt(likeCount.textContent) - 1 + ' Likes';
                    }
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('An error occurred while updating the like status.');
            }
        };
        xhr.send(`action=${action}&user_id=${userId}&comment_id=${commentId}`);
    };

    window.toggleOptionsMenu = function(postId) {
        const optionsMenu = document.getElementById(`options-menu-${postId}`);
        optionsMenu.style.display = optionsMenu.style.display === 'block' ? 'none' : 'block';
    };

    window.editPost = function(postId) {
    const newContent = prompt("Edit your post:");
    if (newContent) {
        fetch('edit_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `post_id=${postId}&content=${encodeURIComponent(newContent)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadPosts();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred while editing the post.');
            console.error('Error:', error);
        });
    }
};
    window.deletePost = function(postId) {
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
            xhr.onerror = function () {
                alert('A network error occurred.');
            };
            xhr.send(`post_id=${postId}`);
        }
    };

    window.addComment = function(postId) {
        const content = document.getElementById(`comment-content-${postId}`).value;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'add_comment.php', true);
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
                alert('An error occurred while adding the comment.');
            }
        };
        xhr.onerror = function () {
            alert('A network error occurred.');
        };
        xhr.send(`post_id=${postId}&content=${encodeURIComponent(content)}`);
    };

    window.addFriend = function(friendId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'friend_action.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                location.reload();
            } else {
                alert('An error occurred while sending the friend request.');
            }
        };
        xhr.onerror = function () {
            alert('A network error occurred.');
        };
        xhr.send(`friend_id=${friendId}&action=add_friend`);
    };

    window.acceptFriend = function(friendId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'friend_action.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                location.reload();
            } else {
                alert('An error occurred while accepting the friend request.');
            }
        };
        xhr.onerror = function () {
            alert('A network error occurred.');
        };
        xhr.send(`friend_id=${friendId}&action=accept_friend`);
    };

    window.removeFriend = function(friendId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'friend_action.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                location.reload();
            } else {
                alert('An error occurred while removing the friend.');
            }
        };
        xhr.onerror = function () {
            alert('A network error occurred.');
        };
        xhr.send(`friend_id=${friendId}&action=remove_friend`);
    };

    loadPosts();
    window.createPost = createPost;
});
</script>
</body>
</html>