<?php
// Подесување на врска со базата на податоци
$servername = "localhost";
$username = "root";
$password = "usbw"; // Проверете ги вашите податоци за конекција
$dbname = "korisnici";

// Флаг за проверка на грешка
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Проверка дали се внесени двата password полиња
    if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Проверка дали лозинките се совпаднат
        if ($new_password == $confirm_password) {
            // Создавање на врска со базата
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Проверање дали има грешка во врската
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Хеширање на новата лозинка
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // SQL upит за ажурирање на лозинката за сите корисници (нема email проверка)
            $sql = "UPDATE users SET password = ?"; // Без специфичен email WHERE
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $hashed_password);
            $stmt->execute();

            // Проверка дали е извршена промената
            if ($stmt->affected_rows > 0) {
                // Успешно променета лозинка
                header("Location: index.php");
                exit(); // Прекинување на извршувањето по пренасочувањето
            } else {
                // Ако не се промени било што
                $error_message = "Failed to update the password. Please try again.";
            }

            // Затворање на подготовката на извршувањето
            $stmt->close();
            // Затворање на врската со базата
            $conn->close();
        } else {
            // Ако лозинките не се совпаднат
            $error_message = "Passwords do not match. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="forget.css">
</head>
<body>
    <div class="container" id="container">
        <div class="form-container forget-password">
            <form method="POST" action="">
                <h1>Reset Password?</h1>
                <span>Type your new password</span>
                <input type="password" name="new_password" placeholder="New Password" required>
                <span>Confirm your password</span>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>

                <!-- Ако има грешка при внесување на различни лозинки -->
                <?php if ($error_message != ''): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>

                <button type="submit">Reset</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>We are very happy to see you again, thank you for coming back.</p>
                    <a href="index.php">
                    <button class="SignIn" id="register">Sign In</button></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
