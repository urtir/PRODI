<?php
include 'header.php';

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get course ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch course details
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    header("Location: courses.php");
    exit();
}
?>


<div class="container" style="padding-top: 110px; padding-bottom: 2rem;">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="courses.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Courses
        </a>
    </div>

    <!-- Course Details Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Course Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1"><?php echo htmlspecialchars($course['name']); ?></h2>
                    <h5 class="text-muted"><?php echo htmlspecialchars($course['code']); ?></h5>
                </div>
                <span class="badge bg-primary fs-6">Semester <?php echo $course['semester']; ?></span>
            </div>

            <!-- Course Info Grid -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-md-4">
                    <div class="p-3 border rounded bg-light">
                        <div class="text-muted mb-1">Credits</div>
                        <div class="h5 mb-0"><?php echo $course['credits']; ?> SKS</div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="p-3 border rounded bg-light">
                        <div class="text-muted mb-1">Course Code</div>
                        <div class="h5 mb-0"><?php echo htmlspecialchars($course['code']); ?></div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="p-3 border rounded bg-light">
                        <div class="text-muted mb-1">Semester</div>
                        <div class="h5 mb-0"><?php echo $course['semester']; ?></div>
                    </div>
                </div>
            </div>

            <!-- Course Description -->
            <div class="mb-4">
                <h5 class="mb-3">Course Description</h5>
                <p class="text-muted"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
            </div>

            <!-- Materials Download -->
            <?php if($course['materials_url']): ?>
            <div class="d-grid">
                <a href="<?php echo htmlspecialchars($course['materials_url']); ?>" 
                   class="btn btn-primary" 
                   target="_blank">
                    <i class="fas fa-download me-2"></i>Download Course Materials
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>