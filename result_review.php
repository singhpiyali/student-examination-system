<?php
session_start();
require("include/connect.php");

/* LOGIN CHECK */
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* GET PARAMS */
$qsid    = (int)($_GET['qsid'] ?? 0);
$attempt = (int)($_GET['attempt'] ?? 0);

if ($qsid <= 0 || $attempt <= 0) {
    die("Invalid request");
}

/* FETCH QUESTIONS + ANSWERS */
$stmt = $pdo->prepare("
    SELECT 
        q.id AS question_id,
        q.ques,
        q.optiona,
        q.optionb,
        q.optionc,
        q.ans AS correct_answer,
        ea.selected_option
    FROM questions q
    LEFT JOIN exam_answers ea
        ON ea.question_id = q.id
       AND ea.user_id = ?
       AND ea.qsid = ?
       AND ea.attempt_no = ?
    WHERE q.qid = ?
    ORDER BY q.id ASC
");
$stmt->execute([$user_id, $qsid, $attempt, $qsid]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Answer Review</title>
<link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">

<style>
.option {
    padding: 6px 10px;
    border-radius: 4px;
    margin-bottom: 6px;
}

.option.correct {
    background: #d4edda;
    color: #155724;
    font-weight: bold;
}

.option.wrong {
    background: #f8d7da;
    color: #721c24;
    font-weight: bold;
}

.option.selected {
    border: 2px solid #007bff;
}

.option.unanswered {
    background: #fff3cd;
    color: #856404;
}
</style>
</head>

<body class="bg-light">

<div class="container mt-4">
<h4 class="mb-4">📝 Question-wise Review</h4>

<?php foreach ($questions as $i => $q): 

    if ($q['selected_option'] === null) {
        $status = "Unanswered";
        $statusClass = "text-warning";
    } elseif (strtoupper($q['selected_option']) === strtoupper($q['correct_answer'])) {
        $status = "Correct";
        $statusClass = "text-success";
    } else {
        $status = "Wrong";
        $statusClass = "text-danger";
    }

    $options = [
        'A' => $q['optiona'],
        'B' => $q['optionb'],
        'C' => $q['optionc']
    ];
?>

<div class="card mb-3 shadow-sm">
<div class="card-body">

<p><b>Q<?= $i+1 ?>.</b> <?= htmlspecialchars($q['ques']) ?></p>

<?php foreach ($options as $key => $text):

    $class = "option";

    if ($key === strtoupper($q['correct_answer'])) {
        $class .= " correct";
    }

    if ($q['selected_option'] !== null &&
        $key === strtoupper($q['selected_option']) &&
        $key !== strtoupper($q['correct_answer'])) {
        $class .= " wrong";
    }

    if ($key === strtoupper($q['selected_option'])) {
        $class .= " selected";
    }

    if ($q['selected_option'] === null && $key === strtoupper($q['correct_answer'])) {
        $class .= " unanswered";
    }
?>

<div class="<?= $class ?>">
    <?= $key ?>) <?= htmlspecialchars($text) ?>
</div>

<?php endforeach; ?>

<hr>

<p><b>Your Answer:</b>
    <?= $q['selected_option'] ?? '<span class="text-warning">Not Answered</span>' ?>
</p>

<p><b>Correct Answer:</b> <?= strtoupper($q['correct_answer']) ?></p>

<p><b>Status:</b>
    <span class="<?= $statusClass ?> fw-bold"><?= $status ?></span>
</p>

</div>
</div>

<?php endforeach; ?>

<a href="result.php?qsid=<?= $qsid ?>" class="btn btn-secondary mt-3">
⬅ Back to Result
</a>

</div>

</body>
</html>
