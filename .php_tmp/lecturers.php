<?php

include 'header.php'; 

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch lecturers
$lecturers_query = "SELECT * FROM lecturers";
$lecturers_result = $conn->query($lecturers_query);
$lecturers = $lecturers_result->fetch_all(MYSQLI_ASSOC);
?>

<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>About - Unisco - Education Website Template for University, College, School</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../static/css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../static/css/font-awesome.min.css">
    <!-- Simple Line Font -->
    <link rel="stylesheet" href="../static/css/simple-line-icons.css">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="../static/css/slick.css">
    <link rel="stylesheet" href="../static/css/slick-theme.css">
    <link rel="stylesheet" href="../static/css/owl.carousel.min.css">
    <!-- Main CSS -->
    <link href="../static/css/style.css" rel="stylesheet">
</head>

<body>
    

<section class="our-teachers">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-5">Style One</h2>
            </div>
        </div>
        <div class="row">
            <?php foreach ($lecturers as $lecturer): ?>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="our-teachers-block">
                    <img src="../static/images/lecturers/<?php echo !empty($lecturer['image_url']) ? htmlspecialchars($lecturer['image_url']) : 'default-lecturer.jpg'; ?>" 
                         class="img-fluid teachers-img" 
                         alt="#"
                         onerror="this.src='../static/images/lecturers/default-lecturer.jpg'">
                    <div class="teachers-description">
                        <p><strong><?php echo htmlspecialchars($lecturer['name']); ?></strong>
                            <br> <?php echo htmlspecialchars($lecturer['education']); ?>
                        </p>
                        <hr>
                        <p>Syllabus : <span><?php echo htmlspecialchars($lecturer['specialization']); ?></span></p>
                        <div class="social-icons">
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                <?php if (!empty($lecturer['social_linkedin'])): ?>
                                <li><a href="<?php echo htmlspecialchars($lecturer['social_linkedin']); ?>"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- End row -->
    </div>
</section>


<section class="our-teachers">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-5">Style Two</h2>
            </div>
        </div>
        <div class="row">
            <?php foreach ($lecturers as $lecturer): ?>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="admission_insruction">
                    <img src="../static/images/lecturers/<?php echo !empty($lecturer['image_url']) ? htmlspecialchars($lecturer['image_url']) : 'default-lecturer.jpg'; ?>" 
                         class="img-fluid" 
                         alt="#"
                         onerror="this.src='../static/images/lecturers/default-lecturer.jpg'">
                    <p class="text-center mt-3">
                        <span><?php echo htmlspecialchars($lecturer['name']); ?></span>
                        <br>
                        <?php echo htmlspecialchars($lecturer['title']); ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- End row -->
    </div>
</section>
<!--//End Style 2 -->

<?php 
$conn->close();
include 'footer.php'; 
?>


                            <!-- jQuery, Bootstrap JS. -->
                            <script src="../static/js/jquery.min.js"></script>
                            <script src="../static/js/tether.min.js"></script>
                            <script src="../static/js/bootstrap.min.js"></script>
                            <!-- Plugins -->
                            <script src="../static/js/slick.min.js"></script>
                            <script src="../static/js/waypoints.min.js"></script>
                            <script src="../static/js/counterup.min.js"></script>
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






