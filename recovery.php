<?php
// recovery.php

// Подесување на врска со базата на податоци
$servername = "localhost";
$username = "root";
$password = "usbw";
$dbname = "korisnici";

// Превземи email од формата
if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Создавање на врска со базата
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Проверање дали има грешка во врската
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL upit за проверка на email во базата
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Проверка дали email-то постои
    if ($result->num_rows > 0) {
        // Ако постои, пренасочувај до pchange.php
        $conn->close(); // Затворање на врската со базата
        header("Location: pchange.php");
        exit();
    } else {
        // Ако не постои, прикажи грешка
        $conn->close(); // Затворање на врската со базата
        header("Location: forget.php?error=email_not_found");
        exit();
    }
}
?>
