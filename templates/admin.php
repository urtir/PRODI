<?php
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "informatics_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get admin user data
$admin_query = $conn->prepare("SELECT username, firstname, lastname FROM users WHERE id = ?");
$admin_query->bind_param("i", $_SESSION['user_id']);
$admin_query->execute();
$admin_data = $admin_query->get_result()->fetch_assoc();

// Core statistics
$event_count = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];
$lecturer_count = $conn->query("SELECT COUNT(*) as count FROM lecturers")->fetch_assoc()['count'];
$award_count = $conn->query("SELECT COUNT(*) as count FROM awards")->fetch_assoc()['count'];
$post_count = $conn->query("SELECT COUNT(*) as count FROM posts")->fetch_assoc()['count'];
$course_count = $conn->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'];
$research_count = $conn->query("SELECT COUNT(*) as count FROM researches")->fetch_assoc()['count'];
$unread_messages = $conn->query("SELECT COUNT(*) as count FROM contact_messages WHERE status='unread'")->fetch_assoc()['count'];

$user_count = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$active_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE TIMESTAMPDIFF(MINUTE, created_at, NOW()) < 30")->fetch_assoc()['count'];
$latest_user = $conn->query("SELECT username FROM users ORDER BY created_at DESC LIMIT 1")->fetch_assoc();

$private_message_count = $conn->query("SELECT COUNT(*) as count FROM private_messages WHERE status='unread'")->fetch_assoc()['count'];
$comment_count = $conn->query("SELECT COUNT(*) as count FROM comments")->fetch_assoc()['count'];
$total_private_messages = $conn->query("SELECT COUNT(*) as count FROM private_messages")->fetch_assoc()['count'];

