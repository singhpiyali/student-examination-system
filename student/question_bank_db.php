<?php
	require("include/connect.php"); 

	$formaction=$_REQUEST['formaction'];
	switch($formaction)
	{
	case 'delete':  
			
					    $qid = $_REQUEST['qid'];
						$sql = "DELETE FROM question_bank WHERE id = :id";
						$stmt = $pdo->prepare($sql);
						$stmt->bindValue(':id', $qid);
						$stmt->execute();
		                break;
                        

	case 'insert':
						$cid = $_REQUEST['course-name'];
						$q_set_name = $_REQUEST['q-s-name'];
						$sid = $_REQUEST['sub-name'];
						$no_of_question = $_REQUEST['noq'];
						$each_question_marks = $_REQUEST['marks'];
						$negetive_marks = $_REQUEST['neg'];
						$exam_time = $_REQUEST['etime'];

						
						$sql = "INSERT INTO question_bank (cid, q_set_name, sid,  no_of_question, each_question_marks, negetive_marks, exam_time) 
								VALUES (:cid, :q_set_name, :sid, :no_of_question, :each_question_marks, :negetive_marks, :exam_time)";
						$stmt = $pdo->prepare($sql);
						$stmt->bindValue(':cid', $cid);
						$stmt->bindValue(':q_set_name', $q_set_name);
						$stmt->bindValue(':sid', $sid);
						$stmt->bindValue(':no_of_question', $no_of_question);
						$stmt->bindValue(':each_question_marks', $each_question_marks);
						$stmt->bindValue(':negetive_marks', $negetive_marks,PDO::PARAM_STR);
						$stmt->bindValue(':exam_time', $exam_time);
						$stmt->execute();		
						break;


						case 'update':
							$qid = $_REQUEST['qid'];
							$cid = $_REQUEST['course-name'];
							$q_set_name = $_REQUEST['q-s-name']; 
							$sid = $_REQUEST['sub-name'];
							$no_of_question = $_REQUEST['noq'];
							$each_question_marks = $_REQUEST['marks'];
							$negetive_marks = $_REQUEST['neg'];
							$exam_time = $_REQUEST['etime'];
						
							$sql = "UPDATE question_bank 
									SET cid = :cid, q_set_name = :q_set_name, sid = :sid, no_of_question = :no_of_question, 
										each_question_marks = :each_question_marks, negetive_marks = :negetive_marks, exam_time = :exam_time 
									WHERE id = :id";
						
							$stmt = $pdo->prepare($sql);
							$stmt->bindValue(':cid', $cid);
							$stmt->bindValue(':q_set_name', $q_set_name);
							$stmt->bindValue(':sid', $sid);
							$stmt->bindValue(':no_of_question', $no_of_question);
							$stmt->bindValue(':each_question_marks', $each_question_marks);
							$stmt->bindValue(':negetive_marks', $negetive_marks,PDO::PARAM_STR);
							$stmt->bindValue(':exam_time', $exam_time);
							$stmt->bindValue(':id', $qid);
							$stmt->execute();
							break;
	}

	header("Location: question_bank.php");
?>	
