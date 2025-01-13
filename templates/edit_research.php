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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$success_message = '';
$error_message = '';

// Fetch existing research data
$stmt = $conn->prepare("SELECT * FROM researches WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$research = $result->fetch_assoc();

if (!$research) {
    header("Location: manage_researches.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $details = $_POST['details'] ?? '';
    $existing_image = $research['image_url'];
    
    // Handle image upload
    if (isset($_FILES['image_url']) && $_FILES['image_url']['size'] > 0) {
        $file = $_FILES['image_url'];
        $file_name = time() . '_' . basename($file['name']);
        $target_path = "../static/images/researches/" . $file_name;
        
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            // Delete old image
            if ($existing_image && file_exists("../static/images/researches/" . $existing_image)) {
                unlink("../static/images/researches/" . $existing_image);
            }
            $existing_image = $file_name;
        }
    }
    
    // Update database
    $sql = "UPDATE researches SET title = ?, description = ?, details = ?, image_url = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $description, $details, $existing_image, $id);
    
    if ($stmt->execute()) {
        $success_message = "Research updated successfully!";
    } else {
        $error_message = "Error updating research: " . $conn->error;
    }
}
?>

<div class="content-wrapper" style="padding-top: 100px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="manage_researches.php">Research Management</a></li>
                <li class="breadcrumb-item active">Edit Research</li>
            </ol>
        </nav>

        <!-- Messages -->
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $success_message; ?>
                <div class="mt-3">
                    <a href="manage_researches.php" class="btn btn-success">
                        <i class="fas fa-arrow-left"></i> Back to Research
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

        <!-- Edit Form -->
        <div class="card">
            <div class="card-header">
                <h4>Edit Research</h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" 
                               value="<?php echo htmlspecialchars($research['title']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="4" 
                                  required><?php echo htmlspecialchars($research['description']); ?></textarea>
                        <div class="form-text">Brief overview of the research project</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Details</label>
                        <textarea class="form-control" name="details" 
                                  rows="6"><?php echo htmlspecialchars($research['details']); ?></textarea>
                        <div class="form-text">Detailed information about methodology, findings, etc.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Research Image</label>
                        <?php if ($research['image_url']): ?>
                            <div class="mb-2">
                                <img src="../static/images/researches/<?php echo htmlspecialchars($research['image_url']); ?>" 
                                     class="img-thumbnail" style="max-height: 200px;" alt="Current image">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" name="image_url" accept="image/*">
                        <div class="mt-2" id="imagePreview"></div>
                        <div class="form-text">Leave empty to keep current image</div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Research
                        </button>
                        <a href="manage_researches.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
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