<pre>
<?php
    ob_start();
    session_start();
    include_once "config.php";
    $query = array('PatientID' => '*');
    $response = $db->getInstancesGet($query);

    print_r($response);
?>
</pre>