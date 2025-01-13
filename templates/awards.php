<?php

include 'header.php'; 

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch awards
$awards_query = "SELECT * FROM awards ORDER BY year DESC";
$awards_result = $conn->query($awards_query);
$awards = $awards_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Tab Penghargaan</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../static/css/bootstrap.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Lora:400,700" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../static/css/font-awesome.min.css">
  <!-- Simple Line Font -->
  <link rel="stylesheet" href="../static/css/simple-line-icons.css">
  <!-- Magnific Popup CSS -->
  <link rel="stylesheet" href="../static/css/magnific-popup.css">
  <!-- Image Hover CSS -->
  <link rel="stylesheet" type="text/css" href="../static/css/normalize.css" />
  <link rel="stylesheet" type="text/css" href="../static/css/set2.css" />

  <!-- Masonry Gallery -->
  <!-- <link href="css/style3.css" rel="stylesheet" type="text/css" /> -->
  <link href="../static/css/animated-masonry-gallery.css" rel="stylesheet" type="text/css" />
  <!-- Main CSS -->
  <link href="../static/css/style.css" rel="stylesheet">
  
</head>

<!-- (Penghargaan) -->
<body>
  
<div class="gallery-wrap">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
            <h2 class="event-title">Galeri Penghargaan</h2>
            <p>Program Studi Informatika telah meraih berbagai penghargaan bergengsi yang mencerminkan dedikasi dan prestasi dalam bidang pendidikan dan teknologi informasi.</p>
            </div>
        </div>
        <div class="row">
            <?php foreach ($awards as $award): ?>
            <div class="col-md-4">
                <?php
                    // Tentukan gambar besar dan kecil, jika ada di database
                    $image_besar = !empty($award['id_image_besar']) ? $award['id_image_besar'] : 'default_besar.jpg';
                    $image_kecil = !empty($award['id_image_kecil']) ? $award['id_image_kecil'] : 'default_kecil.jpg';
                    
                     // Periksa apakah gambar besar atau kecil adalah NULL atau kosong, dan gantikan dengan gambar default
                     if (empty($award['id_image_besar'])) {
                        $image_besar = 'default_besar.jpg';
                    }
                    if (empty($award['id_image_kecil'])) {
                        $image_kecil = 'default_kecil.jpg';
                    }
                    ?>
                    
                    
                    <a href="../static/images/award/<?php echo htmlspecialchars($image_kecil); ?>" class="grid image-link">
                        <figure class="effect-bubba gallery-img-wrap">
                            <img src="../static/images/award/<?php echo htmlspecialchars($image_besar); ?>" 
                             class="img-fluid" 
                             alt="<?php echo htmlspecialchars($award['title']); ?>">
                        <figcaption>
                            <h3 class="hover-effect"><?php echo htmlspecialchars($award['title']); ?></h3>
                            <p><?php echo htmlspecialchars($award['recipient']); ?> (<?php echo $award['year']; ?>)<br>
                               <?php echo htmlspecialchars(substr($award['description'], 0, 100)) . '...'; ?></p>
                        </figcaption>
                    </figure>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</body>

<?php 
$conn->close();
?>

<?php include 'footer.php'; ?>

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

 <script src="../static/js/jquery-ui-1.10.4.min.js"></script>
 <script src="../static/js/jquery.isotope.min.js"></script>
 <script src="../static/js/animated-masonry-gallery.js"></script>
 <!-- Magnific popup JS -->
 <script src="../static/js/jquery.magnific-popup.js"></script>
 <!-- Script JS -->
 <script src="../static/js/script.js"></script>
</html>




