<?php
    require "../connection.php"; 
    $id = $_POST['id'];
    $stmt = $conn->query("SELECT u.id , u.login , u.password FROM users as u WHERE u.id = $id LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $data = (object)array();
    $data->login=$row['login'];
    $data->password=$row['password'];
    $data->id=$row['id'];

    $stmt = $conn->query("SELECT o.id , o.name  FROM user_org as uo INNER JOIN organization as o ON o.id = uo.org_id WHERE uo.user_id = $id");
    $org = $stmt->fetchAll();
    
    $data->orgs=$org;

    echo json_encode($data);

    