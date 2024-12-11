<?php 
include 'header.php'; 

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch courses
$courses_query = "SELECT * FROM courses ORDER BY semester ASC";
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
    <?php foreach ($courses as $course): ?>
    <div class="col-md-6">
        <a href="course_detail.php?id=<?php echo $course['id']; ?>" class="course_box">
            <img src="../static/images/courses/<?php echo htmlspecialchars($course['image_url']); ?>" 
                 class="img-fluid" 
                 alt="<?php echo htmlspecialchars($course['name']); ?>"
                 onerror="this.src='../static/images/courses/default-course.jpg'">
            <div class="couse-desc-wrap">
                <h4><?php echo htmlspecialchars($course['name']); ?></h4>
                <p><?php echo (strlen($course['description']) > 150) ? substr(htmlspecialchars($course['description']), 0, 150) . '...' : htmlspecialchars($course['description']); ?></p>
                <div class="course_duration mt-3">
                <ul class="course-meta" style="display: flex; justify-content: space-between; width: 100%; padding: 0;">
                    <li style="text-align: center;">
                        <p>Semester</p>
                        <span><?php echo htmlspecialchars($course['semester']); ?></span>
                    </li>
                    <li style=" text-align: center;">
                        <p>SKS</p>
                        <span><?php echo htmlspecialchars($course['credits']); ?></span>
                    </li>
                    <li style="text-align: center;">
                        <p>Kode</p>
                        <span><?php echo htmlspecialchars($course['code']); ?></span>
                    </li>
                </ul>
            </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
<?php $conn->close(); ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>

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