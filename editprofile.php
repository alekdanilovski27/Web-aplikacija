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

// Fetch current user data
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $conn->prepare("SELECT firstname, lastname, email, pp FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($firstname, $lastname, $email, $profilePicture);
    $stmt->fetch();
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $profilePicture = $_FILES['profilePicture'];
    $oldProfilePicture = $_POST['old_pp'];

    // Handle file upload
    if ($profilePicture['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($profilePicture['name']);
        if (move_uploaded_file($profilePicture['tmp_name'], $uploadFile)) {
            $profilePicturePath = $uploadFile;
        } else {
            $errorMessage = 'Failed to upload profile picture.';
        }
    } else {
        $profilePicturePath = $oldProfilePicture;
    }

    // Update user information
    $sql = "UPDATE users SET firstname = ?, lastname = ?, email = ?";
    $params = [$firstName, $lastName, $email];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password = ?";
        $params[] = $hashedPassword;
    }

    if ($profilePicturePath) {
        $sql .= ", pp = ?";
        $params[] = $profilePicturePath;
    }

    $sql .= " WHERE id = ?";
    $params[] = $user_id;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    if ($stmt->execute()) {
        $successMessage = 'Profile updated successfully.';
    } else {
        $errorMessage = 'Failed to update profile.';
    }

    $_SESSION['errorMessage'] = $errorMessage;
    $_SESSION['successMessage'] = $successMessage;

    header('Location: editprofile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="editprofile.css">
    <title>Edit Profile</title>
</head>
<body>
<?php
// Include navbar.php
include('navbar.php');
?>
<div class="container mt-5">
    <h1 class="text-center">Edit Profile</h1>
    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger text-center"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success text-center"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <form method="POST" action="editprofile.php" enctype="multipart/form-data">
        <div class="form-group text-center">
            <label for="profilePicture">Profile Picture</label>
            <div class="profile-picture mb-3">
                <?php if (!empty($profilePicture)): ?>
                    <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" class="rounded-circle" width="150" height="150">
                <?php else: ?>
                    <img src="default-profile.png" alt="Default Profile Picture" class="rounded-circle" width="150" height="150">
                <?php endif; ?>
            </div>
            <input type="file" id="profilePicture" name="profilePicture" class="form-control-file">
            <input type="hidden" name="old_pp" value="<?php echo htmlspecialchars($profilePicture); ?>">
        </div>
        <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstname); ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastname); ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label for="password">Password (leave blank to keep current password)</label>
            <input type="password" id="password" name="password" placeholder="Password" class="form-control">
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>