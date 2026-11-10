<?php
// Start the session and handle the login logic
session_start();
$servername = "localhost";
$username = "root";
$password = "usbw";
$dbname = "korisnici";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessage = "";  // Variable for error messages

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
        // Check if the password matches
        if (password_verify($password, $hashed_password)) {
            // Successful login, store session and redirect
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $firstname . " " . $lastname;
            header("Location: homepage.php");
            exit();
        } else {
            // Incorrect password
            $errorMessage = "Incorrect password.";
        }
    } else {
        // No user found with that email
        $errorMessage = "No account found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="LogRegStyle.css">
    <title>Login/Register</title>
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="signup.php" method="POST">
                <h1>Create Account</h1>
                <input type="text" name="firstname" placeholder="First Name" required>
                <input type="text" name="lastname" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="date" name="birthdate" required>
                <button type="submit">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="index.php" method="POST">
                <h1>Sign In</h1>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <a href="forget.php">Forget Your Password?</a>
                <button type="submit">Sign In</button>

                <!-- Display the error message here if set -->
                <?php if (!empty($errorMessage)): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
                <?php endif; ?>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of the site's features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of the site's features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>
