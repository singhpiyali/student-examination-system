<?php
session_start();
require("include/connect.php");

/* LOGIN CHECK */
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* FETCH STUDENT ENROLLED COURSES */
$stmt = $pdo->prepare("
    SELECT c.id, c.name
    FROM course c
    INNER JOIN course_enrollment ce ON ce.cid = c.id
    WHERE ce.user_id = ?
    ORDER BY c.name
");
$stmt->execute([$user_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Online Examination | My Courses</title>

<link rel="icon" href="assets/images/favicon.ico" type="image/ico" />
<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
<link href="build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
<div class="container body">
<div class="main_container">

<!-- SIDEBAR -->
<div class="col-md-3 left_col">
  <div class="left_col scroll-view">
    <div class="navbar nav_title" style="border:0;">
      <a href="dashboard.php" class="site_title">
        <i class="fa fa-graduation-cap"></i> <span>Online Exam</span>
      </a>
    </div>
    <div class="clearfix"></div>
    <?php include("include/sidebar.php"); ?>
  </div>
</div>

<!-- TOP NAV -->
<?php include("include/header.php"); ?>

<!-- PAGE CONTENT -->
<div class="right_col" role="main">
<div class="">

<div class="page-title">
  <div class="title_left">
    <h3>My Courses</h3>
  </div>
</div>

<div class="clearfix"></div>

<div class="row">

<?php if (empty($courses)): ?>
  <div class="col-md-12">
    <div class="alert alert-info">
      You are not enrolled in any course yet.
    </div>
  </div>
<?php else: ?>

<?php foreach ($courses as $course): ?>
<div class="col-md-4 col-sm-6">
  <div class="x_panel">
    <div class="x_title">
      <h2><?= htmlspecialchars($course['name']) ?></h2>
      <div class="clearfix"></div>
    </div>

    <div class="x_content text-center">
      <i class="fa fa-book fa-3x text-primary"></i>

      <p class="mt-3">
        Access subjects and exams under this course.
      </p>

      <!-- NEXT PAGE WILL BE SUBJECT PAGE -->
      <a href="stu_subject.php?cid=<?= $course['id'] ?>"
         class="btn btn-success btn-sm">
        View Subjects
      </a>

      <!-- REMOVE COURSE -->
  <a href="remove_course.php?cid=<?= $course['id'] ?>"
     class="btn btn-danger btn-sm"
     onclick="return confirm('Are you sure you want to remove this course?');">
     Remove Course
  </a>
    </div>
  </div>
</div>
<?php endforeach; ?>

<?php endif; ?>

</div>

</div>
</div>

<!-- FOOTER -->
<footer>
<div class="pull-right">
Online Examination System
</div>
<div class="clearfix"></div>
</footer>

</div>
</div>

<!-- JS -->
<script src="vendors/jquery/dist/jquery.min.js"></script>
<script src="vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="build/js/custom.min.js"></script>

</body>
</html>
