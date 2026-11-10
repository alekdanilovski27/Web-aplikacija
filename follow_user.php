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
$follow_user_id = $_POST['user_id'];

// Check if already following
$sql = "SELECT * FROM followers WHERE user_id = ? AND follower_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $follow_user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Already following this user']);
} else {
    // Follow the user
    $sql = "INSERT INTO followers (user_id, follower_id, mutual, updated_at, status, reference) VALUES (?, ?, 0, NOW(), 'active', '')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $follow_user_id, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Followed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to follow user']);
    }
}

$stmt->close();
$conn->close();
?>