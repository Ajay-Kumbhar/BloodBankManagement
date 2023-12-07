<?php include 'dbconnection/database.php'?>
<?php session_start()?>
<?php

    $compatiblearr = [
        'A+'=>[
            '"A+"','"A-"','"O+"','"O-"'
        ],
        'A-'=>[
            '"A-"','"O-"'
        ],
        'B+'=>[
            '"B+"','"B-"','"O+"','"O-"'
        ],
        'B-'=>[
            '"B-"','"O-"'
        ],
        'O+'=>[
            '"O+"','"O-"'
        ],
        'O-'=>[
            '"O-"'
        ],
        'AB+'=>[
            '"A+"','"A-"','"B+"','"B-"','"O+"','"O-"','"AB+"','"AB-"'
        ],
        'AB-'=>[
            '"AB-"','"A-"','"B-"','"O-"'
        ]
    ];

    if(isset($_POST['request'])){
        $bloodgroup = $_POST['bloodgroup'];
        $receivername = $_POST['receivername'];
        $hospitalname = $_POST['hospitalname']; 
        $amount = $_POST['amount'];
        $status = 'requested';
        if($_SESSION['as']=='receiver'){
            $query = "SELECT bloodgroup FROM receiver WHERE username='$receivername'";
            $result = mysqli_query($conn, $query);
            $userinfo = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $userbloodgroup = $userinfo[0]['bloodgroup'];
            $bg = $bloodgroup;
            $bloodgroup = '"'.$bloodgroup.'"';
            if(in_array($bloodgroup, $compatiblearr[$userbloodgroup])){
                $query = "INSERT INTO requests(bloodgroup, receivername, hospitalname, amount, status) VALUES ('$bg', '$receivername', '$hospitalname', '$amount', '$status')";

                if(mysqli_query($conn, $query)){
                    if($_POST['request']=='Request'){
                        header('Location: receiverdashboard.php');
                    }else{
                        header('Location: availablesamples.php');
                    }
                }else{
                    echo "Error occured". mysqli_error($conn);
                }
            }else{
                $_SESSION['message']="Incompatible";
                header('Location: availablesamples.php');
            }
        }elseif($_SESSION['as']=='hospital'){
            $_SESSION['message']="Ineligible";
            header('Location: availablesamples.php');
        }else{
            header('Location: login.php');
        }
    }

?>