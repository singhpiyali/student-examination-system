<?php
include("include/connect.php");

if (isset($_POST['submit'])) {

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = trim($_POST['pass']);

    // 1️⃣ Check duplicate email
    $check = $pdo->prepare("SELECT id FROM user WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        echo '<script>alert("Email already exists"); window.location.href="register.php";</script>';
        exit;
    }

    

    // 2️⃣ Insert user FIRST (without reg/roll)
    $stmt = $pdo->prepare("INSERT INTO user (name, email, pass) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $pass]);

    

    // 3️⃣ Get inserted user ID
    $user_id = $pdo->lastInsertId();

    // 4️⃣ Generate Registration & Roll No
    $registration_no = 'REG' . date('Y') . str_pad($user_id, 4, '0', STR_PAD_LEFT);
    $roll_no         = 'ROLL' . str_pad($user_id, 4, '0', STR_PAD_LEFT);

    // 5️⃣ Update same row (IMPORTANT)
    $update = $pdo->prepare(
        "UPDATE user 
         SET registration_no = ?, roll_no = ? 
         WHERE id = ?"
    );
    $update->execute([$registration_no, $roll_no, $user_id]);

    echo '<script>
        alert("Registration successful!");
        window.location.href="index.php";
    </script>';
}
?>
