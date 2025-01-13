<?php
include 'header.php';

// Authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$success_message = '';
$error_message = '';

// Fetch existing course data
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    header("Location: manage_courses.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    $name = $_POST['name'] ?? '';
    $credits = $_POST['credits'] ?? '';
    $description = $_POST['description'] ?? '';
    $semester = $_POST['semester'] ?? '';
    $existing_image = $course['image_url'];
    
    // Handle image upload if new image provided
    if (isset($_FILES['image_url']) && $_FILES['image_url']['size'] > 0) {
        $file = $_FILES['image_url'];
        $file_name = time() . '_' . basename($file['name']);
        $target_path = "../static/images/courses/" . $file_name;
        
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            // Delete old image if exists
            if ($existing_image && file_exists("../static/images/courses/" . $existing_image)) {
                unlink("../static/images/courses/" . $existing_image);
            }
            $existing_image = $file_name;
        }
    }
    
    // Update database
    $sql = "UPDATE courses SET code = ?, name = ?, credits = ?, description = ?, 
            semester = ?, image_url = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssi", $code, $name, $credits, $description, $semester, 
                      $existing_image, $id);
    
    if ($stmt->execute()) {
        $success_message = "Course updated successfully!";
    } else {
        $error_message = "Error updating course: " . $conn->error;
    }
}
?>

<div class="content-wrapper" style="padding-top: 100px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="manage_courses.php">Courses Management</a></li>
                <li class="breadcrumb-item active">Edit Course</li>
            </ol>
        </nav>

        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $success_message; ?>
                <div class="mt-3">
                    <a href="manage_courses.php" class="btn btn-success">
                        <i class="fas fa-arrow-left"></i> Back to Courses
                    </a>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h4>Edit Course</h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course Code</label>
                            <input type="text" class="form-control" name="code" 
                                   value="<?php echo htmlspecialchars($course['code']); ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course Name</label>
                            <input type="text" class="form-control" name="name" 
                                   value="<?php echo htmlspecialchars($course['name']); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Credits</label>
                            <input type="number" class="form-control" name="credits" 
                                   value="<?php echo htmlspecialchars($course['credits']); ?>" 
                                   min="1" max="6" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semester</label>
                            <select class="form-select" name="semester" required>
                                <?php for($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?php echo $i; ?>" 
                                            <?php echo $course['semester'] == $i ? 'selected' : ''; ?>>
                                        Semester <?php echo $i; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="4" 
                                  required><?php echo htmlspecialchars($course['description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Course Image</label>
                        <?php if ($course['image_url']): ?>
                            <div class="mb-2">
                                <img src="../static/images/courses/<?php echo htmlspecialchars($course['image_url']); ?>" 
                                     class="img-thumbnail" style="max-height: 200px;" alt="Current image">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" name="image_url" accept="image/*">
                        <div class="mt-2" id="imagePreview"></div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Course</button>
                        <a href="manage_courses.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('input[type="file"]').addEventListener('change', function() {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('img-thumbnail');
            img.style.maxHeight = '200px';
            preview.appendChild(img);
        }
        reader.readAsDataURL(this.files[0]);
    }
});
</script>

<?php
$conn->close();
include 'footer.php';
?>