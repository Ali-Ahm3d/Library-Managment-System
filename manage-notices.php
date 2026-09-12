<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Notices - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'admin-navbar.php'; // Optional: reuse your navbar ?>
<div class="container mt-5">
    <h2 class="mb-4">Manage Notices</h2>
    <!-- Add your notices management table and actions here -->
    <div class="alert alert-info">Notices management functionality coming soon.</div>
</div>
</body>
</html>