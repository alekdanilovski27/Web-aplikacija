<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password'], $_POST['birthdate'])) {
    // Get data from POST
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password
    $birthdate = $_POST['birthdate'];

    // Check if 'pp' is set, if not, set it to 'default.jpg'
    // $pp = isset($_POST['pp']) ? $_POST['pp'] : 'default.jpg';

    // Prepare the SQL statement
    $sql = "INSERT INTO users (firstname, lastname, email, password, birthdate, pp) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $firstname, $lastname, $email, $password, $birthdate, $pp);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to index.php after successful registration
        header("Location: index.php");
        exit();  // Ensure that no further code is executed
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

$conn->close();
?>
