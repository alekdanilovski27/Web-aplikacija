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

$notification_id = intval($_POST['notification_id'] ?? 0);

if ($notification_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid notification ID']);
    exit();
}

$stmt = $conn->prepare("UPDATE notifications SET status = 'read' WHERE notification_id = ?");
$stmt->bind_param("i", $notification_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Notification marked as read']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to mark notification as read']);
}

$stmt->close();
$conn->close();
?>