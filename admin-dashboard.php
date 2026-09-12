<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : "Admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - LMS</title>
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
        .card-link { text-decoration: none; }
        .dashboard-card {
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(33,150,243,0.10);
            transition: transform 0.15s;
            border: none;
        }
        .dashboard-card:hover {
            transform: translateY(-5px) scale(1.03);
            box-shadow: 0 8px 32px rgba(33,150,243,0.18);
        }
        .dashboard-icon {
            font-size: 2.5rem;
            color: #2196f3;
        }
        .dashboard-count {
            font-size: 2rem;
            font-weight: bold;
            color: #17406d;
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
                <small style="color:#17406d;">Admin Panel - Dashboard</small>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <span class="me-3 fw-bold" style="color:#17406d;"><i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($admin_name); ?></span>
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
        <h2 class="section-title"><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
        <div class="row g-4">
            <!-- Students -->
            <div class="col-md-3 col-sm-6">
                <a href="manage-students.php" class="card-link">
                    <div class="card dashboard-card text-center p-3">
                        <div class="dashboard-icon mb-2"><i class="bi bi-people"></i></div>
                        <div class="dashboard-count">
                            <?php
                            $conn = new mysqli("localhost", "root", "", "lms");
                            $res = $conn->query("SELECT COUNT(*) as c FROM students");
                            $row = $res->fetch_assoc();
                            echo $row['c'];
                            ?>
                        </div>
                        <div class="fw-bold mt-1">Students</div>
                    </div>
                </a>
            </div>
            <!-- Books -->
            <div class="col-md-3 col-sm-6">
                <a href="manage-books.php" class="card-link">
                    <div class="card dashboard-card text-center p-3">
                        <div class="dashboard-icon mb-2"><i class="bi bi-book"></i></div>
                        <div class="dashboard-count">
                            <?php
                            $res = $conn->query("SELECT COUNT(*) as c FROM books");
                            $row = $res->fetch_assoc();
                            echo $row['c'];
                            ?>
                        </div>
                        <div class="fw-bold mt-1">Books</div>
                    </div>
                </a>
            </div>
            <!-- Issued Books -->
            <div class="col-md-3 col-sm-6">
                <a href="issued-books.php" class="card-link">
                    <div class="card dashboard-card text-center p-3">
                        <div class="dashboard-icon mb-2"><i class="bi bi-journal-arrow-up"></i></div>
                        <div class="dashboard-count">
                            <?php
                            $res = $conn->query("SELECT COUNT(*) as c FROM issued_books");
                            $row = $res->fetch_assoc();
                            echo $row['c'];
                            ?>
                        </div>
                        <div class="fw-bold mt-1">Issued Books</div>
                    </div>
                </a>
            </div>
            <!-- Admins -->
            <div class="col-md-3 col-sm-6">
                <a href="manage-admins.php" class="card-link">
                    <div class="card dashboard-card text-center p-3">
                        <div class="dashboard-icon mb-2"><i class="bi bi-person-badge"></i></div>
                        <div class="dashboard-count">
                            <?php
                            $res = $conn->query("SELECT COUNT(*) as c FROM admins");
                            $row = $res->fetch_assoc();
                            echo $row['c'];
                            ?>
                        </div>
                        <div class="fw-bold mt-1">Admins</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col text-center">
                <a href="manage-students.php" class="btn btn-outline-primary dashboard-btn"><i class="bi bi-people"></i> Manage Students</a>
                <a href="manage-books.php" class="btn btn-outline-primary dashboard-btn"><i class="bi bi-book"></i> Manage Books</a>
                <a href="issue-books.php" class="btn btn-outline-primary dashboard-btn"><i class="bi bi-journal-arrow-up"></i> Issue Book</a>
                <a href="issued-books.php" class="btn btn-outline-primary dashboard-btn"><i class="bi bi-journal-check"></i> Issued Books</a>
                <a href="manage-admins.php" class="btn btn-outline-primary dashboard-btn"><i class="bi bi-person-badge"></i> Manage Admins</a>
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