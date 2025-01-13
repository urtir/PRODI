<?php 
include 'header.php'; 

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination
$researches_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $researches_per_page;

// Count total researches for pagination
$total_researches = $conn->query("SELECT COUNT(*) as count FROM researches")->fetch_assoc()['count'];
$total_pages = ceil($total_researches / $researches_per_page);

// Query untuk mengambil data research dengan pagination
$sql_research = "SELECT * FROM researches LIMIT $start, $researches_per_page";
$result_research = $conn->query($sql_research);

// Query untuk mengambil data research news
$sql_news = "SELECT * FROM research_news"; // Mengambil data dari tabel `research_news`
$result_news = $conn->query($sql_news);

// Query untuk mengambil data resources
$sql_resources = "SELECT * FROM resources"; // Mengambil data dari tabel `resources`
$result_resources = $conn->query($sql_resources);

// Mengambil data ke dalam array asosiatif
$researches = $result_research->fetch_all(MYSQLI_ASSOC);
$research_news = $result_news->fetch_all(MYSQLI_ASSOC);
$resources = $result_resources->fetch_all(MYSQLI_ASSOC);

?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Research - Informatics Department</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../static/css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../static/css/font-awesome.min.css">
    <!-- Simple Line Font -->
    <link rel="stylesheet" href="../static/css/simple-line-icons.css">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="../static/css/owl.carousel.min.css">
    <!-- Main CSS -->
    <link href="../static/css/style.css" rel="stylesheet">
</head>

<body>
<section class="research">
        <div class="container">
            <div class="row">
                <!-- Current Research -->
                <div class="col-md-8">
                    <h2>Current Research</h2>
                    <?php foreach ($researches as $research): ?>
                    <div class="research-current_block">
                        <img src="../static/images/research/<?php echo htmlspecialchars($research['image_url']) ? htmlspecialchars($research['image_url']) : 'research-img.jpg'; ?>" class="img-fluid research-img" alt="research-img">
                        <div class="research-description">
                            <h4><?php echo htmlspecialchars($research['title']); ?></h4>
                            <p><?php echo strlen($research['description']) > 150 ? substr($research['description'], 0, 150) . '...' : htmlspecialchars($research['description']); ?></p>
                            <?php if ($research['details']): ?>
                            <ul class="research-list">
                                <?php foreach (explode(',', $research['details']) as $detail): ?>
                                    <li><?php echo htmlspecialchars($detail); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                            <!-- Admin Controls -->
                            <?php if (isset($_SESSION['admin_id'])): ?>
                            <div class="admin-controls">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editResearchModal<?php echo $research['id']; ?>">Edit</button>
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#uploadResearchModal<?php echo $research['id']; ?>">Upload</button>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#removeResearchModal<?php echo $research['id']; ?>">Remove</button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Edit Research Modal -->
                    <div class="modal fade" id="editResearchModal<?php echo $research['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editResearchModalLabel<?php echo $research['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editResearchModalLabel<?php echo $research['id']; ?>">Edit Research</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="admin_research_action.php" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id" value="<?php echo $research['id']; ?>">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($research['title']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($research['description']); ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input type="file" class="form-control" name="image">
                                            <small>Current Image: <?php echo htmlspecialchars($research['image_url']); ?></small>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Research Modal -->
                    <div class="modal fade" id="uploadResearchModal<?php echo $research['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="uploadResearchModalLabel<?php echo $research['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="uploadResearchModalLabel<?php echo $research['id']; ?>">Upload Research</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="admin_research_action.php" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="upload">
                                        <input type="hidden" name="id" value="<?php echo $research['id']; ?>">
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input type="file" class="form-control" name="image" required>
                                        </div>
                                        <button type="submit" class="btn btn-success">Upload</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Remove Research Modal -->
                    <div class="modal fade" id="removeResearchModal<?php echo $research['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="removeResearchModalLabel<?php echo $research['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="removeResearchModalLabel<?php echo $research['id']; ?>">Remove Research</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to remove this research?
                                </div>
                                <div class="modal-footer">
                                    <form method="POST" action="admin_research_action.php">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="id" value="<?php echo $research['id']; ?>">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Research News and Video -->
                <div class="col-md-4">
                    <div class="row">
                        <!-- Research News -->
                        <div class="col-12">
                            <h3>Research News</h3>
                            <div class="research-posts">
                                <?php foreach ($research_news as $news): ?>
                                <div class="research-news_block">
                                    <span><?php echo htmlspecialchars($news['DATE']); ?></span> <!-- Date -->
                                    <p><?php echo htmlspecialchars($news['TEXT']); ?></p> <!-- Text -->
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Video -->
                        <div class="col-12 mt-3">
                            <div class="embed-responsive embed-responsive-4by3">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/IOk5yLzAo70?si=YfENh4BV_6bfJvVe" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>    

    <!-- Pagination -->
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo ($page-1); ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo ($page+1); ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>

</body>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php 
$conn->close();
include 'footer.php'; 
?>

<style>
.research-posts {
    max-height: 400px;
    overflow-y: scroll;
    padding-right: 10px;
}

.carousel-indicators li {
    background-color: #007bff;
}

.carousel-indicators .active {
    background-color: #ff5722;
}

.research-current_block {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
}

.research-current_block img {
    max-width: 150px;
    height: auto;
    margin-right: 15px;
    border-radius: 5px;
}

.research-current_block h4 {
    margin: 0 0 10px 0;
    font-size: 18px;
}

.research-current_block p {
    margin: 0;
    font-size: 14px;
    line-height: 1.5;
}

.research-list {
    margin-top: 10px;
    padding-left: 20px;
    list-style-type: disc;
}

.admin-controls {
    margin-top: 10px;
}

.admin-controls .btn {
    margin-right: 5px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .research-current_block {
        flex-direction: column;
        align-items: center;
    }

    .research-current_block img {
        margin-right: 0;
        margin-bottom: 15px;
        max-width: 100%;
    }

    .research-description {
        text-align: center;
    }
}
</style>
