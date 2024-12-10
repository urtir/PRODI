<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Change as needed
define('DB_PASS', ''); // Change as needed
define('DB_NAME', 'informatics_db'); // Change as needed

function connectDB() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}
?>