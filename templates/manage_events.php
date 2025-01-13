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
    
    // Get image URLs before deletion for cleanup
    $stmt = $conn->prepare("SELECT image_url, image_url2, image_url3 FROM events WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    
    // Delete from database
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        // Delete associated images
        if ($event) {
            $image_fields = ['image_url', 'image_url2', 'image_url3'];
            foreach ($image_fields as $field) {
                if (!empty($event[$field])) {
                    $image_path = "../static/images/events/" . $event[$field];
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }
            }
        }
        $_SESSION['success_message'] = "Event deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting event: " . $conn->error;
    }
    
    header("Location: manage_events.php");
    exit();
}

// Pagination setup
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Get total count and pages
$total_events = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];
$total_pages = ceil($total_events / $items_per_page);

// Fetch events
$events = $conn->query("SELECT * FROM events ORDER BY date DESC LIMIT $offset, $items_per_page")->fetch_all(MYSQLI_ASSOC);
?>


<div class="content-wrapper" style="padding-top: 100px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Events Management</li>
            </ol>
        </nav>

        <!-- Header Section with Fixed Position Consideration -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Events Management</h2>
                <p class="text-muted">Manage department events and announcements</p>
            </div>
            <a href="add_event.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Event
            </a>
        </div>

        <!-- Search Box -->
        <div class="mb-4">
            <input type="text" id="eventSearch" class="form-control" placeholder="Search events...">
        </div>

            <!-- Add message alerts at top of content -->
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
        
    <!-- Events Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Images</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['title']); ?></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#descModal<?php echo $event['id']; ?>">
                                <?php echo substr(htmlspecialchars($event['description']), 0, 50) . '...'; ?>
                            </a>
                        </td>
                        <td><?php echo date('Y-m-d', strtotime($event['date'])); ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <?php
                                $images = [$event['image_url'], $event['image_url2'], $event['image_url3']];
                                foreach ($images as $index => $img):
                                    if ($img):
                                        $img_path = "../static/images/events/" . $img;
                                        if (file_exists($img_path)):
                                ?>
                                            <img src="<?php echo $img_path; ?>" 
                                                 class="img-thumbnail" 
                                                 width="50" 
                                                 height="50"
                                                 style="cursor: pointer;"
                                                 onclick="openImageModal('<?php echo $img_path; ?>', '<?php echo htmlspecialchars($event['title']); ?>')"
                                                 alt="Event image <?php echo $index + 1; ?>">
                                <?php
                                        endif;
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        </td>
                        <td><?php echo date('Y-m-d H:i', strtotime($event['created_at'])); ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="edit_event.php?id=<?php echo $event['id']; ?>" 
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" 
                                        class="btn btn-danger btn-sm"
                                        onclick="confirmDelete(<?php echo $event['id']; ?>)">
                                <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Description Modal -->
                    <div class="modal fade" id="descModal<?php echo $event['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <h6>Description:</h6>
                                    <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                                    <h6>Long Description:</h6>
                                    <p><?php echo nl2br(htmlspecialchars($event['long_description'])); ?></p>
                                    
                                    <!-- Images Gallery -->
                                    <h6 class="mt-4">Event Images:</h6>
                                    <div class="row">
                                        <?php foreach ($images as $index => $img):
                                            if ($img):
                                                $img_path = "../static/images/events/" . $img;
                                                if (file_exists($img_path)):
                                        ?>
                                                <div class="col-md-4 mb-3">
                                                    <img src="<?php echo $img_path; ?>" 
                                                         class="img-fluid img-thumbnail" 
                                                         style="cursor: pointer;"
                                                         onclick="openImageModal('<?php echo $img_path; ?>', '<?php echo htmlspecialchars($event['title']); ?>')"
                                                         alt="Event image <?php echo $index + 1; ?>">
                                                </div>
                                        <?php
                                                endif;
                                            endif;
                                        endforeach; ?>
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
    <nav aria-label="Event pagination" class="mt-4">
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

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Event image">
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

function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete this event and all associated images!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = 'manage_events.php';
            
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
document.getElementById('eventSearch').addEventListener('keyup', function() {
    let searchText = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        let title = row.cells[0].textContent.toLowerCase();
        let description = row.cells[1].textContent.toLowerCase();
        row.style.display = (title.includes(searchText) || description.includes(searchText)) ? '' : 'none';
    });
});
</script>

<?php
$conn->close();
include 'footer.php';
?>