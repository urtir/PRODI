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
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $long_description = $_POST['long_description'] ?? '';
    $date = $_POST['date'] ?? '';
    
    // Handle image uploads
    $image_urls = ['image_url', 'image_url2', 'image_url3'];
    $uploaded_images = [];
    
    foreach ($image_urls as $field) {
        if (isset($_FILES[$field]) && $_FILES[$field]['size'] > 0) {
            $file = $_FILES[$field];
            $file_name = time() . '_' . $field . '_' . basename($file['name']);
            $target_path = "../static/images/events/" . $file_name;
            
            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                $uploaded_images[$field] = $file_name;
            }
        } else {
            $uploaded_images[$field] = null;
        }
    }
    
    // Insert into database
    $sql = "INSERT INTO events (title, description, long_description, date, image_url, image_url2, image_url3) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", 
        $title, 
        $description, 
        $long_description, 
        $date,
        $uploaded_images['image_url'],
        $uploaded_images['image_url2'],
        $uploaded_images['image_url3']
    );
    
    if ($stmt->execute()) {
        $success_message = "Event added successfully!";
    } else {
        $error_message = "Error adding event: " . $conn->error;
    }
}
?>

<div class="content-wrapper" style="padding-top: 100px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="manage_events.php">Events Management</a></li>
                <li class="breadcrumb-item active">Add Event</li>
            </ol>
        </nav>

        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <div class="mt-3">
                    <a href="manage_events.php" class="btn btn-success">
                        <i class="fas fa-arrow-left"></i> Back to Events
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
                <h4>Add New Event</h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Long Description</label>
                        <textarea class="form-control" name="long_description" rows="5"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Main Image</label>
                            <input type="file" class="form-control" name="image_url" accept="image/*">
                            <div class="mt-2" id="preview1"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Second Image</label>
                            <input type="file" class="form-control" name="image_url2" accept="image/*">
                            <div class="mt-2" id="preview2"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Third Image</label>
                            <input type="file" class="form-control" name="image_url3" accept="image/*">
                            <div class="mt-2" id="preview3"></div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Add Event</button>
                        <a href="manage_events.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview functionality
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    
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
                img.style.maxHeight = '150px';
                preview.appendChild(img);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
}

// Initialize image previews
document.querySelectorAll('input[type="file"]').forEach((input, index) => {
    previewImage(input, `preview${index + 1}`);
});
</script>

<?php
$conn->close();
include 'footer.php';
?>