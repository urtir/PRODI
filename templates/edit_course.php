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
    try {
        // Get form data
        $code = $_POST['code'] ?? '';
        $name = $_POST['name'] ?? '';
        $credits = $_POST['credits'] ?? '';
        $description = $_POST['description'] ?? '';
        $semester = $_POST['semester'] ?? '';
        $materials_url = $_POST['materials_url'] ?? '';
        $address_url = $_POST['address_url'] ?? '';

        // Update query with address_url
        $sql = "UPDATE courses SET 
                code = ?, 
                name = ?, 
                credits = ?, 
                description = ?, 
                semester = ?, 
                materials_url = ?,
                address_url = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissssi", 
            $code,
            $name, 
            $credits,
            $description,
            $semester,
            $materials_url,
            $address_url,
            $id
        );

        if ($stmt->execute()) {
            // Refresh course data after update
            $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $course = $result->fetch_assoc();
            
            $success_message = "Course updated successfully!";
        } else {
            throw new Exception("Failed to update course");
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
                    
                    <div class="mb-4">
                    <label class="form-label">Course Materials Management</label>
                    <div class="card">
                        <div class="card-body">
                            <!-- Current Material Path -->
                            <?php if (isset($course['materials_url']) && $course['materials_url']): ?>
                            <div class="alert alert-info mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-archive fa-2x me-2"></i>
                                    <div>
                                        <h6 class="mb-0">Current Materials Path</h6>
                                        <code class="small"><?php echo htmlspecialchars($course['materials_url']); ?></code>
                                    </div>
                                    <?php if (file_exists("../static/materials/" . $course['materials_url'])): ?>
                                    <a href="../static/materials/<?php echo htmlspecialchars($course['materials_url']); ?>" 
                                    class="btn btn-sm btn-outline-primary ms-auto" download>
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Materials Path Input -->
                            <div class="mb-3">
                                <label class="form-label d-flex align-items-center">
                                    Materials Path
                                    <i class="fas fa-info-circle ms-2" data-bs-toggle="tooltip" 
                                    title="Example: course_materials/CS101/materials.zip"></i>
                                </label>
                                <input type="text" class="form-control" name="materials_url" 
                                    value="<?php echo htmlspecialchars($course['materials_url'] ?? ''); ?>"
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