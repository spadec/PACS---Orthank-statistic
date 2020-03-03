<?php
  session_start();
  // $_SESSION['server_type']=1;
  $page = $_SESSION['server_type']==1?"orthView.php":"dcm4View.php";
  header("location: ".$page);