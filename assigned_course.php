<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "include/connect.php";

if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-warning'>⚠️ Please log in to view assigned courses.</div>";
    return;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT course.name
        FROM course
        INNER JOIN course_enrollment 
            ON course.id = course_enrollment.cid
        WHERE course_enrollment.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$courses = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<div class="card shadow-sm h-100 w-100">
    <div class="card-body d-flex flex-column">

        <h5 class="fw-bold mb-3">🎓 Your Assigned Courses</h5>

        <?php if ($courses): ?>
            <ul class="list-unstyled mb-3">
                <?php foreach ($courses as $course): ?>
                    <li class="mb-2">
                        👍 <?= htmlspecialchars($course->name) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-muted">No courses assigned yet.</p>
        <?php endif; ?>

        <!-- Push button to bottom -->
        <div class="mt-auto">
            <a href="stu_course.php" class="btn btn-primary w-100">
                Go to Courses
            </a>
        </div>

    </div>
</div>
