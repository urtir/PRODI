<?php
include 'header.php';

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch researches
$sql = "SELECT * FROM researches ORDER BY created_at DESC";
$result = $conn->query($sql);
$researches = $result->fetch_all(MYSQLI_ASSOC);
?>

<!-- CSS Styles -->
<style>
:root {
    --dark-blue: #0A2647;
    --medium-blue: #144272;
    --light-blue: #205295;  
    --highlight: #2C74B3;
}

.hero-section {
    background: linear-gradient(rgba(10, 38, 71, 0.92), rgba(20, 66, 114, 0.92)),
                url('../static/images/research-bg.jpg');
    background-size: cover;
    background-attachment: fixed;
    min-height: 60vh;
    display: flex;
    align-items: center;
}

.research-card {
    background:rgb(225, 231, 242);
    transition: transform 0.3s ease;
    border: none;
}

.research-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(10, 38, 71, 0.25) !important;
}

.modal-content {
    background:rgb(255, 255, 255);
    border: 1px solid var(--light-blue);
}

.modal-header {
    background: var(--dark-blue);
    border-bottom: 1px solid var(--light-blue);
}

.modal-footer {
    border-top: 1px solid var(--light-blue);
}
</style>

<!-- Research Grid -->
<div class="container py-5">
    <h2 class="text-center mb-5 fw-bold pt-100" style="color: var(--dark-blue)">Our Research Projects</h2>
    
    <div class="row g-4">
        <?php foreach ($researches as $research): ?>
        <div class="col-md-6 col-lg-4">
            <div class="research-card card h-100">
                <div class="position-relative">
                    <img src="../static/images/research/<?php echo htmlspecialchars($research['image_url']); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($research['title']); ?>"
                         style="height: 200px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 p-2">
                        <span class="badge" style="background-color: var(--dark-blue)">
                            <?php echo date('M Y', strtotime($research['created_at'])); ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-navy"><?php echo htmlspecialchars($research['title']); ?></h5>
                    <p class="card-text text-black-50">
                        <?php echo substr(htmlspecialchars($research['description']), 0, 100); ?>...
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <button class="btn w-100" 
                            style="background: var(--light-blue); color: white;"
                            data-bs-toggle="modal" 
                            data-bs-target="#research-<?php echo $research['id']; ?>">
                        Read More
                    </button>
                </div>
            </div>
        </div>

        <!-- Research Modal -->
        <div class="modal fade" id="research-<?php echo $research['id']; ?>" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-white">
                            <?php echo htmlspecialchars($research['title']); ?>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <img src="../static/images/research/<?php echo htmlspecialchars($research['image_url']); ?>" 
                             class="img-fluid rounded mb-4 w-100" 
                             style="max-height: 400px; object-fit: cover;">
                        
                        <div class="text-black">
                            <h6 class="text-white-50 mb-3">Description</h6>
                            <p><?php echo htmlspecialchars($research['description']); ?></p>
                            
                            <h6 class="text-white-50 mb-3 mt-4">Details</h6>
                            <div class="p-3 rounded" style="background: rgba(32, 82, 149, 0.1);">
                                <?php echo nl2br(htmlspecialchars($research['details'])); ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" 
                                style="background: var(--light-blue); color: white;"
                                data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>