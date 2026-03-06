<?php
require("include/connect.php");

if (!isset($_GET['sid'])) {
    exit;
}

$sid = (int)$_GET['sid'];

$sql = "
    SELECT id, q_set_name
    FROM question_bank
    WHERE sid = :sid
";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':sid', $sid, PDO::PARAM_INT);
$stmt->execute();

echo "<option value=''>-- Select Question Set --</option>";
while ($set = $stmt->fetch(PDO::FETCH_OBJ)) {
    echo "<option value='{$set->id}'>" .
         htmlspecialchars($set->q_set_name) .
         "</option>";
}
