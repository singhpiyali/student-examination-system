
<?php
session_start();
include("include/connect.php");

if (isset($_POST['submit']) && !empty($_POST['submit'])) {
    $email = trim($_POST['email']);
    $pass = trim($_POST['pass']);
    $regno = trim($_POST['registration_no']);
    $roll  = trim($_POST['roll_no']);

    // Backend validation
    if ($email == "" || $pass == "" || $regno == "" || $roll == "") {
        header("Location: index.php?error=All fields are required");
        exit;
    }


    //Check for valid email id
    $sql = "SELECT * FROM user WHERE email = :email AND  pass = :pass AND registration_no = :regno AND roll_no = :roll LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':pass', $pass);
    $stmt->bindParam(':regno', $regno);
    $stmt->bindParam(':roll', $roll);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_OBJ);

    if ($row) {
        
        $_SESSION['user_id'] = $row->id;
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $row->name;
        $_SESSION['registration_no'] = $row->registration_no;
        $_SESSION['roll_no'] = $row->roll_no;
        header('Location: dashboard.php');
        exit();
    } else {
        header('Location: index.php?error=Login+failed');
        exit();
    }
}
?>

