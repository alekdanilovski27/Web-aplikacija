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

// Handle request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? ''; // 'like' or 'unlike'
    $user_id = intval($_POST['user_id'] ?? 0); // User ID
    $post_id = intval($_POST['post_id'] ?? 0); // Post ID
    $comment_id = intval($_POST['comment_id'] ?? 0); // Comment ID

    if ($user_id === 0 || ($post_id === 0 && $comment_id === 0)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid user ID, post ID, or comment ID.']);
        exit();
    }

    if ($action === 'like') {
        if ($post_id !== 0) {
            $stmt = $conn->prepare("SELECT 1 FROM likes WHERE user_id = ? AND post_id = ? LIMIT 1");
            $stmt->bind_param("ii", $user_id, $post_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows === 0) {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $user_id, $post_id);
                if ($stmt->execute()) {
                    $stmt = $conn->prepare("UPDATE posts SET like_count = like_count + 1 WHERE post_id = ?");
                    $stmt->bind_param("i", $post_id);
                    $stmt->execute();
                    
                    // Send notification
                    $stmt = $conn->prepare("SELECT user_id FROM posts WHERE post_id = ?");
                    $stmt->bind_param("i", $post_id);
                    $stmt->execute();
                    $stmt->bind_result($post_user_id);
                    $stmt->fetch();
                    $stmt->close();

                    $content = "User $user_id liked your post.";
                    $stmt = $conn->prepare("INSERT INTO notifications (user_id, post_id, content, type, status) VALUES (?, ?, ?, 'post_liked', 'unread')");
                    $stmt->bind_param("iis", $post_user_id, $post_id, $content);
                    $stmt->execute();

                    echo json_encode(['status' => 'success', 'message' => 'Post liked.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to like post.']);
                }
                $stmt->close();
            } else {
                $stmt->close();
                echo json_encode(['status' => 'error', 'message' => 'Post already liked.']);
            }
        } elseif ($comment_id !== 0) {
            $stmt = $conn->prepare("SELECT 1 FROM comment_likes WHERE user_id = ? AND comment_id = ? LIMIT 1");
            $stmt->bind_param("ii", $user_id, $comment_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows === 0) {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO comment_likes (user_id, comment_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $user_id, $comment_id);
                if ($stmt->execute()) {
                    $stmt = $conn->prepare("UPDATE comments SET like_count = like_count + 1 WHERE comment_id = ?");
                    $stmt->bind_param("i", $comment_id);
                    $stmt->execute();
                    
                    // Send notification
                    $stmt = $conn->prepare("SELECT user_id FROM comments WHERE comment_id = ?");
                    $stmt->bind_param("i", $comment_id);
                    $stmt->execute();
                    $stmt->bind_result($comment_user_id);
                    $stmt->fetch();
                    $stmt->close();

                    $content = "User $user_id liked your comment.";
                    $stmt = $conn->prepare("INSERT INTO notifications (user_id, comment_id, content, type, status) VALUES (?, ?, ?, 'comment_liked', 'unread')");
                    $stmt->bind_param("iis", $comment_user_id, $comment_id, $content);
                    $stmt->execute();

                    echo json_encode(['status' => 'success', 'message' => 'Comment liked.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to like comment.']);
                }
                $stmt->close();
            } else {
                $stmt->close();
                echo json_encode(['status' => 'error', 'message' => 'Comment already liked.']);
            }
        }
    } elseif ($action === 'unlike') {
        if ($post_id !== 0) {
            $stmt = $conn->prepare("SELECT 1 FROM likes WHERE user_id = ? AND post_id = ? LIMIT 1");
            $stmt->bind_param("ii", $user_id, $post_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->close();
                $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
                $stmt->bind_param("ii", $user_id, $post_id);
                if ($stmt->execute()) {
                    $stmt = $conn->prepare("UPDATE posts SET like_count = like_count - 1 WHERE post_id = ?");
                    $stmt->bind_param("i", $post_id);
                    $stmt->execute();
                    echo json_encode(['status' => 'success', 'message' => 'Post unliked.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to unlike post.']);
                }
                $stmt->close();
            } else {
                $stmt->close();
                echo json_encode(['status' => 'error', 'message' => 'Post not liked.']);
            }
        } elseif ($comment_id !== 0) {
            $stmt = $conn->prepare("SELECT 1 FROM comment_likes WHERE user_id = ? AND comment_id = ? LIMIT 1");
            $stmt->bind_param("ii", $user_id, $comment_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->close();
                $stmt = $conn->prepare("DELETE FROM comment_likes WHERE user_id = ? AND comment_id = ?");
                $stmt->bind_param("ii", $user_id, $comment_id);
                if ($stmt->execute()) {
                    $stmt = $conn->prepare("UPDATE comments SET like_count = like_count - 1 WHERE comment_id = ?");
                    $stmt->bind_param("i", $comment_id);
                    $stmt->execute();
                    echo json_encode(['status' => 'success', 'message' => 'Comment unliked.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to unlike comment.']);
                }
                $stmt->close();
            } else {
                $stmt->close();
                echo json_encode(['status' => 'error', 'message' => 'Comment not liked.']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
    }
}

$conn->close();
?>