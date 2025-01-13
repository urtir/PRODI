<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No ID provided']);
    exit;
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM lecturers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$lecturer = $result->fetch_assoc();

if (!$lecturer) {
    echo json_encode(['error' => 'Lecturer not found']);
    exit;
}

echo json_encode($lecturer);
$conn->close();
?>