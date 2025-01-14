<?php
include 'header.php';

$conn = new mysqli("localhost", "root", "", "informatics_db");

// Pagination setup
$items_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Get total FAQs count
$total_query = "SELECT COUNT(*) as count FROM faqs";
$total_result = $conn->query($total_query);
$total_faqs = $total_result->fetch_assoc()['count'];
$total_pages = ceil($total_faqs / $items_per_page);

// Get FAQs with pagination
$faqs = $conn->query("
    SELECT f.*, u.firstname, u.lastname 
    FROM faqs f
    LEFT JOIN users u ON f.user_id = u.id
    ORDER BY f.created_at DESC
    LIMIT $items_per_page OFFSET $offset
")->fetch_all(MYSQLI_ASSOC);

// Search functionality
$search = '';
$where = '1';
if (isset($_SESSION['user_id']) && isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $where = "(p.title LIKE '%$search%' OR p.content LIKE '%$search%')";
}

// Pagination
$posts_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $posts_per_page;

// Count total posts for pagination
$total_posts = $conn->query("SELECT COUNT(*) as count FROM posts p WHERE $where")->fetch_assoc()['count'];
$total_pages = ceil($total_posts / $posts_per_page);

// Fetch posts with pagination
$posts = $conn->query("
    SELECT p.*, 
           u.firstname, u.lastname,
           COUNT(c.id) as comment_count
    FROM posts p
    LEFT JOIN users u ON p.user_id = u.id
    LEFT JOIN comments c ON p.id = c.post_id
    WHERE $where
    GROUP BY p.id
    ORDER BY p.created_at DESC
    LIMIT $start, $posts_per_page
");

?>

<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admission - Unisco - Education Website Template for University, College, School</title>
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
    <style>

    .search-container {
        width: 100%;
        margin: 20px 0;
    }

    .search-input-group {
        display: flex;
        width: 100%;
        gap: 10px;
    }

    .search-input-group input {
        flex: 1;
    }

    .search-input-group button {
        width: 120px;
    }

    .pagination .page-link {
        color: #3366CC;
    }

    .pagination .active .page-link {
        background-color: #3366CC;
        border-color: #3366CC;
        color: white;
    }

    .search-form .input-group {
        width: 100%;
    }

    </style>
</head>

<div class="contact">
        <div class="row">
            <div class="col-md-12 text-center">
            <h2 class="event-title">Forum Buletin Diskusi</h2>
            <p>Selamat datang di forum diskusi Program Studi Informatika. Di sini Anda dapat berbagi informasi, bertanya, dan berdiskusi dengan civitas akademika.</p>
            </div>
        </div>
    <div class="container w-75  ">
        <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="search-container">
                    <form method="GET" class="w-100">
                        <div class="search-input-group">
                            <input type="text" 
                                class="form-control" 
                                name="search" 
                                value="<?php echo htmlspecialchars($search); ?>" 
                                placeholder="Search posts...">
                            <button class="btn btn-warning" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

        <!-- Posts List -->
        <div class="row">
            <div class="col-md-12">
                <?php while ($post = $posts->fetch_assoc()): ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h4>
                            <h6 class="card-subtitle mb-2 text-muted">
                                Posted by <?php echo htmlspecialchars($post['firstname'] . ' ' . $post['lastname']); ?>
                                on <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                            </h6>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                            
                            <!-- Comments Section -->
                            <?php
                            $comments = $conn->query("
                                SELECT c.*, u.firstname, u.lastname
                                FROM comments c
                                JOIN users u ON c.user_id = u.id
                                WHERE c.post_id = {$post['id']}
                                ORDER BY c.created_at
                            ");
                            ?>
                            
                            <div class="comments-section mt-4">
                                <h6>Comments (<?php echo $post['comment_count']; ?>)</h6>
                                <?php while ($comment = $comments->fetch_assoc()): ?>
                                    <div class="comment p-2 mb-2 bg-light">
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars($comment['firstname'] . ' ' . $comment['lastname']); ?>:
                                        </small>
                                        <p class="mb-1"><?php echo htmlspecialchars($comment['content']); ?></p>
                                        <small class="text-muted">
                                            <?php echo date('M d, Y g:i a', strtotime($comment['created_at'])); ?>
                                        </small>
                                    </div>
                                <?php endwhile; ?>

                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <form method="POST" class="mt-3">
                                        <input type="hidden" name="new_comment" value="1">
                                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                        <div class="form-group">
                                            <textarea class="form-control" name="comment_content" rows="2" placeholder="Write a comment..." required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-warning">Comment</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="row">
            <div class="col-md-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo ($page-1); ?><?php echo $search ? '&search='.urlencode($search) : ''; ?>">Previous</a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search ? '&search='.urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo ($page+1); ?><?php echo $search ? '&search='.urlencode($search) : ''; ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>



<?php 
$conn->close();
include 'footer.php'; 
?>

<!--//END FOOTER -->
        <!-- jQuery, Bootstrap JS. -->
        <script src="../static/js/jquery.min.js"></script>
        <script src="../static/js/tether.min.js"></script>
        <script src="../static/js/bootstrap.min.js"></script>
        <!-- Plugins -->
        <script src="../static/js/instafeed.min.js"></script>
        <script src="../static/js/owl.carousel.min.js"></script>
        <script src="../static/js/validate.js"></script>
        <script src="../static/js/tweetie.min.js"></script>
        <!-- Subscribe -->
        <script src="../static/js/subscribe.js"></script>
        <!-- Script JS -->
        <script src="../static/js/script.js"></script>
        
</body>
</html>