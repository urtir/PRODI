<?php
if (isset($_GET['code'])) {
    $courseCode = $_GET['code'];
    
    // Connect to database to get materials_url
    $conn = new mysqli("localhost", "root", "", "informatics_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("SELECT materials_url FROM courses WHERE code = ?");
    $stmt->bind_param("s", $courseCode);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();
    
    if ($course && $course['materials_url']) {
        $materialPath = "../static/" . $course['materials_url'];
        
        if (file_exists($materialPath)) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($materialPath) . '"');
            header('Content-Length: ' . filesize($materialPath));
            readfile($materialPath);
            exit();
        }
    }
}

header('Location: courses.php');
exit();
?>