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
    $stmt = $conn->prepare("DELETE FROM awards WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Award deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting award: " . $conn->error;
    }
    
    header("Location: manage_awards.php");
    exit();
}

// Display Messages
if (isset($_SESSION['success_message'])) {
    echo "<div class='alert alert-success alert-dismissible fade show'>" . 
         $_SESSION['success_message'] . 
         "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger alert-dismissible fade show'>" . 
         $_SESSION['error_message'] . 
         "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    unset($_SESSION['error_message']);
}
// Pagination
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Get total count
$total_awards = $conn->query("SELECT COUNT(*) as count FROM awards")->fetch_assoc()['count'];
$total_pages = ceil($total_awards / $items_per_page);



// Fetch awards
$awards = $conn->query("SELECT * FROM awards ORDER BY year DESC, id DESC LIMIT $offset, $items_per_page")->fetch_all(MYSQLI_ASSOC);
?>

<!-- Add at the beginning of the content section -->
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
<div class="content-wrapper" style="padding-top: 100px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Awards Management</li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Awards Management</h2>
                <p class="text-muted">Manage department awards and achievements</p>
            </div>
            <a href="add_award.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Award
            </a>
        </div>

        <!-- Search Box -->
        <div class="mb-4">
            <input type="text" id="awardSearch" class="form-control" placeholder="Search awards...">
        </div>

        <!-- Awards Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Recipient</th>
                        <th>Year</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($awards as $award): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($award['title']); ?></td>
                            <td><?php echo htmlspecialchars($award['recipient']); ?></td>
                            <td><?php echo htmlspecialchars($award['year']); ?></td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#awardModal<?php echo $award['id']; ?>">
                                    <?php echo substr(htmlspecialchars($award['description']), 0, 50) . '...'; ?>
                                </a>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="edit_award.php?id=<?php echo $award['id']; ?>" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm"
                                            onclick="confirmDelete(<?php echo $award['id']; ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                    
                                </div>
                            </td>
                        </tr>

                        <!-- Award Details Modal -->
                        <div class="modal fade" id="awardModal<?php echo $award['id']; ?>">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><?php echo htmlspecialchars($award['title']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Recipient:</strong> <?php echo htmlspecialchars($award['recipient']); ?></p>
                                        <p><strong>Year:</strong> <?php echo htmlspecialchars($award['year']); ?></p>
                                        <p><strong>Description:</strong></p>
                                        <p><?php echo nl2br(htmlspecialchars($award['description'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Award pagination" class="mt-4">
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
            form.action = 'manage_awards.php';
            
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
document.getElementById('awardSearch').addEventListener('keyup', function() {
    let searchText = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        let title = row.cells[0].textContent.toLowerCase();
        let recipient = row.cells[1].textContent.toLowerCase();
        let year = row.cells[2].textContent.toLowerCase();
        let description = row.cells[3].textContent.toLowerCase();
        
        row.style.display = (
            title.includes(searchText) || 
            recipient.includes(searchText) || 
            year.includes(searchText) || 
            description.includes(searchText)
        ) ? '' : 'none';
    });
});
</script>

<?php
$conn->close();
include 'footer.php';
?>