<?php
    require '../connection.php';
    $options=[
        cost=>'11'
    ];
    $login = $_POST['login'];
    $password = password_hash($_POST['password'] , PASSWORD_BCRYPT , $options);
    $org = $_POST['org'];

    $stmt = $conn->prepare("INSERT INTO users(login,password,role) VALUES (:login , :password ,1)");
    $stmt->bindParam(':password' , $password);
    $stmt->bindParam(':login' , $login);
    $stmt->execute();

    $stmt = $conn->query("SELECT users.id as uid FROM users order by uid desc LIMIT 1");
    $row = $stmt->fetch();

    $stmt = $conn->prepare("INSERT INTO user_org( user_id , org_id ) VALUES (:uid , :oid)");
    
    foreach($org as $r) {
        
        $stmt->bindParam(':uid' , $row['uid']);
        $stmt->bindParam(':oid' , $r);
        $stmt->execute();
    }

    echo json_encode($row);