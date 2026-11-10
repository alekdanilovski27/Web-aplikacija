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
$group_name = $_POST['group_name'];
$description = $_POST['description'];
$status = $_POST['status'];
$privacy = $_POST['privacy'];
$profile_picture = '';

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    $profile_picture = 'uploads/' . basename($_FILES['profile_picture']['name']);
    move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
}

$sql = "INSERT INTO groups (user_id, name, description, profile_picture, status, privacy, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssi", $user_id, $group_name, $description, $profile_picture, $status, $privacy);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>