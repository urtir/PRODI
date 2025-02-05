<?php 


include 'header.php';

$message = '';
$error = '';

// Check if form is submitted with proper method check
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "informatics_db");
    
    if ($conn->connect_error) {
        $error = "Connection failed: " . $conn->connect_error;
    } else {
        $name = $conn->real_escape_string($_POST['name'] ?? '');
        $email = $conn->real_escape_string($_POST['email'] ?? '');
        $subject = $conn->real_escape_string($_POST['subject'] ?? '');
        $msg = $conn->real_escape_string($_POST['message'] ?? '');
        
        if (empty($name) || empty($email) || empty($subject) || empty($msg)) {
            $error = "Please fill in all fields";
        } else {
            $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $subject, $msg);
            
            if ($stmt->execute()) {
                $message = "Message sent successfully!";
            } else {
                $error = "Error sending message.";
            }
        }
        $conn->close();
    }
}

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

<div class="container py-5">
    <h1 class="text-center mb-5">Hubungi Kami</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h3>Informasi Kontak</h3>
                    <p><i class="bi bi-geo-alt"></i> Alamat: Universitas Pertahanan, Kawasan IPSC Sentul, Sukahati Kec. Citeureup, Kabupaten Bogor, Jawa Barat</p>
                    <p><i class="bi bi-envelope"></i> Email: info@informatics.edu</p>
                    <p><i class="bi bi-telephone"></i> No. Telepon: +1234567890</p>
                    
                    <h3 class="mt-4">Social Media</h3>
                    <div class="social-links">
                        <a href="#" class="btn btn-outline-primary"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-info"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="btn btn-outline-danger"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-dark"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="card-title mb-4">Silahkan Masukkan Pertanyaan Anda</h3>
                    <form method="POST" action="">
                        <?php if ($message): ?>
                            <div class="alert alert-success"><?php echo $message; ?></div>
                        <?php endif; ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control form-control-lg" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control form-control-lg" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control form-control-lg" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" class="form-control form-control-lg" rows="5" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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