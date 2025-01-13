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

// Handle Status Toggle
if (isset($_POST['toggle_status'])) {
    $msg_id = (int)$_POST['message_id'];
    $new_status = $_POST['new_status'];
    
    $stmt = $conn->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $msg_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Message status updated!";
    } else {
        $_SESSION['error_message'] = "Error updating status!";
    }
    header("Location: manage_messages.php");
    exit();
}

// Handle Delete
if (isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Message deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting message!";
    }
    header("Location: manage_messages.php");
    exit();
}

// Filter Setup
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$where_clause = $status_filter != 'all' ? "WHERE status = '$status_filter'" : "";

// Pagination
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$total_messages = $conn->query("SELECT COUNT(*) as count FROM contact_messages $where_clause")->fetch_assoc()['count'];
$total_pages = ceil($total_messages / $items_per_page);

// Fetch Messages
$messages = $conn->query("
    SELECT * FROM contact_messages 
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
                <li class="breadcrumb-item active">Messages Management</li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Contact Messages</h2>
                <p class="text-muted">Manage incoming contact messages</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-4">
            <div class="btn-group">
                <a href="?status=all" class="btn btn-outline-primary <?php echo $status_filter == 'all' ? 'active' : ''; ?>">
                    All Messages
                </a>
                <a href="?status=unread" class="btn btn-outline-primary <?php echo $status_filter == 'unread' ? 'active' : ''; ?>">
                    Unread
                </a>
                <a href="?status=read" class="btn btn-outline-primary <?php echo $status_filter == 'read' ? 'active' : ''; ?>">
                    Read
                </a>
            </div>
        </div>

        <!-- Search Box -->
        <div class="mb-4">
            <input type="text" id="messageSearch" class="form-control" placeholder="Search messages...">
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

        <!-- Messages Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Status</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message): ?>
                        <tr class="<?php echo $message['status'] == 'unread' ? 'table-active' : ''; ?>">
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                    <input type="hidden" name="new_status" 
                                           value="<?php echo $message['status'] == 'unread' ? 'read' : 'unread'; ?>">
                                    <button type="submit" name="toggle_status" class="btn btn-sm btn-link p-0">
                                        <?php if ($message['status'] == 'unread'): ?>
                                            <i class="fas fa-envelope text-warning"></i>
                                        <?php else: ?>
                                            <i class="fas fa-envelope-open text-muted"></i>
                                        <?php endif; ?>
                                    </button>
                                </form>
                            </td>
                            <td><?php echo htmlspecialchars($message['name']); ?></td>
                            <td><?php echo htmlspecialchars($message['email']); ?></td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#messageModal<?php echo $message['id']; ?>">
                                    <?php echo htmlspecialchars($message['subject']); ?>
                                </a>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($message['created_at'])); ?></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete(<?php echo $message['id']; ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>

                        <!-- Message Modal -->
                        <div class="modal fade" id="messageModal<?php echo $message['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><?php echo htmlspecialchars($message['subject']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>From:</strong> <?php echo htmlspecialchars($message['name']); ?> 
                                           (<?php echo htmlspecialchars($message['email']); ?>)</p>
                                        <hr>
                                        <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                                        <hr>
                                        <small class="text-muted">
                                            Received: <?php echo date('F j, Y, g:i a', strtotime($message['created_at'])); ?>
                                        </small>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <a href="mailto:<?php echo $message['email']; ?>" class="btn btn-primary">
                                            <i class="fas fa-reply"></i> Reply
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Message pagination" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page-1; ?>&status=<?php echo $status_filter; ?>">Previous</a>
                    </li>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo $status_filter; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page+1; ?>&status=<?php echo $status_filter; ?>">Next</a>
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
        text: "This will permanently delete this message!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = 'manage_messages.php';
            
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
document.getElementById('messageSearch').addEventListener('keyup', function() {
    let searchText = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        let name = row.cells[1].textContent.toLowerCase();
        let email = row.cells[2].textContent.toLowerCase();
        let subject = row.cells[3].textContent.toLowerCase();
        
        if (name.includes(searchText) || 
            email.includes(searchText) || 
            subject.includes(searchText)) {
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