<?php
    session_start();
    if(isset($_SESSION['as'])){
        if($_SESSION['as']=='hospital'){
            $_SESSION['username'] = null;
            if(isset($_SESSION['user'])){
                $_SESSION['as']='receiver';
            }else{
                $_SESSION['as']='guest';
            }
        }else if($_SESSION['as']=='receiver'){
            $_SESSION['user'] = null;
            if(isset($_SESSION['username'])){
                $_SESSION['as']='hospital';
            }else{
                $_SESSION['as']='guest';
            }
        }
        header('Location: index.php');
    }
?>