
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

// Close connection 
$conn->close();
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
    <h1 class="text-center mb-5">Buletin Terkini</h1>
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">

                    <h2 class="text-center my-5">Informasi Pertanyaan Pengguna</h2>
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <!-- QA 1 -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <strong>User</strong>
                                </div>
                                <div class="card-body">
                                    <p>Apa saja kegiatan yang dilakukan dalam proyek akhir di prodi Informatika?</p>
                                </div>
                                <div class="card-footer bg-light">
                                    <strong>Admin</strong>
                                    <p>Kegiatan proyek akhir meliputi analisis kebutuhan sistem, perancangan aplikasi, pengembangan perangkat lunak, dan pengujian hasil untuk memastikan kualitas aplikasi.</p>
                                </div>
                            </div>

                            <!-- QA 2 -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <strong>User</strong>
                                </div>
                                <div class="card-body">
                                    <p>Bagaimana cara mahasiswa memilih topik proyek?</p>
                                </div>
                                <div class="card-footer bg-light">
                                    <strong>Admin</strong>
                                    <p>Mahasiswa dapat memilih topik berdasarkan minat pribadi, saran dari dosen pembimbing, atau melalui kolaborasi dengan industri untuk proyek berbasis kebutuhan nyata.</p>
                                </div>
                            </div>

                            <!-- QA 3 -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <strong>User</strong>
                                </div>
                                <div class="card-body">
                                    <p>Apakah ada batas waktu untuk menyelesaikan proyek akhir?</p>
                                </div>
                                <div class="card-footer bg-light">
                                    <strong>Admin</strong>
                                    <p>Ya, biasanya proyek akhir harus diselesaikan dalam satu semester dengan batas waktu yang ditentukan oleh kalender akademik.</p>
                                </div>
                            </div>

                            <!-- QA 4 -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <strong>User</strong>
                                </div>
                                <div class="card-body">
                                    <p>Apakah proyek akhir dikerjakan secara individu atau kelompok?</p>
                                </div>
                                <div class="card-footer bg-light">
                                    <strong>Admin</strong>
                                    <p>Proyek akhir dapat dikerjakan secara individu atau kelompok tergantung pada kebijakan prodi dan kompleksitas topik yang dipilih.</p>
                                </div>
                            </div>

                            <!-- QA 5 -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <strong>User</strong>
                                </div>
                                <div class="card-body">
                                    <p>Bagaimana cara mempresentasikan hasil proyek akhir?</p>
                                </div>
                                <div class="card-footer bg-light">
                                    <strong>Admin</strong>
                                    <p>Hasil proyek akhir dipresentasikan dalam seminar akhir yang melibatkan dosen penguji dan mahasiswa lain sebagai audiens untuk memberikan masukan dan penilaian.</p>
                                </div>
                            </div>

                        </div>
                    </div>
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