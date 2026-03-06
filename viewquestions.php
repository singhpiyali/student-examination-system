<?php
session_start();
require("include/connect.php");

/* ================= LOGIN CHECK ================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// 🔐 Exam must be started via Start Exam button
if (empty($_SESSION['attempt_no'])) {
    echo "<div style='margin:60px;text-align:center;color:red;'>
          <h3>❌ Exam not started properly</h3>
          <p>Please click <b>Start Exam</b> from dashboard.</p>
          </div>";
    exit;
}

/* ================= GET EXAM ID ================= */
if (!isset($_GET['qsid'])) {
    echo '<div class="alert alert-danger">Invalid exam request.</div>';
    exit;
}

$qsid = (int)$_GET['qsid'];

/* ================= FETCH QUESTIONS ================= */
$stmt = $pdo->prepare("
    SELECT q.id, q.ques, q.optiona, q.optionb, q.optionc,
           qb.q_set_name, qb.exam_time,
           c.name AS course_name,
           s.sub_name AS subject_name
    FROM questions q
    JOIN question_bank qb ON q.qid = qb.id
    JOIN course c ON qb.cid = c.id
    JOIN subject s ON qb.sid = s.id
    WHERE q.qid = ?
");
$stmt->execute([$qsid]);
$questions = $stmt->fetchAll(PDO::FETCH_OBJ);

if (!$questions) {
    echo '<div class="alert alert-warning">No questions found.</div>';
    exit;
}

$examName = $questions[0]->q_set_name;
$course   = $questions[0]->course_name;
$subject  = $questions[0]->subject_name;
$timeSec  = $questions[0]->exam_time * 60;
$totalQ   = count($questions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Exam | <?= htmlspecialchars($examName) ?></title>
<link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="./css/viewquestions.css?v=999">
</head>

<body>

<nav class="navbar navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <span class="navbar-brand">🎓 Online Examination</span>
    <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
  </div>
</nav>

<div class="navbar-2">
    <span><b>Name:</b> <?= $_SESSION['name'] ?></span>
    <span><b>Reg No:</b> <?= $_SESSION['registration_no'] ?></span>
    <span><b>Roll No:</b> <?= $_SESSION['roll_no'] ?></span>
</div>

<div class="exam-wrapper">

<div class="exam-info-bar">
    <span><b>Exam:</b> <?= $examName ?></span>
    <span><b>Course:</b> <?= $course ?></span>
    <span><b>Subject:</b> <?= $subject ?></span>
    <span><b>Total Q:</b> <?= $totalQ ?></span>
    <div class="timer-wrap">
    <b>Time Left:</b> <span id="time"></span>
</div>
</div>

<div class="exam-body">
<div class="question-area">

<?php foreach ($questions as $i => $q): ?>
<div class="card question"
     data-index="<?= $i ?>"
     data-qid="<?= $q->id ?>"
     data-saved="0"
     style="<?= $i === 0 ? '' : 'display:none'; ?>">

<div class="card-header">
    Question <?= $i+1 ?> of <?= $totalQ ?>
</div>

<div class="card-body">
<p class="question-text">
<strong><?= $i+1 ?>.</strong> <?= htmlspecialchars($q->ques) ?>
</p>

<?php
$opts = ['A'=>'optiona','B'=>'optionb','C'=>'optionc'];
foreach ($opts as $k=>$v):
?>
<label class="option d-block">
    <input type="radio" name="answer[<?= $q->id ?>]" value="<?= $k ?>">
    <span class="opt-no"><?= $k ?>)</span> <?= htmlspecialchars($q->$v) ?>
</label>
<?php endforeach; ?>

<div class="controls mt-3">
    <button class="btn btn-secondary prev-btn">Previous</button>
    <button class="btn btn-success save-btn">💾 Save</button>
    <button class="btn btn-primary next-btn">Next</button>
</div>
</div>
</div>
<?php endforeach; ?>

</div>

<div class="palette-area">
<?php foreach ($questions as $i=>$q): ?>
<button class="palette-btn" data-index="<?= $i ?>"><?= $i+1 ?></button>
<?php endforeach; ?>
</div>

</div>
</div>

<script src="vendors/jquery/dist/jquery.min.js"></script>

<script>
/* ================= TIMER ================= */
let timeLeft = <?= $timeSec ?>;

setInterval(() => {
    let m = Math.floor(timeLeft / 60);
    let s = timeLeft % 60;

    $('#time').text(
        m.toString().padStart(2,'0') + ":" +
        s.toString().padStart(2,'0')
    );

    if (timeLeft <= 300) { // last 5 min
        $('#time').addClass('warning');
    }

    if (--timeLeft < 0) {
        alert("⏰ Time Over! Exam submitted.");
        window.location.href = "submit_exam.php?qsid=<?= $qsid ?>";
    }
}, 1000);

/* ================= SHOW QUESTION ================= */
function showQuestion(i){
    $('.question').hide().eq(i).show();
    $('.palette-btn').removeClass('current').eq(i).addClass('current');
    updateNextButton(i);
}


/* ================= OPTION CLICK (FIXED) ================= */
$(document).on('click','.option',function(){
    $(this).find('input').prop('checked',true);
    let qBox = $(this).closest('.question');

    // Only mark unsaved if it was already saved
    if (qBox.attr('data-saved') === "1") {
        qBox.attr('data-saved','0');
    }
});

/* ================= SAVE ANSWER ================= */
$(document).on('click','.save-btn',function(){
    let qBox = $(this).closest('.question');
    let qid = qBox.data('qid');
    let ans = qBox.find('input[type=radio]:checked').val();

    if(!ans){
        alert("Select an option first");
        return;
    }

    $.post("save_answer_db.php",{
        qsid: <?= $qsid ?>,
        question_id: qid,
        answer: ans
    },function(res){
        if(res.status==="success"){
            qBox.attr('data-saved','1');
            $('.palette-btn').eq(qBox.data('index'))
                .addClass('answered')
                .removeClass('unanswered current');
        } else {
            alert(res.message);
        }
    },'json');
});

function updateNextButton(index){
    let total = $('.question').length;
    let btn = $('.question').eq(index).find('.next-btn');

    if (index === total - 1) {
        btn.text('Finish').removeClass('btn-primary').addClass('btn-danger');
    } else {
        btn.text('Next').removeClass('btn-danger').addClass('btn-primary');
    }
}
/* ================= NEXT / FINISH ================= */
$(document).on('click','.next-btn',function(){
    let qBox = $(this).closest('.question');
    let index = qBox.data('index');
    let total = $('.question').length;

    let selected = qBox.find('input[type=radio]:checked').length;
    let saved = qBox.attr('data-saved') === "1";
    let paletteBtn = $('.palette-btn').eq(index);

    // ⚠️ Selected but not saved
    if (selected && !saved) {
        let ok = confirm("⚠️ Your answer is not saved.\nDo you want to continue?");
        if (!ok) return;

        paletteBtn.removeClass('answered current').addClass('unanswered');
    }

    // No option selected → mark unanswered silently
    if (!selected) {
        paletteBtn.removeClass('answered current').addClass('unanswered');
    }

    // Saved
    if (saved) {
        paletteBtn.removeClass('unanswered current').addClass('answered');
    }

    // 🏁 LAST QUESTION → FINISH
    if (index === total - 1) {
        if (confirm("Finish exam?")) {
            window.location.href = "submit_exam.php?qsid=<?= $qsid ?>";
        }
        return;
    }

    showQuestion(index + 1);
});
/* ================= PREVIOUS ================= */
$(document).on('click','.prev-btn',function(){
    let i = $(this).closest('.question').data('index');
    if(i>0) showQuestion(i-1);
});

/* ================= PALETTE ================= */
$(document).on('click','.palette-btn',function(){
    showQuestion($(this).data('index'));
});

showQuestion(0);
</script>

</body>
</html>
