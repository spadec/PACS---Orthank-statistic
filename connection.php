<?php
    $host = "89.218.233.58";
    $dbname = "auth_db";
    $user = 'webdev';
    $pass = 'u5EAeGBwG@T2';
    $conn = new PDO("pgsql:host=$host;dbname=$dbname;port=5433",$user,$pass , [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
