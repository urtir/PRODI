<?php
include 'header.php';

// 1. Authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// 2. Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3. Directory setup
$uploadsDir = __DIR__ . "/../static/images/courses";
if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// 4. Initialize variables
$success_message = '';
$error_message = '';

// 5. Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get basic form data
        $code = $_POST['code'] ?? '';
        $name = $_POST['name'] ?? '';
        $credits = (int)($_POST['credits'] ?? 0);
        $description = $_POST['description'] ?? '';
        $semester = (int)($_POST['semester'] ?? 0);
        $materials_url = $_POST['materials_url'] ?? '';
        $address_url = $_POST['address_url'] ?? '';

        // Validate required fields
        if (empty($code) || empty($name) || $credits <= 0 || $semester <= 0) {
            throw new Exception("All required fields must be filled");
        }

        // Handle image upload
        $image_url = null;
        if (isset($_FILES['image_url']) && $_FILES['image_url']['size'] > 0) {
            $file = $_FILES['image_url'];
            $fileName = time() . '_' . basename($file['name']);
            $targetPath = $uploadsDir . '/' . $fileName;
            
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                throw new Exception("Failed to upload image");
            }
            $image_url = $fileName;
        }

        // Insert into database
        $sql = "INSERT INTO courses (
            code, name, credits, description, semester,
            materials_url, address_url, image_url
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisssss",
            $code,
            $name,
            $credits,
            $description,
            $semester,
            $materials_url,
            $address_url,
            $image_url
        );

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Course added successfully!";
            header("Location: manage_courses.php");
            exit();
        } else {
            throw new Exception("Failed to add course: " . $conn->error);
        }

    } catch (Exception $e) {
        $error_message = $e->getMessage();
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

                    <!-- Add this before the Address Management section -->
                    <div class="mb-4">
                        <label class="form-label">Course Materials Management</label>
                        <div class="card">
                            <div class="card-body">
                                <!-- Materials Path Input -->
                                <div class="mb-3">
                                    <label class="form-label d-flex align-items-center">
                                        Materials Path
                                        <i class="fas fa-info-circle ms-2" data-bs-toggle="tooltip" 
                                        title="Example: course_materials/CS101/materials.zip"></i>
                                    </label>
                                    <input type="text" class="form-control" name="materials_url" 
                                        placeholder="course_materials/[COURSE_CODE]/materials.zip">
                                </div>

                                <!-- Guide Section -->
                                <div class="alert alert-warning mb-0">
                                    <h6 class="alert-heading"><i class="fas fa-lightbulb me-2"></i>How to Add Course Materials</h6>
                                    <hr>
                                    <ol class="small mb-0">
                                        <li class="mb-2">Create this folder structure locally:
                                            <code class="d-block mt-1">C:/xampp/htdocs/PRODI/static/materials/course_materials/[COURSE_CODE]/</code>
                                        </li>
                                        <li class="mb-2">Compress your course materials into a ZIP file named <code>materials.zip</code></li>
                                        <li class="mb-2">Place the ZIP file in the course code folder you created</li>
                                        <li class="mb-2">Enter the path in format: <code>course_materials/[COURSE_CODE]/materials.zip</code></li>
                                        <li>Example for CS101:
                                            <ul class="mt-1">
                                                <li>Physical path: <code>C:/xampp/htdocs/PRODI/static/materials/course_materials/CS101/materials.zip</code></li>
                                                <li>URL to enter: <code>course_materials/CS101/materials.zip</code></li>
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