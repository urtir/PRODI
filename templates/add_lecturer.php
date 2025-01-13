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
    // Get form data
    $name = $_POST['name'] ?? '';
    $title = $_POST['title'] ?? '';
    $specialization = $_POST['specialization'] ?? '';
    $email = $_POST['email'] ?? '';
    $education = $_POST['education'] ?? '';
    $education_details = $_POST['education_details'] ?? '';
    $professional_profile = $_POST['professional_profile'] ?? '';
    $experience = $_POST['experience'] ?? '';
    $experience_years = $_POST['experience_years'] ?? '';
    $experience_location = $_POST['experience_location'] ?? '';
    $experience_details = $_POST['experience_details'] ?? '';
    $additional_skills = $_POST['additional_skills'] ?? '';
    $social_linkedin = $_POST['social_linkedin'] ?? '';
    $bio = $_POST['bio'] ?? '';

    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image_url']) && $_FILES['image_url']['size'] > 0) {
        $file = $_FILES['image_url'];
        $file_name = time() . '_' . basename($file['name']);
        $target_path = "../static/images/lecturers/" . $file_name;
        
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            $image_url = $file_name;
        }
    }

    // Insert into database
    $sql = "INSERT INTO lecturers (name, title, specialization, email, education, 
            education_details, professional_profile, experience, experience_years, 
            experience_location, experience_details, additional_skills, social_linkedin, 
            bio, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssss", 
        $name, $title, $specialization, $email, $education, 
        $education_details, $professional_profile, $experience, 
        $experience_years, $experience_location, $experience_details, 
        $additional_skills, $social_linkedin, $bio, $image_url
    );
    
    if ($stmt->execute()) {
        $success_message = "Lecturer added successfully!";
    } else {
        $error_message = "Error adding lecturer: " . $conn->error;
    }
}
?>

<div class="content-wrapper" style="padding-top: 100px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="manage_lecturers.php">Lecturers Management</a></li>
                <li class="breadcrumb-item active">Add Lecturer</li>
            </ol>
        </nav>

        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <div class="mt-3">
                    <a href="manage_lecturers.php" class="btn btn-success">
                        <i class="fas fa-arrow-left"></i> Back to Lecturers
                    </a>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h4>Add New Lecturer</h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Basic Information</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Specialization</label>
                            <input type="text" class="form-control" name="specialization">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>

                    <!-- Education -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Education</h5>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Education Level</label>
                            <input type="text" class="form-control" name="education">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Education Details</label>
                            <textarea class="form-control" name="education_details" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Professional Details -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Professional Details</h5>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Professional Profile</label>
                            <textarea class="form-control" name="professional_profile" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Experience -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Experience</h5>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Experience Summary</label>
                            <textarea class="form-control" name="experience" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Years of Experience</label>
                            <input type="text" class="form-control" name="experience_years">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="experience_location">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Experience Details</label>
                            <textarea class="form-control" name="experience_details" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Additional Information</h5>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Additional Skills</label>
                            <textarea class="form-control" name="additional_skills" rows="3"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">LinkedIn Profile</label>
                            <input type="url" class="form-control" name="social_linkedin">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Biography</label>
                            <textarea class="form-control" name="bio" rows="5"></textarea>
                        </div>
                    </div>

                    <!-- Profile Image -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Profile Image</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="file" class="form-control" name="image_url" accept="image/*">
                            <div class="mt-2" id="imagePreview"></div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Add Lecturer</button>
                        <a href="manage_lecturers.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview functionality
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    
    input.addEventListener('change', function() {
        while (preview.firstChild) {
            preview.removeChild(preview.firstChild);
        }
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail');
                img.style.maxHeight = '200px';
                preview.appendChild(img);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
}

document.querySelector('input[type="file"]').addEventListener('change', function() {
    previewImage(this);
});
</script>

<?php
$conn->close();
include 'footer.php';
?>