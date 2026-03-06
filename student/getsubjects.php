<?php
  require("include/connect.php");
  $course_id=$_REQUEST['course_id'];

  $sql = "SELECT id, sub_name FROM subject WHERE course_id=:course_id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':course_id', $course_id);
  $stmt->execute();
  $subjects = $stmt->fetchAll(PDO::FETCH_OBJ);
  foreach ($subjects as $subject) {
    echo "<option value='{$subject->id}'>" . htmlspecialchars($subject->sub_name) . "</option>";
  }
?>

