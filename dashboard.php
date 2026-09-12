<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: student-login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student info
$student_id = $_SESSION['student_id'];
$student_sql = "SELECT * FROM students WHERE id='$student_id'";
$student_result = $conn->query($student_sql);
$student = $student_result->fetch_assoc();

// Fetch issued books
$books_sql = "SELECT * FROM issued_books WHERE student_id='$student_id'";
$books_result = $conn->query($books_sql);

// Fetch notices/resources
$notices_sql = "SELECT * FROM notices ORDER BY created_at DESC LIMIT 5";
$notices_result = $conn->query($notices_sql);

// Handle password update
$update_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];
    if (!password_verify($old_password, $student['password'])) {
        $update_message = "<div class='alert alert-danger'>Old password is incorrect!</div>";
    } elseif ($new_password !== $confirm_new_password) {
        $update_message = "<div class='alert alert-danger'>New passwords do not match!</div>";
    } else {
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE students SET password='$hashed_new_password' WHERE id='$student_id'";
        if ($conn->query($update_sql) === TRUE) {
            $update_message = "<div class='alert alert-success'>Password updated successfully!</div>";
        } else {
            $update_message = "<div class='alert alert-danger'>Error updating password!</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - LMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); font-family: 'Segoe UI', sans-serif; }
        .dashboard-box { background: #fff; border-radius: 16px; box-shadow: 0 8px 32px rgba(33,150,243,0.10); padding: 32px 28px; margin-top: 30px; margin-bottom: 30px; }
        .section-title { color: #17406d; font-weight: 700; margin-bottom: 18px; }
        .table th, .table td { vertical-align: middle; }
        .navbar { background-color: #e3f2fd; border-bottom: 2px solid #2196f3; }
        .footer-section { background-color: #e3f2fd; color: #17406d; padding: 40px 20px; }
        .footer-section ul li:hover { text-decoration: underline; cursor: pointer; }
        footer { background-color: #17406d; border-top: 2px solid #17406d; text-align: center; }
        footer p, footer span { color: #fff; font-size: 0.95rem; }
        .ticker { background: #bbdefb; padding: 6px 10px; font-weight: 500; color: #17406d; font-size: 1rem; }
        @media (max-width: 768px) {
            .dashboard-box { padding: 16px 8px; }
            .footer-section { padding: 20px 5px; }
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
        <div class="d-flex align-items-center">
            <span id="header-datetime" class="me-3" style="font-size:0.98rem"></span>
            <a href="logout.php" class="btn btn-danger btn-sm rounded-pill px-3"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
</nav>

<!-- Ticker -->
<div class="ticker text-center">
    <marquee behavior="" direction="">Library opens at 8:00 AM and closes at 8:00 PM</marquee>
</div>

<div class="container">
    <div class="dashboard-box">
        <h2 class="section-title"><i class="bi bi-person-circle"></i> Welcome, <?php echo htmlspecialchars($student['fullname']); ?>!</h2>
        <p class="mb-4" style="font-size:1.1rem;color:#17406d;">This is your LMS dashboard. Here you can view your personal info, issued books, notices, and manage your password.</p>
        <h4 class="section-title"><i class="bi bi-person-lines-fill"></i> Personal Information</h4>
        <table class="table table-bordered">
            <tr><th>Name</th><td><?php echo htmlspecialchars($student['fullname']); ?></td></tr>
            <tr><th>Surname</th><td><?php echo htmlspecialchars($student['surname']); ?></td></tr>
            <tr><th>Father Name</th><td><?php echo htmlspecialchars($student['fathername']); ?></td></tr>
            <tr><th>Email</th><td><?php echo htmlspecialchars($student['email']); ?></td></tr>
            <tr><th>CNIC</th><td><?php echo htmlspecialchars($student['cnic']); ?></td></tr>
            <tr><th>Roll Number</th><td><?php echo htmlspecialchars($student['rollnumber']); ?></td></tr>
            <tr><th>Phone Number</th><td><?php echo htmlspecialchars($student['phonenumber']); ?></td></tr>
            <tr><th>Home Address</th><td><?php echo htmlspecialchars($student['homeaddress']); ?></td></tr>
            <tr><th>Department</th><td><?php echo htmlspecialchars($student['department']); ?></td></tr>
            <tr><th>Program</th><td><?php echo htmlspecialchars($student['program']); ?></td></tr>
            <tr><th>Category</th><td><?php echo htmlspecialchars($student['category']); ?></td></tr>
        </table>

        <h4 class="section-title mt-4"><i class="bi bi-book"></i> Issued Books / Library Records</h4>
        <?php if ($books_result->num_rows > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($book = $books_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['book_title']); ?></td>
                        <td><?php echo htmlspecialchars($book['issue_date']); ?></td>
                        <td><?php echo htmlspecialchars($book['due_date']); ?></td>
                        <td><?php echo htmlspecialchars($book['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No books issued.</div>
        <?php endif; ?>

        <h4 class="section-title mt-4"><i class="bi bi-megaphone"></i> Latest Library Notices / Resources</h4>
        <?php if ($notices_result->num_rows > 0): ?>
            <ul class="list-group mb-4">
                <?php while($notice = $notices_result->fetch_assoc()): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($notice['title']); ?></strong>
                        <br>
                        <span><?php echo htmlspecialchars($notice['description']); ?></span>
                        <br>
                        <small class="text-muted"><?php echo htmlspecialchars($notice['created_at']); ?></small>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-info">No notices available.</div>
        <?php endif; ?>

        <h4 class="section-title mt-4"><i class="bi bi-key"></i> Change Password</h4>
        <?php echo $update_message; ?>
        <form method="post" class="row g-3">
            <div class="col-md-4">
                <input type="password" name="old_password" class="form-control" placeholder="Old Password" required>
            </div>
            <div class="col-md-4">
                <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
            </div>
            <div class="col-md-4">
                <input type="password" name="confirm_new_password" class="form-control" placeholder="Confirm New Password" required>
            </div>
            <div class="col-12">
                <button type="submit" name="update_password" class="btn btn-primary">Update Password</button>
            </div>
        </form>
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