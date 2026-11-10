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

if (isset($_GET['friend_id']) && isset($_SESSION['user_id'])) {
    $friend_id = intval($_GET['friend_id']);
    $user_id = intval($_SESSION['user_id']);

    // Get messages between the user and the selected friend
    $stmt = $conn->prepare("
        SELECT m.sender_id, m.recipient_id, m.content, m.created_at, u.firstname, u.lastname, u.pp 
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE (m.sender_id = ? AND m.recipient_id = ?) 
           OR (m.sender_id = ? AND m.recipient_id = ?) 
        ORDER BY m.created_at ASC
    ");
    $stmt->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    $stmt->close();
    $conn->close();

    // Output messages in HTML
    foreach ($messages as $message) {
        $isUser = $message['sender_id'] == $user_id;
        $alignment = $isUser ? "right" : "left";
        $background = $isUser ? "#007bff" : "#f1f1f1";
        $color = $isUser ? "white" : "black";

        echo "<div style='text-align: {$alignment}; margin: 10px 0;'>
                <div style='display: flex; align-items: center; justify-content: {$alignment};'>
                    <img src='{$message['pp']}' alt='Profile Picture' style='width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;'>
                    <strong>{$message['firstname']} {$message['lastname']}</strong>
                </div>
                <div style='display: flex; justify-content: {$alignment};'>
                    <span style='display: inline-block; max-width: 70%; background-color: {$background}; color: {$color}; padding: 10px; border-radius: 10px; margin-top: 5px;'>
                        {$message['content']}
                    </span>
                </div>
              </div>";
    }
} else {
    echo "<p>No messages available.</p>";
}
?>