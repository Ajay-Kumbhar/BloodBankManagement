<?php include 'dbconnection/database.php'?>
<?php session_start()?>
<?php
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        if(isset($_POST['Accept'])){
            $available = $_POST['available'];
            $amount = $_POST['amount'];
            $bloodgroup = $_POST['bloodgroup'];

            $receivername = $_POST['receivername'];

            if($available<$amount){
                $_SESSION['request']='invalid';
                header('Location: viewrequests.php');
            }else{
                $_SESSION['request']='valid';
                $left = $available-$amount;
                $query = "UPDATE bloodbank SET amount='$left' WHERE bloodgroup='$bloodgroup' AND username='$username'";
                if(mysqli_query($conn, $query)){
                    $query = "UPDATE requests SET status='Accepted' WHERE bloodgroup='$bloodgroup' AND receivername='$receivername' AND hospitalname='$username' AND amount='$amount'";
                    if(mysqli_query($conn, $query)){
                        header('Location: viewrequests.php');
                    }else{
                        echo "Error occured". mysqli_error($conn);
                    }
                }else{
                    echo "Error occured". mysqli_error($conn);
                }
            }
        }
    }
?>