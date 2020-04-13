<?php 
    require "../connection.php";
    $id = $_POST['id'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $orgs = $_POST['orgs'];
    $stmt = $conn->prepare("UPDATE users SET login = :login WHERE users.id = :id");
    $stmt->bindParam(':login' , $login);
    $stmt->bindParam(':id' , $id);
    $stmt->execute();

    if($password!='') {
        $stmt = $conn->prepare("UPDATE users SET password = :password WHERE users.id = :id");
        $stmt->bindParam(':password' , password_hash($password, PASSWORD_BCRYPT , array('cost'=>11)));
        $stmt->bindParam(':id' , $id);
        $stmt->execute();
    }

    $stmt = $conn->prepare("DELETE FROM user_org WHERE user_id = :id");
    $stmt->bindParam(':id' , $id);
    $stmt->execute();

    $stmt = $conn->prepare("INSERT INTO user_org(org_id , user_id) VALUES(:oid , :uid)");
    if(count($orgs)>0) {
        foreach($orgs as $org) {
            $stmt->bindParam(':oid' , $org);
            $stmt->bindParam(':uid' , $id);
            $stmt->execute();
        }
    }


    $stmt = $conn->query("SELECT o.id , o.name  FROM user_org as uo INNER JOIN organization as o ON o.id = uo.org_id WHERE uo.user_id = $id");
    $org = $stmt->fetchAll();
    
    echo json_encode($org);
    