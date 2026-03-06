<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$name = isset($_SESSION['name']) ? ucwords(strtolower(htmlspecialchars($_SESSION['name']))) : 'Guest';
?>

<link href="build/css/custom.min.css" rel="stylesheet">
<link href="css/header.css" rel="stylesheet">

<div class="top_nav">
<div class="nav_menu">

<div class="nav toggle">
<a id="menu_toggle"><i class="fa fa-bars"></i></a>
</div>

<nav class="nav navbar-nav">
<ul class="navbar-right">

<li class="nav-item dropdown" style="padding-left:15px;">
<a href="javascript:;" 
   class="user-profile dropdown-toggle"
   aria-haspopup="true"
   id="navbarDropdown"
   data-toggle="dropdown"
   aria-expanded="false">

<img src="assets/images/img.jpg" alt="">
<?= "Welcome " . $name ?>

</a>

<div class="dropdown-menu dropdown-usermenu pull-right"
     aria-labelledby="navbarDropdown">

<a class="dropdown-item" href="dashboard.php">
<i class="fa fa-home pull-right"></i> Dashboard
</a>

<a class="dropdown-item" href="stu_course.php">
<i class="fa fa-book pull-right"></i> My Courses
</a>

<a class="dropdown-item" href="profile.php">
<i class="fa fa-user pull-right"></i> My Profile
</a>

<div class="dropdown-divider"></div>

<a class="dropdown-item" href="logout.php">
<i class="fa fa-sign-out pull-right"></i> Log Out
</a>

</div>

</li>

</ul>
</nav>

</div>
</div>