<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Log In</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-page">
  <div class="form">
        <form class="login-form" role="form" method="post" action="index_db.php">
      <input type="email"  name="email" placeholder="email" class="form-control" autofocus required/>
      <input type="password" name="pass" placeholder="password" class="form-control" required>
      <input type="text" name="registration_no" placeholder="Registration no" class="form-control" required/>
      <input type="text" name="roll_no" placeholder="Roll no" class="form-control" required/>
      <button type="submit" name="submit" value="submit">Log In</button> 
      <p class="message">
        <?php if (isset($_GET['error'])) echo htmlspecialchars($_GET['error']); ?>
      </p>
      <p class="message">Not registered? <a href="register.php">Create an Account</a></p>
    </form>
  </div>
</div>
</body>
</html>