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

// Handle Delete Operation
if (isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    
    // Get image URL before deletion
    $stmt = $conn->prepare("SELECT image_url FROM researches WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $research = $result->fetch_assoc();
    
    // Delete from database
    $stmt = $conn->prepare("DELETE FROM researches WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        // Delete associated image
        if ($research && !empty($research['image_url'])) {
            $image_path = "../static/images/researches/" . $research['image_url'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        $_SESSION['success_message'] = "Research deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting research: " . $conn->error;
    }
    
    header("Location: manage_researches.php");
    exit();
}

// Pagination setup
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Get total count
$total_researches = $conn->query("SELECT COUNT(*) as count FROM researches")->fetch_assoc()['count'];
$total_pages = ceil($total_researches / $items_per_page);

// Fetch researches
$researches = $conn->query("SELECT * FROM researches ORDER BY created_at DESC LIMIT $offset, $items_per_page")->fetch_all(MYSQLI_ASSOC);
?>

<div class="content-wrapper" style="padding-top: 100px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Research Management</li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Research Management</h2>
                <p class="text-muted">Manage research projects and publications</p>
            </div>
            <a href="add_research.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Research
            </a>
        </div>

        <!-- Search Box -->
        <div class="mb-4">
            <input type="text" id="researchSearch" class="form-control" placeholder="Search research...">
        </div>

        <!-- Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Research Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($researches as $research): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($research['title']); ?></td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#researchModal<?php echo $research['id']; ?>">
                                    <?php echo substr(htmlspecialchars($research['description']), 0, 50) . '...'; ?>
                                </a>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($research['created_at'])); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="edit_research.php?id=<?php echo $research['id']; ?>" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm"
                                            onclick="confirmDelete(<?php echo $research['id']; ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Research Details Modal -->
                        <div class="modal fade" id="researchModal<?php echo $research['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><?php echo htmlspecialchars($research['title']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php if ($research['image_url']): ?>
                                            <img src="../static/images/researches/<?php echo htmlspecialchars($research['image_url']); ?>" 
                                                 class="img-fluid mb-3" alt="Research image">
                                        <?php endif; ?>
                                        <h6>Description:</h6>
                                        <p><?php echo nl2br(htmlspecialchars($research['description'])); ?></p>
                                        <h6>Details:</h6>
                                        <p><?php echo nl2br(htmlspecialchars($research['details'])); ?></p>
                                        <small class="text-muted">
                                            Created: <?php echo date('F j, Y, g:i a', strtotime($research['created_at'])); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Research pagination" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page-1; ?>">Previous</a>
                    </li>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page+1; ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete this research and its image!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = 'manage_researches.php';
            
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_id';
            input.value = id;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Search functionality
document.getElementById('researchSearch').addEventListener('keyup', function() {
    let searchText = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        let title = row.cells[0].textContent.toLowerCase();
        let description = row.cells[1].textContent.toLowerCase();
        
        if (title.includes(searchText) || description.includes(searchText)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<?php
$conn->close();
include 'footer.php';
?>