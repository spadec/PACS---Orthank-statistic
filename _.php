<pre>
<?php 
    require_once 'classes/appointment.php';
    $config = array(
        'login'=>'alice',
        'pass'=>'alicePassword',
        'host'=>'192.168.10.60',
        'port'=>'8080',
        'protocol'=>'http://',
        'viewer'=>"/osimis-viewer/app/index/html"
    );
    $dcm = new dcm4chee($config);

    
    // $res = json_encode($res1);
    // foreach ($res1 as $el) {
    // print_r($res1);
    // }
    

?>
</pre>