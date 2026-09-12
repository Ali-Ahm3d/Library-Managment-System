<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - Library Management System</title>
    <!-- Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            font-family: 'Segoe UI', sans-serif;
        }
        .nav-link-btn {
            background-color: #17406d;
            border-color: #17406d;
            font-size: 0.95rem;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 2px 6px rgba(23, 64, 109, 0.4);
        }
        .nav-link-btn:hover {
            background-color: #0f2f4d;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(15, 47, 77, 0.6);
        }
        .info-list li {
            margin-bottom: 8px;
            font-size: 1rem;
        }
        .footer-section {
            background: linear-gradient(135deg, #bbdefb 0%, #e3f2fd 100%);
            color: #17406d;
            padding: 40px 20px;
            box-shadow: inset 0 0 15px rgba(23, 64, 109, 0.15);
        }
        .footer-section ul li:hover {
            text-decoration: underline;
            cursor: pointer;
        }
        footer {
            background: #17406d;
            border-top: 3px solid #0f2f4d;
            text-align: center;
            box-shadow: 0 -4px 8px rgba(15, 47, 77, 0.7);
        }
        footer p, footer span {
            color: #fff;
            font-size: 1rem;
        }
        .ticker {
            background: #bbdefb;
            padding: 8px 10px;
            font-weight: 600;
            color: #17406d;
            font-size: 1.1rem;
            letter-spacing: 1px;
            box-shadow: inset 0 2px 6px rgba(23, 64, 109, 0.3);
        }
        .card-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(33,150,243,0.15);
            padding: 28px 22px;
            transition: box-shadow 0.3s ease;
        }
        .card-box:hover {
            box-shadow: 0 10px 30px rgba(33,150,243,0.25);
        }
        .gallery-section {
            margin: 50px 0 30px 0;
        }
        .gallery-title {
            color: #17406d;
            font-weight: 700;
            margin-bottom: 28px;
            text-align: center;
            font-size: 2rem;
            letter-spacing: 1px;
            text-shadow: 1px 1px 3px rgba(33,150,243,0.3);
        }
        .carousel {
            max-width: 500px;
            margin: 0 auto;
        }
        .gallery-img {
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(33,150,243,0.15);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .carousel-item img:hover {
            transform: scale(1.08);
            box-shadow: 0 12px 40px rgba(33,150,243,0.3);
        }
        .animate__animated.animate__fadeInUp {
            --animate-duration: 1.2s;
        }
        .welcome-title {
            color: #17406d;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 18px;
            text-shadow: 1px 1px 2px rgba(23, 64, 109, 0.4);
        }
        .welcome-text {
            font-size: 1.1rem;
            color: #17406d;
        }
        .highlight {
            color: #2196f3;
            font-weight: bold;
        }
        /* Center logo image vertically and horizontally */
        nav .container-fluid > div.d-flex.align-items-center:first-child {
            gap: 10px;
            justify-content: center;
        }
        nav .container-fluid > div.d-flex.align-items-center:first-child img {
            display: block;
            margin: 0 auto;
            height: 60px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-light border-bottom" style="background-color:#e3f2fd; border-bottom:2px solid #2196f3;">
    <div class="container-fluid d-flex justify-content-between align-items-center py-2">
        <div class="d-flex align-items-center">
            <img src="lms-logo.png" alt="LMS Logo" style="height:60px; margin-right:10px;">
            <div>
                <span class="h4 mb-0 fw-bold" style="color:#17406d; letter-spacing:2px;">Institute of Mathematics & Computer Science (LMS)</span><br>
                <small style="color:#17406d;">Library Management System</small>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <span id="header-datetime" class="me-3" style="font-size:1rem"></span>
            <a href="admin-login.php" class="btn btn-sm rounded-pill px-3 me-2 nav-link-btn" style="color:#fff;">Admin Login</a>
            <a href="student-login.php" class="btn btn-sm rounded-pill px-3 me-2 nav-link-btn" style="color:#fff;">Student Login</a>
            <a href="registration.php" class="btn btn-sm rounded-pill px-3 nav-link-btn" style="color:#fff;">Register</a>
        </div>
    </div>
</nav>

<!-- Ticker -->
<div class="ticker text-center" style="font-size:rem;">
    <marquee behavior="" direction="">Library opens at 8:00 AM and closes at 8:00 PM</marquee>
</div>

<!-- Main Content -->
<div class="container my-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-4 mb-3">
            <div class="card-box animate__animated animate__fadeInLeft">
                <h5 class="mb-3"><i class="bi bi-clock"></i> Library Timing</h5>
                <ul class="list-unstyled info-list">
                    <li>Opening: <span class="highlight">8:00 AM</span></li>
                    <li>Closing: <span class="highlight">8:00 PM</span></li>
                    <li><span class="highlight">(Sunday Closed)</span></li>
                </ul>
                <h5 class="mb-3"><i class="bi bi-star"></i> Facilities</h5>
                <ul class="list-unstyled info-list">
                    <li><i class="bi bi-check-circle"></i> Full furniture</li>
                    <li><i class="bi bi-wifi"></i> Free Wi-Fi</li>
                    <li><i class="bi bi-newspaper"></i> Newspapers</li>
                    <li><i class="bi bi-people"></i> Discussion Room</li>
                    <li><i class="bi bi-droplet"></i> RO Water</li>
                </ul>
            </div>
        </div>
        <!-- Main Section -->
        <div class="col-md-8">
            <div class="card-box animate__animated animate__fadeInRight">
                <div class="welcome-title"><i class="bi bi-book-half"></i> Welcome to the Library Management System</div>
                <div class="welcome-text mb-2">
                    Discover a smarter way to manage your library. <span class="highlight">Register</span> as a student, <span class="highlight">log in</span> to your account, and explore our collection of books and resources.
                </div>
                <div class="welcome-text mb-2">
                    <i class="bi bi-info-circle"></i> Need help? Our friendly staff is always here to assist you. Enjoy a comfortable study space, fast Wi-Fi, and a wide range of books.
                </div>
                <div class="welcome-text">
                    <i class="bi bi-lightbulb"></i> <span class="highlight">Tip:</span> Use the search and catalogue features to quickly find your favorite books!
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Library Gallery Section -->
<div class="container gallery-section">
    <h3 class="gallery-title animate__animated animate__fadeInUp text-center"><i class="bi bi-images"></i> Library Gallery</h3>
    <div id="libraryGalleryCarousel" class="carousel slide mx-auto animate__animated animate__fadeInUp" data-bs-ride="carousel" style="max-width:500px;">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="library1.jpg" class="d-block w-100 gallery-img" alt="Library Interior">
            </div>
            <div class="carousel-item">
                <img src="library2.jpg" class="d-block w-100 gallery-img" alt="Bookshelves">
            </div>
            <div class="carousel-item">
                <img src="library3.jpg" class="d-block w-100 gallery-img" alt="Reading Area">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#libraryGalleryCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#libraryGalleryCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<!-- Footer Top Section -->
<div class="container-fluid py-4 footer-section">
    <div class="row justify-content-center text-center text-md-start">
        <!-- Logo and Address -->
        <div class="col-md-3 mb-3">
            <img src="lms-logo.png" alt="IMCS University of Sindh Jamshoro" style="height:70px; margin-bottom:10px;">
            <div class="fw-bold">Sindh University<br>Jamshoro</div>
            <div class="mt-2" style="font-size:1rem;">
                University Road, Jamshoro<br>
                Sindh, Pakistan
            </div>
        </div>
        <!-- Useful Links -->
        <div class="col-md-3 mb-3">
            <ul class="list-unstyled" style="font-size:1rem;">
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
            <ul class="list-unstyled" style="font-size:1rem;">
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

<!-- Bootstrap JS for carousel functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
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