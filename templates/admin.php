<?php
session_start();

// Check for admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

include 'header.php';
$current_page = 'admin';
?>

<div class="container mt-5 pt-5">
    <h2>Admin Panel</h2>
    <!-- Admin content here -->
</div>

<?php include 'footer.php'; ?>