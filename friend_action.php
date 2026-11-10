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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $friend_id = intval($_POST['friend_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($friend_id === 0 || !in_array($action, ['add_friend', 'remove_friend', 'accept_friend'])) {
        echo "Invalid friend ID or action.";
        exit();
    }

    if ($action === 'add_friend') {
        // Check if the friend relationship already exists
        $stmt = $conn->prepare("SELECT * FROM friends WHERE user_id = ? AND friend_id = ?");
        $stmt->bind_param("ii", $user_id, $friend_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows === 0) {
            // Insert friend relationship into database for the current user
            $stmt = $conn->prepare("INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, 'pending')");
            $stmt->bind_param("ii", $user_id, $friend_id);
            $stmt->execute();
            $stmt->close();

            // Create notification
            $stmt = $conn->prepare("INSERT INTO notifications (user_id, content, status) VALUES (?, ?, 'unread')");
            $message = "User $user_id sent you a friend request.";
            $stmt->bind_param("is", $friend_id, $message);
            $stmt->execute();
            $stmt->close();
        }
    } elseif ($action === 'accept_friend') {
        // Update the relationship to 'accepted' for both users
        $stmt = $conn->prepare("UPDATE friends SET status = 'accepted' WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)");
        $stmt->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
        $stmt->execute();
        $stmt->close();

        // Create notification
        $stmt = $conn->prepare("INSERT INTO notifications (user_id, content, status) VALUES (?, ?, 'unread')");
        $message = "User $user_id accepted your friend request.";
        $stmt->bind_param("is", $friend_id, $message);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'remove_friend') {
        // Delete friend relationship from database for both users
        $stmt = $conn->prepare("DELETE FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)");
        $stmt->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
        $stmt->execute();
        $stmt->close();

        // Create notification
        $stmt = $conn->prepare("INSERT INTO notifications (user_id, content, status) VALUES (?, ?, 'unread')");
        $message = "User $user_id removed you as a friend.";
        $stmt->bind_param("is", $friend_id, $message);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: homepage.php");
exit();
?>