<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "include/connect.php";

/* User must be logged in */
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-warning'>Please log in to enroll courses.</div>";
    return;
}

$user_id = $_SESSION['user_id'];

/* Already enrolled courses */
$assignedStmt = $pdo->prepare(
    "SELECT cid FROM course_enrollment WHERE user_id = ?"
);
$assignedStmt->execute([$user_id]);
$assignedCourses = $assignedStmt->fetchAll(PDO::FETCH_COLUMN);
$assignedCourses = $assignedCourses ?: [];

/* All courses */
$stmt = $pdo->prepare("SELECT id, name FROM course");
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<!-- 🔴 CARD ONLY (NO CONTAINER / NO HTML TAGS) -->
<div class="card shadow-sm h-100 w-100">
    <div class="card-body d-flex flex-column">

        <h5 class="fw-bold mb-3">📚 Enroll in Short Courses</h5>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info py-2">
                <?= $_SESSION['message']; ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form method="POST" action="enroll_db.php">

            <div class="form-check mb-2">
                <input type="checkbox" id="select_all" class="form-check-input">
                <label class="form-check-label fw-bold" for="select_all">
                    Select All
                </label>
            </div>

            <?php foreach ($courses as $course): ?>
                <?php $isAssigned = in_array($course->id, $assignedCourses); ?>

                <div class="form-check mb-1">
                    <input type="checkbox"
                           class="form-check-input course_checkbox"
                           name="courses[]"
                           value="<?= $course->id ?>"
                           id="course_<?= $course->id ?>"
                           <?= $isAssigned ? 'checked disabled' : '' ?>>

                    <label class="form-check-label" for="course_<?= $course->id ?>">
                        <?= htmlspecialchars($course->name) ?>
                        <?= $isAssigned ? '<span class="text-success">(Already Enrolled)</span>' : '' ?>
                    </label>
                </div>
            <?php endforeach; ?>

            <div class="mt-auto">
                <button type="submit" class="btn btn-primary btn-sm w-100 mt-3">
                    Submit
                </button>
            </div>
        </form>

    </div>
</div>

<script>
document.getElementById('select_all')?.addEventListener('change', function () {
    document.querySelectorAll('.course_checkbox:not(:disabled)')
        .forEach(cb => cb.checked = this.checked);
});
</script>
