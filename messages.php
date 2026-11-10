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

// Fetch friends list
$friends = [];
$sql = "SELECT id, firstname, lastname, pp FROM users"; // Промени го според базата
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $friends[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="messages.css">
    <title>Chat</title>
</head>
<body>
<?php
// Include navbar.php
include('navbar.php');
?>
<div class="container">
    <div class="chats">
        <label>Chats</label>
        <?php foreach ($friends as $friend): ?>
            <div class="friend" onclick="openChat(<?php echo $friend['id']; ?>)">
                <img src="<?php echo $friend['pp']; ?>" alt="Profile Picture">
                <span><?php echo $friend['firstname'] . ' ' . $friend['lastname']; ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="chat-window">
        <div class="messages" id="chatMessages">
            <p>Select a friend to start chatting.</p>
        </div>
        <div class="message-input">
            <textarea id="messageInput" placeholder="Type your message..."></textarea>
            <button id="sendButton">Send</button>
        </div>
    </div>
</div>
<script>
    let currentFriendId = null;
    let fetchMessagesInterval = null;

    function openChat(friendId) {
        currentFriendId = friendId;
        // Load chat messages dynamically via AJAX
        fetch(`get_messages.php?friend_id=${friendId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('chatMessages').innerHTML = data;
            });

        // Start polling for new messages
        if (fetchMessagesInterval) {
            clearInterval(fetchMessagesInterval);
        }
        fetchMessagesInterval = setInterval(fetchMessages, 3000);
    }

    function fetchMessages() {
        if (currentFriendId !== null) {
            fetch(`get_messages.php?friend_id=${currentFriendId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('chatMessages').innerHTML = data;
                });
        }
    }

    function sendMessage() {
        const message = document.getElementById('messageInput').value;
        if (message.trim() !== '' && currentFriendId !== null) {
            console.log('Sending message:', message); // Debugging line
            // Send message to server via AJAX
            fetch('send_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `message=${encodeURIComponent(message)}&friend_id=${currentFriendId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const chatMessages = document.getElementById('chatMessages');
                        chatMessages.innerHTML += `
                            <div style='text-align: right; margin: 10px 0;'>
                                <div style='display: flex; align-items: center; justify-content: flex-end;'>
                                    <img src="${data.sender_pp}" alt="Profile Picture" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">
                                    <strong>${data.sender}</strong>
                                </div>
                                <div style='display: flex; justify-content: flex-end;'>
                                    <span style='display: inline-block; max-width: 70%; background-color: #007bff; color: white; padding: 10px; border-radius: 10px; margin-top: 5px;'>
                                        ${data.message}
                                    </span>
                                </div>
                            </div>`;
                        document.getElementById('messageInput').value = '';
                        chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to the bottom
                    } else {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error sending message:', error); // Debugging line
                });
        }
    }

    // Add event listener for Enter key
    document.getElementById('messageInput').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent newline in textarea
            sendMessage();
        }
    });

    // Add event listener for Send button
    document.getElementById('sendButton').addEventListener('click', sendMessage);
</script>
</body>
</html>