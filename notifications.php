<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

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

$user_id = $_SESSION['user_id'];

// Fetch notifications for the logged-in user
$stmt = $conn->prepare("
    SELECT n.notification_id, n.content, n.created_at, u.firstname, u.lastname, n.status 
    FROM notifications n 
    JOIN users u ON n.user_id = u.id 
    WHERE n.user_id = ? 
    ORDER BY n.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="notifications.css">
</head>
<body>
<?php
// Include navbar.php
include('navbar.php');
?>
<div class="container mt-5">
    <h1 class="mb-4">Notifications</h1>
    <div id="notifications" class="list-group">
        <?php if (empty($notifications)): ?>
            <p class="list-group-item">No notifications found.</p>
        <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="list-group-item <?php echo $notification['status'] === 'unread' ? 'list-group-item-warning' : ''; ?>">
                    <strong><?php echo htmlspecialchars($notification['firstname'] . ' ' . $notification['lastname']); ?>:</strong>
                    <?php echo htmlspecialchars($notification['content']); ?>
                    <br>
                    <small><?php echo htmlspecialchars($notification['created_at']); ?></small>
                    <?php if ($notification['status'] === 'unread'): ?>
                        <button class="btn btn-sm btn-primary float-right" onclick="markAsRead(<?php echo $notification['notification_id']; ?>)">Mark as read</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.markAsRead = function (notificationId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'notifications.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('An error occurred while marking the notification as read.');
            }
        };
        xhr.send(`action=markAsRead&notification_id=${notificationId}`);
    };
});
</script>
</body>
</html>

<?php
// Handle marking notification as read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'markAsRead') {
    $notification_id = intval($_POST['notification_id'] ?? 0);

    if ($notification_id === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid notification ID']);
        exit();
    }

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
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
}
?>