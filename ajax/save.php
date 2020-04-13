<?php 
    require "../connection.php";
    $id = $_POST['id'];
    $login = $_POST['login'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("UPDATE user");
    