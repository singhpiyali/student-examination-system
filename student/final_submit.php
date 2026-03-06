<?php
session_start();
require("include/connect.php");

/* LOGIN CHECK */
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

/* POST ONLY */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid access.");
}

/* CSRF CHECK */
if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']
) {
    die("Invalid CSRF token");
}
unset($_SESSION['csrf_token']);

$user_id    = $_SESSION['user_id'];
$qsid       = (int)($_POST['qsid'] ?? 0);
$attempt_no = $_SESSION['attempt_no'] ?? 0;

if ($qsid <= 0 || $attempt_no <= 0) {
    die("Invalid exam attempt.");
}

/* BLOCK DOUBLE SUBMIT */
$chk = $pdo->prepare("
    SELECT status FROM exam_attempts
    WHERE user_id=? AND qsid=? AND attempt_no=?
");
$chk->execute([$user_id, $qsid, $attempt_no]);

if ($chk->fetchColumn() === 'submitted') {
    die("Exam already submitted.");
}

/* CALCULATE RESULT */
$stmt = $pdo->prepare("
SELECT q.id, q.ans, ea.selected_option
FROM questions q
LEFT JOIN exam_answers ea
  ON ea.question_id=q.id
 AND ea.user_id=?
 AND ea.qsid=?
 AND ea.attempt_no=?
WHERE q.qid=?
");
$stmt->execute([$user_id, $qsid, $attempt_no, $qsid]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = count($data);
$correct = $wrong = $unanswered = 0;

foreach ($data as $row) {
    if ($row['selected_option'] === null) {
        $unanswered++;
    } elseif (strtoupper($row['selected_option']) === strtoupper($row['ans'])) {
        $correct++;
    } else {
        $wrong++;
    }
}

$score = $correct;

/* INSERT / UPDATE RESULT */
$chk = $pdo->prepare("
    SELECT id FROM result
    WHERE user_id=? AND qsid=? AND attempt_no=?
");
$chk->execute([$user_id, $qsid, $attempt_no]);
$id = $chk->fetchColumn();

if ($id) {
    $pdo->prepare("
        UPDATE result SET
            total_questions=?,
            correct=?,
            wrong=?,
            unanswered=?,
            score=?,
            submitted_at=NOW()
        WHERE id=?
    ")->execute([$total,$correct,$wrong,$unanswered,$score,$id]);
} else {
    $pdo->prepare("
        INSERT INTO result
        (user_id, qsid, attempt_no, total_questions, correct, wrong, unanswered, score, submitted_at)
        VALUES (?,?,?,?,?,?,?,?,NOW())
    ")->execute([$user_id,$qsid,$attempt_no,$total,$correct,$wrong,$unanswered,$score]);
}

/* UPDATE ATTEMPT */
$pdo->prepare("
    UPDATE exam_attempts
    SET status='submitted', end_time=NOW()
    WHERE user_id=? AND qsid=? AND attempt_no=?
")->execute([$user_id,$qsid,$attempt_no]);

/* CLEAR SESSION ATTEMPT */
unset($_SESSION['attempt_no']);
?>

<!DOCTYPE html>
<html>
<head>
<title>Exam Submitted</title>
<link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="card p-4 shadow text-center">
<h3 class="text-success">✅ Exam Submitted Successfully</h3>
<p>You cannot modify answers now.</p>
<div class="d-grid gap-2 d-md-flex justify-content-md-center mt-3">
    <a href="dashboard.php" class="btn btn-primary px-4">
        🏠 Dashboard
    </a>

    <a href="result.php?qsid=<?= $qsid ?>" class="btn btn-success px-4">
        📊 View Result
    </a>

    <a href="result_review.php?qsid=<?= $qsid ?>" class="btn btn-warning px-4 text-white">
        📝 Result Review
    </a>
</div>
</div>
</div>

</body>
</html>
