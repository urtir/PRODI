<?php 
include 'header.php';
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle new question submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $question = $conn->real_escape_string($_POST['question']);
    $user_id = $_SESSION['user_id'];
    
    $sql = "INSERT INTO faqs (user_id, question) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $question);
    $stmt->execute();
}

// Fetch FAQs
$faqs_query = "SELECT f.*, u.username FROM faqs f 
               LEFT JOIN users u ON f.user_id = u.id 
               ORDER BY f.created_at DESC";
$faqs_result = $conn->query($faqs_query);
?>

<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Events - Unisco - Education Website Template for University, College, School</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../static/css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../static/css/font-awesome.min.css">
    <!-- Simple Line Font -->
    <link rel="stylesheet" href="../static/css/simple-line-icons.css">
    <!-- Calendar Css -->
    <link rel="stylesheet" href="../static/css/fullcalendar.min.css" />
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="../static/css/owl.carousel.min.css">
    <!-- Main CSS -->
    <link href="../static/css/style.css" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="../static/css/fullcalendar.min.css" />
</head>

<div class="container mt-5 pt-5">
    <h2 class="mb-4">FAQ Bulletin Board</h2>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Question Form for logged-in users -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Ask a Question</h5>
                <form method="POST">
                    <div class="form-group">
                        <textarea class="form-control" name="question" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit Question</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Please <a href="login.php">login</a> or <a href="register.php">register</a> to ask questions.
        </div>
    <?php endif; ?>

    <!-- Display FAQs -->
    <div class="faq-list">
        <?php while ($faq = $faqs_result->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">
                        Asked by: <?php echo htmlspecialchars($faq['username']); ?> 
                        on <?php echo date('M d, Y', strtotime($faq['created_at'])); ?>
                    </h6>
                    <p class="card-text"><?php echo htmlspecialchars($faq['question']); ?></p>
                    <?php if ($faq['answer']): ?>
                        <div class="answer mt-2 p-3 bg-light">
                            <strong>Answer:</strong><br>
                            <?php echo htmlspecialchars($faq['answer']); ?>
                        </div>
                    <?php else: ?>
                        <span class="badge bg-warning">Pending Answer</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php
$conn->close();
?>

<?php include 'footer.php'; ?>
<!--//END FOOTER -->
        <!-- jQuery, Bootstrap JS. -->
        <script src="../static/js/jquery.min.js"></script>
        <script src="../static/js/tether.min.js"></script>
        <script src="../static/js/bootstrap.min.js"></script>
        <!-- Plugins -->
        <script src="../static/js/moment.min.js"></script>
        <script src="../static/js/fullcalendar.min.js"></script>
        <script src="../static/js/instafeed.min.js"></script>
        <script src="../static/js/owl.carousel.min.js"></script>
        <script src="../static/js/validate.js"></script>
        <script src="../static/js/tweetie.min.js"></script>
        <!-- Subscribe -->
        <script src="../static/js/subscribe.js"></script>
        <!-- Script JS -->
        <script src="../static/js/script.js"></script>
        <!-- jQuery -->
        <script src="../static/js/jquery.min.js"></script>
        <!-- Moment.js -->
        <script src="../static/js/moment.min.js"></script>
        <!-- FullCalendar JS -->
        <script src="../static/js/fullcalendar.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="../static/js/bootstrap.min.js"></script>
        
        
        <script>
        $(document).ready(function() {
            // Handle toggle text and collapse
            $('.event-toggle').on('click', function(e) {
                e.preventDefault(); // Prevent default link behavior
                
                var $this = $(this);
                var $collapse = $($this.attr('href'));
                
                // Toggle collapse using Bootstrap's API
                $collapse.collapse('toggle');
                
                // Update text based on collapse state
                $collapse.on('show.bs.collapse', function() {
                    $this.text('Hide Details');
                    $this.removeClass('collapsed');
                    $this.attr('aria-expanded', 'true');
                });
                
                $collapse.on('hide.bs.collapse', function() {
                    $this.text('Show Details');
                    $this.addClass('collapsed');
                    $this.attr('aria-expanded', 'false');
                });
                
                // Stop event from bubbling up
                return false;
            });
            
            // Optional: Close other panels when opening a new one
            $('.panel-collapse').on('show.bs.collapse', function () {
                $('.panel-collapse.in').collapse('hide');
            });
        });
        
        
        <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                height: 650,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: [
                    <?php foreach ($events as $event): ?>
                    {
                        title: '<?php echo addslashes($event['title']); ?>',
                        start: '<?php echo $event['date']; ?>',
                        url: '#',
                        allDay: true
                    },
                    <?php endforeach; ?>
                ]
            });
        });
        </script>

</body>
</html>