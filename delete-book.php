<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: admin-login.php"); exit(); }
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { header("Location: manage-books.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "lms");
$book_id = intval($_GET['id']);
$conn->query("DELETE FROM books WHERE id = $book_id");
header("Location: manage-books.php?msg=deleted");
exit();
?>