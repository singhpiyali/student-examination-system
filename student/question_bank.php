<?php
session_start(); 
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

    <title>Online Examination | Question Bank</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js"></script>

    <!-- Bootstrap -->
  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-daterangepicker/daterangepicker.css">
    
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
              <h3>Question Bank</h3>
            </div>

          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 ">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Add Question Set</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form class="form-horizontal form-label-left" action="question_bank_db.php" method="post">
                    <!--Hidden field -->
                    <input type="hidden" name="formaction" id="action" value="insert">
                    <input type="hidden" name="qid" id="qid"/>                                 
                    
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
                        <select class="form-control" name="sub-name" id="sub-name"  required="required" >
                          <option value="">--Select Subject--</option>
                          <div id="subjects"></div>
                        </select>
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="col-form-label col-md-3 col-sm-3 label-align" for="q-s-name"><b>Question Set Name:</b> <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 ">
                        <input type="text" id="q-s-name" name="q-s-name" required="required" class="form-control ">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="col-form-label col-md-3 col-sm-3 label-align" for="noq"><b>No Of Question:</b> <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 ">
                        <input type="number" min="1" id="noq" name="noq" required="required" class="form-control ">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="col-form-label col-md-3 col-sm-3 label-align" for="marks"><b>Each Question Marks:</b> <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 ">
                        <input type="number" min="1" id="marks" name="marks" required="required" class="form-control ">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="col-form-label col-md-3 col-sm-3 label-align" for="neg"><b>Negetive Mark(s):</b> <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 ">
                        <input type="number" min="-10" step="0.01" id="neg" name="neg" required="required" class="form-control ">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="col-form-label col-md-3 col-sm-3 label-align" for="etime" value="00:00:30"><b>Exam Time (in minute):</b> <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 ">
                      <input type="number" min="-10" step="0.01" id="etime" name="etime" required="required" class="form-control">
                      </div>
                    </div>
                    
                    <div class="ln_solid"></div>
                    <div class="item form-group">
                      <div class="col-md-6 col-sm-6 offset-md-3">
                        <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                        <button class="btn btn-primary" id="reset" onclick="return cancel_update()" type="reset">Reset</button>
                      </div>
                    </div>

                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Manage Question Bank</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S# </th>
                            <th class="column-title">Course Name </th>
                            <th class="column-title">Question Set Name </th>
                            <th class="column-title">Subject Name </th>
                            
                            <th class="column-title">No of Question </th>
                            <th class="column-title">Each Ques Marks</th>
                            <th class="column-title">Negetive Marks </th>
                            <th class="column-title">Exam Time </th>
                            <th class="column-title no-link last"><span class="nobr">Edit</span></th>
                            <th class="column-title no-link last"><span class="nobr">Remove</span>
                            </th>
                          </tr>
                        </thead>
                        <?php
                          $sql = "SELECT question_bank.*, course.name as course_name ,q_set_name,subject.sub_name as sub_name
                          FROM question_bank 
                          JOIN course ON question_bank.cid = course.id
                          JOIN subject ON  question_bank.sid=subject.id";
                          $stmt = $pdo->prepare($sql);
                          $stmt->execute();
                          $records=$stmt->fetchAll(PDO::FETCH_OBJ);
                        ?>
                        <tbody>
                          <?php
                            $sno=1;
                            foreach($records as $row){
                          ?>
                          <tr class="even pointer">
                            <td class=" "><?= $sno++ ?></td>
                            <td class=" "><?= $row->course_name ?></td>
                            <td class=" "><?= $row->q_set_name ?></td>
                            <td class=" "><?= $row->sub_name ?></td>
                            <td class=" "><?= $row->no_of_question ?></td>
                            <td class=" "><?= $row->each_question_marks ?></td>
                            <td class=" "><?= $row->negetive_marks ?></td>
                            <td class=" "><?= $row->exam_time ?>min</td>

                            
                            <td class=" last"><img src="assets/images/edit.png"
  onclick='loadFormData(
    <?= json_encode($row->id) ?>,
    <?= json_encode($row->cid) ?>,
    <?= json_encode($row->q_set_name) ?>,
    <?= json_encode($row->sub_name) ?>,
    <?= json_encode($row->no_of_question) ?>,
    <?= json_encode($row->each_question_marks) ?>,
    <?= json_encode($row->negetive_marks) ?>,
    <?= json_encode($row->exam_time) ?>
  )'
  style="cursor: pointer;"/>
</td>    
                            
    <td class=" last"><a href="question_bank_db.php?qid=<?= $row->id ?>&formaction=delete"><img src="assets/images/delete.png" onclick="return confirm_del(<?= $row->id ?>)" /></a>
                            </td>
                          </tr>
                          <?php 
                          } 
                          ?>
                          
                        </tbody>
                      </table>
                    </div>
              
            
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
      function confirm_del(qid)
      {
        if (confirm("Do you want to delete the record?") == true) {
          document.getElementById("qid").value=qid;
          document.getElementById("action").value="delete";
          
          return true;
        } else {
          return false;
        }
      }

      function loadFormData(qid, courseId, name, subjectId, noq, marks, neg, etime)
       {
      document.getElementById("qid").value = qid;
      document.getElementById("course-name").value = courseId;
      document.getElementById("q-s-name").value = name;
      document.getElementById("sub-name").value = subjectId;
      document.getElementById("noq").value = noq;
      document.getElementById("marks").value = marks;
      document.getElementById("neg").value = neg;
      document.getElementById("etime").value = etime;
      document.getElementById("submit").innerHTML = "Update";
      document.getElementById("action").value = "update";
      getsubjects();
      }

function cancel_update() {  
  if (confirm("Do you want to cancel the update request?") == true) {
    document.getElementById("submit").innerHTML = "Submit";
    document.getElementById("action").value = "insert";
    document.getElementById("qid").value = '';
    document.getElementById("course-name").value = '';
    document.getElementById("q-s-name").value = '';
    document.getElementById("sub-name").value = '';
    document.getElementById("noq").value = '';
    document.getElementById("marks").value = '';
    document.getElementById("neg").value = '';
    document.getElementById("etime").value = '';
    return false;
  }
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

    </script>
  </body>
</html>
