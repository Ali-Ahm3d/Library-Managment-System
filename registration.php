<?php
// Start session if needed
session_start();
// Database connection

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    // Enhanced error handling with detailed message and user-friendly output
    die("<div class='alert alert-danger mt-3'>Database connection failed: " . htmlspecialchars($conn->connect_error) . "</div>");
}

// Handle form submission
$registration_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname     = $conn->real_escape_string($_POST['fullname']);
    $surname      = $conn->real_escape_string($_POST['surname']);
    $fathername   = $conn->real_escape_string($_POST['fathername']);
    $email        = $conn->real_escape_string($_POST['email']);
    $cnic         = $conn->real_escape_string($_POST['cnic']);
    $rollnumber   = $conn->real_escape_string($_POST['rollnumber']);
    $phonenumber  = $conn->real_escape_string($_POST['phonenumber']);
    $homeaddress  = $conn->real_escape_string($_POST['homeaddress']);
    $department   = $conn->real_escape_string($_POST['department']);
    $program      = $conn->real_escape_string($_POST['program']);
    $category     = $conn->real_escape_string($_POST['category']);
    $password     = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $registration_message = "<div class='alert alert-danger'>Passwords do not match!</div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO students (fullname, surname, fathername, email, cnic, rollnumber, phonenumber, homeaddress, department, program, category, password) VALUES ('$fullname', '$surname', '$fathername', '$email', '$cnic', '$rollnumber', '$phonenumber', '$homeaddress', '$department', '$program', '$category', '$hashed_password')";
        if ($conn->query($sql) === TRUE) {
            header("Location: student-login.php");
            exit();
        } else {
            $registration_message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}
// Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            font-family: 'Segoe UI', sans-serif;
        }
        .card-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(33,150,243,0.10), 0 1.5px 6px rgba(33,150,243,0.08);
            padding: 32px 28px;
            margin-top: 30px;
            margin-bottom: 30px;
            transition: box-shadow 0.3s ease;
        }
        .card-box:hover {
            box-shadow: 0 12px 40px rgba(33,150,243,0.18), 0 2px 8px rgba(33,150,243,0.12);
        }
        .form-label {
            font-weight: 500;
            color: #17406d;
        }
        .form-control:focus {
            border-color: #2196f3;
            box-shadow: 0 0 0 0.15rem rgba(33,150,243,.25);
        }
        .btn-primary {
            background: linear-gradient(90deg, #2196f3 60%, #17406d 100%);
            border: none;
            font-weight: 600;
            letter-spacing: 1px;
            padding: 12px 36px;
            transition: background 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
            box-shadow: 0 6px 16px rgba(33,150,243,0.4);
            cursor: pointer;
            border-radius: 50px;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #17406d 60%, #2196f3 100%);
            box-shadow: 0 8px 24px rgba(33,150,243,0.6);
            transform: scale(1.07);
        }
        .btn-primary:active {
            transform: scale(0.95);
            box-shadow: 0 4px 14px rgba(33,150,243,0.5);
        }
        .registration-title {
            color: #17406d;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 18px;
            text-align: center;
            text-shadow: 1px 1px 2px rgba(23, 64, 109, 0.4);
        }
        .input-group-text {
            background: #e3f2fd;
            color: #17406d;
            border: none;
            font-size: 1.1rem;
        }
        .mb-3 {
            margin-bottom: 1.2rem !important;
        }
        /* Header and Footer styling */
        .navbar {
            background-color: #e3f2fd;
            box-shadow: 0 2px 6px rgba(23, 64, 109, 0.4);
        }
        .footer-section {
            background-color: #e3f2fd;
            color: #17406d;
            padding: 40px 20px;
        }
        .footer-section ul li:hover {
            text-decoration: underline;
            cursor: pointer;
        }
        footer {
            background-color: #17406d;
            border-top: 2px solid #17406d;
            text-align: center;
        }
        footer p, footer span {
            color: #fff;
            font-size: 0.95rem;
        }
        .ticker {
            background: #bbdefb;
            padding: 6px 10px;
            font-weight: 500;
            color: #17406d;
            font-size: 1rem;
        }
        .nav-link-btn {
    transition: color 5s ease, text-decoration 5s ease;
}
.nav-link-btn:hover {
    color: #0057b7 !important;
    text-decoration: underline;
}
/* For buttons */
button[type="submit"] {
    transition: all 5s ease;
}
button[type="submit"]:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 24px rgba(0, 87, 183, 0.5);
    background: linear-gradient(135deg, #ffd700, #0057b7);
}
button[type="submit"]:active {
    transform: scale(0.96);
}

/* Input & Select fields */
.form-control,
.form-select {
    transition: border-color 5s ease, box-shadow 5s ease;
}
.input-group-text {
    transition: background 5s ease, color 5s ease;
}

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
        <!-- Removed login/register buttons from header -->
        <div class="d-flex align-items-center">
            <a href="index.php" class="btn btn-sm rounded-pill px-3 me-3 nav-link-btn" style="color:#000080; font-weight: 600;">Home</a>
            <span id="header-datetime" class="me-3" style="font-size:0.98rem"></span>
        </div>
    </div>
</nav>

<!-- Ticker -->
<div class="ticker text-center">
    <marquee behavior="" direction="">Library opens at 8:00 AM and closes at 8:00 PM</marquee>
</div>

<!-- Main Content -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card-box">
                <h2 class="registration-title"><i class="bi bi-person-plus"></i> Student Registration</h2>
                <?php echo $registration_message; ?>
                <form action="registration.php" method="post" autocomplete="off">
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Full Name" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                        <input type="text" name="surname" id="surname" class="form-control" placeholder="Surname" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                        <input type="text" name="fathername" id="fathername" class="form-control" placeholder="Father Name" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                        <input type="text" name="cnic" id="cnic" class="form-control" placeholder="CNIC" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-hash"></i></span>
                        <input type="text" name="rollnumber" id="rollnumber" class="form-control" placeholder="Roll Number" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="phonenumber" id="phonenumber" class="form-control" placeholder="Phone Number" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" name="homeaddress" id="homeaddress" class="form-control" placeholder="Home Address" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-building"></i></span>
                        <select name="department" id="department" class="form-select" required>
                            <option value="">Select Department</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Artificial Intelligence">Artificial Intelligence</option>
                        </select>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                        <select name="program" id="program" class="form-select" required>
                            <option value="">Select Program</option>
                            <option value="Bachelor">Bachelor</option>
                            <option value="Master">Master</option>
                            <option value="PhD">PhD</option>
                        </select>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-tags"></i></span>
                        <select name="category" id="category" class="form-select" required>
                            <option value="">Select Category</option>
                            <option value="Merit">Merit</option>
                            <option value="Self Finance">Self Finance</option>
                        </select>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>
                    </div>
                    <div style="text-align:center; margin-top: 20px;">
                        <button type="submit"
                            style="background: linear-gradient(135deg, #17406d, #2196f3); color: #fff; border: none; border-radius: 50px; padding: 12px 36px; font-size: 1.1rem; font-weight: 600; letter-spacing: 0.8px; box-shadow: 0 6px 14px rgba(23,64,109,0.3); cursor: pointer; transition: all 0.35s ease-in-out; outline: none;"
                            onmouseover="this.style.background='linear-gradient(135deg, #2196f3, #17406d)'; this.style.boxShadow='0 0 20px #2196f3, 0 10px 20px rgba(23,64,109,0.4)'; this.style.transform='scale(1.05)'"
                            onmouseout="this.style.background='linear-gradient(135deg, #17406d, #2196f3)'; this.style.boxShadow='0 6px 14px rgba(23,64,109,0.3)'; this.style.transform='scale(1)'"
                            onmousedown="this.style.transform='scale(0.96)'"
                            onmouseup="this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 20px #2196f3, 0 10px 20px rgba(23,64,109,0.4)'">
                            <i class="bi bi-check-circle me-2"></i> Register
                        </button>
                    </div>
                </form>
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