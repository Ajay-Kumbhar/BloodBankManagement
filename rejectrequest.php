<?php include 'dbconnection/database.php'?>
<?php session_start()?>
<?php
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        if(isset($_POST['Reject'])){
            $amount = $_POST['amount'];
            $bloodgroup = $_POST['bloodgroup'];
            $receivername = $_POST['receivername'];
            $query = "UPDATE requests SET status='Rejected' WHERE bloodgroup='$bloodgroup' AND receivername='$receivername' AND hospitalname='$username' AND amount='$amount'";
            if(mysqli_query($conn, $query)){
                header('Location: viewrequests.php');
            }else{
                echo "Error occured". mysqli_error($conn);
            }
        }
    }
?>