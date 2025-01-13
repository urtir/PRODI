<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_POST['receiver_id']) || !isset($_POST['message'])) {
    header("Location: messages.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "informatics_db");
$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO private_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);
$stmt->execute();

header("Location: messages.php?user=" . $receiver_id);
exit();
?>