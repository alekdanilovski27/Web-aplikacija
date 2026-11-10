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
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = intval($_POST['post_id'] ?? 0);
$content = trim($_POST['content'] ?? '');

if ($post_id === 0 || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Invalid post ID or content']);
    exit();
}

// Check if the post belongs to the user
$stmt = $conn->prepare("SELECT 1 FROM posts WHERE post_id = ? AND user_id = ? LIMIT 1");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
    exit();
}
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    // Update the post content
    $stmt = $conn->prepare("UPDATE posts SET content = ?, updated_at = NOW() WHERE post_id = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare update statement: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("si", $content, $post_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Post updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update post: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    $stmt->close();
    echo json_encode(['success' => false, 'message' => 'Post not found or you do not have permission to edit this post']);
}

$conn->close();
?>