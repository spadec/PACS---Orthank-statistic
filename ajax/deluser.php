<?php
    require '../connection.php';
    $ids = $_POST['dels'];
    $stmt = $conn->prepare("DELETE FROM users WHERE users.id  = :id");
    
    foreach($ids as $id) {
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    echo json_encode($_POST);