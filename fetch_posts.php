<?php
$servername = "localhost";
$username = "root";
$password = "usbw";
$dbname = "korisnici";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Fetch posts sorted by newest to oldest
$sql = "SELECT posts.*, users.firstname, users.lastname, users.pp AS profile_picture 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);

$posts = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $post_id = $row['post_id'];
        
        // Fetch comments for the post
        $comments_sql = "SELECT comments.*, users.firstname, users.lastname, users.pp AS profile_picture 
                         FROM comments 
                         JOIN users ON comments.user_id = users.id 
                         WHERE comments.post_id = ? 
                         ORDER BY comments.created_at ASC";
        $comments_stmt = $conn->prepare($comments_sql);
        $comments_stmt->bind_param("i", $post_id);
        $comments_stmt->execute();
        $comments_result = $comments_stmt->get_result();
        
        $comments = [];
        while ($comment_row = $comments_result->fetch_assoc()) {
            $comments[] = $comment_row;
        }
        
        $row['comments'] = $comments;
        $posts[] = $row;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No posts found']);
    $conn->close();
    exit();
}

echo json_encode(['success' => true, 'posts' => $posts]);

$conn->close();
?>