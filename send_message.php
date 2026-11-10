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

// Get data from POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $friend_id = isset($_POST['friend_id']) ? intval($_POST['friend_id']) : 0;
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

    // Check if all necessary data is provided
    if (!empty($message) && $friend_id > 0 && $user_id > 0) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, recipient_id, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $user_id, $friend_id, $message);

        if ($stmt->execute()) {
            // Fetch sender's name and profile picture
            $stmt = $conn->prepare("SELECT firstname, lastname, pp FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $sender = $result->fetch_assoc();
            echo json_encode([
                'status' => 'success',
                'message' => $message,
                'sender' => $sender['firstname'] . ' ' . $sender['lastname'],
                'sender_pp' => $sender['pp']
            ]);
        } else {
            echo json_encode(['status' => 'error', 'error' => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'error' => 'Invalid data provided.']);
    }
} else {
    echo json_encode(['status' => 'error', 'error' => 'Invalid request method.']);
}

$conn->close();
?>