<?php include 'dbconnection/database.php'?>
<?php session_start();?>
<?php

    if(isset($_SESSION['bloodgroup'])){
        $bloodgroup = $_SESSION['bloodgroup'];
    }

    //Associative array of compatible blood groups for the receiver
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

    $bloodgroups = $compatiblearr[$bloodgroup];
    $bloodgroups = implode(',',$bloodgroups);

    $query = "SELECT * FROM bloodbank WHERE bloodgroup IN($bloodgroups) AND amount>0";
    $result = mysqli_query($conn, $query);
    $bloodbankinfo = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $query = "SELECT DISTINCT username FROM bloodbank";
    $result = mysqli_query($conn, $query);
    $hospitals = [];
    while ($row = $result->fetch_row()) {
        $hospitals[] = $row[0];
    }

    $hospitalsinfo = [];
    foreach($hospitals as $hospital){
        $query = "SELECT * FROM hospital WHERE username='$hospital'";
        $result = mysqli_query($conn, $query);
        while($row = $result->fetch_row()){
            $hospitalsinfo[$hospital]  = $row;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <title>Document</title>
</head>
<body>
    <nav class="border-bottom" style="background-color:#ebedec">
        <div class="container pt-4 pb-3 d-flex flex-sm-row flex-column justify-content-between align-items-sm-center px-0">
            <a href="index.php" class="navbar-brand d-flex align-items-center ps-2 ps-sm-0"><i class="bi bi-droplet-fill text-danger fs-2"></i><span class="fs-3 ms-2">Life Blood</span></a>
            <div class="d-flex align-items-center justify-content-start ps-3 ps-sm-0 mt-sm-0 mt-3">
                <a href="availablesamples.php" class="nav-link d-inline me-5">Available Blood Samples</a>
                <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person"></i> Guest
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="login.php">Login</a></li>
                    <li><a role="button" data-bs-toggle="modal" data-bs-target="#registerModal" class="btn">Register</a></li>
                </ul>
                <!--Register Modal-->
                <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex flex-column align-items-center py-5">
                            <a href="receiverregister.php" role="button" class="btn btn-outline-dark w-50">Register as Receiver</a>
                            <a href="hospitalregister.php" role="button" class="btn btn-outline-primary mt-3 w-50">Register as Hospital</a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </nav>

    <main class="container d-flex flex-wrap pt-5 justify-content-center">
        <?php foreach($bloodbankinfo as $item):?>
            <?php 
                $hos = $item['username']; 
                $hospitalname = $hos;
                $bloodgroup = $item['bloodgroup'];
                $len = strlen($bloodgroup)-1;
                $bg = substr($bloodgroup, 0, $len);
                $last = $bloodgroup[-1];
                $c = $last=='+'?'plus':'minus';
                $bg = $bg.$c;
                $hos = $hos.$bg;

                $query = "SELECT amount FROM bloodbank WHERE bloodgroup='$bloodgroup' AND username='$hospitalname'";
                $result = mysqli_query($conn, $query);
                $available = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $available = $available[0]['amount'];
            ?>
            <div class="card m-3" style="width:330px;">
                <div class="card-header fs-5 d-flex align-items-center my-1"><p class="rounded-pill border border-danger-subtle text-danger text-center m-0" style="width:50px;"><?php echo $item['bloodgroup']?><p></div>
                <div class="card-body">
                    <h5 class="card-title fw-normal pt-1"><i class="bi bi-hospital me-1"></i> <?php echo $hospitalsinfo[$item['username']][2]?></h5>
                    <p class="card-text fs-6 py-3"><i class="bi bi-geo-alt-fill me-1"></i> <?php echo $hospitalsinfo[$item['username']][3]?></p>
                    <a href="login.php" class="btn btn-outline-primary me-2" style="width:90px;">Request</a>
                    <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#<?php echo $hos?>Modal" style="width:90px;">Info</button>
                </div>
            </div>

            <!-- InfoModal -->
            <div class="modal fade" id="<?php echo $hos?>Modal" tabindex="-1" aria-labelledby="<?php echo $hos?>ModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="<?php echo $hos?>ModalLabel"><?php echo $hospitalsinfo[$item['username']][2]?></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><i class="bi bi-droplet-fill me-1"></i> <?php echo $bloodgroup." (".$available." units)" ?></p>
                    <p><i class="bi bi-geo-alt-fill me-1"></i> <?php echo $hospitalsinfo[$item['username']][3]?></p>
                    <p><i class="bi bi-envelope me-1"></i> <?php echo $hospitalsinfo[$item['username']][4]?></p>
                    <p><i class="bi bi-telephone me-1"></i> <?php echo $hospitalsinfo[$item['username']][5]?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
            </div>
        <?php endforeach;?>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>