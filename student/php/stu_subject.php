<?php
session_start();
require("include/connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$cid = intval($_GET['cid'] ?? 0);

// Course name
$cstmt = $pdo->prepare("SELECT name FROM course WHERE id=?");
$cstmt->execute([$cid]);
$course = $cstmt->fetch(PDO::FETCH_ASSOC);

// Subjects
$sstmt = $pdo->prepare("
    SELECT id, sub_name 
    FROM subject 
    WHERE course_id = ?
    ORDER BY sub_name
");
$sstmt->execute([$cid]);
$subjects = $sstmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($course['name']) ?> – Subjects</title>

<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="build/css/custom.min.css" rel="stylesheet">

<style>
.right_col { margin-top: 10px !important; }

.subject-card {
    background: #fff;
    border-radius: 10px;
    padding: 22px;
    border: 1px solid #eee;
    margin-bottom: 25px;
}

.subject-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
}

.set-row {
    border: 1px dashed #ddd;
    border-radius: 6px;
    padding: 12px 15px;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.set-info small {
    color: #777;
}
</style>
</head>

<body class="nav-md">
<div class="container body">
<div class="main_container">

<!-- SIDEBAR -->
<div class="col-md-3 left_col">
<div class="left_col scroll-view">
<div class="navbar nav_title">
<a href="dashboard.php" class="site_title">
<i class="fa fa-graduation-cap"></i> <span>Online Exam</span>
</a>
</div>
<?php include("include/sidebar.php"); ?>
</div>
</div>

<!-- HEADER -->
<?php include("include/header.php"); ?>

<!-- CONTENT -->
<div class="right_col" role="main">

<div class="page-title">
<div class="title_left">
<h3><?= htmlspecialchars($course['name']) ?> — Subjects</h3>
</div>
</div>
<div class="clearfix"></div>

<div class="row">

<?php foreach ($subjects as $sub): ?>
<div class="col-md-6 col-sm-12">
<div class="subject-card">

<div class="subject-title">
<i class="fa fa-book text-primary"></i>
<?= htmlspecialchars($sub['sub_name']) ?>
</div>

<?php
// Sets under this subject
$setStmt = $pdo->prepare("
    SELECT id, q_set_name, no_of_question, exam_time
    FROM question_bank
    WHERE cid = ? AND sid = ?
    ORDER BY q_set_name
");
$setStmt->execute([$cid, $sub['id']]);
$sets = $setStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if ($sets): foreach ($sets as $set): ?>

<?php
// 🔢 Total attempts count
$cntStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM exam_attempts 
    WHERE user_id = ? AND qsid = ?
");
$cntStmt->execute([$_SESSION['user_id'], $set['id']]);
$attemptCount = (int)$cntStmt->fetchColumn();

// 🔍 Latest attempt status
$lastStmt = $pdo->prepare("
    SELECT status 
    FROM exam_attempts 
    WHERE user_id = ? AND qsid = ?
    ORDER BY attempt_no DESC
    LIMIT 1
");
$lastStmt->execute([$_SESSION['user_id'], $set['id']]);
$lastStatus = $lastStmt->fetchColumn();
?>

<div class="set-row">
    <div class="set-info">
        <strong><?= htmlspecialchars($set['q_set_name']) ?></strong><br>
        <small>
            <?= $set['no_of_question'] ?> Questions · 
            <?= $set['exam_time'] ?> min
        </small>
    </div>

    

    <?php if ($attemptCount == 0): ?>

    <a href="start_exam.php?qsid=<?= $set['id'] ?>"
       class="btn btn-success btn-sm">
        Start
    </a>

<?php elseif ($attemptCount < 3 && $lastStatus === 'started'): ?>

    <a href="viewquestions.php?qsid=<?= $set['id'] ?>"
       class="btn btn-warning btn-sm">
        Resume
    </a>

<?php elseif ($attemptCount < 3 && $lastStatus === 'submitted'): ?>

    <a href="start_exam.php?qsid=<?= $set['id'] ?>"
       class="btn btn-primary btn-sm">
        Reattempt (<?= $attemptCount ?>/3)
    </a>

<?php else: ?>

    <a href="result.php?qsid=<?= $set['id'] ?>"
       class="btn btn-info btn-sm">
        View Result
    </a>

<?php endif; ?>

</div>

<?php endforeach; else: ?>
<p class="text-muted">No exam sets available.</p>
<?php endif; ?>

</div>
</div>
<?php endforeach; ?>

</div>

<a href="stu_course.php" class="btn btn-secondary mt-3">
← Back to Courses
</a>

</div>
</div>
</div>

<script src="vendors/jquery/dist/jquery.min.js"></script>
<script src="vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="build/js/custom.min.js"></script>
</body>
</html>
