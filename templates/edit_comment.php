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

// Get available posts for dropdown
$posts = $conn->query("SELECT id, title FROM posts ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);

// Fetch existing comment data
$stmt = $conn->prepare("SELECT c.*, p.title as post_title FROM comments c LEFT JOIN posts p ON c.post_id = p.id WHERE c.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$comment = $result->fetch_assoc();

if (!$comment) {
    header("Location: manage_comments.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'] ?? '';
    $content = $_POST['content'] ?? '';
    
    $sql = "UPDATE comments SET post_id = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $post_id, $content, $id);
    
    if ($stmt->execute()) {
        $success_message = "Comment updated successfully!";
    } else {
        $error_message = "Error updating comment: " . $conn->error;
    }
}
?>

<div class="content-wrapper" style="padding-top: 100px;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="manage_comments.php">Comments Management</a></li>
                <li class="breadcrumb-item active">Edit Comment</li>
            </ol>
        </nav>

        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $success_message; ?>
                <div class="mt-3">
                    <a href="manage_comments.php" class="btn btn-success">
                        <i class="fas fa-arrow-left"></i> Back to Comments
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
                <h4>Edit Comment</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Select Post</label>
                        <select class="form-select" name="post_id" required>
                            <?php foreach ($posts as $post): ?>
                                <option value="<?php echo $post['id']; ?>" 
                                        <?php echo $comment['post_id'] == $post['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Comment</label>
                        <textarea class="form-control" name="content" rows="5" 
                                  required><?php echo htmlspecialchars($comment['content']); ?></textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Comment</button>
                        <a href="manage_comments.php" class="btn btn-secondary">Cancel</a>
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
