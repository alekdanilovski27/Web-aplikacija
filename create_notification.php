<?php
function createNotification($conn, $user_id, $target_user_id, $message) {
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, content, status) VALUES (?, ?, 'unread')");
    $stmt->bind_param("is", $target_user_id, $message);
    $stmt->execute();
    $stmt->close();
}
?>