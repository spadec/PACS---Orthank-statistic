<?php
require_once "classes/appointment.php";
$config = array(
'login' => "alice",
'pass' => "alicePassword",
'host' => "localhost",
'port' =>"8042",
'protocol'=>"http://",
'viewer'=>"/osimis-viewer/app/index.html",
);
$db = new orthank($config);
?>