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

// Fetch all groups
$groups_sql = "SELECT groups.*, users.firstname, users.lastname FROM groups JOIN users ON groups.user_id = users.id";
$groups_result = $conn->query($groups_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groups</title>
    <link rel="stylesheet" href="groups.css">
</head>
<body>
<?php
// Include navbar.php
include('navbar.php');
?>
<div class="container">
    <h1>Groups</h1>
    <div class="create-group">
        <h2>Create a New Group</h2>
        <form id="create-group-form" enctype="multipart/form-data">
            <input type="text" name="group_name" placeholder="Group Name" required>
            <textarea name="description" placeholder="Group Description" required></textarea>
            <input type="file" name="profile_picture" accept="image/*">
            <select name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <select name="privacy" required>
                <option value="0">Public</option>
                <option value="1">Private</option>
            </select>
            <button type="submit">Create Group</button>
        </form>
        <div id="create-group-message" class="message"></div>
    </div>

    <div class="groups-list">
        <h2>Join a Group</h2>
        <?php while ($group = $groups_result->fetch_assoc()): ?>
            <div class="group-card">
                <div class="group-profile-picture">
                    <?php if (!empty($group['profile_picture'])): ?>
                        <img src="<?php echo htmlspecialchars($group['profile_picture']); ?>" alt="Group Profile Picture">
                    <?php else: ?>
                        <img src="default-group.png" alt="Default Group Picture">
                    <?php endif; ?>
                </div>
                <div class="group-details">
                    <h3><?php echo htmlspecialchars($group['name']); ?></h3>
                    <p><?php echo htmlspecialchars($group['description']); ?></p>
                    <small>Created by: <?php echo htmlspecialchars($group['firstname'] . ' ' . $group['lastname']); ?></small>
                    <form action="join_group.php" method="POST" class="join-group-form">
                        <input type="hidden" name="group_id" value="<?php echo $group['group_id']; ?>">
                        <button type="submit">Join Group</button>
                        <div class="message"></div>
                    </form>
                    <button onclick="viewGroup(<?php echo $group['group_id']; ?>)">View Group</button>
                    <div id="message-<?php echo $group['group_id']; ?>" class="message"></div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<script src="join_group.js"></script>
<script>
document.getElementById('create-group-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'create_groups.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            const messageDiv = document.getElementById('create-group-message');
            if (response.success) {
                messageDiv.innerText = 'You successfully created a group.';
                messageDiv.style.color = 'green';
                setTimeout(() => {
                    location.reload();
                }, 2000); // Refresh the page after 2 seconds
            } else {
                messageDiv.innerText = 'Failed to create group: ' + response.message;
                messageDiv.style.color = 'red';
            }
        } else {
            alert('An error occurred while creating the group.');
        }
    };
    xhr.send(formData);
});

function viewGroup(groupId) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `check_group_membership.php?group_id=${groupId}`, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.is_member) {
                window.location.href = `view_group.php?group_id=${groupId}`;
            } else {
                document.getElementById(`message-${groupId}`).innerText = 'You are not a member of this group.';
            }
        } else {
            alert('An error occurred while checking group membership.');
        }
    };
    xhr.send();
}
</script>
</body>
</html>