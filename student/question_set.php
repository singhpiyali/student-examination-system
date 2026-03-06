<?php
  require("include/connect.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="assets/images/favicon.ico" type="image/ico" />

    <title>Online Examination | Question Set</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;padding-top: 10px;">
              <a href="index.php" class="site_title"><i class="fa fa-graduation-cap"></i> <span>Online Exam</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- sidebar menu -->
            <?php include("include/sidebar.php"); ?>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <?php include("include/header.php"); ?>
        <!-- /top navigation -->

              <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Exam Set</h3>
            </div>

          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 ">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Add Question</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                    <!--Hidden field -->
                    <div class="item form-group">
                      <label class="col-form-label col-md-3 col-sm-3 label-align" for="course-name"><b>Choose Course:</b> <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 ">
                      <select class="form-control" name="course-name" id="course-name"  onchange="getsubjects()" required>
                       <option value="">--Select Course--</option>
                        <?php
                          $sql = "SELECT id, name FROM course";
                          $stmt = $pdo->prepare($sql);
                          $stmt->execute();
                          $courses = $stmt->fetchAll(PDO::FETCH_OBJ);

                          foreach ($courses as $course) {
                           echo "<option value='{$course->id}'>" . htmlspecialchars($course->name) . "</option>";
                            }
                            ?>
                          </select>
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="col-form-label col-md-3 col-sm-3 label-align" for="sub-name"><b>Choose Subject:</b> <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 ">
                        <select class="form-control" name="sub-name" id="sub-name" onchange="getsets()" required>
                          <option value="">--Select Subject--</option>
                          <div id="subjects"></div>
                        </select>
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="col-form-label col-md-3 col-sm-3 label-align" for="q-set"><b>Choose Question Set:</b> <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 ">
                      <select class="form-control" name="q-set" id="q-set"  required>
                       <option value="">--Select Question Set--</option>
                        <div id="sets"></div>
                          </select>
                      </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="item form-group">
                      <div class="col-md-6 col-sm-6 offset-md-3">
                        <button type="button" class="btn btn-primary" onclick="getquestions()">Prepare Question</button>
                      </div>
                    </div>
                </div>
              </div>
            </div>

            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add Questions</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <br />
                  <div id="questions"></div>
                </div>

                </div>
              </div>

          </div>
          


              </div>
              </div>
        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FastClick -->
    <script src="vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="vendors/Flot/jquery.flot.js"></script>
    <script src="vendors/Flot/jquery.flot.pie.js"></script>
    <script src="vendors/Flot/jquery.flot.time.js"></script>
    <script src="vendors/Flot/jquery.flot.stack.js"></script>
    <script src="vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="vendors/moment/min/moment.min.js"></script>
    <script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="build/js/custom.min.js"></script>
	  
    <script>

     function getquestions()
     {
      var qid=document.getElementById("q-set").value;
      if (!qid) {
    alert("Please select a question set.");
    return;
  }
      const xmlhttp = new XMLHttpRequest();
      xmlhttp.onload = function() {
        document.getElementById("questions").innerHTML = this.responseText;
      }
      xmlhttp.open("GET", "getquestions.php?qid=" + qid);
      xmlhttp.send();
     }

     function getsubjects()
     {
      var courseid=document.getElementById("course-name").value;
      const xmlhttp = new XMLHttpRequest();
      xmlhttp.onload = function() {
        document.getElementById("subjects").innerHTML = this.responseText;
      }
      xmlhttp.open("GET", "getsubjects.php?course_id=" + courseid);
      xmlhttp.send();
     }

     function getsets() {
    var courseid = document.getElementById("course-name").value;
    var subid = document.getElementById("sub-name").value;

    if (!courseid || !subid) {
    alert("Please select both course and subject.");
    return;
  }
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
    document.getElementById("sets").innerHTML = this.responseText;

  }
  xmlhttp.open("GET", "getset.php?cid=" + courseid + "&sid=" + subid);
  xmlhttp.send();
}
    </script>
  </body>
</html>
