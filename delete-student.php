<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage-students.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";
$conn = new mysqli($servername, $username, $password, $dbname);

$student_id = intval($_GET['id']);

// Delete student
$sql = "DELETE FROM students WHERE id = $student_id";
if ($conn->query($sql) === TRUE) {
    header("Location: manage-students.php?msg=deleted");
    exit();
} else {
    echo "<div class='alert alert-danger m-4'>Error deleting student.</div>";
}
?>