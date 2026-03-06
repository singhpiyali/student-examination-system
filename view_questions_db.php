<?php
require("include/connect.php");

$formaction = $_REQUEST['formaction'] ?? '';
$qid = $_REQUEST['qid'] ?? 0;

if ($formaction == 'delete') {
    $qid = intval($qid);  // sanitize

    $sql = "DELETE FROM questions WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $qid, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "fail";
    }
    exit;
}
?>
