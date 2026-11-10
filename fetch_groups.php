<?php
// fetch_groups.php

// Подесување на врска со базата на податоци
$servername = "localhost";
$username = "root";
$password = "usbw";
$dbname = "korisnici";

// Создавање на врска со базата
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверање дали има грешка во врската
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL upit за превземање на групи од базата
$sql = "SELECT groups.*, users.firstname, users.lastname FROM groups JOIN users ON groups.user_id = users.id";
$result = $conn->query($sql);

$groups = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $groups[] = $row;
    }
}

$conn->close();

// Враќање на групите како JSON одговор
echo json_encode($groups);
?>