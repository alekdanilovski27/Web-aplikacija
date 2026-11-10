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
$group_id = $_GET['group_id'];

// Check if the user is a member of the group
$member_check_sql = "SELECT * FROM group_members WHERE group_id = ? AND user_id = ?";
$member_check_stmt = $conn->prepare($member_check_sql);
$member_check_stmt->bind_param("ii", $group_id, $user_id);
$member_check_stmt->execute();
$member_check_result = $member_check_stmt->get_result();

if ($member_check_result->num_rows == 0) {
    echo "You are not a member of this group.";
    exit();
}

// Fetch group details
$group_sql = "SELECT groups.*, users.firstname, users.lastname FROM groups JOIN users ON groups.user_id = users.id WHERE groups.group_id = ?";
$group_stmt = $conn->prepare($group_sql);
$group_stmt->bind_param("i", $group_id);
$group_stmt->execute();
$group_result = $group_stmt->get_result();
$group = $group_result->fetch_assoc();

$group_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($group['name']); ?></title>
    <link rel="stylesheet" href="show_group.css">
</head>
<body>
<?php
// Include navbar.php
include('navbar.php');
?>
<div class="container">
    <div class="group-header">
        <div class="group-profile-picture">
            <?php if (!empty($group['profile_picture'])): ?>
                <img src="<?php echo htmlspecialchars($group['profile_picture']); ?>" alt="Group Profile Picture">
            <?php else: ?>
                <img src="default-group.png" alt="Default Group Picture">
            <?php endif; ?>
        </div>
        <div class="group-info">
            <h1><?php echo htmlspecialchars($group['name']); ?></h1>
            <p><?php echo htmlspecialchars($group['description']); ?></p>
            <small>Created by: <?php echo htmlspecialchars($group['firstname'] . ' ' . $group['lastname']); ?></small>
            <small>Status: <?php echo htmlspecialchars($group['status']); ?></small>
            <small>Privacy: <?php echo $group['privacy'] == 0 ? 'Public' : 'Private'; ?></small>
        </div>
    </div>

    <div class="group-content">
        <div class="group-posts">
            <h2>Posts</h2>
            <form id="create-post-form" action="create_post.php" method="POST" enctype="multipart/form-data">
                <textarea name="content" placeholder="What's on your mind?" required></textarea>
                <input type="file" name="image" accept="image/*">
                <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                <button type="submit">Post</button>
            </form>
            <div id="posts-container"></div>
        </div>
    </div>
</div>
<script src="show_group.js"></script>
</body>
</html>