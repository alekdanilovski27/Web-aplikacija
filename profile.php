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
$errorMessage = "";
$successMessage = "";

// Fetch user details
$stmt = $conn->prepare("SELECT firstname, lastname, pp FROM users WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($firstname, $lastname, $profilePicture);
    $stmt->fetch();
    $stmt->close();
} else {
    $errorMessage = 'Failed to prepare statement: ' . $conn->error;
}

// Fetch post count
$stmt = $conn->prepare("SELECT COUNT(*) AS post_count FROM posts WHERE user_id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($postCount);
    $stmt->fetch();
    $stmt->close();
} else {
    $errorMessage = 'Failed to prepare statement: ' . $conn->error;
}

// Fetch posts
$stmt = $conn->prepare("SELECT content, image, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $postsResult = $stmt->get_result();
    $stmt->close();
} else {
    $errorMessage = 'Failed to prepare statement: ' . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="profile.css">
    <title>Profile</title>
</head>
<body>
<?php
// Include navbar.php
include('navbar.php');
?>
<div class="container mt-4">
    <div class="text-center">
        <div class="profile-picture mb-3">
            <?php if (!empty($profilePicture)): ?>
                <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" class="rounded-circle" width="150" height="150">
            <?php else: ?>
                <img src="default-profile.png" alt="Default Profile Picture" class="rounded-circle" width="150" height="150">
            <?php endif; ?>
        </div>
        <div class="user-name">
            <h1><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></h1>
        </div>
        <div class="statistics mt-3">
            <div>
                <strong>Posts:</strong> <?php echo htmlspecialchars($postCount); ?>
            </div>
        </div>
    </div>

    <div class="posts-container mt-4">
        <?php while ($post = $postsResult->fetch_assoc()): ?>
            <div class="post mb-4 p-3 bg-white border rounded shadow-sm">
                <?php if (!empty($post['image'])): ?>
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" class="img-fluid rounded mb-3">
                <?php endif; ?>
                <?php if (!empty($post['content'])): ?>
                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                <?php endif; ?>
                <small class="text-muted">Posted on: <?php echo htmlspecialchars($post['created_at']); ?></small>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>