<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: admin-login.php"); exit(); }
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { header("Location: manage-books.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "lms");
$book_id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM books WHERE id = $book_id");
if (!$result || $result->num_rows != 1) { echo "<div class='alert alert-danger m-4'>Book not found.</div>"; exit(); }
$book = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Book - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Book Details</h2>
    <table class="table table-bordered">
        <?php foreach ($book as $key => $value): ?>
            <tr>
                <th><?php echo ucwords(str_replace('_',' ', $key)); ?></th>
                <td><?php echo htmlspecialchars($value); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="manage-books.php" class="btn btn-primary">Back</a>
</div>
</body>
</html>