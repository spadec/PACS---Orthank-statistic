<?php 
    require "../connection.php";
    $id = $_POST['id'];
    $name = $_POST['name'];
    $param = $_POST['param'];

    $stmt = $conn->prepare("UPDATE organization SET name = :name , param=:param WHERE id = :id");
    $stmt->bindParam(':id' , $id);
    $stmt->bindParam(':name',$name);
    $stmt->bindParam(':param',$param);
    $stmt->execute();

    echo json_encode($_POST);