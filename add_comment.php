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
    $content = $_POST['content'] ?? '';

    if ($user_id === 0 || $post_id === 0 || empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO comments (user_id, post_id, content, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("iis", $user_id, $post_id, $content);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Comment added.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add comment.']);
    }
    $stmt->close();
}

$conn->close();
?>