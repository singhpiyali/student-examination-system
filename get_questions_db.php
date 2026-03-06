<?php
	require("include/connect.php"); 


    				$pdo->beginTransaction();

    				try {
        			$qid = $_REQUEST['qid'];
       				$noq = $_REQUEST['noq'];
        			for ($i = 1; $i <= $noq; $i++) {
           			$ques   = $_REQUEST['ques' . $i];
            		$optiona = $_REQUEST['optiona' . $i];
            		$optionb = $_REQUEST['optionb' . $i];
            		$optionc = $_REQUEST['optionc' . $i];
            		$ans     = $_REQUEST['ans' . $i];

            		$sql = "INSERT INTO questions (qid, ques, optiona, optionb, optionc, ans) 
                    VALUES (:qid, :ques, :optiona, :optionb, :optionc, :ans)";
					
            		$stmt = $pdo->prepare($sql);
            		$stmt->execute([
                	':qid' => $qid,
                	':ques' => $ques,
                	':optiona' => $optiona,
                	':optionb' => $optionb,
                	':optionc' => $optionc,
                	':ans' => $ans
            		]);
        		}
       				 $pdo->commit();

    			} 	catch (Exception $e) {
        			$pdo->rollBack();
       				 die("Failed to insert questions: " . $e->getMessage());
    			}
   					 

	header("Location: question_set.php");
?>	