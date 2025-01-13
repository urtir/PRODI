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

// Fetch event data
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    header("Location: manage_events.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $long_description = $_POST['long_description'];
    $date = $_POST['date'];
    
    // Image processing
    $image_fields = ['image_url', 'image_url2', 'image_url3'];
    $updated_images = [];
    
    foreach ($image_fields as $field) {
        if (isset($_FILES[$field]) && $_FILES[$field]['size'] > 0) {
            $file = $_FILES[$field];
            $file_name = time() . '_' . $field . '_' . basename($file['name']);
            $target_path = "../static/images/events/" . $file_name;
            
            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                $updated_images[$field] = $file_name;
            }
        } else {
            // Keep existing image
            $updated_images[$field] = $event[$field];
        }
    }
    
    // Update database
    $sql = "UPDATE events SET 
            title = ?, 
            description = ?, 
            long_description = ?, 
            date = ?, 
            image_url = ?,
            image_url2 = ?,
            image_url3 = ?
            WHERE id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", 
        $title, 
        $description, 
        $long_description, 
        $date,
        $updated_images['image_url'],
        $updated_images['image_url2'],
        $updated_images['image_url3'],
        $id
    );
    
    if ($stmt->execute()) {
        $success_message = "Event updated successfully!";
    } else {
        $error_message = "Error updating event: " . $conn->error;
    }
}
?>
<style>
.content-wrapper {
    position: relative;
    z-index: 1;
    min-height: calc(100vh - 80px);
    background: #fff;
    padding-top: 120px; /* Increased padding */
}

.breadcrumb-container {
    margin-bottom: 2rem;
}

.page-header {
    margin-bottom: 3rem;
}
</style>

<div class="content-wrapper" style="padding-top: 100px;">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb-container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="manage_events.php">Events Management</a></li>
                    <li class="breadcrumb-item active">Edit Event</li>
                </ol>
            </nav>
        </div>

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

        <!-- Existing form content -->
        <div class="card">
                <div class="card-header">
                    <h4>Edit Event</h4>
                </div>
                <div class="card-body">
                    <?php if ($success_message): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" 
                                   value="<?php echo htmlspecialchars($event['title']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" 
                                      required><?php echo htmlspecialchars($event['description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Long Description</label>
                            <textarea class="form-control" name="long_description" 
                                      rows="5"><?php echo htmlspecialchars($event['long_description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" name="date" 
                                   value="<?php echo $event['date']; ?>" required>
                        </div>

                        <?php
                        $image_fields = [
                            ['name' => 'image_url', 'label' => 'Main Image'],
                            ['name' => 'image_url2', 'label' => 'Second Image'],
                            ['name' => 'image_url3', 'label' => 'Third Image']
                        ];
                        
                        foreach ($image_fields as $field):
                            $current_image = $event[$field['name']];
                        ?>
                            <div class="mb-3">
                                <label class="form-label"><?php echo $field['label']; ?></label>
                                <?php if ($current_image): ?>
                                    <div class="mb-2">
                                        <img src="../static/images/events/<?php echo $current_image; ?>" 
                                             class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" name="<?php echo $field['name']; ?>" 
                                       accept="image/*">
                                <small class="text-muted">Leave empty to keep current image</small>
                            </div>
                        <?php endforeach; ?>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Event</button>
                            <a href="manage_events.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
include 'footer.php';
?>