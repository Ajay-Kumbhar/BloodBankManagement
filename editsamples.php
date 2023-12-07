<?php include 'dbconnection/database.php'?>
<?php session_start()?>
<?php
    if(isset($_POST['save']) && isset($_SESSION['username'])){
        $username = filter_var($_SESSION['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $bloodgroup = filter_var($_POST['bloodgroup'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_INT);
        
        $query = "SELECT amount FROM bloodbank WHERE username='$username' AND bloodgroup='$bloodgroup'";
        $result = mysqli_query($conn, $query);
        $amountinfo = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if(!count($amountinfo)){
            $query = "INSERT INTO bloodbank VALUES('$bloodgroup', $amount, '$username')";
        }else{
            $query = "UPDATE bloodbank SET amount=$amount WHERE username='$username' AND bloodgroup='$bloodgroup'";
        }
        if(mysqli_query($conn, $query)){
            header('Location: hospitaldashboard.php');
        }else{
            echo "Error occured". mysqli_error($conn);
        }
    }

?>