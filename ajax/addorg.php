<?php
    require '../connection.php';
    $options=[
        cost=>'11'
    ];
    $name = $_POST['name'];
    $tag = $_POST['tag'];

    $stmt = $conn->prepare("INSERT INTO organization(name,param) VALUES (:name , :tag)");
    $stmt->bindParam(':name' , $name);
    $stmt->bindParam(':tag' , $tag);
    $stmt->execute();

    $stmt = $conn->query("SELECT org.id as oid FROM organization as org order by oid desc LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($row);