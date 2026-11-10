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

$group_id = $_GET['group_id'];

// Fetch group posts
$posts_sql = "SELECT posts.*, users.firstname, users.lastname, users.pp FROM posts JOIN users ON posts.user_id = users.id WHERE posts.group_id = ? ORDER BY posts.created_at DESC";
$posts_stmt = $conn->prepare($posts_sql);
$posts_stmt->bind_param("i", $group_id);
$posts_stmt->execute();
$posts_result = $posts_stmt->get_result();

$posts = [];
while ($post = $posts_result->fetch_assoc()) {
    $posts[] = $post;
}

$posts_stmt->close();
$conn->close();

echo json_encode($posts);
?>