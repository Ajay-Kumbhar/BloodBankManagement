<?php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'ajay');
    define('DB_PASS', 'root');
    define('DB_NAME', 'bloodbankdb');

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if($conn->connect_error){
        die('Connection failed').$conn->connect_error;
    }
?>
