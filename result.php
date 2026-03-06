<?php
session_start();
require("include/connect.php");

/* ================= LOGIN CHECK ================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ================= GET PARAMS ================= */
$qsid = (int)($_GET['qsid'] ?? 0);

if ($qsid <= 0) {
    die("Invalid exam request");
}

/* ================= GET LATEST ATTEMPT (OR PASS attempt via GET) ================= */
$attempt_no = (int)($_GET['attempt'] ?? 0);

if ($attempt_no <= 0) {
    $stmt = $pdo->prepare("
        SELECT MAX(attempt_no)
        FROM result
        WHERE user_id = ? AND qsid = ?
    ");
    $stmt->execute([$user_id, $qsid]);
    $attempt_no = (int)$stmt->fetchColumn();
}

if ($attempt_no <= 0) {
    die("Result not found");
}

/* ================= FETCH RESULT ================= */
$stmt = $pdo->prepare("
    SELECT 
        total_questions,
        correct,
        wrong,
        unanswered,
        score,
        submitted_at,
        attempt_no
    FROM result
    WHERE user_id = ?
      AND qsid = ?
      AND attempt_no = ?
");
$stmt->execute([$user_id, $qsid, $attempt_no]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    die("Result not found");
}

/* ================= CALCULATIONS ================= */
$percentage = ($result['total_questions'] > 0)
    ? round(($result['score'] / $result['total_questions']) * 100)
    : 0;

$submitTime = date("d M Y, h:i A", strtotime($result['submitted_at']));

/* Simple grade logic */
if ($percentage >= 80) $grade = 'A';
elseif ($percentage >= 60) $grade = 'B';
elseif ($percentage >= 40) $grade = 'C';
else $grade = 'D';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Exam Result</title>
<link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="card shadow">

<div class="card-header bg-success text-white">
    <h5 class="mb-0">
        📊 Exam Result (Attempt <?= $result['attempt_no'] ?>)
    </h5>
</div>

<div class="card-body">

<p class="text-muted mb-3">
    🕒 <b>Submitted On:</b> <?= $submitTime ?>
</p>

<hr>

<p><b>Total Questions:</b> <?= $result['total_questions'] ?></p>
<p class="text-success"><b>Correct:</b> <?= $result['correct'] ?></p>
<p class="text-danger"><b>Wrong:</b> <?= $result['wrong'] ?></p>
<p class="text-warning"><b>Unanswered:</b> <?= $result['unanswered'] ?></p>

<hr>

<p><b>Score:</b> <?= $result['score'] ?> / <?= $result['total_questions'] ?></p>
<p><b>Percentage:</b> <?= $percentage ?>%</p>
<p><b>Grade:</b> <?= $grade ?></p>

<hr>

<div class="d-flex gap-2">
    <a href="result_review.php?qsid=<?= $qsid ?>&attempt=<?= $attempt_no ?>" 
       class="btn btn-info text-white">
        📝 Answer Review
    </a>

    <a href="dashboard.php" class="btn btn-secondary">
        🏠 Dashboard
    </a>

    <a href="result_list.php" class="btn btn-success">
        📋 View All Results
    </a>
</div>

</div>
</div>
</div>

</body>
</html>
