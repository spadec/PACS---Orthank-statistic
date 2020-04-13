<?php
require_once "classes/appointment.php";

$host = $_SESSION['server_type']==1?"localhost":"89.218.233.58";
$port = $_SESSION['server_type']==1?"8042":"8080";
$viewer = $_SESSION['server_type']==1?"/osimis-viewer/app/index.html":"/dcm4chee-arc/aets/3GP/wado";
$config = array(
    'login' => "alice",
    'pass' => "alicePassword",
    'host' => $host,
    'port' => $port,
    'protocol'=> "http://",
    'viewer'=> $viewer,
    'auth' => 'https://89.218.233.58:8843/auth/realms/dcm4che/protocol/openid-connect/token/',
    'secret'=>'c56c6cbc-6025-4a58-b4fe-a2334532ca9d'
);
$db = $_SESSION['server_type']==1? new orthank($config) : new dcm4chee($config);
?>
