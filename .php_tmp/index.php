<?php 
include 'header.php'; 

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events
$events_query = "SELECT * FROM events ORDER BY date DESC";
$events_result = $conn->query($events_query);
$events = $events_result->fetch_all(MYSQLI_ASSOC);

// Fetch lecturers
$lecturers_query = "SELECT * FROM lecturers";
$lecturers_result = $conn->query($lecturers_query);
$lecturers = $lecturers_result->fetch_all();

// Fetch courses
$courses_query = "SELECT * FROM courses";
$courses_result = $conn->query($courses_query);
$courses = $courses_result->fetch_all();

$conn->close();


?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Home - Unisco - Education Website Template for University, College, School</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../static/css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../static/css/font-awesome.min.css">
    <!-- Simple Line Font -->
    <link rel="stylesheet" href="../static/css/simple-line-icons.css">
    <!-- Slider / Carousel -->
    <link rel="stylesheet" href="../static/css/slick.css">
    <link rel="stylesheet" href="../static/css/slick-theme.css">
    <link rel="stylesheet" href="../static/css/owl.carousel.min.css">
    <!-- Main CSS -->
    <link href="../static/css/style.css" rel="stylesheet">

<style>

/* Carousel Controls */
.carousel-control-prev,
.carousel-control-next {
    width: 40px;
    height: 40px;
    background: #3366CC;
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.8;
    position: absolute;
}

.carousel-control-prev {
    left: 20px;
}

.carousel-control-next {
    right: 20px;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    opacity: 1;
}

/* Carousel Indicators */
.carousel-indicators {
    bottom: 0;
}

.carousel-indicators li {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #ccc;
    opacity: 0.5;
    margin: 0 5px;
}

.carousel-indicators li.active {
    background-color: #3366CC;
    opacity: 1;
}

</style>
</head>

<body>
<!-- Hero/Carousel Section -->
<div class="slider_img">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../static/images/slider1.jpg" class="d-block w-100" alt="...">
                <div class="carousel-caption slider_title">
                    <h1>Program Studi Informatika</h1>
                    <h4>Membentuk Pakar IT Masa Depan</h4>
                    <div class="slider-btn">
                        <a href="#" class="btn btn-default">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../static/images/slider2.jpg" class="d-block w-100" alt="...">
                <div class="carousel-caption slider_title">
                    <h1>Program Studi Informatika</h1>
                    <h4>Membentuk Pakar IT Masa Depan</h4>
                    <div class="slider-btn">
                        <a href="#" class="btn btn-default">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../static/images/slider3.jpg" class="d-block w-100" alt="...">
                <div class="carousel-caption slider_title">
                    <h1>Program Studi Informatika</h1>
                    <h4>Membentuk Pakar IT Masa Depan</h4>
                    <div class="slider-btn">
                        <a href="#" class="btn btn-default">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Carousel Navigation Arrows -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
<!--============================= EVENTS =============================-->
<section class="event">
    <div class="container">
        <div class="row">
            <!-- Upcoming Events (Left Column) -->
            <div class="col-lg-6 offset-xl-0">
                <h2>Upcoming Events</h2>
                <?php if (!empty($events)): ?>
                <?php $featured_event = $events[0]; ?>
                <div class="event-img">
                    <span class="event-img_date"><?php echo date('d-M-y', strtotime($featured_event['date'])); ?></span>
                    <img src="../static/images/<?php echo $featured_event['image_url']; ?>" class="img-fluid" alt="event-img"
                    onerror="this.src='../static/images/upcoming-event-img.jpg'">
                    <div class="event-img_title">
                        <h3><?php echo $featured_event['title']; ?></h3>
                        <p><?php 
                            $description = $featured_event['description'];
                            echo strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                        ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Important Dates (Right Column) -->
            <div class="col-lg-6">
                <h2>Important Dates</h2>
                <div class="event-date-slide">
                    <?php 
                    // Skip first event and chunk remaining events into pairs
                    $remaining_events = array_slice($events, 1);
                    $chunks = array_chunk($remaining_events, 2);
                    
                    foreach ($chunks as $chunk): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?php foreach ($chunk as $index => $event): ?>
                            <div class="event_date">
                                <div class="event-date-wrap">
                                    <p><?php echo date('d', strtotime($event['date'])); ?></p>
                                    <span><?php echo date('M.y', strtotime($event['date'])); ?></span>
                                </div>
                            </div>
                            <div class="date-description">
                                <h3><?php echo $event['title']; ?></h3>
                                <p><?php 
                                    $description = $event['description'];
                                    echo strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                                ?></p>
                                <?php if ($index < count($chunk) - 1): ?>
                                <hr class="event_line">
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!--//END EVENTS -->
<!--//END EVENTS -->

<!--============================= OUR COURSES =============================-->
<section class="our_courses">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Mata Kuliah</h2>
            </div>
        </div>
        <div class="row" >
            <?php 
            $top_courses = array_slice($courses, 0, 4); // Get only first 4 courses
            foreach ($top_courses as $course): 
            ?>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="courses_box ">
                    <div class="course-img-wrap">
                        <img src="../static/images/courses/<?php echo $course[6]; ?>" class="img-fluid" alt="<?php echo $course[2]; ?>"
                        onerror="this.src='../static/images/courses/default-course.jpg'">
                        <div class="courses_box-img">
                            <div class="courses-link-wrap">
                                <a href="course_detail.php?id=<?php echo $course[0]; ?>" class="course-link">
                                    <span>Detail Mata Kuliah</span>
                                </a>
                                <a href="course_registration.php?id=<?php echo $course[0]; ?>" class="course-link">
                                    <span>Daftar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="courses_icon">
                        <img src="../static/images/plus-icon.png" class="img-fluid close-icon" alt="plus-icon">
                    </div>
                    <a href="course_detail.php?id=<?php echo $course[0]; ?>" class="course-box-content" style="height: 180px;" >
                        <h3><?php echo $course[2]; ?></h3>
                        <p><?php echo (strlen($course[4]) > 100) ? substr($course[4], 0, 100) . '...' : $course[4]; ?></p>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>





<!-- Lecturers Section -->
<div class="our-teachers">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center">Dosen Pengajar</h2>
            </div>
        </div>
        <div class="row">
        <?php 
            $top_lecturers = array_slice($lecturers, 0, 3); // Get only first 3 lecturers
            foreach ($top_lecturers as $lecturer): ?>
            <div class="col-md-4">
                <div class="our-teachers-block">
                    <img src="../static/images/lecturers/<?php echo !empty($lecturer[4]) ? $lecturer[4] : 'default-lecturer.jpg'; ?>" 
                         class="img-fluid teachers-img" alt="<?php echo $lecturer[1]; ?>"
                         onerror="this.src='../static/images/teachers/default-teacher.jpg'">
                    <div class="teachers-description">
                        <p><span><?php echo $lecturer[1]; ?></span></p>
                        <p><?php echo $lecturer[2]; ?></p>
                        <p><?php echo $lecturer[3]; ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!--//END Lecturers Section -->

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

 <?php       include 'footer.php';
?>