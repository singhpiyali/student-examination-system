<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require("include/connect.php");

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Must select courses
if (empty($_POST['courses'])) {
    $_SESSION['message'] = "<div class='alert alert-warning'>Please select at least one course.</div>";
    header("Location: dashboard.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$courses = $_POST['courses'];

// Check if already enrolled
$checkStmt = $pdo->prepare(
    "SELECT 1 FROM course_enrollment WHERE user_id = ? AND cid = ?"
);

// Insert enrollment
$insertStmt = $pdo->prepare(
    "INSERT INTO course_enrollment (user_id, cid) VALUES (?, ?)"
);

$count = 0;

foreach ($courses as $course_id) {

    // ✅ FIXED LINE
    $cid = (int) $course_id;

    $checkStmt->execute([$user_id, $cid]);

    if (!$checkStmt->fetchColumn()) {
        $insertStmt->execute([$user_id, $cid]);
        $count++;
    }
}

$_SESSION['message'] = $count > 0
    ? "<div class='alert alert-success'>Enrollment successful.</div>"
    : "<div class='alert alert-info'>You are already enrolled in selected courses.</div>";

header("Location: dashboard.php");
exit;
