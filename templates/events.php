<?php 
include 'header.php'; 

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination
$events_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $events_per_page;

// Count total events for pagination
$total_events = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];
$total_pages = ceil($total_events / $events_per_page);

// Fetch events with pagination
$events_query = "SELECT * FROM events ORDER BY date DESC LIMIT $start, $events_per_page";
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
    <!-- Add to head section -->
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css' rel='stylesheet' />
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.js'></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.js'></script>

</head>

<body>

<section class="events">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h2 class="event-title">Events</h2>
            </div>
        </div>
        <br>
        <div class="row">
            <!-- Tab panes -->
            <div class="tab-content">
            <div class="tab-pane active" id="upcoming-events" role="tabpanel">
                <div id="eventAccordion">
                    <?php foreach ($events as $event): ?>
                    <div class="col-md-12">
                        <div class="row">
                <!-- Event Date -->
                    <div class="col-md-2">
                        <div class="event-date">
                            <h4><?php echo date('d', strtotime($event['date'])); ?></h4>
                            <span><?php echo date('M Y', strtotime($event['date'])); ?></span>
                        </div>
                        <span class="event-time"><?php echo htmlspecialchars($event['time'] ?? ''); ?></span>
                    </div>
                <!-- Event Content -->
                <div class="col-md-10">
                        <div class="event-heading">
                            <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                            <p><?php echo htmlspecialchars($event['description']); ?></p>
                        </div>
                    
                    <!-- Toggle Button -->
                    

                    <!-- Collapsible Details -->
                    <div id="collapse<?php echo $event['id']; ?>" class="collapse">
                        <div class="panel-body">
                            <div class="event-hilights">
                                <h5>Event Highlights Photos</h5>
                            </div>
                            <div class="row">
                                <!-- Event Date -->
                                <div class="col-md-4">
                                    <img src="../static/images/events/<?php echo htmlspecialchars($event['image_url2'] ?? 'default-event.jpg'); ?>" class="img-fluid" alt="event-img" onerror="this.src='../static/images/upcoming-event-img.jpg'">
                                </div>
                                <div class="col-md-4">
                                    <img src="../static/images/events/<?php echo htmlspecialchars($event['image_url3'] ?? 'default-event.jpg'); ?>" class="img-fluid" alt="event-img" onerror="this.src='../static/images/upcoming-event-img.jpg'">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="event-highlight-discription">
                                        <p><?php echo nl2br(htmlspecialchars($event['long_description'] ?? '')); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Toggle Button -->
                    <!-- Toggle Button -->
                    <button class="event-toggle" 
                            type="button"
                            data-bs-toggle="collapse" 
                            data-bs-target="#collapse<?php echo $event['id']; ?>" 
                            aria-expanded="false"
                            aria-controls="collapse<?php echo $event['id']; ?>">
                        Show Details
                    </button>
                </div>
            </div>
            <hr class="event-underline">
        </div>
        <?php endforeach; ?>
    </div>
    <div class="tab-pane" id="calendar-view" role="tabpanel">
        <!-- Calendar will be rendered here -->
        <div id='calendar'></div>
</div>

<div class="tab-pane" id="calendar-view" role="tabpanel">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</section>

<!-- Pagination -->
<div class="row">
    <div class="col-md-12">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo ($page-1); ?>">Previous</a>
                    </li>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo ($page+1); ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
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
        <script src="../static/js/bootstrap.bundle.min.js"></script>
        
        
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

        <script>
        document.querySelectorAll('.event-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const isCollapsed = this.getAttribute('aria-expanded') === 'false';
                this.textContent = isCollapsed ? 'Hide Details' : 'Show Details';
            });
        });
        </script>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    <?php foreach ($events as $event): ?>
                    {
                        title: '<?php echo addslashes($event['title']); ?>',
                        start: '<?php echo $event['date']; ?>',
                        url: '#event-<?php echo $event['id']; ?>',
                        description: '<?php echo addslashes($event['description']); ?>'
                    },
                    <?php endforeach; ?>
                ],
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    const eventId = info.event.url.split('#')[1];
                    document.getElementById(eventId).scrollIntoView();
                }
            });
            calendar.render();

            // Tab switching
            document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function (e) {
                    if (e.target.getAttribute('href') === '#calendar-view') {
                        calendar.updateSize();
                    }
                });
            });
        });
        </script>


</body>
</html>