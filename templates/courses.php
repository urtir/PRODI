<?php
include 'header.php';

// Create courses materials directory if not exists
$coursesDir = "../static/courses/";
if (!file_exists($coursesDir)) {
    mkdir($coursesDir, 0777, true);
}

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all courses grouped by semester
$courses = $conn->query("SELECT * FROM courses ORDER BY semester, code")->fetch_all(MYSQLI_ASSOC);

// Group courses by semester
$coursesBySemester = [];
foreach ($courses as $course) {
    $coursesBySemester[$course['semester']][] = $course;
}
ksort($coursesBySemester);
?>


<head>
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

</head>


<div class="container" style="padding-top: 2rem;">
    <h2 class="text-center mb-4">Course List</h2>
    
    <!-- Semester Tabs -->
    <ul class="nav nav-tabs mb-4" id="semesterTabs" role="tablist">
        <?php foreach ($coursesBySemester as $semester => $semesterCourses): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo ($semester == 1) ? 'active' : ''; ?>" 
                   id="semester<?php echo $semester; ?>-tab" 
                   data-bs-toggle="tab" 
                   href="#semester<?php echo $semester; ?>" 
                   role="tab">
                    Semester <?php echo $semester; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Courses Tables -->
    <div class="tab-content" id="semesterTabsContent">
        <?php foreach ($coursesBySemester as $semester => $semesterCourses): ?>
            <div class="tab-pane fade <?php echo ($semester == 1) ? 'show active' : ''; ?>" 
                 id="semester<?php echo $semester; ?>" 
                 role="tabpanel">
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Code</th>
                                <th>Course Name</th>
                                <th>Credits</th>
                                <th>Description</th>
                                <th>Materials</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($semesterCourses as $course): ?>
                                <tr>
                                <td><?php echo htmlspecialchars($course['code']); ?></td>
                                <td><?php echo htmlspecialchars($course['name']); ?></td>
                                <td><?php echo $course['credits']; ?></td>
                                <td>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#descriptionModal<?php echo $course['id']; ?>">
                                        View Description
                                    </button>
                                </td>
                                <td>
                                    <?php
                                    if (!empty($course['materials_url'])) {
                                        $materialPath = "../static/" . $course['materials_url'];
                                        if (file_exists($materialPath)) {
                                            echo '<a href="download_materials.php?code=' . $course['code'] . '" 
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-download"></i> Download Materials
                                                </a>';
                                        } else {
                                            echo '<span class="text-muted">
                                                    <i class="fas fa-exclamation-circle"></i> Files not uploaded yet
                                                </span>';
                                        }
                                    } else {
                                        echo '<span class="text-muted">
                                                <i class="fas fa-times-circle"></i> No materials available
                                            </span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($course['image_url']): ?>
                                        <button type="button" 
                                                class="btn btn-sm btn-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#imageModal<?php echo $course['id']; ?>">
                                            <i class="fas fa-image"></i> View Image
                                        </button>
                                    <?php endif; ?>
                                </td>
                                </tr>

                                <!-- Description Modal -->
                                <div class="modal fade" id="descriptionModal<?php echo $course['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><?php echo htmlspecialchars($course['name']); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php echo nl2br(htmlspecialchars($course['description'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Modal -->
                                <?php if ($course['image_url']): ?>
                                    <div class="modal fade" id="imageModal<?php echo $course['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><?php echo htmlspecialchars($course['name']); ?> - Image</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="<?php echo htmlspecialchars($course['image_url']); ?>" 
                                                         class="img-fluid" 
                                                         alt="Course Image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.content-wrapper {
    min-height: calc(100vh - 180px); /* 100vh minus header and footer height */
    padding-bottom: 2rem;
}

.sticky-footer {
    position: relative;
    margin-top: auto;
    width: 100%;
    background: #152340;
    color: white;
    padding: 1rem 0;
}
</style>

<?php
$conn->close();
include 'footer.php';
?>