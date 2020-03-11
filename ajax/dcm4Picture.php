<?php
    session_start();
    header("charset=utf-8");
    $study = $_POST['st'];
    $ser   = $_POST['ser'];
    $obj   = $_POST['obj'];

    include_once "../config.php";

    $b64 = $db->getPicture($study,$ser,$obj);
    $b64 = str_replace("\r\n" , " " , $b64);
    echo $b64;
