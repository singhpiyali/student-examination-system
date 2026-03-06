<?php
	require("include/connect.php"); 

	$formaction=$_REQUEST['formaction'];
	switch($formaction)
	{
		case 'delete':  $cid=$_REQUEST['cid'];
						$sql = "DELETE FROM course WHERE id=:id";
						$stmt = $pdo->prepare($sql);
						$stmt->bindValue(':id', $cid);
						$stmt->execute();
						break;

		case 'insert':	$course_name=$_REQUEST['course-name'];

						$sql = "INSERT INTO course (name) VALUES (:course_name)";
						$stmt = $pdo->prepare($sql);
						$stmt->bindValue(':course_name', $course_name);
						$stmt->execute();
						break;

		case 'update':	$cid=$_REQUEST['cid'];
						$course_name=$_REQUEST['course-name'];
						$sql = "UPDATE course SET `name`=:course_name WHERE `id`=:cid";

						$stmt = $pdo->prepare($sql);
						$stmt->bindValue(':cid', $cid);
						$stmt->bindValue(':course_name', $course_name);
						$stmt->execute();
						break;
	}

	header("Location: course.php");
?>	