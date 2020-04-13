<?php
    require "../connection.php"; 
    $id = $_POST['id'];
    $stmt = $conn->query("SELECT o.id , o.name , o.param FROM organization as o WHERE o.id = $id LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $data = (object)array();
    $data->name=$row['name'];
    $data->param=$row['param'];
    $data->id=$row['id'];

    echo json_encode($data);
