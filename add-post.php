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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = '';
    $image = '';
    $status = 'active';
    $created_at = date('Y-m-d');
    $uploadFile = '';

    // Handle text post
    if (!empty($_POST['main_text'])) {
        $content = $_POST['main_text'];
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . uniqid() . '.txt';
        if (file_put_contents($uploadFile, $content) === false) {
            $errorMessage = 'Failed to save text file.';
        }
    }

    // Handle file upload
    if (!empty($_FILES['media_file']['name'])) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['media_file']['name']);
        $fileExtension = pathinfo($uploadFile, PATHINFO_EXTENSION);

        if (move_uploaded_file($_FILES['media_file']['tmp_name'], $uploadFile)) {
            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $image = $uploadFile;
            } elseif ($fileExtension === 'txt') {
                $content = file_get_contents($uploadFile);
            }
        } else {
            $errorMessage = 'Failed to upload file.';
        }
    }

    // Insert new post into database
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content, reference, image, status, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("isssss", $user_id, $content, $uploadFile, $image, $status, $created_at);
        if ($stmt->execute()) {
            $successMessage = 'Post created successfully.';
        } else {
            $errorMessage = 'Failed to create post: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $errorMessage = 'Failed to prepare statement: ' . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="add-post.css">
</head>
<body>
<?php
// Include navbar.php
include('navbar.php');
?>
<main>
    <div class="icon-options">
        <button class="icon-button" id="text-icon">
            <img src="type.png" alt="Text Icon">
        </button>
        <button class="icon-button" id="media-icon">
            <img src="images.png" alt="Media Icon">
        </button>
    </div>

    <form method="POST" action="save_post.php" enctype="multipart/form-data">
        <h2>Create a Post</h2>
        
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message">Error: <?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <div class="success-message">Success: <?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>

        <div id="main-text-box" class="form-group hidden">
            <label for="main-text">Write Your Post:</label>
            <textarea id="main-text" name="main_text" rows="8" placeholder="Start writing..."></textarea>
        </div>

        <div id="media-upload" class="form-group hidden">
            <label for="media-file">Upload Media:</label>
            <input type="file" id="media-file" name="media_file" accept="image/*,video/*">
        </div>

        <div id="description-field" class="form-group hidden">
            <label for="post-description">Description:</label>
            <textarea id="post-description" name="post_description" rows="5" placeholder="Add a description..."></textarea>
        </div>

        <button type="submit"><img src="send-horizontal.png"></button>
    </form>
</main>

<script src="add-post.js"></script>
</body>
</html>