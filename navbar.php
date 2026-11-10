<?php
// Поставување на податоците за врска со базата
$servername = "localhost";
$username = "root";
$password = "usbw";
$dbname = "korisnici";

// Креирање на конекцијата
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка на конекцијата
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Почетување на сесијата и проверка дали корисникот е најавен
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Пренасочување на не-најавени корисници
    exit();
}

// Влечење на податоците на корисникот од базата
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT firstname, lastname FROM users WHERE id = $user_id";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();
$user_name = $user['firstname'] . ' ' . $user['lastname']; // Создавање на целосно име за корисникот

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="navbar.css"> <!-- Вклучување на CSS за navbar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"> <!-- Вклучување на FontAwesome -->
</head>
<body>
<nav class="navbar">
    <div class="logo">
        <a href="homepage.php"><img src="twitter.png" alt="Logo" class="icon"></a>
    </div>
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="search-input" placeholder="Search">
    </div>
    <div class="right-icons">
        <a href="messages.php"><img src="message-square.png" alt="messages" class="icon"></a>

        <!-- Notification Dropdown -->
      <!-- Notification Dropdown -->
<div class="dropdown">
    <a href="notifications.php"><img src="bell.png" alt="notifications" class="icon" id="notification-btn"></a>
    </a>
</div>

        <!-- User Profile Dropdown (keeps the previous structure) -->
        <div class="dropdown">
            <img src="square-user-round.png" id="dropbtn" class="icon">
            <div class="dropdown-content">
                <!-- Испишувањето на податоците за корисникот -->
                <div class="image-text">
                    <a href="profile.php"><img src="user.png" alt="Profile"><?php echo $user_name; ?></a> <!-- Испишување на целосното име -->
                    <a href="editprofile.php"><img src="user-round-pen.png" alt="Profile">Edit Profile</a>
                    <a href="about-us.php"><img src="info.png" alt="Profile">About US</a>
                    <a href="logout.php"><img src="log-out.png" alt="Profile">Logout</a>
                </div>
            </div>
        </div>
    </div>
</nav>

</body>
</html>
