<?php
    session_start();
    $study = $_POST['st'];
    $ser   = $_POST['ser'];
    $obj   = $_POST['obj'];

    include_once "../config.php";

    $b64 = $db->getPicture($study,$ser,$obj);

    echo json_encode(array("res"=>$b64));
