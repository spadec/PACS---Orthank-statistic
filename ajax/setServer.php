<?php
    session_start();
    $_SESSION['server_type']=$_GET['type'];
    header('location: /index.php');
    // echo json_encode(array('val'=>$_SESSION));/