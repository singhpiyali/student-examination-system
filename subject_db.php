<?php
session_start();
require_once("include/connect.php");

	$formaction=$_REQUEST['formaction'];
	switch($formaction)
	{
		case 'delete':  $sid=$_REQUEST['sid'];
						$sql = "DELETE FROM subject WHERE id=:id";
						$stmt = $pdo->prepare($sql);
						$stmt->bindValue(':id', $sid);
						$stmt->execute();
						break;
                        

		case 'insert':	$course_id = $_REQUEST['course-name']; 
                        $sub_name = $_REQUEST['sub-name'];
		                
		                $sql = "INSERT INTO subject (course_id, sub_name) VALUES (:course_id, :sub_name)";
		                $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':course_id', $course_id);
		                $stmt->bindValue(':sub_name', $sub_name);
		                $stmt->execute();
		                break;


	 case 'update':
						$sid = $_REQUEST['sid'];
						$course_id = $_REQUEST['course-name']; 
						$sub_name = $_REQUEST['sub-name'];
	
						$sql = "UPDATE subject SET course_id = :course_id, sub_name = :sub_name WHERE id = :id";
						$stmt = $pdo->prepare($sql);
						$stmt->bindValue(':course_id', $course_id);
						$stmt->bindValue(':sub_name', $sub_name);
						$stmt->bindValue(':id', $sid);
						$stmt->execute();
						break;
	}

	header("Location: subject.php");
?>	