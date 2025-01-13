<?php
include 'header.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get counts for each section
$event_count = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];
$lecturer_count = $conn->query("SELECT COUNT(*) as count FROM lecturers")->fetch_assoc()['count'];
$award_count = $conn->query("SELECT COUNT(*) as count FROM awards")->fetch_assoc()['count'];
$post_count = $conn->query("SELECT COUNT(*) as count FROM posts")->fetch_assoc()['count'];
$message_count = $conn->query("SELECT COUNT(*) as count FROM contact_messages")->fetch_assoc()['count'];
$user_count = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
?>

<div class="container mt-5">
    <h2 class="mb-4">Admin Dashboard</h2>
    
    <div class="row g-4">
        <!-- Events Card -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Events Management</h5>
                    <p class="card-text">Manage all events (<?php echo $event_count; ?> total)</p>
                    <a href="manage_events.php" class="btn btn-primary">Manage Events</a>
                </div>
            </div>
        </div>

        <!-- Lecturers Card -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Lecturers Management</h5>
                    <p class="card-text">Manage faculty members (<?php echo $lecturer_count; ?> total)</p>
                    <a href="manage_lecturers.php" class="btn btn-primary">Manage Lecturers</a>
                </div>
            </div>
        </div>

        <!-- Awards Card -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Awards Management</h5>
                    <p class="card-text">Manage achievements (<?php echo $award_count; ?> total)</p>
                    <a href="manage_awards.php" class="btn btn-primary">Manage Awards</a>
                </div>
            </div>
        </div>

        <!-- Posts Card -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Posts Management</h5>
                    <p class="card-text">Manage blog posts (<?php echo $post_count; ?> total)</p>
                    <a href="manage_posts.php" class="btn btn-primary">Manage Posts</a>
                </div>
            </div>
        </div>

        <!-- Messages Card -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Messages</h5>
                    <p class="card-text">View contact messages (<?php echo $message_count; ?> total)</p>
                    <a href="manage_messages.php" class="btn btn-primary">View Messages</a>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Users Management</h5>
                    <p class="card-text">Manage user accounts (<?php echo $user_count; ?> total)</p>
                    <a href="manage_users.php" class="btn btn-primary">Manage Users</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
include 'footer.php';
?>