<?php
include 'header.php';

$error = '';

// Prevent logged in users from accessing login page
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if form submitted
if (isset($_POST) && !empty($_POST)) {
    $conn = new mysqli("localhost", "root", "", "informatics_db");
    
    if ($conn->connect_error) {
        $error = "Connection failed: " . $conn->connect_error;
    } else {
        $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        if (empty($email) || empty($password)) {
            $error = "Please fill in all fields";
        } else {
            $stmt = $conn->prepare("SELECT id, email, password, firstname FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
        
            if ($user = $result->fetch_assoc()) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['firstname'] = $user['firstname'];
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Invalid password";
                }
            } else {
                $error = "Email not found";
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

<div class="login">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="modal-content">
                    <div class="modal-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-warning w-100">Login</button>
                            <p class="text-center mt-3">
                                Don't have an account? <a href="register.php">Register here</a>
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
ob_end_flush(); // Flush the output buffer
?>