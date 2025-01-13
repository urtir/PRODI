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

// Handle Delete
if (isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    
    // Get image URL before deletion
    $stmt = $conn->prepare("SELECT image_url FROM courses WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();
    
    // Delete from database
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        if ($course && !empty($course['image_url'])) {
            $image_path = "../static/images/courses/" . $course['image_url'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        $_SESSION['success_message'] = "Course deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting course: " . $conn->error;
    }
    
    header("Location: manage_courses.php");
    exit();
}

// Pagination
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$total_courses = $conn->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'];
$total_pages = ceil($total_courses / $items_per_page);

// Fetch courses
$courses = $conn->query("SELECT * FROM courses ORDER BY semester, code LIMIT $offset, $items_per_page")->fetch_all(MYSQLI_ASSOC);
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content-wrapper" style="padding-top: 100px;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Courses Management</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Courses Management</h2>
                <p class="text-muted">Manage department courses</p>
            </div>
            <a href="add_course.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Course
            </a>
        </div>

        <!-- Search Box -->
        <div class="mb-4">
            <input type="text" id="courseSearch" class="form-control" placeholder="Search courses...">
        </div>

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

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Credits</th>
                        <th>Semester</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['code']); ?></td>
                            <td><?php echo htmlspecialchars($course['name']); ?></td>
                            <td><?php echo htmlspecialchars($course['credits']); ?></td>
                            <td><?php echo htmlspecialchars($course['semester']); ?></td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#courseModal<?php echo $course['id']; ?>">
                                    <?php echo substr(htmlspecialchars($course['description']), 0, 50) . '...'; ?>
                                </a>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="edit_course.php?id=<?php echo $course['id']; ?>" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm"
                                            onclick="confirmDelete(<?php echo $course['id']; ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Course Details Modal -->
                        <div class="modal fade" id="courseModal<?php echo $course['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><?php echo htmlspecialchars($course['name']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <?php if ($course['image_url']): ?>
                                            <div class="col-md-4">
                                                <img src="../static/images/courses/<?php echo htmlspecialchars($course['image_url']); ?>" 
                                                     class="img-fluid rounded" alt="Course image">
                                            </div>
                                            <?php endif; ?>
                                            <div class="<?php echo $course['image_url'] ? 'col-md-8' : 'col-md-12'; ?>">
                                                <p><strong>Course Code:</strong> <?php echo htmlspecialchars($course['code']); ?></p>
                                                <p><strong>Credits:</strong> <?php echo htmlspecialchars($course['credits']); ?></p>
                                                <p><strong>Semester:</strong> <?php echo htmlspecialchars($course['semester']); ?></p>
                                                <p><strong>Description:</strong></p>
                                                <p><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Course pagination" class="mt-4">
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

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = 'manage_courses.php';
            
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
document.getElementById('courseSearch').addEventListener('keyup', function() {
    let searchText = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        let code = row.cells[0].textContent.toLowerCase();
        let name = row.cells[1].textContent.toLowerCase();
        let semester = row.cells[3].textContent.toLowerCase();
        
        row.style.display = (
            code.includes(searchText) || 
            name.includes(searchText) || 
            semester.includes(searchText)
        ) ? '' : 'none';
    });
});
</script>

<?php
$conn->close();
include 'footer.php';
?>