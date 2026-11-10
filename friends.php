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

// Fetch all users
$users_sql = "SELECT id, firstname, lastname, pp FROM users WHERE id != $user_id"; // Exclude the current user
$users_result = $conn->query($users_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
    <link rel="stylesheet" href="friends.css">
</head>
<body>
<?php
// Include navbar.php
include('navbar.php');
?>
<div class="friends-container">
    <h1>Friends</h1>
    <div class="friends-grid">
        <?php while ($user_row = $users_result->fetch_assoc()): ?>
            <div class="friend-card">
                <div class="profile-picture">
                    <?php if (!empty($user_row['pp'])): ?>
                        <img src="<?php echo htmlspecialchars($user_row['pp']); ?>" alt="Profile Picture">
                    <?php else: ?>
                        <img src="default-profile.png" alt="Default Profile Picture">
                    <?php endif; ?>
                </div>
                <div class="user-name">
                    <span><?php echo htmlspecialchars($user_row['firstname'] . ' ' . $user_row['lastname']); ?></span>
                </div>
                <div class="friend-actions">
                    <button onclick="messageUser(<?php echo $user_row['id']; ?>)">Message</button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="follow_user.js"></script>
<script>
function messageUser(userId) {
    window.location.href = 'messages.php?friend_id=' + userId;
}
</script>
</body>
</html>