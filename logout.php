<?php
    ob_start();
    session_start();
    session_destroy();
    unset($_SESSION);
    header("location: ./index.php");