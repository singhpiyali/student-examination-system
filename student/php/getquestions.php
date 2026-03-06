<?php
  require("include/connect.php");
  $qid=$_REQUEST['qid'];

  $sql = "SELECT question_bank.no_of_question as no_of_question, subject.sub_name as sub_name, course.name as course_name FROM question_bank INNER JOIN course ON question_bank.cid = course.id  INNER JOIN subject ON subject.id = question_bank.sid WHERE question_bank.id=:qid";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':qid', $qid);
  $stmt->execute();
  $subjects = $stmt->fetch(PDO::FETCH_OBJ);
  $no_of_question=$subjects->no_of_question;
  $subject=$subjects->sub_name;
  $course=$subjects->course_name;

  $heading="<h2>Question for $course $subject</h2>";

  $html='<form class="form-horizontal form-label-left" action="get_questions_db.php?formaction=insert" method="post">';
  $html .= '<input type="hidden" name="qid" value="'.$qid.'">';
  $html .= '<input type="hidden" name="noq" value="'.$no_of_question.'">';
  for($i=0;$i<$no_of_question;$i++){
    $qno = $i + 1;

  $html.='<div class="item form-group">
  
    <label class="col-form-label col-md-3 col-sm-3 label-align" for="q-s-name"><b>Question No '.$qno.':</b> <span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 ">
      <input type="text" id="" name="ques'.$qno.'" required="required" placeholder="Type your Question" class="form-control ">
    </div>
  </div>
  <div class="item form-group">
    <label class="col-form-label col-md-3 col-sm-3 label-align" for="q-s-name"><b>Option a:</b> <span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 ">
      <input type="text" id="" name="optiona'.$qno.'" required="required" class="form-control ">
    </div>
  </div>
    <div class="item form-group">
    <label class="col-form-label col-md-3 col-sm-3 label-align" for="q-s-name"><b>Option b:</b> <span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 ">
      <input type="text" id="" name="optionb'.$qno.'" required="required" class="form-control ">
    </div>
  </div>
  <div class="item form-group">
    <label class="col-form-label col-md-3 col-sm-3 label-align" for="q-s-name"><b>Option c:</b> <span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 ">
      <input type="text" id="" name="optionc'.$qno.'" required="required" class="form-control ">
    </div>
  </div>
  <div class="item form-group">
    <label class="col-form-label col-md-3 col-sm-3 label-align" for="q-s-name"><b>Answer:</b> <span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 ">
      <select class="form-control" name="ans'.$qno.'" required>
        <option value="">--Choose Correct Answer--</option>
        <option value="a">a</option>
        <option value="b">b</option>
        <option value="c">c</option>
      </select>
    </div>
  </div><br><br>';

  }

  $html.='<div class="ln_solid"></div>
  <div class="item form-group">
    <div class="col-md-6 col-sm-6 offset-md-3">
      <button type="submit" id="submit" class="btn btn-primary">Save</button>
      <button type="reset" id="reset" class="btn btn-primary">Reset</button>
    </div>
  </div>

</form>';
echo $heading,$html;
?>


