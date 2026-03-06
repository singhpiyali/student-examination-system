<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "include/connect.php";



if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* Total Exam Sets */
$stmt = $pdo->prepare("
    SELECT COUNT(qb.id) AS total
    FROM question_bank qb
    JOIN course_enrollment ce ON qb.cid = ce.cid
    WHERE ce.user_id = ?
");
$stmt->execute([$user_id]);
$row = $stmt->fetch();
$total_exam = $row->total ?? 0;


/* Attempted (Unique Submitted) */
$stmt = $pdo->prepare("
    SELECT COUNT(DISTINCT qsid) AS attempted
    FROM exam_attempts
    WHERE user_id = ? AND status = 'submitted'
");
$stmt->execute([$user_id]);
$row = $stmt->fetch();
$attempted = $row->attempted ?? 0;


/* Pending */
$pending = max(0, $total_exam - $attempted);


/* Last Score */
$stmt = $pdo->prepare("
    SELECT score 
    FROM result 
    WHERE user_id = ?
    ORDER BY id DESC 
    LIMIT 1
");
$stmt->execute([$user_id]);
$row = $stmt->fetch();
$last_score = $row->score ?? 0;

/* Progress Percentage */
$progress_percent = 0;

if ($total_exam > 0) {
    $progress_percent = round(($attempted / $total_exam) * 100);
}

/* Progress Bar Color */
$progress_class = "progress-bar-danger";

if ($progress_percent >= 75) {
    $progress_class = "progress-bar-success";
} elseif ($progress_percent >= 40) {
    $progress_class = "progress-bar-warning";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title> 🏠 Dashboard | Online Examination</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
<link href="build/css/custom.min.css" rel="stylesheet">

<link href="css/dashboard.css?v=2" rel="stylesheet">


</head>

<body class="nav-md">

<!-- ✅ REQUIRED -->
<div class="container body">
<div class="main_container">

    <!-- LEFT SIDEBAR -->
    <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border:0;">
                <a href="dashboard.php" class="site_title">
                    <i class="fa fa-graduation-cap"></i>
                    <span>Online Exam</span>
                </a>
            </div>
            <div class="clearfix"></div>
            <?php include "include/sidebar.php"; ?>
        </div>
    </div>

    <!-- TOP NAV -->
    <?php include "include/header.php"; ?>

    <!-- PAGE CONTENT -->
    <div class="right_col" role="main">

        <div class="">
            <h2 class="page-title">
    Welcome, <span class="welcome-name"><?= htmlspecialchars($_SESSION['name']) ?></span>
</h2>
<div class="result-header">
    <div class="result-title">
        <h2><i class="fa fa-bar-chart"></i> My Results</h2>
    </div>
    <a href="result_list.php" class="btn btn-primary btn-sm">
        View Detailed Results
    </a>
</div>

            <!-- STATS -->
            <div class="row result-stats">
                <div class="col-md-3 col-sm-6">
        <div class="result-card">
            <div class="card-icon">
                <i class="fa fa-book"></i>
            </div>
            <div class="card-content">
                <span>Total Exams</span>
                <h2><?= $total_exam ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="result-card">
            <div class="card-icon">
                <i class="fa fa-check-circle"></i>
            </div>
            <div class="card-content">
                <span>Attempted</span>
                <h2><?= $attempted ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="result-card">
            <div class="card-icon">
                <i class="fa fa-hourglass-half"></i>
            </div>
            <div class="card-content">
                <span>Pending</span>
                <h2><?= $pending ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="result-card score-card">
            <div class="card-icon">
                <i class="fa fa-trophy"></i>
            </div>
            <div class="card-content">
                <span>Last Score</span>
                <h2><?= $last_score ?></h2>
            </div>
        </div>
    </div>

</div>

            <div class="progress-section">
    <div class="progress-label">
    Overall Completion
    <span class="progress-percentage"><?= $progress_percent ?>%</span>
</div>
    <div class="progress">
        <div class="progress-bar <?= $progress_class ?>"style="width: <?= $progress_percent ?>%">
        </div>
    </div>
</div>
            

            <!-- COURSES (Centered & Spaced) -->
<div class="row course-section">

    <div class="col-md-6 d-flex">
        <div class="course-card flex-fill">
            <div class="course-header">
                <i class="fa fa-book"></i>
                <span>Enroll in Short Courses</span>
            </div>
            <div class="course-body">
                <?php include 'register_course.php'; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6 d-flex">
        <div class="course-card flex-fill">
            <div class="course-header">
                <i class="fa fa-graduation-cap"></i>
                <span>Your Assigned Courses</span>
            </div>
            <div class="course-body">
                <?php include 'assigned_course.php'; ?>
            </div>
        </div>
    </div>

</div>
    

</div>
</div>

    <!-- /PAGE CONTENT -->

    <footer>
   <div class="footer-content">
      © 2025 Online Examination System | Developed by Piyali Singh
   </div>
</footer>



<script src="vendors/jquery/dist/jquery.min.js"></script>
<script src="vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="vendors/nprogress/nprogress.js"></script>
<script src="build/js/custom.min.js"></script>

</body>
</html>
