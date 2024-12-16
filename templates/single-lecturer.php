<?php
include 'header.php'; 
// Database connection
$conn = new mysqli('localhost', 'root', '', 'informatics_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and validate lecturer ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id) {
    header('Location: lecturers.php');
    exit;
}

// Query lecturer data
$stmt = $conn->prepare("SELECT * FROM lecturers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$lecturer = $result->fetch_assoc();

if (!$lecturer) {
    header('Location: lecturers.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
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

    <style>
        .admission_insruction img {
            transition: transform 0.3s ease, opacity 0.3s ease;
            opacity: 1;
        }

        .admission_insruction img:hover {
            transform: scale(1.1); /* Memperbesar gambar 10% */
            opacity: 0.9; /* Sedikit transparan saat hover */
        }
    </style>   
</head>
<body>
    <div class="container mt-5">
        <!-- Back Button -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="lecturers.php" class="btn btn-outline-primary">&larr; Back to Lecturers</a>
            </div>
        </div>

        <!-- Main Info -->
        <div class="row">
            <div class="col-md-4">
                <img src="../static/images/lecturers/<?php echo !empty($lecturer['image_url']) ? 
                    htmlspecialchars($lecturer['image_url']) : 'default-lecturer.jpg'; ?>"
                    class="img-fluid rounded shadow"
                    alt="<?php echo htmlspecialchars($lecturer['name']); ?>"
                    onerror="this.src='../static/images/lecturers/default-lecturer.jpg'">
            </div>
            <div class="col-md-8">
                <h2 class="mb-3"><?php echo htmlspecialchars($lecturer['name']); ?></h2>
                <h4 class="text-muted mb-4"><?php echo htmlspecialchars($lecturer['title']); ?></h4>
                
                <div class="mb-4">
                    <h5>Contact Information</h5>
                    <?php if (!empty($lecturer['email'])): ?>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($lecturer['email']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($lecturer['social_linkedin'])): ?>
                        <a href="<?php echo htmlspecialchars($lecturer['social_linkedin']); ?>" 
                           class="btn btn-primary btn-sm" target="_blank">
                            LinkedIn Profile
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Education -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="border-bottom pb-2">Education</h3>
                <p><?php echo htmlspecialchars($lecturer['education']); ?></p>
                <?php if (!empty($lecturer['education_details'])): ?>
                    <p><?php echo nl2br(htmlspecialchars($lecturer['education_details'])); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Professional Profile -->
        <div class="row mt-4">
            <div class="col-12">
                <h3 class="border-bottom pb-2">Professional Profile</h3>
                <p><strong>Specialization:</strong> <?php echo htmlspecialchars($lecturer['specialization']); ?></p>
                <?php if (!empty($lecturer['professional_profile'])): ?>
                    <p><?php echo nl2br(htmlspecialchars($lecturer['professional_profile'])); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Experience -->
        <div class="row mt-4">
            <div class="col-12">
                <h3 class="border-bottom pb-2">Experience</h3>
                <?php if (!empty($lecturer['experience_years'])): ?>
                    <p><strong>Years of Experience:</strong> <?php echo htmlspecialchars($lecturer['experience_years']); ?></p>
                <?php endif; ?>
                <?php if (!empty($lecturer['experience_location'])): ?>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($lecturer['experience_location']); ?></p>
                <?php endif; ?>
                <?php if (!empty($lecturer['experience_details'])): ?>
                    <p><?php echo nl2br(htmlspecialchars($lecturer['experience_details'])); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Additional Skills -->
        <?php if (!empty($lecturer['additional_skills'])): ?>
        <div class="row mt-4 mb-5">
            <div class="col-12">
                <h3 class="border-bottom pb-2">Additional Skills</h3>
                <p><?php echo nl2br(htmlspecialchars($lecturer['additional_skills'])); ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <?php 
$conn->close();
include 'footer.php'; 
?>   
    <script src="../static/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>