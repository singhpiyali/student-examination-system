<?php
require("include/connect.php");
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'], $_SESSION['attempt_no'])) {
    echo json_encode(['status'=>'error','message'=>'Session expired']);
    exit;
}

$user_id    = $_SESSION['user_id'];
$attempt_no = $_SESSION['attempt_no'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Invalid request']);
    exit;
}

$qsid        = (int)($_POST['qsid'] ?? 0);
$question_id = (int)($_POST['question_id'] ?? 0);
$answer      = strtoupper($_POST['answer'] ?? '');

if ($qsid <= 0 || $question_id <= 0 || !in_array($answer,['A','B','C'])) {
    echo json_encode(['status'=>'error','message'=>'Invalid input']);
    exit;
}

/* VERIFY QUESTION */
$check = $pdo->prepare("SELECT id FROM questions WHERE id=? AND qid=?");
$check->execute([$question_id, $qsid]);
if (!$check->fetch()) {
    echo json_encode(['status'=>'error','message'=>'Invalid question']);
    exit;
}

/* UPSERT ANSWER (ATTEMPT SAFE) */
$stmt = $pdo->prepare("
    SELECT id FROM exam_answers
    WHERE user_id=? AND qsid=? AND attempt_no=? AND question_id=?
");
$stmt->execute([$user_id,$qsid,$attempt_no,$question_id]);

if ($stmt->fetch()) {
    $pdo->prepare("
        UPDATE exam_answers
        SET selected_option=?
        WHERE user_id=? AND qsid=? AND attempt_no=? AND question_id=?
    ")->execute([$answer,$user_id,$qsid,$attempt_no,$question_id]);
} else {
    $pdo->prepare("
        INSERT INTO exam_answers
        (user_id, qsid, attempt_no, question_id, selected_option)
        VALUES (?,?,?,?,?)
    ")->execute([$user_id,$qsid,$attempt_no,$question_id,$answer]);
}

echo json_encode(['status'=>'success']);
