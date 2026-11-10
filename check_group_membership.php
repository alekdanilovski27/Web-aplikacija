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
    echo json_encode(['is_member' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];
$group_id = intval($_GET['group_id'] ?? 0);

if ($group_id === 0) {
    echo json_encode(['is_member' => false]);
    exit();
}

// Check if the user is a member of the group
$stmt = $conn->prepare("SELECT 1 FROM group_members WHERE group_id = ? AND user_id = ?");
$stmt->bind_param("ii", $group_id, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['is_member' => true]);
} else {
    echo json_encode(['is_member' => false]);
}

$stmt->close();
$conn->close();
?>
