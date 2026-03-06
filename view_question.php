<?php
session_start();
require("include/connect.php");

/* ================= LOGIN CHECK ================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ================= OPTIONAL PRESELECT ================= */
$cid = isset($_GET['cid']) ? (int)$_GET['cid'] : 0;
$sid = isset($_GET['sid']) ? (int)$_GET['sid'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Online Examination | Select Exam</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
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

<!-- ================= SIDEBAR ================= -->
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

<!-- ================= HEADER ================= -->
<?php include("include/header.php"); ?>

<!-- ================= CONTENT ================= -->
<div class="right_col" role="main">

<div class="page-title">
<div class="title_left">
<h3>Select Exam</h3>
</div>
</div>

<div class="clearfix"></div>

<div class="row">
<div class="col-md-12">
<div class="x_panel">

<div class="x_title">
<h2>Choose Course → Subject → Set</h2>
<div class="clearfix"></div>
</div>

<div class="x_content">
<br>

<!-- ================= COURSE ================= -->
<div class="form-group row">
<label class="col-md-3 col-form-label"><b>Course</b></label>
<div class="col-md-6">
<select id="course" class="form-control" <?= $cid ? 'disabled' : '' ?>>
<option value="">-- Select Course --</option>
<?php
$stmt = $pdo->prepare("
    SELECT c.id, c.name
    FROM course c
    JOIN course_enrollment ce ON ce.cid = c.id
    WHERE ce.user_id = ?
");
$stmt->execute([$user_id]);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $c) {
    $sel = ($cid == $c['id']) ? 'selected' : '';
    echo "<option value='{$c['id']}' $sel>{$c['name']}</option>";
}
?>
</select>
</div>
</div>

<!-- ================= SUBJECT ================= -->
<div class="form-group row">
<label class="col-md-3 col-form-label"><b>Subject</b></label>
<div class="col-md-6">
<select id="subject" class="form-control" <?= $sid ? 'disabled' : '' ?>>
<option value="">-- Select Subject --</option>
<?php
if ($cid) {
    $stmt = $pdo->prepare("
        SELECT id, sub_name 
        FROM subject 
        WHERE course_id = ?
    ");
    $stmt->execute([$cid]);
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $s) {
        $sel = ($sid == $s['id']) ? 'selected' : '';
        echo "<option value='{$s['id']}' $sel>{$s['sub_name']}</option>";
    }
}
?>
</select>
</div>
</div>

<!-- ================= QUESTION SET ================= -->
<div class="form-group row">
<label class="col-md-3 col-form-label"><b>Question Set</b></label>
<div class="col-md-6">
<select id="qset" class="form-control">
<option value="">-- Select Question Set --</option>
<?php
if ($sid) {
    $stmt = $pdo->prepare("
        SELECT id, q_set_name
        FROM question_bank
        WHERE subject_id = ?
    ");
    $stmt->execute([$sid]);
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $q) {
        echo "<option value='{$q['id']}'>{$q['q_set_name']}</option>";
    }
}
?>
</select>
</div>
</div>

<div class="ln_solid"></div>

<!-- ================= START BUTTON ================= -->
<div class="form-group row">
<div class="col-md-6 offset-md-3">
<button type="button" class="btn btn-success" onclick="startExam()">
<i class="fa fa-play"></i> Start Exam
</button>
</div>
</div>

</div>
</div>
</div>
</div>

</div>

<!-- ================= FOOTER ================= -->
<footer>
<div class="pull-right">Online Examination System</div>
<div class="clearfix"></div>
</footer>

</div>
</div>

<!-- ================= SCRIPTS ================= -->
<script src="vendors/jquery/dist/jquery.min.js"></script>
<script src="vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="build/js/custom.min.js"></script>

<script>
/* ================= LOAD SUBJECT ================= */
$('#course').change(function(){
    let cid = $(this).val();
    $('#subject').html('<option value="">Loading...</option>');
    $('#qset').html('<option value="">-- Select Question Set --</option>');

    if(cid){
        $.get('getsubjects.php?course_id=' + cid, function(res){
            $('#subject').html(res);
        });
    }
});

/* ================= LOAD SET ================= */
$('#subject').change(function(){
    let sid = $(this).val();
    $('#qset').html('<option value="">Loading...</option>');

    if(sid){
        $.get('getset.php?sid=' + sid, function(res){
            $('#qset').html(res);
        });
    }
});

/* ================= START EXAM ================= */
function startExam(){
    let qsid = $('#qset').val();

    if(!qsid){
        alert("Please select a Question Set");
        return;
    }

    // ✅ ALWAYS go through attempt checker
    window.location.href = 'start_exam.php?qsid=' + qsid;
}
</script>

</body>
</html>
