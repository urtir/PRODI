<?php
session_start();
ob_start(); // Add output buffering
?>
<!DOCTYPE html>
<?php
// Get current page name
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informatics Department</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url_for('static', filename='css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../static/js/jquery.min.js"></script>
    <script src="../static/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="../static/js/main.js"></script>
    <script src="js/jquery.min.js"></script>
        <script src="js/tether.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!-- Plugins -->
        <script src="js/moment.min.js"></script>
        <script src="js/fullcalendar.min.js"></script>
        <script src="js/instafeed.min.js"></script>
        <script src="js/owl.carousel.min.js"></script>
        <script src="js/validate.js"></script>
        <script src="js/tweetie.min.js"></script>
        <!-- Subscribe -->
        <script src="js/subscribe.js"></script>
        <!-- Script JS -->
        <script src="js/script.js"></script>
    <script>
    $(document).ready(function() {
        $('.event-toggle').click(function() {
            $(this).text(function(i, text) {
                return text === "Show Details" ? "Hide Details" : "Show Details";
            });
        });
        
        // Prevent collapse from closing when clicking inside
        $('.event-details').click(function(e) {
            e.stopPropagation();
        });
    });
    </script>   

    <style>
    * {
        font-family: 'Poppins', sans-serif;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    main {
    flex: 1 0 auto;
    }

    /* Base template structure */
    .page-wrapper {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    }

    /* Content block spacing */
    .content-wrapper {
        flex: 1 0 auto;
        width: 100%;
        padding-top: 100px;
        background: transparent; /* Match navbar height */
    }

    /* Footer positioning */
    footer {
        flex-shrink: 0;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        body {
            padding-top: 70px;
        }
        
        .content-wrapper {
            padding-top: 70px;
        }
    }
    </style>


    <style>


    h1, h2, h3, h4, h5, h6 {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
    }

    .navbar-nav .nav-link {
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
    }

    p {
        font-family: 'Poppins', sans-serif;
        font-weight: 400;
    }

    .btn {
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
    }
    .event-description {
    white-space: pre-line;  /* This preserves newlines without needing <br> tags */
    line-height: 1.6;
    }

    .event-toggle {
    color: #3366CC;
    text-decoration: none;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    }

    .event-toggle:hover {
        color: #254B99;
        text-decoration: none;
    }

    .event-toggle:focus {
        outline: none;
        box-shadow: none;
    }

    .event-toggle.collapsed {
        color: #3366CC;
    }

    .event-toggle-wrap {
        margin-top: 15px;
    }

    .event-panel {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    </style>

        <!-- Update navbar class and add custom style -->
        <style>
        /* Base Navbar */
        .navbar {
            height: 100px;
            background-color: #152340 !important;
            padding: 0;
            position: fixed;
            width: 100%;
            z-index: 1030;
            top: 0;
        }
        
        /* Main Container */
        .container-navbar {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
            width: 100%;
        }
        
        /* Logo Styling */
        .navbar-brand {
            display: flex;
            align-items: center;
            height: 100%;
            padding: 10px 0;
            margin: 0;
            z-index: 2;
        }
        
        .navbar-brand img {
            height: 60px;
            width: auto;
            object-fit: contain;
        }
        
        /* Navigation Container */
        .navbar-collapse {
            display: flex;
            justify-content: center;
            position: absolute;
            left: 0;
            right: 0;
        }
        
        /* Navigation Items */
        .navbar-nav {
            display: flex;
            align-items: center;
            margin: 0;
            padding: 0;
        }
        
        /* Navigation Items */
        .navbar-nav .nav-link {
            padding: 4px 12px !important;
            font-size: 19px;
            color: #ffffff !important;
            position: relative;
            transition: all 0.3s ease;
        }
        
        /* Hover Effect */
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link:focus {
            color: #3366CC !important;
        }

        /* Active State */
        .navbar-nav .nav-link.active {
            color: #3366CC !important;
        }

        /* Hover Underline Effect */
        .navbar-nav .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: #3366CC;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }

        .navbar-nav .nav-link:hover:after,
        .navbar-nav .nav-link.active:after {
            width: calc(100% - 24px); /* Adjust for padding */
        }

        /* Dropdown Hover */
        .dropdown-item:hover {
            background: #3366CC;
            color: #fff;
        }

        /* Desktop Styles */
        @media (min-width: 992px) {
            .container-navbar {
                position: relative;
            }
        
            .navbar-collapse {
                position: static;
                flex: 1;
                display: flex !important;
                justify-content: center !important;
            }
        
            .navbar-nav {
                width: auto;
            }
        
            .navbar-brand {
                margin-right: 40px;
            }
        }
        
        /* Mobile Styles */
        @media (max-width: 991.98px) {
            .navbar {
                min-height: 70px;
            }
        
            .container-navbar {
                justify-content: space-between;
            }
        
            .navbar-brand img {
                height: 50px;
            }
        
            .navbar-collapse {
                position: absolute;
                top: 100%;
                background: #152340;
                width: 100%;
                padding: 10px 0;
            }
        
            .navbar-nav {
                flex-direction: column;
                width: 100%;
            }
        
            .navbar-nav .nav-link:after {
                display: none;
            }
            
            .navbar-nav .nav-link:hover,
            .navbar-nav .nav-link.active {
                background: rgba(51, 102, 204, 0.1);
            }
        
            .navbar-toggler {
                padding: 4px 8px;
                margin-left: auto;
                border-color: rgba(255,255,255,0.5) !important;
            }
        
            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
            }
        
            .navbar-toggler:focus {
                box-shadow: 0 0 0 0.25rem rgba(255,255,255,0.2);
            }
        
            .navbar-nav .nav-link:hover,
            .navbar-nav .nav-link.active {
                color: #e3f2fd !important;
                background: rgba(255,255,255,0.1);
            }
        }

        /* Responsive Adjustments */
@media (max-width: 991.98px) {
    .navbar {
        height: 70px;  /* Smaller height on mobile */
    }
    
    .navbar-brand img {
        height: 40px;  /* Smaller logo on mobile */
    }
    
    .navbar-collapse {
        max-height: calc(100vh - 70px);  /* Prevent overflow */
        overflow-y: auto;
    }
    
    .navbar-nav .nav-link {
        font-size: 16px;  /* Smaller font on mobile */
        padding: 12px 15px !important;  /* More touch-friendly padding */
    }
    
    .dropdown-menu {
        background: #1a2b4d;  /* Darker background for dropdown */
        border: none;
        margin: 0;
    }
    
    .dropdown-item {
        color: #fff;
        padding: 10px 15px;
    }
    }

    /* Extra small devices */
    @media (max-width: 575.98px) {
        .navbar-brand {
            max-width: 200px;  /* Limit logo width */
        }
        
        .navbar-nav .nav-link {
            font-size: 15px;
            padding: 10px 12px !important;
        }
    }

    /* Fix transition issues */
    .navbar-collapse {
        transition: height 0.3s ease;
    }

    /* Improve dropdown visibility */
    .dropdown-menu {
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-radius: 4px;
        margin-top: 0;
    }

    /* Smoother transitions */
    .navbar-nav .nav-link,
    .dropdown-item {
        transition: all 0.2s ease-in-out;
    }



        </style>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar Affix
        document.addEventListener("DOMContentLoaded", function(){
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    document.querySelector('.navbar').classList.add('affix');
                } else {
                    document.querySelector('.navbar').classList.remove('affix');
                }
            });
        });
    </script>
    
</head>
<body>
    
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container h-100 d-flex align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../static/images/logo.png" alt="Logo" height="70">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto d-flex align-items-center">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'courses') ? 'active' : ''; ?>" href="courses.php">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'lecturers') ? 'active' : ''; ?>" href="lecturers.php">Lecturers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'events') ? 'active' : ''; ?>" href="events.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'research') ? 'active' : ''; ?>" href="research.php">Riset</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'awards') ? 'active' : ''; ?>" href="awards.php">Penghargaan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'buletin') ? 'active' : ''; ?>" href="buletin.php">Buletin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'contact') ? 'active' : ''; ?>" href="contact.php">Hubungi</a>
                    </li>

                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'admin') ? 'active' : ''; ?>" href="admin.php">Console</a>
                    </li>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item me-3 d-flex align-items-center">
                            <a class="nav-link px-2" href="messages.php">Messages</a>
                        </li>
                        <li class="nav-item dropdown d-flex align-items-center">
                            <a class="nav-link dropdown-toggle px-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <?php echo htmlspecialchars($_SESSION['firstname']); ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>

                

            </div>
        </div>
    </nav>
    <!-- End Main Navigation -->

</body>
</html>
