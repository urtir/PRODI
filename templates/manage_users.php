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

// Handle Role Toggle
if (isset($_POST['toggle_role'])) {
    $user_id = (int)$_POST['user_id'];
    $new_role = $_POST['new_role'];
    
    if ($user_id !== $_SESSION['user_id']) { // Prevent self-role change
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "User role updated successfully!";
        } else {
            $_SESSION['error_message'] = "Error updating role!";
        }
    } else {
        $_SESSION['error_message'] = "You cannot change your own role!";
    }
    header("Location: manage_users.php");
    exit();
}

// Handle Delete
if (isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    
    if ($delete_id !== $_SESSION['user_id']) { // Prevent self-deletion
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "User deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Error deleting user!";
        }
    } else {
        $_SESSION['error_message'] = "You cannot delete your own account!";
    }
    header("Location: manage_users.php");
    exit();
}

// Filter Setup
$role_filter = isset($_GET['role']) ? $_GET['role'] : 'all';
$where_clause = $role_filter != 'all' ? "WHERE role = '$role_filter'" : "";

// Pagination
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$total_users = $conn->query("SELECT COUNT(*) as count FROM users $where_clause")->fetch_assoc()['count'];
$total_pages = ceil($total_users / $items_per_page);

// Fetch Users
$users = $conn->query("
    SELECT * FROM users 
    $where_clause 
    ORDER BY created_at DESC 
    LIMIT $offset, $items_per_page
")->fetch_all(MYSQLI_ASSOC);
?>

<div class="content-wrapper" style="padding-top: 100px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Users Management</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Users Management</h2>
                <p class="text-muted">Manage system users and roles</p>
            </div>
            <a href="add_user.php" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Add New User
            </a>
        </div>

        <!-- Filters -->
        <div class="mb-4">
            <div class="btn-group">
                <a href="?role=all" class="btn btn-outline-primary <?php echo $role_filter == 'all' ? 'active' : ''; ?>">
                    All Users
                </a>
                <a href="?role=admin" class="btn btn-outline-primary <?php echo $role_filter == 'admin' ? 'active' : ''; ?>">
                    Admins
                </a>
                <a href="?role=user" class="btn btn-outline-primary <?php echo $role_filter == 'user' ? 'active' : ''; ?>">
                    Regular Users
                </a>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-4">
            <input type="text" id="userSearch" class="form-control" placeholder="Search users...">
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

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <input type="hidden" name="new_role" 
                                               value="<?php echo $user['role'] === 'admin' ? 'user' : 'admin'; ?>">
                                        <button type="submit" name="toggle_role" 
                                                class="btn btn-sm <?php echo $user['role'] === 'admin' ? 'btn-danger' : 'btn-success'; ?>">
                                            <?php echo ucfirst($user['role']); ?>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge bg-primary"><?php echo ucfirst($user['role']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(<?php echo $user['id']; ?>)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="User pagination" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page-1; ?>&role=<?php echo $role_filter; ?>">Previous</a>
                    </li>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&role=<?php echo $role_filter; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page+1; ?>&role=<?php echo $role_filter; ?>">Next</a>
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
        text: "This will permanently delete this user account!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = 'manage_users.php';
            
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
document.getElementById('userSearch').addEventListener('keyup', function() {
    let searchText = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        let username = row.cells[0].textContent.toLowerCase();
        let name = row.cells[1].textContent.toLowerCase();
        let email = row.cells[2].textContent.toLowerCase();
        
        if (username.includes(searchText) || 
            name.includes(searchText) || 
            email.includes(searchText)) {
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