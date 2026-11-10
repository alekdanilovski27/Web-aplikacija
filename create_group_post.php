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
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$group_id = intval($_POST['group_id'] ?? 0);
$content = $_POST['content'] ?? '';

if ($group_id === 0 || empty($content)) {
    echo "Invalid group ID or content.";
    exit();
}

// Insert post into database
$stmt = $conn->prepare("INSERT INTO posts (user_id, group_id, content, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $user_id, $group_id, $content);
$stmt->execute();
$stmt->close();

header("Location: view_group.php?group_id=" . $group_id);
exit();
?>