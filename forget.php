<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recovery</title>
    <link rel="stylesheet" href="forget.css">
</head>
<body>
    <div class="container" id="container">
        <div class="form-container forget-password">
            <form method="POST" action="recovery.php">
                <h1>Forget Password?</h1>
                <span>Type your email address to recover it</span>
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit">Recovery</button>
                
                <!-- Прикажување на порака за грешка ако email не е пронајден -->
                <?php
                if (isset($_GET['error']) && $_GET['error'] == 'email_not_found') {
                    echo "<p style='color: red;'>There is no account with this Email!</p>";
                }
                ?>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>We are very happy to see you again, thank you for coming back.</p>
                    <a href="index.php">
                        <button class="SignIn" id="register">Sign In</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
