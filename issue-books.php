<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";
$conn = new mysqli($servername, $username, $password, $dbname);

// Fetch students and available books
$students = $conn->query("SELECT id, fullname, rollnumber FROM students ORDER BY fullname ASC");
$books = $conn->query("SELECT id, title FROM books WHERE status = 'Available' ORDER BY title ASC");

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $book_id = intval($_POST['book_id']);
    $issue_date = $_POST['issue_date'];
    $return_date = $_POST['return_date'];

    // Insert into issued_books table (create this table if not exists)
    $stmt = $conn->prepare("INSERT INTO issued_books (student_id, book_id, issue_date, return_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $student_id, $book_id, $issue_date, $return_date);
    if ($stmt->execute()) {
        // Update book status to Issued
        $conn->query("UPDATE books SET status='Issued' WHERE id=$book_id");
        $message = "<div class='alert alert-success'>Book issued successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Failed to issue book.</div>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Issue Book - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); font-family: 'Segoe UI', sans-serif; }
        .dashboard-box { background: #fff; border-radius: 16px; box-shadow: 0 8px 32px rgba(33,150,243,0.10); padding: 32px 28px; margin-top: 30px; margin-bottom: 30px; }
        .section-title { color: #17406d; font-weight: 700; margin-bottom: 18px; }
        .navbar { background-color: #e3f2fd; border-bottom: 2px solid #2196f3; }
        .footer-section { background-color: #e3f2fd; color: #17406d; padding: 40px 20px; }
        .footer-section ul li:hover { text-decoration: underline; cursor: pointer; }
        footer { background-color: #17406d; border-top: 2px solid #17406d; text-align: center; }
        footer p, footer span { color: #fff; font-size: 0.95rem; }
        .ticker { background: #bbdefb; padding: 6px 10px; font-weight: 500; color: #17406d; font-size: 1rem; }
        .dashboard-btn { margin-right: 18px; }
        .form-label { font-weight: 600; color: #17406d; }
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
                <small style="color:#17406d;">Admin Panel - Issue Book</small>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <a href="admin-dashboard.php" class="btn btn-primary dashboard-btn me-2"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <span id="header-datetime" class="me-3" style="font-size:0.98rem"></span>
            <a href="admin-logout.php" class="btn btn-danger btn-sm rounded-pill px-3"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
</nav>

<!-- Ticker -->
<div class="ticker text-center">
    <marquee behavior="" direction="">Library opens at 8:00 AM and closes at 8:00 PM</marquee>
</div>

<div class="container">
    <div class="dashboard-box">
        <h2 class="section-title"><i class="bi bi-journal-arrow-up"></i> Issue Book</h2>
        <?php echo $message; ?>
        <form method="post" autocomplete="off">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Select Student</label>
                    <select name="student_id" class="form-control" required>
                        <option value="">-- Select Student --</option>
                        <?php while($s = $students->fetch_assoc()): ?>
                            <option value="<?php echo $s['id']; ?>">
                                <?php echo htmlspecialchars($s['fullname']) . " (" . htmlspecialchars($s['rollnumber']) . ")"; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Select Book</label>
                    <select name="book_id" class="form-control" required>
                        <option value="">-- Select Book --</option>
                        <?php while($b = $books->fetch_assoc()): ?>
                            <option value="<?php echo $b['id']; ?>">
                                <?php echo htmlspecialchars($b['title']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Issue Date</label>
                    <input type="date" name="issue_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Return Date</label>
                    <input type="date" name="return_date" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Issue Book</button>
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