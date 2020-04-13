<?php
ob_start();
  session_start();
  if($_SESSION['user']) {
    $_SESSION['server_type']=2;
    if(!isset($_SESSION['server_type'])) {
      $_SESSION['server_type']=1;
    }
    $page = $_SESSION['server_type']==1?"orthView.php":"dcm4View.php";
    if ($_SESSION['admin']) {
      header('location: ./admin/index.php');
    } else {header("location: ".$page);}
  }
 
  else {
    header('location: ./login.php');
  }

