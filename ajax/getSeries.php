<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
date_default_timezone_set('Asia/Omsk');
header('Content-type: application/json');
include_once "../config.php";
if(isset($_POST["data"])){
    $series = array();
    $arr = $_POST["data"];
    for($i=0;$i<count($arr);$i++){
        array_push($series,$db->getSeries($arr[$i]));
    }
    echo json_encode($series,JSON_UNESCAPED_UNICODE);
}
else {
    echo json_encode(array("error"=>"Не передан идентификатор серии"),JSON_UNESCAPED_UNICODE);
}