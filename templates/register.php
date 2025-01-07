<?php
include 'header.php';

$error = '';

// Redirect if already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "informatics_db");
    
    if ($conn->connect_error) {
        $error = "Connection failed: " . $conn->connect_error;
    } else {
        // Validate email match
        if ($_POST['email'] !== $_POST['reenteremail']) {
            $error = "Emails do not match";
        } else {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $_POST['email']);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error = "Email already registered";
            } else {
                // Insert new user
                $sql = "INSERT INTO users (username, email, password, firstname, lastname) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt->bind_param("sssss", 
                    $_POST['email'],
                    $_POST['email'],
                    $hashed_password,
                    $_POST['firstname'],
                    $_POST['lastname']
                );
                
                if ($stmt->execute()) {
                    $_SESSION['register_success'] = true;
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Registration failed: " . $conn->error;
                }
            }
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
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

<div class="login sign-up">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="modal-content">
                    <div class="modal-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="POST" class="form sign-up-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input class="form-control" name="firstname" type="text" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input class="form-control" name="lastname" type="text" required />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" name="email" type="email" required />
                            </div>
                            <div class="form-group">
                                <label>Re-enter Email</label>
                                <input class="form-control" name="reenteremail" type="email" required />
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input class="form-control" name="password" type="password" required minlength="6" />
                            </div>
                            <button type="submit" class="btn btn-warning w-100">Sign Up</button>
                            <p class="text-center mt-3">
                                Already have an account? <a href="login.php">Login here</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
include 'footer.php';
ob_end_flush();
?>