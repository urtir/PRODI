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

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    $name = $_POST['name'] ?? '';
    $credits = $_POST['credits'] ?? '';
    $description = $_POST['description'] ?? '';
    $semester = $_POST['semester'] ?? '';
    
    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image_url']) && $_FILES['image_url']['size'] > 0) {
        $file = $_FILES['image_url'];
        $file_name = time() . '_' . basename($file['name']);
        $target_path = "../static/images/courses/" . $file_name;
        
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            $image_url = $file_name;
        }
    }
    
    $sql = "INSERT INTO courses (code, name, credits, description, semester, image_url) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisis", $code, $name, $credits, $description, $semester, $image_url);
    
    if ($stmt->execute()) {
        $success_message = "Course added successfully!";
    } else {
        $error_message = "Error adding course: " . $conn->error;
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
                <li class="breadcrumb-item active">Add Course</li>
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
                <h4>Add New Course</h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course Code</label>
                            <input type="text" class="form-control" name="code" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Credits</label>
                            <input type="number" class="form-control" name="credits" min="1" max="6" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semester</label>
                            <select class="form-select" name="semester" required>
                                <option value="">Select Semester</option>
                                <?php for($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Course Image</label>
                        <input type="file" class="form-control" name="image_url" accept="image/*">
                        <div class="mt-2" id="imagePreview"></div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Add Course</button>
                        <a href="manage_courses.php" class="btn btn-secondary">Cancel</a>
                    </div>

                    <!-- Add this after materials management section -->
                    <div class="mb-4">
                    <label class="form-label">Course Address Management</label>
                    <div class="card">
                        <div class="card-body">
                            <!-- Address Path Input -->
                            <div class="mb-3">
                                <label class="form-label d-flex align-items-center">
                                    Address Path
                                    <i class="fas fa-info-circle ms-2" data-bs-toggle="tooltip" 
                                    title="Example: course_address/CS101/location.html"></i>
                                </label>
                                <input type="text" class="form-control" name="address_url" 
                                    placeholder="course_address/[COURSE_CODE]/location.html">
                            </div>

                            <!-- Guide Section -->
                            <div class="alert alert-warning mb-0">
                                <h6 class="alert-heading">
                                    <i class="fas fa-lightbulb me-2"></i>How to Add Course Address
                                </h6>
                                <hr>
                                <ol class="small mb-0">
                                    <li class="mb-2">Create this folder structure:
                                        <code class="d-block mt-1">C:/xampp/htdocs/PRODI/static/address/course_address/[COURSE_CODE]/</code>
                                    </li>
                                    <li class="mb-2">Create your HTML file with the address/location details</li>
                                    <li class="mb-2">Save the file in the course code folder</li>
                                    <li class="mb-2">Enter path format: <code>course_address/[COURSE_CODE]/location.html</code></li>
                                    <li>Example for CS101:
                                        <ul class="mt-1">
                                            <li>Physical path: <code>C:/xampp/htdocs/PRODI/static/address/course_address/CS101/location.html</code></li>
                                            <li>URL to enter: <code>course_address/CS101/location.html</code></li>
                                        </ul>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>                        


                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview functionality
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