// Update recent activities query
$recent_activities = $conn->query("
    (SELECT 'event' as type, title, created_at, NULL as username 
     FROM events 
     ORDER BY created_at DESC LIMIT 3)
    UNION
    (SELECT 'post' as type, p.title, p.created_at, u.username 
     FROM posts p 
     LEFT JOIN users u ON p.user_id = u.id 
     ORDER BY p.created_at DESC LIMIT 3)
    ORDER BY created_at DESC LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Update recent messages query
$recent_messages = $conn->query("
    (SELECT 'contact' as type, name as sender_name, subject, message, created_at, status
     FROM contact_messages 
     WHERE status='unread' 
     ORDER BY created_at DESC LIMIT 3)
    UNION
    (SELECT 'private' as type, u.username as sender_name, pm.subject, pm.message, 
            pm.created_at, pm.status
     FROM private_messages pm
     LEFT JOIN users u ON pm.sender_id = u.id
     WHERE pm.status='unread'
     ORDER BY created_at DESC LIMIT 3)
    ORDER BY created_at DESC LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

?>

<!-- Main Content -->
<main class="content-wrapper">
    <!-- Dashboard Content -->
    <div class="admin-dashboard" style="margin-top: 100px;">
        <div class="container-fluid px-4">
            <!-- Admin Header -->
            <div class="row align-items-center g-3 mb-4">
                <div class="col-12 col-md">
                    <h1 class="h3 mb-0 text-navy">Admin Dashboard</h1>
                    <p class="text-muted mb-0">
                        Welcome back, <?php echo htmlspecialchars($admin_data['firstname'] . ' ' . $admin_data['lastname']); ?>
                    </p>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="d-flex gap-2">
                        <a href="manage_users.php" class="btn btn-primary">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
                <!-- Quick Stats Cards -->
                <div class="row g-3 mb-4">
                <!-- Events Card -->
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar bg-primary-subtle p-2 rounded">
                                        <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0 small text-muted">Total Events</h6>
                                    <h2 class="mb-0 text-navy"><?php echo $event_count; ?></h2>
                                </div>
                            </div>
                            <a href="manage_events.php" class="btn btn-light btn-sm w-100 text-start">
                                <i class="fas fa-arrow-right text-primary me-2"></i>Manage Events
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Lecturers Card -->
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar bg-success-subtle p-2 rounded">
                                        <i class="fas fa-chalkboard-teacher fa-2x text-success"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0 small text-muted">Lecturers</h6>
                                    <h2 class="mb-0 text-navy"><?php echo $lecturer_count; ?></h2>
                                </div>
                            </div>
                            <a href="manage_lecturers.php" class="btn btn-light btn-sm w-100 text-start">
                                <i class="fas fa-arrow-right text-success me-2"></i>Manage Lecturers
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Awards Card -->
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar bg-warning-subtle p-2 rounded">
                                        <i class="fas fa-trophy fa-2x text-warning"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0 small text-muted">Awards</h6>
                                    <h2 class="mb-0 text-navy"><?php echo $award_count; ?></h2>
                                </div>
                            </div>
                            <a href="manage_awards.php" class="btn btn-light btn-sm w-100 text-start">
                                <i class="fas fa-arrow-right text-warning me-2"></i>Manage Awards
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Posts Card -->
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar bg-info-subtle p-2 rounded">
                                        <i class="fas fa-newspaper fa-2x text-info"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0 small text-muted">Posts</h6>
                                    <h2 class="mb-0 text-navy"><?php echo $post_count; ?></h2>
                                </div>
                            </div>
                            <a href="manage_posts.php" class="btn btn-light btn-sm w-100 text-start">
                                <i class="fas fa-arrow-right text-info me-2"></i>Manage Posts
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Courses Card -->
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar bg-danger-subtle p-2 rounded">
                                        <i class="fas fa-graduation-cap fa-2x text-danger"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0 small text-muted">Courses</h6>
                                    <h2 class="mb-0 text-navy"><?php echo $course_count; ?></h2>
                                </div>
                            </div>
                            <a href="manage_courses.php" class="btn btn-light btn-sm w-100 text-start">
                                <i class="fas fa-arrow-right text-danger me-2"></i>Manage Courses
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Research Card -->
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar bg-purple-subtle p-2 rounded">
                                        <i class="fas fa-flask fa-2x text-purple"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0 small text-muted">Research</h6>
                                    <h2 class="mb-0 text-navy"><?php echo $research_count; ?></h2>
                                </div>
                            </div>
                            <a href="manage_researches.php" class="btn btn-light btn-sm w-100 text-start">
                                <i class="fas fa-arrow-right text-purple me-2"></i>Manage Research
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Recent Activities & Messages Section -->
<div class="row g-3 mb-4">
    <!-- Recent Activities -->
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history text-primary me-2"></i>Recent Activities
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="timeline p-3">
                    <?php foreach ($recent_activities as $activity): ?>
                        <div class="timeline-item pb-3">
                            <div class="d-flex">
                                <div class="timeline-icon me-3">
                                    <?php if ($activity['type'] == 'post'): ?>
                                        <i class="fas fa-file-alt text-info"></i>
                                    <?php else: ?>
                                        <i class="fas fa-calendar text-primary"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($activity['title']); ?></h6>
                                    <small class="text-muted">
                                        <?php echo date('M d, Y h:i A', strtotime($activity['created_at'])); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Overview -->
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-envelope text-primary me-2"></i>Recent Messages
                </h5>
                <?php if ($unread_messages > 0): ?>
                    <span class="badge bg-danger"><?php echo $unread_messages; ?> unread</span>
                <?php endif; ?>
            </div>
            <div class="card-body p-0">
                <div class="message-list">
                    <?php foreach ($recent_messages as $message): ?>
                        <div class="message-item p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?php echo htmlspecialchars($message['subject']); ?></h6>
                                <?php if ($message['status'] == 'unread'): ?>
                                    <span class="badge bg-warning">New</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-muted small mb-2">
                                <?php echo substr(htmlspecialchars($message['message']), 0, 100) . '...'; ?>
                            </p>
                            <small class="text-muted">
                                <?php echo date('M d, Y', strtotime($message['created_at'])); ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="p-3">
                    <a href="manage_messages.php" class="btn btn-light btn-sm w-100">
                        View All Messages
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- System Overview Section -->
<div class="row g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-server text-primary me-2"></i>System Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- System Status -->
                    <div class="col-md-3">
                        <div class="system-stat p-3 rounded bg-light">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-signal fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">System Status</h6>
                                    <span class="badge bg-success">Online</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Stats -->
                    <div class="col-md-3">
                        <div class="system-stat p-3 rounded bg-light">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-users fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Total Users</h6>
                                    <div class="d-flex align-items-center">
                                        <h3 class="mb-0 me-2"><?php echo $user_count; ?></h3>
                                        <small class="text-success">
                                            <i class="fas fa-user-plus"></i> <?php echo $active_users; ?> active
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Latest Activity -->
                    <div class="col-md-3">
                        <div class="system-stat p-3 rounded bg-light">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Latest User</h6>
                                    <p class="mb-0"><?php echo htmlspecialchars($latest_user['username'] ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Overview -->
                    <div class="col-md-3">
                        <div class="system-stat p-3 rounded bg-light">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-envelope fa-2x text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Unread Messages</h6>
                                    <div class="d-flex align-items-center">
                                        <h3 class="mb-0 me-2"><?php echo $unread_messages; ?></h3>
                                        <a href="manage_messages.php" class="btn btn-sm btn-info">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Quick Management Links -->
<div class="row g-3 mb-4">
    <!-- Content Management -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="quick-access-section">
            <h6 class="text-navy mb-3">
                <i class="fas fa-newspaper me-2"></i>Content Management
            </h6>
            <div class="d-grid gap-2">
                <a href="manage_posts.php" class="btn btn-light text-start">
                    <i class="fas fa-file-alt text-primary me-2"></i>Posts
                    <span class="badge bg-primary float-end"><?php echo $post_count; ?></span>
                </a>
                <a href="manage_events.php" class="btn btn-light text-start">
                    <i class="fas fa-calendar-alt text-success me-2"></i>Events
                    <span class="badge bg-success float-end"><?php echo $event_count; ?></span>
                </a>
                <a href="manage_researches.php" class="btn btn-light text-start">
                    <i class="fas fa-flask text-info me-2"></i>Research
                    <span class="badge bg-info float-end"><?php echo $research_count; ?></span>
                </a>
            </div>
        </div>
    </div>

    <!-- Educational -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="quick-access-section">
            <h6 class="text-navy mb-3">
                <i class="fas fa-graduation-cap me-2"></i>Educational
            </h6>
            <div class="d-grid gap-2">
                <a href="manage_courses.php" class="btn btn-light text-start">
                    <i class="fas fa-book text-primary me-2"></i>Courses
                    <span class="badge bg-primary float-end"><?php echo $course_count; ?></span>
                </a>
                <a href="manage_lecturers.php" class="btn btn-light text-start">
                    <i class="fas fa-chalkboard-teacher text-success me-2"></i>Lecturers
                    <span class="badge bg-success float-end"><?php echo $lecturer_count; ?></span>
                </a>
                <a href="manage_awards.php" class="btn btn-light text-start">
                    <i class="fas fa-trophy text-warning me-2"></i>Awards
                    <span class="badge bg-warning float-end"><?php echo $award_count; ?></span>
                </a>
            </div>
        </div>
    </div>

    <!-- Communication -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="quick-access-section">
            <h6 class="text-navy mb-3">
                <i class="fas fa-comments me-2"></i>Communication
            </h6>
            <div class="d-grid gap-2">
                <a href="manage_messages.php" class="btn btn-light text-start">
                    <i class="fas fa-envelope text-primary me-2"></i>Messages
                    <?php if ($unread_messages > 0): ?>
                        <span class="badge bg-danger float-end"><?php echo $unread_messages; ?></span>
                    <?php endif; ?>
                </a>
                <a href="manage_comments.php" class="btn btn-light text-start">
                    <i class="fas fa-comment-alt text-success me-2"></i>Comments
                    <span class="badge bg-success float-end"><?php echo $comment_count; ?></span>
                </a>
                <a href="manage_private_messages.php" class="btn btn-light text-start">
                    <i class="fas fa-envelope-open-text text-info me-2"></i>Private Messages
                    <?php if ($private_message_count > 0): ?>
                        <span class="badge bg-danger float-end"><?php echo $private_message_count; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</div>







</div>
</main>

<style>
.content-wrapper {
    padding-top: 60px;
    padding-left: 48px;
    padding-right: 48px;
    min-height: 100vh;
    
    background: #f8f9fa;
}

.admin-dashboard {
    position: relative;
    z-index: 1;
}

.text-navy {
    color: #0D47A1;
}
.bg-success-subtle { background-color: rgba(25, 135, 84, 0.1); }
.bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1); }
.bg-info-subtle { background-color: rgba(13, 202, 240, 0.1); }
.bg-danger-subtle { background-color: rgba(220, 53, 69, 0.1); }
.bg-purple-subtle { background-color: rgba(111, 66, 193, 0.1); }
.text-purple { color: #6f42c1; }
.avatar { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; }

/* Add to existing style section */
.quick-access-section {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
}

.quick-access-section .btn-light {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.quick-access-section .btn-light:hover {
    background: #fff;
    transform: translateX(5px);
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
}
</style>

<?php 
$conn->close();
include 'footer.php'; 
?>