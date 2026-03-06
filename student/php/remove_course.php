<?php
session_start();
require("include/connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$cid = $_GET['cid'];

/* DELETE ENROLLMENT */
$stmt = $pdo->prepare("
    DELETE FROM course_enrollment
    WHERE user_id = ? AND cid = ?
");
$stmt->execute([$user_id, $cid]);

header("Location: stu_course.php");
exit;
?>
