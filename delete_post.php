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
    $user_id = $_SESSION['user_id'] ?? 0;
    $post_id = intval($_POST['post_id'] ?? 0);

    if ($user_id === 0 || $post_id === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $post_id, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Post deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete post.']);
    }
    $stmt->close();
}

$conn->close();
?>