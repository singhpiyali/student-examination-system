<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Exam System</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="register-page">
  <div class="form"> 
    <form class="register-form" method="POST" action="register_db.php">
      <input type="text" name="name" placeholder="name"/>
      <input type="text" name="email" placeholder="email address"/>
      <input type="password" name="pass" placeholder="password"/>
      
      <button type="submit" name="submit" value="submit">Create</button> 
      <p class="message">Already registered? <a href="index.php">Sign In</a></p>
    </form>
</div>
</div>
</body>
</html>