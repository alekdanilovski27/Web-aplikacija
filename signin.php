<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "usbw";
$dbname = "korisnici";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessage = "";  // Initialize variable to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if email exists in the database
    $sql = "SELECT id, firstname, lastname, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $firstname, $lastname, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Successful login: store session variables and redirect
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $firstname . " " . $lastname;
            header("Location: homepage.php");
            exit();
        } else {
            // Incorrect password
            $errorMessage = "Incorrect password.";
        }
    } else {
        // No account found with the provided email
        $errorMessage = "No account found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>
