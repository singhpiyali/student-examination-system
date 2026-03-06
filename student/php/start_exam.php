<?php
session_start();
require("include/connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$qsid    = (int)($_GET['qsid'] ?? 0);

if ($qsid <= 0) {
    die("Invalid exam set");
}

/* 🔢 CHECK TOTAL ATTEMPTS */
$cntStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM exam_attempts 
    WHERE user_id = ? AND qsid = ?
");
$cntStmt->execute([$user_id, $qsid]);
$attemptCount = (int)$cntStmt->fetchColumn();

/* ❌ BLOCK AFTER 3 ATTEMPTS */
if ($attemptCount >= 3) {
    header("Location: result.php?qsid=".$qsid);
    exit;
}

/* 🔢 Get next attempt number */
$stmt = $pdo->prepare("
    SELECT COALESCE(MAX(attempt_no), 0) + 1
    FROM exam_attempts
    WHERE user_id = ? AND qsid = ?
");
$stmt->execute([$user_id, $qsid]);
$attempt_no = (int)$stmt->fetchColumn();

/* 📝 Insert exam attempt */
$insert = $pdo->prepare("
    INSERT INTO exam_attempts
    (user_id, qsid, attempt_no, start_time, status)
    VALUES (?, ?, ?, NOW(), 'started')
");
$insert->execute([$user_id, $qsid, $attempt_no]);

/* 🧠 SESSION */
$_SESSION['qsid']       = $qsid;
$_SESSION['attempt_no'] = $attempt_no;

/* 🚀 Go to exam */
header("Location: viewquestions.php?qsid=".$qsid);
exit;
