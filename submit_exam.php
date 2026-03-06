<?php

$currentTime = date("d M Y, h:i A");
session_start();
require("include/connect.php");

/* ================= LOGIN CHECK ================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ================= GET EXAM ID ================= */
if (!isset($_GET['qsid'])) {
    die("Invalid exam request");
}

$qsid = (int)$_GET['qsid'];
if ($qsid <= 0) {
    die("Invalid exam ID");
}

/* ================= ATTEMPT CHECK ================= */
$attempt_no = $_SESSION['attempt_no'] ?? 0;
if ($attempt_no <= 0) {
    die("❌ Exam not started properly.");
}

/* ================= BLOCK IF ALREADY SUBMITTED ================= */
$chk = $pdo->prepare("
    SELECT status 
    FROM exam_attempts 
    WHERE user_id=? AND qsid=? AND attempt_no=?
");
$chk->execute([$user_id, $qsid, $attempt_no]);

if ($chk->fetchColumn() === 'submitted') {
    die("❌ Exam already submitted.");
}

/* ================= FETCH TOTAL QUESTIONS ================= */
$qStmt = $pdo->prepare("
    SELECT id 
    FROM questions 
    WHERE qid=?
");
$qStmt->execute([$qsid]);
$questions = $qStmt->fetchAll(PDO::FETCH_COLUMN);

$totalQ = count($questions);

/* ================= FETCH ANSWERED QUESTIONS ================= */
$aStmt = $pdo->prepare("
    SELECT DISTINCT question_id
    FROM exam_answers
    WHERE user_id=? AND qsid=? AND attempt_no=?
");
$aStmt->execute([$user_id, $qsid, $attempt_no]);
$answered = $aStmt->fetchAll(PDO::FETCH_COLUMN);

$answeredCount   = count($answered);
$unansweredCount = $totalQ - $answeredCount;

/* ================= PROGRESS ================= */
$progress = ($totalQ > 0)
    ? round(($answeredCount / $totalQ) * 100)
    : 0;

/* ================= CSRF TOKEN ================= */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Submit Exam</title>
<link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">

<style>
.summary-box {
    display:inline-block;
    width:42px;
    height:42px;
    line-height:42px;
    text-align:center;
    margin:6px;
    border-radius:5px;
    font-weight:bold;
    color:#fff;
}
.summary-box.answered { background:#28a745; }
.summary-box.unanswered { background:#dc3545; }
</style>
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="card shadow">

<div class="card-header bg-primary text-white">
    <h5 class="mb-0">📄 Exam Submission Summary</h5>
</div>

<div class="progress m-3">
    <div class="progress-bar bg-success" style="width: <?= $progress ?>%">
        <?= $progress ?>%
    </div>
</div>

<div class="card-body">
<p class="text-muted">
    ⏰ <b>Current Time:</b> <?= $currentTime ?>
</p>
<p><b>Total Questions:</b> <?= $totalQ ?></p>
<p class="text-success"><b>Answered:</b> <?= $answeredCount ?></p>
<p class="text-danger"><b>Unanswered:</b> <?= $unansweredCount ?></p>

<?php if ($unansweredCount > 0): ?>
<div class="alert alert-warning">
    ⚠️ You have <b><?= $unansweredCount ?></b> unanswered questions.
</div>
<?php endif; ?>

<h6 class="mt-3">Question Overview</h6>

<div>
<?php foreach ($questions as $i => $qid): ?>
    <span class="summary-box <?= in_array($qid, $answered) ? 'answered' : 'unanswered' ?>">
        <?= $i + 1 ?>
    </span>
<?php endforeach; ?>
</div>

<div class="mt-2 small">
    <span class="badge bg-success">Answered</span>
    <span class="badge bg-danger">Unanswered</span>
</div>

<hr>

<div class="d-flex justify-content-between mt-3">
    <a href="viewquestions.php?qsid=<?= $qsid ?>" class="btn btn-secondary">
        ⬅ Go Back to Exam
    </a>

    <form method="post" action="final_submit.php">
        <input type="hidden" name="qsid" value="<?= $qsid ?>">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" class="btn btn-danger">
            ✅ Submit Exam Final
        </button>
    </form>
</div>

</div>
</div>
</div>

</body>
</html>
