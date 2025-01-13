<?php
include 'header.php';

// Authentication
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

// Fetch lecturer data
$stmt = $conn->prepare("SELECT * FROM lecturers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$lecturer = $result->fetch_assoc();

// Add after fetching lecturer data
$lecturer = array_merge([
    'name' => '',
    'title' => '',
    'specialization' => '',
    'email' => '',
    'education' => '',
    'education_details' => '',
    'professional_profile' => '',
    'experience' => '',
    'experience_years' => '',
    'experience_location' => '',
    'experience_details' => '',
    'additional_skills' => '',
    'social_linkedin' => '',
    'bio' => '',
    'image_url' => ''
], $lecturer ?? []);

if (!$lecturer) {
    header("Location: manage_lecturers.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    
    // Image processing
    if (isset($_FILES['image_url']) && $_FILES['image_url']['size'] > 0) {
        $file = $_FILES['image_url'];
        $file_name = time() . '_' . basename($file['name']);
        $target_path = "../static/images/lecturers/" . $file_name;
        
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            $image_url = $file_name;
        }
    } else {
        $image_url = $lecturer['image_url'];
    }
    
    // Update database
    $sql = "UPDATE lecturers SET 
            name = ?, 
            title = ?,
            specialization = ?,
            email = ?,
            education = ?,
            education_details = ?,
            professional_profile = ?,
            experience = ?,
            experience_years = ?,
            experience_location = ?,
            experience_details = ?,
            additional_skills = ?,
            social_linkedin = ?,
            bio = ?,
            image_url = ?
            WHERE id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssi", 
        $name, $title, $specialization, $email, 
        $education, $education_details, $professional_profile,
        $experience, $experience_years, $experience_location,
        $experience_details, $additional_skills, $social_linkedin,
        $bio, $image_url, $id
    );
    
    if ($stmt->execute()) {
        $success_message = "Lecturer updated successfully!";
    } else {
        $error_message = "Error updating lecturer: " . $conn->error;
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
                <li class="breadcrumb-item active">Edit Lecturer</li>
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
                <h4>Edit Lecturer</h4>
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
                            <input type="text" class="form-control" name="name" 
                                   value="<?php echo htmlspecialchars($lecturer['name']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" 
                                   value="<?php echo htmlspecialchars($lecturer['title']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Specialization</label>
                            <input type="text" class="form-control" name="specialization" 
                                   value="<?php echo htmlspecialchars($lecturer['specialization']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" 
                                   value="<?php echo htmlspecialchars($lecturer['email']); ?>" required>
                        </div>
                    </div>

                    <!-- Education -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Education</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Education Level</label>
                            <input type="text" class="form-control" name="education" 
                                   value="<?php echo htmlspecialchars($lecturer['education']); ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Education Details</label>
                            <textarea class="form-control" name="education_details" 
                                      rows="3"><?php echo htmlspecialchars($lecturer['education_details']); ?></textarea>
                        </div>
                    </div>

                    <!-- Professional Details -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Professional Details</h5>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Professional Profile</label>
                            <textarea class="form-control" name="professional_profile" 
                                      rows="3"><?php echo htmlspecialchars($lecturer['professional_profile']); ?></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Experience</label>
                            <textarea class="form-control" name="experience" 
                                      rows="3"><?php echo htmlspecialchars($lecturer['experience']); ?></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Experience Years</label>
                            <input type="text" class="form-control" name="experience_years" 
                                   value="<?php echo htmlspecialchars($lecturer['experience_years']); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Experience Location</label>
                            <input type="text" class="form-control" name="experience_location" 
                                   value="<?php echo htmlspecialchars($lecturer['experience_location']); ?>">
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Additional Information</h5>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Additional Skills</label>
                            <textarea class="form-control" name="additional_skills" 
                                      rows="3"><?php echo htmlspecialchars($lecturer['additional_skills']); ?></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">LinkedIn Profile</label>
                            <input type="url" class="form-control" name="social_linkedin" 
                                   value="<?php echo htmlspecialchars($lecturer['social_linkedin']); ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Biography</label>
                            <textarea class="form-control" name="bio" 
                                      rows="5"><?php echo htmlspecialchars($lecturer['bio']); ?></textarea>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Profile Image</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <?php if ($lecturer['image_url']): ?>
                                <div class="mb-2">
                                    <img src="../static/images/lecturers/<?php echo $lecturer['image_url']; ?>" 
                                         class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="image_url" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Lecturer</button>
                        <a href="manage_lecturers.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
include 'footer.php';
?>