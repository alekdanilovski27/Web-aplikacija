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
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$content = isset($_POST['main_text']) ? $conn->real_escape_string($_POST['main_text']) : '';
$image_path = '';
$reference = '';
$status = 'active';
$created_at = date('Y-m-d H:i:s');

if (!empty($_FILES['media_file']['name'])) {
    $target_dir = "uploads/";
    $image_name = basename($_FILES['media_file']['name']);
    $target_file = $target_dir . uniqid() . "_" . $image_name;

    if (move_uploaded_file($_FILES['media_file']['tmp_name'], $target_file)) {
        $image_path = $conn->real_escape_string($target_file);
    }
}

$sql = "INSERT INTO posts (user_id, content, reference, image, status, created_at) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("isssss", $user_id, $content, $reference, $image_path, $status, $created_at);
    if ($stmt->execute()) {
        $post_id = $stmt->insert_id;
        $stmt->close();

        // Fetch the newly created post
        $post_sql = "SELECT posts.*, users.firstname, users.lastname, users.pp AS profile_picture FROM posts JOIN users ON posts.user_id = users.id WHERE posts.post_id = ?";
        $post_stmt = $conn->prepare($post_sql);
        if ($post_stmt) {
            $post_stmt->bind_param("i", $post_id);
            $post_stmt->execute();
            $post_result = $post_stmt->get_result();
            $post = $post_result->fetch_assoc();
            $post_stmt->close();

            echo json_encode(['success' => true, 'post' => $post]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch post: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create post: ' . $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
}

$conn->close();
?>