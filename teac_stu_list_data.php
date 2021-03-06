<?php
  /*--this file get student list teacher wants to submit result of from database--*/

  session_start();

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");

  /*-- connect to database --*/
  require 'connect_database.php';

  // get the q parameter from URL
  $q = $_REQUEST["q"];

  // get teacher id
  $tId= $_SESSION['nub-login-id'];

  /*--   get "t_name" from teachers database   --*/
  require 'fetch_all.php';

  /*--   check authority of teacher to access course   --*/
  $author= 0;

  if ($resultTeacReg->num_rows > 0) {
    for($i= 0; $i< $resultTeacReg->num_rows; $i++) {
      if($rowTeacReg[$i]['c_id'] == $q){
        $author= 1;
      }
    }
  }

  /*--   if teacher is allowed to access, get student list of that course   --*/
  if($author == 1){
    /*--   get student id from registered courses from database   --*/
    $sqlRegCourses = "SELECT s_id FROM reg_semester WHERE status= 'reg' AND c_id= '$q' AND g_point IS NULL";
    $resultRegCourses = $conn->query($sqlRegCourses);

    if ($resultRegCourses->num_rows > 0) {
        /*-- output data of each row --*/
        $outp = array();
        $outp= $resultRegCourses->fetch_all(MYSQLI_NUM);
        echo json_encode($outp);
    }else echo "nothing";
  }else echo "nothing";

  $conn->close();
?>
