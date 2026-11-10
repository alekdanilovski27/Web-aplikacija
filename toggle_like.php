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

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];

// Check if the user has already liked the post
$stmt = $conn->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User has already liked the post, so unlike it
    $stmt = $conn->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    echo json_encode(['success' => true, 'liked' => false]);
} else {
    // User has not liked the post, so like it
    $stmt = $conn->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    echo json_encode(['success' => true, 'liked' => true]);
}
?>