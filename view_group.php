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
$group_id = intval($_GET['group_id'] ?? 0);

if ($group_id === 0) {
    echo "Invalid group ID.";
    exit();
}

// Fetch group details
$stmt = $conn->prepare("SELECT groups.*, users.firstname, users.lastname FROM groups JOIN users ON groups.user_id = users.id WHERE groups.group_id = ?");
$stmt->bind_param("i", $group_id);
$stmt->execute();
$group_result = $stmt->get_result();
$group = $group_result->fetch_assoc();
$stmt->close();

if (!$group) {
    echo "Group not found.";
    exit();
}

// Fetch group members
$members_sql = "SELECT users.firstname, users.lastname FROM group_members JOIN users ON group_members.user_id = users.id WHERE group_members.group_id = ?";
$stmt = $conn->prepare($members_sql);
$stmt->bind_param("i", $group_id);
$stmt->execute();
$members_result = $stmt->get_result();
$members = $members_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch group posts
$posts_sql = "SELECT posts.*, users.firstname, users.lastname FROM posts JOIN users ON posts.user_id = users.id WHERE posts.group_id = ? ORDER BY posts.created_at DESC";
$stmt = $conn->prepare($posts_sql);
$stmt->bind_param("i", $group_id);
$stmt->execute();
$posts_result = $stmt->get_result();
$posts = $posts_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Group</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="view-group.css">
</head>
<body>
<?php
// Include navbar.php
include('navbar.php');
?>
<div class="container mt-4">
    <h1 class="text-center"><?php echo htmlspecialchars($group['name']); ?></h1>
    <div class="group-details text-center mt-4">
        <div class="group-profile-picture mb-3">
            <?php if (!empty($group['profile_picture'])): ?>
                <img src="<?php echo htmlspecialchars($group['profile_picture']); ?>" alt="Group Profile Picture" class="rounded-circle" width="100" height="100">
            <?php else: ?>
                <img src="default-group.png" alt="Default Group Picture" class="rounded-circle" width="100" height="100">
            <?php endif; ?>
        </div>
        <p><?php echo htmlspecialchars($group['description']); ?></p>
        <small>Created by: <?php echo htmlspecialchars($group['firstname'] . ' ' . $group['lastname']); ?></small>
        <p>Members: <?php echo count($members); ?></p>
    </div>

    <div class="group-members mt-4">
        <h2>Members</h2>
        <ul class="list-group">
            <?php foreach ($members as $member): ?>
                <li class="list-group-item"><?php echo htmlspecialchars($member['firstname'] . ' ' . $member['lastname']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="group-posts mt-4">
        <h2>Posts</h2>
        <form action="create_group_post.php" method="POST" class="mb-4">
            <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
            <textarea name="content" class="form-control mb-2" placeholder="Write something..." required></textarea>
            <button type="submit" class="btn btn-primary">Post</button>
        </form>
        <ul class="list-group">
            <?php foreach ($posts as $post): ?>
                <li class="list-group-item">
                    <strong><?php echo htmlspecialchars($post['firstname'] . ' ' . $post['lastname']); ?>:</strong>
                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                    <small><?php echo htmlspecialchars($post['created_at']); ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>