<?php
session_start();
require("include/connect.php");

/* LOGIN CHECK */
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* FETCH ALL RESULTS OF THIS USER */
$stmt = $pdo->prepare("
    SELECT r.*, qb.q_set_name
    FROM result r
    JOIN question_bank qb ON r.qsid = qb.id
    WHERE r.user_id = ?
    ORDER BY r.submitted_at DESC
");
$stmt->execute([$user_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Exam Results</title>
<link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">

<style>

    .custom-table-head th {
    background-color: #c9efd6;   /* soft light green */
    color: #0f5132;              /* dark green text */
    font-weight: 600;
    border-color: #b6e6c8;
}
.result-card {
    border-radius: 14px;
    overflow: hidden;
}
.table thead th {
    vertical-align: middle;
}
.badge-score {
    font-size: 0.95rem;
    padding: 6px 10px;
}
.stat-pill {
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
}
.stat-correct { background: #e6f7ee; color: #1e7e34; }
.stat-wrong { background: #fdecea; color: #bd2130; }
.stat-unanswered { background: #fff3cd; color: #856404; }

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}


</style>
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow result-card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📊 My Exam Results</h5>
        <span class="small text-light">Performance Overview</span>
    </div>

    <div class="card-body">

    <?php if (empty($results)): ?>
        <div class="alert alert-info text-center">
            <h6 class="mb-1">No exams attempted yet</h6>
            <small>Start an exam to see your results here.</small>
        </div>
    <?php else: ?>

    <div class="table-responsive">
    <table class="table table-bordered align-middle text-center">
        <thead class="custom-table-head">
            <tr>
                <th>#</th>
                <th>Exam Name</th>
                <th>Score</th>
                <th>Correct</th>
                <th>Wrong</th>
                <th>Unanswered</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($results as $i => $r): 
            $percentage = ($r['total_questions'] > 0)
                ? round(($r['score'] / $r['total_questions']) * 100)
                : 0;

            if ($percentage >= 60) $scoreClass = "bg-success";
            elseif ($percentage >= 40) $scoreClass = "bg-warning text-dark";
            else $scoreClass = "bg-danger";
        ?>
            <tr>
                <td><?= $i + 1 ?></td>

                <td class="fw-semibold">
                    <?= htmlspecialchars($r['q_set_name']) ?>
                </td>

                <td>
                    <span class="badge badge-score <?= $scoreClass ?>">
                        <?= $r['score'] ?>/<?= $r['total_questions'] ?>
                        (<?= $percentage ?>%)
                    </span>
                </td>

                <td>
                    <span class="stat-pill stat-correct">
                        <?= $r['correct'] ?>
                    </span>
                </td>

                <td>
                    <span class="stat-pill stat-wrong">
                        <?= $r['wrong'] ?>
                    </span>
                </td>

                <td>
                    <span class="stat-pill stat-unanswered">
                        <?= $r['unanswered'] ?>
                    </span>
                </td>

                <td>
                    <?= date("d M Y", strtotime($r['submitted_at'])) ?>
                    <br>
                    <small class="text-muted">
                        <?= date("h:i A", strtotime($r['submitted_at'])) ?>
                    </small>
                </td>

                <td>
                    <a href="result.php?qsid=<?= $r['qsid'] ?>"
                       class="btn btn-sm btn-primary px-3">
                        👁 View
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>

    <?php endif; ?>

    </div>
</div>

<div class="mt-4 text-center">
    <a href="dashboard.php" class="btn btn-secondary px-4">
        ⬅ Back to Dashboard
    </a>
</div>

</div>

</body>
</html>
