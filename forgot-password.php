<?php
// forgot-password.php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cnic = str_replace('-', '', $conn->real_escape_string($_POST['cnic']));
    $phonenumber = $conn->real_escape_string($_POST['phonenumber']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $sql = "SELECT * FROM students WHERE REPLACE(cnic, '-', '')='$cnic' AND phonenumber='$phonenumber'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        if ($new_password !== $confirm_password) {
            $message = "<div class='alert alert-danger'>Passwords do not match!</div>";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE students SET password='$hashed_password' WHERE REPLACE(cnic, '-', '')='$cnic' AND phonenumber='$phonenumber'";
            if ($conn->query($update_sql) === TRUE) {
                $message = "<div class='alert alert-success'>Password updated successfully! <a href='student-login.php'>Login</a></div>";
            } else {
                $message = "<div class='alert alert-danger'>Error updating password!</div>";
            }
        }
    } else {
        $message = "<div class='alert alert-danger'>No account found with this CNIC and Phone Number!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - LMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); font-family: 'Segoe UI', sans-serif; }
        .card-box { background: #fff; border-radius: 16px; box-shadow: 0 8px 32px rgba(33,150,243,0.10); padding: 32px 28px; margin-top: 60px; margin-bottom: 30px; }
        .form-label { font-weight: 500; color: #17406d; }
        .form-control:focus { border-color: #2196f3; box-shadow: 0 0 0 0.15rem rgba(33,150,243,.25); }
        .btn-primary { background: linear-gradient(90deg, #2196f3 60%, #17406d 100%); border: none; font-weight: 600; letter-spacing: 1px; padding: 10px 32px; }
        .btn-primary:hover { background: linear-gradient(90deg, #17406d 60%, #2196f3 100%); }
        .login-title { color: #17406d; font-weight: 700; letter-spacing: 1px; margin-bottom: 18px; text-align: center; }
        .navbar { background-color: #e3f2fd; border-bottom: 2px solid #2196f3; }
        .footer-section { background-color: #e3f2fd; color: #17406d; padding: 40px 20px; }
        .footer-section ul li:hover { text-decoration: underline; cursor: pointer; }
        footer { background-color: #17406d; border-top: 2px solid #17406d; text-align: center; }
        footer p, footer span { color: #fff; font-size: 0.95rem; }
        .ticker { background: #bbdefb; padding: 6px 10px; font-weight: 500; color: #17406d; font-size: 1rem; }
    </style>
</head>
<body>

<!-- Header/Navbar -->
<nav class="navbar navbar-light">
    <div class="container-fluid d-flex justify-content-between align-items-center py-2">
        <div class="d-flex align-items-center">
            <img src="lms-logo.png" alt="LMS Logo" style="height:60px; margin-right:10px;">
            <div>
                <span class="h4 mb-0 fw-bold" style="color:#17406d; letter-spacing:2px;">Institute of Mathematics & Computer Science (LMS)</span><br>
                <small style="color:#17406d;">Library Management System</small>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <span id="header-datetime" class="me-3" style="font-size:0.98rem"></span>
        </div>
    </div>
</nav>

<!-- Ticker -->
<div class="ticker text-center">
    <marquee behavior="" direction="">Library opens at 8:00 AM and closes at 8:00 PM</marquee>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card-box">
                <h2 class="login-title"><i class="bi bi-key"></i> Forgot Password</h2>
                <?php echo $message; ?>
                <form action="forgot-password.php" method="post" autocomplete="off">
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                        <input type="text" name="cnic" id="cnic" class="form-control" placeholder="CNIC (without dashes)" pattern="\d{13}" maxlength="13" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="phonenumber" id="phonenumber" class="form-control" placeholder="Phone Number" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm New Password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-key"></i> Reset Password</button>
                    </div>
                </form>
                <div class="mt-3 text-center">
                    <a href="student-login.php" class="btn btn-link">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer Top Section -->
<div class="container-fluid py-4 footer-section">
    <div class="row justify-content-center text-center text-md-start">
        <!-- Logo and Address -->
        <div class="col-md-3 mb-3">
            <img src="lms-logo.png" alt="IMCS University of Sindh Jamshoro" style="height:70px; margin-bottom:10px;">
            <div class="fw-bold">Sindh University<br>Jamshoro</div>
            <div class="mt-2" style="font-size:0.98rem;">
                University Road, Jamshoro<br>Sindh, Pakistan
            </div>
        </div>
        <!-- Useful Links -->
        <div class="col-md-3 mb-3">
            <ul class="list-unstyled" style="font-size:0.98rem;">
                <li>&#8250; About Us</li>
                <li>&#8250; Contact Us</li>
                <li>&#8250; Privacy Policy</li>
                <li>&#8250; Terms of Service</li>
                <li>&#8250; FAQs</li>
                <li>&#8250; Help Center</li>
            </ul>
        </div>
        <!-- Resources and Social Media -->
        <div class="col-md-3 mb-3">
            <ul class="list-unstyled" style="font-size:0.98rem;">
                <li>&#8250; Search Faculty & Staff</li>
                <li>&#8250; Course Catalogue</li>
                <li>&#8250; Library Resources</li>
                <li>&#8250; Academic Calendar</li>
                <li>&#8250; Campus Map</li>
                <li>&#8250; News & Events</li>
            </ul>
            <div class="mt-3 fw-bold">Follow Us</div>
            <div class="mt-2">
                <a href="#" class="btn btn-sm btn-dark rounded-circle me-1"><i class="bi bi-facebook"></i></a>
                <a href="#" class="btn btn-sm btn-dark rounded-circle me-1"><i class="bi bi-instagram"></i></a>
                <a href="#" class="btn btn-sm btn-dark rounded-circle"><i class="bi bi-youtube"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="py-4">
    <div class="container text-center">
        <p>&copy; <strong>Institute of Mathematics & Computer Science (LMS)</strong>. All Rights Reserved</p>
        <span>Designed by <strong>Ali Ahmed && Abdul Hayee</strong></span>
    </div>
</footer>

<!-- DateTime Script -->
<script>
    function updateDateTime() {
        const now = new Date();
        const formatted = now.getFullYear() + '-' +
            String(now.getMonth() + 1).padStart(2, '0') + '-' +
            String(now.getDate()).padStart(2, '0') + ' ' +
            String(now.getHours()).padStart(2, '0') + ':' +
            String(now.getMinutes()).padStart(2, '0') + ':' +
            String(now.getSeconds()).padStart(2, '0');
        document.getElementById('header-datetime').textContent = formatted;
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>
</body>
</html>