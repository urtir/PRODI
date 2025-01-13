<?php 
include 'header.php'; 

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination
$courses_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $courses_per_page;

// Count total courses for pagination
$total_courses = $conn->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'];
$total_pages = ceil($total_courses / $courses_per_page);

// Fetch courses with pagination
$courses_query = "SELECT * FROM courses ORDER BY semester ASC LIMIT $start, $courses_per_page";
$courses_result = $conn->query($courses_query);
$courses = $courses_result->fetch_all(MYSQLI_ASSOC);
?>

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
        .description-hover {
            position: relative;
            cursor: pointer;
        }

        .description-hover .full-description {
            display: none;
            position: relative;
            width: 100%;
            background: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .description-hover.active .full-description {
            display: block;
        }

        @media (max-width: 768px) {
            .description-hover .full-description {
                width: 100%;
            }
        }

        .table-header {
            background-color: #f8f9fa;
        }

        .table-separator {
            background-color: transparent;
        }
    </style>
</head>

<body>

<section class="admission_cources">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h2>Mata Kuliah</h2>
                <p>Program Studi Informatika menawarkan berbagai mata kuliah yang dirancang untuk mempersiapkan mahasiswa menghadapi dunia teknologi informasi.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th>Code</th>
                            <th>SKS</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $current_semester = null;
                        foreach ($courses as $course): 
                            if ($current_semester !== $course['semester']):
                                if ($current_semester !== null): ?>
                                    <tr class="table-separator">
                                        <td colspan="4"></td>
                                    </tr>
                                <?php endif; 
                                $current_semester = $course['semester']; ?>
                                <tr class="table-header">
                                    <td colspan="4"><h3>Semester <?php echo $current_semester; ?></h3></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td><?php echo htmlspecialchars($course['name']); ?></td>
                                <td><?php echo htmlspecialchars($course['code']); ?></td>
                                <td><?php echo htmlspecialchars($course['credits']); ?></td>
                                <td class="description-hover">
                                    <span class="short-description">
                                        <?php echo strlen($course['description']) > 100 ? substr(htmlspecialchars($course['description']), 0, 100) . '...' : htmlspecialchars($course['description']); ?>
                                    </span>
                                    <?php if (strlen($course['description']) > 100): ?>
                                        <span class="full-description">
                                            <?php echo htmlspecialchars($course['description']); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

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
    </div>
</section>

<?php 
$conn->close();
include 'footer.php'; 
?>

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
<script>
    $(document).ready(function() {
        $('.description-hover').on('click', function() {
            $(this).toggleClass('active');
        });
    });
</script>

</body>
</html>