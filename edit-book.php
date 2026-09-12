<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: admin-login.php"); exit(); }
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { header("Location: manage-books.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "lms");
$book_id = intval($_GET['id']);
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title']; $author = $_POST['author']; $isbn = $_POST['isbn'];
    $category = $_POST['category']; $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, isbn=?, category=?, status=? WHERE id=?");
    $stmt->bind_param("sssssi", $title, $author, $isbn, $category, $status, $book_id);
    if ($stmt->execute()) { $message = "<div class='alert alert-success'>Book updated.</div>"; }
    else { $message = "<div class='alert alert-danger'>Update failed.</div>"; }
    $stmt->close();
}
$result = $conn->query("SELECT * FROM books WHERE id = $book_id");
if (!$result || $result->num_rows != 1) { echo "<div class='alert alert-danger m-4'>Book not found.</div>"; exit(); }
$book = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Book</h2>
    <?php echo $message; ?>
    <form method="post">
        <div class="mb-3"><label>Title</label><input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($book['title']); ?>"></div>
        <div class="mb-3"><label>Author</label><input type="text" name="author" class="form-control" required value="<?php echo htmlspecialchars($book['author']); ?>"></div>
        <div class="mb-3"><label>ISBN</label><input type="text" name="isbn" class="form-control" value="<?php echo htmlspecialchars($book['isbn']); ?>"></div>
        <div class="mb-3"><label>Category</label><input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($book['category']); ?>"></div>
        <div class="mb-3"><label>Status</label>
            <select name="status" class="form-control">
                <option value="Available" <?php if($book['status']=='Available') echo 'selected'; ?>>Available</option>
                <option value="Issued" <?php if($book['status']=='Issued') echo 'selected'; ?>>Issued</option>
                <option value="Reserved" <?php if($book['status']=='Reserved') echo 'selected'; ?>>Reserved</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update Book</button>
        <a href="manage-books.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>