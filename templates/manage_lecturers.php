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
    $stmt = $conn->prepare("SELECT image_url FROM lecturers WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $lecturer = $result->fetch_assoc();
    
    // Delete from database
    $stmt = $conn->prepare("DELETE FROM lecturers WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        // Delete associated image
        if ($lecturer && !empty($lecturer['image_url'])) {
            $image_path = "../static/images/lecturers/" . $lecturer['image_url'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        $_SESSION['success_message'] = "Lecturer deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting lecturer: " . $conn->error;
    }
    
    header("Location: manage_lecturers.php");
    exit();
}

// Pagination setup
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Get total count
$total_lecturers = $conn->query("SELECT COUNT(*) as count FROM lecturers")->fetch_assoc()['count'];
$total_pages = ceil($total_lecturers / $items_per_page);

// Fetch lecturers
$lecturers = $conn->query("SELECT * FROM lecturers ORDER BY name ASC LIMIT $offset, $items_per_page")->fetch_all(MYSQLI_ASSOC);
?>


<div class="content-wrapper" style="padding-top: 100px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Lecturers Management</li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Lecturers Management</h2>
                <p class="text-muted">Manage faculty members and their profiles</p>
            </div>
            <a href="add_lecturer.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Lecturer
            </a>
        </div>

        <!-- Search Box -->
        <div class="mb-4">
            <input type="text" id="lecturerSearch" class="form-control" placeholder="Search lecturers...">
        </div>

                <!-- Add message alerts -->
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

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Lecturers Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Specialization</th>
                        <th>Email</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lecturers as $lecturer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($lecturer['name']); ?></td>
                            <td><?php echo htmlspecialchars($lecturer['title']); ?></td>
                            <td><?php echo htmlspecialchars($lecturer['specialization']); ?></td>
                            <td><?php echo htmlspecialchars($lecturer['email']); ?></td>
                            <td>
                                <?php if ($lecturer['image_url']): ?>
                                    <?php 
                                    $img_path = "../static/images/lecturers/" . $lecturer['image_url'];
                                    if (file_exists($img_path)):
                                    ?>
                                        <img src="<?php echo $img_path; ?>" 
                                             class="img-thumbnail" 
                                             width="50" 
                                             height="50"
                                             style="cursor: pointer;"
                                             onclick="openImageModal('<?php echo $img_path; ?>', '<?php echo htmlspecialchars($lecturer['name']); ?>')"
                                             alt="Lecturer image">
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Lecturer actions">
                                    <a href="edit_lecturer.php?id=<?php echo $lecturer['id']; ?>" 
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="tooltip"
                                    title="Edit Lecturer">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm"
                                            data-bs-toggle="tooltip"
                                            title="Delete Lecturer"
                                            onclick="confirmDelete(<?php echo $lecturer['id']; ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Lecturer pagination" class="mt-4">
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

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Lecturer image">
            </div>
        </div>
    </div>
</div>

 

<script>
function openImageModal(imgPath, title) {
    document.getElementById('modalImage').src = imgPath;
    const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
    modal.show();
}

function viewDetails(id) {
    const modal = new bootstrap.Modal(document.getElementById('lecturerDetailsModal'));
    modal.show();
    fetch(`get_lecturer_details.php?id=${id}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('lecturerDetailsContent').innerHTML = data;
        });
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete this lecturer and their image!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = 'manage_lecturers.php';
            
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

document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});

// Search functionality
document.getElementById('lecturerSearch').addEventListener('keyup', function() {
    let searchText = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        let name = row.cells[0].textContent.toLowerCase();
        let title = row.cells[1].textContent.toLowerCase();
        let specialization = row.cells[2].textContent.toLowerCase();
        let email = row.cells[3].textContent.toLowerCase();
        
        row.style.display = (
            name.includes(searchText) || 
            title.includes(searchText) || 
            specialization.includes(searchText) || 
            email.includes(searchText)
        ) ? '' : 'none';
    });
});
</script>

<?php
$conn->close();
include 'footer.php';
?>