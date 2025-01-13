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

// Fetch award data
$stmt = $conn->prepare("SELECT * FROM awards WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$award = $result->fetch_assoc();

if (!$award) {
    header("Location: manage_awards.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $recipient = $_POST['recipient'] ?? '';
    $year = $_POST['year'] ?? '';
    $description = $_POST['description'] ?? '';

    // Update database
    $sql = "UPDATE awards SET title = ?, recipient = ?, year = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $title, $recipient, $year, $description, $id);
    
    if ($stmt->execute()) {
        $success_message = "Award updated successfully!";
    } else {
        $error_message = "Error updating award: " . $conn->error;
    }
}
?>

<div class="content-wrapper" style="padding-top: 100px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="manage_awards.php">Awards Management</a></li>
                <li class="breadcrumb-item active">Edit Award</li>
            </ol>
        </nav>

        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <div class="mt-3">
                    <a href="manage_awards.php" class="btn btn-success">
                        <i class="fas fa-arrow-left"></i> Back to Awards
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
                <h4>Edit Award</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" 
                               value="<?php echo htmlspecialchars($award['title']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Recipient</label>
                        <input type="text" class="form-control" name="recipient" 
                               value="<?php echo htmlspecialchars($award['recipient']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Year</label>
                        <input type="number" class="form-control" name="year" 
                               value="<?php echo htmlspecialchars($award['year']); ?>" 
                               min="1900" max="<?php echo date('Y'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" 
                                  rows="4" required><?php echo htmlspecialchars($award['description']); ?></textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Award</button>
                        <a href="manage_awards.php" class="btn btn-secondary">Cancel</a>
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