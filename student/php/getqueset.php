<?php
  require("include/connect.php");

  if (!isset($_REQUEST['cid']) || !is_numeric($_REQUEST['cid'])) {
    echo '<option value="">--Select Question Set--</option>';
    exit;
}

  $cid=$_REQUEST['cid'];

  $sql = "SELECT id, q_set_name FROM question_bank WHERE cid=:cid";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':cid', $cid, PDO::PARAM_INT);
  $stmt->execute();
  $qsets = $stmt->fetchAll(PDO::FETCH_OBJ);
  
  if (empty($qsets)) {
    echo '<option value="">No question sets available</option>';
    exit;
}

  foreach ($qsets as $qset) {
    echo "<option value='{$qset->id}'>" . htmlspecialchars($qset->q_set_name) . "</option>";
  }
?>


