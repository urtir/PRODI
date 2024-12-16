

<?php 
include 'header.php'; 

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data research
$sql_research = "SELECT * FROM researches"; // Mengambil data dari tabel `researches`
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                        <img src="../static/images/research/<?php echo htmlspecialchars($research['image_url']) ? htmlspecialchars($research['image_url']) : 'research-img.jpg'; ?>" class="img-fluid" alt="research-img">
                        <div>
                            <h4><?php echo htmlspecialchars($research['title']); ?></h4>
                            <p><?php echo strlen($research['description']) > 150 ? substr($research['description'], 0, 150) . '...' : htmlspecialchars($research['description']); ?></p>
                            <?php if ($research['details']): ?>
                            <ul class="research-list">
                                <?php foreach (explode(',', $research['details']) as $detail): ?>
                                    <li><?php echo htmlspecialchars($detail); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
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

    <section class="research-features">
        <img src="../static/images/research/research-features_img.jpg" class="img-fluid" alt="research-features_img">
        <div class="research-featurestext_block">
            <h3>Research with Purpose</h3>
            <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum...</p>
        </div>
    </section>

    <section class="resources">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="resources-title">Resources</h2>
            </div>
        </div>

        <!-- Carousel -->
        <div id="resourcesCarousel" class="carousel slide" data-ride="carousel" data-interval="5000">
            <!-- Indikator Bulatan (Dots) -->
            <ol class="carousel-indicators">
                <?php foreach ($resources as $index => $resource): ?>
                    <li data-target="#resourcesCarousel" data-slide-to="<?php echo $index; ?>" class="<?php echo $index == 0 ? 'active' : ''; ?>"></li>
                <?php endforeach; ?>
            </ol>

            <div class="carousel-inner">
                <?php foreach ($resources as $index => $resource): ?>
                    <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
                        <div class="resources-slick">
                            <div class="resources-slider_wrap">
                                <div class="research-testi_block">
                                    <!-- Gambar -->
                                    <img src="../static/images/research/<?php echo htmlspecialchars($resource['image_url']) ? htmlspecialchars($resource['image_url']) : 'resources-img.jpg'; ?>" class="img-fluid" alt="resources-img">
                                    
                                    <!-- Judul -->
                                    <h4><?php echo htmlspecialchars($resource['title']); ?> »</h4>
                                    
                                    <!-- Deskripsi -->
                                    <p><?php echo htmlspecialchars($resource['description']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

                <!-- Kontrol carousel -->
                <a class="carousel-control-prev" href="#resourcesCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#resourcesCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </section>

</body>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



<?php 
$conn->close();
include 'footer.php'; 
?>




                    <script src="..static/js/jquery.min.js"></script>
                    <script src="..static/js/tether.min.js"></script>
                    <script src="..static/js/bootstrap.min.js"></script>
<!-- Plugins -->
                    <script src="..static/js/instafeed.min.js"></script>
                    <script src="..static/js/owl.carousel.min.js"></script>
                    <script src="..static/js/validate.js"></script>
                    <script src="..static/js/tweetie.min.js"></script>
<!-- Subscribe -->
                    <script src="..static/js/subscribe.js"></script>
<!-- Script JS -->
                    <script src="..static/js/script.js"></script>


                </body>
                </html>



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
</style>
